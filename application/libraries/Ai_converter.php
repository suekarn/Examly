<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// Omml.php (pengubah Equation Word -> LaTeX): dicari di libraries, lalu third_party. Jika tidak ada, rumus tetap diproses tanpa terjemahan LaTeX.
if (!class_exists('Omml', false)) {
    foreach ([__DIR__ . '/Omml.php', APPPATH . 'third_party/Omml.php'] as $f_omml) {
        if (is_file($f_omml)) { require_once $f_omml; break; }
    }
}

// Cadangan bawaan: jika Omml.php tidak ditemukan, pakai pengubah Equation Word -> LaTeX berikut (supaya penanda [[EQ:n|latex]] berisi rumus yang benar, bukan angka tergabung seperti "13").
if (!class_exists('Omml', false)) {
class Omml
{
    const NS_M = 'http://schemas.openxmlformats.org/officeDocument/2006/math';
    const NS_W = 'http://schemas.openxmlformats.org/wordprocessingml/2006/main';

    private static $map = [
        '×' => '\\times ', '÷' => '\\div ', '·' => '\\cdot ', '≥' => '\\geq ', '≤' => '\\leq ', '≠' => '\\neq ',
        '±' => '\\pm ', '≈' => '\\approx ', '∞' => '\\infty ', 'π' => '\\pi ', 'θ' => '\\theta ', 'α' => '\\alpha ',
        'β' => '\\beta ', 'Δ' => '\\Delta ', '→' => '\\rightarrow ', '°' => '^{\\circ}', '%' => '\\%', '−' => '-',
        '…' => '\\dots ', '∑' => '\\sum ', '∫' => '\\int ',
    ];

    /** $frag = string XML '<m:oMath>...</m:oMath>' (tanpa deklarasi namespace). Kembali '' jika gagal. */
    public static function to_latex($frag)
    {
        $xml = '<r xmlns:m="' . self::NS_M . '" xmlns:w="' . self::NS_W . '">' . $frag . '</r>';
        $dom = new DOMDocument();
        $prev = libxml_use_internal_errors(true);
        $ok = $dom->loadXML($xml);
        libxml_clear_errors();
        libxml_use_internal_errors($prev);
        if (!$ok || !$dom->documentElement) return '';
        return trim(self::kids($dom->documentElement));
    }

    private static function kids($n)
    {
        $s = '';
        foreach ($n->childNodes as $c) $s .= self::node($c);
        return $s;
    }

    private static function child($n, $name)
    {
        foreach ($n->childNodes as $c) {
            if ($c->nodeType === XML_ELEMENT_NODE && $c->namespaceURI === self::NS_M && $c->localName === $name) return $c;
        }
        return null;
    }

    private static function attr($n, $name, $default = '')
    {
        if (!$n) return $default;
        $a = $n->getAttributeNS(self::NS_M, $name);
        return $a !== '' ? $a : $default;
    }

    private static function teks($s)
    {
        return strtr($s, self::$map);
    }

    private static function node($n)
    {
        if ($n->nodeType !== XML_ELEMENT_NODE) return '';
        if ($n->namespaceURI !== self::NS_M) return '';   // w:rPr dll tidak berisi isi rumus
        $name = $n->localName;
        switch ($name) {
            case 'r':
                $t = '';
                foreach ($n->childNodes as $c) {
                    if ($c->nodeType === XML_ELEMENT_NODE && $c->namespaceURI === self::NS_M && $c->localName === 't') $t .= $c->textContent;
                }
                return self::teks($t);
            case 'f':
                $num = self::child($n, 'num'); $den = self::child($n, 'den');
                return '\\frac{' . ($num ? self::kids($num) : '') . '}{' . ($den ? self::kids($den) : '') . '}';
            case 'sSup':
                return '{' . self::sub($n, 'e') . '}^{' . self::sub($n, 'sup') . '}';
            case 'sSub':
                return '{' . self::sub($n, 'e') . '}_{' . self::sub($n, 'sub') . '}';
            case 'sSubSup':
                return '{' . self::sub($n, 'e') . '}_{' . self::sub($n, 'sub') . '}^{' . self::sub($n, 'sup') . '}';
            case 'rad':
                $deg = trim(self::sub($n, 'deg'));
                return $deg !== '' ? '\\sqrt[' . $deg . ']{' . self::sub($n, 'e') . '}' : '\\sqrt{' . self::sub($n, 'e') . '}';
            case 'd':
                $pr = self::child($n, 'dPr');
                $beg = $pr ? self::child($pr, 'begChr') : null;
                $end = $pr ? self::child($pr, 'endChr') : null;
                $sep = $pr ? self::child($pr, 'sepChr') : null;
                $b = $beg ? self::attr($beg, 'val', '') : '(';
                $e2 = $end ? self::attr($end, 'val', '') : ')';
                $sp = $sep ? self::attr($sep, 'val', '|') : '|';
                $items = [];
                foreach ($n->childNodes as $c) {
                    if ($c->nodeType === XML_ELEMENT_NODE && $c->namespaceURI === self::NS_M && $c->localName === 'e') $items[] = self::kids($c);
                }
                return self::kurung($b, true) . implode($sp, $items) . self::kurung($e2, false);
            case 'nary':
                $pr = self::child($n, 'naryPr');
                $chr = $pr ? self::child($pr, 'chr') : null;
                $c = $chr ? self::attr($chr, 'val', '∫') : '∫';
                $op = $c === '∑' ? '\\sum' : ($c === '∏' ? '\\prod' : '\\int');
                $lo = trim(self::sub($n, 'sub')); $hi = trim(self::sub($n, 'sup'));
                return $op . ($lo !== '' ? '_{' . $lo . '}' : '') . ($hi !== '' ? '^{' . $hi . '}' : '') . '{' . self::sub($n, 'e') . '}';
            case 'func':
                return self::sub($n, 'fName') . ' ' . self::sub($n, 'e');
            case 'bar':
                return '\\overline{' . self::sub($n, 'e') . '}';
            case 'acc':
                return '\\hat{' . self::sub($n, 'e') . '}';
            case 'limLow':
                return '{' . self::sub($n, 'e') . '}_{' . self::sub($n, 'lim') . '}';
            case 'limUpp':
                return '{' . self::sub($n, 'e') . '}^{' . self::sub($n, 'lim') . '}';
            case 'eqArr':
                $items = [];
                foreach ($n->childNodes as $c) {
                    if ($c->nodeType === XML_ELEMENT_NODE && $c->namespaceURI === self::NS_M && $c->localName === 'e') $items[] = self::kids($c);
                }
                return '\\begin{cases}' . implode(' \\\\ ', $items) . '\\end{cases}';
            case 'ctrlPr': case 'rPr': case 'fPr': case 'dPr': case 'radPr': case 'naryPr': case 'sSupPr':
            case 'sSubPr': case 'sSubSupPr': case 'funcPr': case 'barPr': case 'accPr': case 'eqArrPr':
                return '';
            default:
                return self::kids($n);
        }
    }

    private static function sub($n, $name)
    {
        $c = self::child($n, $name);
        return $c ? self::kids($c) : '';
    }

    private static function kurung($ch, $buka)
    {
        if ($ch === '') return '';
        if ($ch === '{') return $buka ? '\\{' : '';
        if ($ch === '}') return $buka ? '' : '\\}';
        if ($ch === '|') return '|';
        return $ch;
    }
}

}

/**
 * Simpan di: application/libraries/Ai_converter.php
 * Teks/berkas soal  ->  AI (Gemini / OpenAI-compatible, fallback otomatis)  ->  array soal bersih
 * Array ini lalu diisikan ke Template_.docx oleh Docx_template.
 */
class Ai_converter
{
    const POLA_SOAL = '/^\s*(?:\d{1,3}\s*(?:[\.\)]|\|)|[-\x{2022}])\s/mu';
    const POLA_SPLIT = '/(?=^\s*(?:\d{1,3}\s*(?:[\.\)]|\|)|[-\x{2022}])\s)/mu';

    private $CI;
    public $log = [];
    public $kuota = false;          // true jika semua provider kena batas kuota (429)
    public $expected_total = 0;     // perkiraan jumlah soal di sumber
    private $retry_after = null;
    private $daily = false;
    private $sleeps = 0;
    public $equations = [];         // potongan OMML asli (Equation Word) dari docx sumber
    public $images = [];   // gambar yang ditemukan di docx sumber (dipakai Docx_template)
    public $gambar_dilewati = 0;   // gambar docx yang formatnya tidak bisa dibaca server (mis. .emf/.wmf)

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->database();
    }

    /* ---------------- 1. EKSTRAK TEKS ---------------- */

    /** ['text'=>...] untuk docx/txt, ['binary'=>[mime,base64]] untuk pdf/gambar (dibaca Gemini langsung) */
    public function baca_file($path, $ext)
    {
        $ext = strtolower($ext);
        if ($ext === 'docx') return ['text' => $this->docx_ke_teks($path)];
        if ($ext === 'txt')  return ['text' => file_get_contents($path)];
        $mime = ['pdf' => 'application/pdf', 'png' => 'image/png', 'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'webp' => 'image/webp'];
        if (isset($mime[$ext])) return ['binary' => [$mime[$ext], base64_encode(file_get_contents($path))]];
        return ['text' => ''];
    }

    /** Ganti tiap <m:oMath>...</m:oMath> dengan run teks "[[EQ:n|latex]]" */
    private function ganti_persamaan($xml)
    {
        $out = ''; $pos = 0;
        while (preg_match('/<m:oMath(?:\s[^>]*)?>/', $xml, $mm, PREG_OFFSET_CAPTURE, $pos)) {
            $s = $mm[0][1];
            $e = strpos($xml, '</m:oMath>', $s);
            if ($e === false) break;
            $e += strlen('</m:oMath>');
            $frag = substr($xml, $s, $e - $s);
            $this->equations[] = $frag;
            $n = count($this->equations);
            $latex = '';
            try { $latex = class_exists('Omml') ? Omml::to_latex($frag) : ''; } catch (Exception $ex) { $latex = ''; } catch (Throwable $ex) { $latex = ''; }
            if (trim((string)$latex) === '') {
                // cadangan: ambil teks mentah rumus supaya AI tetap punya petunjuk isi rumusnya
                $latex = trim(html_entity_decode(strip_tags($frag), ENT_QUOTES | ENT_XML1, 'UTF-8'));
            }
            $latex = str_replace(['[[', ']]'], ['[ [', '] ]'], $latex);
            $out .= substr($xml, $pos, $s - $pos)
                  . '<w:r><w:t xml:space="preserve">' . htmlspecialchars('[[EQ:' . $n . '|' . $latex . ']]', ENT_XML1 | ENT_QUOTES, 'UTF-8') . '</w:t></w:r>';
            $pos = $e;
        }
        return $out . substr($xml, $pos);
    }

    /** Ubah gambar format lain (webp/bmp, dan emf/wmf bila Imagick tersedia) menjadi PNG. null = gagal. */
    private function ke_png($data, $ext)
    {
        if (class_exists('Imagick')) {
            try {
                $im = new Imagick();
                $im->setResolution(200, 200);
                $im->readImageBlob($data);
                $im->setImageBackgroundColor('white');
                $im = $im->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);
                $im->setImageFormat('png');
                $out = $im->getImageBlob();
                $im->clear();
                if ($out) return $out;
            } catch (Exception $e) { /* lanjut ke GD */ }
        }
        if (function_exists('imagecreatefromstring') && in_array($ext, ['webp', 'bmp'], true)) {
            $src = @imagecreatefromstring($data);
            if ($src) { ob_start(); imagepng($src); $out = ob_get_clean(); imagedestroy($src); return $out ?: null; }
        }
        return null;
    }

    /** Ambil satu gambar dari docx. Mengembalikan [ext, data] atau null jika tidak bisa dipakai. */
    private function muat_gambar($zip, $target)
    {
        $ext  = strtolower(pathinfo($target, PATHINFO_EXTENSION));
        $nama = ($target !== '' && $target[0] === '/') ? ltrim($target, '/') : 'word/' . $target;
        $data = $zip->getFromName($nama);
        if (!$data) { $this->gambar_dilewati++; $this->log[] = "Gambar $target dilewati (berkas gambar tidak ditemukan di dalam docx)"; return null; }
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'], true)) return [$ext, $data];
        $png = $this->ke_png($data, $ext);
        if ($png !== null) { $this->log[] = "Gambar $target (format .$ext) diubah ke PNG"; return ['png', $png]; }
        $this->gambar_dilewati++;
        $this->log[] = "Gambar $target dilewati: format .$ext tidak bisa dibaca server (biasanya rumus MathType atau gambar tempelan lama). "
                     . "Ganti dengan gambar PNG/JPG, atau tulis ulang rumusnya dengan Equation Word.";
        return null;
    }

    /** Gambar "garis" (garis pecahan / garis tabel hasil konversi PDF ke Word): sangat tipis, bukan gambar soal. */
    private function gambar_garis($data, $cx, $cy)
    {
        $sz = @getimagesizefromstring($data);
        if ($sz && min($sz[0], $sz[1]) <= 6 && max($sz[0], $sz[1]) < 600) return true;
        if ($cx > 0 && $cy > 0 && min($cx, $cy) <= 40000 && max($cx, $cy) < 5400000) return true;   // tampil setebal <= ±3 pt
        return false;
    }

    /** Cari gambar di sebuah potongan XML (paragraf / sel tabel), simpan ke $this->images, kembalikan penanda " [[IMG:n]]" */
    private function tandai_gambar($part, $media, $zip, &$idx)
    {
        // blok cadangan (mc:Fallback) memuat gambar yang sama dalam bentuk VML; abaikan agar tidak ganda
        $scan = preg_replace('/<mc:Fallback>.*?<\/mc:Fallback>/s', '', $part);
        $ids = [];
        if (preg_match_all('/r:embed="([^"]+)"/', $scan, $m)) $ids = $m[1];
        if (preg_match_all('/<v:imagedata[^>]*r:id="([^"]+)"/', $scan, $m2)) $ids = array_merge($ids, $m2[1]);
        $ids = array_values(array_unique($ids));
        // ukuran tampil: ambil wp:extent MILIK drawing yang memuat gambar itu. (Dulu diambil extent pertama di paragraf,
        // sehingga garis/bentuk pecahan di paragraf yang sama membuat gambar soal tampil hanya ±0,2 cm.)
        $tampil = [];
        if (preg_match_all('/<w:drawing>.*?<\/w:drawing>/s', $scan, $dr)) {
            foreach ($dr[0] as $d) {
                if (preg_match('/r:embed="([^"]+)"/', $d, $e) && preg_match('/<wp:extent cx="(\d+)" cy="(\d+)"/', $d, $x)) {
                    $tampil[$e[1]] = [(int)$x[1], (int)$x[2]];
                }
            }
        }
        $marker = '';
        foreach ($ids as $rid) {
            if (!isset($media[$rid])) continue;
            if (!isset($idx[$rid])) {
                $g = $this->muat_gambar($zip, $media[$rid]);
                if (!$g) { $idx[$rid] = 0; }
                else {
                    $cx = $cy = 0;
                    if (isset($tampil[$rid])) { $cx = $tampil[$rid][0]; $cy = $tampil[$rid][1]; }
                    if ($this->gambar_garis($g[1], $cx, $cy)) {
                        // bukan gambar soal: dibuang tanpa penanda (garis pecahan sudah dianggap hilang oleh aturan pecahan di prompt)
                        $this->log[] = "Gambar $media[$rid] dibuang: berupa garis tipis (garis pecahan hasil konversi PDF)";
                        $idx[$rid] = 0;
                        continue;
                    }
                    $sz = @getimagesizefromstring($g[1]);
                    if ($sz) {
                        $asli = $sz[0] * 9525; $tinggi = $sz[1] * 9525;
                        // pakai ukuran tampil dokumen hanya bila rasionya cocok dengan gambar aslinya
                        $cocok = $cx > 0 && $cy > 0 && abs(($cx / $cy) - ($sz[0] / $sz[1])) <= 0.2 * ($sz[0] / $sz[1]);
                        if (!$cocok) {
                            $cx = $asli; $cy = $tinggi;
                            if ($cx > 5400000) { $cy = (int)($cy * 5400000 / $cx); $cx = 5400000; }   // maks ±15 cm
                        }
                    }
                    $this->images[] = ['ext' => $g[0], 'data' => $g[1], 'cx' => $cx ?: 2500000, 'cy' => $cy ?: 2000000];
                    $idx[$rid] = count($this->images);
                }
            }
            if ($idx[$rid]) $marker .= ' [[IMG:' . $idx[$rid] . ']]';
        }
        return $marker;
    }

    /**
     * Docx -> teks. Yang dipertahankan:
     *  - paragraf berdaftar otomatis (nomor otomatis Word) diberi awalan "- "
     *  - tabel diubah menjadi baris teks "a | b | c" (gambar di dalam sel ikut ditandai)
     *  - gambar diganti penanda [[IMG:n]] (gambar disimpan di $this->images)
     */
    private function docx_ke_teks($path)
    {
        $zip = new ZipArchive();
        if ($zip->open($path) !== true) return '';
        $xml  = $zip->getFromName('word/document.xml');
        $rels = $zip->getFromName('word/_rels/document.xml.rels');
        if (!$xml) { $zip->close(); return ''; }

        // rId -> lokasi gambar
        $media = [];
        if ($rels && preg_match_all('/<Relationship\b[^>]*>/', $rels, $m)) {
            foreach ($m[0] as $tag) {
                if (preg_match('/\bId="([^"]+)"/', $tag, $a) && preg_match('/\bTarget="([^"]+)"/', $tag, $b)
                    && strpos($b[1], 'media/') !== false) $media[$a[1]] = $b[1];
            }
        }
        $this->images = []; $this->gambar_dilewati = 0; $idx = [];
        $buang_gambar = '/<w:drawing>.*?<\/w:drawing>|<w:pict>.*?<\/w:pict>/s';

        // 0. Equation Word -> penanda [[EQ:n|latex]] (OMML asli disimpan untuk dipasang kembali)
        $this->equations = [];
        $xml = $this->ganti_persamaan($xml);

        // 1. tabel -> teks (pakai strpos, aman untuk dokumen besar)
        $out = ''; $pos = 0;
        while (($s = strpos($xml, '<w:tbl>', $pos)) !== false) {
            $e = strpos($xml, '</w:tbl>', $s);
            if ($e === false) break;
            $e += 8;
            $rows = [];
            foreach (explode('</w:tr>', substr($xml, $s, $e - $s)) as $tr) {
                if (strpos($tr, '<w:tc>') === false) continue;
                $cells = [];
                foreach (explode('</w:tc>', $tr) as $tc) {
                    if (strpos($tc, '<w:tc>') === false) continue;
                    $gbr = $this->tandai_gambar($tc, $media, $zip, $idx);
                    $tc  = preg_replace($buang_gambar, '', $tc);
                    $cells[] = trim(preg_replace('/\s+/', ' ', strip_tags(str_replace('</w:p>', ' ', $tc))) . $gbr);
                }
                $rows[] = implode(' | ', $cells);
            }
            $besar = count($rows) >= 5;   // tabel panjang = daftar soal (tiap baris satu soal); tabel pendek = bagian dari satu soal
            $tag = $besar ? 'DAFTAR SOAL TABEL' : 'TABEL';
            $out .= substr($xml, $pos, $s - $pos) . '<w:p><w:r><w:t>' . "\n[$tag]\n" . implode("\n", $rows) . "\n[/$tag]" . '</w:t></w:r></w:p>';
            $pos = $e;
        }
        $xml = $out . substr($xml, $pos);

        // 2. per paragraf
        $lines = [];
        foreach (explode('</w:p>', $xml) as $part) {
            $marker = $this->tandai_gambar($part, $media, $zip, $idx);
            $t = preg_replace($buang_gambar, '', $part);   // buang isi gambar (angka posisi dsb)
            $t = str_replace(['<w:tab/>', '<w:br/>', '<w:br w:type="textWrapping"/>'], ["\t", "\n", "\n"], $t);
            $t = html_entity_decode(strip_tags($t), ENT_QUOTES | ENT_XML1, 'UTF-8');
            $isList = strpos($part, '<w:numPr>') !== false && trim($t) !== '';
            // paragraf yang isinya hanya beberapa gambar yang dipisah baris baru/tab (opsi gambar berurutan di satu sel):
            // pisahkan menjadi satu baris per gambar supaya tidak menumpuk di satu opsi
            $jmlMarker = substr_count($marker, '[[IMG:');
            if ($jmlMarker > 1 && trim($t) === '' && preg_match('/<w:br\/>|<w:br w:type="textWrapping"\/>|<w:tab\/>/', $part)) {
                $pertama = true;
                foreach (preg_split('/\s+(?=\[\[IMG:)/', trim($marker)) as $mk) {
                    if ($mk === '') continue;
                    $lines[] = ($pertama && $isList ? '- ' : '') . $mk;
                    $pertama = false;
                }
                continue;
            }
            $line = ($isList ? '- ' : '') . rtrim($t) . $marker;
            if (trim($line) !== '') $lines[] = $line;
        }
        $zip->close();

        return trim(implode("\n", $this->susun_opsi_kolom($lines)));
    }

    /**
     * Opsi 2 kolom ("kiri <tab> c. kanan" lalu "kiri <tab> d. kanan"): label a./b. di kolom kiri hilang (penomoran otomatis Word),
     * jadi susun ulang menjadi 4 baris berlabel eksplisit a./b./c./d. supaya urutan opsi tidak ditebak AI.
     * Juga menangani opsi pecahan hasil konversi PDF, di mana penyebut berdiri sendiri pada baris berikutnya:
     *   "- 5<tab>c. 1" / "3" / "- 3<tab>d. 1" / "5"  ->  a. 5, b. 3, c. $\frac{1}{3}$, d. $\frac{1}{5}$
     */
    private function susun_opsi_kolom(array $lines)
    {
        $jml = count($lines); $baru = [];
        $kiri = function ($x) {
            $x = preg_replace('/^\s*(?:-\s+)?/u', '', (string)$x);
            return trim(preg_replace('/^[aAbB][\.\)]\s+/u', '', $x));
        };
        $tok = '[0-9A-Za-z]{1,3}';
        for ($i = 0; $i < $jml; $i++) {
            // pola pecahan: baris c. + penyebut + baris d. + penyebut
            if ($i + 3 < $jml
                && preg_match('/^(.*?)\t+\s*[cC][\.\)]\s*(' . $tok . ')\s*$/su', $lines[$i], $m1)
                && preg_match('/^\s*(' . $tok . ')\s*$/u', $lines[$i + 1], $d1)
                && preg_match('/^(.*?)\t+\s*[dD][\.\)]\s*(' . $tok . ')\s*$/su', $lines[$i + 2], $m3)
                && preg_match('/^\s*(' . $tok . ')\s*$/u', $lines[$i + 3], $d2)) {
                $baru[] = 'a. ' . $kiri($m1[1]);
                $baru[] = 'b. ' . $kiri($m3[1]);
                $baru[] = 'c. $\\frac{' . $m1[2] . '}{' . $d1[1] . '}$';
                $baru[] = 'd. $\\frac{' . $m3[2] . '}{' . $d2[1] . '}$';
                $i += 3;
                continue;
            }
            if ($i + 1 < $jml
                && preg_match('/^(.*?)\t+\s*[cC][\.\)]\s*(.*)$/su', $lines[$i], $m1)
                && preg_match('/^(.*?)\t+\s*[dD][\.\)]\s*(.*)$/su', $lines[$i + 1], $m2)) {
                $baru[] = 'a. ' . $kiri($m1[1]);
                $baru[] = 'b. ' . $kiri($m2[1]);
                $baru[] = 'c. ' . trim($m1[2]);
                $baru[] = 'd. ' . trim($m2[2]);
                $i++;
                continue;
            }
            $baru[] = $lines[$i];
        }
        return $baru;
    }

    /* ---------------- 2. POTONG PER BATCH ---------------- */

    public function potong($text, $per = 12)
    {
        $parts = preg_split(self::POLA_SPLIT, $text, -1, PREG_SPLIT_NO_EMPTY);
        if (count($parts) < 2) return array_values(array_filter(str_split($text, 6000), 'trim'));
        $chunks = []; $cur = ''; $n = 0;
        foreach ($parts as $p) {
            $cur .= $p; $n++;
            if ($n >= $per || strlen($cur) > 7000) { $chunks[] = $cur; $cur = ''; $n = 0; }
        }
        if (trim($cur) !== '') $chunks[] = $cur;
        return $chunks;
    }

    /* ---------------- 3. PROMPT & SKEMA ---------------- */

    /** $sumber: 'teks' (teks biasa) | 'penanda' (teks docx yang berisi penanda gambar) | 'berkas' (PDF/foto dibaca langsung) */
    private function prompt($tpl, $tebak = false, $sumber = 'teks')
    {
        $kunci_aturan = $tebak
            ? "- Jika kunci jawaban TIDAK ada di sumber, TENTUKAN sendiri jawaban yang paling benar (kunci / jawaban), lalu set \"kunci_ai\": true. Jika kunci ada di sumber, \"kunci_ai\": false.\n"
            : "- Jika kunci jawaban tidak ditemukan di sumber, kosongkan (kunci: [], jawaban: \"\"). JANGAN menebak.\n";
        return "Kamu adalah mesin konversi bank soal ujian menjadi JSON. Ubah SEMUA soal pada sumber berikut.\n"
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
             . "- Jangan mengubah, memperbaiki, atau meringkas isi soal dan opsi. Salin apa adanya (ejaan sumber dipertahankan).\n"
             . "- Jangan menambah soal yang tidak ada dan jangan melewatkan satu pun. Satu soal = satu objek.\n"
             . "- Abaikan judul bagian dan petunjuk pengerjaan (mis. 'Berilah tanda silang...'); itu bukan soal.\n"
             . "- Nomor soal di sumber bisa hilang, ganda, atau berupa tanda '-' (nomor otomatis Word). Anggap tiap pertanyaan baru sebagai satu soal, urut sesuai kemunculan.\n"
             . "- Opsi (a. b. c. d.) sering tertulis SEBARIS dipisah tab/spasi, atau di beberapa baris; pisahkan menjadi opsi sendiri-sendiri.\n"
             . "- Opsi sering tersusun 2 KOLOM dipisah tab: baris pertama berisi opsi A (kiri) dan C (kanan), baris kedua berisi opsi B (kiri) dan D (kanan). Label a./b. di kolom kiri sering HILANG (penomoran otomatis Word), sedangkan label c./d. di kolom kanan tertulis. Jika opsi sudah berlabel eksplisit a./b./c./d. satu per baris, pakai urutan itu. Susun opsi berurutan A,B,C,D: kiri-atas = A, kiri-bawah = B, kanan-atas = C, kanan-bawah = D. Jangan menukar posisi, jangan mengarang isi opsi. Jika isi sebuah opsi hanya penanda [[EQ:n|...]], salin penanda itu apa adanya sebagai isi opsi.\n"
             . "- Hapus label nomor dari 'pertanyaan' dan label huruf (a., b., A.) dari tiap opsi.\n"
             . "- Penanda [[IMG:n]] adalah gambar pada dokumen. Salin penanda itu persis ke akhir 'pertanyaan' soal yang memakai gambar tersebut "
             . "(petunjuk: kalimat seperti 'gambar di samping/berikut'; penanda biasanya berada di dekat soal itu, bisa tepat sebelum atau sesudah). Jangan membuang penanda.\n"
             . "- Blok [TABEL] ... [/TABEL] adalah tabel kecil yang menjadi BAGIAN dari satu soal. SALIN blok itu APA ADANYA ke dalam 'pertanyaan' pada posisi semula, lengkap dengan penanda [TABEL] dan [/TABEL], satu baris tabel per baris (\\n), kolom dipisah ' | '. Jangan diubah ke format lain.\n"
             . "- Blok [DAFTAR SOAL TABEL] ... [/DAFTAR SOAL TABEL] adalah naskah ujian yang disusun dalam tabel: tiap BARIS (selain baris judul kolom) adalah SATU soal sendiri. Kolomnya bisa berisi nomor, soal, opsi, dan kunci. Pecah menjadi soal-soal terpisah, JANGAN menggabungkan seluruh baris menjadi satu soal.\n"
             . $kunci_aturan
             . "- Penanda [[EQ:n|latex]] adalah persamaan matematika (Equation Word) dari dokumen. SALIN penanda itu PERSIS (lengkap dengan isinya) ke posisi semula di 'pertanyaan' atau di teks opsi. "
             . "Baca isi LaTeX-nya untuk memahami soal dan menentukan jawaban, tetapi jangan mengubah atau menulis ulang rumusnya.\n"
             . "\n" . $this->blok_rumus() . "\n"
             . "\n" . $this->blok_template() . "\n"
             . $this->blok_sumber($sumber)
             . "FORMAT KELUARAN: jangan menyertakan penanda sitasi/sumber seperti [cite: 1] atau [cite_start]. Keluarkan HANYA JSON valid: {\"soal\":[{\"tipe\":1,\"pertanyaan\":\"...\",\"opsi\":[\"...\"],\"kunci\":[\"A\"],\"kunci_ai\":false}]}\n\n"
             . "Catatan format sumber: " . $tpl->prompt;
    }

    /* ---- blok instruksi tambahan: rumus & gambar ---- */

    /** Aturan khusus agar hasil cocok dengan Template_.docx (5 tabel: PG, PG Kompleks, Menjodohkan, Isian, Uraian) */
    private function blok_template()
    {
        return <<<'TXT'
ATURAN KHUSUS TEMPLATE (hasil dimasukkan ke 5 tabel menurut 'tipe'):
- Nomor soal diisi otomatis oleh template. JANGAN menulis nomor di 'pertanyaan'. Nomor sumber sering berupa penomoran otomatis Word dan bukan nomor asli.
- Abaikan baris kosong dan karakter tak terlihat. Buang karakter asing/dekoratif yang tidak bermakna.
- Deretan angka panjang tanpa makna (mis. 1026477130772 atau 9147176782) adalah sisa objek gambar/tabel pada konversi. BUANG, jangan disalin ke pertanyaan maupun opsi.
- Huruf matematika bergaya (𝑎 𝑏 𝑥 𝑦) tulis sebagai huruf biasa di dalam $...$, mis. $a$, $b$, $x$.
- Pilihan ganda (tipe 1): 'kunci' selalu array berisi SATU huruf kapital, mis. ["B"]. Soal pilihan ganda hampir selalu punya 4 opsi (A-D). JANGAN pernah mengosongkan atau melewati sebuah opsi: urutan A-D harus utuh. Jika sebuah opsi sulit dibaca, tulis pembacaan terbaik + [CEK]; jika sama sekali tidak ada isinya, tulis hanya [CEK].
- Pilihan ganda kompleks (tipe 2) HANYA jika soal meminta memilih LEBIH DARI SATU jawaban. Soal "pernyataan yang benar adalah..." dengan opsi kombinasi ("1) dan 2)", "1), 2), dan 3)") adalah tipe 1 dengan satu kunci.
- Pernyataan bernomor di dalam soal (1) 2) 3)) tulis di 'pertanyaan', satu per baris: "1) ...\n2) ...\n3) ...".
- Menjodohkan (tipe 3): satu tabel menjodohkan = SATU soal. 'baris' dan 'kolom' berisi teks saja tanpa nomor/huruf. 'pasangan' contoh: [{"baris":1,"kolom":["B"]},{"baris":2,"kolom":["A","C"]}].
- Isian singkat (tipe 4) dan uraian (tipe 5): hanya 'pertanyaan' dan 'jawaban'; 'jawaban' kosong ("") jika tidak ada di sumber.
- Informasi bersama ("Gunakan informasi berikut untuk soal nomor ... dan ...") salin ke 'pertanyaan' SETIAP soal yang memakainya.
- Opsi berupa gambar: tiap opsi A-D diisi satu penanda [[IMG:n]] sesuai urutan kemunculan; jangan ada opsi gambar yang dibiarkan kosong.

PECAHAN PADA SUMBER HASIL KONVERSI PDF KE WORD (PENTING):
Pecahan TIDAK tersimpan sebagai rumus. Pembilang tertulis di dalam baris sebagai angka biasa, garis pecahan hilang, dan PENYEBUT berdiri SENDIRIAN pada baris berikutnya (baris yang hanya berisi angka atau huruf, tanpa kata).
1. Baris penyebut BUKAN opsi dan BUKAN soal baru. DILARANG menjadikannya opsi jawaban, menempelkannya sebagai angka di akhir kalimat (salah: "adalah….5", "sebanyak8 kg"), atau menggabungkannya dengan angka lain (pembilang 1 dengan penyebut 4 adalah $\frac{1}{4}$, bukan 14). Jumlah opsi tetap sesuai jumlah label (a-d).
2. Tulis $\frac{pembilang}{penyebut}$ tepat di posisi pembilangnya. Pembilang adalah angka yang berada di dekat kata penunjuk pecahan ("bagian", "pecahan", "dari", "dikalikan", "dibagi") atau satuan (kg, liter, m), atau angka di ujung baris sebelumnya.
3. Baris penyebut bisa muncul SETELAH satu kalimat penuh atau lebih, bukan tepat setelah angkanya. Cari angka bersatuan/berkata pecahan di kalimat sebelumnya yang belum punya penyebut, lalu pasangkan.
4. Jika baris penyebut berisi beberapa nilai dipisah tab ("6<tab>b"), nilai-nilai itu dipasangkan BERURUTAN kiri ke kanan dengan pecahan pada baris sebelumnya. Huruf juga bisa menjadi penyebut ($\frac{a}{b}$). Pada opsi 2 kolom: nilai pertama = penyebut opsi kiri, nilai kedua = penyebut opsi kanan.
5. Jika kalimat punya beberapa angka dan pembilangnya tidak jelas, UJI dengan hitungan: pilih pembacaan yang hasilnya ADA di antara opsi. Penyebut tidak selalu milik angka terakhir; bisa milik angka pertama.
6. Bilangan campuran: "3 1" lalu penyebut "5" adalah $3\frac{1}{5}$. Dua digit tanpa spasi di atas penyebut ("22" lalu "5") pada opsi bercorak bilangan campuran adalah $2\frac{2}{5}$ (spasi hilang) bila pembilang < penyebut.
7. OPSI: label eksplisit menang. Teks setelah "c." selalu opsi C, setelah "d." selalu opsi D, apa pun posisinya pada baris. Pada opsi 2 kolom, baris atas berisi A (kiri) dan C (kanan), baris bawah B (kiri) dan D (kanan); jadi urutan ke bawah-kanan BUKAN urutan huruf. Buang label ("a.", "d.1") yang menempel di teks opsi.
8. Baris opsi bisa TERSELIP di tengah atau setelah soal lain (mis. satu baris "d. 14 / 5" muncul di antara soal berikutnya). Jika sebuah soal punya kurang dari 4 opsi, cari baris opsi yang tersasar dan tentukan miliknya dengan label huruf yang kosong dan hitungan (hasil hitungan soal itu harus ada di opsi). Jangan mengambil opsi yang sudah dimiliki soal lain.
9. Jangan mengarang angka. Jika sumber tidak lengkap (mis. opsi hanya "4x-" tanpa pembilang/penyebut), tulis yang ada saja, mis. "$4 \times$ [CEK]". Jika masih ragu setelah semua langkah, tulis pembacaan terbaik dan tambahkan " [CEK]".
10. Hitung kunci SETELAH pecahan dibaca benar. Kunci harus salah satu opsi yang benar-benar ada. Jika hasil hitungan tidak ada di opsi, baca ulang pecahannya; jika tetap tidak cocok, kosongkan kunci dan set "kunci_ai": false. Untuk soal yang bergantung gambar, jangan menebak.
Aturan ini adalah PENGECUALIAN dari "salin apa adanya": menyusun ulang pecahan boleh dan wajib, isi soal lainnya tetap disalin apa adanya.

CONTOH BENAR (dari dokumen ini):
- "Pecahan 3 dikalikan dengan 3. ... adalah…." + baris "5"  ->  "Pecahan $\frac{3}{5}$ dikalikan dengan 3. ... adalah….".
- "Hasil dari 15 X 1 adalah….." + baris "3"; opsi "- 5<tab>c. 1", "3", "- 3<tab>d. 1", "5"  ->  "Hasil dari $15 \times \frac{1}{3}$ adalah….."; A "5", B "3", C $\frac{1}{3}$, D $\frac{1}{5}$.
- "Hasil dari 5 : 2 adalah a" + baris "6<tab>b"  ->  "Hasil dari $\frac{5}{6} : 2$ adalah $\frac{a}{b}$" (bukan $5 : \frac{2}{6}$).
- "Bu neni membeli 5 liter minyak ... sama banyak." + baris "4" + "Volume ..." (lima botol)  ->  "membeli $\frac{5}{4}$ liter ..." (5/4 : 5 = 1/4 ada di opsi). Penyebut 4 bukan angka tambahan dan bukan opsi.
- Soal luas persegi panjang 2 x 4/5 dengan opsi: "- 13", "5", "c. 13", "4", "- 12", "3" dan baris tersasar "d. 14", "5"  ->  A $1\frac{3}{5}$, B $1\frac{2}{3}$, C $1\frac{3}{4}$, D $1\frac{4}{5}$ (bukan 13, 5, 13, 4).
- Opsi 2 kolom "a. 1 1/4 <tab> c. 1 1/2" lalu "b. 1 2/5 <tab> d. 1 3/4"  ->  A $1\frac{1}{4}$, B $1\frac{2}{5}$, C $1\frac{1}{2}$, D $1\frac{3}{4}$.
- "Dimas memakai 1 bagian ..." + "3"  ->  $\frac{1}{3}$ bagian. "senilai dengan 1" + "4" + "bagian sisa"  ->  $\frac{1}{4}$ bagian. "Ada 9" + "10" + "liter"  ->  $\frac{9}{10}$ liter.

- "x" yang berarti perkalian ditulis \times, ":" pembagian ditulis ":" atau \div. Lambang ■ ● ▲ adalah variabel soal: salin sebagai karakter Unicode itu sendiri (■ ● ▲), BUKAN LaTeX (\blacksquare, \bullet, \mathbb, \square).
- Sebelum menjawab, hitung jumlah soal di sumber dan pastikan jumlah objek di JSON sama.
TXT;
    }

    private function blok_rumus()
    {
        return <<<'TXT'
PEMBACAAN RUMUS MATEMATIKA / SAINS:
- Jika isi penanda [[EQ:n|...]] kosong, tetap salin PERSIS; jangan mengisinya sendiri.
- Rumus yang TIDAK memakai penanda (tertulis biasa, atau dari PDF/foto) tulis dalam LaTeX di antara $...$.
- Pakai LaTeX hanya untuk rumus sungguhan. Angka biasa, persen, dan satuan sederhana (25 cm, 3 kg, 10%) tetap teks biasa.
- Pola baku: pecahan \frac{a}{b}; pangkat x^{2}; indeks x_{1}; akar \sqrt{x} dan \sqrt[3]{x}; kali \times; bagi \div; titik \cdot; \pm; \leq \geq \neq \approx; derajat ^{\circ}; persen \%; huruf Yunani \pi \theta \alpha \beta \Delta; \sum \int \lim \infty; fungsi \sin \cos \tan \log \ln; vektor \vec{a}.
- Sistem persamaan: \begin{cases} ... \\ ... \end{cases}. Matriks: \begin{pmatrix} a & b \\ c & d \end{pmatrix}.
- Satuan (cm, km, kg, liter, m, jam) ditulis di LUAR tanda $...$, mis. $\frac{3}{4}$ km. DILARANG memakai \text{...} di dalam rumus. Satuan majemuk yang harus di dalam rumus ditulis \mathrm{m/s^{2}}. Rumus kimia: $\mathrm{H_2SO_4}$, ion $\mathrm{Fe^{3+}}$, reaksi dengan \rightarrow.
- DILARANG memakai perintah LaTeX di luar daftar baku di atas, terutama \text, \mathbb, \bullet, \blacksquare, \square, \left, \right, \dfrac, \displaystyle, \, dan \quad. Simbol bentuk ■ ● ▲ ditulis sebagai karakter Unicode-nya langsung.
- Salin setiap simbol sebagaimana tertulis. Jangan menghitung, menyederhanakan, atau mengubah bentuk rumus. Bedakan huruf l, angka 1, huruf O, dan angka 0 dari konteksnya. Pastikan tanda kurung dan akolade seimbang.
- Di dalam JSON, setiap backslash LaTeX ditulis DUA kali (\\frac, \\times), baris baru ditulis \n.
TXT;
    }

    private function blok_gambar_berkas()
    {
        return <<<'TXT'
PEMBACAAN BERKAS PDF / FOTO:
- Baca SEMUA halaman dari awal sampai akhir. Soal yang terpotong di pergantian halaman atau kolom digabung menjadi satu soal.
- Dokumen dua kolom dibaca per kolom: seluruh kolom kiri dulu, baru kolom kanan.
- Foto miring, buram, atau tulisan tangan: baca teliti baris demi baris dan perbesar bagian yang kecil, terutama pecahan bertumpuk pada opsi jawaban. JANGAN menulis [tidak terbaca] dan jangan mengosongkan opsi: tulis pembacaan terbaik lalu tambahkan " [CEK]" tepat di belakang bagian yang ragu. Simbol dan angka yang terbaca jelas (mis. < > = ≤ ≥, 70, 2/9) TIDAK diberi [CEK]. Untuk soal hitungan, pakai hasil hitungan dan kunci jawaban sebagai petunjuk untuk memastikan angka yang ragu. Jangan mengarang bagian yang sama sekali tidak tercetak.
- Tabel yang menjadi bagian soal ditulis dalam blok [TABEL] ... [/TABEL], satu baris tabel per baris, kolom dipisah ' | '.
- Gambar, grafik, diagram, atau peta yang menjadi bagian soal tidak bisa disalin. Tulis di posisinya: [GAMBAR: deskripsi singkat tapi lengkap] (jangan memakai tanda ] di dalam deskripsi), contoh [GAMBAR: grafik garis suhu per jam, sumbu x jam 1-6, sumbu y 20-35 derajat Celsius]. Guru akan memasang gambar aslinya sendiri.
- Jika isi gambar diperlukan untuk menjawab (nilai pada grafik, panjang sisi, label sudut), sertakan angka-angkanya pada deskripsi.
- Opsi jawaban yang berupa gambar: tulis [GAMBAR: deskripsi] sebagai teks opsi tersebut, satu per opsi.
TXT;
    }

    private function blok_gambar_penanda()
    {
        return <<<'TXT'
GAMBAR PADA DOKUMEN INI:
- Gambar hanya terlihat sebagai penanda [[IMG:n]]; kamu tidak melihat isinya. Jangan mengarang isi atau makna gambar.
- Pengecualian aturan penanda: jika penanda berada pada baris opsi jawaban, penanda itu adalah teks opsi tersebut dan tetap di opsi itu, bukan dipindah ke 'pertanyaan'.
- Opsi jawaban berupa gambar: SATU penanda untuk SATU opsi. Jika ada beberapa penanda gambar berurutan setelah soal (mis. 5 penanda = opsi A-E), bagi satu per opsi sesuai urutan; jangan menumpuk dua penanda pada satu opsi.
- Soal yang bergantung pada gambar (mis. "perhatikan gambar") dan kuncinya tidak tertulis di sumber: JANGAN menebak. Kosongkan kunci dan set "kunci_ai": false.
TXT;
    }

    private function blok_sumber($sumber)
    {
        if ($sumber === 'berkas')  return "\n" . $this->blok_gambar_berkas() . "\n";
        if ($sumber === 'penanda') return "\n" . $this->blok_gambar_penanda() . "\n";
        return '';
    }

    private function schema_gemini()
    {
        $str = ['type' => 'STRING'];
        $arrStr = ['type' => 'ARRAY', 'items' => $str];
        return [
            'type' => 'OBJECT',
            'properties' => ['soal' => ['type' => 'ARRAY', 'items' => [
                'type' => 'OBJECT',
                'properties' => [
                    'tipe'       => ['type' => 'INTEGER'],
                    'pertanyaan' => $str,
                    'opsi'       => $arrStr,
                    'kunci'      => $arrStr,
                    'baris'      => $arrStr,
                    'kolom'      => $arrStr,
                    'pasangan'   => ['type' => 'ARRAY', 'items' => [
                        'type' => 'OBJECT',
                        'properties' => ['baris' => ['type' => 'INTEGER'], 'kolom' => $arrStr],
                    ]],
                    'jawaban'    => $str,
                    'kunci_ai'   => ['type' => 'BOOLEAN'],
                ],
                'required' => ['tipe', 'pertanyaan'],
            ]]],
            'required' => ['soal'],
        ];
    }

    /* ---------------- 4. PANGGIL AI (fallback antar provider) ---------------- */

    private function http($url, $headers, $body)
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST => true, CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 180,
            CURLOPT_HTTPHEADER => $headers, CURLOPT_POSTFIELDS => json_encode($body),
        ]);
        $res  = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err  = curl_error($ch);
        curl_close($ch);
        if ($res === false) throw new Exception("cURL: $err");
        if ($code == 429) {
            $this->retry_after = preg_match('/"retryDelay":\s*"(\d+(?:\.\d+)?)s"/', (string)$res, $m) ? (float)$m[1] : null;
            $this->daily = stripos((string)$res, 'PerDay') !== false;
            throw new Exception('HTTP 429: kuota / batas kecepatan terlampaui', 429);
        }
        if ($code >= 400) throw new Exception("HTTP $code: " . substr($res, 0, 300));
        return json_decode($res, true);
    }

    private function panggil($p, $prompt, $text, $binary, $schema = null)
    {
        if ($p->driver === 'gemini') {
            $parts = [['text' => $prompt . ($text !== '' ? "\n\nSUMBER SOAL:\n" . $text : '')]];
            if ($binary) $parts[] = ['inline_data' => ['mime_type' => $binary[0], 'data' => $binary[1]]];
            $url = rtrim($p->base_url, '/') . '/models/' . $p->model . ':generateContent';
            $hdr = ['Content-Type: application/json', 'x-goog-api-key: ' . $p->api_key];
            $cfg = [
                'temperature' => 0,
                'responseMimeType' => 'application/json',
                'responseSchema' => $schema ?: $this->schema_gemini(),
            ];
            if ($this->media_resolution !== '' && $binary) $cfg['mediaResolution'] = $this->media_resolution;
            try {
                $j = $this->http($url, $hdr, ['contents' => [['parts' => $parts]], 'generationConfig' => $cfg]);
            } catch (Exception $e) {
                // model yang tidak mengenal mediaResolution menjawab HTTP 400: ulangi tanpa pengaturan itu
                if (isset($cfg['mediaResolution']) && $e->getCode() != 429 && strpos($e->getMessage(), 'HTTP 400') === 0) {
                    $this->media_resolution = '';
                    unset($cfg['mediaResolution']);
                    $j = $this->http($url, $hdr, ['contents' => [['parts' => $parts]], 'generationConfig' => $cfg]);
                } else throw $e;
            }
            return $j['candidates'][0]['content']['parts'][0]['text'] ?? '';
        }
        if ($binary) throw new Exception('Provider ini hanya mendukung teks');
        $j = $this->http(rtrim($p->base_url, '/') . '/chat/completions',
            ['Content-Type: application/json', 'Authorization: Bearer ' . $p->api_key], [
            'model' => $p->model, 'temperature' => 0,
            'response_format' => ['type' => 'json_object'],
            'messages' => [
                ['role' => 'system', 'content' => $prompt],
                ['role' => 'user', 'content' => "SUMBER SOAL:\n" . $text],
            ],
        ]);
        return $j['choices'][0]['message']['content'] ?? '';
    }

    /** Paket prompt untuk ditempel ke Gemini web / chatbot lain (mode manual, tanpa API key) */
    public function prompt_manual($tebak = true, $catatan = '', $sumber = 'teks')
    {
        return $this->prompt((object)['prompt' => $catatan], $tebak, $sumber);
    }

    public $json_error = '';   // alasan JSON gagal dibaca (untuk pesan error)
    public $media_resolution = '';   // mis. 'MEDIA_RESOLUTION_HIGH': Gemini membaca gambar lebih rinci (pecahan kecil). Kosong = bawaan

    /** Backslash tunggal sebelum perintah LaTeX (\frac, \times, \theta, ...) digandakan agar tidak dibaca JSON sebagai \f, \t, \b, \n, \r */
    private function perbaiki_latex_json($s)
    {
        return preg_replace('/(?<!\\\\)\\\\(?=(?:frac|dfrac|tfrac|times|theta|tau|tan|text|textbf|textit|to|tilde|beta|bar|begin|binom|bigcup|bigcap|boldsymbol|neq|ne|nu|nabla|neg|notin|not|ni|right|rightarrow|rho|rangle|rfloor|rceil|forall|bmatrix|bullet|blacksquare|bigcirc|bigtriangleup|bigstar|backslash|because|blacktriangle|triangle|textrm|textnormal|therefore|top|bot)\b)/', '\\\\\\\\', $s);
    }

    /** Perbaiki JSON "hampir benar" dari chatbot: baris baru mentah, backslash tidak sah, tanda kutip liar di dalam teks, koma berlebih */
    private function perbaiki_json($s)
    {
        $out = ''; $n = strlen($s); $in = false;
        for ($i = 0; $i < $n; $i++) {
            $c = $s[$i];
            if (!$in) { if ($c === '"') $in = true; $out .= $c; continue; }
            if ($c === '\\') {
                $nx = ($i + 1 < $n) ? $s[$i + 1] : '';
                if ($nx !== '' && strpos('"\\/bfnrt', $nx) !== false) { $out .= $c . $nx; $i++; }
                elseif ($nx === 'u' && preg_match('/^[0-9a-fA-F]{4}$/', substr($s, $i + 2, 4))) { $out .= substr($s, $i, 6); $i += 5; }
                else { $out .= '\\\\'; }
                continue;
            }
            if ($c === '"') {
                // kutip penutup hanya jika setelahnya (abaikan spasi) ada , : } ]; selain itu kutip di dalam teks
                $k = $i + 1; while ($k < $n && ctype_space($s[$k])) $k++;
                $nx = ($k < $n) ? $s[$k] : '';
                if ($nx === '' || strpos(',:}]', $nx) !== false) { $in = false; $out .= $c; }
                else $out .= '\\"';
                continue;
            }
            if ($c === "\n") { $out .= '\\n'; continue; }
            if ($c === "\r") continue;
            if ($c === "\t") { $out .= '\\t'; continue; }
            if (ord($c) < 32) { $out .= ' '; continue; }
            $out .= $c;
        }
        return preg_replace('/,\s*([}\]])/', '$1', $out);
    }

    /**
     * Perbaikan JSON versi ketat: kutip dianggap penutup string hanya bila konteks sesudahnya masuk akal secara struktur
     * (":" hanya setelah kunci; "," hanya bila diikuti kunci "xxx": atau elemen larik berikutnya). Kutip lain di dalam teks
     * soal (mis. "kelalaian", katanya) di-escape. Dipakai sebagai cadangan kedua setelah perbaiki_json().
     */
    private function perbaiki_json_ketat($s)
    {
        $out = ''; $n = strlen($s); $in = false; $kunci = false; $stack = [];
        for ($i = 0; $i < $n; $i++) {
            $c = $s[$i];
            if (!$in) {
                if ($c === '"') {
                    $in = true;
                    $top = $stack ? end($stack) : '';
                    $p = rtrim($out); $last = $p === '' ? '' : substr($p, -1);
                    $kunci = ($top === '{' && ($last === '{' || $last === ','));
                } elseif ($c === '{' || $c === '[') { $stack[] = $c; }
                elseif ($c === '}' || $c === ']') { array_pop($stack); }
                $out .= $c; continue;
            }
            if ($c === '\\') {
                $nx = ($i + 1 < $n) ? $s[$i + 1] : '';
                if ($nx !== '' && strpos('"\\/bfnrt', $nx) !== false) { $out .= $c . $nx; $i++; }
                elseif ($nx === 'u' && preg_match('/^[0-9a-fA-F]{4}$/', substr($s, $i + 2, 4))) { $out .= substr($s, $i, 6); $i += 5; }
                else { $out .= '\\\\'; }
                continue;
            }
            if ($c === '"') {
                $k = $i + 1; while ($k < $n && ctype_space($s[$k])) $k++;
                $nx = ($k < $n) ? $s[$k] : '';
                $top = $stack ? end($stack) : '';
                $tutup = false;
                if ($nx === '' || $nx === '}' || $nx === ']') $tutup = true;
                elseif ($nx === ':') $tutup = $kunci;
                elseif ($nx === ',') {
                    $m = $k + 1; while ($m < $n && ctype_space($s[$m])) $m++;
                    $mc = ($m < $n) ? $s[$m] : '';
                    if ($top === '{') $tutup = ($mc === '"' && preg_match('/^"[^"\\\\\n]{1,60}"\s*:/', substr($s, $m, 80)));
                    else $tutup = ($mc !== '' && strpos('"{[-0123456789tfn', $mc) !== false);
                }
                if ($tutup) { $in = false; $out .= $c; } else { $out .= '\\"'; }
                continue;
            }
            if ($c === "\n") { $out .= '\\n'; continue; }
            if ($c === "\r") continue;
            if ($c === "\t") { $out .= '\\t'; continue; }
            if (ord($c) < 32) { $out .= ' '; continue; }
            $out .= $c;
        }
        return preg_replace('/,\s*([}\]])/', '$1', $out);
    }

    /** Terima keluaran AI yang bisa berisi teks pengantar, pagar ```json, penanda sitasi [cite: n], atau JSON yang sedikit rusak */
    public function parse_json($raw)
    {
        $this->json_error = '';
        $raw = trim((string)$raw);
        $raw = trim(preg_replace('/^```(?:json)?|```$/m', '', $raw));
        $raw = preg_replace('/\s*\[cite(?:_start|_end)?(?::[^\]]*)?\]/i', '', $raw);   // sitasi yang ditambahkan Gemini web
        $raw = $this->perbaiki_latex_json($raw);

        $kand = [$raw];
        $a = strpos($raw, '{'); $b = strrpos($raw, '}');
        if ($a !== false && $b > $a) $kand[] = substr($raw, $a, $b - $a + 1);
        $a = strpos($raw, '['); $b = strrpos($raw, ']');
        if ($a !== false && $b > $a) $kand[] = substr($raw, $a, $b - $a + 1);

        $j = null;
        foreach ($kand as $k) { $j = json_decode($k, true); if (is_array($j)) break; }
        if (!is_array($j)) {
            foreach ($kand as $k) { $j = json_decode($this->perbaiki_json($k), true); if (is_array($j)) break; }
        }
        if (!is_array($j)) {
            foreach ($kand as $k) { $j = json_decode($this->perbaiki_json_ketat($k), true); if (is_array($j)) break; }
        }
        if (!is_array($j)) { $this->json_error = function_exists('json_last_error_msg') ? json_last_error_msg() : ''; return null; }
        return isset($j['soal']) ? $j['soal'] : (isset($j[0]) ? $j : null);
    }

    private function konversi_chunk($providers, $prompt, $text, $binary)
    {
        $kena429 = false;
        foreach ($providers as $p) {
            if ($binary && $p->driver !== 'gemini') continue;
            for ($try = 1; $try <= 2; $try++) {
                try {
                    $soal = $this->parse_json($this->panggil($p, $prompt, $text, $binary));
                    if ($soal) { $this->log[] = "OK via {$p->nama}"; return $soal; }
                    $this->log[] = "{$p->nama}: JSON tidak valid (percobaan $try)";
                } catch (Exception $e) {
                    if ($e->getCode() == 429) {
                        $kena429 = true;
                        // batas per-menit: tunggu lalu coba lagi; batas harian: tidak ada gunanya menunggu
                        if (!$this->daily && $this->retry_after !== null && $this->retry_after <= 65 && $this->sleeps < 4 && $try < 2) {
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

    /** Bandingkan jumlah soal yang terdeteksi di sumber dengan hasil AI; jika kurang, pecah batch dan ulangi */
    private function konversi_lengkap($providers, $prompt, $c, $binary, $label, $depth)
    {
        $expected = $binary ? 0 : (int)preg_match_all(self::POLA_SOAL, $c);
        $p2 = $expected > 0 ? $prompt . "\n\nPerkiraan jumlah soal pada sumber ini: sekitar $expected. Kembalikan SEMUA soal, jangan berhenti di soal pertama." : $prompt;
        $hasil = $this->konversi_chunk($providers, $p2, $c, $binary);
        $got = $hasil === null ? 0 : count($hasil);
        $this->log[] = "$label: terdeteksi ±$expected soal di sumber, diterima $got";
        if (!$this->kuota && $expected >= 4 && $got < $expected * 0.8 && $depth < 3) {
            $parts = $this->potong($c, max(2, (int)ceil($expected / 2)));
            if (count($parts) > 1) {
                $this->log[] = "$label: hasil kurang, batch dipecah dan diulang";
                $all = [];
                foreach ($parts as $j => $pt) {
                    $r = $this->konversi_lengkap($providers, $prompt, $pt, $binary, $label . '.' . ($j + 1), $depth + 1);
                    if ($this->kuota) break;
                    if ($r) $all = array_merge($all, $r);
                }
                if (count($all) > $got) return $all;
            }
        }
        return $hasil;
    }

    /* ---------------- 5. PROSES UTAMA ---------------- */

    public function proses($text, $binary, $tpl, $tebak = false)
    {
        $providers = $this->CI->db->where('aktif', 1)->order_by('prioritas')->get('ai_provider')->result();
        if (!$providers) throw new Exception('Belum ada provider AI aktif. Tambahkan di bagian Provider.');

        $this->log[] = 'Mode kunci AI: ' . ($tebak ? 'AKTIF' : 'mati');
        $prompt = $this->prompt($tpl, $tebak, $binary ? 'berkas' : ($this->images ? 'penanda' : 'teks'));
        $semua  = [];
        $chunks = $binary ? [''] : $this->potong($text);
        $this->expected_total = 0;
        if (!$binary) foreach ($chunks as $c0) $this->expected_total += (int)preg_match_all(self::POLA_SOAL, $c0);
        foreach ($chunks as $i => $c) {
            $hasil = $this->konversi_lengkap($providers, $prompt, $c, $binary, 'Batch ' . ($i + 1), 0);
            if ($hasil !== null) $semua = array_merge($semua, $hasil);
            else $this->log[] = 'Batch ' . ($i + 1) . ' GAGAL di semua provider';
            if ($this->kuota) break;     // jangan buang-buang permintaan saat kuota habis
        }
        if ($this->kuota) {
            throw new Exception('Kuota / batas kecepatan Gemini terlampaui sehingga konversi berhenti di tengah (berhasil ' . count($semua)
                . ' dari ±' . $this->expected_total . ' soal) dan berkas TIDAK dibuat agar tidak mengimpor soal yang kurang. '
                . 'Tunggu beberapa menit lalu coba lagi. Jika kuota harian habis, tunggu besok, ganti ke API key / model lain, atau tambah provider cadangan di Pengaturan.');
        }
        $soal = $this->bersihkan($semua);
        if ($tebak && $soal) $soal = $this->lengkapi_kunci($providers, $soal);
        return $soal;
    }

    /* ---------------- 5b. LENGKAPI KUNCI (langkah khusus menjawab soal) ---------------- */

    private function schema_kunci()
    {
        return [
            'type' => 'OBJECT',
            'properties' => ['jawaban' => ['type' => 'ARRAY', 'items' => [
                'type' => 'OBJECT',
                'properties' => ['no' => ['type' => 'INTEGER'], 'kunci' => ['type' => 'ARRAY', 'items' => ['type' => 'STRING']]],
                'required' => ['no', 'kunci'],
            ]]],
            'required' => ['jawaban'],
        ];
    }

    /** Untuk soal PG yang kuncinya masih kosong: minta AI menjawab, per 15 soal */
    private function lengkapi_kunci($providers, array $soal)
    {
        $idx = []; $bergambar = 0;
        foreach ($soal as $i => $s) {
            if (!in_array($s['tipe'], [1, 2], true) || $s['kunci']) continue;
            if (strpos($s['pertanyaan'], '[[IMG:') !== false) { $bergambar++; continue; }   // butuh gambar, tidak bisa dijawab dari teks
            $idx[] = $i;
        }
        if ($bergambar) $this->log[] = "$bergambar soal bergambar dilewati (kunci tidak bisa ditentukan dari teks).";
        $terisi = 0;
        foreach (array_chunk($idx, 15) as $grp) {
            $teks = '';
            foreach ($grp as $n => $i) {
                $teks .= ($n + 1) . '. ' . $soal[$i]['pertanyaan'] . "\n";
                foreach ($soal[$i]['opsi'] as $k => $o) $teks .= chr(65 + $k) . '. ' . $o . "\n";
                $teks .= "\n";
            }
            $prompt = "Kamu adalah guru yang ahli. Tentukan jawaban yang paling benar untuk setiap soal pilihan ganda berikut.\n"
                    . "Berikan satu huruf kapital (A, B, C, ...) pada 'kunci' untuk tiap soal; 'no' sesuai nomor soal di bawah.\n"
                    . "Keluarkan HANYA JSON: {\"jawaban\":[{\"no\":1,\"kunci\":[\"B\"]}]}";
            $hasil = null;
            foreach ($providers as $p) {
                try {
                    $raw = $this->panggil($p, $prompt, $teks, null, $this->schema_kunci());
                    $j = json_decode(trim(preg_replace('/^```(?:json)?|```$/m', '', trim($raw))), true);
                    if (isset($j['jawaban']) && is_array($j['jawaban'])) { $hasil = $j['jawaban']; break; }
                    $this->log[] = "Kunci: {$p->nama} balasan tidak valid";
                } catch (Exception $e) {
                    $this->log[] = "Kunci: {$p->nama}: " . $e->getMessage();
                }
            }
            if ($hasil === null) { $this->log[] = 'Kunci untuk satu kelompok soal gagal ditentukan'; continue; }
            foreach ($hasil as $row) {
                $no = (int)($row['no'] ?? 0);
                if ($no < 1 || !isset($grp[$no - 1])) continue;
                $i = $grp[$no - 1];
                $kunci = [];
                foreach ((array)($row['kunci'] ?? []) as $k) {
                    $h = $this->huruf_valid($k, count($soal[$i]['opsi']));
                    if ($h !== '' && !in_array($h, $kunci, true)) $kunci[] = $h;
                }
                if ($soal[$i]['tipe'] === 1) $kunci = array_slice($kunci, 0, 1);
                if ($kunci) { $soal[$i]['kunci'] = $kunci; $soal[$i]['kunci_ai'] = true; $terisi++; }
            }
        }
        $this->log[] = "Kunci ditentukan AI: $terisi soal";
        return $soal;
    }

    /* ---------------- 7. GAMBAR UNGGAHAN (untuk penanda [GAMBAR: ...] dari PDF/foto) ---------------- */

    /** Jalankan $fn($teks, $no_soal, $nama_field) pada setiap teks soal (urutan tetap), tulis balik hasilnya */
    private function telusur(array $list, $fn)
    {
        foreach ($list as $i => $s) {
            if (!is_array($s)) continue;
            foreach (['pertanyaan', 'jawaban'] as $f) {
                if (isset($s[$f]) && is_string($s[$f])) $list[$i][$f] = call_user_func($fn, $s[$f], $i + 1, $f);
            }
            foreach (['opsi', 'baris', 'kolom'] as $f) {
                if (!isset($s[$f]) || !is_array($s[$f])) continue;
                foreach ($s[$f] as $j => $o) {
                    if (is_string($o)) $list[$i][$f][$j] = call_user_func($fn, $o, $i + 1, $f);
                }
            }
        }
        return $list;
    }

    /** Daftar tempat "[GAMBAR: ...]" pada hasil AI, urut kemunculan: [['no'=>nomor soal, 'ket'=>deskripsi], ...] */
    public function tempat_gambar(array $list)
    {
        $out = [];
        $this->telusur($list, function ($t, $no, $f) use (&$out) {
            if (preg_match_all('/\[GAMBAR:\s*([^\]]*)\]/u', $t, $m)) {
                foreach ($m[1] as $ket) $out[] = ['no' => $no, 'ket' => trim($ket)];
            }
            return $t;
        });
        return $out;
    }

    /** Simpan satu berkas gambar unggahan ke $this->images. Mengembalikan nomor gambar (1..n) atau 0 jika gagal. */
    public function tambah_gambar_file($path)
    {
        $data = @file_get_contents($path);
        $sz = $data ? @getimagesizefromstring($data) : false;
        if (!$sz) { $this->log[] = 'Gambar unggahan tidak terbaca (bukan berkas gambar).'; return 0; }
        $tipe = [IMAGETYPE_JPEG => 'jpg', IMAGETYPE_PNG => 'png', IMAGETYPE_GIF => 'gif'];
        if (isset($tipe[$sz[2]])) {
            $ext = $tipe[$sz[2]];
        } else {
            $png = $this->ke_png($data, $sz[2] == IMAGETYPE_WEBP ? 'webp' : 'bmp');
            if ($png === null) { $this->log[] = 'Gambar unggahan dilewati: format tidak didukung (pakai PNG atau JPG).'; return 0; }
            $data = $png; $ext = 'png'; $sz = @getimagesizefromstring($data) ?: $sz;
        }
        $cx = $sz[0] * 9525; $cy = $sz[1] * 9525;
        if ($cx > 4000000) { $cy = (int)($cy * 4000000 / $cx); $cx = 4000000; }   // maks ±11 cm
        $this->images[] = ['ext' => $ext, 'data' => $data, 'cx' => $cx ?: 2500000, 'cy' => $cy ?: 2000000];
        return count($this->images);
    }

    /**
     * Ganti penanda [GAMBAR: ...] ke-k dengan [[IMG:n]] memakai berkas yang diunggah.
     * $unggah: [urutan tempat => ['tmp' => path]]. Pada 'pertanyaan' penanda dipindah ke akhir pertanyaan; pada opsi tetap di tempatnya.
     */
    public function pasang_gambar(array $list, array $unggah)
    {
        $k = 0;
        $self = $this;
        return $this->telusur($list, function ($t, $no, $f) use (&$k, $unggah, $self) {
            $mk = '';
            $hasil = preg_replace_callback('/\[GAMBAR:\s*([^\]]*)\]/u', function ($m) use (&$k, &$mk, $unggah, $self, $f) {
                $idx = $k++;
                if (empty($unggah[$idx])) return $m[0];
                $n = $self->tambah_gambar_file($unggah[$idx]['tmp']);
                if (!$n) return $m[0];
                if ($f === 'pertanyaan') { $mk .= ' [[IMG:' . $n . ']]'; return ''; }
                return '[[IMG:' . $n . ']]';
            }, $t);
            if ($mk !== '') $hasil = trim(preg_replace("/\n{3,}/", "\n\n", $hasil)) . $mk;
            return $hasil;
        });
    }

    /* ---------------- 6. VALIDASI & NORMALISASI ---------------- */

    private function huruf_valid($k, $n)
    {
        $k = strtoupper(trim((string)$k));
        return ($k !== '' && ord($k[0]) >= 65 && ord($k[0]) < 65 + $n && strlen($k) === 1) ? $k : '';
    }

    private function bersih_opsi($opsi)
    {
        // Posisi opsi dipertahankan: opsi kosong di TENGAH tidak boleh dibuang (kalau dibuang, opsi sesudahnya bergeser naik
        // dan huruf kunci jadi salah). Opsi kosong di ujung dibuang; yang di tengah diberi penanda [CEK] agar guru bisa melengkapi.
        $opsi = array_values(array_map('trim', array_map('strval', (array)$opsi)));
        while ($opsi && end($opsi) === '') array_pop($opsi);
        foreach ($opsi as $k => $o) { if ($o === '') $opsi[$k] = '[CEK]'; }
        // opsi yang isinya hanya beberapa penanda gambar sekaligus (mis. "[[IMG:7]] [[IMG:8]]") = beberapa opsi gambar yang tertumpuk; pecah
        $pecah = [];
        foreach ($opsi as $o) {
            if (preg_match('/^(?:\[\[IMG:\d+\]\]\s*){2,}$/', $o) && preg_match_all('/\[\[IMG:\d+\]\]/', $o, $mm)) {
                foreach ($mm[0] as $mk) $pecah[] = $mk;
            } else $pecah[] = $o;
        }
        $opsi = $pecah;
        return array_map(function ($o) { return preg_replace('/^[A-Ja-j][\.\)]\s+/', '', $o); }, array_slice($opsi, 0, 10));
    }

    /* ---------------- 6a. PEMBERSIH LaTeX LIAR ---------------- */

    /**
     * AI sering menulis perintah LaTeX yang tidak dikenal pengubah rumus Word, mis. \text{ km}, \mathbb{\pi}, \bullet.
     * Hasilnya di Word / bank soal tampil sebagai kata mentah ("text km", "mathbbπ", "bullet").
     * Fungsi ini menggantinya dengan karakter / teks biasa. Penanda [[EQ:..]] dan [[IMG:..]] tidak disentuh.
     */
    public function normalisasi_latex($t)
    {
        if (!is_string($t) || strpos($t, '\\') === false) return $t;
        $bagian = preg_split('/(\[\[(?:EQ|IMG):\d+.*?\]\])/su', $t, -1, PREG_SPLIT_DELIM_CAPTURE);
        foreach ($bagian as $i => $b) {
            if ($i % 2 === 1) continue;   // penanda: biarkan apa adanya
            $bagian[$i] = $this->normalisasi_latex_bagian($b);
        }
        return implode('', $bagian);
    }

    private function normalisasi_latex_bagian($t)
    {
        // 1. simbol bentuk -> karakter Unicode (\mathbb{\pi} adalah salah-baca AI untuk kotak ■)
        $t = preg_replace('/\\\\mathbb\s*\{\s*(?:\\\\pi|π)\s*\}/u', '■', $t);
        $t = preg_replace_callback('/\\\\mathbb\s*\{\s*([^{}]*?)\s*\}/u', function ($m) {
            $h = ['N' => 'ℕ', 'Z' => 'ℤ', 'Q' => 'ℚ', 'R' => 'ℝ', 'C' => 'ℂ'];
            return isset($h[$m[1]]) ? $h[$m[1]] : $m[1];
        }, $t);
        $bentuk = ['blacksquare' => '■', 'square' => '■', 'Box' => '■', 'bullet' => '●', 'bigcirc' => '○',
                   'blacktriangle' => '▲', 'triangle' => '△', 'bigstar' => '★', 'star' => '★'];
        $t = preg_replace_callback('/\\\\(blacksquare|square|Box|bullet|bigcirc|blacktriangle|triangle|bigstar|star)(?![A-Za-z])/',
            function ($m) use ($bentuk) { return $bentuk[$m[1]]; }, $t);

        // 2. \text{...} di dalam $...$ : satuan di ujung rumus dipindah ke luar, sisanya jadi teks biasa
        $t = preg_replace_callback('/\$([^$]+)\$/u', function ($m) {
            $in = $m[1]; $luar = '';
            if (preg_match('/^(.*?)\s*\\\\(?:text|textrm|textnormal|mbox)\s*\{\s*([^{}]*?)\s*\}\s*(?:\^\s*\{?\s*([23])\s*\}?)?\s*$/su', $in, $x)) {
                $sup = (isset($x[3]) && $x[3] !== '') ? ($x[3] === '2' ? '²' : '³') : '';
                $luar = ' ' . trim($x[2]) . $sup;
                $in = $x[1];
            }
            $in = preg_replace_callback('/\\\\(?:text|textrm|textnormal|mbox|textbf|textit)\s*\{([^{}]*)\}/u', function ($y) { return $y[1]; }, $in);
            $in = trim($in);
            return ($in === '' ? trim($luar) : '$' . $in . '$' . $luar);
        }, $t);
        // \text{...} yang tertulis di luar $...$
        $t = preg_replace_callback('/\\\\(?:text|textrm|textnormal|mbox|textbf|textit)\s*\{([^{}]*)\}/u', function ($y) { return $y[1]; }, $t);

        // 3. perintah hias yang tidak dikenal
        $t = preg_replace('/\\\\(?:dfrac|tfrac)(?![A-Za-z])/', '\\\\frac', $t);
        $t = preg_replace('/\\\\(?:left|right)\s*\./', '', $t);
        $t = preg_replace('/\\\\(?:left|right)(?![A-Za-z])\s*/', '', $t);
        $t = preg_replace('/\\\\(?:displaystyle|textstyle)(?![A-Za-z])\s*/', '', $t);
        $t = preg_replace('/\\\\(?:qquad|quad)(?![A-Za-z])|\\\\[,;:!]/', ' ', $t);
        return $t;
    }

    public function bersihkan($list)
    {
        $self = $this;
        $list = $this->telusur((array)$list, function ($t) use ($self) { return $self->normalisasi_latex($t); });
        $out = [];
        foreach ($list as $s) {
            $tipe = (int)($s['tipe'] ?? 0);
            $q = trim((string)($s['pertanyaan'] ?? ''));
            $q = preg_replace('/^\d{1,3}\s*[\.\)]\s+/', '', $q);
            if ($q === '' || $tipe < 1 || $tipe > 5) continue;

            if ($tipe === 1 || $tipe === 2) {
                $opsi = $this->bersih_opsi($s['opsi'] ?? []);
                if (count($opsi) < 2) continue;
                $kunci = [];
                foreach ((array)($s['kunci'] ?? []) as $k) {
                    $h = $this->huruf_valid($k, count($opsi));
                    if ($h !== '' && !in_array($h, $kunci, true)) $kunci[] = $h;
                }
                if ($tipe === 1) $kunci = array_slice($kunci, 0, 1);
                $out[] = ['tipe' => $tipe, 'pertanyaan' => $q, 'opsi' => $opsi, 'kunci' => $kunci, 'kunci_ai' => !empty($s['kunci_ai']) && $kunci];
            } elseif ($tipe === 3) {
                $baris = array_values(array_filter(array_map('trim', (array)($s['baris'] ?? [])), 'strlen'));
                $kolom = array_values(array_filter(array_map('trim', (array)($s['kolom'] ?? [])), 'strlen'));
                if (!$baris || count($kolom) < 2) continue;
                $pas = [];
                foreach ((array)($s['pasangan'] ?? []) as $p) {
                    $b = (int)($p['baris'] ?? 0);
                    if ($b < 1 || $b > count($baris)) continue;
                    $hs = [];
                    foreach ((array)($p['kolom'] ?? []) as $k) {
                        $h = $this->huruf_valid($k, count($kolom));
                        if ($h !== '') $hs[] = $h;
                    }
                    if ($hs) $pas[(string)$b] = implode(',', array_unique($hs));
                }
                $out[] = ['tipe' => 3, 'pertanyaan' => $q, 'baris' => $baris, 'kolom' => $kolom, 'pasangan' => $pas, 'kunci_ai' => !empty($s['kunci_ai']) && $pas];
            } else {
                $jw = trim((string)($s['jawaban'] ?? ''));
                $out[] = ['tipe' => $tipe, 'pertanyaan' => $q, 'jawaban' => $jw, 'kunci_ai' => !empty($s['kunci_ai']) && $jw !== ''];
            }
        }
        return $out;
    }
}