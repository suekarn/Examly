/**
 * fraksi-editor.js
 * Tambahan untuk editor Summernote di halaman buat/edit soal:
 *  1. Ketik pecahan biasa "3/4" atau pecahan campuran "1 1/4" -> langsung menjadi
 *     \(\frac{3}{4}\) / \(1\frac{1}{4}\) begitu penyebut diketik (tanpa spasi). Penyebut lebih dari
 *     satu angka (3/10) otomatis diperpanjang. Jika LANGSUNG = false, perubahan baru terjadi
 *     setelah spasi (atau . , ? !). Rumus dengan format ini tampil
 *     sebagai pecahan bertumpuk di halaman Detail Soal dan ujian (lewat math-render.js).
 *  2. Pratinjau rumus di bawah setiap kotak editor, jadi pecahan langsung terlihat rapi
 *     tanpa menyimpan dulu. Isi editor tidak diubah oleh pratinjau.
 * Letakkan di: assets/app/js/fraksi-editor.js
 * Butuh: assets/plugins/katex/katex.min.js (+ katex.min.css), jQuery, Summernote.
 */
(function (w, d, $) {
    'use strict';

    var LANGSUNG = true;   // true: berubah saat penyebut diketik; false: tunggu spasi / tanda baca
    var TRIGGER = /[ \u00a0.,;:!?)]/;
    // pecahan tepat sebelum kursor: [bulat spasi] pembilang/penyebut + 1 karakter pemicu
    var RE_UJUNG = /(^|[^0-9\\\/.,\w])(?:(\d{1,4})[ \u00a0]+)?(\d{1,4})\/(\d{1,4})([ \u00a0.,;:!?)])$/;
    var RE_LANGSUNG = /(^|[^0-9\\\/.,\w])(?:(\d{1,4})[ \u00a0]+)?(\d{1,4})\/([1-9]\d{0,3})$/;
    var RE_LANJUT = /\\\((\d{1,4})?\\frac\{(\d{1,4})\}\{(\d{1,3})\}\\\)(\d)$/;
    var RE_BALIK = /\\\((\d{1,4})?\\frac\{(\d{1,4})\}\{(\d{1,4})\}\\\)\/$/;
    var RE_RUMUS = /\\\(([\s\S]+?)\\\)|\\\[([\s\S]+?)\\\]/g;

    /** Teks sebelum kursor -> {awal, akhir, latex, pemicu} bila diakhiri pecahan; selain itu null */
    function cariPecahan(sebelum) {
        var m = RE_UJUNG.exec(sebelum);
        if (!m) return null;
        // sedang berada di dalam rumus \( ... \) yang belum ditutup: jangan diubah
        if (sebelum.lastIndexOf('\\(') > sebelum.lastIndexOf('\\)')) return null;
        if (parseInt(m[4], 10) === 0) return null;
        var latex = '\\(' + (m[2] || '') + '\\frac{' + m[3] + '}{' + m[4] + '}\\)';
        return {
            awal: m.index + m[1].length,
            akhir: sebelum.length - 1,
            latex: latex,
            pemicu: sebelum.charAt(sebelum.length - 1),
            ganti: latex + (/[ \u00a0]/.test(sebelum.charAt(sebelum.length - 1)) ? '\u00a0' : sebelum.charAt(sebelum.length - 1))
        };
    }

    function dalamRumus(sebelum) {
        return sebelum.lastIndexOf('\\(') > sebelum.lastIndexOf('\\)');
    }

    function buatLatex(bulat, pb, pn) {
        return '\\(' + (bulat || '') + '\\frac{' + pb + '}{' + pn + '}\\)';
    }

    /** Pecahan baru saja diketik sampai penyebut (tanpa spasi): "3/4", "1 1/4" */
    function cariLangsung(sebelum) {
        var m = RE_LANGSUNG.exec(sebelum);
        if (!m || dalamRumus(sebelum)) return null;
        return {awal: m.index + m[1].length, ganti: buatLatex(m[2], m[3], m[4])};
    }

    /** Angka diketik tepat setelah pecahan yang baru dibuat: perpanjang penyebut (3/1 + "0" -> 3/10) */
    function cariPerpanjangan(sebelum) {
        var m = RE_LANJUT.exec(sebelum);
        if (!m) return null;
        return {awal: m.index, ganti: buatLatex(m[1], m[2], m[3] + m[4])};
    }

    /** "/" diketik tepat setelah pecahan: itu bukan pecahan (mis. tanggal 12/10/2026), kembalikan ke teks biasa */
    function cariBalik(sebelum) {
        var m = RE_BALIK.exec(sebelum);
        if (!m) return null;
        return {awal: m.index, ganti: (m[1] ? m[1] + ' ' : '') + m[2] + '/' + m[3] + '/'};
    }

    function dekode(s) {
        var t = d.createElement('textarea');
        t.innerHTML = s;
        return t.value;
    }

    /** HTML isi editor -> HTML pratinjau (\( ... \) diganti hasil render KaTeX) */
    function htmlPratinjau(html) {
        if (!w.katex) return html;
        return html.replace(RE_RUMUS, function (semua, a, b) {
            var tampil = b !== undefined;
            var latex = dekode(String(tampil ? b : a).replace(/<[^>]+>/g, '')).trim();
            try {
                return w.katex.renderToString(latex, {displayMode: tampil, throwOnError: false});
            } catch (e) {
                return semua;
            }
        });
    }

    w.FraksiEditor = {cariPecahan: cariPecahan, cariLangsung: cariLangsung, cariPerpanjangan: cariPerpanjangan, cariBalik: cariBalik, htmlPratinjau: htmlPratinjau};
    if (!$ || !d.addEventListener) return;

    /* ---------- 1. ketik 3/4 + spasi -> pecahan ---------- */
    var sibuk = false;
    $(d).on('input', '.note-editable', function (e) {
        if (sibuk) return;
        var ev = e.originalEvent || e;
        if (ev.isComposing || ev.inputType !== 'insertText' || !ev.data || ev.data.length !== 1) return;
        var data = ev.data;
        var sel = w.getSelection();
        if (!sel || !sel.rangeCount || !sel.isCollapsed) return;
        var node = sel.anchorNode, off = sel.anchorOffset;
        if (!node || node.nodeType !== 3) return;
        var p = node.parentNode;
        if (p && p.closest && p.closest('code,pre')) return;
        var sebelum = node.nodeValue.slice(0, off);
        var h = null;
        if (/[0-9]/.test(data)) {
            h = cariPerpanjangan(sebelum) || (LANGSUNG ? cariLangsung(sebelum) : null);
        } else if (data === '/') {
            h = cariBalik(sebelum);
        }
        if (!h && TRIGGER.test(data)) h = cariPecahan(sebelum);
        if (!h) return;
        var r = d.createRange();
        r.setStart(node, h.awal);
        r.setEnd(node, off);
        sel.removeAllRanges();
        sel.addRange(r);
        sibuk = true;
        try {
            // execCommand supaya Ctrl+Z tetap bisa membatalkan dan Summernote ikut tahu isinya berubah
            d.execCommand('insertText', false, h.ganti);
        } finally {
            sibuk = false;
        }
    });

    /* ---------- 2. pratinjau di bawah tiap editor ---------- */
    function pasangGaya() {
        if ($('#fraksi-gaya').length) return;
        $('head').append('<style id="fraksi-gaya">' +
            '.fraksi-pratinjau{margin:6px 0 10px;padding:8px 12px;border:1px dashed #adb5bd;border-radius:4px;background:#f8f9fa;font-size:1rem;overflow-x:auto}' +
            '.fraksi-label{font-size:.72rem;color:#6c757d;margin-bottom:2px;text-transform:uppercase;letter-spacing:.04em}' +
            '.fraksi-bantuan{font-size:.82rem;color:#6c757d;margin:0 0 6px}' +
            '</style>');
        if (!$('link[href*="katex"]').length) {
            var dasar = (typeof w.base_url !== 'undefined') ? w.base_url : '/';
            $('head').append('<link rel="stylesheet" href="' + dasar + 'assets/plugins/katex/katex.min.css">');
        }
    }

    function perbarui() {
        if (d.hidden) return;
        pasangGaya();
        $('.note-editor').each(function () {
            var $ed = $(this), $e = $ed.find('.note-editable').first();
            if (!$e.length) return;
            var html = $e.html() || '';
            if ($ed.data('fraksiHtml') === html) return;
            $ed.data('fraksiHtml', html);
            var pv = $ed.next('.fraksi-pratinjau');
            if (!pv.length) pv = $('<div class="fraksi-pratinjau" style="display:none"></div>').insertAfter($ed);
            if (html.indexOf('\\(') === -1 && html.indexOf('\\[') === -1) {
                pv.hide().empty();
                return;
            }
            pv.html('<div class="fraksi-label">Pratinjau rumus' + (w.katex ? '' : ' (KaTeX belum dimuat)') + '</div>' + htmlPratinjau(html)).show();
        });
        if (!$('.fraksi-bantuan').length) {
            var $pertama = $('.note-editor').first();
            if ($pertama.length) {
                $('<p class="fraksi-bantuan">Tips: ketik <b>3/4</b> untuk membuat pecahan (langsung berubah); pecahan campuran: <b>1 1/4</b>. Tekan Ctrl+Z bila tidak ingin berubah.</p>').insertBefore($pertama);
            }
        }
    }

    setInterval(perbarui, 500);
})(window, document, window.jQuery);