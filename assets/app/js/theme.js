/* =====================================================================
   TEMA SIANG / MALAM - logika tombol + deteksi panel terang
   Simpan di: assets/app/js/theme.js
   Dipanggil dari _footer.php (setelah jQuery & AdminLTE dimuat)
   ===================================================================== */
(function () {
    var KEY = 'garudaCBT.theme';
    var root = document.documentElement;

    function current() {
        return root.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
    }

    /* ---------- Deteksi otomatis panel terang (untuk gaya khusus / inline) ---------- */
    var SKIP_TAGS = {SCRIPT: 1, STYLE: 1, IMG: 1, SVG: 1, CANVAS: 1, PATH: 1, INPUT: 1, SELECT: 1, TEXTAREA: 1, OPTION: 1, I: 1, BR: 1, HR: 1};
    var SKIP_SEL = '.badge,.btn,.alert,.small-box,.progress-bar,.toast,.theme-switch,.note-editor,.select2-container,.swal2-container,.callout,.label,.card-stat-custom,.pj-stage,.mode-proyektor-aktif';

    function parse(s) {
        var m = s && s.match(/rgba?\(([^)]+)\)/);
        if (!m) return null;
        var p = m[1].split(/[\s,\/]+/).filter(Boolean).map(parseFloat);
        return {r: p[0], g: p[1], b: p[2], a: p.length > 3 ? p[3] : 1};
    }

    function lum(c) {
        return (0.2126 * c.r + 0.7152 * c.g + 0.0722 * c.b) / 255;
    }

    function hasOwnText(el) {
        for (var n = el.firstChild; n; n = n.nextSibling) {
            if (n.nodeType === 3 && n.nodeValue.trim()) return true;
        }
        return false;
    }

    // true jika latar efektif elemen gelap (atau sudah ditandai gelap)
    function onDark(el) {
        for (var p = el; p && p !== root; p = p.parentElement) {
            if (p.hasAttribute('data-t-bg')) return true;
            var c = parse(getComputedStyle(p).backgroundColor);
            if (c && c.a > 0.6) return lum(c) < 0.5;
        }
        return true; // sampai atas: latar halaman = gelap
    }

    function clearMarks() {
        var list = document.querySelectorAll('[data-t-bg],[data-t-fg]');
        for (var i = 0; i < list.length; i++) {
            list[i].removeAttribute('data-t-bg');
            list[i].removeAttribute('data-t-fg');
        }
    }

    var scanning = false;

    function scan() {
        if (current() !== 'dark' || scanning) return;
        scanning = true;
        try {
            var els = document.querySelectorAll('.content-wrapper *, .modal-content *, .main-footer, .main-footer *');
            var i, el, c;
            // Tahap 1: latar terang -> ditandai
            for (i = 0; i < els.length; i++) {
                el = els[i];
                if (SKIP_TAGS[el.tagName.toUpperCase()] || el.hasAttribute('data-t-bg')) continue;
                if (el.closest(SKIP_SEL)) continue;
                c = parse(getComputedStyle(el).backgroundColor);
                if (c && c.a > 0.6) {
                    var l = lum(c);
                    if (l > 0.985) el.setAttribute('data-t-bg', '1');
                    else if (l > 0.8) el.setAttribute('data-t-bg', '2');
                }
            }
            // Tahap 2: teks gelap di atas latar gelap -> dibuat terang
            for (i = 0; i < els.length; i++) {
                el = els[i];
                if (SKIP_TAGS[el.tagName.toUpperCase()] || el.hasAttribute('data-t-fg')) continue;
                if (!hasOwnText(el) || el.closest(SKIP_SEL)) continue;
                c = parse(getComputedStyle(el).color);
                if (c && lum(c) < 0.5 && onDark(el)) el.setAttribute('data-t-fg', '1');
            }
        } catch (e) {}
        scanning = false;
    }

    var timer = null;

    function scheduleScan(delay) {
        clearTimeout(timer);
        timer = setTimeout(scan, delay || 120);
    }

    function apply(theme) {
        if (theme === 'dark') {
            root.setAttribute('data-theme', 'dark');
            scheduleScan(450); // tunggu transisi warna selesai
        } else {
            root.removeAttribute('data-theme');
            clearMarks();
        }
        try { localStorage.setItem(KEY, theme); } catch (e) {}
    }

    document.addEventListener('click', function (ev) {
        var btn = ev.target.closest ? ev.target.closest('[data-theme-set]') : null;
        if (!btn) return;
        ev.preventDefault();
        root.classList.add('theme-anim');
        apply(btn.getAttribute('data-theme-set'));
        setTimeout(function () { root.classList.remove('theme-anim'); }, 400);
    });

    // Sinkron antar tab
    window.addEventListener('storage', function (ev) {
        if (ev.key === KEY && ev.newValue && ev.newValue !== current()) {
            apply(ev.newValue);
        }
    });

    // Pindai saat halaman dimuat & saat isi halaman berubah (ajax, DataTables, modal)
    function init() {
        scheduleScan(150);
        if (window.MutationObserver) {
            new MutationObserver(function () {
                if (current() === 'dark') scheduleScan(150);
            }).observe(document.body, {childList: true, subtree: true, attributes: true, attributeFilter: ['class', 'style']});
        }
    }

    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
    else init();
    window.addEventListener('load', function () { scheduleScan(300); });
})();
