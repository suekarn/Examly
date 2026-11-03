<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once __DIR__ . '/Ai_converter.php';

/**
 * Simpan di: application/libraries/Ai_converter_pdf.php
 *
 * Importer KHUSUS PDF / foto soal (terpisah dari docx).
 * Pewarisan dari Ai_converter hanya untuk memakai ulang mesin pemanggil AI (provider, fallback, kuota 429),
 * pembersih hasil (bersihkan) dan penentu kunci.
 */
class Ai_converter_pdf extends Ai_converter
{
    public $halaman = 0;          // jumlah halaman PDF terakhir yang dibaca (0 = tidak diketahui)
    public $per_halaman = 3;      // jumlah halaman per tahap
    public $ratakan = true;       // PDF dirender jadi gambar (PDF gambar-saja) sebelum dikirim ke AI; false = kirim PDF asli
    public $media_resolution = 'MEDIA_RESOLUTION_HIGH';   // baca gambar halaman dengan rincian tinggi (pecahan bertumpuk kecil)
    public $baca_ulang = true;    // soal yang opsinya bertanda [CEK] dibaca ulang satu kali (fokus ke opsi saja)
    public $dpi_ratakan = 170;    // resolusi render (turun otomatis ke 130 bila berkas terlalu besar)
    public $poppler_dir = 'C:/poppler/Library/bin/';     // folder poppler bila belum ada di PATH, mis. 'C:/poppler/Library/bin/' (Windows/XAMPP)
    public $ratakan_alasan = '';  // alasan perataan gagal (untuk log)
    protected $rentang = null;    // [dari, sampai, total] saat menyusun prompt satu tahap
    protected $pdf_path = '';     // lokasi berkas PDF yang sedang diproses (untuk membaca judul bagian)
    protected $peta = null;       // cache peta judul bagian: [[halaman, judul], ...]
    protected $inventaris = null; // cache gambar tersemat per halaman: [halaman => ['WxH px', ...]]
    protected $rekon = null;      // cache teks rekonstruksi per halaman: [halaman => teks]

    /**
     * Memanggil method milik Ai_converter walau bersifat private (parent::nama() gagal pada method private).
     * Dipakai untuk blok_gambar_berkas, schema_gemini, dan lengkapi_kunci.
     */
    protected function induk($nama, ...$arg)
    {
        $m = new ReflectionMethod('Ai_converter', $nama);
        $m->setAccessible(true);
        return $m->invokeArgs($this, $arg);
    }

    /* ---------------- 1. BACA BERKAS ---------------- */

    /** Hanya PDF / foto. Hasil selalu ['binary' => [mime, base64]] (dibaca Gemini langsung). */
    public function baca_file($path, $ext)
    {
        $ext = strtolower($ext);
        $mime = ['pdf' => 'application/pdf', 'png' => 'image/png', 'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'webp' => 'image/webp'];
        if (!isset($mime[$ext])) {
            $this->log[] = "Importer PDF tidak mendukung .$ext (pakai Ai_converter untuk docx/txt).";
            return ['text' => ''];
        }
        $data = file_get_contents($path);
        $this->pdf_path = ($ext === 'pdf') ? $path : '';
        $this->peta = null;
        $this->inventaris = null;
        $this->rekon = null;
        $this->halaman = ($ext === 'pdf') ? $this->hitung_halaman($data, $path) : 0;
        $this->log[] = $ext === 'pdf'
            ? ($this->halaman ? "PDF terbaca: {$this->halaman} halaman" : 'PDF terbaca: jumlah halaman tidak diketahui, dibaca sekaligus')
            : 'Foto soal terbaca';
        $kirim = $data;
        if ($ext === 'pdf' && $this->ratakan) {
            $rata = $this->ratakan_pdf($path);
            if ($rata !== null) {
                $kirim = $rata;
                $this->log[] = 'PDF diratakan menjadi gambar halaman (teks mentah PDF yang kacau tidak dipakai AI)';
            } else {
                $this->log[] = 'PERATAAN PDF GAGAL, memakai PDF asli (hasil bisa kurang akurat). Alasan: ' . ($this->ratakan_alasan ?: 'tidak diketahui') . '. Pasang poppler dan isi $poppler_dir.';
            }
        }
        return ['binary' => [$mime[$ext], base64_encode($kirim)]];
    }

    protected function ratakan_pdf($path)
    {
        $nonaktif = array_map('trim', explode(',', (string)ini_get('disable_functions')));
        $this->ratakan_alasan = '';
        if (!is_file($path) || !function_exists('shell_exec') || in_array('shell_exec', $nonaktif, true)) { $this->ratakan_alasan = 'shell_exec dimatikan di php.ini (disable_functions)'; return null; }
        $dir = rtrim(sys_get_temp_dir(), '/\\') . '/ratakan_' . uniqid('', true);
        if (!@mkdir($dir, 0700, true)) return null;
        $hasil = null;
        foreach (array_unique([(int)$this->dpi_ratakan, 130]) as $dpi) {
            foreach ((array)glob($dir . '/h*.jpg') as $lama) @unlink($lama);
            $out = (string)@shell_exec($this->bin('pdftoppm') . ' -jpeg -jpegopt quality=85 -r ' . (int)$dpi . ' ' . escapeshellarg($path) . ' ' . escapeshellarg($dir . '/h') . ' 2>&1');
            $file = (array)glob($dir . '/h*.jpg');
            sort($file);
            if (!$file) { $this->ratakan_alasan = trim(substr(preg_replace('/\\s+/', ' ', $out), 0, 160)) ?: 'pdftoppm tidak menghasilkan gambar'; break; }
            $pdf = $this->susun_pdf_gambar($file, $dpi);
            if ($pdf !== null && strlen($pdf) <= 12 * 1024 * 1024) { $hasil = $pdf; break; }
        }
        foreach ((array)glob($dir . '/*') as $f) @unlink($f);
        @rmdir($dir);
        return $hasil;
    }

    protected function susun_pdf_gambar(array $files, $dpi)
    {
        $n = count($files);
        if ($n < 1) return null;
        $pdf = "%PDF-1.4\n";
        $off = [];
        $tulis = function ($no, $isi) use (&$pdf, &$off) {
            $off[$no] = strlen($pdf);
            $pdf .= $no . " 0 obj\n" . $isi . "\nendobj\n";
        };
        $sf = function ($x) { return number_format((float)$x, 2, '.', ''); };
        $kids = [];
        $halaman = [];
        for ($i = 0; $i < $n; $i++) {
            $info = @getimagesize($files[$i]);
            if (!$info || $info[2] !== IMAGETYPE_JPEG) return null;
            $w = (int)$info[0]; $h = (int)$info[1];
            $ch = isset($info['channels']) ? (int)$info['channels'] : 3;
            $cs = ($ch === 1) ? '/DeviceGray' : (($ch === 4) ? '/DeviceCMYK' : '/DeviceRGB');
            $data = file_get_contents($files[$i]);
            if ($data === false || $data === '') return null;
            $pw = $sf($w * 72 / $dpi); $ph = $sf($h * 72 / $dpi);
            $pg = 3 + 3 * $i; $ct = $pg + 1; $im = $pg + 2;
            $kids[] = $pg . ' 0 R';
            $konten = 'q ' . $pw . ' 0 0 ' . $ph . ' 0 0 cm /Im0 Do Q';
            $halaman[$pg] = '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 ' . $pw . ' ' . $ph . '] /Resources << /XObject << /Im0 ' . $im . ' 0 R >> >> /Contents ' . $ct . ' 0 R >>';
            $halaman[$ct] = '<< /Length ' . strlen($konten) . " >>\nstream\n" . $konten . "\nendstream";
            $halaman[$im] = '<< /Type /XObject /Subtype /Image /Width ' . $w . ' /Height ' . $h . ' /ColorSpace ' . $cs
                . ' /BitsPerComponent 8 /Filter /DCTDecode /Length ' . strlen($data) . " >>\nstream\n" . $data . "\nendstream";
        }
        $tulis(1, '<< /Type /Catalog /Pages 2 0 R >>');
        $tulis(2, '<< /Type /Pages /Kids [' . implode(' ', $kids) . '] /Count ' . $n . ' >>');
        for ($no = 3; $no <= 2 + 3 * $n; $no++) $tulis($no, $halaman[$no]);
        $total = 3 + 3 * $n;
        $xref = strlen($pdf);
        $pdf .= "xref\n0 " . $total . "\n0000000000 65535 f \n";
        for ($no = 1; $no < $total; $no++) $pdf .= sprintf("%010d 00000 n \n", $off[$no]);
        $pdf .= "trailer\n<< /Size " . $total . " /Root 1 0 R >>\nstartxref\n" . $xref . "\n%%EOF";
        return $pdf;
    }

    protected function bin($nama)
    {
        $dir = trim((string)$this->poppler_dir);
        if ($dir !== '') $nama = rtrim($dir, '/\\') . DIRECTORY_SEPARATOR . $nama;
        return escapeshellarg($nama);
    }

    protected function null_dev()
    {
        return (DIRECTORY_SEPARATOR === '\\') ? '2>NUL' : '2>/dev/null';
    }

    public function hitung_halaman($data, $path = '')
    {
        $nonaktif = array_map('trim', explode(',', (string)ini_get('disable_functions')));
        if ($path !== '' && is_file($path) && function_exists('shell_exec') && !in_array('shell_exec', $nonaktif, true)) {
            $o = @shell_exec($this->bin('pdfinfo') . ' ' . escapeshellarg($path) . ' ' . $this->null_dev());
            if (preg_match('/^Pages:\s+(\d+)/m', (string)$o, $m) && (int)$m[1] > 0) return (int)$m[1];
        }
        $a = (int)preg_match_all('~/Type\s*/Page(?![a-zA-Z])~', $data);
        $b = 0;
        foreach (explode('endobj', $data) as $obj) {
            if (preg_match('~/Type\s*/Pages(?![a-zA-Z])~', $obj) && preg_match_all('~/Count\s+(\d+)~', $obj, $m)) $b = max($b, max($m[1]));
        }
        return max($a, $b);
    }

    /* ---------------- 1b. JUDUL BAGIAN ---------------- */

    const POLA_BAGIAN = '/^\s*(pilihan\s+ganda(?:\s+(?:tunggal|kompleks))?|menjodohkan|isian(?:\s+singkat)?|uraian|essai|esai|benar\s*\/?\s*salah)\s*$/iu';

    public function peta_bagian()
    {
        if ($this->peta !== null) return $this->peta;
        $this->peta = [];
        $nonaktif = array_map('trim', explode(',', (string)ini_get('disable_functions')));
        if ($this->pdf_path === '' || !is_file($this->pdf_path) || !$this->halaman
            || !function_exists('shell_exec') || in_array('shell_exec', $nonaktif, true)) return $this->peta;
        $o = @shell_exec($this->bin('pdftotext') . ' -layout ' . escapeshellarg($this->pdf_path) . ' - ' . $this->null_dev());
        if (!is_string($o) || $o === '') return $this->peta;
        foreach (explode("\f", $o) as $i => $teks) {
            foreach (preg_split('/\R/u', $teks) as $baris) {
                if (preg_match(self::POLA_BAGIAN, $baris, $m)) { $this->peta[] = [$i + 1, trim(preg_replace('/\s+/', ' ', $m[1]))]; break; }
            }
        }
        return $this->peta;
    }

    protected function schema_gemini()
    {
        $sk = $this->induk('schema_gemini');
        $sk['properties']['soal']['items']['properties']['bagian'] = ['type' => 'STRING'];
        return $sk;
    }

    public function terapkan_bagian(array $list)
    {
        $ubah = 0;
        foreach ($list as $i => $s) {
            if (!is_array($s)) continue;
            if ((int)($s['tipe'] ?? 0) === 2 && $this->opsi_kombinasi($s) && !$this->ada_judul_kompleks()
                && !preg_match('/lebih dari satu|beberapa jawaban|pilih\s+(dua|tiga)|semua jawaban|centang/iu', (string)($s['pertanyaan'] ?? ''))) {
                $list[$i]['tipe'] = 1;
                if (isset($list[$i]['kunci']) && is_array($list[$i]['kunci']) && count($list[$i]['kunci']) > 1) $list[$i]['kunci'] = array_slice($list[$i]['kunci'], 0, 1);
                $ubah++;
                continue;
            }
            if (empty($s['bagian'])) continue;
            $b = mb_strtolower((string)$s['bagian'], 'UTF-8');
            $t = (int)($s['tipe'] ?? 0); $baru = $t;
            if (strpos($b, 'kompleks') !== false)                       { if ($t === 1) $baru = 2; }
            elseif (strpos($b, 'tunggal') !== false)                    { if ($t === 2) $baru = 1; }
            elseif (strpos($b, 'isian') !== false)                      { if (in_array($t, [1, 2, 5], true)) $baru = 4; }
            elseif (preg_match('/uraian|essai|esai/u', $b))             { if (in_array($t, [1, 2, 4], true)) $baru = 5; }
            if ($baru !== $t) { $list[$i]['tipe'] = $baru; $ubah++; }
        }
        if ($ubah) $this->log[] = "Jenis soal dikoreksi menurut judul bagian: $ubah soal";
        return $list;
    }

    protected function opsi_kombinasi(array $s)
    {
        $opsi = (array)($s['opsi'] ?? []);
        if (count($opsi) < 2) return false;
        foreach ($opsi as $o) {
            if (!is_string($o) || !preg_match('/^\s*\d+\)(?:\s*,?\s*(?:dan\s+)?\d+\))*\s*$/iu', $o)) return false;
        }
        return true;
    }

    protected function ada_judul_kompleks()
    {
        foreach ((array)$this->peta_bagian() as $x) if (stripos($x[1], 'kompleks') !== false) return true;
        return false;
    }

    protected function periksa_kewajaran(array $soal)
    {
        $tebak = 0;
        $perluCek = 0;
        foreach ($soal as $i => $s) {
            $nomor = $i + 1;
            if (!is_array($s)) {
                $this->log[] = "PERLU CEK soal hasil ke-$nomor: struktur data bukan objek soal.";
                $perluCek++;
                continue;
            }
            $tipe = (int)($s['tipe'] ?? 0);
            $pertanyaan = trim((string)($s['pertanyaan'] ?? ''));
            if ($pertanyaan === '') {
                $this->log[] = "PERLU CEK soal hasil ke-$nomor: teks pertanyaan kosong.";
                $perluCek++;
            }
            if (!empty($s['kunci_ai'])) $tebak++;
            if (in_array($tipe, [1, 2], true)) {
                $opsi = (array)($s['opsi'] ?? []);
                if (count($opsi) < 2) {
                    $this->log[] = "PERLU CEK soal hasil ke-$nomor: hanya " . count($opsi) . " opsi terbaca.";
                    $perluCek++;
                }
                $lihat = [];
                foreach ($opsi as $o) {
                    if (!is_string($o)) continue;
                    $k = mb_strtolower(preg_replace('/\s+/u', '', $o), 'UTF-8');
                    if ($k === '' || strpos($k, '[gambar:') !== false) continue;
                    if (isset($lihat[$k])) {
                        $this->log[] = 'PERLU CEK soal hasil ke-' . $nomor . ': opsi kembar ("' . $o . '"), mungkin salah baca.';
                        $perluCek++;
                        break;
                    }
                    $lihat[$k] = 1;
                }
                $kunci = (array)($s['kunci'] ?? []);
                foreach ($kunci as $huruf) {
                    if (!is_string($huruf) || !preg_match('/^[A-Z]$/i', $huruf)
                        || (ord(strtoupper($huruf)) - 65) >= count($opsi)) {
                        $this->log[] = "PERLU CEK soal hasil ke-$nomor: kunci tidak cocok dengan jumlah opsi.";
                        $perluCek++;
                        break;
                    }
                }
                if ($tipe === 1 && count($kunci) > 1) {
                    $this->log[] = "PERLU CEK soal hasil ke-$nomor: pilihan ganda tunggal memiliki lebih dari satu kunci.";
                    $perluCek++;
                }
            }
            if ($tipe === 3) {
                $baris = (array)($s['baris'] ?? []);
                $kolom = (array)($s['kolom'] ?? []);
                if (empty($baris) || empty($kolom)) {
                    $this->log[] = "PERLU CEK soal hasil ke-$nomor: soal tipe 3 (menjodohkan/tabel) tidak memiliki baris atau kolom yang valid.";
                    $perluCek++;
                }
            }
            if (strpos($pertanyaan, '[PERLU CEK SUMBER]') !== false || strpos($pertanyaan, '[CEK]') !== false) {
                $this->log[] = "PERLU CEK soal hasil ke-$nomor: ada teks/rumus yang tidak terbaca jelas.";
                $perluCek++;
            }
        }
        if ($tebak) $this->log[] = "PERHATIAN: kunci ditentukan AI untuk $tebak soal (PDF tanpa kunci); periksa kembali sebelum dipakai.";
        if ($perluCek) $this->log[] = "PEMERIKSAAN OTOMATIS: ditemukan $perluCek indikasi yang perlu diperiksa manual; hasil belum tentu siap dipakai.";
    }

    protected function inventaris_gambar()
    {
        if ($this->inventaris !== null) return $this->inventaris;
        $this->inventaris = [];
        $nonaktif = array_map('trim', explode(',', (string)ini_get('disable_functions')));
        if ($this->pdf_path === '' || !is_file($this->pdf_path) || !function_exists('shell_exec') || in_array('shell_exec', $nonaktif, true)) return $this->inventaris;
        $o = @shell_exec($this->bin('pdfimages') . ' -list ' . escapeshellarg($this->pdf_path) . ' ' . $this->null_dev());
        if (!is_string($o) || $o === '') return $this->inventaris;
        foreach (preg_split('/\R/', $o) as $baris) {
            $c = preg_split('/\s+/', trim($baris));
            if (count($c) < 5 || !ctype_digit($c[0]) || !ctype_digit($c[1]) || $c[2] !== 'image') continue;
            $w = (int)$c[3]; $h = (int)$c[4];
            if ($w < 60 || $h < 60 || $w * $h > 1500000) continue;
            $this->inventaris[(int)$c[0]][] = $w . 'x' . $h . ' px';
        }
        ksort($this->inventaris);
        return $this->inventaris;
    }

    protected function blok_inventaris_gambar()
    {
        $inv = $this->inventaris_gambar();
        if (!$inv) return '';
        $t = "GAMBAR TERSEMAT DI PDF (hasil pemindaian struktur berkas, TERPERCAYA; halaman pertama = 1):\n";
        $total = 0;
        foreach ($inv as $h => $daftar) { $t .= "- halaman $h: " . count($daftar) . ' gambar (' . implode(', ', $daftar) . ")\n"; $total += count($daftar); }
        $t .= "Total $total gambar tersemat. Aturan:\n"
            . "- Setiap gambar pada daftar ini HARUS punya satu [GAMBAR: ...] pada soal tempat gambar itu tercetak. Jangan ada yang terlewat dan jangan ada yang diberi dua penanda.\n"
            . "- Bentuk yang digambar langsung (vektor) tidak masuk daftar ini; bila tampak sebagai gambar soal, tetap beri [GAMBAR: ...].\n"
            . "- Ukuran membantu mengenali: beberapa gambar BERUKURAN SAMA yang berdekatan biasanya opsi bergambar A, B, C, D (satu penanda per opsi, urut huruf). Gambar yang jauh lebih lebar daripada tingginya biasanya ilustrasi/foto lebar.\n"
            . "- Deskripsikan tiap gambar HANYA dari yang tampak pada gambar itu, bukan dari tebakan isi soal.\n";
        return $t;
    }

    protected function rekonstruksi_teks()
    {
        if ($this->rekon !== null) return $this->rekon;
        $this->rekon = [];
        $nonaktif = array_map('trim', explode(',', (string)ini_get('disable_functions')));
        if ($this->pdf_path === '' || !is_file($this->pdf_path) || !function_exists('shell_exec') || in_array('shell_exec', $nonaktif, true)) return $this->rekon;
        $x = @shell_exec($this->bin('pdftotext') . ' -bbox ' . escapeshellarg($this->pdf_path) . ' - ' . $this->null_dev());
        if (!is_string($x) || $x === '' || !preg_match_all('~<page[^>]*>(.*?)</page>~s', $x, $hal)) return $this->rekon;
        foreach ($hal[1] as $i => $isi) {
            $ws = [];
            if (preg_match_all('~<word xMin="(-?[\d.]+)" yMin="(-?[\d.]+)" xMax="(-?[\d.]+)" yMax="(-?[\d.]+)">(.*?)</word>~s', $isi, $m, PREG_SET_ORDER)) {
                foreach ($m as $k) {
                    $ws[] = ['x0' => (float)$k[1], 'y0' => (float)$k[2], 'x1' => (float)$k[3], 'y1' => (float)$k[4],
                             't' => html_entity_decode($k[5], ENT_QUOTES | ENT_HTML5, 'UTF-8')];
                }
            }
            $baris = $this->rekon_halaman($ws);
            if ($baris) $this->rekon[$i + 1] = implode("\n", $baris);
        }
        return $this->rekon;
    }

    protected function rekon_halaman(array $ws)
    {
        if (count($ws) < 2) return [];
        $hs = [];
        foreach ($ws as $k => $w) { $ws[$k]['h'] = $w['y1'] - $w['y0']; $hs[] = $ws[$k]['h']; }
        sort($hs);
        $med = $hs[(int)floor(count($hs) / 2)];
        if ($med <= 0) return [];
        $kecil = 0.75 * $med;
        $pola = '/^[\p{L}\p{N},.■●]+$/u';
        $urut = array_keys($ws);
        usort($urut, function ($a, $b) use ($ws) { return $ws[$a]['y0'] <=> $ws[$b]['y0']; });

        $pakai = []; $pecahan = [];
        foreach ($urut as $i) {
            if (isset($pakai[$i])) continue;
            $a = $ws[$i];
            if ($a['h'] >= $kecil || !preg_match($pola, $a['t'])) continue;
            $best = null; $bj = null;
            foreach ($ws as $j => $b) {
                if ($j === $i || isset($pakai[$j]) || $b['y0'] <= $a['y0'] || $b['h'] >= $kecil || !preg_match($pola, $b['t'])) continue;
                $hm = max($a['h'], $b['h']);
                $gap = $b['y0'] - $a['y1'];
                if ($gap > 0.45 * $hm || $gap < -2) continue;
                if (abs($a['h'] - $b['h']) > 0.2 * $hm) continue;
                $ov = min($a['x1'], $b['x1']) - max($a['x0'], $b['x0']);
                if ($ov < 0.5 * min($a['x1'] - $a['x0'], $b['x1'] - $b['x0'])) continue;
                if ($best === null || $gap < $best) { $best = $gap; $bj = $j; }
            }
            if ($bj === null) continue;
            $b = $ws[$bj];
            $pakai[$i] = true; $pakai[$bj] = true;
            $pecahan[] = ['x0' => min($a['x0'], $b['x0']), 'x1' => max($a['x1'], $b['x1']), 'y0' => $a['y0'], 'y1' => $b['y1'],
                          't' => '{' . $a['t'] . '/' . $b['t'] . '}'];
        }

        $mati = [];
        foreach ($pecahan as $fi => $f) {
            $cy = ($f['y0'] + $f['y1']) / 2;
            foreach ($ws as $j => $w) {
                if (isset($pakai[$j]) || isset($mati[$j]) || !preg_match('/^\d+$/', $w['t'])) continue;
                if (abs($w['x1'] - $f['x0']) <= 2.0 && $w['y0'] < $cy && $cy < $w['y1']) {
                    $pecahan[$fi]['t'] = $w['t'] . $f['t'];
                    $pecahan[$fi]['x0'] = $w['x0'];
                    $mati[$j] = true;
                    break;
                }
            }
        }

        $item = [];
        foreach ($ws as $j => $w) {
            if (isset($pakai[$j]) || isset($mati[$j])) continue;
            $item[] = ['x0' => $w['x0'], 'x1' => $w['x1'], 'cy' => ($w['y0'] + $w['y1']) / 2, 't' => $w['t']];
        }
        foreach ($pecahan as $f) $item[] = ['x0' => $f['x0'], 'x1' => $f['x1'], 'cy' => ($f['y0'] + $f['y1']) / 2, 't' => $f['t']];
        usort($item, function ($a, $b) { return $a['cy'] <=> $b['cy']; });
        $baris = [];
        foreach ($item as $w) {
            $ketemu = false;
            foreach ($baris as $bi => $L) {
                if (abs($L['cy'] - $w['cy']) <= 0.6 * $med) {
                    $baris[$bi]['w'][] = $w;
                    $n = count($baris[$bi]['w']);
                    $baris[$bi]['cy'] = ($L['cy'] * ($n - 1) + $w['cy']) / $n;
                    $ketemu = true;
                    break;
                }
            }
            if (!$ketemu) $baris[] = ['cy' => $w['cy'], 'w' => [$w]];
        }
        usort($baris, function ($a, $b) { return $a['cy'] <=> $b['cy']; });
        $out = [];
        foreach ($baris as $L) {
            $kata = $L['w'];
            usort($kata, function ($a, $b) { return $a['x0'] <=> $b['x0']; });
            $r = ''; $px = null;
            foreach ($kata as $k) {
                if ($px !== null) $r .= ($k['x0'] - $px > 30) ? '   ' : ' ';
                $r .= $k['t'];
                $px = $k['x1'];
            }
            $out[] = $r;
        }
        return $out;
    }

    protected function blok_rekonstruksi()
    {
        $per = $this->rekonstruksi_teks();
        if (!$per) return '';
        $a = $this->rentang ? $this->rentang[0] : 1;
        $b = $this->rentang ? $this->rentang[1] + 1 : PHP_INT_MAX;
        $isi = '';
        foreach ($per as $h => $teks) {
            if ($h < $a || $h > $b) continue;
            $isi .= '--- halaman ' . $h . "\n" . $teks . "\n";
        }
        if ($isi === '') return '';
        return "TEKS PATOKAN HASIL REKONSTRUKSI POSISI HURUF PDF (disusun mesin, TERPERCAYA untuk pecahan kecil dan susunan kolom/tabel):\n"
            . '- Pecahan ditulis {pembilang/penyebut}. Bilangan bulat yang menempel di kirinya (mis. 2{2/5}) adalah bilangan campuran. Tulis ke JSON sebagai $2\frac{2}{5}$ atau $\frac{a}{b}$.' . "\n"
            . "- Pakai teks ini sebagai patokan ANGKA pecahan dan susunan baris/kolom tabel.\n"
            . "- Teks ini belum bersih: baris dan kolom bisa tercampur, label opsi bisa janggal, dan pecahan BESAR tampil terpisah atas-bawah. Bagian yang ditulis terpotong memang rusak di sumber: jangan dilengkapi dengan tebakan.\n"
            . "- Nomor soal, pembagian opsi, dan gambar tetap ditentukan dari halaman, bukan dari teks ini.\n"
            . $isi;
    }

    /* ---------------- 2. PROMPT KHUSUS PDF ---------------- */

    protected function prompt($tpl, $tebak = false, $sumber = 'berkas')
    {
        $kunci_aturan = $tebak
            ? "- Jika kunci jawaban TIDAK ada di dokumen, TENTUKAN sendiri jawaban yang paling benar, lalu set \"kunci_ai\": true. Jika kunci ada di dokumen, \"kunci_ai\": false.\n"
                . "- CARA MENENTUKAN KUNCI: untuk soal hitungan, hitung SENDIRI langkah demi langkah dari angka yang sudah kamu baca (pecahan, tabel, serta angka pada gambar), lalu cocokkan dengan SETIAP opsi; kunci adalah opsi yang hasilnya sama persis. Jika hasil tidak cocok dengan opsi mana pun, baca ulang soalnya. Jika tetap tidak cocok, atau soal/opsi terpotong atau rusak, kosongkan kunci dan set kunci_ai false. Jangan memilih opsi hanya karena paling mendekati. Soal yang butuh gambar/grafik yang tidak ada di dokumen: kosongkan kunci.\n"
            : "- Jika kunci jawaban tidak ditemukan di dokumen, kosongkan (kunci: [], jawaban: \"\"). JANGAN menebak.\n";
        $rentang = '';
        if ($this->rentang) {
            list($a, $b, $total) = $this->rentang;
            $rentang = "\nRENTANG PROSES (PENTING):\n"
                . "- Dokumen ini berisi $total halaman. Kerjakan HANYA soal yang NOMORNYA DIMULAI pada halaman $a sampai $b.\n"
                . "- Soal yang mulai di halaman $b dan berlanjut ke halaman berikutnya: baca sampai selesai (termasuk opsinya) dan masukkan.\n"
                . "- Soal yang dimulai SEBELUM halaman $a, atau SESUDAH halaman $b, JANGAN dimasukkan (diproses tahap lain).\n"
                . "- Bacaan / informasi / tabel bersama / gambar bersama yang letaknya di luar rentang tetapi dipakai soal dalam rentang: tetap salin ke 'pertanyaan' soal tersebut.\n"
                . "- Lembar kunci jawaban yang ada di halaman lain dokumen boleh dan harus dipakai untuk soal pada rentang ini (cocokkan menurut nomor soal).\n"
                . "- Jika tidak ada soal yang dimulai pada rentang ini, kembalikan {\"soal\":[]}.\n";
        }
        $peta = '';
        if ($this->peta_bagian()) {
            $peta = "\nJUDUL BAGIAN TERDETEKSI (nomor halaman = urutan halaman berkas, halaman pertama = 1):\n";
            foreach ($this->peta_bagian() as $x) $peta .= "- halaman {$x[0]}: {$x[1]}\n";
            $peta .= "Soal setelah sebuah judul (sampai judul berikutnya) termasuk bagian itu. Nomor soal di tiap bagian biasanya mulai lagi dari 1.\n";
        }
        return "Kamu adalah mesin konversi bank soal ujian dari berkas PDF / foto menjadi JSON. Ubah SEMUA soal pada rentang yang diminta.\n"
             . "Tentukan 'tipe' tiap soal:\n"
             . "  1 = pilihan ganda (satu jawaban benar)\n"
             . "  2 = pilihan ganda kompleks (pilih lebih dari satu jawaban / benar-salah per pernyataan dengan centang)\n"
             . "  3 = menjodohkan (cocokkan baris dengan kolom, atau tabel benar/salah)\n"
             . "  4 = isian singkat\n"
             . "  5 = uraian / essai\n"
             . "Isi field menurut tipe:\n"
             . "  tipe 1: pertanyaan, opsi (urut A,B,C,...), kunci (array berisi SATU huruf)\n"
             . "  tipe 2: pertanyaan, opsi, kunci (array berisi satu atau lebih huruf)\n"
             . "  tipe 3: pertanyaan, baris (array teks), kolom (array teks), pasangan (array {baris:nomor baris mulai 1, kolom:[huruf kolom yang cocok]})\n"
             . "  tipe 4: pertanyaan, jawaban (jawaban singkat)\n"
             . "  tipe 5: pertanyaan, jawaban (pedoman jawaban; kosongkan jika tidak ada)\n"
             . "Aturan ketat:\n"
             . "- Salin isi soal dan opsi sesuai sumber. Jangan mengubah kata yang benar-benar tercetak. HAPUS artefak OCR/pemformatan seperti kata literal text atau \\text yang tidak tampak tercetak pada halaman.\n"
             . "- Jangan menambah soal yang tidak ada dan jangan melewatkan satu pun. Satu soal = satu objek.\n"
             . "- JENIS SOAL DITENTUKAN OLEH JUDUL BAGIAN terdekat di atas soal, walau judul itu ada di halaman lain / di luar rentang: bagian 'Pilihan Ganda' atau 'Pilihan Ganda Tunggal' = tipe 1; bagian 'Pilihan Ganda Kompleks' = SEMUA soal berpilihan a-d bertipe 2 (jangan dijadikan tipe 1 walau tampak satu jawaban), sedangkan soal bertabel Benar/Salah atau Tepat/Tidak Tepat bertipe 3; bagian 'Isian' = tipe 4; bagian 'Uraian'/'Essai' = tipe 5. Isi field 'bagian' dengan judul bagian itu (mis. \"Pilihan Ganda Kompleks\").\n"
             . "- PEMBACAAN TABEL DATA/INFORMASI DALAM SOAL: Jika pada soal/bacaan terdapat tabel data (misal tabel provinsi & banyak penduduk), WAJIB salin teks pengantar beserta tabelnya ke 'pertanyaan' menggunakan penanda [TABEL] Header1 | Header2 \\n Baris1_Kolom1 | Baris1_Kolom2 [/TABEL]. Salin LENGKAP ke field 'pertanyaan' pada SETIAP nomor soal yang merujuk tabel itu.\n"
             . "- Titik-titik isian (..., ...., …) adalah BAGIAN dari soal. Salin persis di posisinya dan jangan dihapus atau digabung.\n"
             . "- Pernyataan/kalimat perintah di atas tabel menjodohkan/benar-salah (mis. \"Pilih jawaban benar atau salah untuk setiap pernyataan berikut!\") ikut di 'pertanyaan' soal tabel itu, bukan soal tersendiri. Soal tabel (tipe 3): 'baris' = isi tiap baris/pernyataan tabel, 'kolom' = judul kolom pilihan (mis. Benar, Salah).\n"
             . "- Hapus nomor soal dari 'pertanyaan' dan label huruf (a., b., A.) dari tiap opsi.\n"
             . "- Abaikan judul bagian, identitas ujian, petunjuk pengerjaan, kop halaman, nomor halaman, catatan kaki, watermark, dan pembahasan.\n"
             . "- Pernyataan bernomor di dalam soal (1) 2) 3)) tulis di 'pertanyaan', satu per baris: \"1) ...\\n2) ...\\n3) ...\".\n"
             . "- Soal PG dengan opsi kombinasi (\"1) dan 2)\") adalah tipe 1 dengan satu kunci. Tipe 2 HANYA jika soal meminta memilih LEBIH DARI SATU jawaban.\n"
             . "- Teks bacaan / informasi / tabel bersama (\"Informasi berikut untuk menjawab soal nomor 24 dan 25...\") WAJIB disalin ke 'pertanyaan' SETIAP soal yang memakainya.\n"
             . "- Menjodohkan (tipe 3): satu tabel menjodohkan = SATU soal. 'baris' dan 'kolom' berisi teks saja tanpa nomor/huruf.\n"
             . $kunci_aturan
             . "- Kunci jawaban sering berada di lembar tersendiri (mis. tabel \"Kunci Jawaban\" di halaman terakhir). Cari di seluruh dokumen dan cocokkan menurut NOMOR soal.\n"
             . "- Bacalah dengan TELITI satu per satu. Urutan A, B, C, D mengikuti label yang tercetak.\n"
             . "- Pecahan, pangkat, akar, dan simbol dibaca sebagaimana TERLIHAT di halaman.\n"
             . "- PEMERIKSAAN WAJIB: cocokkan setiap nomor dengan sumber; cocokkan teks soal, semua opsi, dan tanda kunci satu per satu.\n"
             . "- Jika rumus, pecahan, angka, simbol, atau kalimat sulit dibaca, perbesar dan baca ulang lebih teliti. Bila masih ragu, tulis pembacaan terbaik lalu tambahkan [CEK] tepat di belakang bagian itu (jangan dikosongkan, jangan menulis [tidak terbaca]). Jangan mengarang bagian yang sama sekali tidak tercetak.\n"
             . $rentang . $peta
             . "\n" . $this->blok_rumus() . "\n"
             . "\n" . $this->blok_matematika_pdf() . "\n"
             . "\n" . $this->blok_gambar_berkas() . "\n" . $this->blok_inventaris_gambar() . "\n" . $this->blok_rekonstruksi() . "\n"
             . "FORMAT KELUARAN: jangan menyertakan penanda sitasi/sumber seperti[cite: 1] atau . Keluarkan HANYA JSON valid: {\"soal\":[{\"tipe\":1,\"bagian\":\"Pilihan Ganda Tunggal\",\"pertanyaan\":\"...\",\"opsi\":[\"...\"],\"kunci\":[\"A\"],\"kunci_ai\":false}]}\n\n"
             . "Catatan format sumber: " . (isset($tpl->prompt) ? $tpl->prompt : '');
    }

    protected function blok_gambar_berkas()
    {
        return $this->induk('blok_gambar_berkas') . "\n" . <<<'TXT'
ATURAN PEMBACAAN GAMBAR (PENTING, gambar sering salah dibaca):
1. Setiap ada gambar ilustrasi, foto, botol, buah, orang, papan tulis, atau grafik pada soal (misal: gambar botol minyak, keranjang mangga, anak berlari), WAJIB tuliskan penanda [GAMBAR: deskripsi singkat] pada 'pertanyaan'.
2. Angka/pecahan yang tercetak DI DALAM gambar (seperti 1/3 liter pada botol, 0,48 pada papan tulis) BUKAN teks pertanyaan. Tetap berikan penanda [GAMBAR: ...] dan tulis angka tersebut di dalam deskripsi gambar.
3. Jangan pernah melewatkan penanda [GAMBAR: ...] jika pada halaman PDF terdapat gambar visual untuk soal tersebut.
4. Angka hanya boleh ditulis jika benar-benar tercetak sebagai teks pada gambar (label sisi, berat, harga, skala, sumbu, judul tabel). DILARANG mengarang atau menebak angka.
5. Satu gambar fisik = satu [GAMBAR]. Opsi gambar: tepat satu per huruf A-D menurut urutan cetak.
6. BUKAN gambar: garis pecahan, simbol/rumus, kotak jawaban, garis titik isian, logo/kop, watermark, bingkai atau garis hias, nomor halaman, dan TABEL DATA TEKS.
7. Gambar bersama beberapa soal: tulis deskripsi yang sama persis di tiap soal itu.
8. PENEMPATAN: [GAMBAR] dipasang pada soal yang gambarnya tercetak di antara teks soal dan opsinya.
9. Soal yang jawabannya bergantung pada gambar: kunci hanya dari lembar kunci dokumen.
TXT;
    }

    protected function blok_matematika_pdf()
    {
        return <<<'TXT'
PEMBACAAN SOAL MATEMATIKA & PECAHAN PADA PDF (PENTING, sering salah baca):
1. Pecahan tercetak BERTUMPUK dan kecil: pembilang di atas garis, penyebut di bawah. Tulis $\frac{pembilang}{penyebut}$ tepat di posisinya.
2. Bilangan campuran = bilangan bulat berukuran normal dengan pecahan bertumpuk kecil di kanannya: tulis $1\frac{3}{5}$. BUKAN $\frac{13}{5}$.
3. Pecahan di tengah kalimat tetap di posisi katanya: "Pecahan $\frac{3}{5}$ dikalikan dengan 3".
4. Opsi pecahan: tiap opsi punya pembilang dan penyebut sendiri. Opsi 2 kolom: kiri-atas = A, kiri-bawah = B, kanan-atas = C, kanan-bawah = D.
5. Garis pecahan BUKAN gambar soal: jangan menulis [GAMBAR: ...] untuknya.
6. Simbol: "x" / "X" perkalian = \times; ":" pembagian = ":"; ■ ● ▲ adalah variabel soal, ditulis sebagai karakter Unicode itu sendiri (BUKAN \blacksquare, \bullet, \mathbb, \square).
7. Nomor soal pada sumber bisa salah atau ganda: patokan satu soal = SATU kelompok opsi a-d (atau satu perintah isian/uraian).
8. Informasi bersama ("Informasi berikut untuk menjawab soal nomor 24 dan 25...") beserta tabel/gambarnya WAJIB disalin ke SETIAP soal yang memakainya.
9. Gambar yang berisi angka atau label: tulis [GAMBAR: deskripsi] singkat.
10. Verifikasi hitungan: untuk soal hitungan, setelah pecahan dibaca, hitung hasilnya.
11. Angka yang diikuti titik pada posisi lanjutan kalimat adalah ISI soal, bukan nomor soal.
12. Simbol variabel soal salin sebagai karakter itu sendiri: ■ dan ●.
13. Satuan (m, cm, km, kg, liter, Rp) ditulis sebagai teks biasa DI LUAR tanda $...$, mis. $\frac{3}{4}$ km. JANGAN memakai \text{...}.
13b. Opsi pecahan kecil jangan dilewatkan: baca pembilang DAN penyebut tiap opsi. Bila ragu, hitung hasil soal lalu cocokkan dengan kunci / opsi lain, tulis pembacaan terbaik + [CEK]. Jangan menulis [tidak terbaca]. Simbol dan angka yang terbaca jelas (mis. < > = ≤ ≥, 70, 2/9) TIDAK boleh diberi [CEK]; [CEK] hanya untuk bagian yang benar-benar meragukan. JANGAN mengosongkan atau melewati opsi mana pun: tiap soal pilihan ganda harus punya opsi A, B, C, D berurutan; opsi yang tidak jelas tetap ditulis (pembacaan terbaik + [CEK], atau hanya [CEK]).
14. Pecahan di dalam kalimat tidak boleh hilang.
15. Satu soal PG bisa punya opsi yang hanya terdiri dari bilangan/pecahan tanpa kata.
16. Label yang tercetak DI DALAM gambar bukan teks soal.
17. Soal yang merujuk gambar/grafik/tabel padahal tidak ada di dokumen: tambahkan penanda [GAMBAR: (gambar/grafik tidak ada di dokumen sumber)].
18. TABEL DATA DALAM BACAAN/SOAL: Jangan gabungkan baris tabel menjadi teks menyatu. Formatlah setiap tabel data menggunakan penanda [TABEL] Header1 | Header2 \n Baris1 | Baris2 [/TABEL] agar dirender sebagai tabel fisik.
TXT;
    }

    /* ---------------- 3. PROSES UTAMA ---------------- */

    protected function provider_aktif()
    {
        return $this->CI->db->where('aktif', 1)->order_by('prioritas')->get('ai_provider')->result();
    }

    public function buat_rentang($total)
    {
        $per = max(1, (int)$this->per_halaman);
        if ($total <= 0 || $total <= $per + 1) return [null];
        $r = [];
        for ($a = 1; $a <= $total; $a += $per) $r[] = [$a, min($total, $a + $per - 1)];
        return $r;
    }

    public function proses($text, $binary, $tpl, $tebak = false)
    {
        if (!$binary) throw new Exception('Importer PDF hanya menerima berkas PDF atau foto. Untuk docx/txt pakai Ai_converter.');
        $providers = array_values(array_filter((array)$this->provider_aktif(), function ($p) { return $p->driver === 'gemini'; }));
        if (!$providers) throw new Exception('Membaca PDF/foto membutuhkan provider Gemini aktif. Tambahkan / aktifkan di bagian Provider.');

        $this->log[] = 'Mode kunci AI: ' . ($tebak ? 'AKTIF' : 'mati');
        $total = ($binary[0] === 'application/pdf')
            ? ($this->halaman ?: $this->hitung_halaman(base64_decode($binary[1])))
            : 0;
        $daftar = $this->buat_rentang($total);
        $this->expected_total = 0;
        $semua = [];
        foreach ($daftar as $k => $r) {
            $label = $r ? "Halaman {$r[0]}-{$r[1]}" : 'Seluruh berkas';
            $hasil = $this->proses_rentang($providers, $tpl, $tebak, $binary, $r, $total, $label);
            if ($hasil !== null) $semua = array_merge($semua, $hasil);
            else $this->log[] = "$label GAGAL di semua provider";
            if ($this->kuota) break;
        }
        if ($this->kuota) {
            throw new Exception('Kuota / batas kecepatan Gemini terlampaui sehingga konversi PDF berhenti di tengah (berhasil ' . count($semua)
                . ' soal) dan berkas TIDAK dibuat agar tidak mengimpor soal yang kurang.');
        }
        $sebelum = count($semua);
        $semua = $this->hilangkan_ganda($semua);
        if ($sebelum !== count($semua)) $this->log[] = 'Soal ganda di batas halaman dibuang: ' . ($sebelum - count($semua));
        $soal = $this->bersihkan($semua);
        if ($soal && $this->baca_ulang) $soal = $this->baca_ulang_opsi($providers, $soal, $binary);
        if ($tebak && $soal) $soal = $this->lengkapi_kunci($providers, $soal);
        $this->periksa_kewajaran((array)$soal);
        return $soal;
    }

    protected function proses_rentang($providers, $tpl, $tebak, $binary, $r, $total, $label)
    {
        $this->rentang = $r ? [$r[0], $r[1], $total] : null;
        $prompt = $this->prompt($tpl, $tebak, 'berkas');
        $this->rentang = null;
        $hasil = $this->konversi_rentang($providers, $prompt, $binary);
        $this->log[] = "$label: " . ($hasil === null ? 'gagal' : count($hasil) . ' soal');
        if ($hasil === null && $r && $r[1] > $r[0] && !$this->kuota) {
            $this->log[] = "$label: dipecah per halaman";
            $all = [];
            for ($h = $r[0]; $h <= $r[1]; $h++) {
                $x = $this->proses_rentang($providers, $tpl, $tebak, $binary, [$h, $h], $total, "Halaman $h");
                if ($this->kuota) break;
                if ($x) $all = array_merge($all, $x);
            }
            return $all ?: null;
        }
        return $hasil;
    }

    protected function konversi_rentang($providers, $prompt, $binary)
    {
        $kena429 = false;
        foreach ($providers as $p) {
            for ($try = 1; $try <= 2; $try++) {
                try {
                    $soal = $this->parse_json($this->panggil($p, $prompt, '', $binary));
                    if (is_array($soal)) { $this->log[] = "OK via {$p->nama}"; return $soal; }
                    $this->log[] = "{$p->nama}: JSON tidak valid (percobaan $try)";
                } catch (Exception $e) {
                    if ($e->getCode() == 429) {
                        $kena429 = true;
                        if (!$this->daily && $this->retry_after !== null && $this->retry_after <= 65 && $this->sleeps < 6 && $try < 2) {
                            $this->sleeps++;
                            $this->log[] = "{$p->nama}: batas kecepatan, menunggu " . ceil($this->retry_after) . " detik lalu mencoba lagi";
                            sleep((int)ceil($this->retry_after) + 1);
                            continue;
                        }
                        $this->log[] = "{$p->nama}: kuota" . ($this->daily ? ' HARIAN' : '') . ' habis (429)';
                        break;
                    }
                    $this->log[] = "{$p->nama}: " . $e->getMessage();
                }
            }
        }
        if ($kena429) $this->kuota = true;
        return null;
    }

    public function hilangkan_ganda(array $list)
    {
        $out = []; $peta = [];
        foreach ($list as $s) {
            if (!is_array($s)) continue;
            $q = mb_strtolower(preg_replace('/[^\p{L}\p{N}]+/u', '', (string)($s['pertanyaan'] ?? '')), 'UTF-8');
            $o = '';
            foreach ((array)($s['opsi'] ?? []) as $x) $o .= mb_strtolower(preg_replace('/[^\p{L}\p{N}]+/u', '', (string)$x), 'UTF-8') . '|';
            $kunci = $q . '#' . substr($o, 0, 40);
            if ($q === '') { $out[] = $s; continue; }
            if (isset($peta[$kunci])) {
                $i = $peta[$kunci];
                if (count((array)($s['opsi'] ?? [])) > count((array)($out[$i]['opsi'] ?? []))) $out[$i] = $s;
                continue;
            }
            $peta[$kunci] = count($out);
            $out[] = $s;
        }
        return $out;
    }

    /* ---------------- 4. PEMBERSIH & KONVERSI TABEL ---------------- */

    public function bersihkan($list)
    {
        return parent::bersihkan($this->konversi_tabel_html($this->rapikan_tabel($this->tandai_gambar_hilang($this->terapkan_bagian((array)$list)))));
    }

    /** Memastikan penutup tag [TABEL] tidak ada yang tertinggal/hilang pada pertanyaan. */
    public function rapikan_tabel(array $list)
    {
        foreach ($list as $i => $s) {
            if (!is_array($s)) continue;
            if (isset($s['pertanyaan']) && is_string($s['pertanyaan'])) {
                $q = $s['pertanyaan'];
                if (stripos($q, '[TABEL]') !== false && stripos($q, '[/TABEL]') === false) {
                    $list[$i]['pertanyaan'] = rtrim($q) . ' [/TABEL]';
                }
            }
        }
        return $list;
    }

    /** Mengubah tag [TABEL] ... [/TABEL] menjadi HTML <table> baku. */
    public function konversi_tabel_html(array $list)
    {
        foreach ($list as $i => $s) {
            if (!is_array($s) || empty($s['pertanyaan']) || !is_string($s['pertanyaan'])) continue;

            $q = $s['pertanyaan'];

            if (preg_match('/\[TABEL\](.*?)\[\/TABEL\]/is', $q, $m)) {
                $isi_tabel = trim($m[1]);
                $html_tabel = $this->parse_pipa_ke_html($isi_tabel);
                $q = str_replace($m[0], "\n" . $html_tabel . "\n", $q);
                $list[$i]['pertanyaan'] = $q;
            }
        }
        return $list;
    }

    /** Mengonversi teks string berpemisah '|' menjadi tabel HTML secara presisi. */
    protected function parse_pipa_ke_html($teks)
    {
        // Pisahkan baris dengan regex HANYA jika teks sama sekali tidak punya baris baru (tabel menyatu dalam satu baris).
        // Jika AI sudah memberi satu baris tabel per baris, JANGAN dipecah lagi: regex ini akan memotong sel berisi
        // lebih dari satu kata ("Jawa Barat" -> "Jawa" / "Barat") dan merusak tabel.
        $teks = str_replace(["\r\n", "\r"], "\n", trim($teks));
        $teks_rapia = (strpos($teks, "\n") === false)
            ? preg_replace('/(?<=[0-9a-zA-Z\)\,\.\%])\s+(?=[A-Z][a-zA-Z0-9\s\.\,\-\(\)]+\|)/u', "\n", $teks)
            : $teks;
        
        $baris = array_filter(explode("\n", str_replace(["\r\n", "\r"], "\n", $teks_rapia)));

        if (empty($baris)) return $teks;

        $html = '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse:collapse; margin:8px 0; width:100%;">';
        $is_first = true;

        foreach ($baris as $b) {
            $kolom = explode('|', $b);
            $html .= '<tr>';
            foreach ($kolom as $k) {
                $val = trim($k);
                if ($is_first) {
                    $html .= '<th style="background-color:#f2f2f2; font-weight:bold; text-align:center; padding:6px; border:1px solid #ccc;">' . $val . '</th>';
                } else {
                    $html .= '<td style="padding:6px; border:1px solid #ccc; text-align:center; vertical-align:middle;">' . $val . '</td>';
                }
            }
            $html .= '</tr>';
            $is_first = false;
        }
        $html .= '</table>';
        return $html;
    }

    /**
     * Menjamin ketersediaan penanda [GAMBAR: ...] jika soal terdeteksi memiliki ilustrasi.
     * Hanya frasa yang merujuk gambar/grafik/diagram yang memicu slot. Kata benda umum (botol, mangga, dll.)
     * sengaja tidak dipakai, karena membuat soal teks biasa salah dikira bergambar dan kuncinya ikut terhapus.
     */
    public function tandai_gambar_hilang(array $list)
    {
        $pola = '/(gambar|grafik|diagram|denah|peta)\s+(berikut|di\s+atas|di\s+bawah|tersebut|ini)|(menurut|berdasarkan|pada|dari|amati(?:lah)?|perhatikan(?:lah)?)\s+(gambar|grafik|diagram)|(benar|tepat|sesuai)\s+(?:menurut\s+)?(gambar|grafik|diagram)/iu';
        $n = 0;
        foreach ($list as $i => $s) {
            if (!is_array($s) || !in_array((int)($s['tipe'] ?? 0), [1, 2, 3, 4, 5], true)) continue;
            $q = (string)($s['pertanyaan'] ?? '');
            if ($q === '' || strpos($q, '[GAMBAR:') !== false || !preg_match($pola, $q)) continue;
            if (strpos((string)json_encode($s['opsi'] ?? [], JSON_UNESCAPED_UNICODE), '[GAMBAR:') !== false) continue;
            $list[$i]['pertanyaan'] = rtrim($q) . "\n";
            if (!empty($s['kunci_ai'])) {
                $list[$i]['kunci'] = [];
                if (isset($s['jawaban'])) $list[$i]['jawaban'] = '';
                $list[$i]['kunci_ai'] = false;
            }
            $n++;
        }
        if ($n) $this->log[] = "Soal merujuk gambar tanpa penanda: $n soal diberi slot gambar otomatis.";
        return $list;
    }

    /* ---------------- BACA ULANG OPSI BERTANDA [CEK] ---------------- */

    /** Pembungkus panggil() (private di Ai_converter) supaya bisa diganti saat pengujian */
    protected function ai_panggil($p, $prompt, $binary, $schema)
    {
        return $this->induk('panggil', $p, $prompt, '', $binary, $schema);
    }

    /**
     * Soal pilihan ganda yang opsinya masih bertanda [CEK] dibaca ulang dalam SATU panggilan tambahan:
     * AI hanya diminta membaca opsi jawabannya (fokus), tanpa harus menyusun seluruh soal lagi.
     * Hasil dipakai hanya jika jumlah [CEK]-nya berkurang dan jumlah opsinya tidak menyusut.
     */
    protected function baca_ulang_opsi($providers, array $soal, $binary)
    {
        $daftar = [];
        foreach ($soal as $i => $s) {
            if (!in_array($s['tipe'], [1, 2], true)) continue;
            $teks = json_encode($s['opsi'], JSON_UNESCAPED_UNICODE);
            if (strpos($teks, '[CEK]') === false || strpos($teks, '[GAMBAR:') !== false) continue;
            $daftar[] = $i;
            if (count($daftar) >= 25) break;
        }
        if (!$daftar) return $soal;

        $baris = '';
        foreach ($daftar as $n => $i) {
            $q = trim(preg_replace('/\s+/u', ' ', (string)$soal[$i]['pertanyaan']));
            if (mb_strlen($q, 'UTF-8') > 160) $q = mb_substr($q, 0, 160, 'UTF-8') . '...';
            $baris .= ($n + 1) . '. Awal soal: ' . $q . "\n   Pembacaan lama (ada yang salah): " . json_encode($soal[$i]['opsi'], JSON_UNESCAPED_UNICODE) . "\n";
        }
        $prompt = "Berkas terlampir adalah naskah soal ujian (halaman berupa gambar). Daftar soal di bawah ini pernah dibaca dengan opsi jawaban yang keliru atau kosong.\n"
            . "TUGAS: temukan tiap soal di berkas (cocokkan dari kalimat awal soalnya) lalu baca ULANG hanya OPSI JAWABANNYA, dengan sangat teliti, satu soal pada satu waktu.\n\n"
            . "ATURAN:\n"
            . "- Tiap opsi ditandai kotak berhuruf a. b. c. d. Teks/angka tepat di sebelah kanan huruf itu adalah opsinya. Opsi sering tersusun 2 KOLOM: a. dan b. di kolom kiri, c. dan d. di kolom kanan; tiap huruf berdiri sendiri.\n"
            . "- Pecahan tertulis bertumpuk: angka di ATAS garis = pembilang, di BAWAH garis = penyebut. Tulis \$\\frac{pembilang}{penyebut}\$. Bilangan campuran: \$1\\frac{1}{3}\$. Jangan mengambil angka dari soal di atas atau opsi di sebelahnya.\n"
            . "- Tulis TEPAT 4 opsi bila tercetak 4 (urut a, b, c, d), jangan melewati atau menggeser opsi. Bilangan biasa tulis polos (mis. 70). Jangan memakai \\text, \\mathbb, \\bullet atau perintah LaTeX selain \\frac dan \\times.\n"
            . "- Jika sebuah opsi memang cacat di sumber (mis. hanya tampak garis pecahan tanpa angka), tulis hanya [CEK] untuk opsi itu. Jangan mengarang.\n"
            . "- Keluarkan JSON saja: {\"soal\":[{\"id\":1,\"opsi\":[\"...\",\"...\",\"...\",\"...\"]}, ...]} dengan id sesuai nomor pada daftar.\n\n"
            . "DAFTAR SOAL:\n" . $baris;

        $schema = ['type' => 'OBJECT', 'properties' => ['soal' => ['type' => 'ARRAY', 'items' => [
            'type' => 'OBJECT',
            'properties' => ['id' => ['type' => 'INTEGER'], 'opsi' => ['type' => 'ARRAY', 'items' => ['type' => 'STRING']]],
            'required' => ['id', 'opsi'],
        ]]], 'required' => ['soal']];

        $baru = null;
        foreach ($providers as $p) {
            try {
                $baru = $this->parse_json($this->ai_panggil($p, $prompt, $binary, $schema));
                if (is_array($baru)) break;
            } catch (Exception $e) {
                $this->log[] = 'Baca ulang opsi: ' . $e->getMessage();
                if ($e->getCode() == 429) break;
            }
        }
        if (!is_array($baru)) { $this->log[] = 'Baca ulang opsi: gagal (hasil lama dipakai)'; return $soal; }

        $diperbaiki = 0;
        foreach ($baru as $item) {
            if (!is_array($item) || !isset($item['id'], $item['opsi'])) continue;
            $n = (int)$item['id'] - 1;
            if (!isset($daftar[$n])) continue;
            $i = $daftar[$n];
            $opsiBaru = [];
            foreach ((array)$item['opsi'] as $o) $opsiBaru[] = $this->normalisasi_latex(trim((string)$o));
            $opsiBaru = $this->induk('bersih_opsi', $opsiBaru);
            $lama = $soal[$i]['opsi'];
            $cekLama = substr_count(json_encode($lama, JSON_UNESCAPED_UNICODE), '[CEK]');
            $cekBaru = substr_count(json_encode($opsiBaru, JSON_UNESCAPED_UNICODE), '[CEK]');
            if (count($opsiBaru) >= count($lama) && $cekBaru < $cekLama) {
                $soal[$i]['opsi'] = $opsiBaru;
                $diperbaiki++;
            }
        }
        $this->log[] = 'Baca ulang opsi: ' . $diperbaiki . ' dari ' . count($daftar) . ' soal diperbaiki';
        return $soal;
    }

    /* ---------------- BACA ULANG LEWAT GEMINI WEB (mode manual) ---------------- */

    /** Nomor urut (mulai 1) soal pilihan ganda pada JSON mentah yang opsinya masih bertanda [CEK] */
    protected function soal_ragu(array $list)
    {
        $hasil = [];
        foreach (array_values($list) as $k => $s) {
            if (!is_array($s) || !in_array((int)($s['tipe'] ?? 0), [1, 2], true)) continue;
            $teks = json_encode($s['opsi'] ?? [], JSON_UNESCAPED_UNICODE);
            if (strpos($teks, '[CEK]') === false || strpos($teks, '[GAMBAR:') !== false) continue;
            $hasil[$k + 1] = $s;
        }
        return $hasil;
    }

    /**
     * Teks lanjutan untuk ditempel ke percakapan Gemini YANG SAMA (PDF masih terlampir) agar opsi yang masih ragu dibaca ulang.
     * Kosong bila tidak ada soal yang perlu dibaca ulang.
     */
    public function prompt_ulang(array $list)
    {
        $ragu = $this->soal_ragu($list);
        if (!$ragu) return '';
        $baris = '';
        foreach ($ragu as $no => $s) {
            $q = trim(preg_replace('/\s+/u', ' ', (string)($s['pertanyaan'] ?? '')));
            if (mb_strlen($q, 'UTF-8') > 150) $q = mb_substr($q, 0, 150, 'UTF-8') . '...';
            $baris .= "id " . $no . ". Awal soal: " . $q . "\n   Bacaan Anda tadi (ada yang salah/ragu): " . json_encode($s['opsi'], JSON_UNESCAPED_UNICODE) . "\n";
        }
        return "Berkas soal tadi masih terlampir di percakapan ini. Pada beberapa soal, opsi jawaban hasil bacaan Anda masih salah atau meragukan. "
            . "Buka lagi halaman gambarnya, temukan tiap soal di bawah ini (cocokkan dari kalimat awalnya), lalu baca ULANG hanya OPSI JAWABANNYA dengan sangat teliti, satu soal pada satu waktu.\n\n"
            . "ATURAN:\n"
            . "- Tiap opsi ditandai kotak berhuruf a. b. c. d.; isi opsi ada tepat di sebelah kanan hurufnya. Opsi sering tersusun 2 KOLOM (a. dan b. di kiri, c. dan d. di kanan); tiap huruf berdiri sendiri, jangan menggeser opsi.\n"
            . "- Pecahan bertumpuk: angka di ATAS garis = pembilang, di BAWAH garis = penyebut. Tulis \$\\frac{pembilang}{penyebut}\$; bilangan campuran \$1\\frac{1}{3}\$. Jangan mengambil angka dari soal di atasnya atau opsi di sebelahnya.\n"
            . "- Tulis semua opsi yang tercetak, urut a, b, c, d. Bilangan biasa dan simbol (<, >, =) tulis polos. Jangan memakai \\text, \\mathbb, \\bullet.\n"
            . "- Beri [CEK] HANYA pada opsi yang memang cacat/tidak tercetak jelas di sumber. Bacaan yang sudah jelas tidak diberi [CEK].\n"
            . "- Keluarkan JSON saja (bukan seluruh soal): {\"soal\":[{\"id\":4,\"opsi\":[\"...\",\"...\",\"...\",\"...\"]}, ...]}. Isi \"id\" persis sama dengan nomor id di daftar.\n\n"
            . "DAFTAR SOAL:\n" . $baris;
    }

    /** Terapkan jawaban baca ulang ([{id, opsi}]) ke JSON mentah; id = nomor urut soal pada JSON mentah (mulai 1) */
    public function terapkan_koreksi(array $list, array $koreksi)
    {
        $list = array_values($list);
        foreach ($koreksi as $k) {
            if (!is_array($k) || !isset($k['id'], $k['opsi']) || !is_array($k['opsi'])) continue;
            $i = (int)$k['id'] - 1;
            if (!isset($list[$i]) || !is_array($list[$i]) || !in_array((int)($list[$i]['tipe'] ?? 0), [1, 2], true)) continue;
            $baru = array_values(array_map(function ($o) { return trim((string)$o); }, $k['opsi']));
            while ($baru && end($baru) === '') array_pop($baru);
            $lama = (array)($list[$i]['opsi'] ?? []);
            if (count($baru) < count($lama) || count($baru) < 2) continue;
            $list[$i]['opsi'] = $baru;
            $this->log[] = 'Baca ulang: opsi soal ke-' . ($i + 1) . ' diganti dengan hasil baca ulang';
        }
        return $list;
    }

    protected function lengkapi_kunci($providers, array $soal)
    {
        $tahan = [];
        foreach ($soal as $i => $s) {
            if (empty($s['kunci']) && in_array($s['tipe'], [1, 2], true) && (strpos((string)$s['pertanyaan'], '[GAMBAR:') !== false || strpos(json_encode($s['opsi'] ?? [], JSON_UNESCAPED_UNICODE), '[GAMBAR:') !== false)) {
                $tahan[$i] = $s; unset($soal[$i]);
            }
        }
        if ($tahan) $this->log[] = count($tahan) . ' soal bergambar dilewati saat penentuan kunci.';
        $hasil = $soal ? $this->induk('lengkapi_kunci', $providers, $soal) : [];
        $hasil = $hasil + $tahan;
        ksort($hasil);
        return array_values($hasil);
    }

    /**
     * Prompt untuk mode manual (Gemini web) khusus PDF/foto. Memuat aturan penanda [GAMBAR: ...] dan
     * pembacaan matematika, yang pada mode manual sebelumnya tidak ikut terkirim.
     */
    public function prompt_manual_pdf($tebak, $tplPrompt)
    {
        return parent::prompt_manual($tebak, $tplPrompt, 'berkas')
            . "\n\n" . $this->blok_gambar_berkas()
            . "\n\n" . $this->blok_matematika_pdf();
    }
}