<div class="content-wrapper bg-white">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-9">
                    <h1><?= $subjudul ?></h1>
                </div>
                <div class="col-3">
                    <button onclick="window.history.back();" type="button" class="btn btn-sm btn-danger float-right">
                        <i class="fas fa-arrow-circle-left"></i><span
                                class="d-none d-sm-inline-block ml-1">Kembali</span>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="col-lg-12 p-0">
                <div class="alert alert-danger shadow align-content-center" role="alert">
                    <strong>Catatan!</strong> untuk import data dari file excel/word, silahkan download templatenya
                    terlebih dahulu.
                </div>
            </div>

            <div class="card my-shadow mb-4">
                <div class="card-header">
                    <h6 class="card-title"><b>Upload Soal <?= $bank->nama_mapel . " kelas " . $bank->bank_level ?></b>
                    </h6>
                    <input type="hidden" name="bank_id" id="formInput" class="form-control">
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3" style="gap:8px;">
                        <a href="<?= base_url('uploads/import/format/format_soal_akm.docx') ?>"
                           class="btn btn-success" title="Download Template" data-toggle="tooltip"
                           download="Template Soal <?= $bank->nama_mapel ?> <?= $bank->bank_kode ?>">
                            <i class="fas fa-download"></i>
                        </a>
                        <button type="button" class="btn btn-danger" onclick="clearSoal()"
                                title="Bersihkan Preview" data-toggle="tooltip">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                        <div class="flex-grow-1">
                            <?= form_open_multipart('', array('id' => 'formPreviewWord')); ?>
                            <div class="custom-file">
                                <input type="file" name="upload_file[]" class="custom-file-input input-sm"
                                       id="upload-word" accept=".doc, .docx" multiple>
                                <label class="custom-file-label" for="upload-word">Upload Soal</label>
                            </div>
                            <?= form_close(); ?>
                        </div>
                        <button type="button" class="btn btn-primary" onclick="getData()"
                                title="Upload" data-toggle="tooltip">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </button>
                    </div>
                    <div id="file-preview" class="table-responsive"
                         data-id="<?= $this->security->get_csrf_hash() ?>"
                         data-name="<?= $this->security->get_csrf_token_name() ?>">
                        <div class="alert alert-default-info align-content-center" role="alert">
                            Sebelum upload, pastikan anda telah mengisi format yang telah disediakan.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?= form_open('create', array('id' => 'grouping')) ?>
<div id="input-soal-word" class="d-none"></div>
<?= form_close() ?>

<script type="text/javascript"
        src="<?= base_url() ?>/assets/plugins/jquery-table2json/src/tabletojson-cell.js"></script>
<script type="text/javascript" src="<?= base_url() ?>/assets/plugins/jquery-table2json/src/tabletojson-row.js"></script>
<script type="text/javascript" src="<?= base_url() ?>/assets/plugins/jquery-table2json/src/tabletojson.js"></script>
<script src="<?= base_url() ?>/assets/plugins/converter/jszip.min.js"></script>
<script src="<?= base_url() ?>/assets/app/js/mammoth.browser.min.js"></script>
<script>
    var bank_id = '<?= $bank->id_bank ?>';
    const jenjang = '<?= $setting->jenjang ?>';
    $(function () {
        bsCustomFileInput.init();
        $('[data-toggle="tooltip"]').tooltip();
    });

    var pg;
    var essai;
    var filename = '';
    $(document).ready(function () {
        ajaxcsrf();

        $('#upload-word').on('change', function (e) {
            var form = new FormData($("#formPreviewWord")[0]);
            filename = $("#formPreviewWord").text();
            //preview(base_url + 'cbtbanksoal/previewword/'+bank_id, form, filename);
            parseWordDocxFile(e.target.files, '#file-preview');
        });
    });

    // ================================================================
    // IMPORT WORD + EQUATION WORD (OMML)
    // Mammoth tidak meneruskan m:oMath sebagai HTML biasa. Karena itu
    // Equation Word diambil dulu dari word/document.xml, diubah menjadi
    // token [[EQ_1]], lalu setelah Mammoth selesai token dikembalikan
    // menjadi HTML pecahan/rumus.
    // ================================================================
    var IMPORTED_WORD_EQUATIONS = {};

    // Tampilan Equation Word di preview import. Dibuat cukup besar agar
    // pembilang/penyebut jelas dan tidak berubah menjadi teks \frac{...}{...}.
    (function ensureEquationImportStyle(){
        if (document.getElementById('equationImportStyle')) return;
        var st = document.createElement('style');
        st.id = 'equationImportStyle';
        st.textContent = `
            .word-equation{display:inline-flex;align-items:center;vertical-align:middle;
                font-family:Cambria Math, 'Times New Roman', serif; font-size:1.12em;
                line-height:1.15; margin:0 2px; white-space:nowrap; cursor:default;}
            .word-equation .eq-frac{display:inline-flex;flex-direction:column;
                align-items:center;justify-content:center;vertical-align:middle;
                line-height:1.0; margin:0 3px; min-width:1.25em;}
            .word-equation .eq-num{display:block;padding:0 4px 2px;
                border-bottom:1.5px solid currentColor; min-height:1em;}
            .word-equation .eq-den{display:block;padding:2px 4px 0;min-height:1em;}
            .word-equation .eq-mixed{display:inline-flex;align-items:center;gap:2px;}
            .word-equation .eq-rad{display:inline-block;position:relative;padding-top:1px;}
            .word-equation .eq-rad:before{content:'';position:absolute;left:0;right:0;top:0;
                border-top:1px solid currentColor;}
            .word-equation .eq-script,.word-equation .eq-sub{font-size:.72em;
                line-height:1;position:relative;vertical-align:baseline;margin-left:1px;}
            .word-equation .eq-script{top:-.55em;}
            .word-equation .eq-sub{top:.35em;}
            .word-equation[data-equation-latex]{border-radius:3px;}
        `;
        document.head.appendChild(st);
    })();

    function xmlEsc(s) {
        return String(s == null ? '' : s)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    function localName(el) {
        return el && (el.localName || el.nodeName || '').split(':').pop();
    }

    function elementChildren(el) {
        return Array.from(el && el.childNodes ? el.childNodes : [])
            .filter(function (n) { return n.nodeType === 1; });
    }

    function firstChildByLocal(el, name) {
        return elementChildren(el).find(function (n) {
            return localName(n) === name;
        }) || null;
    }

    function allDescendantsByLocal(el, name) {
        return Array.from(el && el.getElementsByTagName ? el.getElementsByTagName('*') : [])
            .filter(function (n) { return localName(n) === name; });
    }

    function ommlText(node) {
        if (!node) return '';
        var ts = allDescendantsByLocal(node, 't');
        if (ts.length) return ts.map(function (n) { return n.textContent || ''; }).join('');
        return node.textContent || '';
    }

    function ommlSymbolToLatex(s) {
        s = String(s || '');
        var map = {
            '×': '\\times ', '÷': '\\div ', '−': '-', '–': '-', '—': '-',
            '≤': '\\le ', '≥': '\\ge ', '≠': '\\neq ', '±': '\\pm ',
            '√': '\\sqrt{}', 'π': '\\pi ', 'α': '\\alpha ', 'β': '\\beta ',
            'γ': '\\gamma ', 'δ': '\\delta ', 'θ': '\\theta ', 'λ': '\\lambda ',
            'μ': '\\mu ', 'σ': '\\sigma ', '∞': '\\infty ',
            // nama perintah LaTeX yang tertulis apa adanya di file Word lama (hasil konversi sebelum diperbaiki)
            'bullet': '●', 'blacksquare': '■', 'square': '□', 'bigcirc': '○', 'triangle': '△',
            'underline': '', 'mathbb': '', 'text': '', 'mathrm': '', 'mathbf': '', 'textbf': '', 'left': '', 'right': ''
        };
        return Object.prototype.hasOwnProperty.call(map, s) ? map[s] : s;
    }

    function ommlToLatex(node) {
        if (!node) return '';
        var tag = localName(node);

        if (tag === 'oMath' || tag === 'oMathPara' || tag === 'e' || tag === 'num' ||
            tag === 'den' || tag === 'sub' || tag === 'sup' || tag === 'fName') {
            return elementChildren(node).map(ommlToLatex).join('');
        }

        if (tag === 'r') {
            var txt = ommlText(node);
            return ommlSymbolToLatex(txt);
        }

        if (tag === 't') {
            return ommlSymbolToLatex(node.textContent || '');
        }

        if (tag === 'f') {
            var num = firstChildByLocal(node, 'num');
            var den = firstChildByLocal(node, 'den');
            return '\\frac{' + ommlToLatex(num) + '}{' + ommlToLatex(den) + '}';
        }

        if (tag === 'sSup') {
            var e = firstChildByLocal(node, 'e');
            var sup = firstChildByLocal(node, 'sup');
            return '{' + ommlToLatex(e) + '}^{' + ommlToLatex(sup) + '}';
        }

        if (tag === 'sSub') {
            var e2 = firstChildByLocal(node, 'e');
            var sub = firstChildByLocal(node, 'sub');
            return '{' + ommlToLatex(e2) + '}_{' + ommlToLatex(sub) + '}';
        }

        if (tag === 'sSubSup') {
            var e3 = firstChildByLocal(node, 'e');
            var sub2 = firstChildByLocal(node, 'sub');
            var sup2 = firstChildByLocal(node, 'sup');
            return '{' + ommlToLatex(e3) + '}_{' + ommlToLatex(sub2) + '}^{' + ommlToLatex(sup2) + '}';
        }

        if (tag === 'rad') {
            var deg = firstChildByLocal(node, 'deg');
            var body = firstChildByLocal(node, 'e');
            var b = ommlToLatex(body);
            return deg ? '\\sqrt[' + ommlToLatex(deg) + ']{' + b + '}' : '\\sqrt{' + b + '}';
        }

        if (tag === 'd') {
            var body2 = firstChildByLocal(node, 'e');
            var dpr = firstChildByLocal(node, 'dPr');
            var beg = dpr ? firstChildByLocal(dpr, 'begChr') : null;
            var end = dpr ? firstChildByLocal(dpr, 'endChr') : null;
            var left = beg && (beg.textContent || '').trim() ? beg.textContent.trim() : '(';
            var right = end && (end.textContent || '').trim() ? end.textContent.trim() : ')';
            return '\\left' + left + ommlToLatex(body2) + '\\right' + right;
        }

        if (tag === 'func') {
            var fname = firstChildByLocal(node, 'fName');
            var body3 = firstChildByLocal(node, 'e');
            return ommlToLatex(fname) + ommlToLatex(body3);
        }

        if (tag === 'acc' || tag === 'bar') {
            var body4 = firstChildByLocal(node, 'e');
            var val = ommlToLatex(body4);
            return tag === 'bar' ? '\\bar{' + val + '}' : '\\hat{' + val + '}';
        }

        if (tag === 'groupChr') {
            var body5 = firstChildByLocal(node, 'e');
            return ommlToLatex(body5);
        }

        if (tag === 'nary') {
            var body6 = firstChildByLocal(node, 'e');
            var chrPr = firstChildByLocal(node, 'naryPr');
            var chr = chrPr ? firstChildByLocal(chrPr, 'chr') : null;
            var symbol = chr && (chr.textContent || '').trim();
            var cmd = symbol === '∏' ? '\\prod' :
                      symbol === '∫' ? '\\int' :
                      symbol === '∑' ? '\\sum' : '\\sum';
            var sub3 = firstChildByLocal(node, 'sub');
            var sup3 = firstChildByLocal(node, 'sup');
            var out = cmd;
            if (sub3) out += '_{' + ommlToLatex(sub3) + '}';
            if (sup3) out += '^{' + ommlToLatex(sup3) + '}';
            out += ommlToLatex(body6);
            return out;
        }

        // Properti OMML seperti rPr, fPr, ctrlPr tidak menghasilkan teks.
        if (tag === 'rPr' || tag === 'ctrlPr' || tag.endsWith('Pr')) return '';

        return elementChildren(node).map(ommlToLatex).join('');
    }

    function latexGroupValue(str, start) {
        if (str[start] !== '{') return {value:'', end:start};
        var depth = 0, out = '';
        for (var i = start; i < str.length; i++) {
            var ch = str[i];
            if (ch === '{') {
                if (depth > 0) out += ch;
                depth++;
            } else if (ch === '}') {
                depth--;
                if (depth === 0) return {value:out, end:i + 1};
                out += ch;
            } else {
                out += ch;
            }
        }
        return {value:out, end:str.length};
    }

    function latexInlineHtml(latex) {
        var src = String(latex || '').trim();
        var out = '';
        var i = 0;

        while (i < src.length) {
            if (src.slice(i, i + 5) === '\\frac' && src[i + 5] === '{') {
                var ng = latexGroupValue(src, i + 5);
                var dg = latexGroupValue(src, ng.end);
                if (ng.end < src.length && dg.end > ng.end) {
                    out += '<span class="eq-frac" style="display:inline-block;vertical-align:middle;text-align:center;margin:0 3px;line-height:1.1;">' +
                        '<span class="eq-num" style="display:block;padding:0 4px 1px;border-bottom:1px solid currentColor;">' +
                        latexInlineHtml(ng.value) + '</span><span class="eq-den" style="display:block;padding:1px 4px 0;">' +
                        latexInlineHtml(dg.value) + '</span></span>';
                    i = dg.end;
                    continue;
                }
            }

            if (src.slice(i, i + 5) === '\\sqrt' && src[i + 5] === '{') {
                var rg = latexGroupValue(src, i + 5);
                if (rg.end > i + 5) {
                    out += '√<span class="eq-rad" style="border-top:1px solid currentColor;padding-top:1px;">' + latexInlineHtml(rg.value) + '</span>';
                    i = rg.end;
                    continue;
                }
            }

            if (src[i] === '^' || src[i] === '_') {
                var marker = src[i], sg;
                if (src[i + 1] === '{') {
                    sg = latexGroupValue(src, i + 1);
                    if (sg.end > i + 1) {
                        out += '<' + (marker === '^' ? 'sup' : 'sub') + '>' +
                            latexInlineHtml(sg.value) + '</' + (marker === '^' ? 'sup' : 'sub') + '>';
                        i = sg.end;
                        continue;
                    }
                } else if (i + 1 < src.length) {
                    out += '<' + (marker === '^' ? 'sup' : 'sub') + '>' +
                        xmlEsc(src[i + 1]) + '</' + (marker === '^' ? 'sup' : 'sub') + '>';
                    i += 2;
                    continue;
                }
            }

            if (src[i] === '\\') {
                var m = src.slice(i).match(/^\\(times|cdot|div|pm|le|ge|neq|pi|alpha|beta|gamma|delta|theta|lambda|mu|sigma|infty)\s*/);
                if (m) {
                    var sym = {
                        times:'×', cdot:'·', div:'÷', pm:'±', le:'≤', ge:'≥', neq:'≠',
                        pi:'π', alpha:'α', beta:'β', gamma:'γ', delta:'δ', theta:'θ',
                        lambda:'λ', mu:'μ', sigma:'σ', infty:'∞'
                    }[m[1]];
                    out += xmlEsc(sym);
                    i += m[0].length;
                    continue;
                }
            }

            // Kurung kelompok biasa tidak perlu ditampilkan sebagai tanda kurung.
            if (src[i] === '{' || src[i] === '}') {
                i++;
                continue;
            }

            out += xmlEsc(src[i]);
            i++;
        }
        return out;
    }

    function latexToEquationHtml(latex) {
        var safe = xmlEsc(latex);
        return '<span class="word-equation" data-equation-latex="' + safe +
            '" title="Equation Word: ' + safe + '" style="display:inline-block;vertical-align:middle;white-space:nowrap;font-family:Cambria Math,serif;">' + latexInlineHtml(latex) + '</span>';
    }

    // Mammoth pada beberapa dokumen/versi dapat mengeluarkan LaTeX sebagai
    // teks biasa. Fungsi ini menjadi fallback: cari \\frac, \\sqrt, x^2,
    // x_1, dan simbol LaTeX di text-node lalu ubah menjadi HTML equation.
    function renderLatexTextNodes(root) {
        if (!root) return;
        var walker = document.createTreeWalker(root, NodeFilter.SHOW_TEXT, null);
        var nodes = [];
        while (walker.nextNode()) nodes.push(walker.currentNode);

        nodes.forEach(function(node){
            if (!node.parentNode || node.parentNode.closest('.word-equation')) return;
            var text = node.nodeValue || '';
            if (!/\\(?:frac|sqrt|times|cdot|div|pm|le|ge|neq|pi|alpha|beta|gamma|delta|theta|lambda|mu|sigma|infty)\s*\{|\^\{|_\{|\\frac|\\sqrt/.test(text)) return;

            // Ambil segmen equation yang paling umum. Untuk teks campuran,
            // sisipkan satu span equation pada setiap ekspresi yang ditemukan.
            var re = /(\\frac\s*\{(?:[^{}]|\{[^{}]*\})*\}\s*\{(?:[^{}]|\{[^{}]*\})*\}|\\sqrt\s*\{(?:[^{}]|\{[^{}]*\})*\}|\\(?:times|cdot|div|pm|le|ge|neq|pi|alpha|beta|gamma|delta|theta|lambda|mu|sigma|infty)\s*(?:\{[^{}]*\})?)/g;
            var parts = [];
            var last = 0, m;
            while ((m = re.exec(text)) !== null) {
                if (m.index > last) parts.push(document.createTextNode(text.slice(last, m.index)));
                var holder = document.createElement('span');
                holder.innerHTML = latexToEquationHtml(m[0]);
                parts.push(holder.firstChild);
                last = m.index + m[0].length;
            }
            if (!parts.length) return;
            if (last < text.length) parts.push(document.createTextNode(text.slice(last)));
            var frag = document.createDocumentFragment();
            parts.forEach(function(p){ frag.appendChild(p); });
            node.parentNode.replaceChild(frag, node);
        });
    }

    // Tabel yang tersimpan di Word sebagai TEKS kode HTML (&lt;table ...&gt;), mis. hasil konversi AI,
    // diubah kembali menjadi tabel sungguhan. Hanya tag tabel/format dasar yang dipertahankan.
    var TABLE_SAFE_TAGS = ['TABLE','THEAD','TBODY','TFOOT','TR','TH','TD','CAPTION','COLGROUP','COL',
                           'B','STRONG','I','EM','U','BR','SPAN','SUB','SUP','P'];

    function cleanTableHtml(html) {
        var tpl = document.createElement('template');
        tpl.innerHTML = html;
        var all = Array.from(tpl.content.querySelectorAll('*'));
        all.forEach(function (el) {
            if (!el.parentNode) return;
            if (TABLE_SAFE_TAGS.indexOf(el.tagName) === -1) {
                if (/^(SCRIPT|STYLE|IFRAME|OBJECT|EMBED|LINK|META)$/.test(el.tagName)) {
                    el.parentNode.removeChild(el);
                } else {
                    el.parentNode.replaceChild(document.createTextNode(el.textContent || ''), el);
                }
                return;
            }
            Array.from(el.attributes).forEach(function (a) {
                if (/^on/i.test(a.name) || /javascript:/i.test(a.value)) el.removeAttribute(a.name);
            });
            // isi tabel (judul maupun data) rata tengah
            if (el.tagName === 'TD' || el.tagName === 'TH') {
                el.style.textAlign = 'center';
                el.style.verticalAlign = 'middle';
            }
        });
        return tpl.content;
    }

    function unescapeHtmlTables(root) {
        if (!root) return;
        var walker = document.createTreeWalker(root, NodeFilter.SHOW_TEXT, null);
        var nodes = [];
        while (walker.nextNode()) nodes.push(walker.currentNode);

        nodes.forEach(function (node) {
            var text = node.nodeValue || '';
            if (!node.parentNode || !/<table[\s>]/i.test(text)) return;
            var re = /<table[\s\S]*?<\/table>/gi;
            var frag = document.createDocumentFragment();
            var last = 0, m, found = false;
            while ((m = re.exec(text)) !== null) {
                found = true;
                if (m.index > last) frag.appendChild(document.createTextNode(text.slice(last, m.index)));
                frag.appendChild(cleanTableHtml(m[0]));
                last = m.index + m[0].length;
            }
            if (!found) return;
            if (last < text.length) frag.appendChild(document.createTextNode(text.slice(last)));
            node.parentNode.replaceChild(frag, node);
        });
    }

    function replaceEquationTokens(html) {
        var out = String(html || '');
        Object.keys(IMPORTED_WORD_EQUATIONS).forEach(function (token) {
            var latex = IMPORTED_WORD_EQUATIONS[token] || '';
            var rendered = latexToEquationHtml(latex);
            out = out.split(token).join(rendered);
        });
        return out;
    }

    async function prepareDocxEquationTokens(arrayBuffer) {
        IMPORTED_WORD_EQUATIONS = {};

        if (typeof JSZip === 'undefined') {
            return arrayBuffer;
        }

        var zip = await JSZip.loadAsync(arrayBuffer);
        var entry = zip.file('word/document.xml');
        if (!entry) return arrayBuffer;

        var xml = await entry.async('string');
        var parser = new DOMParser();
        var doc = parser.parseFromString(xml, 'application/xml');
        if (doc.getElementsByTagName('parsererror').length) return arrayBuffer;

        var M = 'http://schemas.openxmlformats.org/officeDocument/2006/math';
        var W = 'http://schemas.openxmlformats.org/wordprocessingml/2006/main';
        var seq = 0;

        // oMathPara biasanya merupakan paragraf Equation Word tersendiri.
        Array.from(doc.getElementsByTagNameNS(M, 'oMathPara')).forEach(function (mathPara) {
            var equations = Array.from(mathPara.getElementsByTagNameNS(M, 'oMath'));
            if (!equations.length) return;

            var tokenParts = equations.map(function (eq) {
                var latex = ommlToLatex(eq).trim();
                var token = '[[EQ_' + (++seq) + ']]';
                IMPORTED_WORD_EQUATIONS[token] = latex || ommlText(eq);
                return token;
            });

            var p = doc.createElementNS(W, 'w:p');
            var r = doc.createElementNS(W, 'w:r');
            var t = doc.createElementNS(W, 'w:t');
            t.setAttribute('xml:space', 'preserve');
            t.textContent = tokenParts.join(' ');
            r.appendChild(t);
            p.appendChild(r);

            if (mathPara.parentNode) mathPara.parentNode.replaceChild(p, mathPara);
        });

        // oMath yang berada di dalam w:p / w:tc.
        Array.from(doc.getElementsByTagNameNS(M, 'oMath')).forEach(function (math) {
            if (!math.parentNode) return;

            var latex = ommlToLatex(math).trim();
            var token = '[[EQ_' + (++seq) + ']]';
            IMPORTED_WORD_EQUATIONS[token] = latex || ommlText(math);

            var r = doc.createElementNS(W, 'w:r');
            var t = doc.createElementNS(W, 'w:t');
            t.setAttribute('xml:space', 'preserve');
            t.textContent = token;
            r.appendChild(t);

            math.parentNode.replaceChild(r, math);
        });

        if (!seq) return arrayBuffer;

        var serializer = new XMLSerializer();
        zip.file('word/document.xml', serializer.serializeToString(doc));
        return await zip.generateAsync({
            type: 'arraybuffer',
            compression: 'DEFLATE',
            compressionOptions: { level: 3 }
        });
    }

    // Baca SATU file Word -> kembalikan HTML hasil konversi (equation, tabel kode HTML sudah dirapikan)
    function convertOneDocx(file) {
        return new Promise(function (resolve, reject) {
            var reader = new FileReader();
            var options = {
                styleMap: ["u => u", "strike => del"]
            };
            reader.onerror = function () {
                reject(new Error("File tidak dapat dibaca: " + file.name));
            };
            reader.onloadend = async function () {
                try {
                    var arrayBuffer = reader.result;

                    // Hanya DOCX yang mempunyai XML OMML. File .doc lama langsung ke Mammoth.
                    var ext = (file.name || '').split('.').pop().toLowerCase();
                    if (ext === 'docx') {
                        arrayBuffer = await prepareDocxEquationTokens(arrayBuffer);
                    }

                    var resultObject = await mammoth.convertToHtml({arrayBuffer: arrayBuffer}, options);
                    if (!resultObject || !resultObject.value) {
                        throw new Error("Dokumen Word tidak menghasilkan isi yang dapat dibaca.");
                    }

                    // Kembalikan Equation Word yang sebelumnya ditahan sebagai token.
                    resultObject.value = replaceEquationTokens(resultObject.value);

                    var equationPreview = document.createElement('div');
                    equationPreview.innerHTML = resultObject.value;
                    renderLatexTextNodes(equationPreview);
                    unescapeHtmlTables(equationPreview);
                    resolve(equationPreview.innerHTML);
                } catch (err) {
                    err.message = '[' + file.name + '] ' + (err.message || err);
                    reject(err);
                }
            };
            reader.readAsArrayBuffer(file);
        });
    }

    // Rapikan tabel di preview (sama seperti sebelumnya, dijalankan sekali untuk semua file)
    function postProcessPreview() {
        $('#file-preview').children().not("table").remove();
                    $('#file-preview').children('table').each(function (i, v) {
                        console.log('tbls', i);
                        $(this).addClass('table table-bordered w-100 table-soal');

                        const $trs = $(this).find('tr'), headers = $trs.splice(0, 1);

                        var indexTr = 0;
                        let hide = false;
                        $trs.each(function (index, tr) {
                            var cekTbl = $(tr).parent().closest('td');
                            if (cekTbl.length === 0) {
                                $(this).addClass('tr-soal');
                                var $tds = $(this).find('td');
                                $tds.each(function (it, td) {
                                    var tdlength = $(td).closest('tr').hasClass('tr-soal');
                                    if (tdlength) $(td).addClass('td-soal');
                                    $(td).find('table').addClass('table table-bordered table-inner');
                                });

                                var rows = $(this).find("td:eq(0)").attr('rowspan');
                                var soal = $(this).find("td:eq(1)").text().trim();
                                var jenis = $(this).find("td:eq(2)").text().trim();
                                if (rows != null) {
                                    const imgSoal = $(this).find("td:eq(1)").find('img').length
                                    indexTr = 1;
                                    if (jenis === '1' || jenis === '2') {
                                        const noSoal = soal === '' && imgSoal === 0
                                        var jawaban = $(this).find("td:eq(4)").text().trim();
                                        console.log('jawaban', jawaban)
                                        hide = jawaban === '' && noSoal;
                                        if (hide) {
                                            $(this).remove();
                                        }
                                    } else if (jenis === '3') {
                                        const imgBaris = $(this).find("td:eq(4)").find('img').length
                                        const imgkolom = $(this).find("td:eq(6)").find('img').length

                                        var baris = $(this).find("td:eq(4)").text().trim();
                                        var kolom = $(this).find("td:eq(6)").text().trim();

                                        const noSoal = soal === '' && imgSoal === 0
                                        const noBaris = baris === '' && imgBaris === 0
                                        const noKolom = kolom === '' && imgkolom === 0

                                        hide = noBaris && noKolom && noSoal;
                                        if (hide) {
                                            $(this).remove();
                                        }
                                    }
                                } else {
                                    indexTr += 1;
                                    if (jenis === '4' || jenis === '5') {
                                        const imgSoal = $(this).find("td:eq(1)").find('img').length
                                        var jawab = $(this).find("td:eq(3)").text().trim();
                                        const noSoal = soal === '' && imgSoal === 0
                                        hide = jawab === '' && noSoal;
                                        if (hide) {
                                            $(this).remove();
                                        }
                                    }
                                }

                                if (indexTr > 1 && hide) {
                                    $(this).remove();
                                }
                            }
                        });

                        $(this).find('p').each(function () {
                            var arabic = /[\u0600-\u06FF]/;
                            var string = $(this).text();
                            if (arabic.test(string)) {
                                $(this).css({
                                    'font-size': '16pt',
                                    'font-family': 'Calibri',
                                    'direction': 'rtl',
                                    'text-align': 'justify'
                                });
                            }
                        });
                    });
        var attrId = document.getElementById("formInput");
        attrId.setAttribute("value", bank_id);
    }

    // Mendukung 1 file atau lebih. Semua tabel soal dari semua file digabung
    // di #file-preview dan diberi penanda data-file (urutan file) supaya nomor soal
    // bisa disambung otomatis saat import.
    async function parseWordDocxFile(inputElement, showDiv) {
        var files = Array.from(inputElement || []);
        if (!files.length) return;

        swal.fire({
            title: "Mempersiapkan upload",
            text: "Membaca " + files.length + " file, silahkan tunggu....",
            button: false,
            closeOnClickOutside: false,
            closeOnEsc: false,
            allowEscapeKey: false,
            allowOutsideClick: false,
            onOpen: () => {
                swal.showLoading();
            }
        });

        try {
            var target = $(showDiv).empty()[0];
            for (var fi = 0; fi < files.length; fi++) {
                var html = await convertOneDocx(files[fi]);
                var tmp = document.createElement('div');
                tmp.innerHTML = html;
                Array.from(tmp.children).forEach(function (el) {
                    if (el.tagName === 'TABLE') {
                        el.setAttribute('data-file', fi);
                        el.setAttribute('data-filename', files[fi].name);
                        target.appendChild(el);
                    }
                });
            }

            setTimeout(function () {
                try {
                    postProcessPreview();
                    filename = files.map(function (f) { return f.name; }).join(', ');
                    swal.close();
                } catch (err) {
                    console.error("Preview error:", err);
                    swal.close();
                    swal.fire({title: "Gagal membaca Word", text: String(err.message || err), icon: "error"});
                }
            }, 500);
        } catch (err) {
            console.error("Import Word/Equation error:", err);
            filename = '';
            swal.close();
            swal.fire({
                title: "Gagal membaca Word",
                html: "Dokumen gagal diproses.<br><small>" + $('<div>').text(err.message || err).html() + "</small>",
                icon: "error"
            });
        }
    }

    // Nomor soal tiap file disambung: file ke-2 dimulai setelah nomor terakhir file ke-1 (per jenis soal)
    var noState = {};
    function mapNo(jenis, fileIdx, no, autoOk) {
        var n = parseInt(no, 10);
        var st = noState[jenis] || (noState[jenis] = {file: fileIdx, base: 0, max: 0});
        if (st.file !== fileIdx) {
            st.file = fileIdx;
            st.base = st.max;
        }
        if (isNaN(n)) {
            // Nomor kosong: kalau barisnya memang soal (ada isi), beri nomor otomatis berikutnya
            if (String(no).trim() === '' && autoOk) {
                st.max = st.max + 1;
                return st.max;
            }
            return no;
        }
        var nn = st.base + n;
        if (nn > st.max) st.max = nn;
        return nn;
    }

    // Bersihkan preview soal (hanya di layar, belum ada data yang masuk database)
    function clearSoal() {
        $('#file-preview').html(
            '<div class="alert alert-default-info align-content-center" role="alert">' +
            'Sebelum upload, pastikan anda telah mengisi format yang telah disediakan.</div>'
        );
        $('#upload-word').val('');
        $('#upload-word').next('.custom-file-label').text('Upload Soal');
        filename = '';
        IMPORTED_WORD_EQUATIONS = {};
        noState = {};
    }

    function getData() {
        if (filename === '') {
            showDangerToast('Pilih File dulu');
            return;
        }
        swal.fire({
            title: "Import soal ke database",
            text: "Silahkan tunggu....",
            button: false,
            closeOnClickOutside: false,
            closeOnEsc: false,
            allowEscapeKey: false,
            allowOutsideClick: false,
            onOpen: () => {
                swal.showLoading();
            }
        });

        noState = {};
        var tempJenis = "0";
        var index = 0;
        var $tables = $('.table-soal');
        var tbls = {};

        let formData = new FormData($('#grouping')[0])
        formData.append("id_bank", bank_id)

        try {
        $.each($tables, function (i, row) {
            var fileIdx = parseInt($(this).attr('data-file') || 0, 10);
            var tbl = $(this).find('.tr-soal').get().map(function (row) {
                return $(row).find('.td-soal').get().map(function (cell) {
                    return $(cell).html();
                });
            });
            if (tbl[0] === undefined) return;
            //console.log('tabel1', tbl[0])
            // Cari jenis soal pada 3 baris pertama tabel (selalu dihitung untuk SETIAP tabel,
            // supaya tabel kosong / tabel dari file lain tidak membuat jenis tertukar)
            var rowSoal = 0;
            var detected = null;
            for (var r = 0; r < Math.min(3, tbl.length); r++) {
                if (tbl[r][2] === undefined) continue;
                var jj = $('<div>').html(tbl[r][2]).text().trim();
                if (!isNaN(parseInt(jj))) {
                    detected = jj;
                    rowSoal = r;
                    break;
                }
            }
            if (detected === null) return;
            tempJenis = detected;

            var myRows = {};
            var no1 = 0;
            var no3 = 0;
            for (let j = rowSoal; j < tbl.length; j++) {
                var items = tbl[j];
                myRows[j] = {};
                if (tempJenis == "1" || tempJenis == "2") {
                    if (items.length === 6) {
                        const imgCheck = $(items[1]).find('img').length > 0
                        var soalCek = $(items[1]).text().trim();
                        var no = mapNo(tempJenis, fileIdx, $(items[0]).text().trim(), soalCek !== '' || imgCheck);
                        no1 = no;
                        var ops = $(items[3]).text().trim();
                        var knc = $(items[5]).text().trim();

                        if (soalCek != "" || imgCheck) {
                            formData.append('soal[' + tempJenis + ']' +'[' + no + '][soal]', encodeURIComponent(removeUrl(items[1])));
                            formData.append('soal[' + tempJenis + ']' + '[' + no + '][opsi][' + ops + ']', encodeURIComponent(removeUrl(items[4])));

                            if (knc != "" && knc.toUpperCase() == "V") {
                                formData.append('soal[' + tempJenis + ']' + '[' + no + '][kunci][]', ops);
                            }
                        }
                    } else {
                        myRows[j]['NO'] = no1;
                        myRows[j]['SOAL'] = '';
                        myRows[j]['JENIS'] = tempJenis;
                        var ops1 = $(items[0]).text().trim();

                        formData.append('soal[' + tempJenis + ']' + '[' + no1 + '][opsi][' + ops1 + ']', encodeURIComponent(removeUrl(items[1])));

                        var knc1 = $(items[2]).text().trim();
                        if (knc1 != "" && knc1.toUpperCase() == "V") {
                            formData.append('soal[' + tempJenis + ']' + '[' + no1 + '][kunci][]', ops1);
                        }
                    }
                } else if (tempJenis == '3') {
                    if (items.length === 9) {
                        no3 = mapNo(tempJenis, fileIdx, $(items[0]).text().trim(), $(items[1]).text().trim() !== '' || $(items[1]).find('img').length > 0); // nomor (kosong = otomatis)

                        var kd_baris = $(items[3]).text().trim().toUpperCase();
                        var kd_kolom = $(items[5]).text().trim().toUpperCase();
                        var kd_kunci = $(items[7]).text().trim().toUpperCase();

                        formData.append('soal[' + tempJenis + ']' + '[' + no3 + '][soal]', encodeURIComponent(removeUrl(items[1])));
                        // Penanda kunci kosong: tanpa ini, soal menjodohkan yang kolom KUNCI-nya belum diisi
                        // membuat server PHP mengeluarkan peringatan "Undefined array key kunci"
                        formData.append('soal[' + tempJenis + ']' + '[' + no3 + '][kunci][0]', '');

                        const imgBrs = $(items[4]).find('img').length > 0
                        var brs = $(items[4]).text().trim();
                        if (brs != "" || imgBrs) {
                            formData.append('soal[' + tempJenis + ']' + '[' + no3 + '][baris][' + kd_baris + ']', encodeURIComponent(removeUrl(items[4])));
                        }

                        const imgKlm = $(items[6]).find('img').length > 0
                        var klm = $(items[6]).text().trim();
                        if (klm != "" || imgKlm) {
                            formData.append('soal[' + tempJenis + ']' + '[' + no3 + '][kolom][' + kd_kolom + ']', encodeURIComponent(removeUrl(items[6])));
                        }

                        var kncs = $(items[8]).text().trim().toUpperCase();
                        if (kncs != "") {
                            formData.append('soal[' + tempJenis + ']' + '[' + no3 + '][kunci][' + kd_kunci + ']', kncs);
                        }
                    } else {
                        var kd_baris1 = $(items[0]).text().trim().toUpperCase();
                        var kd_kolom1 = $(items[2]).text().trim().toUpperCase();
                        var kd_kunci1 = $(items[4]).text().trim().toUpperCase();

                        const imgBrs1 = $(items[1]).find('img').length > 0
                        var brs1 = $(items[1]).text().trim();
                        if (brs1 != "" || imgBrs1) {
                            formData.append('soal[' + tempJenis + ']' + '[' + no3 + '][baris][' + kd_baris1 + ']', encodeURIComponent(removeUrl(items[1])));
                        }

                        const imgKlm1 = $(items[3]).find('img').length > 0
                        var klm1 = $(items[3]).text().trim();
                        if (klm1 != "" || imgKlm1) {
                            formData.append('soal[' + tempJenis + ']' + '[' + no3 + '][kolom][' + kd_kolom1 + ']', encodeURIComponent(removeUrl(items[3])));
                        }

                        var kncs1 = $(items[5]).text().trim().toUpperCase();
                        if (kncs1 != "") {
                            formData.append('soal[' + tempJenis + ']' + '[' + no3 + '][kunci][' + kd_kunci1 + ']', kncs1);
                        }
                    }
                } else {
                    var no4 = mapNo(tempJenis, fileIdx, $(items[0]).text().trim(), $(items[1]).text().trim() !== '' || $(items[1]).find('img').length > 0);
                    formData.append('soal[' + tempJenis + ']' + '[' + no4 + '][soal]', encodeURIComponent(removeUrl(items[1])));
                    if (tempJenis == '4') {
                        formData.append('soal[' + tempJenis + ']' + '[' + no4 + '][kunci]', $(items[3]).text().trim());
                    } else {
                        formData.append('soal[' + tempJenis + ']' + '[' + no4 + '][kunci]', encodeURIComponent(removeUrl(items[3])));
                    }
                }
                tbls[tempJenis] = myRows;
            }
            index++;
        });
        } catch (err) {
            console.error('getData error', err);
            swal.close();
            swal.fire({
                title: "Gagal menyiapkan soal",
                html: "Ada bagian soal yang tidak terbaca.<br><small>" + $('<div>').text(err.message || err).html() + "</small>",
                icon: "error"
            });
            return;
        }

        setTimeout(function () {
            //console.log('old_json', datapost);
            console.log('form', Object.fromEntries(formData))
            sendData(formData);
        }, 500);
    }

    function sendData(datapost) {
        var fieldCount = 0;
        try { datapost.forEach(function () { fieldCount++; }); } catch (e) {}
        swal.fire({
            title: "Import soal ke database",
            text: "Mengirim " + fieldCount + " isian, silahkan tunggu....",
            button: false,
            closeOnClickOutside: false,
            closeOnEsc: false,
            allowEscapeKey: false,
            allowOutsideClick: false,
            onOpen: () => {
                swal.showLoading();
            }
        });
        $.ajax({
            url: base_url + "cbtbanksoal/uploadsoal",
            method: "POST",
            processData: false,
            contentType: false,
            timeout: 0,
            data: datapost,
            success: function (result) {
                console.log("result", result);
                if (!result || typeof result.total === 'undefined') {
                    swal.fire({
                        title: "Error",
                        html: "Respon server tidak sesuai. Buka F12 > Console untuk melihat detailnya.",
                        icon: "error"
                    });
                    return;
                }
                swal.fire({
                    title: "Sukses",
                    html: "Total isi soal: <b>" + result.total + "</b><br>Total soal diimport: <b>" + result.insert + "</b>",
                    icon: "success",
                    showCancelButton: false,
                    confirmButtonColor: "#3085d6",
                }).then(result => {
                    if (result.value) {
                        window.history.back()
                        //window.location.href = base_url + 'cbtbanksoal';
                    }
                });
            }, error: function (xhr, status, error) {
                console.log("error", xhr.status, status, xhr.responseText);
                var msg = '';
                try { msg = JSON.parse(xhr.responseText).Message; } catch (e) {}
                var serverText = $('<div>').html(xhr.responseText || '').text().replace(/\s+/g, ' ').trim().substring(0, 300);
                if (!msg && serverText) {
                    msg = 'Pesan dari server: ' + serverText;
                }
                if (!msg) {
                    msg = 'Server gagal memproses (HTTP ' + xhr.status + ' ' + (error || status) + '). ' +
                        'Jumlah isian yang dikirim: ' + fieldCount + '. ' +
                        'Jika file banyak/bergambar, naikkan max_input_vars, post_max_size dan upload_max_filesize di php.ini lalu restart Apache.';
                }
                swal.close();
                swal.fire({
                    title: "Error",
                    text: msg,
                    icon: "error"
                });
            }
        });
    }

    function appendFileAndSubmit(id, imageURL, fileName) {
        var datapost = $('#grouping').serialize() + "&src=" + imageURL.replace(/\+/g, "%2B") + "&name=" + fileName;
        $.ajax({
            url: base_url + "cbtbanksoal/uploadsoalimage",
            data: datapost,
            type: "POST",
            error: function (err) {
                console.error(err);
            },
            success: function (data) {
                $(id).attr('src', base_url + data.src);
                //$(id).attr('data-imgsrc', data.src);
                //console.log(data);
            },
            complete: function () {
                //console.log("Request finished.");
            }
        });
    }

    function removeUrl(str) {
        return str.replace(base_url, '');
    }

</script>