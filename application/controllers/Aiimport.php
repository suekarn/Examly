<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Simpan di: application/controllers/Aiimport.php
 * Buka: http://localhost/ujian/aiimport
 *
 * Template_.docx (milik aplikasi Anda) taruh di: application/third_party/Template_.docx
 */
class Aiimport extends CI_Controller
{
    /** Ekstensi yang ditangani importer PDF/foto (Ai_converter_pdf). Selain ini (docx/txt) memakai Ai_converter. */
    const EKS_PDF = ['pdf', 'png', 'jpg', 'jpeg', 'webp'];

    /** Mesin konversi yang sedang dipakai pada request ini (untuk log, gambar, dll). */
    private $mesin;
    private $prompt_ulang = '';   // teks lanjutan untuk membaca ulang opsi yang masih bertanda [CEK] (mode PDF/foto)

    public function __construct()
    {
        parent::__construct();
        // Hanya admin yang sudah login (GarudaCBT memakai ion_auth). Sesuaikan jika berbeda.
        $this->load->library('ion_auth');
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            show_error('Akses ditolak', 403);
        }
        $this->load->library('ai_converter');
        $this->load->library('ai_converter_pdf');
        $this->load->library('docx_template');
        $this->mesin = $this->ai_converter;
    }

    /** Pilih mesin menurut ekstensi berkas: PDF/foto -> Ai_converter_pdf, lainnya -> Ai_converter. */
    private function pilih_mesin($ext)
    {
        return in_array(strtolower((string)$ext), self::EKS_PDF, true) ? $this->ai_converter_pdf : $this->ai_converter;
    }

    /**
     * Mode PDF/foto = token kosong (langkah 1 untuk PDF/foto memang tidak membuat token).
     * Pada mode ini gambar tidak diekstrak server, jadi setiap soal yang butuh gambar harus punya slot unggah.
     */
    private function mode_pdf()
    {
        return (string)$this->input->post('token') === '';
    }

    public function index()
    {
        $data['templates'] = $this->db->get('ai_template')->result();
        $data['providers'] = $this->db->order_by('prioritas')->get('ai_provider')->result();
        $data['csrf_name'] = $this->security->get_csrf_token_name();
        $data['csrf_hash'] = $this->security->get_csrf_hash();
        $this->load->view('aiimport/index', $data);
    }

    /* ================= MODE OTOMATIS (pakai API key) ================= */

    public function proses()
    {
        set_time_limit(0);
        header('Content-Type: application/json');
        try {
            $tpl = $this->ambil_template();
            $text = trim((string)$this->input->post('teks')); $binary = null;
            if (!empty($_FILES['berkas']['tmp_name'])) {
                $ext = pathinfo($_FILES['berkas']['name'], PATHINFO_EXTENSION);
                $this->mesin = $this->pilih_mesin($ext);
                $r = $this->mesin->baca_file($_FILES['berkas']['tmp_name'], $ext);
                if (isset($r['binary'])) $binary = $r['binary']; else $text = trim($r['text'] . "\n" . $text);
            }
            if ($text === '' && !$binary) throw new Exception('Teks soal / berkas kosong.');

            $soal = $this->mesin->proses($text, $binary, $tpl, (bool)$this->input->post('tebak_kunci'));
            if (!$soal) throw new Exception('Tidak ada soal yang berhasil dikonversi. Lihat log.');
            $this->selesai($soal, $this->mesin->images, $this->mesin->equations);
        } catch (Throwable $e) {
            $this->gagal($e);
        }
    }

    /* ================= MODE MANUAL (Gemini web / chatbot lain, tanpa API key) ================= */

    /** Kumpulkan berkas unggahan (mendukung berkas[] banyak berkas maupun berkas tunggal) */
    private function kumpulkan_berkas()
    {
        $out = [];
        if (empty($_FILES['berkas']['tmp_name'])) return $out;
        $tmp = (array)$_FILES['berkas']['tmp_name'];
        $nama = (array)$_FILES['berkas']['name'];
        $err = (array)$_FILES['berkas']['error'];
        foreach ($tmp as $i => $t) {
            if (!$t || (int)$err[$i] !== UPLOAD_ERR_OK) continue;
            $out[] = ['tmp' => $t, 'name' => $nama[$i]];
        }
        return $out;
    }

    /** Langkah 1: ekstrak teks dari docx + susun paket "prompt + soal" untuk ditempel ke Gemini (boleh banyak berkas) */
    public function ekstrak()
    {
        header('Content-Type: application/json');
        try {
            $tpl = $this->ambil_template();
            $files = $this->kumpulkan_berkas();
            if (!$files) throw new Exception('Pilih berkas Word (.docx) atau .txt.');
            if (count($files) > 10) throw new Exception('Maksimal 10 berkas sekali proses.');
            $banyak = count($files) > 1;

            $teks = ''; $mode_lampiran = null; $nama_lampiran = [];
            $gambar = []; $persamaan = []; $dilewati = 0;
            foreach ($files as $n => $f) {
                $ext = pathinfo($f['name'], PATHINFO_EXTENSION);
                $r = $this->ai_converter->baca_file($f['tmp'], $ext);
                $lampiran = isset($r['binary']);
                if ($mode_lampiran !== null && $mode_lampiran !== $lampiran) {
                    throw new Exception('Satu kali proses tidak bisa mencampur Word/teks dengan PDF/foto. Proses secara terpisah.');
                }
                $mode_lampiran = $lampiran;
                if ($lampiran) { $nama_lampiran[] = $f['name']; continue; }

                if (trim($r['text']) === '') throw new Exception('Berkas "' . $f['name'] . '" kosong atau tidak terbaca. Jika berupa hasil scan, simpan sebagai PDF atau foto lalu unggah lagi.');
                // Gambar/persamaan milik berkas ini (txt tidak punya; jangan ikut yang tersisa dari berkas sebelumnya)
                $im = strtolower($ext) === 'docx' ? $this->ai_converter->images : [];
                $eq = strtolower($ext) === 'docx' ? $this->ai_converter->equations : [];
                $isi = $r['text'];
                if ($banyak) {
                    // Nomor penanda tiap berkas dimulai dari 1; geser agar tidak bertabrakan dengan berkas sebelumnya
                    $offI = count($gambar); $offE = count($persamaan);
                    if ($offI || $offE) {
                        $isi = preg_replace_callback('/\[\[IMG:(\d+)\]\]/', function ($m) use ($offI) { return '[[IMG:' . ((int)$m[1] + $offI) . ']]'; }, $isi);
                        $isi = preg_replace_callback('/\[\[EQ:(\d+)\|/', function ($m) use ($offE) { return '[[EQ:' . ((int)$m[1] + $offE) . '|'; }, $isi);
                    }
                    $teks .= ($teks === '' ? '' : "\n\n") . '=== BERKAS ' . ($n + 1) . ': ' . $f['name'] . " ===\n";
                }
                $teks .= $isi;
                $gambar = array_merge($gambar, $im);
                $persamaan = array_merge($persamaan, $eq);
                if (strtolower($ext) === 'docx') $dilewati += $this->ai_converter->gambar_dilewati;
            }

            if ($mode_lampiran) {
                // PDF / gambar: tidak diekstrak di server. Pengguna melampirkan berkasnya sendiri di Gemini, kita hanya beri prompt.
                $prompt = $this->ai_converter_pdf->prompt_manual_pdf((bool)$this->input->post('tebak_kunci'), $tpl->prompt)
                        . "\n\nSUMBER SOAL: " . ($banyak
                            ? count($nama_lampiran) . " berkas PDF/gambar yang saya lampirkan (" . implode(', ', $nama_lampiran) . "). Baca SEMUA halaman dari SEMUA berkas sesuai urutan, lalu gabungkan menjadi satu daftar soal bernomor berurutan."
                            : "berkas PDF/gambar yang saya lampirkan. Baca SEMUA halaman dari awal sampai akhir.");
                echo json_encode(['ok' => true, 'lampiran' => true, 'token' => '', 'gambar' => 0,
                                  'paket' => $prompt, 'csrf' => $this->security->get_csrf_hash()]);
                return;
            }

            $token = bin2hex(random_bytes(8));
            if ($gambar || $persamaan) {
                file_put_contents(APPPATH . 'cache/aiimport_img_' . $token . '.dat',
                    serialize(['images' => $gambar, 'equations' => $persamaan]));
            }
            $prompt = $this->ai_converter->prompt_manual((bool)$this->input->post('tebak_kunci'), $tpl->prompt,
                      $gambar ? 'penanda' : 'teks');
            if ($banyak) $prompt .= "\n\nCATATAN: sumber soal terdiri dari " . count($files) . " berkas yang dipisah penanda \"=== BERKAS n: nama ===\". Gabungkan semuanya menjadi satu daftar soal bernomor berurutan.";
            echo json_encode([
                'ok' => true, 'token' => $token, 'gambar' => count($gambar), 'persamaan' => count($persamaan),
                'gambar_dilewati' => $dilewati,
                'paket' => $prompt . "\n\nSUMBER SOAL:\n" . $teks,
                'csrf' => $this->security->get_csrf_hash(),
            ]);
        } catch (Throwable $e) {
            $this->gagal($e);
        }
    }

    /** Daftar tempat [GAMBAR: ...] pada JSON hasil Gemini (untuk form unggah gambar di halaman) */
    public function cek_gambar()
    {
        header('Content-Type: application/json');
        $mesin = $this->mode_pdf() ? $this->ai_converter_pdf : $this->ai_converter;
        $list = $mesin->parse_json($this->input->post('json'));
        // Mode PDF/foto: soal yang merujuk gambar tetapi tanpa penanda otomatis diberi slot, jadi kotak unggah selalu muncul.
        if ($list && $this->mode_pdf()) $list = $this->ai_converter_pdf->tandai_gambar_hilang($list);
        $tempat = $list ? $mesin->tempat_gambar($list) : [];
        echo json_encode(['ok' => true, 'tempat' => $tempat, 'csrf' => $this->security->get_csrf_hash()]);
    }

    /** Langkah 3: JSON hasil Gemini ditempel -> isi Template_.docx (gambar dari langkah 1 / unggahan ikut) */
    public function dari_json()
    {
        header('Content-Type: application/json');
        try {
            $pdf = $this->mode_pdf();
            $this->mesin = $pdf ? $this->ai_converter_pdf : $this->ai_converter;
            $list = $this->mesin->parse_json($this->input->post('json'));
            if (!$list) throw new Exception('JSON tidak terbaca' . ($this->mesin->json_error ? ' (' . $this->mesin->json_error . ')' : '') . '. Pastikan seluruh jawaban Gemini (mulai { sampai }) ikut ditempel. Jika masih gagal, minta Gemini: "ulangi jawabanmu sebagai JSON valid saja, tanpa penanda sitasi".');

            $images = []; $equations = [];
            $token = (string)$this->input->post('token');
            if (preg_match('/^[a-f0-9]{16}$/', $token) && is_file(APPPATH . 'cache/aiimport_img_' . $token . '.dat')) {
                $data = @unserialize(file_get_contents(APPPATH . 'cache/aiimport_img_' . $token . '.dat'), ['allowed_classes' => false]);
                if (is_array($data)) { $images = $data['images'] ?? []; $equations = $data['equations'] ?? []; }
            }

            // hasil baca ulang opsi dari Gemini (opsional, bisa lebih dari satu putaran); id = nomor urut soal pada JSON ini
            if ($pdf) {
                foreach ((array)$this->input->post('koreksi') as $kor) {
                    if (trim((string)$kor) === '') continue;
                    $kl = $this->ai_converter_pdf->parse_json($kor);
                    if (!is_array($kl)) throw new Exception('Jawaban baca ulang tidak terbaca. Tempel seluruh jawaban Gemini (mulai { sampai }) di kotak baca ulang.');
                    $list = $this->ai_converter_pdf->terapkan_koreksi($list, $kl);
                }
                $this->prompt_ulang = $this->ai_converter_pdf->prompt_ulang($list);
            }

            // gambar yang diunggah pengguna untuk penanda [GAMBAR: ...] (soal dari PDF/foto)
            $unggah = [];
            if (!empty($_FILES['gbr']['tmp_name']) && is_array($_FILES['gbr']['tmp_name'])) {
                foreach ($_FILES['gbr']['tmp_name'] as $k => $tmp) {
                    if (!$tmp || (int)$_FILES['gbr']['error'][$k] !== UPLOAD_ERR_OK || !is_uploaded_file($tmp)) continue;
                    $unggah[(int)$k] = ['tmp' => $tmp];
                }
            }
            // Urutan slot harus sama persis dengan cek_gambar(): tandai dulu, baru pasang gambar unggahan.
            if ($pdf) $list = $this->ai_converter_pdf->tandai_gambar_hilang($list);
            if ($unggah) {
                $this->mesin->images = $images;
                $list = $this->mesin->pasang_gambar($list, $unggah);
                $images = $this->mesin->images;
            }

            $soal = $this->mesin->bersihkan($list);
            if (!$soal) throw new Exception('Tidak ada soal valid di JSON tersebut.');
            $this->selesai($soal, $images, $equations);
        } catch (Throwable $e) {
            $this->gagal($e);
        }
    }

    /* ================= util ================= */

    private function ambil_template()
    {
        $tpl = $this->db->get_where('ai_template', ['id' => (int)$this->input->post('id_template')])->row();
        if (!$tpl) throw new Exception('Pilih template.');
        return $tpl;
    }

    private function selesai(array $soal, array $images, array $equations = [])
    {
        $nama = bin2hex(random_bytes(8)) . '.docx';
        $this->docx_template->generate(APPPATH . 'third_party/Template_.docx', $soal, APPPATH . 'cache/aiimport_' . $nama, $images, $equations);
        $this->rapikan_content_types(APPPATH . 'cache/aiimport_' . $nama); // cegah pesan Word "unreadable content"
        // nama unduhan mengikuti nama berkas soal yang diunggah (disimpan di berkas pendamping untuk unduhan biasa)
        $dasar = trim((string)preg_replace('/[\\\\\/:*?"<>|\x00-\x1f]/', '_', (string)$this->input->post('nama_unduh')));
        $dasar = preg_replace('/\.docx$/i', '', $dasar);
        if ($dasar !== '') @file_put_contents(APPPATH . 'cache/aiimport_' . $nama . '.nama', mb_substr($dasar, 0, 120));

        $hit = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0]; $tanpa_kunci = 0; $kunci_ai = 0;
        foreach ($soal as $s) {
            $hit[$s['tipe']]++;
            if (!empty($s['kunci_ai'])) $kunci_ai++;
            if (($s['tipe'] <= 2 && !$s['kunci']) || ($s['tipe'] == 3 && !$s['pasangan']) || ($s['tipe'] == 4 && $s['jawaban'] === '')) $tanpa_kunci++;
        }
        echo json_encode([
            'ok' => true, 'jumlah' => count($soal), 'per_jenis' => $hit, 'tanpa_kunci' => $tanpa_kunci,
            'kunci_ai' => $kunci_ai, 'gambar_kosong' => substr_count(json_encode($soal), '[GAMBAR:'), 'gambar_dilewati' => $this->mesin->gambar_dilewati, 'gambar' => count($images), 'persamaan' => count($equations), 'diharapkan' => $this->mesin->expected_total,
            'unduh' => site_url('aiimport/unduh/' . $nama),
            'prompt_ulang' => $this->prompt_ulang,
            'log' => $this->mesin->log, 'csrf' => $this->security->get_csrf_hash(),
        ]);
    }

    /**
     * Merapikan [Content_Types].xml pada docx hasil generate:
     * - menghapus entri <Default Extension="..."> ganda (penyebab Word: "found unreadable content"),
     * - mendaftarkan tipe gambar (jpg, gif, dll.) yang dipakai di word/media tetapi belum terdaftar.
     */
    private function rapikan_content_types($path)
    {
        if (!class_exists('ZipArchive')) return;
        $zip = new ZipArchive();
        if ($zip->open($path) !== true) return;
        $xml = $zip->getFromName('[Content_Types].xml');
        if ($xml === false) { $zip->close(); return; }
        $tipe = ['png' => 'image/png', 'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'gif' => 'image/gif',
                 'bmp' => 'image/bmp', 'webp' => 'image/webp', 'emf' => 'image/x-emf', 'wmf' => 'image/x-wmf'];
        $ada = [];
        $xml = preg_replace_callback('/<Default\s+[^>]*?Extension="([^"]+)"[^>]*\/>/i', function ($m) use (&$ada) {
            $e = strtolower($m[1]);
            if (isset($ada[$e])) return '';
            $ada[$e] = true;
            return $m[0];
        }, $xml);
        $tambah = '';
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $nama = (string)$zip->getNameIndex($i);
            if (strpos($nama, 'word/media/') !== 0) continue;
            $e = strtolower(pathinfo($nama, PATHINFO_EXTENSION));
            if (isset($tipe[$e]) && empty($ada[$e])) {
                $ada[$e] = true;
                $tambah .= '<Default Extension="' . $e . '" ContentType="' . $tipe[$e] . '"/>';
            }
        }
        if ($tambah !== '') $xml = preg_replace('/(<Types[^>]*>)/', '$1' . $tambah, $xml, 1);
        $zip->addFromString('[Content_Types].xml', $xml);
        $zip->close();
    }

    private function gagal($e)
    {
        echo json_encode(['ok' => false, 'pesan' => $e->getMessage() . (($e instanceof Exception) ? '' : ' [' . basename($e->getFile()) . ':' . $e->getLine() . ']'), 'log' => $this->mesin->log,
                          'csrf' => $this->security->get_csrf_hash()]);
    }

    public function unduh($nama = '')
    {
        if (!preg_match('/^[a-f0-9]{16}\.docx$/', $nama)) show_404();
        $path = APPPATH . 'cache/aiimport_' . $nama;
        if (!is_file($path)) show_404();
        $this->load->helper('download');
        $asal = is_file($path . '.nama') ? trim((string)@file_get_contents($path . '.nama')) : '';
        force_download(($asal !== '' ? $asal : 'Soal_Siap_Import_' . date('Ymd_His')) . '.docx', file_get_contents($path));
    }

    /* ================= pengaturan provider & template ================= */

    public function simpan_provider()
    {
        $this->db->insert('ai_provider', [
            'nama' => $this->input->post('nama'), 'driver' => $this->input->post('driver'),
            'base_url' => $this->input->post('base_url'), 'api_key' => $this->input->post('api_key'),
            'model' => $this->input->post('model'), 'prioritas' => (int)$this->input->post('prioritas'), 'aktif' => 1,
        ]);
        redirect('aiimport');
    }

    public function hapus_provider($id)
    {
        $this->db->delete('ai_provider', ['id' => (int)$id]);
        redirect('aiimport');
    }

    public function simpan_template()
    {
        $this->db->insert('ai_template', [
            'nama' => $this->input->post('nama'), 'prompt' => $this->input->post('prompt'),
            'opsi_maks' => (int)$this->input->post('opsi_maks') ?: 5,
        ]);
        redirect('aiimport');
    }
}