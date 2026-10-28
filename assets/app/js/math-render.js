/**
 * math-render.js
 * Merender rumus \( ... \) dan \[ ... \] menjadi pecahan/rumus sungguhan dengan KaTeX.
 * Letakkan di: assets/app/js/math-render.js
 * Butuh: assets/plugins/katex/katex.min.js (+ katex.min.css)
 */
(function (w, d) {
    'use strict';

    var RE = /\\\(([\s\S]+?)\\\)|\\\[([\s\S]+?)\\\]/g;
    var SKIP = {SCRIPT: 1, STYLE: 1, TEXTAREA: 1, CODE: 1, PRE: 1, NOSCRIPT: 1};

    function decode(s) {
        var t = d.createElement('textarea');
        t.innerHTML = s;
        return t.value;
    }

    function renderTextNode(node) {
        var text = node.nodeValue;
        if (text.indexOf('\\(') === -1 && text.indexOf('\\[') === -1) return;
        var frag = d.createDocumentFragment(), last = 0, m;
        RE.lastIndex = 0;
        while ((m = RE.exec(text)) !== null) {
            if (m.index > last) frag.appendChild(d.createTextNode(text.slice(last, m.index)));
            var display = m[2] !== undefined;
            var latex = decode((display ? m[2] : m[1]).trim());
            var span = d.createElement('span');
            span.className = 'math-rendered';
            try {
                w.katex.render(latex, span, {displayMode: display, throwOnError: false});
            } catch (e) {
                span.textContent = m[0];
            }
            frag.appendChild(span);
            last = m.index + m[0].length;
        }
        if (!last) return;
        if (last < text.length) frag.appendChild(d.createTextNode(text.slice(last)));
        node.parentNode.replaceChild(frag, node);
    }

    function renderMath(root) {
        if (!w.katex) { console.warn('KaTeX belum dimuat'); return; }
        root = root || d.body;
        var walker = d.createTreeWalker(root, NodeFilter.SHOW_TEXT, {
            acceptNode: function (n) {
                for (var p = n.parentNode; p && p !== root.parentNode; p = p.parentNode) {
                    if (p.nodeType === 1 && (SKIP[p.nodeName] || p.classList.contains('math-rendered')
                        || p.classList.contains('katex'))) return NodeFilter.FILTER_REJECT;
                }
                return NodeFilter.FILTER_ACCEPT;
            }
        });
        var nodes = [];
        while (walker.nextNode()) nodes.push(walker.currentNode);
        nodes.forEach(renderTextNode);
    }

    w.renderMath = renderMath;
    d.addEventListener('DOMContentLoaded', function () {
        renderMath(d.body);
        // render ulang jika konten dimuat lewat AJAX / pagination
        var t;
        new MutationObserver(function () {
            clearTimeout(t);
            t = setTimeout(function () { renderMath(d.body); }, 150);
        }).observe(d.body, {childList: true, subtree: true});
    });
})(window, document);
