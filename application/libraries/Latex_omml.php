<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Simpan di: application/libraries/Latex_omml.php
 *
 * Mengubah teks yang berisi LaTeX ($\frac{1}{3}$, $1\frac{3}{5}$, $a\times b$, dst)
 * menjadi XML Word: run teks biasa (<w:r>) + persamaan asli (<m:oMath>).
 *
 * Syarat: <w:document> pada Template_.docx sudah punya xmlns:m="http://schemas.openxmlformats.org/officeDocument/2006/math"
 * (di hasil MTK_convert.docx sudah ada).
 *
 * Pemakaian:
 *   $this->load->library('latex_omml');
 *   $xml = $this->latex_omml->runs($teks, $rpr);   // $teks MENTAH (belum di-escape XML)
 *
 *   $rpr  = XML properti run, mis. '<w:rPr><w:sz w:val="22"/></w:rPr>' (boleh kosong)
 */
class Latex_omml
{
    private $sym = [
        'times' => '×', 'div' => '÷', 'cdot' => '·', 'dots' => '…', 'ldots' => '…',
        'ge' => '≥', 'geq' => '≥', 'le' => '≤', 'leq' => '≤', 'neq' => '≠', 'pm' => '±',
    ];

    /** True jika teks mengandung pasangan $...$ */
    public function ada_latex($teks)
    {
        return substr_count((string)$teks, '$') >= 2;
    }

    /**
     * Teks mentah -> deretan <w:r> dan <m:oMath>.
     * Jumlah tanda $ ganjil -> teks dibiarkan apa adanya (tidak dikonversi).
     */
    public function runs($teks, $rpr = '')
    {
        $teks = (string)$teks;
        $parts = explode('$', $teks);
        if (count($parts) % 2 === 0) {
            return $this->run_teks($teks, $rpr);
        }
        $out = '';
        foreach ($parts as $k => $part) {
            if ($part === '') continue;
            $out .= ($k % 2 === 0) ? $this->run_teks($part, $rpr) : '<m:oMath>' . $this->omml($part) . '</m:oMath>';
        }
        return $out;
    }

    /* ---------- internal ---------- */

    private function esc($s)
    {
        return htmlspecialchars($s, ENT_XML1 | ENT_NOQUOTES, 'UTF-8');
    }

    private function run_teks($t, $rpr)
    {
        if ($t === '') return '';
        return '<w:r>' . $rpr . '<w:t xml:space="preserve">' . $this->esc($t) . '</w:t></w:r>';
    }

    // run di dalam persamaan; font Cambria Math + ukuran 11pt
    private function mr($t, $plain)
    {
        if ($t === '') return '';
        $sty = $plain ? '<m:rPr><m:sty m:val="p"/></m:rPr>' : '';
        $wr = '<w:rPr><w:rFonts w:ascii="Cambria Math" w:hAnsi="Cambria Math"/><w:sz w:val="22"/><w:szCs w:val="22"/></w:rPr>';
        return '<m:r>' . $sty . $wr . '<m:t xml:space="preserve">' . $this->esc($t) . '</m:t></m:r>';
    }

    // ambil isi {...} mulai posisi $i; kembalikan [isi, posisi_berikutnya]
    private function grup($s, $i)
    {
        $n = strlen($s); $depth = 0;
        for ($j = $i; $j < $n; $j++) {
            if ($s[$j] === '{') $depth++;
            elseif ($s[$j] === '}') {
                $depth--;
                if ($depth === 0) return [substr($s, $i + 1, $j - $i - 1), $j + 1];
            }
        }
        return [substr($s, $i + 1), $n];
    }

    private function omml($s)
    {
        $out = ''; $buf = ''; $i = 0; $n = strlen($s);
        $flush = function () use (&$buf, &$out) {
            if ($buf === '') return;
            // huruf berurutan (mis. "kg") ditulis tegak; satu huruf = variabel (miring)
            preg_match_all('/[A-Za-z]{2,}|./us', $buf, $m);
            foreach ($m[0] as $t) {
                $plain = (mb_strlen($t, 'UTF-8') > 1) || !preg_match('/^[A-Za-z]$/', $t);
                $out .= $this->mr($t, $plain);
            }
            $buf = '';
        };
        while ($i < $n) {
            $c = $s[$i];
            if ($c === '\\') {
                if (preg_match('/^\\\\([A-Za-z]+)/', substr($s, $i), $mm)) {
                    $cmd = $mm[1]; $i += strlen($mm[0]);
                    if ($cmd === 'frac') {
                        while ($i < $n && $s[$i] === ' ') $i++;
                        list($a, $i) = $this->grup($s, $i);
                        while ($i < $n && $s[$i] === ' ') $i++;
                        list($b, $i) = $this->grup($s, $i);
                        $flush();
                        $out .= '<m:f><m:num>' . $this->omml($a) . '</m:num><m:den>' . $this->omml($b) . '</m:den></m:f>';
                    } elseif (isset($this->sym[$cmd])) {
                        $buf .= $this->sym[$cmd];
                    }
                    continue;
                }
                $i++; continue;
            }
            if ($c === '{' || $c === '}') { $i++; continue; }
            // ambil 1 karakter UTF-8 utuh
            $len = 1; $o = ord($c);
            if ($o >= 0xF0) $len = 4; elseif ($o >= 0xE0) $len = 3; elseif ($o >= 0xC0) $len = 2;
            $buf .= substr($s, $i, $len); $i += $len;
        }
        $flush();
        return $out;
    }
}
