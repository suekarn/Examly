<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Simpan di: application/libraries/Omml.php
 * Pengubah Equation Word (OMML, <m:oMath>...</m:oMath>) -> LaTeX.
 * Dipakai Ai_converter::ganti_persamaan() lewat Omml::to_latex($fragmen_xml).
 */
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