<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Simpan di: application/libraries/Docx_template.php
 * Mengisi Template_.docx (5 tabel: PG, PG Kompleks, Menjodohkan, Isian Singkat, Uraian)
 * tanpa mengubah format tabel bawaan template.
 *
 * Letakkan Template_.docx di: application/third_party/Template_.docx  (atau atur path di generate())
 */
class Docx_template
{
    const NBSP = "\xC2\xA0";
    private $L = 'ABCDEFGHIJ';
    private $images = [];     // dari Ai_converter->images (indeks mulai 0 = penanda [[IMG:1]])
    private $used = [];       // nomor gambar yang benar-benar dipakai
    private $docprId = 5000;
    private $equations = [];  // dari Ai_converter->equations (OMML asli; indeks mulai 0 = penanda [[EQ:1|...]])

    /** $soal: array item, lihat Ai_converter::bersihkan(). Mengembalikan true jika berhasil. */
    public function generate($tplPath, array $soal, $outPath, array $images = [], array $equations = [])
    {
        $this->images = array_values($images); $this->used = [];
        $this->equations = array_values($equations);
        if (!class_exists('ZipArchive')) throw new Exception('Ekstensi PHP zip belum aktif (php.ini: extension=zip).');
        if (!is_file($tplPath)) throw new Exception('Template tidak ditemukan: ' . $tplPath);
        if (!copy($tplPath, $outPath)) throw new Exception('Gagal membuat berkas keluaran.');

        $zip = new ZipArchive();
        if ($zip->open($outPath) !== true) throw new Exception('Gagal membuka template.');
        $doc = $zip->getFromName('word/document.xml');
        // hindari ID paragraf ganda akibat penggandaan baris
        $doc = preg_replace('/\s+w14:(paraId|textId)="[^"]*"/', '', $doc);

        $tables = $this->find_all($doc, ['<w:tbl>'], '</w:tbl>');
        if (count($tables) !== 5) throw new Exception('Template harus punya 5 tabel, ditemukan ' . count($tables));

        $by = [1 => [], 2 => [], 3 => [], 4 => [], 5 => []];
        foreach ($soal as $it) { $by[(int)$it['tipe']][] = $it; }

        // proses dari tabel terakhir agar posisi tidak bergeser
        for ($i = 4; $i >= 0; $i--) {
            list($s, $e) = $tables[$i];
            $t = substr($doc, $s, $e - $s);
            switch ($i) {
                case 0: $n = $this->build_pg($t, $by[1]); break;
                case 1: $n = $this->build_pgk($t, $by[2]); break;
                case 2: $n = $this->build_jodoh($t, $by[3]); break;
                case 3: $n = $this->build_simple($t, $by[4], 4); break;
                default: $n = $this->build_simple($t, $by[5], 5);
            }
            $doc = substr($doc, 0, $s) . $n . substr($doc, $e);
        }
        $zip->addFromString('word/document.xml', $doc);
        $this->tulis_gambar($zip);
        $zip->close();
        return true;
    }

    /* ---------- util XML (pakai strpos, bukan regex, agar aman untuk dokumen besar) ---------- */

    private function find_all($s, array $opens, $close)
    {
        $res = []; $pos = 0;
        while (true) {
            $best = false;
            foreach ($opens as $o) {
                $p = strpos($s, $o, $pos);
                if ($p !== false && ($best === false || $p < $best)) $best = $p;
            }
            if ($best === false) break;
            $e = strpos($s, $close, $best);
            if ($e === false) break;
            $e += strlen($close);
            $res[] = [$best, $e];
            $pos = $e;
        }
        return $res;
    }

    private function slices($s, array $opens, $close)
    {
        $out = [];
        foreach ($this->find_all($s, $opens, $close) as $r) $out[] = substr($s, $r[0], $r[1] - $r[0]);
        return $out;
    }

    /** Isi sel: teks biasa, penanda gambar [[IMG:n]], dan blok [TABEL]...[/TABEL] (jadi tabel Word sungguhan) */
    private function set_cell($tc, $text)
    {
        $a = strpos($tc, '<w:p '); $b = strpos($tc, '<w:p>');
        if ($a === false || ($b !== false && $b < $a)) $a = $b;
        if ($a === false) return $tc;
        $last = strrpos($tc, '</w:p>') + 6;
        $p = substr($tc, $a, $last - $a);
        preg_match('/^<w:p[^>]*>/', $p, $m);
        $open = $m[0];
        $ppr = preg_match('/<w:pPr>.*?<\/w:pPr>/s', $p, $m) ? $m[0] : '';
        $rpr = ($ppr !== '' && preg_match('/<w:rPr>.*?<\/w:rPr>/s', $ppr, $m)) ? $m[0] : '';

        $blocks = ''; $endsWithTable = false;
        $pieces = preg_split('/\[TABEL\]\s*\n(.*?)\n?\s*\[\/TABEL\]/s', (string)$text, -1, PREG_SPLIT_DELIM_CAPTURE);
        foreach ($pieces as $i => $piece) {
            if ($i % 2 === 1) {                       // isi tabel
                $tbl = $this->tabel_word($piece, $rpr);
                if ($tbl !== '') { $blocks .= $tbl; $endsWithTable = true; continue; }
                continue;
            }
            $piece = trim($piece, "\n");
            if ($piece === '' && $i > 0) continue;
            $blocks .= $open . $ppr . $this->runs_teks($piece, $rpr) . '</w:p>';
            $endsWithTable = false;
        }
        if ($blocks === '' || $endsWithTable) $blocks .= $open . $ppr . '</w:p>';   // sel wajib diakhiri paragraf
        return substr($tc, 0, $a) . $blocks . substr($tc, $last);
    }

    /** Teks (baris baru, penanda gambar [[IMG:n]], persamaan [[EQ:n|latex]], dan LaTeX $...$) -> deretan <w:r> / <m:oMath> */
    private function runs_teks($text, $rpr)
    {
        $runs = '';
        if ((string)$text === '') return '';
        $segs = preg_split('/(\[\[IMG:\d+\]\]|\[\[EQ:\d+\|.*?\]\])/s', (string)$text, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        foreach ($segs as $seg) {
            if (preg_match('/^\[\[IMG:(\d+)\]\]$/', $seg, $mm)) {
                $d = $this->drawing_run((int)$mm[1]);
                if ($d !== '') $runs .= ($runs !== '' ? '<w:r>' . $rpr . '<w:br/></w:r>' : '') . $d;
                continue;
            }
            if (preg_match('/^\[\[EQ:(\d+)\|(.*)\]\]$/s', $seg, $mm)) {
                $runs .= $this->eq_xml((int)$mm[1], $mm[2]);
                continue;
            }
            foreach (explode("\n", $seg) as $i => $part) {
                if ($i) $runs .= '<w:r>' . $rpr . '<w:br/></w:r>';
                if ($part === '') continue;
                $runs .= $this->teks_latex($part, $rpr);
            }
        }
        return $runs;
    }

    /* ---------- persamaan ---------- */

    /** Penanda [[EQ:n|...]] -> OMML asli dari dokumen sumber; jika tidak ada, coba bangun dari LaTeX di dalam penanda */
    private function eq_xml($n, $latex)
    {
        if (isset($this->equations[$n - 1]) && $this->equations[$n - 1] !== '') return $this->equations[$n - 1];
        $latex = trim((string)$latex);
        return $latex === '' ? '' : '<m:oMath>' . $this->latex_omml($latex) . '</m:oMath>';
    }

    /** Teks biasa yang mungkin mengandung $...$ (LaTeX), atau LaTeX tanpa $ (mis. opsi "1\frac{3}{5}") -> <w:r> + <m:oMath> */
    private function teks_latex($part, $rpr)
    {
        $parts = explode('$', $part);
        if (count($parts) >= 3 && count($parts) % 2 === 1) {
            $out = '';
            foreach ($parts as $k => $p) {
                if ($p === '') continue;
                $out .= ($k % 2 === 0) ? $this->run_polos($p, $rpr) : '<m:oMath>' . $this->latex_omml($p) . '</m:oMath>';
            }
            return $out;
        }
        if (!preg_match('/\\\\(?:frac|times|div|cdot|geq|ge|leq|le|neq|pm|dots|ldots|sqrt)/', $part)) return $this->run_polos($part, $rpr);
        return $this->latex_tanpa_dolar($part, $rpr);
    }

    /** LaTeX tanpa tanda $: kata berisi perintah LaTeX (+ angka/operator di sebelahnya) dijadikan persamaan, sisanya teks biasa */
    private function latex_tanpa_dolar($t, $rpr)
    {
        $toks = preg_split('/(\s+)/', $t, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        $n = count($toks);
        $isSpace = function ($x) { return (bool)preg_match('/^\s+$/', $x); };
        $words = [];
        foreach ($toks as $i => $x) { if (!$isSpace($x)) $words[] = $i; }
        $math = [];
        foreach ($words as $i) {
            if (strpos($toks[$i], '\\') !== false && preg_match('/\\\\(?:frac|times|div|cdot|geq|ge|leq|le|neq|pm|dots|ldots|sqrt)/', $toks[$i])) $math[$i] = true;
        }
        for ($pass = 0; $pass < 3; $pass++) {
            foreach ($words as $k => $i) {
                if (isset($math[$i]) || !preg_match('/^[0-9]+(?:[.,][0-9]+)?$|^[=+\-:]$/', $toks[$i])) continue;
                if (($k > 0 && isset($math[$words[$k - 1]])) || (isset($words[$k + 1]) && isset($math[$words[$k + 1]]))) $math[$i] = true;
            }
        }
        $out = ''; $buf = ''; $mode = 't';
        $flush = function () use (&$out, &$buf, &$mode, $rpr) {
            if ($buf === '') return;
            $out .= ($mode === 'm') ? '<m:oMath>' . $this->latex_omml(trim($buf)) . '</m:oMath>' : $this->run_polos($buf, $rpr);
            $buf = '';
        };
        foreach ($toks as $i => $x) {
            if ($isSpace($x)) {
                $nx = null;
                for ($j = $i + 1; $j < $n; $j++) { if (!$isSpace($toks[$j])) { $nx = $j; break; } }
                if (!($mode === 'm' && $nx !== null && isset($math[$nx]))) { $flush(); $mode = 't'; }
                $buf .= $x;
                continue;
            }
            $kind = isset($math[$i]) ? 'm' : 't';
            if ($kind !== $mode) { $flush(); $mode = $kind; }
            $buf .= $x;
        }
        $flush();
        return $out;
    }

    private function run_polos($t, $rpr)
    {
        return '<w:r>' . $rpr . '<w:t xml:space="preserve">' . htmlspecialchars($t, ENT_XML1 | ENT_QUOTES, 'UTF-8') . '</w:t></w:r>';
    }

    private function m_run($t, $plain)
    {
        if ($t === '') return '';
        $sty = $plain ? '<m:rPr><m:sty m:val="p"/></m:rPr>' : '';
        return '<m:r>' . $sty . '<w:rPr><w:rFonts w:ascii="Cambria Math" w:hAnsi="Cambria Math"/><w:sz w:val="22"/><w:szCs w:val="22"/></w:rPr>'
             . '<m:t xml:space="preserve">' . htmlspecialchars($t, ENT_XML1 | ENT_NOQUOTES, 'UTF-8') . '</m:t></m:r>';
    }

    private function latex_grup($s, $i)
    {
        $n = strlen($s); $depth = 0;
        for ($j = $i; $j < $n; $j++) {
            if ($s[$j] === '{') $depth++;
            elseif ($s[$j] === '}') { $depth--; if ($depth === 0) return [substr($s, $i + 1, $j - $i - 1), $j + 1]; }
        }
        return [substr($s, $i + 1), $n];
    }

    /** LaTeX sederhana (\frac, \times, \div, \cdot, \dots, \ge, \le, \neq, \pm, ^{..}, _{..}, \sqrt{..}) -> isi <m:oMath> */
    private function latex_omml($s)
    {
        $sym = ['times' => '×', 'div' => '÷', 'cdot' => '·', 'dots' => '…', 'ldots' => '…', 'ge' => '≥', 'geq' => '≥',
                'le' => '≤', 'leq' => '≤', 'neq' => '≠', 'pm' => '±', 'pi' => 'π', 'theta' => 'θ', 'alpha' => 'α', 'beta' => 'β',
                'infty' => '∞', 'approx' => '≈', 'rightarrow' => '→', 'circ' => '°',
                // simbol variabel soal (kotak/bulatan/segitiga)
                'blacksquare' => '■', 'square' => '□', 'Box' => '□', 'bullet' => '●', 'bigcirc' => '○', 'circle' => '○',
                'triangle' => '△', 'blacktriangle' => '▲', 'triangleup' => '△', 'star' => '★', 'diamond' => '◇',
                'angle' => '∠', 'degree' => '°', 'cdots' => '⋯', 'leftarrow' => '←', 'Rightarrow' => '⇒'];
        // perintah LaTeX yang hanya membungkus teks: ambil isi {..}, nama perintahnya jangan ditulis
        $bungkus = ['text', 'mathrm', 'mathbf', 'mathit', 'mathbb', 'mathcal', 'textbf', 'textit', 'operatorname', 'mbox'];
        // perintah tata letak yang diabaikan (isi dalam {..} tetap dipakai)
        $abaikan = ['underline', 'overline', 'left', 'right', 'displaystyle', 'quad', 'qquad', 'limits', 'boxed', 'mathstrut'];
        $out = ''; $buf = ''; $i = 0; $n = strlen($s);
        $flush = function () use (&$buf, &$out) {
            if ($buf === '') return;
            preg_match_all('/[A-Za-z]{2,}|./us', $buf, $m);
            foreach ($m[0] as $t) {
                $plain = (mb_strlen($t, 'UTF-8') > 1) || !preg_match('/^[A-Za-z]$/', $t);
                $out .= $this->m_run($t, $plain);
            }
            $buf = '';
        };
        while ($i < $n) {
            $c = $s[$i];
            if ($c === '\\') {
                if (preg_match('/^\\\\([A-Za-z]+|%)/', substr($s, $i), $mm)) {
                    $cmd = $mm[1]; $i += strlen($mm[0]);
                    if ($cmd === 'frac') {
                        while ($i < $n && $s[$i] === ' ') $i++;
                        list($a, $i) = $this->latex_grup($s, $i);
                        while ($i < $n && $s[$i] === ' ') $i++;
                        list($b, $i) = $this->latex_grup($s, $i);
                        $flush();
                        $out .= '<m:f><m:num>' . $this->latex_omml($a) . '</m:num><m:den>' . $this->latex_omml($b) . '</m:den></m:f>';
                    } elseif ($cmd === 'sqrt') {
                        while ($i < $n && $s[$i] === ' ') $i++;
                        $flush();
                        if ($i < $n && $s[$i] === '{') {
                            list($a, $i) = $this->latex_grup($s, $i);
                            $out .= '<m:rad><m:radPr><m:degHide m:val="1"/></m:radPr><m:deg/><m:e>' . $this->latex_omml($a) . '</m:e></m:rad>';
                        }
                    } elseif ($cmd === '%') {
                        $buf .= '%';
                    } elseif (isset($sym[$cmd])) {
                        $buf .= $sym[$cmd];
                    } elseif (in_array($cmd, $bungkus, true)) {
                        while ($i < $n && $s[$i] === ' ') $i++;
                        if ($i < $n && $s[$i] === '{') { list($a, $i) = $this->latex_grup($s, $i); $flush(); $out .= $this->latex_omml($a); }
                    } elseif (in_array($cmd, $abaikan, true)) {
                        // tidak menulis apa pun
                    } else {
                        $buf .= $cmd;   // \sin, \cos, \log, dst -> tulis namanya
                    }
                    continue;
                }
                $i++; continue;
            }
            if ($c === '^' || $c === '_') {
                $i++;
                if ($i < $n && $s[$i] === '{') { list($a, $i) = $this->latex_grup($s, $i); }
                else { $a = $i < $n ? $s[$i] : ''; $i++; }
                // basis = karakter terakhir di buffer (atau persamaan sebelumnya jika buffer kosong)
                $base = '';
                if ($buf !== '') { $base = $this->m_run(mb_substr($buf, -1, 1, 'UTF-8'), false); $buf = mb_substr($buf, 0, mb_strlen($buf, 'UTF-8') - 1, 'UTF-8'); }
                $flush();
                $tag = ($c === '^') ? 'sSup' : 'sSub'; $arg = ($c === '^') ? 'sup' : 'sub';
                $out .= '<m:' . $tag . '><m:e>' . $base . '</m:e><m:' . $arg . '>' . $this->latex_omml($a) . '</m:' . $arg . '></m:' . $tag . '>';
                continue;
            }
            if ($c === '{' || $c === '}') { $i++; continue; }
            $len = 1; $o = ord($c);
            if ($o >= 0xF0) $len = 4; elseif ($o >= 0xE0) $len = 3; elseif ($o >= 0xC0) $len = 2;
            $buf .= substr($s, $i, $len); $i += $len;
        }
        $flush();
        return $out;
    }

    /** "a | b | c" per baris -> tabel Word (baris pertama dicetak tebal sebagai judul kolom) */
    private function tabel_word($raw, $rpr)
    {
        $rows = [];
        foreach (preg_split('/\R/', trim($raw)) as $line) {
            if (trim($line) === '') continue;
            $rows[] = array_map('trim', explode('|', $line));
        }
        if (!$rows) return '';
        $nc = max(array_map('count', $rows));
        $w = (int)floor(6200 / max($nc, 1));
        $bd = '';
        foreach (['top', 'left', 'bottom', 'right', 'insideH', 'insideV'] as $e) {
            $bd .= '<w:' . $e . ' w:val="single" w:sz="4" w:space="0" w:color="000000"/>';
        }
        $x = '<w:tbl><w:tblPr><w:tblW w:w="0" w:type="auto"/><w:tblBorders>' . $bd . '</w:tblBorders>'
           . '<w:tblLayout w:type="autofit"/><w:tblCellMar><w:left w:w="70" w:type="dxa"/><w:right w:w="70" w:type="dxa"/></w:tblCellMar></w:tblPr><w:tblGrid>';
        for ($i = 0; $i < $nc; $i++) $x .= '<w:gridCol w:w="' . $w . '"/>';
        $x .= '</w:tblGrid>';
        $rprB = $rpr !== '' ? preg_replace('/^<w:rPr>/', '<w:rPr><w:b/>', $rpr) : '<w:rPr><w:b/></w:rPr>';
        foreach ($rows as $ri => $cells) {
            $x .= '<w:tr>';
            for ($i = 0; $i < $nc; $i++) {
                $t = $cells[$i] ?? '';
                $r = $t === '' ? '' : '<w:r>' . ($ri === 0 ? $rprB : $rpr) . '<w:t xml:space="preserve">'
                   . htmlspecialchars($t, ENT_XML1 | ENT_QUOTES, 'UTF-8') . '</w:t></w:r>';
                $x .= '<w:tc><w:tcPr><w:tcW w:w="' . $w . '" w:type="dxa"/></w:tcPr>'
                    . '<w:p><w:pPr><w:spacing w:before="0" w:after="0"/></w:pPr>' . $r . '</w:p></w:tc>';
            }
            $x .= '</w:tr>';
        }
        return $x . '</w:tbl>';
    }

    /** $vals[i] === null -> sel dibiarkan (mis. sel vMerge) */
    private function fill_row($row, array $vals)
    {
        $out = ''; $pos = 0;
        foreach ($this->find_all($row, ['<w:tc>'], '</w:tc>') as $i => $r) {
            $out .= substr($row, $pos, $r[0] - $pos);
            $tc = substr($row, $r[0], $r[1] - $r[0]);
            $out .= (array_key_exists($i, $vals) && $vals[$i] !== null) ? $this->set_cell($tc, $vals[$i]) : $tc;
            $pos = $r[1];
        }
        return $out . substr($row, $pos);
    }

    private function split_table($t, $headerRows)
    {
        $rows = $this->slices($t, ['<w:tr ', '<w:tr>'], '</w:tr>');
        $prefix = substr($t, 0, strpos($t, $rows[0]));
        return [$prefix, array_slice($rows, 0, $headerRows), $rows[$headerRows], $rows[$headerRows + 1] ?? $rows[$headerRows]];
    }

    /* ---------- gambar ---------- */

    private function drawing_run($n)
    {
        if (!isset($this->images[$n - 1])) return '';
        $im = $this->images[$n - 1];
        $this->used[$n] = true;
        $cx = (int)$im['cx']; $cy = (int)$im['cy'];
        $maxW = 3600000; $maxH = 3000000;                    // +/- 10 cm x 8 cm
        $k = min(1, $maxW / max($cx, 1), $maxH / max($cy, 1));
        $cx = (int)($cx * $k); $cy = (int)($cy * $k);
        $id = ++$this->docprId; $rid = 'rIdAi' . $n;
        return '<w:r><w:drawing><wp:inline distT="0" distB="0" distL="0" distR="0">'
             . '<wp:extent cx="' . $cx . '" cy="' . $cy . '"/><wp:docPr id="' . $id . '" name="Gambar ' . $id . '"/>'
             . '<a:graphic xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main">'
             . '<a:graphicData uri="http://schemas.openxmlformats.org/drawingml/2006/picture">'
             . '<pic:pic xmlns:pic="http://schemas.openxmlformats.org/drawingml/2006/picture">'
             . '<pic:nvPicPr><pic:cNvPr id="' . $id . '" name="gambar' . $n . '.' . $im['ext'] . '"/><pic:cNvPicPr/></pic:nvPicPr>'
             . '<pic:blipFill><a:blip r:embed="' . $rid . '"/><a:stretch><a:fillRect/></a:stretch></pic:blipFill>'
             . '<pic:spPr><a:xfrm><a:off x="0" y="0"/><a:ext cx="' . $cx . '" cy="' . $cy . '"/></a:xfrm>'
             . '<a:prstGeom prst="rect"><a:avLst/></a:prstGeom></pic:spPr></pic:pic>'
             . '</a:graphicData></a:graphic></wp:inline></w:drawing></w:r>';
    }

    private function tulis_gambar($zip)
    {
        if (!$this->used) return;
        $rels = $zip->getFromName('word/_rels/document.xml.rels');
        $ct   = $zip->getFromName('[Content_Types].xml');
        $mime = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif'];
        $add = ''; $exts = [];
        foreach (array_keys($this->used) as $n) {
            $im = $this->images[$n - 1];
            $file = 'aiimg' . $n . '.' . $im['ext'];
            $zip->addFromString('word/media/' . $file, $im['data']);
            $add .= '<Relationship Id="rIdAi' . $n . '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image" Target="media/' . $file . '"/>';
            $exts[$im['ext']] = true;
        }
        $zip->addFromString('word/_rels/document.xml.rels', str_replace('</Relationships>', $add . '</Relationships>', $rels));
        $def = '';
        foreach (array_keys($exts) as $e) {
            if (stripos($ct, 'Extension="' . $e . '"') === false) $def .= '<Default Extension="' . $e . '" ContentType="' . $mime[$e] . '"/>';
        }
        if ($def !== '') $zip->addFromString('[Content_Types].xml', str_replace('<Override ', $def . '<Override ', $ct));
    }

    /* ---------- pembangun tiap tabel ---------- */

    private function build_pg($t, $items)
    {
        list($prefix, $head, $rFirst, $rNext) = $this->split_table($t, 1);
        if (!$items) $items = [['pertanyaan' => '', 'opsi' => [], 'kunci' => '']];
        $body = [];
        foreach (array_values($items) as $n => $it) {
            $opsi = array_values($it['opsi'] ?? []);
            $kunci = strtoupper(trim(is_array($it['kunci'] ?? '') ? ($it['kunci'][0] ?? '') : ($it['kunci'] ?? '')));
            $total = max(5, count($opsi));
            for ($i = 0; $i < $total; $i++) {
                $h = $this->L[$i]; $txt = $opsi[$i] ?? '';
                $kv = ($kunci === $h && $txt !== '') ? 'v' : '';
                $body[] = $i === 0
                    ? $this->fill_row($rFirst, [(string)($n + 1), $it['pertanyaan'] ?? '', '1', $h, $txt, $kv])
                    : $this->fill_row($rNext, [null, null, null, $h, $txt, $kv]);
            }
        }
        return $prefix . implode('', $head) . implode('', $body) . '</w:tbl>';
    }

    private function build_pgk($t, $items)
    {
        list($prefix, $head, $rFirst, $rNext) = $this->split_table($t, 1);
        if (!$items) $items = [['pertanyaan' => '', 'opsi' => [''], 'kunci' => []]];
        $body = [];
        foreach (array_values($items) as $n => $it) {
            $opsi = array_values($it['opsi'] ?? []); if (!$opsi) $opsi = [''];
            $kunci = $it['kunci'] ?? [];
            if (is_string($kunci)) $kunci = preg_split('/[\s,;]+/', $kunci, -1, PREG_SPLIT_NO_EMPTY);
            $kunci = array_map('strtoupper', array_map('trim', $kunci));
            foreach ($opsi as $i => $txt) {
                $h = $this->L[$i]; $kv = in_array($h, $kunci, true) ? 'v' : '';
                $body[] = $i === 0
                    ? $this->fill_row($rFirst, [(string)($n + 1), $it['pertanyaan'] ?? '', '2', $h, $txt, $kv])
                    : $this->fill_row($rNext, [null, null, null, $h, $txt, $kv]);
            }
        }
        return $prefix . implode('', $head) . implode('', $body) . '</w:tbl>';
    }

    private function build_jodoh($t, $items)
    {
        list($prefix, $head, $rFirst, $rNext) = $this->split_table($t, 2);
        if (!$items) $items = [['pertanyaan' => '', 'baris' => [], 'kolom' => [], 'pasangan' => []]];
        $nb = self::NBSP; $body = [];
        foreach (array_values($items) as $n => $it) {
            $baris = array_values($it['baris'] ?? []); $kolom = array_values($it['kolom'] ?? []);
            $pas = $it['pasangan'] ?? [];   // [ "1" => "B,D", ... ] nomor baris => huruf kolom
            $rows = max(count($baris), count($kolom), 1);
            for ($i = 0; $i < $rows; $i++) {
                $hasB = $i < count($baris); $hasK = $i < count($kolom);
                $key = $hasB ? ($pas[(string)($i + 1)] ?? ($pas[$i + 1] ?? '')) : '';
                if (is_array($key)) $key = implode(',', $key);
                $v = [
                    $hasB ? (string)($i + 1) : $nb, $hasB ? $baris[$i] : $nb,
                    $hasK ? $this->L[$i] : $nb,     $hasK ? $kolom[$i] : $nb,
                    $hasB ? (string)($i + 1) : $nb, ($hasB && $key !== '') ? $key : $nb,
                ];
                $body[] = $i === 0
                    ? $this->fill_row($rFirst, array_merge([(string)($n + 1), $it['pertanyaan'] ?? '', '3'], $v))
                    : $this->fill_row($rNext, array_merge([null, null, null], $v));
            }
        }
        return $prefix . implode('', $head) . implode('', $body) . '</w:tbl>';
    }

    private function build_simple($t, $items, $jenis)
    {
        list($prefix, $head, $rFirst) = $this->split_table($t, 1);
        if (!$items) $items = [['pertanyaan' => '', 'jawaban' => '']];
        $body = [];
        foreach (array_values($items) as $n => $it) {
            $body[] = $this->fill_row($rFirst, [(string)($n + 1), $it['pertanyaan'] ?? '', (string)$jenis, $it['jawaban'] ?? '']);
        }
        return $prefix . implode('', $head) . implode('', $body) . '</w:tbl>';
    }
}