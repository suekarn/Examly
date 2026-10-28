<script>
(function(){
  try { document.documentElement.setAttribute('data-ai-theme', localStorage.getItem('AI_CONVERTER_THEME') || 'night'); } catch(e) { document.documentElement.setAttribute('data-ai-theme','night'); }
})();
</script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    let currentTitle = document.title;
    if (currentTitle.includes("<div") || currentTitle.includes("border:")) {
        document.title = "AI Converter Soal";
    }
});
</script>

<style>
/* =========================================================
   DARK NEON GREEN / CYAN THEME
   Visual-only theme layer: existing IDs, handlers and logic
   are intentionally preserved.
   ========================================================= */
:root{
    --neon:#00ff9c;
    --neon-2:#00e5ff;
    --neon-soft:#21f6b0;
    --bg:#020b0a;
    --bg-2:#061513;
    --panel:#071b18;
    --panel-2:#09231f;
    --line:rgba(0,255,156,.34);
    --line-cyan:rgba(0,229,255,.22);
    --text:#ecfffa;
    --muted:#8ab7ae;
    --danger:#ff4f9a;
    --warning:#ffd84d;
}
.content-wrapper{
    position:relative;
    min-height:100vh;
    overflow-x:hidden;
    background:
        radial-gradient(circle at 12% 15%, rgba(0,255,156,.10), transparent 26%),
        radial-gradient(circle at 90% 10%, rgba(0,229,255,.08), transparent 24%),
        linear-gradient(180deg,#020b0a 0%,#03110f 45%,#020807 100%) !important;
    color:var(--text) !important;
}
.content-wrapper:before{
    content:"";
    position:absolute;
    inset:0;
    pointer-events:none;
    opacity:.30;
    background-image:
        linear-gradient(90deg,rgba(0,255,156,.09) 1px,transparent 1px),
        linear-gradient(rgba(0,255,156,.055) 1px,transparent 1px);
    background-size:72px 72px;
    mask-image:linear-gradient(to bottom,black,transparent 88%);
}
.content-wrapper:after{
    content:"";
    position:absolute;
    top:0;right:0;
    width:42%;
    height:240px;
    pointer-events:none;
    opacity:.28;
    background:
        linear-gradient(135deg,transparent 46%,rgba(0,255,156,.45) 47%,transparent 48%) 0 0/90px 90px,
        linear-gradient(45deg,transparent 46%,rgba(0,229,255,.30) 47%,transparent 48%) 35px 35px/90px 90px;
}
.content-wrapper > *{position:relative;z-index:1}
.text-teal{color:var(--neon)!important}
.bg-teal{background:linear-gradient(135deg,#00e5a0,#00bfa6)!important;color:#00130e!important}
.btn-teal{
    background:linear-gradient(135deg,#00f0a4,#00c9b7)!important;
    color:#00130e!important;
    border:1px solid rgba(0,255,156,.65)!important;
    font-weight:800;
    box-shadow:0 0 14px rgba(0,255,156,.18);
}
.btn-teal:hover{filter:brightness(1.12);box-shadow:0 0 22px rgba(0,255,156,.30)}
.card-teal.card-outline{
    border:1px solid var(--line)!important;
    border-top:1px solid var(--neon)!important;
    border-radius:18px!important;
    box-shadow:0 0 0 1px rgba(0,255,156,.06),0 0 28px rgba(0,255,156,.08)!important;
    overflow:hidden;
}
.cursor-pointer{cursor:pointer}
.badge-type{font-size:11px;padding:4px 8px}

/* Main converter cards */
.content-wrapper .card-teal .card-header,
.content-wrapper .card-teal .card-footer,
.content-wrapper .card-teal .card-body{
    background:linear-gradient(180deg,rgba(5,28,24,.97),rgba(3,17,15,.98))!important;
    color:var(--text)!important;
    border-color:rgba(0,255,156,.18)!important;
}
.content-wrapper .card-teal .card-header{
    min-height:58px;
    border-bottom:1px solid rgba(0,255,156,.22)!important;
}
.content-wrapper .card-teal .card-title,
.content-wrapper .card-teal label,
.content-wrapper .card-teal .text-dark{color:#e9fff9!important}
.content-wrapper .card-teal .text-muted{color:#7faea4!important}
.content-wrapper .card-teal .form-control,
.content-wrapper .card-teal .custom-select{
    background:#041310!important;
    color:#dffef5!important;
    border:1px solid rgba(0,255,156,.30)!important;
}
.content-wrapper .card-teal .form-control:focus,
.content-wrapper .card-teal .custom-select:focus{
    border-color:var(--neon)!important;
    box-shadow:0 0 0 .12rem rgba(0,255,156,.13),0 0 18px rgba(0,255,156,.12)!important;
}
.terminal-input{
    height:420px;
    overflow-y:auto;
    font-family:ui-monospace,SFMono-Regular,Menlo,Monaco,Consolas,monospace;
    font-size:13px;
    line-height:1.6;
    white-space:pre-wrap;
    background:
        radial-gradient(circle at 15% 10%,rgba(0,255,156,.06),transparent 25%),
        #020d0c!important;
    color:#d9fff4!important;
    border:1px solid rgba(0,255,156,.42)!important;
    box-shadow:inset 0 0 28px rgba(0,255,156,.035),0 0 18px rgba(0,255,156,.05)!important;
    border-radius:12px!important;
}
.terminal-input:focus{
    border-color:var(--neon)!important;
    box-shadow:0 0 0 .12rem rgba(0,255,156,.12),inset 0 0 25px rgba(0,255,156,.04)!important;
}
.terminal-input table,#previewRenderArea table{border-collapse:collapse;margin:8px 0;width:100%}
.terminal-input th,.terminal-input td,#previewRenderArea th,#previewRenderArea td{
    border:1px solid rgba(0,255,156,.22);
    padding:4px 8px;
    font-size:12px;
}
#previewRenderArea{
    background:
        radial-gradient(circle at 50% 35%,rgba(0,255,156,.08),transparent 30%),
        #020d0c!important;
    color:#eafff8!important;
    border:1px solid rgba(0,255,156,.38)!important;
}
#previewRenderArea .preview-question{
    line-height:1.55;margin-bottom:10px!important;word-break:normal;overflow-wrap:anywhere
}
#previewRenderArea .preview-options{display:block;margin:6px 0 0;padding-left:0}
#previewRenderArea .preview-option{
    display:block!important;width:100%;margin:0 0 7px!important;padding:4px 8px 4px 4px;
    line-height:1.5;white-space:normal;word-break:normal;overflow-wrap:anywhere
}
#previewRenderArea .preview-option-label{display:inline-block;min-width:24px;margin-right:8px;font-weight:700;color:var(--neon)}
#previewRenderArea .preview-option-text{display:inline}
#previewRenderArea img{max-width:100%;height:auto;vertical-align:middle;border-radius:4px}
#previewRenderArea .preview-img{margin:6px 0;border:1px solid rgba(0,255,156,.22);background:#fff}
#previewRenderArea .preview-option .preview-img{margin:2px 6px 2px 0}
.content-wrapper .btn-primary{
    background:linear-gradient(135deg,#005cff,#006eff)!important;
    border-color:#00b8ff!important;
    box-shadow:0 0 16px rgba(0,140,255,.18);
}
.content-wrapper .btn-warning{
    background:linear-gradient(135deg,#ffd93d,#ffb400)!important;
    border-color:#ffe26a!important;
    color:#111!important;
}
.content-wrapper .btn-outline-danger{
    color:#ff62a5!important;border-color:#ff3f8f!important;background:rgba(255,30,120,.05)!important
}
.content-wrapper .badge-warning{background:#ffd21c!important;color:#111!important}

/* Dark table / form helpers */
.content-wrapper .table{color:#dffef5!important}
.content-wrapper .table thead th{background:#071d19!important;color:#8fffe0!important;border-color:rgba(0,255,156,.22)!important}
.content-wrapper .table td{border-color:rgba(0,255,156,.15)!important}
.content-wrapper .table-hover tbody tr:hover{background:rgba(0,255,156,.06)!important}
.content-wrapper .alert-light{background:#071d19!important;color:#a9d8ce!important;border-color:rgba(0,255,156,.18)!important}
.content-wrapper hr{border-color:rgba(0,255,156,.16)!important}
.content-wrapper code{color:#8fffe0;background:#041713}
.content-wrapper .custom-control-label{color:#dffef5!important}
.content-wrapper .btn-outline-secondary{color:#a9d8ce;border-color:rgba(0,255,156,.25)}
.content-wrapper .btn-outline-secondary:hover{background:rgba(0,255,156,.10);color:#fff}

/* Modal */
#apiKeyModal .modal-content{
    background:linear-gradient(180deg,#061a17,#020d0c)!important;
    color:#eafff8!important;
    border:1px solid rgba(0,255,156,.35)!important;
    border-radius:16px;
    box-shadow:0 0 45px rgba(0,255,156,.15);
}
#apiKeyModal .modal-header{border-bottom:1px solid rgba(0,255,156,.22)!important}
#apiKeyModal .modal-footer{border-top:1px solid rgba(0,255,156,.18)!important}
#apiKeyModal .text-dark,#apiKeyModal label{color:#eafff8!important}
#apiKeyModal .text-muted{color:#83aaa2!important}
#apiKeyModal .form-control{
    background:#03110f!important;color:#eafff8!important;border-color:rgba(0,255,156,.28)!important
}
#apiKeyModal .card.bg-light{background:#071d19!important;color:#eafff8!important;border-color:rgba(0,255,156,.22)!important}

/* Scrollbars */
.content-wrapper ::-webkit-scrollbar{width:8px;height:8px}
.content-wrapper ::-webkit-scrollbar-track{background:#020a09}
.content-wrapper ::-webkit-scrollbar-thumb{background:linear-gradient(#00e5a0,#00aebf);border-radius:10px}

/* Responsive */
@media(max-width:768px){
    .terminal-input{height:360px}
}

.import-option-line{display:block;margin:0;padding:0;line-height:1.45;}
.import-option-line .import-option-body{display:inline;}
.import-option-line p{display:inline;margin:0;padding:0;}
</style>

<style>
/* =========================================================
   THEME SWITCHER — SIANG / MALAM / MODE BACA
   Tidak mengubah fungsi converter; hanya tampilan dan keterbacaan.
   ========================================================= */
.ai-theme-switcher{
    display:flex;align-items:center;justify-content:flex-end;gap:6px;flex-wrap:nowrap;
    margin:0;position:absolute;top:12px;right:14px;z-index:20;
}
.ai-theme-switcher button{
    display:inline-flex;align-items:center;justify-content:center;
    width:36px;height:36px;min-width:36px;padding:0;
    border:1px solid rgba(0,255,156,.28);background:rgba(3,24,20,.78);color:#dffef5;
    border-radius:10px;font-size:15px;font-weight:800;
    cursor:pointer;transition:.18s ease;box-shadow:0 0 8px rgba(0,255,156,.04);
}
.ai-theme-switcher button:hover,.ai-theme-switcher button.active{
    background:linear-gradient(135deg,#00ffad,#00cfc0);color:#00130e;border-color:#00ffb0;
    box-shadow:0 0 14px rgba(0,255,156,.25)
}
.ai-theme-switcher button i{margin:0;font-size:15px}
.ai-theme-caption{font-size:10px;color:#78c7ba;font-weight:700;margin-right:3px}


/* Theme controls: icon-only cluster in the upper-right corner */
.smart-hero{position:relative}
.smart-topbar{padding-right:132px}
.ai-theme-switcher button:focus-visible{outline:2px solid #00ffb0;outline-offset:2px}
@media(max-width:575px){
  .smart-topbar{padding-right:0;padding-top:48px}
  .ai-theme-switcher{top:8px;right:8px}
  .ai-theme-switcher button{width:32px;height:32px;min-width:32px}
}

/* SIANG */
html[data-ai-theme="day"] .content-wrapper{
    background:linear-gradient(180deg,#f8fbff 0%,#eef5f4 55%,#e8f0ef 100%) !important;
    color:#17211f !important;
}
html[data-ai-theme="day"] .content-wrapper:before,
html[data-ai-theme="day"] .content-wrapper:after{opacity:.05}
html[data-ai-theme="day"] .smart-hero{
    color:#16201e !important;background:linear-gradient(180deg,#ffffff,#edf7f4)!important;
    border-color:#b9dcd3!important;box-shadow:0 5px 24px rgba(20,70,60,.10)!important;
}
html[data-ai-theme="day"] .smart-title{color:#12312a!important;text-shadow:none!important}
html[data-ai-theme="day"] .smart-sub,.ai-theme-switcher .ai-theme-caption{color:#55756d!important}
html[data-ai-theme="day"] .smart-nav button{background:#fff!important;color:#20332f!important;border-color:#b8d9d1!important;box-shadow:0 2px 8px rgba(0,0,0,.06)!important}
html[data-ai-theme="day"] .smart-nav button i{color:#008f70!important}
html[data-ai-theme="day"] .smart-nav button.active,html[data-ai-theme="day"] .smart-nav button:hover{background:linear-gradient(135deg,#00d99b,#00b9b0)!important;color:#001c15!important}
html[data-ai-theme="day"] .smart-nav button.active i,html[data-ai-theme="day"] .smart-nav button:hover i{color:#001c15!important}
html[data-ai-theme="day"] .smart-stat,html[data-ai-theme="day"] .smart-card{background:#fff!important;color:#1d2a27!important;border-color:#c5ddd7!important}
html[data-ai-theme="day"] .smart-card .card-header{background:#f4f9f7!important;color:#17312a!important}
html[data-ai-theme="day"] .card-teal .card-header,html[data-ai-theme="day"] .card-teal .card-footer,html[data-ai-theme="day"] .card-teal .card-body{background:#fff!important;color:#1d2926!important}
html[data-ai-theme="day"] .card-teal .card-title,html[data-ai-theme="day"] .card-teal label,html[data-ai-theme="day"] .card-teal .text-dark{color:#17231f!important}
html[data-ai-theme="day"] .card-teal .text-muted{color:#60756f!important}
html[data-ai-theme="day"] .terminal-input{background:#fff!important;color:#17231f!important;border-color:#8fcbbd!important;box-shadow:inset 0 0 12px rgba(0,120,100,.04)!important}
html[data-ai-theme="day"] #previewRenderArea{background:#fff!important;color:#17231f!important;border-color:#8fcbbd!important}
html[data-ai-theme="day"] .content-wrapper .table{color:#25332f!important}
html[data-ai-theme="day"] .content-wrapper .table thead th{background:#eaf5f2!important;color:#174b3f!important}
html[data-ai-theme="day"] .content-wrapper .table td{border-color:#c9ddd8!important}
html[data-ai-theme="day"] .content-wrapper .alert-light{background:#f4f8f7!important;color:#334a44!important}
html[data-ai-theme="day"] .ai-theme-switcher button{background:#fff;color:#263d37;border-color:#b8d9d1}

/* MODE BACA — kontras tinggi, lebih besar, latar hangat dan nyaman */
html[data-ai-theme="read"] .content-wrapper{
    background:linear-gradient(180deg,#f7f3e8 0%,#eee9dc 100%) !important;
    color:#20231f !important;
}
html[data-ai-theme="read"] .content-wrapper:before,html[data-ai-theme="read"] .content-wrapper:after{opacity:.025}
html[data-ai-theme="read"] .smart-hero{background:#fffdf6!important;color:#20231f!important;border-color:#d9ccb1!important;box-shadow:0 4px 20px rgba(75,55,25,.10)!important}
html[data-ai-theme="read"] .smart-title{color:#20231f!important;text-shadow:none!important}
html[data-ai-theme="read"] .smart-nav button{background:#fffdf6!important;color:#252820!important;border-color:#cfc4ab!important;box-shadow:none!important}
html[data-ai-theme="read"] .smart-nav button.active,html[data-ai-theme="read"] .smart-nav button:hover{background:#dff4eb!important;color:#10271f!important;border-color:#5a9d86!important}
html[data-ai-theme="read"] .smart-stat,html[data-ai-theme="read"] .smart-card{background:#fffdf8!important;color:#252820!important;border-color:#d6cbb5!important;box-shadow:0 2px 12px rgba(60,45,20,.07)!important}
html[data-ai-theme="read"] .smart-card .card-header{background:#f3eee2!important;color:#252820!important}
html[data-ai-theme="read"] .card-teal .card-header,html[data-ai-theme="read"] .card-teal .card-footer,html[data-ai-theme="read"] .card-teal .card-body{background:#fffdf8!important;color:#252820!important}
html[data-ai-theme="read"] .card-teal .card-title,html[data-ai-theme="read"] .card-teal label,html[data-ai-theme="read"] .card-teal .text-dark{color:#252820!important}
html[data-ai-theme="read"] .card-teal .text-muted{color:#625f55!important}
html[data-ai-theme="read"] .terminal-input{background:#fffdf8!important;color:#20231f!important;border-color:#b8ab8e!important;font-size:16px!important;line-height:1.75!important;letter-spacing:.1px}
html[data-ai-theme="read"] #previewRenderArea{background:#fffdf8!important;color:#20231f!important;border-color:#b8ab8e!important;font-size:17px!important}
html[data-ai-theme="read"] #previewRenderArea .preview-question{line-height:1.75!important}
html[data-ai-theme="read"] #previewRenderArea .preview-option{line-height:1.7!important;margin-bottom:9px!important}
html[data-ai-theme="read"] .content-wrapper .table{color:#292b25!important}
html[data-ai-theme="read"] .content-wrapper .table thead th{background:#eee8da!important;color:#2b493e!important}
html[data-ai-theme="read"] .content-wrapper .table td{border-color:#d5cbb7!important}
html[data-ai-theme="read"] .content-wrapper .form-control,html[data-ai-theme="read"] .content-wrapper .custom-select{background:#fffdf8!important;color:#20231f!important;border-color:#b8ab8e!important}
html[data-ai-theme="read"] .ai-theme-switcher button{background:#fffdf8;color:#2b2b26;border-color:#cfc4ab}
html[data-ai-theme="read"] .ai-theme-caption{color:#5d5a50!important}

/* Semua mode: jangan sampai teks tombol/ikon ikut hilang */
html[data-ai-theme="day"] .ai-theme-switcher button.active,html[data-ai-theme="read"] .ai-theme-switcher button.active{font-weight:900}
@media(max-width:768px){
    .ai-theme-switcher{margin-top:8px;gap:4px}
    .ai-theme-switcher button{padding:6px 9px;font-size:11px;min-height:34px}
    .ai-theme-caption{display:none}
}
</style>

<div class="content-wrapper pt-3">
<style>
/* =========================================================
   SMART AI API MANAGER — HEADER MATCH TO REFERENCE IMAGE
   ========================================================= */
.smart-manager{
    margin:0 auto 18px;
    padding:0 18px;
    max-width:1500px;
}
.smart-hero{
    position:relative;
    overflow:hidden;
    padding:14px 18px 12px;
    color:#ecfffa;
    border-radius:0 0 18px 18px;
    border:1px solid rgba(0,255,156,.30);
    border-top:1px solid rgba(0,255,156,.16);
    background:
        radial-gradient(circle at 12% 20%,rgba(0,255,156,.12),transparent 23%),
        radial-gradient(circle at 82% 15%,rgba(0,229,255,.10),transparent 25%),
        linear-gradient(180deg,rgba(3,17,15,.98),rgba(2,12,11,.98));
    box-shadow:0 0 30px rgba(0,255,156,.08),inset 0 -1px 0 rgba(0,255,156,.10);
}
.smart-hero:before{
    content:"";
    position:absolute;
    inset:0;
    opacity:.22;
    pointer-events:none;
    background:
        linear-gradient(135deg,transparent 45%,rgba(0,255,156,.55) 46%,transparent 47%) 0 0/110px 70px,
        linear-gradient(45deg,transparent 45%,rgba(0,229,255,.35) 46%,transparent 47%) 35px 20px/120px 80px;
}
.smart-hero:after{
    content:"";
    position:absolute;
    width:360px;height:160px;
    right:-80px;top:-95px;
    border-radius:50%;
    background:rgba(0,255,156,.08);
    filter:blur(2px);
    pointer-events:none;
}
.smart-topbar{
    position:relative;
    z-index:2;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:18px;
    min-height:58px;
}
.smart-brand{
    display:flex;
    align-items:center;
    justify-content:center;
    gap:12px;
    min-width:0;
    text-align:center;
}
.smart-brand-mark{
    width:42px;height:42px;
    flex:0 0 42px;
    border-radius:12px;
    display:flex;
    align-items:center;
    justify-content:center;
    color:#03130f;
    font-size:20px;
    background:linear-gradient(145deg,#00ffb0,#00bfa8);
    box-shadow:0 0 18px rgba(0,255,156,.38),inset 0 0 0 1px rgba(255,255,255,.24);
}
.smart-brand-copy{min-width:0}
.smart-title{
    font-size:21px;
    line-height:1.1;
    font-weight:900;
    letter-spacing:.1px;
    color:#eafff7;
    text-shadow:0 0 14px rgba(0,255,156,.20);
}
.smart-sub{
    margin-top:4px;
    color:#77bcb0;
    font-size:11px;
    letter-spacing:.25px;
}
.smart-header-actions{
    display:flex;
    align-items:center;
    gap:10px;
}
.smart-live-card,.smart-clock-card{
    min-height:48px;
    border:1px solid rgba(0,255,156,.24);
    border-radius:12px;
    background:rgba(3,22,18,.80);
    box-shadow:inset 0 0 20px rgba(0,255,156,.035),0 0 18px rgba(0,255,156,.05);
}
.smart-live-card{
    display:flex;align-items:center;gap:9px;padding:7px 13px;
}
.smart-live-dot{
    width:11px;height:11px;border-radius:50%;
    background:#00ff9c;
    box-shadow:0 0 0 4px rgba(0,255,156,.10),0 0 16px rgba(0,255,156,.85);
    animation:smartPulse 1.8s infinite;
}
.smart-live-text strong{display:block;font-size:12px;color:#eafff7}
.smart-live-text span{display:block;font-size:10px;color:#76c5b6;margin-top:1px}
.smart-clock-card{
    min-width:116px;
    padding:7px 12px;
    display:flex;
    align-items:center;
    gap:8px;
}
.smart-clock-card i{font-size:23px;color:#00e5ff;text-shadow:0 0 12px rgba(0,229,255,.55)}
.smart-clock-time{font-size:13px;font-weight:900;color:#eafff7;font-variant-numeric:tabular-nums}
.smart-clock-date{font-size:9px;color:#76c5b6;margin-top:1px}
@keyframes smartPulse{
    0%,100%{box-shadow:0 0 0 4px rgba(0,255,156,.10),0 0 10px rgba(0,255,156,.55)}
    50%{box-shadow:0 0 0 6px rgba(0,255,156,.06),0 0 22px rgba(0,255,156,.95)}
}
.smart-nav{
    position:relative;
    z-index:2;
    display:flex;
    gap:8px;
    flex-wrap:wrap;
    margin-top:12px;
    justify-content:center;
    align-items:center;
}
.smart-nav button{
    border:1px solid rgba(0,255,156,.26);
    background:linear-gradient(180deg,rgba(9,46,39,.92),rgba(4,25,22,.92));
    color:#dffef5;
    border-radius:10px;
    padding:10px 16px;
    min-height:44px;
    font-size:14px;
    font-weight:800;
    letter-spacing:.1px;
    box-shadow:inset 0 0 12px rgba(0,255,156,.035),0 0 8px rgba(0,255,156,.03);
    transition:.18s ease;
}
.smart-nav button i{color:#75ffe0;margin-right:5px}
.smart-nav button.active,
.smart-nav button:hover{
    color:#00130e;
    border-color:#00ffb0;
    background:linear-gradient(135deg,#00ffad,#00cfc0);
    box-shadow:0 0 16px rgba(0,255,156,.34),inset 0 0 0 1px rgba(255,255,255,.24);
}
.smart-nav button.active i,.smart-nav button:hover i{color:#00130e}
.smart-panel{display:none;margin-top:10px}
.smart-panel.active{display:block}
.smart-stat{
    border:1px solid rgba(0,255,156,.20)!important;
    border-radius:12px;
    background:linear-gradient(180deg,#071c18,#04110f)!important;
    color:#eafff8;
    box-shadow:0 4px 20px rgba(0,0,0,.25);
}
.smart-stat .num{font-size:24px;font-weight:900;color:#eafff8}
.smart-stat small,.smart-card .text-muted{color:#78a9a0!important}
.smart-card{
    border:1px solid rgba(0,255,156,.22)!important;
    border-radius:12px;
    overflow:hidden;
    background:#04120f!important;
    color:#eafff8;
    box-shadow:0 5px 22px rgba(0,0,0,.24);
}
.smart-card .card-header{
    background:linear-gradient(180deg,#09231f,#061713)!important;
    color:#eafff8!important;
    border-bottom:1px solid rgba(0,255,156,.18)!important;
}
.smart-card .card-body{background:#04120f!important}
.smart-status{font-size:10px;font-weight:800;border-radius:20px;padding:4px 8px}
.smart-status.healthy{background:rgba(0,255,156,.13);color:#43ffb9;border:1px solid rgba(0,255,156,.20)}
.smart-status.cooldown,.smart-status.rate_limited{background:rgba(255,216,77,.12);color:#ffe27b}
.smart-status.error,.smart-status.disabled,.smart-status.invalid_key{background:rgba(255,79,154,.12);color:#ff75b3}
.smart-status.model_error{background:rgba(133,113,255,.13);color:#a99dff}
.smart-status.unknown{background:rgba(148,180,174,.10);color:#9abcb5}
.smart-table{font-size:12px}
.smart-table th{white-space:nowrap;background:#061a17!important;color:#7fffe0!important;border-color:rgba(0,255,156,.18)!important}
.smart-table td{border-color:rgba(0,255,156,.12)!important;color:#d7f9f0!important}
.smart-table tbody tr:hover{background:rgba(0,255,156,.055)!important}
.smart-mono{font-family:ui-monospace,SFMono-Regular,Menlo,monospace}
.smart-log{max-height:360px;overflow:auto}
.smart-progress{height:7px;border-radius:20px;background:#09231f;overflow:hidden}
.smart-progress>span{display:block;height:100%;background:linear-gradient(90deg,#00e5ff,#00ff9c);box-shadow:0 0 10px rgba(0,255,156,.45)}
.smart-health-grid{display:grid;grid-template-columns:repeat(5,minmax(0,1fr));gap:10px;margin-bottom:12px}
.smart-health-mini{
    border:1px solid rgba(0,255,156,.18);
    border-radius:11px;padding:11px;
    background:#061a17;
}
.smart-health-mini .v{font-size:20px;font-weight:900;color:#eafff8}
.smart-health-mini .l{font-size:10px;color:#6faaa0;text-transform:uppercase;font-weight:800;letter-spacing:.3px}
.smart-http{font-size:10px;font-weight:800;border-radius:6px;padding:3px 6px;background:#0b211d;color:#8bb8af}
.smart-http.ok{background:rgba(0,255,156,.12);color:#43ffb9}
.smart-http.bad{background:rgba(255,79,154,.12);color:#ff75b3}
.smart-refreshing{opacity:.6;pointer-events:none;transition:opacity .15s ease}

/* ===== Ringkasan Pemakaian Provider: bar persen limit ===== */
.sq-legend{display:flex;flex-wrap:wrap;gap:6px 16px;font-size:11px;color:#8ab7ae;margin-bottom:12px}
.sq-legend i{display:inline-block;width:10px;height:10px;border-radius:3px;margin-right:5px;vertical-align:-1px}
.sq-item{--c1:#00ff9c;--c2:#00e5ff;--glow:rgba(0,255,156,.45);
    padding:12px 14px 11px;margin-bottom:10px;border-radius:12px;
    border:1px solid rgba(0,255,156,.16);
    background:linear-gradient(180deg,#071c18,#04110f)}
.sq-item.sq-mid{--c1:#ffd84d;--c2:#ffb400;--glow:rgba(255,200,40,.45);border-color:rgba(255,216,77,.25)}
.sq-item.sq-high{--c1:#ff9f43;--c2:#ff6b3d;--glow:rgba(255,120,60,.5);border-color:rgba(255,140,60,.32)}
.sq-item.sq-full{--c1:#ff4f9a;--c2:#ff2e63;--glow:rgba(255,50,110,.55);border-color:rgba(255,79,154,.45)}
.sq-head{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:8px}
.sq-name{font-weight:800;font-size:13px;color:#eafff8;display:flex;align-items:center;gap:8px;flex-wrap:wrap;min-width:0}
.sq-chip{font-size:10px;font-weight:800;padding:3px 9px;border-radius:20px;color:var(--c1);
    background:rgba(255,255,255,.04);border:1px solid var(--c1);letter-spacing:.2px}
.sq-pct{font-size:22px;font-weight:900;line-height:1;color:var(--c1);text-shadow:0 0 14px var(--glow);font-variant-numeric:tabular-nums}
.sq-pct small{font-size:12px;font-weight:800;margin-left:1px}
.sq-track{position:relative;height:14px;border-radius:20px;background:#08201c;overflow:hidden;
    box-shadow:inset 0 1px 4px rgba(0,0,0,.55),0 0 0 1px rgba(0,255,156,.10)}
.sq-fill{display:block;height:100%;min-width:0;border-radius:20px;
    background:linear-gradient(90deg,var(--c1),var(--c2));
    box-shadow:0 0 12px var(--glow);
    animation:sqGrow .9s cubic-bezier(.2,.8,.2,1)}
.sq-item.sq-full .sq-fill{animation:sqGrow .9s cubic-bezier(.2,.8,.2,1),sqPulse 1.6s ease-in-out infinite .9s}
.sq-mark{position:absolute;top:0;bottom:0;width:1px;background:rgba(255,255,255,.22)}
.sq-scale{display:flex;justify-content:space-between;font-size:9px;color:#5f8f86;margin:3px 1px 0}
.sq-meta{display:flex;flex-wrap:wrap;gap:4px 16px;margin-top:7px;font-size:11px;color:#8ab7ae}
.sq-meta b{color:#eafff8}
@keyframes sqGrow{from{width:0}}
@keyframes sqPulse{0%,100%{filter:brightness(1)}50%{filter:brightness(1.35)}}
@media(max-width:992px){
    .smart-header-actions{gap:6px}
    .smart-live-card{display:none}
    .smart-health-grid{grid-template-columns:repeat(3,minmax(0,1fr))}
}
@media(max-width:768px){
    .smart-manager{padding:0 8px}
    .smart-topbar{align-items:flex-start}
    .smart-brand-mark{width:38px;height:38px;flex-basis:38px}
    .smart-title{font-size:17px}
    .smart-sub{font-size:9px}
    .smart-clock-card{min-width:96px}
    .smart-clock-card i{font-size:19px}
    .smart-clock-time{font-size:11px}
    .smart-nav{overflow-x:auto;flex-wrap:nowrap;justify-content:flex-start;padding-bottom:3px}
    .smart-nav button{white-space:nowrap}
}
@media(max-width:576px){
    .smart-clock-card{padding:6px 8px}
    .smart-clock-date{font-size:8px}
    .smart-health-grid{grid-template-columns:repeat(2,minmax(0,1fr))}
}

/* ===== Premium navigation: efek sentuh + dropdown di layar kecil ===== */
.smart-navwrap{position:relative;z-index:5}
.smart-nav-toggle{display:none}
.smart-nav button{position:relative;overflow:hidden;-webkit-tap-highlight-color:transparent;touch-action:manipulation;cursor:pointer}
.smart-nav button:active{transform:scale(.95)}
.smart-nav button .rip{position:absolute;border-radius:50%;background:rgba(255,255,255,.55);transform:scale(0);animation:snRip .55s ease-out forwards;pointer-events:none}
@keyframes snRip{to{transform:scale(1);opacity:0}}
@keyframes snDrop{from{opacity:0;transform:translateY(-8px) scale(.98)}to{opacity:1;transform:none}}
@keyframes snItem{from{opacity:0;transform:translateX(-10px)}to{opacity:1;transform:none}}
@media(max-width:768px){
  .smart-nav-toggle{
    display:flex;align-items:center;justify-content:space-between;width:100%;margin-top:12px;
    min-height:48px;padding:10px 16px;border-radius:14px;color:#eafff7;font-size:15px;font-weight:800;
    border:1px solid rgba(0,255,156,.4);
    background:linear-gradient(135deg,rgba(9,56,47,.96),rgba(4,25,22,.96));
    box-shadow:0 0 18px rgba(0,255,156,.14),inset 0 0 0 1px rgba(255,255,255,.05);
    -webkit-tap-highlight-color:transparent;touch-action:manipulation;transition:.2s ease;
  }
  .smart-nav-toggle:active{transform:scale(.98)}
  .smart-nav-toggle .snt-left{display:flex;align-items:center;gap:10px}
  .smart-nav-toggle .snt-left i{color:#00ffb0;font-size:16px;width:20px;text-align:center}
  .smart-nav-toggle .snt-caret{color:#75ffe0;font-size:13px;transition:transform .28s ease}
  .smart-navwrap.open .smart-nav-toggle{border-color:#00ffb0;box-shadow:0 0 24px rgba(0,255,156,.32)}
  .smart-navwrap.open .snt-caret{transform:rotate(180deg)}
  .smart-navwrap .smart-nav{
    display:none;position:relative;margin:8px 0 0;padding:8px;
    flex-direction:column;flex-wrap:nowrap;align-items:stretch;justify-content:flex-start;gap:4px;
    overflow:visible;max-height:70vh;overflow-y:auto;-webkit-overflow-scrolling:touch;
    border-radius:16px;border:1px solid rgba(0,255,156,.35);
    background:rgba(4,22,19,.94);-webkit-backdrop-filter:blur(16px);backdrop-filter:blur(16px);
    box-shadow:0 18px 40px rgba(0,0,0,.55),0 0 26px rgba(0,255,156,.18);
  }
  .smart-navwrap.open .smart-nav{display:flex;animation:snDrop .24s ease-out}
  .smart-navwrap .smart-nav button{
    display:flex;align-items:center;width:100%;text-align:left;white-space:nowrap;
    min-height:48px;padding:12px 14px;font-size:15px;border-radius:11px;border-color:transparent;
    background:transparent;box-shadow:none;
  }
  .smart-navwrap.open .smart-nav button{animation:snItem .28s ease-out both}
  .smart-navwrap.open .smart-nav button:nth-child(1){animation-delay:.02s}
  .smart-navwrap.open .smart-nav button:nth-child(2){animation-delay:.05s}
  .smart-navwrap.open .smart-nav button:nth-child(3){animation-delay:.08s}
  .smart-navwrap.open .smart-nav button:nth-child(4){animation-delay:.11s}
  .smart-navwrap.open .smart-nav button:nth-child(5){animation-delay:.14s}
  .smart-navwrap.open .smart-nav button:nth-child(6){animation-delay:.17s}
  .smart-navwrap.open .smart-nav button:nth-child(7){animation-delay:.2s}
  .smart-navwrap.open .smart-nav button:nth-child(8){animation-delay:.23s}
  .smart-navwrap .smart-nav button i{width:24px;margin-right:12px;text-align:center;font-size:16px}
  .smart-navwrap .smart-nav button.active{box-shadow:0 0 14px rgba(0,255,156,.3)}
}

/* =========================================================
   HERO PREMIUM — aurora, grid bergerak, logo ber-ring, judul shimmer
   (visual saja, tidak mengubah logika)
   ========================================================= */
.smart-hero{
    padding:26px 18px 16px;
    border-radius:0 0 22px 22px;
    background:
        radial-gradient(circle at 12% 20%,rgba(0,255,156,.16),transparent 28%),
        radial-gradient(circle at 85% 10%,rgba(0,229,255,.16),transparent 30%),
        linear-gradient(180deg,#031411,#020c0b);
    box-shadow:0 0 0 1px rgba(0,255,156,.18),0 10px 50px rgba(0,255,156,.12),0 0 90px rgba(0,229,255,.06),inset 0 -1px 0 rgba(0,255,156,.14);
}
/* garis tepi neon berjalan */
.smart-hero .sh-edge{
    position:absolute;left:0;right:0;bottom:0;height:2px;z-index:3;pointer-events:none;
    background:linear-gradient(90deg,transparent,#00ff9c,#00e5ff,#00ff9c,transparent);
    background-size:240% 100%;animation:shEdge 5s linear infinite;
    box-shadow:0 0 14px rgba(0,255,156,.7);
}
/* aurora */
.smart-hero .sh-aurora{
    position:absolute;inset:-40%;z-index:0;pointer-events:none;opacity:.55;filter:blur(38px);
    background:
        radial-gradient(40% 35% at 20% 40%,rgba(0,255,156,.35),transparent 70%),
        radial-gradient(35% 40% at 75% 55%,rgba(0,229,255,.30),transparent 70%),
        radial-gradient(30% 30% at 50% 20%,rgba(120,90,255,.18),transparent 70%);
    animation:shAurora 14s ease-in-out infinite alternate;
}
/* grid perspektif halus */
.smart-hero .sh-grid{
    position:absolute;inset:0;z-index:0;pointer-events:none;opacity:.16;
    background-image:
        linear-gradient(rgba(0,255,156,.55) 1px,transparent 1px),
        linear-gradient(90deg,rgba(0,255,156,.55) 1px,transparent 1px);
    background-size:38px 38px;
    -webkit-mask-image:radial-gradient(ellipse at 50% 40%,#000 15%,transparent 70%);
            mask-image:radial-gradient(ellipse at 50% 40%,#000 15%,transparent 70%);
    animation:shGrid 9s linear infinite;
}
/* garis scan */
.smart-hero .sh-scan{
    position:absolute;top:0;bottom:0;width:140px;left:-160px;z-index:1;pointer-events:none;
    background:linear-gradient(90deg,transparent,rgba(0,255,156,.10),rgba(0,229,255,.18),transparent);
    transform:skewX(-18deg);animation:shScan 7s ease-in-out infinite;
}
/* partikel */
.smart-hero .sh-dots{position:absolute;inset:0;z-index:1;pointer-events:none;overflow:hidden}
.smart-hero .sh-dots i{
    position:absolute;bottom:-10px;width:4px;height:4px;border-radius:50%;
    background:#00ff9c;box-shadow:0 0 10px #00ff9c;opacity:0;animation:shRise 8s linear infinite;
}
.smart-hero .sh-dots i:nth-child(1){left:6%;animation-delay:0s}
.smart-hero .sh-dots i:nth-child(2){left:17%;animation-delay:2.1s;background:#00e5ff;box-shadow:0 0 10px #00e5ff}
.smart-hero .sh-dots i:nth-child(3){left:29%;animation-delay:4.4s}
.smart-hero .sh-dots i:nth-child(4){left:41%;animation-delay:1.2s;background:#00e5ff;box-shadow:0 0 10px #00e5ff}
.smart-hero .sh-dots i:nth-child(5){left:55%;animation-delay:5.6s}
.smart-hero .sh-dots i:nth-child(6){left:67%;animation-delay:3.1s;background:#00e5ff;box-shadow:0 0 10px #00e5ff}
.smart-hero .sh-dots i:nth-child(7){left:78%;animation-delay:6.5s}
.smart-hero .sh-dots i:nth-child(8){left:90%;animation-delay:1.8s;background:#00e5ff;box-shadow:0 0 10px #00e5ff}

.smart-topbar{min-height:84px}
.smart-brand{gap:18px}

/* logo dengan ring berputar */
.smart-brand-mark{
    position:relative;width:62px;height:62px;flex:0 0 62px;border-radius:18px;font-size:28px;
    background:linear-gradient(145deg,#00ffb8,#00c2b0 60%,#00a8c8);
    box-shadow:0 0 28px rgba(0,255,156,.55),0 0 60px rgba(0,229,255,.25),inset 0 0 0 1px rgba(255,255,255,.35);
    animation:shFloat 4.5s ease-in-out infinite;
}
.smart-brand-mark:before{
    content:"";position:absolute;inset:-6px;border-radius:23px;padding:2px;pointer-events:none;
    background:conic-gradient(from 0deg,transparent 0 55%,#00ff9c 75%,#00e5ff 90%,transparent 100%);
    -webkit-mask:linear-gradient(#000 0 0) content-box,linear-gradient(#000 0 0);
    -webkit-mask-composite:xor;mask-composite:exclude;
    animation:shSpin 3.2s linear infinite;
}
.smart-brand-mark:after{
    content:"";position:absolute;inset:0;border-radius:18px;pointer-events:none;
    background:linear-gradient(120deg,transparent 30%,rgba(255,255,255,.55) 50%,transparent 70%);
    background-size:250% 100%;animation:shGloss 3.6s ease-in-out infinite;
}
.smart-brand-mark i{position:relative;z-index:1;filter:drop-shadow(0 2px 4px rgba(0,60,40,.35))}

/* judul */
.smart-brand-copy{text-align:left}
.smart-title{
    font-size:40px;line-height:1;font-weight:900;letter-spacing:-.5px;
    display:flex;align-items:center;gap:12px;flex-wrap:wrap;
    background:linear-gradient(90deg,#eafff7 0%,#00ff9c 28%,#00e5ff 50%,#00ff9c 72%,#eafff7 100%);
    background-size:260% 100%;
    -webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent;color:transparent;
    animation:shShine 6s linear infinite;
    filter:drop-shadow(0 0 16px rgba(0,255,156,.35));
}
.smart-title .st-badge{
    -webkit-text-fill-color:#03130f;color:#03130f;filter:none;
    font-size:11px;font-weight:900;letter-spacing:1.4px;padding:5px 10px;border-radius:999px;
    background:linear-gradient(135deg,#00ff9c,#00e5ff);
    box-shadow:0 0 16px rgba(0,255,156,.55);
    animation:shBadge 2.4s ease-in-out infinite;
}
.smart-sub{margin-top:10px;display:flex;flex-wrap:wrap;gap:8px;font-size:12px}
.smart-sub .sc{
    display:inline-flex;align-items:center;gap:7px;padding:5px 12px;border-radius:999px;
    color:#bff7e6;letter-spacing:.3px;font-weight:600;
    background:rgba(0,255,156,.07);border:1px solid rgba(0,255,156,.28);
    backdrop-filter:blur(4px);transition:.25s;
}
.smart-sub .sc i{color:#00e5ff;text-shadow:0 0 10px rgba(0,229,255,.8)}
.smart-sub .sc:hover{transform:translateY(-2px);background:rgba(0,255,156,.16);box-shadow:0 0 18px rgba(0,255,156,.3)}

@keyframes shSpin{to{transform:rotate(360deg)}}
@keyframes shFloat{0%,100%{transform:translateY(0)}50%{transform:translateY(-4px)}}
@keyframes shGloss{0%,60%{background-position:150% 0}100%{background-position:-100% 0}}
@keyframes shShine{to{background-position:260% 0}}
@keyframes shBadge{0%,100%{box-shadow:0 0 10px rgba(0,255,156,.4)}50%{box-shadow:0 0 22px rgba(0,229,255,.9)}}
@keyframes shEdge{to{background-position:240% 0}}
@keyframes shAurora{0%{transform:translate3d(-4%,-2%,0) rotate(0)}100%{transform:translate3d(4%,3%,0) rotate(8deg)}}
@keyframes shGrid{to{background-position:38px 38px}}
@keyframes shScan{0%{left:-160px}55%,100%{left:110%}}
@keyframes shRise{0%{transform:translateY(0);opacity:0}10%{opacity:.9}100%{transform:translateY(-150px);opacity:0}}
@media(max-width:768px){
    .smart-hero{padding:18px 12px 12px}
    .smart-brand{gap:12px}
    .smart-brand-mark{width:48px;height:48px;flex-basis:48px;font-size:22px;border-radius:14px}
    .smart-title{font-size:26px}
    .smart-sub{font-size:10px;gap:6px}
    .smart-sub .sc{padding:4px 9px}
}
@media(prefers-reduced-motion:reduce){
    .smart-hero *,.smart-hero:before,.smart-hero:after{animation:none!important}
}

/* logo animasi di samping SMART */
.smart-title .st-orbit{
    -webkit-text-fill-color:#00ff9c;color:#00ff9c;filter:none;
    position:relative;width:40px;height:40px;flex:0 0 40px;border-radius:50%;
    display:inline-flex;align-items:center;justify-content:center;font-size:17px;
    background:radial-gradient(circle,rgba(0,255,156,.22),rgba(0,255,156,.04) 70%);
    border:1px solid rgba(0,255,156,.45);
    box-shadow:0 0 18px rgba(0,255,156,.45),inset 0 0 12px rgba(0,229,255,.25);
    animation:shOrbitPulse 2.6s ease-in-out infinite;
}
.smart-title .st-orbit i{text-shadow:0 0 12px #00ff9c;animation:shChip 2.6s ease-in-out infinite}
.smart-title .st-orbit b{
    position:absolute;inset:-7px;border-radius:50%;pointer-events:none;
    border:1px dashed rgba(0,229,255,.55);animation:shSpin 5s linear infinite;
}
.smart-title .st-orbit b:before{
    content:"";position:absolute;top:-4px;left:50%;margin-left:-4px;width:8px;height:8px;border-radius:50%;
    background:#00e5ff;box-shadow:0 0 12px #00e5ff,0 0 24px #00e5ff;
}
.smart-title .st-orbit b+b{inset:-14px;border:1px dotted rgba(0,255,156,.35);animation:shSpin 8s linear infinite reverse}
.smart-title .st-orbit b+b:before{background:#00ff9c;box-shadow:0 0 12px #00ff9c;width:6px;height:6px;margin-left:-3px;top:-3px}
@keyframes shOrbitPulse{0%,100%{box-shadow:0 0 14px rgba(0,255,156,.4),inset 0 0 10px rgba(0,229,255,.2)}50%{box-shadow:0 0 28px rgba(0,255,156,.8),inset 0 0 16px rgba(0,229,255,.45)}}
@keyframes shChip{0%,100%{transform:scale(1)}50%{transform:scale(1.18)}}
@media(max-width:768px){.smart-title .st-orbit{width:30px;height:30px;flex-basis:30px;font-size:13px}}

/* badge "Soal Terdeteksi" */
#countBadge.count-pill{
    position:relative;display:inline-flex;align-items:center;gap:10px;
    padding:5px 14px 5px 6px;border-radius:999px;overflow:hidden;
    font-weight:800;line-height:1;white-space:nowrap;border:1px solid var(--cp-b);
    color:var(--cp-c);background:linear-gradient(135deg,var(--cp-bg1),var(--cp-bg2));
    box-shadow:0 0 18px var(--cp-glow),inset 0 0 12px rgba(255,255,255,.04);
    transition:box-shadow .3s,border-color .3s;
}
#countBadge.count-pill:before{
    content:"";position:absolute;top:0;bottom:0;left:-60%;width:40%;pointer-events:none;
    background:linear-gradient(90deg,transparent,rgba(255,255,255,.28),transparent);
    transform:skewX(-20deg);animation:cpShine 3.4s ease-in-out infinite;
}
#countBadge.count-pill.idle{--cp-c:#8ab7ae;--cp-b:rgba(138,183,174,.35);--cp-bg1:rgba(10,30,27,.9);--cp-bg2:rgba(8,24,21,.9);--cp-glow:rgba(0,0,0,0)}
#countBadge.count-pill.idle:before{display:none}
#countBadge.count-pill.draft{--cp-c:#ffe27a;--cp-b:rgba(255,216,77,.6);--cp-bg1:rgba(60,45,0,.75);--cp-bg2:rgba(40,30,0,.85);--cp-glow:rgba(255,216,77,.35)}
#countBadge.count-pill.ok{--cp-c:#7dffc9;--cp-b:rgba(0,255,156,.65);--cp-bg1:rgba(0,60,40,.75);--cp-bg2:rgba(0,40,28,.85);--cp-glow:rgba(0,255,156,.45)}
#countBadge .cp-ico{
    position:relative;width:30px;height:30px;border-radius:50%;flex:0 0 30px;
    display:flex;align-items:center;justify-content:center;font-size:13px;color:#06140f;
    background:linear-gradient(145deg,var(--cp-c),var(--cp-b));
}
#countBadge .cp-ico:after{
    content:"";position:absolute;inset:-4px;border-radius:50%;border:2px solid var(--cp-c);opacity:0;
    animation:cpPing 2s ease-out infinite;
}
#countBadge.idle .cp-ico{background:rgba(138,183,174,.25);color:#8ab7ae}
#countBadge.idle .cp-ico:after{display:none}
#countBadge .cp-num{font-size:22px;font-weight:900;font-variant-numeric:tabular-nums;color:#fff;text-shadow:0 0 12px var(--cp-c)}
#countBadge.idle .cp-num{color:#8ab7ae;text-shadow:none}
#countBadge .cp-txt{display:flex;flex-direction:column;gap:3px;text-align:left}
#countBadge .cp-txt b{font-size:11px;letter-spacing:.6px;text-transform:uppercase}
#countBadge .cp-txt small{font-size:9px;letter-spacing:1.2px;font-weight:800;opacity:.85}
#countBadge.pop{animation:cpPop .45s ease}
@keyframes cpShine{0%,55%{left:-60%}100%{left:130%}}
@keyframes cpPing{0%{transform:scale(.8);opacity:.8}100%{transform:scale(1.7);opacity:0}}
@keyframes cpPop{0%{transform:scale(1)}40%{transform:scale(1.12)}100%{transform:scale(1)}}
@media(prefers-reduced-motion:reduce){#countBadge,#countBadge *{animation:none!important}}
</style>

<div class="smart-manager container-fluid">
  <div class="smart-hero">
    <span class="sh-aurora"></span><span class="sh-grid"></span><span class="sh-scan"></span>
    <span class="sh-dots"><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i></span>
    <span class="sh-edge"></span>
    <div class="smart-topbar">
      <div class="smart-brand">
        <div class="smart-brand-mark"><i class="fas fa-shield-alt"></i></div>
        <div class="smart-brand-copy">
          <div class="smart-title">AI Converter <span class="st-badge">SMART</span><span class="st-orbit" aria-hidden="true"><i class="fas fa-microchip"></i><b></b><b></b></span></div>
        </div>
      </div>
      <div class="ai-theme-switcher" id="aiThemeSwitcher" aria-label="Pilihan tema tampilan">
        <button type="button" data-ai-theme-btn="day" title="Mode siang" aria-label="Mode siang"><i class="fas fa-sun" aria-hidden="true"></i></button>
        <button type="button" data-ai-theme-btn="night" title="Mode malam" aria-label="Mode malam"><i class="fas fa-moon" aria-hidden="true"></i></button>
        <button type="button" data-ai-theme-btn="read" title="Mode baca" aria-label="Mode baca"><i class="fas fa-book-open" aria-hidden="true"></i></button>
      </div>
    </div>
    <div class="smart-navwrap" id="smartNavWrap">
    <button type="button" class="smart-nav-toggle" id="smartNavToggle" aria-haspopup="true" aria-expanded="false">
      <span class="snt-left"><i class="fas fa-bars" id="smartNavIcon"></i><span id="smartNavLabel">Menu</span></span>
      <i class="fas fa-chevron-down snt-caret"></i>
    </button>
    <div class="smart-nav" id="smartNav">
      <button data-tab="dashboard"><i class="fas fa-home"></i>Dashboard</button>
      <button data-tab="providers"><i class="fas fa-network-wired"></i>Providers</button>
      <button data-tab="keys"><i class="fas fa-key"></i>API Keys</button>
      <button data-tab="models"><i class="fas fa-cubes"></i>Models</button>
      <button data-tab="health"><i class="fas fa-heartbeat"></i>Health Check</button>
      <button data-tab="usage"><i class="fas fa-chart-line"></i>Usage</button>
      <button data-tab="logs"><i class="fas fa-list-alt"></i>Logs</button>
      <button data-tab="settings"><i class="fas fa-sliders-h"></i>Settings</button>
    </div>
    </div>
  </div>

  <div id="smart-dashboard" class="smart-panel">
    <div class="row mt-2">
      <div class="col-md-3 col-6"><div class="card smart-stat"><div class="card-body p-3"><small class="text-muted">Providers</small><div class="num" id="smProviders">0</div></div></div></div>
      <div class="col-md-3 col-6"><div class="card smart-stat"><div class="card-body p-3"><small class="text-muted">API Keys Aktif</small><div class="num" id="smKeys">0</div></div></div></div>
      <div class="col-md-3 col-6"><div class="card smart-stat"><div class="card-body p-3"><small class="text-muted">Request</small><div class="num" id="smRequests">0</div></div></div></div>
      <div class="col-md-3 col-6"><div class="card smart-stat"><div class="card-body p-3"><small class="text-muted">Success Rate</small><div class="num text-success" id="smSuccess">0%</div></div></div></div>
    </div>
    <div class="card smart-card mt-2"><div class="card-header bg-white font-weight-bold"><i class="fas fa-brain text-teal mr-1"></i> Smart Routing Status</div><div class="card-body p-0"><div class="table-responsive"><table class="table table-hover smart-table mb-0"><thead><tr><th>Provider</th><th>Keys</th><th>Healthy</th><th>Cooldown</th><th>Priority</th><th>Status</th></tr></thead><tbody id="smProviderRows"><tr><td colspan="6" class="text-center text-muted p-4">Memuat...</td></tr></tbody></table></div></div></div>
  </div>

  <div id="smart-providers" class="smart-panel"><div class="card smart-card"><div class="card-header bg-white font-weight-bold">Providers & Smart Priority</div><div class="card-body"><div class="table-responsive"><table class="table smart-table"><thead><tr><th>Provider</th><th>Enabled</th><th>Priority</th><th>Models</th><th>Action</th></tr></thead><tbody id="smProvidersRows"></tbody></table></div></div></div></div>
  <div id="smart-keys" class="smart-panel"><div class="card smart-card"><div class="card-header bg-white font-weight-bold d-flex justify-content-between"><span>API Keys</span><button class="btn btn-teal btn-sm" onclick="openApiKeyModal()"><i class="fas fa-key mr-1"></i> Kelola Key</button></div><div class="card-body"><div class="alert alert-light border small mb-2">Key lama tetap digunakan. Smart Manager menambahkan status health, cooldown, statistik sukses/gagal, dan urutan otomatis.</div><div class="table-responsive"><table class="table smart-table"><thead><tr><th>Provider</th><th>Key</th><th>Status</th><th>Request</th><th>Success</th><th>Last Use</th></tr></thead><tbody id="smKeyRows"></tbody></table></div></div></div></div>
  <div id="smart-models" class="smart-panel"><div class="card smart-card"><div class="card-header bg-white font-weight-bold d-flex justify-content-between align-items-center flex-wrap"><span>Model Manager</span><span><button class="btn btn-xs btn-outline-primary mr-1" onclick="smartTestAllModels()" id="btnTestAllModels"><i class="fas fa-vial mr-1"></i>Tes Semua Model</button><button class="btn btn-xs btn-outline-warning mr-1" onclick="smartModelBulk('disable_failed')"><i class="fas fa-ban mr-1"></i>Matikan yang Error</button><button class="btn btn-xs btn-outline-success" onclick="smartModelBulk('enable_all')"><i class="fas fa-power-off mr-1"></i>Aktifkan Semua</button></span></div><div class="card-body"><div id="smModelSummary" class="small text-muted mb-2"></div><div class="table-responsive"><table class="table smart-table"><thead><tr><th>Provider</th><th>Model</th><th>Urutan</th><th>Status</th><th>Hasil Tes</th><th>Success</th><th>Failed</th><th>Aksi</th></tr></thead><tbody id="smModelRows"></tbody></table></div><div class="small text-muted mt-2">Model yang <b>Mati</b> tidak dipakai saat konversi maupun Health Check. Urutan ▲▼ menentukan model yang dicoba lebih dulu dalam satu provider.</div></div></div></div>
  <div id="smart-health" class="smart-panel"><div class="card smart-card"><div class="card-header bg-white font-weight-bold d-flex justify-content-between"><span><i class="fas fa-heartbeat text-danger mr-1"></i> Health Check</span><button class="btn btn-warning btn-sm" onclick="smartHealthAll()">Tes Semua Key</button></div><div class="card-body"><div id="smHealthSummary" class="small text-muted mb-2"></div>
<div class="smart-health-grid">
  <div class="smart-health-mini"><div class="l">Healthy</div><div class="v text-success" id="smHealthHealthy">0</div></div>
  <div class="smart-health-mini"><div class="l">Cooldown</div><div class="v text-warning" id="smHealthCooldown">0</div></div>
  <div class="smart-health-mini"><div class="l">Error</div><div class="v text-danger" id="smHealthError">0</div></div>
  <div class="smart-health-mini"><div class="l">Disabled</div><div class="v text-secondary" id="smHealthDisabled">0</div></div>
  <div class="smart-health-mini"><div class="l">HTTP Error</div><div class="v text-danger" id="smHealthHttpError">0</div></div>
</div>
<div class="table-responsive"><table class="table smart-table">
<thead><tr><th>Provider</th><th>Key</th><th>Model</th><th>Status</th><th>HTTP</th><th>Response</th><th>Pesan</th><th>Checked</th><th></th></tr></thead>
<tbody id="smHealthRows"></tbody></table></div></div></div></div>
  <div id="smart-usage" class="smart-panel"><div class="row"><div class="col-12"><div class="card smart-card"><div class="card-header bg-white font-weight-bold"><i class="fas fa-tachometer-alt text-teal mr-1"></i> Ringkasan Pemakaian Provider <small class="text-muted ml-2">Limit harian • reset otomatis 00:00</small></div><div class="card-body"><div class="sq-legend"><span><i style="background:#00ff9c"></i>Aman (&lt; 60%)</span><span><i style="background:#ffd84d"></i>Mulai ramai (60–84%)</span><span><i style="background:#ff7a45"></i>Hampir habis (85–99%)</span><span><i style="background:#ff4f9a"></i>Limit tercapai (100%)</span></div><div id="smUsageBars"></div><div class="small text-muted mt-1">Persen = jumlah request konversi hari ini ÷ limit harian provider. Atur limit di tab <b>Settings</b>.</div></div></div></div></div></div>
  <div id="smart-logs" class="smart-panel"><div class="card smart-card"><div class="card-header bg-white d-flex justify-content-between"><span class="font-weight-bold">Activity & Error Logs</span><button class="btn btn-outline-danger btn-sm" onclick="smartClearLogs()">Bersihkan Log</button></div><div class="card-body smart-log"><div class="table-responsive"><table class="table smart-table"><thead><tr><th>Waktu</th><th>Level</th><th>Provider</th><th>Model</th><th>Event</th><th>Message</th></tr></thead><tbody id="smLogRows"></tbody></table></div></div></div></div>
  <div id="smart-settings" class="smart-panel"><div class="card smart-card"><div class="card-header bg-white font-weight-bold">Smart Routing Settings</div><div class="card-body"><div class="row"><div class="col-md-3"><label>Cooldown (detik)</label><input id="smCooldown" type="number" class="form-control form-control-sm" value="60"></div><div class="col-md-3"><label>Max Retry / Batch</label><input id="smRetry" type="number" class="form-control form-control-sm" value="3"></div><div class="col-md-3"><label>Mode Routing</label><select id="smRouting" class="form-control form-control-sm"><option value="smart">Smart Health + Priority</option><option value="chain">Manual Provider Chain</option><option value="speed">Response Speed</option></select></div><div class="col-md-3 d-flex align-items-end"><button class="btn btn-teal btn-sm btn-block" onclick="smartSaveSettings()">Simpan Settings</button></div></div><hr><div class="font-weight-bold small mb-1"><i class="fas fa-tachometer-alt text-teal mr-1"></i>Limit Request Harian per Provider</div><div class="small text-muted mb-2">Dipakai untuk bar persen di tab Usage. Sesuaikan dengan kuota paket/free-tier masing-masing provider, lalu klik <b>Simpan Settings</b>.</div><div class="row" id="smLimitInputs"></div><hr><div class="small text-muted">Smart mode akan mengutamakan provider/key yang sehat, kemudian menggunakan chain lama sebagai fallback. Sistem lama tetap berjalan sebagai mesin kompatibilitas.</div></div></div></div>
</div>

    <section class="content">
        <div class="container-fluid">
            <div class="row" id="aiQuestionWorkspace">
                <div class="col-lg-6 col-md-12">
                    <div class="card card-teal card-outline shadow-sm">
                        <div class="card-header bg-light d-flex align-items-center py-2">
                            <h3 class="card-title font-weight-bold text-dark mb-0" style="font-size: 15px;">
                                <i class="fas fa-paste text-teal mr-1"></i> Input Bank Soal Mentah
                                <button type="button" class="ai-workspace-fullscreen-btn" title="Layar penuh Input + Preview" aria-label="Layar penuh Input + Preview" onclick="toggleQuestionWorkspaceFullscreen()">
                                    <i class="fas fa-expand"></i>
                                </button>
                            </h3>
                            <div class="card-tools ml-auto">
                                <button type="button" onclick="clearAll()" class="btn btn-xs btn-outline-danger">
                                    <i class="fas fa-trash mr-1"></i> Bersihkan
                                </button>
                            </div>
                        </div>

                        <div class="card-body bg-light border-bottom py-2 px-3">
                            <div class="row">
                                <div class="col-6">
                                    <label class="btn btn-primary btn-xs btn-block mb-0 shadow-sm cursor-pointer" for="fileImporter">
                                        <i class="fas fa-file-upload mr-1"></i> Import Word / PDF
                                        <input type="file" id="fileImporter" accept=".docx,.pdf" multiple style="display: none;" onchange="handleFileUpload(this)">
                                    </label>
                                </div>
                                <div class="col-6">
                                    <label class="btn btn-teal btn-xs btn-block mb-0 shadow-sm cursor-pointer" for="batchImageUploader">
                                        <i class="fas fa-images mr-1"></i> Upload Gambar (Batch)
                                        <input type="file" id="batchImageUploader" multiple accept="image/*" style="display: none;" onchange="uploadBatchImages(this)">
                                    </label>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12">
                                    <button type="button" id="btnEditOriginalWord"
                                            class="btn btn-sm btn-block word-original-btn shadow-sm"
                                            onclick="openOriginalWordEditor()" style="display:none;">
                                        <i class="fas fa-file-word mr-1"></i>
                                        Edit Word Asli (pertahankan rumus & gambar)
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div id="imagePasteHint" class="ai-image-paste-hint" role="button" tabindex="0"
                                 title="Klik untuk fokus ke area input, lalu tekan Ctrl+V"
                                 onclick="document.getElementById('inputText').focus()"
                                 onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();document.getElementById('inputText').focus();}">
                                <span class="ai-image-paste-icon"><i class="fas fa-paste"></i></span>
                                <span><strong>Tempel gambar atau screenshot dengan Ctrl+V</strong>
                                    <small>Klik area input di bawah, lalu tekan Ctrl+V. Bisa juga seret gambar ke area input atau gunakan Upload Gambar (Batch).</small></span>
                                <span class="ai-image-paste-tag">Ctrl + V</span>
                            </div>
                            <div id="inputText" contenteditable="true" class="form-control terminal-input"
                                 aria-label="Input bank soal. Tempel teks atau gambar dengan Ctrl+V."
                                 oninput="autoCleanAndParse()"></div>
                            <div class="mt-2">
                                <small class="text-muted d-block mb-0">
                                    <i class="fas fa-info-circle text-teal"></i> Gambar yang ditempel akan masuk ke dokumen soal dan ikut diproses pada preview/ekspor.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-12">
                    <div class="card card-teal card-outline shadow-sm">
                        <div class="card-header d-flex align-items-center bg-light py-2">
                            <h3 class="card-title font-weight-bold text-dark mb-0" style="font-size: 15px;">
                                <i class="fas fa-check-circle text-teal mr-1"></i> Preview Struktur & Kunci Jawaban
                            </h3>
                            <div class="card-tools ml-auto">
                                <span class="count-pill idle" id="countBadge"><span class="cp-ico"><i class="fas fa-search"></i></span><span class="cp-num">0</span><span class="cp-txt"><b>Soal Terdeteksi</b><small>MENUNGGU INPUT</small></span></span>
                            </div>
                        </div>
                        
                        <div class="card-body p-3" id="previewRenderArea" style="height: 415px; overflow-y: auto; background-color: #f8f9fa;">
                            <div class="text-center text-muted py-5">
                                <i class="fas fa-magic fa-3x mb-3 text-teal d-block"></i>
                                Tempelkan teks bank soal di sebelah kiri atau unggah dokumen untuk memulai.
                            </div>
                        </div>

                        <div class="card-footer bg-white py-2">
                            <div id="cacheStatus" class="small text-muted mb-2" style="display:none;"></div>
                            <button type="button" onclick="prosesDanRapikanDenganAI()" id="btnAiAnswer" class="btn btn-warning btn-block font-weight-bold py-2 mb-2 shadow-sm text-dark" style="display:none;">
                                <i class="fas fa-robot mr-2"></i> Jalankan AI Generator & Kunci Jawaban Otomatis ✨
                            </button>
                            <button type="button" onclick="convertToWordGaruda()" id="btnDownloadWord" class="btn btn-primary btn-block font-weight-bold py-2 shadow-sm">
                                <i class="fas fa-file-word mr-2"></i> Download Soal (.docx)
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


<!-- Modal Editor Word Asli: tambahan, tidak mengganti editor/parser lama -->
<div class="modal fade word-editor-modal" id="wordOriginalEditorModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header py-2">
                <div>
                    <h5 class="modal-title font-weight-bold mb-0"><i class="fas fa-file-word text-teal mr-2"></i>Editor Word Asli</h5>
                    <div class="word-editor-info mt-1">Edit teks langsung dari <span id="wordOriginalName">dokumen.docx</span>. Rumus Word, gambar, tabel, style, dan elemen non-teks tidak dibongkar.</div>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body p-2">
                <div class="word-editor-toolbar mb-2">
                    <input type="text" id="wordEditorSearch" class="form-control form-control-sm word-editor-search" placeholder="Cari teks soal..." oninput="filterOriginalWordEditor(this.value)">
                    <span id="wordEditorCount" class="word-editor-info"></span>
                </div>
                <div id="wordOriginalEditorList" class="word-editor-list"><div class="text-center text-muted py-5">Memuat dokumen Word...</div></div>
            </div>
            <div class="modal-footer py-2">
                <span class="word-editor-info mr-auto"><i class="fas fa-shield-alt text-teal mr-1"></i>File asli tidak ditimpa. Hasil disimpan sebagai file baru.</span>
                <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-teal btn-sm" onclick="saveOriginalWordEdits()"><i class="fas fa-save mr-1"></i>Simpan & Download Word</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Setting Multi-API Key & Provider Rotation -->
<div class="modal fade" id="apiKeyModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-teal text-white py-2">
                <h5 class="modal-title font-weight-bold" style="font-size: 16px;">
                    <i class="fas fa-sync-alt mr-2"></i>Pengaturan Multi-API Key & Rotasi Provider AI
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="max-height: 75vh; overflow-y: auto;">
                <p class="text-muted mb-3" style="font-size: 12px;">
                    <i class="fas fa-info-circle text-teal"></i> Anda dapat memasukkan beberapa API Key per provider dipisahkan dengan tanda koma (<code>,</code>).
                </p>

                <!-- Google Gemini -->
                <div class="form-group mb-3">
                    <label class="font-weight-bold text-dark mb-1" style="font-size: 13px;">Google Gemini API Key(s):</label>
                    <div class="input-group input-group-sm">
                        <input type="password" id="inputGeminiKey" class="form-control" placeholder="AIzaSy1..., AIzaSy2...">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" title="Salin API Key" onclick="copyToClipboard('inputGeminiKey', this)"><i class="fas fa-copy"></i></button>
                            <button class="btn btn-outline-secondary" type="button" title="Lihat/Sembunyikan" onclick="toggleVisibility('inputGeminiKey', this)"><i class="fas fa-eye"></i></button>
                        </div>
                    </div>
                </div>

                <!-- OpenAI -->
                <div class="form-group mb-3">
                    <label class="font-weight-bold text-dark mb-1" style="font-size: 13px;">OpenAI API Key(s):</label>
                    <div class="input-group input-group-sm">
                        <input type="password" id="inputOpenaiKey" class="form-control" placeholder="sk-proj-1..., sk-proj-2...">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" title="Salin API Key" onclick="copyToClipboard('inputOpenaiKey', this)"><i class="fas fa-copy"></i></button>
                            <button class="btn btn-outline-secondary" type="button" title="Lihat/Sembunyikan" onclick="toggleVisibility('inputOpenaiKey', this)"><i class="fas fa-eye"></i></button>
                        </div>
                    </div>
                </div>

                <!-- Groq -->
                <div class="form-group mb-3">
                    <label class="font-weight-bold text-dark mb-1" style="font-size: 13px;">Groq API Key(s):</label>
                    <div class="input-group input-group-sm">
                        <input type="password" id="inputGroqKey" class="form-control" placeholder="gsk_1..., gsk_2...">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" title="Salin API Key" onclick="copyToClipboard('inputGroqKey', this)"><i class="fas fa-copy"></i></button>
                            <button class="btn btn-outline-secondary" type="button" title="Lihat/Sembunyikan" onclick="toggleVisibility('inputGroqKey', this)"><i class="fas fa-eye"></i></button>
                        </div>
                    </div>
                </div>

                <!-- Pollinations.ai -->
                <div class="form-group mb-3">
                    <label class="font-weight-bold text-dark mb-1" style="font-size: 13px;">Pollinations.ai API Key(s):</label>
                    <div class="input-group input-group-sm">
                        <input type="password" id="inputPollinationsKey" class="form-control" placeholder="sk_..., sk_...">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" title="Salin API Key" onclick="copyToClipboard('inputPollinationsKey', this)"><i class="fas fa-copy"></i></button>
                            <button class="btn btn-outline-secondary" type="button" title="Lihat/Sembunyikan" onclick="toggleVisibility('inputPollinationsKey', this)"><i class="fas fa-eye"></i></button>
                        </div>
                    </div>
                </div>

                <!-- OpenRouter -->
                <div class="form-group mb-3">
                    <label class="font-weight-bold text-dark mb-1" style="font-size: 13px;">OpenRouter API Key(s):</label>
                    <div class="input-group input-group-sm">
                        <input type="password" id="inputOpenRouterKey" class="form-control" placeholder="sk-or-v1-1..., sk-or-v1-2...">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" title="Salin API Key" onclick="copyToClipboard('inputOpenRouterKey', this)"><i class="fas fa-copy"></i></button>
                            <button class="btn btn-outline-secondary" type="button" title="Lihat/Sembunyikan" onclick="toggleVisibility('inputOpenRouterKey', this)"><i class="fas fa-eye"></i></button>
                        </div>
                    </div>
                </div>

                


                <!-- Provider Chain -->
                <div class="form-group mb-2">
                    <label class="font-weight-bold text-dark mb-1" style="font-size: 13px;">Prioritas Urutan Provider AI (Fallback Hierarchy):</label>
                    <div class="custom-control custom-checkbox mb-1">
                        <input type="checkbox" class="custom-control-input provider-chain-item" id="prov1" value="gemini" checked>
                        <label class="custom-control-label font-weight-bold text-dark" for="prov1">1. Google Gemini <span class="badge badge-primary">Utama</span></label>
                    </div>
                    <div class="custom-control custom-checkbox mb-1">
                        <input type="checkbox" class="custom-control-input provider-chain-item" id="prov_openai" value="openai" checked>
                        <label class="custom-control-label font-weight-bold text-dark" for="prov_openai">2. OpenAI (GPT-4o/Mini) <span class="badge badge-success">Cadangan 1</span></label>
                    </div>
                    <div class="custom-control custom-checkbox mb-1">
                        <input type="checkbox" class="custom-control-input provider-chain-item" id="prov2" value="groq" checked>
                        <label class="custom-control-label font-weight-bold text-dark" for="prov2">3. Groq AI <span class="badge badge-info">Cadangan 2</span></label>
                    </div>
                    <div class="custom-control custom-checkbox mb-1">
                        <input type="checkbox" class="custom-control-input provider-chain-item" id="prov4" value="pollinations" checked>
                        <label class="custom-control-label font-weight-bold text-dark" for="prov4">4. Pollinations.ai <span class="badge badge-dark">Cadangan 3</span></label>
                    </div>
</div>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-teal btn-sm font-weight-bold px-3" onclick="saveApiKeyModal()">
                    <i class="fas fa-check mr-1"></i> Simpan Pengaturan
                </button>
            </div>
        </div>
    </div>
</div>

<style id="aiLoaderStyle">
/* ===== Loading konversi soal (Preview Struktur & Kunci Jawaban) ===== */
#previewRenderArea .ai-ld{max-width:640px;margin:0 auto;padding:22px 18px 18px;color:#eafff8;text-align:left}
.ai-ld-head{display:flex;align-items:center;gap:14px;margin-bottom:14px}
.ai-ld-orb{position:relative;flex:0 0 52px;width:52px;height:52px;border-radius:50%;display:flex;align-items:center;justify-content:center;
    font-size:20px;color:#00ff9c;background:radial-gradient(circle,rgba(0,255,156,.18),rgba(0,255,156,.04));border:1px solid rgba(0,255,156,.45);
    box-shadow:0 0 18px rgba(0,255,156,.35),inset 0 0 12px rgba(0,255,156,.15)}
.ai-ld-orb::after{content:"";position:absolute;inset:-6px;border-radius:50%;border:2px solid transparent;border-top-color:#00e5ff;border-right-color:rgba(0,229,255,.35);animation:aiLdSpin 1.1s linear infinite}
.ai-ld-title{flex:1;min-width:0}
.ai-ld-title b{display:block;font-size:16px;letter-spacing:.2px;color:#fff}
.ai-ld-title span{display:block;font-size:12.5px;color:#8ab7ae;margin-top:2px;overflow-wrap:anywhere}
.ai-ld-pct{font-size:30px;font-weight:800;line-height:1;color:#00ff9c;text-shadow:0 0 12px rgba(0,255,156,.45);font-variant-numeric:tabular-nums}
.ai-ld-pct small{font-size:15px;font-weight:700;margin-left:1px}
.ai-ld-bar{position:relative;height:12px;border-radius:20px;background:#09231f;border:1px solid rgba(0,255,156,.25);overflow:hidden}
.ai-ld-bar>span{position:absolute;left:0;top:0;bottom:0;width:0;border-radius:20px;background:linear-gradient(90deg,#00e5ff,#00ff9c);box-shadow:0 0 12px rgba(0,255,156,.55)}
.ai-ld-bar>span::after{content:"";position:absolute;inset:0;background:linear-gradient(100deg,transparent 30%,rgba(255,255,255,.45) 50%,transparent 70%);transform:translateX(-100%);animation:aiLdShine 1.6s ease-in-out infinite}
.ai-ld-bar.indet>span{width:38%!important;animation:aiLdSlide 1.3s ease-in-out infinite}
.ai-ld-meta{display:flex;flex-wrap:wrap;gap:6px 18px;margin:9px 2px 14px;font-size:12px;color:#8ab7ae}
.ai-ld-meta b{color:#eafff8;font-variant-numeric:tabular-nums}
.ai-ld-steps{display:flex;gap:6px;margin-bottom:14px}
.ai-ld-step{flex:1;display:flex;align-items:center;gap:8px;padding:8px 10px;border-radius:9px;font-size:12px;color:#6f968d;
    background:rgba(255,255,255,.03);border:1px solid rgba(0,255,156,.12);transition:.3s}
.ai-ld-step i{width:18px;text-align:center;font-size:13px}
.ai-ld-step.done{color:#00ff9c;border-color:rgba(0,255,156,.4);background:rgba(0,255,156,.07)}
.ai-ld-step.active{color:#fff;border-color:#00e5ff;background:rgba(0,229,255,.09);box-shadow:0 0 12px rgba(0,229,255,.2)}
.ai-ld-chips{display:flex;flex-wrap:wrap;gap:6px;margin-bottom:14px}
.ai-ld-chip{padding:3px 9px;border-radius:20px;font-size:11px;font-weight:600;color:#6f968d;border:1px solid rgba(0,255,156,.15);background:rgba(255,255,255,.02);font-variant-numeric:tabular-nums}
.ai-ld-chip.done{color:#041412;background:#00ff9c;border-color:#00ff9c}
.ai-ld-chip.active{color:#fff;border-color:#00e5ff;background:rgba(0,229,255,.15);animation:aiLdPulse 1.2s ease-in-out infinite}
.ai-ld-q{border:1px solid rgba(0,255,156,.2);border-radius:10px;background:rgba(7,27,24,.75);padding:10px 12px;margin-bottom:8px}
.ai-ld-q.wait{opacity:.55}
.ai-ld-qh{display:flex;align-items:center;gap:8px;margin-bottom:6px;font-size:12px}
.ai-ld-qh .no{font-weight:800;color:#00ff9c}
.ai-ld-qh .st{margin-left:auto;font-size:10.5px;padding:1px 8px;border-radius:20px;border:1px solid rgba(0,229,255,.4);color:#7befff}
.ai-ld-qt{font-size:13px;line-height:1.45;color:#eafff8;overflow-wrap:anywhere}
.ai-ld-sk{display:flex;align-items:center;gap:8px;margin-top:6px}
.ai-ld-sk i{flex:0 0 18px;font-style:normal;font-size:11px;font-weight:700;color:#4f7a70}
.ai-ld-sk em{display:block;height:9px;border-radius:6px;background:linear-gradient(100deg,rgba(255,255,255,.05) 30%,rgba(0,255,156,.22) 50%,rgba(255,255,255,.05) 70%);background-size:220% 100%;animation:aiLdSk 1.4s linear infinite}
.ai-ld-foot{margin-top:12px;text-align:center;font-size:11.5px;color:#6f968d}
@keyframes aiLdSpin{to{transform:rotate(360deg)}}
@keyframes aiLdShine{to{transform:translateX(100%)}}
@keyframes aiLdSlide{0%{left:-40%}100%{left:102%}}
@keyframes aiLdPulse{0%,100%{box-shadow:0 0 0 rgba(0,229,255,0)}50%{box-shadow:0 0 10px rgba(0,229,255,.55)}}
@keyframes aiLdSk{to{background-position:-220% 0}}
@media (max-width:520px){.ai-ld-steps{flex-direction:column}.ai-ld-pct{font-size:24px}}
@media (prefers-reduced-motion:reduce){.ai-ld *,.ai-ld *::after{animation:none!important}}
</style>

<style>
/* ===== Native Word Editor: tambahan saja, fitur lama tetap dipertahankan ===== */
.word-original-btn{border:1px solid rgba(0,255,156,.35)!important;color:#bfffe9!important;background:linear-gradient(135deg,rgba(0,255,156,.12),rgba(0,229,255,.08))!important}
.word-original-btn:hover{color:#00130e!important;background:linear-gradient(135deg,#00ffad,#00cfc0)!important}
.word-editor-modal .modal-content{background:linear-gradient(180deg,#061a17,#020d0c)!important;color:#eafff8!important;border:1px solid rgba(0,255,156,.35)!important;border-radius:16px;box-shadow:0 0 45px rgba(0,255,156,.16)}
.word-editor-modal .modal-header,.word-editor-modal .modal-footer{border-color:rgba(0,255,156,.20)!important}
.word-editor-list{max-height:62vh;overflow:auto;padding:10px;background:#020d0c;border:1px solid rgba(0,255,156,.18);border-radius:12px}
.word-editor-item{border:1px solid rgba(0,255,156,.14);border-radius:10px;padding:8px;margin-bottom:8px;background:rgba(6,27,23,.72)}
.word-editor-item:hover{border-color:rgba(0,255,156,.38)}
.word-editor-meta{display:flex;align-items:center;gap:7px;flex-wrap:wrap;margin-bottom:6px;font-size:10px;color:#77bcb0}
.word-editor-no{font-weight:900;color:#00ff9c}
.word-editor-tag{border:1px solid rgba(0,229,255,.28);color:#7befff;border-radius:20px;padding:2px 7px}
.word-editor-text{width:100%;min-height:42px;resize:vertical;background:#03110f!important;color:#eafff8!important;border:1px solid rgba(0,255,156,.22)!important;border-radius:8px;padding:8px 10px;font-size:12px;line-height:1.45}
.word-editor-text:focus{border-color:#00ff9c!important;box-shadow:0 0 0 .12rem rgba(0,255,156,.10)!important}
.word-editor-text[readonly]{opacity:.75;cursor:not-allowed}
.word-editor-toolbar{display:flex;align-items:center;justify-content:space-between;gap:8px;flex-wrap:wrap}
.word-editor-search{flex:1 1 260px;background:#03110f!important;color:#eafff8!important;border:1px solid rgba(0,255,156,.22)!important}
.word-editor-info{font-size:11px;color:#7faea4}
.word-editor-equation{margin-top:8px;padding:8px 10px;border:1px solid rgba(0,229,255,.18);border-radius:9px;background:rgba(0,229,255,.035)}
.word-editor-equation-head{display:flex;align-items:center;gap:7px;margin-bottom:5px;font-size:10px;color:#7befff}
.word-editor-equation-preview{padding:10px 14px;margin-bottom:6px;background:#020b0a;border-radius:7px;overflow:auto;color:#fff;font-size:30px;line-height:1.9;min-height:64px;text-align:center}.word-editor-equation-preview mjx-container{font-size:145% !important}.word-editor-equation-preview svg{height:1.9em !important;max-height:none !important}.word-editor-equation-preview mjx-mfrac{font-size:1.25em}.word-editor-equation-preview mjx-mfrac mjx-num,.word-editor-equation-preview mjx-mfrac mjx-den{font-size:1.08em}
.word-editor-equation-input{width:100%;min-height:38px;resize:vertical;background:#03110f!important;color:#eafff8!important;border:1px solid rgba(0,255,156,.22)!important;border-radius:7px;padding:7px 9px;font-family:Consolas,monospace;font-size:12px}
.word-editor-equation-input:focus{border-color:#00ff9c!important;box-shadow:0 0 0 .12rem rgba(0,255,156,.10)!important}.word-editor-equation-tools{display:flex;align-items:center;gap:6px;flex-wrap:wrap;margin:4px 0 6px}.word-editor-equation-tools button{font-size:11px;padding:2px 8px}.word-editor-equation-size{font-size:11px;color:#8ab8ae;min-width:55px;text-align:center}.word-editor-equation-preview .MathJax,.word-editor-equation-preview mjx-container{overflow:visible !important}


/* ===== Tampilan TAB BARU Input + Preview ===== */
html.ai-workspace-tab-page,
html.ai-workspace-tab-page body {
    width:100%!important;
    min-width:0!important;
    height:100%!important;
    margin:0!important;
    padding:0!important;
    overflow:hidden!important;
    background:#061613!important;
}
/* Jangan sembunyikan parent workspace. #aiQuestionWorkspace berada di dalam beberapa
   wrapper Bootstrap; jika parent disembunyikan, elemen fixed di dalamnya ikut hilang.
   JavaScript di bawah menyembunyikan sibling yang tidak diperlukan. */
#aiWorkspaceTabClose {
    position:fixed!important;
    top:10px!important;
    right:12px!important;
    z-index:100000!important;
    border:1px solid rgba(255,255,255,.25)!important;
    border-radius:8px!important;
    padding:7px 12px!important;
    background:#087f66!important;
    color:#fff!important;
    font-weight:800!important;
    cursor:pointer!important;
    box-shadow:0 4px 18px rgba(0,0,0,.35)!important;
}
#aiWorkspaceTabClose:hover { filter:brightness(1.12); }
body.ai-workspace-tab-page #aiQuestionWorkspace {
    position:fixed!important;
    inset:0!important;
    z-index:99999!important;
    display:grid!important;
    grid-template-columns:minmax(0,1fr) minmax(0,1fr)!important;
    gap:14px!important;
    width:100vw!important;
    height:100vh!important;
    max-width:none!important;
    margin:0!important;
    padding:14px!important;
    box-sizing:border-box!important;
    overflow:hidden!important;
    background:linear-gradient(135deg,#061b17,#020c0a)!important;
}
body.ai-workspace-tab-page #aiQuestionWorkspace > [class*="col-"] {
    width:auto!important;
    max-width:none!important;
    min-width:0!important;
    height:calc(100vh - 28px)!important;
    padding:0!important;
    margin:0!important;
    flex:none!important;
}
/* Hanya CARD UTAMA kiri/kanan yang memenuhi tinggi tab. Kartu soal di dalam Preview
   harus tetap setinggi isi agar tidak memanjang kosong dan tidak memotong konten. */
body.ai-workspace-tab-page #aiQuestionWorkspace > [class*="col-"] > .card {
    height:100%!important;
    min-height:0!important;
    margin:0!important;
    display:flex!important;
    flex-direction:column!important;
    overflow:hidden!important;
    border-radius:14px!important;
}
body.ai-workspace-tab-page #aiQuestionWorkspace > [class*="col-"] > .card > .card-header {
    flex:0 0 auto!important;
    min-height:52px!important;
    padding:10px 14px!important;
    position:relative!important;
}
body.ai-workspace-tab-page #aiQuestionWorkspace > [class*="col-"] > .card > .card-body {
    min-height:0!important;
}
/* Panel editor kiri: tombol tetap di atas, editor mengisi sisa tinggi yang tersedia. */
/* Toolbar import/upload HARUS selalu terlihat dan tidak boleh ikut menyusut
   ketika isi soal bertambah banyak. */
body.ai-workspace-tab-page #aiQuestionWorkspace > [class*="col-"]:first-child > .card > .card-body:first-of-type {
    flex:0 0 auto!important;
    flex-shrink:0!important;
    min-height:0!important;
    max-height:none!important;
    overflow:visible!important;
    position:relative!important;
    z-index:3!important;
    box-sizing:border-box!important;
}
body.ai-workspace-tab-page #aiQuestionWorkspace > [class*="col-"]:first-child > .card > .card-body:nth-of-type(2) {
    flex:1 1 0!important;
    flex-shrink:1!important;
    min-height:0!important;
    max-height:none!important;
    overflow:hidden!important;
    display:flex!important;
    flex-direction:column!important;
    padding:10px!important;
    box-sizing:border-box!important;
}
/* Input mengikuti ruang yang tersisa; jangan mengambil ruang toolbar. */
body.ai-workspace-tab-page #aiQuestionWorkspace #inputText {
    width:100%!important;
    height:auto!important;
    flex:1 1 auto!important;
    min-height:0!important;
    max-height:none!important;
    box-sizing:border-box!important;
    overflow:auto!important;
    padding:14px!important;
    line-height:1.55!important;
    font-size:14px!important;
}
body.ai-workspace-tab-page #aiQuestionWorkspace > [class*="col-"]:first-child > .card > .card-body:nth-of-type(2) > .mt-2 {
    flex:0 0 auto!important;
}
/* Panel Preview: area scroll sendiri, footer tetap terlihat. */
body.ai-workspace-tab-page #aiQuestionWorkspace #previewRenderArea {
    width:100%!important;
    height:auto!important;
    flex:1 1 auto!important;
    min-height:0!important;
    max-height:none!important;
    box-sizing:border-box!important;
    overflow:auto!important;
    padding:10px!important;
}
/* Reset kartu soal yang berada di dalam preview agar tinggi mengikuti isi. */
body.ai-workspace-tab-page #aiQuestionWorkspace #previewRenderArea > .preview-question-card,
body.ai-workspace-tab-page #aiQuestionWorkspace #previewRenderArea > .question-card {
    height:auto!important;
    min-height:0!important;
    margin-bottom:10px!important;
    display:block!important;
    overflow:visible!important;
}
body.ai-workspace-tab-page #aiQuestionWorkspace #previewRenderArea > .preview-question-card > .card-header,
body.ai-workspace-tab-page #aiQuestionWorkspace #previewRenderArea > .question-card > .card-header {
    display:flex!important;
    min-height:0!important;
    padding:8px 12px!important;
}
body.ai-workspace-tab-page #aiQuestionWorkspace #previewRenderArea > .preview-question-card > .card-body,
body.ai-workspace-tab-page #aiQuestionWorkspace #previewRenderArea > .question-card > .card-body {
    display:block!important;
    flex:none!important;
    min-height:0!important;
    height:auto!important;
    overflow:visible!important;
    padding:12px!important;
}
body.ai-workspace-tab-page #aiQuestionWorkspace .ai-workspace-fullscreen-btn {
    display:none!important;
}
body.ai-workspace-tab-page .eq-inline-editor {
    z-index:100001!important;
}
body.ai-workspace-tab-page #aiQuestionWorkspace .card-footer {
    flex:0 0 auto!important;
    flex-shrink:0!important;
    min-height:0!important;
    position:relative!important;
    z-index:3!important;
}
body.ai-workspace-tab-page #aiQuestionWorkspace > [class*="col-"] > .card > .card-header {
    flex-shrink:0!important;
}
@media (max-width:900px) {
    body.ai-workspace-tab-page {
        overflow:auto!important;
    }
    body.ai-workspace-tab-page #aiQuestionWorkspace {
        position:relative!important;
        display:grid!important;
        grid-template-columns:1fr!important;
        height:auto!important;
        min-height:100vh!important;
        overflow:visible!important;
    }
    body.ai-workspace-tab-page #aiQuestionWorkspace > [class*="col-"] {
        height:calc(100vh - 28px)!important;
        min-height:520px!important;
    }
}

/* ===== Combined fullscreen: Input Bank Soal Mentah + Preview ===== */
#aiQuestionWorkspace.ai-workspace-fullscreen {
    position: fixed !important;
    inset: 10px !important;
    z-index: 99999 !important;
    display: flex !important;
    flex-wrap: nowrap !important;
    width: auto !important;
    height: calc(100vh - 20px) !important;
    margin: 0 !important;
    padding: 10px !important;
    overflow: hidden !important;
    background: var(--ai-panel-bg, #071512) !important;
    border: 1px solid rgba(0,255,190,.35) !important;
    border-radius: 16px !important;
    box-shadow: 0 0 0 9999px rgba(0,0,0,.78), 0 20px 70px rgba(0,0,0,.5) !important;
}
#aiQuestionWorkspace.ai-workspace-fullscreen > [class*="col-"] {
    flex: 1 1 50% !important;
    width: 50% !important;
    max-width: 50% !important;
    height: 100% !important;
    padding-left: 6px !important;
    padding-right: 6px !important;
}
#aiQuestionWorkspace.ai-workspace-fullscreen .card {
    height: 100% !important;
    margin-bottom: 0 !important;
}
#aiQuestionWorkspace.ai-workspace-fullscreen .card-body {
    min-height: 0 !important;
}
#aiQuestionWorkspace.ai-workspace-fullscreen #inputText {
    height: calc(100vh - 190px) !important;
    min-height: 0 !important;
    max-height: none !important;
    overflow-y: auto !important;
}
#aiQuestionWorkspace.ai-workspace-fullscreen #previewRenderArea {
    height: calc(100vh - 180px) !important;
    max-height: none !important;
}
body.ai-question-workspace-fullscreen-open {
    overflow: hidden !important;
}
.ai-workspace-fullscreen-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 30px;
    margin-left: 8px;
    padding: 0;
    border: 1px solid rgba(0, 210, 180, .45);
    border-radius: 7px;
    background: rgba(0, 100, 85, .25);
    color: inherit;
    cursor: pointer;
    font-size: 13px;
    vertical-align: middle;
    transition: .15s ease;
}
.ai-workspace-fullscreen-btn:hover {
    transform: scale(1.06);
    filter: brightness(1.15);
}
#aiQuestionWorkspace.ai-workspace-fullscreen .ai-workspace-fullscreen-btn {
    width: 36px;
    height: 32px;
}
@media (max-width: 900px) {
    #aiQuestionWorkspace.ai-workspace-fullscreen {
        display: block !important;
        overflow-y: auto !important;
    }
    #aiQuestionWorkspace.ai-workspace-fullscreen > [class*="col-"] {
        width: 100% !important;
        max-width: 100% !important;
        height: 50% !important;
        padding-bottom: 6px !important;
    }
}

</style>
<!-- Online libraries are kept for full-fidelity import/export.
     The app also contains an offline Word/Equation fallback below, so Word import
     does not stop when CDN libraries are unavailable. -->
<script src="<?= base_url() ?>/assets/plugins/converter/jszip.min.js"></script>
<script src="<?= base_url() ?>/assets/plugins/converter/mammoth.browser.min.js"></script>
<script src="<?= base_url() ?>/assets/plugins/converter/pdf.min.js"></script>
<script src="<?= base_url() ?>/assets/plugins/converter/docx.js"></script>
<script src="<?= base_url() ?>/assets/plugins/converter/FileSaver.min.js"></script>
<script>window.MathJax={tex:{inlineMath:[['\\(','\\)']],displayMath:[['\\[','\\]']]},svg:{fontCache:'global'}};</script>
<script async src="<?= base_url() ?>/assets/plugins/converter/mathjax/tex-svg.js"></script>

<script>
const SERVER_KEYS = <?= json_encode($savedKeys ?? ['gemini'=>'','openai'=>'','groq'=>'','pollinations'=>'','openrouter'=>'','chain'=>'["gemini","openai","groq","pollinations","openrouter"]']) ?>;

try { localStorage.removeItem('CUSTOM_MISTRAL_KEY'); } catch(e) {}
/* ================= SMART AI MANAGER ENGINE ================= */
let SMART_STATE={providers:[],keys:[],health:[],models:[],usage:{},logs:[],settings:[]};
let SMART_PROVIDER_CHAIN=null, SMART_KEY_ORDER={};
const SMART_DEFAULTS={cooldown:60,retry:3,routing:'smart',limit:100};
const SMART_MODEL_BUSY=new Set();
function smartCsrf(fd){const n='<?= $this->security->get_csrf_token_name(); ?>',h='<?= $this->security->get_csrf_hash(); ?>';if(n)fd.append(n,h);return fd;}
async function smartPost(data){
  const fd=new FormData();
  Object.keys(data||{}).forEach(k=>fd.append(k,typeof data[k]==='object'?JSON.stringify(data[k]):data[k]));
  smartCsrf(fd);
  const r=await fetch(window.location.href,{method:'POST',body:fd,credentials:'same-origin',headers:{'X-Requested-With':'XMLHttpRequest'}});
  const raw=await r.text();
  let json=null;
  try{json=JSON.parse(raw);}catch(_){
    const snippet=String(raw||'').replace(/<[^>]*>/g,' ').replace(/\s+/g,' ').trim().slice(0,240);
    throw new Error(`Backend tidak mengembalikan JSON (HTTP ${r.status}). ${snippet||'Response kosong.'}`);
  }
  if(!r.ok) throw new Error(json?.message||json?.error||`HTTP ${r.status}`);
  return json;
}
function smartMask(v){v=String(v||'');return v.length<8?'••••••••':v.slice(0,4)+'••••••••'+v.slice(-4)}
function smartEscape(v){return String(v??'').replace(/[&<>"']/g,m=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m]));}
function smartStatusLabel(s){
  const map={healthy:'HEALTHY',cooldown:'COOLDOWN',rate_limited:'RATE LIMITED',error:'ERROR',disabled:'DISABLED',invalid_key:'INVALID KEY',model_error:'MODEL ERROR',unknown:'NOT CHECKED'};
  return map[s]||String(s||'UNKNOWN').replaceAll('_',' ').toUpperCase();
}
function smartStatusBadge(s){s=s||'unknown';return `<span class="smart-status ${smartEscape(s)}"><i class="fas ${s==='healthy'?'fa-check-circle':s==='cooldown'||s==='rate_limited'?'fa-clock':s==='disabled'||s==='invalid_key'?'fa-ban':s==='model_error'?'fa-cubes':'fa-exclamation-circle'}"></i>${smartStatusLabel(s)}</span>`}
function smartHttpBadge(http){
  const n=Number(http||0);
  if(!n)return '<span class="text-muted">—</span>';
  return `<span class="smart-http ${n>=200&&n<300?'ok':'bad'}">${n}</span>`;
}
function smartErrorText(h){
  if(h?.message)return smartEscape(h.message).slice(0,180);
  if(h?.http_status)return `HTTP ${h.http_status}`;
  return '—';
}
function smartSetBusy(on){
  const box=document.querySelector('.smart-manager');
  if(box)box.classList.toggle('smart-refreshing',!!on);
}
async function smartRefresh(){
  try{
    const r=await smartPost({action:'manager_snapshot'});
    if(r.status!=='success')throw Error(r.message||'Snapshot gagal');
    SMART_STATE=r;
    smartBuildRouting();
    smartRender();
  }catch(e){
    console.warn('Smart Manager snapshot:',e);
    // Fallback frontend: UI tetap hidup walaupun endpoint manager belum tersedia.
    smartBuildLocalFallback();
    smartBuildRouting();
    smartRender();
    const el=document.getElementById('smHealthSummary');
    if(el && !(SMART_STATE.health||[]).length){
      el.innerHTML='<span class="text-warning"><i class="fas fa-exclamation-triangle mr-1"></i>Backend Smart Manager belum mengembalikan snapshot. Health Check langsung ke provider tetap dapat digunakan.</span>';
    }
  }
}
function smartBuildLocalFallback(){
  const defs=[
    ['gemini','Google Gemini',1,['gemini-3.5-flash','gemini-3.1-flash-lite']],
    ['openai','OpenAI',2,['gpt-4.1-mini','gpt-4.1']],
    ['groq','Groq AI',4,['openai/gpt-oss-120b','openai/gpt-oss-20b']],
    ['pollinations','Pollinations.ai',6,['openai','openai-fast','deepseek']],
    ['openrouter','OpenRouter',7,['deepseek/deepseek-chat','meta-llama/llama-3.3-70b-instruct']],

  ];
  const providers=[],keys=[],models=[];
  defs.forEach(d=>{
    const val=SERVER_KEYS[d[0]] || localStorage.getItem('CUSTOM_'+d[0].toUpperCase()+'_KEY') || '';
    providers.push({provider_key:d[0],provider_name:d[1],priority:d[2],enabled:1});
    d[3].forEach((m,i)=>models.push({provider_key:d[0],model_name:m,priority:i+1,enabled:1,success_count:0,fail_count:0}));
    val.split(',').map(x=>x.trim()).filter(Boolean).forEach((v,i)=>keys.push({
      provider_key:d[0],key_index:i+1,key_value:v,enabled:1,status:'unknown',
      total_requests:0,success_count:0,fail_count:0
    }));
  });
  SMART_STATE.providers=providers;
  SMART_STATE.keys=keys;
  SMART_STATE.models=models;
  SMART_STATE.health=SMART_STATE.health||[];
  SMART_STATE.usage=SMART_STATE.usage||{total:0,success:0,avg_ms:0};
  SMART_STATE.logs=SMART_STATE.logs||[];
  SMART_STATE.settings=SMART_STATE.settings||[];
}

function smartBuildRouting(){
  const now=Date.now(), healthy={}; SMART_KEY_ORDER={};
  (SMART_STATE.keys||[]).forEach(k=>{
    const cool=k.cooldown_until?new Date(String(k.cooldown_until).replace(' ','T')).getTime():0;
    if(k.enabled && k.status!=='disabled' && (!cool||cool<now)){
      healthy[k.provider_key]??=[];healthy[k.provider_key].push(k.key_index);
    }
  });
  const score={};(SMART_STATE.providers||[]).forEach(p=>score[p.provider_key]=Number(p.priority||100));
  Object.keys(healthy).forEach(p=>{
    healthy[p].sort((a,b)=>{
      const ka=(SMART_STATE.keys||[]).find(x=>x.provider_key===p&&Number(x.key_index)===Number(a));
      const kb=(SMART_STATE.keys||[]).find(x=>x.provider_key===p&&Number(x.key_index)===Number(b));
      return Number(kb?.success_count||0)-Number(ka?.success_count||0);
    });
    SMART_KEY_ORDER[p]=healthy[p];
  });
  const providers=(SMART_STATE.providers||[]).filter(p=>p.enabled!=0).sort((a,b)=>score[a.provider_key]-score[b.provider_key]);
  SMART_PROVIDER_CHAIN=providers.map(p=>p.provider_key); if(!SMART_PROVIDER_CHAIN.length)SMART_PROVIDER_CHAIN=null;
}
function smartRender(){
  const ps=SMART_STATE.providers||[],ks=SMART_STATE.keys||[],ms=SMART_STATE.models||[],hs=SMART_STATE.health||[],u=SMART_STATE.usage||{};
  const el=id=>document.getElementById(id);
  el('smProviders').textContent=ps.length;
  el('smKeys').textContent=ks.length;
  el('smRequests').textContent=Number(u.total||0).toLocaleString();
  const rate=Number(u.total||0)?Math.round(Number(u.success||0)/Number(u.total||1)*1000)/10:0;
  el('smSuccess').textContent=rate+'%';

  const grouped={};ks.forEach(k=>(grouped[k.provider_key]??=[]).push(k));
  el('smProviderRows').innerHTML=ps.map(p=>{
    const a=grouped[p.provider_key]||[];
    const healthy=a.filter(k=>k.status==='healthy'||k.status==='unknown').length;
    const cool=a.filter(k=>k.status==='cooldown').length;
    const errors=a.filter(k=>['error','disabled'].includes(k.status)).length;
    const checked=hs.filter(h=>h.provider_key===p.provider_key);
    const last=checked.map(h=>h.checked_at).filter(Boolean).sort().pop();
    return `<tr>
      <td class="font-weight-bold">${smartEscape(p.provider_name)}</td><td>${a.length}</td>
      <td class="text-success font-weight-bold">${healthy}</td><td class="text-warning font-weight-bold">${cool}</td>
      <td>${p.priority}</td><td>${errors?'<span class="smart-status error">ATTENTION</span>':'<span class="smart-status healthy">READY</span>'}</td>
      <td class="small text-muted">${last||'—'}</td></tr>`;
  }).join('')||'<tr><td colspan="7" class="text-center text-muted p-4">Belum ada provider.</td></tr>';

  el('smProvidersRows').innerHTML=ps.map(p=>`<tr>
    <td>${smartEscape(p.provider_name)}</td><td>${p.enabled?'🟢':'🔴'}</td><td>${p.priority}</td>
    <td>${ms.filter(m=>m.provider_key===p.provider_key&&Number(m.enabled)!==0).length}</td>
    <td><button class="btn btn-xs btn-outline-secondary" onclick="smartHealthProvider('${smartEscape(p.provider_key)}')"><i class="fas fa-heartbeat mr-1"></i>Test</button></td>
  </tr>`).join('');

  el('smKeyRows').innerHTML=ks.map(k=>`<tr>
    <td>${smartEscape(k.provider_key)}</td><td class="smart-mono">${smartMask(k.key_value)}</td>
    <td>${smartStatusBadge(k.status)}</td><td>${k.total_requests||0}</td><td>${k.success_count||0}</td><td>${k.last_used_at||'-'}</td>
  </tr>`).join('')||'<tr><td colspan="6" class="text-center text-muted p-4">Belum ada API key.</td></tr>';

  const byProv={}; ms.forEach(m=>(byProv[m.provider_key]??=[]).push(m));
  const modelArgs=m=>`'${smartEscape(m.provider_key)}','${smartEscape(m.model_name)}'`;
  el('smModelRows').innerHTML=ms.map(m=>{
    const grp=byProv[m.provider_key], idx=grp.indexOf(m), on=Number(m.enabled)!==0;
    const busy=SMART_MODEL_BUSY.has(m.provider_key+'|'+m.model_name);
    const test=busy?'<span class="text-info"><i class="fas fa-spinner fa-spin mr-1"></i>Menguji…</span>'
      :!m.last_status?'<span class="text-muted">Belum dites</span>'
      :`<span title="${smartEscape(m.last_message||'')}">${smartStatusBadge(m.last_status)} ${smartHttpBadge(m.last_http)} <span class="small text-muted">${m.last_ms?Number(m.last_ms)+' ms':''}</span></span>`;
    return `<tr class="${on?'':'text-muted'}">
      <td>${smartEscape(m.provider_key)}</td><td class="smart-mono">${smartEscape(m.model_name)}</td>
      <td style="white-space:nowrap"><button class="btn btn-xs btn-outline-secondary" ${idx===0?'disabled':''} onclick="smartModelMove(${modelArgs(m)},'up')" title="Naikkan prioritas">▲</button> <button class="btn btn-xs btn-outline-secondary" ${idx===grp.length-1?'disabled':''} onclick="smartModelMove(${modelArgs(m)},'down')" title="Turunkan prioritas">▼</button> <span class="ml-1">${idx+1}</span></td>
      <td><button class="btn btn-xs ${on?'btn-success':'btn-outline-secondary'}" onclick="smartModelToggle(${modelArgs(m)})" title="Klik untuk ${on?'mematikan':'mengaktifkan'}">${on?'🟢 Aktif':'🔴 Mati'}</button></td>
      <td>${test}</td><td>${m.success_count||0}</td><td>${m.fail_count||0}</td>
      <td><button class="btn btn-xs btn-outline-primary" ${busy?'disabled':''} onclick="smartTestModel(${modelArgs(m)})"><i class="fas fa-vial mr-1"></i>Test</button></td>
    </tr>`;
  }).join('');
  {
    const on=ms.filter(m=>Number(m.enabled)!==0).length, tested=ms.filter(m=>m.last_status);
    const ok=tested.filter(m=>m.last_status==='healthy').length;
    el('smModelSummary').innerHTML=`<b>${on}</b> aktif • <b>${ms.length-on}</b> mati • <b class="text-success">${ok}</b> lolos tes • <b class="text-danger">${tested.length-ok}</b> gagal tes • <b>${ms.length-tested.length}</b> belum dites`;
  }

  const counts={healthy:0,cooldown:0,error:0,disabled:0,httpError:0};
  hs.forEach(h=>{
    if(h.status==='healthy')counts.healthy++;
    else if(h.status==='cooldown'||h.status==='rate_limited')counts.cooldown++;
    else if(h.status==='disabled'||h.status==='invalid_key')counts.disabled++;
    else if(h.status==='error'||h.status==='model_error')counts.error++;
    if(Number(h.http_status)>=400)counts.httpError++;
  });
  el('smHealthHealthy').textContent=counts.healthy;
  el('smHealthCooldown').textContent=counts.cooldown;
  el('smHealthError').textContent=counts.error;
  el('smHealthDisabled').textContent=counts.disabled;
  el('smHealthHttpError').textContent=counts.httpError;
  el('smHealthSummary').innerHTML=`<b>${counts.healthy}</b> sehat • <b>${counts.cooldown}</b> cooldown • <b>${counts.error}</b> error • <b>${counts.disabled}</b> disabled • <b>${counts.httpError}</b> HTTP error`;

  el('smHealthRows').innerHTML=hs.map(h=>`<tr>
    <td class="font-weight-bold">${smartEscape(h.provider_key)}</td><td>#${h.key_index}</td>
    <td class="smart-mono">${smartEscape(h.model_name||'—')}</td><td>${smartStatusBadge(h.status)}</td>
    <td>${smartHttpBadge(h.http_status)}</td><td>${h.response_ms?Number(h.response_ms)+' ms':'—'}</td>
    <td title="${smartEscape(h.message||'')}">${smartErrorText(h)}</td><td>${h.checked_at||'—'}</td>
    <td><button class="btn btn-xs btn-outline-primary" onclick="smartHealthKey('${smartEscape(h.provider_key)}',${h.key_index})"><i class="fas fa-redo mr-1"></i>Test</button></td>
  </tr>`).join('')||'<tr><td colspan="9" class="text-center text-muted p-4">Belum ada API key untuk Health Check.</td></tr>';

  {
    const st={};(SMART_STATE.settings||[]).forEach(x=>st[x.setting_key]=x.setting_value);
    const ubp=SMART_STATE.usage_by_provider||{};
    const limitOf=k=>Math.max(1,parseInt(st['limit_'+k],10)||SMART_DEFAULTS.limit);
    const LV={low:'Aman',mid:'Mulai ramai',high:'Hampir habis',full:'Limit tercapai'};
    el('smUsageBars').innerHTML=ps.map(p=>{
      const k=p.provider_key,u2=ubp[k]||{},used=Number(u2.total||0),ok=Number(u2.success||0);
      const limit=limitOf(k),raw=used/limit*100,pct=Math.min(100,Math.round(raw*10)/10);
      const lvl=raw>=100?'full':raw>=85?'high':raw>=60?'mid':'low';
      const a=ks.filter(x=>x.provider_key===k),cool=a.filter(x=>x.status==='cooldown').length;
      const sr=used?Math.round(ok/used*100)+'%':'—';
      const ms=Number(u2.avg_ms||0)?Math.round(Number(u2.avg_ms))+' ms':'—';
      return `<div class="sq-item sq-${lvl}">
        <div class="sq-head">
          <div class="sq-name">${smartEscape(p.provider_name)}<span class="sq-chip">${LV[lvl]}</span></div>
          <div class="sq-pct">${pct}<small>%</small></div>
        </div>
        <div class="sq-track" title="${used} dari ${limit} request hari ini">
          <span class="sq-fill" style="width:${pct}%"></span>
          <i class="sq-mark" style="left:60%"></i><i class="sq-mark" style="left:85%"></i>
        </div>
        <div class="sq-scale"><span>0</span><span>${limit.toLocaleString()} request</span></div>
        <div class="sq-meta">
          <span>Terpakai <b>${used.toLocaleString()}</b> / ${limit.toLocaleString()}</span>
          <span>Sisa <b>${Math.max(0,limit-used).toLocaleString()}</b></span>
          <span>Sukses <b>${sr}</b></span>
          <span>Rata-rata <b>${ms}</b></span>
          <span>${a.length} key${cool?` • <b style="color:#ffe27b">${cool} cooldown</b>`:''}</span>
        </div>
      </div>`;
    }).join('')||'<div class="text-center text-muted p-3">Belum ada provider.</div>';

    // Input limit di tab Settings (dibangun ulang hanya jika data tersimpan berubah, supaya ketikan tidak hilang)
    const box=el('smLimitInputs');
    if(box){
      const sig=JSON.stringify(ps.map(p=>[p.provider_key,limitOf(p.provider_key)]));
      if(box.dataset.sig!==sig){
        box.dataset.sig=sig;
        box.innerHTML=ps.map(p=>`<div class="col-md-3 col-6 mb-2"><label class="small mb-1">${smartEscape(p.provider_name)}</label><input type="number" min="1" class="form-control form-control-sm sm-limit-input" data-p="${smartEscape(p.provider_key)}" value="${limitOf(p.provider_key)}"></div>`).join('');
      }
    }
  }

  el('smLogRows').innerHTML=(SMART_STATE.logs||[]).map(l=>`<tr>
    <td>${l.created_at||'-'}</td><td>${l.level==='error'?'<span class="text-danger font-weight-bold">ERROR</span>':'<span class="text-success">INFO</span>'}</td>
    <td>${smartEscape(l.provider_key||'-')} #${l.key_index||''}</td><td>${smartEscape(l.model_name||'-')}</td>
    <td>${smartEscape(l.event||'-')}</td><td>${smartEscape(l.message||'')}</td>
  </tr>`).join('')||'<tr><td colspan="6" class="text-center text-muted p-4">Belum ada log.</td></tr>';

  const settings={};(SMART_STATE.settings||[]).forEach(x=>settings[x.setting_key]=x.setting_value);
  el('smCooldown').value=settings.cooldown||SMART_DEFAULTS.cooldown;
  el('smRetry').value=settings.retry||SMART_DEFAULTS.retry;
  el('smRouting').value=settings.routing||SMART_DEFAULTS.routing;
}
function smartTab(tab){
  const target=document.getElementById('smart-'+tab);
  const alreadyOpen=!!target && target.classList.contains('active');

  // Klik tab yang sama = tutup panel. Klik tab lain = buka panel tersebut.
  document.querySelectorAll('#smartNav button').forEach(b=>{
    b.classList.toggle('active', !alreadyOpen && b.dataset.tab===tab);
  });
  document.querySelectorAll('.smart-panel').forEach(p=>{
    p.classList.toggle('active', !alreadyOpen && p.id==='smart-'+tab);
  });
}
function smartNavSync(){
  const act=document.querySelector('#smartNav button.active');
  const lab=document.getElementById('smartNavLabel'),ic=document.getElementById('smartNavIcon');
  if(!lab||!ic)return;
  if(act){lab.textContent=act.textContent.trim();ic.className=act.querySelector('i').className;}
  else{lab.textContent='Menu';ic.className='fas fa-bars';}
}
function smartNavToggle(open){
  const w=document.getElementById('smartNavWrap'),t=document.getElementById('smartNavToggle');
  if(!w)return;
  const o=(typeof open==='boolean')?open:!w.classList.contains('open');
  w.classList.toggle('open',o);
  if(t)t.setAttribute('aria-expanded',o?'true':'false');
}
document.addEventListener('click',e=>{
  const b=e.target.closest('#smartNav button');
  if(b){
    // efek ripple saat disentuh
    const r=b.getBoundingClientRect(),d=Math.max(r.width,r.height)*2,rip=document.createElement('span');
    rip.className='rip';rip.style.width=rip.style.height=d+'px';
    rip.style.left=(e.clientX-r.left-d/2)+'px';rip.style.top=(e.clientY-r.top-d/2)+'px';
    b.appendChild(rip);setTimeout(()=>rip.remove(),600);
    smartTab(b.dataset.tab);
    smartNavSync();
    smartNavToggle(false);
    return;
  }
  if(e.target.closest('#smartNavToggle')){smartNavToggle();return;}
  if(!e.target.closest('#smartNavWrap'))smartNavToggle(false);
});
document.addEventListener('keydown',e=>{if(e.key==='Escape')smartNavToggle(false);});

function smartParseHttpError(error){
  const raw=String(error?.message||error||'');
  // Status HTTP sebenarnya dibawa oleh error.httpStatus (diisi smartApiError / proxy server).
  let http=Number(error?.httpStatus||0);
  let message=raw;
  try{
    const body=error?.body;
    if(body){const j=typeof body==='string'?JSON.parse(body):body;message=j?.error?.message||j?.message||raw;}
  }catch(_){}
  if(!http){const m=raw.match(/(?:HTTP|Status)[\s:=#-]*(\d{3})/i);if(m)http=Number(m[1]);}
  if(!http && /failed to fetch|networkerror|load failed|network request failed/i.test(raw)) message='Gagal terhubung ke provider (jaringan/CORS/diblokir). '+raw;
  if(http===402) message='Saldo/kredit akun provider habis (HTTP 402). '+message;
  return {http,message};
}
function smartClassify(http){
  return http===401||http===403?'invalid_key':http===429?'rate_limited':http===404?'model_error':'error';
}

// ---- Pemanggilan provider: langsung dari browser, otomatis lewat server jika CORS/jaringan gagal ----
const SMART_DIRECT={
  gemini:(k,m,p)=>callGeminiApi(k,m,p), openai:(k,m,p)=>callOpenAiApi(k,m,p), 
  groq:(k,m,p)=>callGroqApi(k,m,p), pollinations:(k,m,p)=>callPollinationsApi(k,m,p),
  openrouter:(k,m,p)=>callOpenRouterApi(k,m,p)
};
async function smartCallViaProxy(provider,apiKey,model,prompt){
  const j=await smartPost({
    action:'ai_proxy',provider_key:provider,api_key:apiKey,model_name:model,prompt:prompt,
  });
  if(j.status!=='success'){
    const e=new Error(j.message||'Proxy server gagal.');
    e.httpStatus=Number(j.http||0);
    throw e;
  }
  return j.content;
}
function smartTrackUsage(provider,apiKey,model,ok,http,ms,msg){
  const k=(SMART_STATE.keys||[]).find(x=>x.provider_key===provider&&String(x.key_value||'').trim()===String(apiKey).trim());
  smartLog(provider,k?Number(k.key_index):1,model,ok,http,Math.round(ms),String(msg||'').slice(0,300),ok?'convert_ok':'convert_fail');
}
// track=true hanya dari proses konversi (bukan Health Check / tes model) agar hitungan limit akurat.
async function smartCall(provider,apiKey,model,prompt,track){
  const t0=performance.now();
  try{
    const out=await smartCallInner(provider,apiKey,model,prompt);
    if(track) smartTrackUsage(provider,apiKey,model,true,200,performance.now()-t0,'OK');
    return out;
  }catch(e){
    if(track){const x=smartParseHttpError(e);smartTrackUsage(provider,apiKey,model,false,x.http,performance.now()-t0,x.message);}
    throw e;
  }
}
async function smartCallInner(provider,apiKey,model,prompt){
  const direct=SMART_DIRECT[provider];
  if(!direct) throw new Error('Provider tidak dikenal: '+provider);
  try{
    return await direct(apiKey,model,prompt);
  }catch(e){
    if(e && e.httpStatus) throw e;   // provider menjawab dengan error → itu error sebenarnya
    console.warn(provider+': request langsung gagal, mencoba lewat server…',e);
    return await smartCallViaProxy(provider,apiKey,model,prompt);
  }
}
// Daftar model yang AKTIF (urut prioritas) dari Model Manager; fallback ke daftar bawaan bila belum ada data.
function smartModels(provider,fallback){
  const all=(SMART_STATE.models||[]).filter(m=>m.provider_key===provider);
  if(!all.length) return fallback;
  return all.filter(m=>Number(m.enabled)!==0).sort((a,b)=>Number(a.priority||100)-Number(b.priority||100)).map(m=>m.model_name);
}

// ---- Model Manager: aksi ----
const SMART_HEALTH_PROMPT='Return a JSON object with exactly one field: {"ok":true}. This is a health check.';
function smartFindModel(provider,model){return (SMART_STATE.models||[]).find(x=>x.provider_key===provider&&x.model_name===model);}
async function smartModelToggle(provider,model){
  const m=smartFindModel(provider,model); if(!m)return;
  const to=Number(m.enabled)!==0?0:1;
  try{
    await smartPost({action:'manager_model_toggle',provider_key:provider,model_name:model,enabled:to});
    m.enabled=to; smartRender();
  }catch(e){alert('Gagal menyimpan perubahan model: '+e.message);}
}
async function smartModelMove(provider,model,direction){
  try{
    await smartPost({action:'manager_model_move',provider_key:provider,model_name:model,direction:direction});
    await smartRefresh();
  }catch(e){alert('Gagal mengubah urutan model: '+e.message);}
}
async function smartModelBulk(mode){
  try{
    await smartPost({action:'manager_model_bulk',mode:mode});
    await smartRefresh();
  }catch(e){alert('Gagal: '+e.message);}
}
async function smartTestModel(provider,model,quiet){
  const m=smartFindModel(provider,model); if(!m)return;
  const keys=(SMART_STATE.keys||[]).filter(k=>k.provider_key===provider&&Number(k.enabled)!==0&&k.status!=='disabled'&&k.key_value);
  if(!keys.length){
    if(!quiet)alert('Provider "'+provider+'" belum punya API key aktif. Isi/perbaiki key di Pengaturan API Key.');
    return;
  }
  const busyId=provider+'|'+model;
  SMART_MODEL_BUSY.add(busyId); smartRender();
  let res=null;
  for(const k of keys){
    const t=performance.now();
    try{
      const out=await smartCall(provider,k.key_value,model,SMART_HEALTH_PROMPT);
      if(!out||!String(out).trim()) throw new Error('Respons kosong dari model.');
      res={status:'healthy',http:200,ms:Math.round(performance.now()-t),message:'OK'};
      break;
    }catch(e){
      const x=smartParseHttpError(e);
      res={status:smartClassify(x.http),http:x.http,ms:Math.round(performance.now()-t),message:x.message};
      // Salah model/jaringan: key lain tidak akan beda. Salah key / kena limit: coba key berikutnya.
      if(res.status==='error'||res.status==='model_error')break;
    }
  }
  SMART_MODEL_BUSY.delete(busyId);
  const mm=smartFindModel(provider,model)||m;   // state bisa diganti oleh auto-refresh saat menunggu
  Object.assign(mm,{last_status:res.status,last_http:res.http,last_ms:res.ms,last_message:res.message,last_checked_at:new Date().toLocaleString('sv-SE')});
  if(res.status==='healthy')mm.success_count=Number(mm.success_count||0)+1; else mm.fail_count=Number(mm.fail_count||0)+1;
  smartRender();
  try{await smartPost({action:'manager_model_result',provider_key:provider,model_name:model,status:res.status,http_status:res.http,response_ms:res.ms,message:res.message});}
  catch(e){console.warn('Simpan hasil tes model:',e);}
}
let SMART_TEST_ALL_RUNNING=false;
async function smartTestAllModels(){
  if(SMART_TEST_ALL_RUNNING)return;
  SMART_TEST_ALL_RUNNING=true;
  const btn=document.getElementById('btnTestAllModels');
  if(btn){btn.disabled=true;btn.innerHTML='<i class="fas fa-spinner fa-spin mr-1"></i>Menguji…';}
  try{
    const byProv={};
    (SMART_STATE.models||[]).forEach(m=>(byProv[m.provider_key]??=[]).push(m.model_name));
    // Antar provider paralel, model dalam satu provider berurutan (hindari rate limit).
    await Promise.all(Object.keys(byProv).map(async p=>{for(const name of byProv[p])await smartTestModel(p,name,true);}));
  }finally{
    SMART_TEST_ALL_RUNNING=false;
    if(btn){btn.disabled=false;btn.innerHTML='<i class="fas fa-vial mr-1"></i>Tes Semua Model';}
  }
}
async function smartHealthKey(provider,keyIndex){
  const k=(SMART_STATE.keys||[]).find(x=>x.provider_key===provider&&Number(x.key_index)===Number(keyIndex));
  if(!k)return;
  const models=(SMART_STATE.models||[]).filter(x=>x.provider_key===provider&&x.enabled!=0).sort((a,b)=>Number(a.priority||100)-Number(b.priority||100));
  if(!models.length)return;

  let start=performance.now(),selectedModel='',finalStatus='error',finalHttp=0,finalMsg='',attempts=[];
  for(const m of models){
    const model=m.model_name; const t=performance.now();
    try{
      const out=await smartCall(provider,k.key_value,model,SMART_HEALTH_PROMPT);
      if(!out||!String(out).trim()) throw new Error('Respons kosong dari model.');
      const rms=Math.round(performance.now()-t);
      attempts.push({model,status:'healthy',http:200,message:`OK • ${rms} ms`});
      selectedModel=model;finalStatus='healthy';finalHttp=200;finalMsg=`Health check OK • ${rms} ms`;break;
    }catch(e){
      const x=smartParseHttpError(e), rms=Math.round(performance.now()-t);
      let st=smartClassify(x.http);
      attempts.push({model,status:st,http:x.http,message:x.message});
      finalStatus=st;finalHttp=x.http;finalMsg=x.message;
      // Key/auth/rate-limit errors are provider-wide enough to stop immediately.
      if(st==='invalid_key'||st==='rate_limited')break;
    }
  }
  // Jika semua model gagal, tampilkan pesan tiap model (bukan hanya model terakhir).
  if(finalStatus!=='healthy' && attempts.length>1) finalMsg=attempts.map(a=>`${a.model}: ${a.message}`).join(' | ');
  const ms=Math.round(performance.now()-start);
  const healthRow={
    provider_key:provider,key_index:Number(keyIndex),status:finalStatus,http_status:finalHttp,
    response_ms:ms,message:finalMsg,model_name:selectedModel||models[0].model_name,
    checked_at:new Date().toLocaleString('sv-SE').replace('T',' ')
  };
  // Tampilkan hasil segera walaupun backend logging gagal.
  SMART_STATE.health=SMART_STATE.health||[];
  const hi=SMART_STATE.health.findIndex(x=>x.provider_key===provider&&Number(x.key_index)===Number(keyIndex));
  if(hi>=0) SMART_STATE.health[hi]=healthRow; else SMART_STATE.health.unshift(healthRow);
  const kk=(SMART_STATE.keys||[]).find(x=>x.provider_key===provider&&Number(x.key_index)===Number(keyIndex));
  if(kk){kk.status=finalStatus;kk.last_used_at=healthRow.checked_at;}
  try{
    await smartPost({action:'manager_health',provider_key:provider,key_index:keyIndex,health_status:finalStatus,http_status:finalHttp,response_ms:ms,message:finalMsg,model_name:selectedModel||models[0].model_name,attempts});
  }catch(e){console.warn('Health log backend:',e);}
  smartRender(); smartTab('health');
}
async function smartHealthProvider(provider){
  const keys=(SMART_STATE.keys||[]).filter(k=>k.provider_key===provider);
  for(const k of keys)await smartHealthKey(provider,k.key_index);
}
async function smartHealthAll(){
  smartTab('health');
  const keys=[...(SMART_STATE.keys||[])];
  for(const k of keys)await smartHealthKey(k.provider_key,k.key_index);
}
async function smartClearLogs(){if(!confirm('Hapus semua log Smart AI?'))return;await smartPost({action:'manager_reset_logs'});smartRefresh();}
async function smartSaveSettings(){
  const cfg={cooldown:document.getElementById('smCooldown').value,retry:document.getElementById('smRetry').value,routing:document.getElementById('smRouting').value};
  document.querySelectorAll('.sm-limit-input').forEach(i=>{cfg['limit_'+i.dataset.p]=Math.max(1,parseInt(i.value,10)||SMART_DEFAULTS.limit);});
  await smartPost({action:'manager_settings',settings:cfg});
  await smartRefresh();
  alert('Smart AI Settings tersimpan.');
}
function smartSortKeys(provider,keys){const order=SMART_KEY_ORDER[provider]||[];return [...keys].sort((a,b)=>{const ia=order.indexOf(a),ib=order.indexOf(b);return (ia<0?999:ia)-(ib<0?999:ib)})}
function smartChain(){return SMART_PROVIDER_CHAIN&&SMART_PROVIDER_CHAIN.length?SMART_PROVIDER_CHAIN:null}
async function smartLog(provider,keyIndex,model,success,httpStatus,responseMs,message,event){try{await smartPost({action:'manager_event',provider_key:provider,key_index:keyIndex,model_name:model,success:success?1:0,http_status:httpStatus||0,response_ms:responseMs||0,message:message||'',event:event||''})}catch(e){} }

// Bersihkan sisa data provider yang sudah dihapus dari penyimpanan browser.
(function(){try{
  ['CUSTOM_CLAUDE_KEY','CUSTOM_KIMI_KEY'].forEach(k=>localStorage.removeItem(k));
  const c=JSON.parse(localStorage.getItem('AI_PROVIDER_CHAIN')||'null');
  if(Array.isArray(c)) localStorage.setItem('AI_PROVIDER_CHAIN',JSON.stringify(c.filter(x=>!['claude','kimi'].includes(x))));
}catch(e){}})();
window.addEventListener('load',()=>{smartRefresh();setInterval(smartRefresh,30000)});


function toggleVisibility(inputId, btn) {
    let input = document.getElementById(inputId);
    let icon = btn.querySelector('i');
    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = "password";
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

function copyToClipboard(inputId, btn) {
    let input = document.getElementById(inputId);
    if (!input.value.trim()) {
        alert("Key/Token masih kosong, tidak ada yang disalin.");
        return;
    }

    navigator.clipboard.writeText(input.value.trim()).then(() => {
        let icon = btn.querySelector('i');
        icon.className = 'fas fa-check text-success';
        btn.classList.add('border-success');

        setTimeout(() => {
            icon.className = 'fas fa-copy';
            btn.classList.remove('border-success');
        }, 1500);
    }).catch(err => {
        input.select();
        document.execCommand('copy');
        alert("Berhasil disalin!");
    });
}

function openApiKeyModal() {
    let geminiVal = SERVER_KEYS.gemini || localStorage.getItem('CUSTOM_GEMINI_KEY') || '';
    let openaiVal = SERVER_KEYS.openai || localStorage.getItem('CUSTOM_OPENAI_KEY') || '';
    let groqVal = SERVER_KEYS.groq || localStorage.getItem('CUSTOM_GROQ_KEY') || '';
    let pollinationsVal = SERVER_KEYS.pollinations || localStorage.getItem('CUSTOM_POLLINATIONS_KEY') || '';
    let openRouterVal = SERVER_KEYS.openrouter || localStorage.getItem('CUSTOM_OPENROUTER_KEY') || '';
    
    let chainVal = SERVER_KEYS.chain || localStorage.getItem('AI_PROVIDER_CHAIN') || '["gemini","openai","groq","pollinations","openrouter"]';

    document.getElementById('inputGeminiKey').value = geminiVal;
    document.getElementById('inputOpenaiKey').value = openaiVal;
    document.getElementById('inputGroqKey').value = groqVal;
    document.getElementById('inputPollinationsKey').value = pollinationsVal;
    document.getElementById('inputOpenRouterKey').value = openRouterVal;
    

    let chain = [];
    try { 
        chain = typeof chainVal === 'string' ? JSON.parse(chainVal) : chainVal; 
    } catch(e) { 
        chain = ["gemini","openai","groq","pollinations","openrouter"]; 
    }

    document.querySelectorAll('.provider-chain-item').forEach(cb => {
        cb.checked = chain.includes(cb.value);
    });

    if (typeof $ !== 'undefined') $('#apiKeyModal').modal('show');
}

async function saveApiKeyModal() {
    let geminiVal = document.getElementById('inputGeminiKey').value.trim();
    let openaiVal = document.getElementById('inputOpenaiKey').value.trim();
    let groqVal = document.getElementById('inputGroqKey').value.trim();
    let pollinationsVal = document.getElementById('inputPollinationsKey').value.trim();
    let openRouterVal = document.getElementById('inputOpenRouterKey').value.trim();
    

    let selectedProviders = [];
    document.querySelectorAll('.provider-chain-item:checked').forEach(cb => {
        selectedProviders.push(cb.value);
    });
    let chainJsonStr = JSON.stringify(selectedProviders);

    let formData = new FormData();
    formData.append('action', 'save_keys');
    formData.append('gemini', geminiVal);
    formData.append('openai', openaiVal);
    formData.append('groq', groqVal);
    formData.append('pollinations', pollinationsVal);
    formData.append('openrouter', openRouterVal);
    
    formData.append('chain', chainJsonStr);

    let csrfName = '<?= $this->security->get_csrf_token_name(); ?>';
    let csrfHash = '<?= $this->security->get_csrf_hash(); ?>';
    if (csrfName) formData.append(csrfName, csrfHash);

    // Simpan lokal sebagai fallback agar konfigurasi tidak hilang jika endpoint controller belum tersedia.
    localStorage.setItem('CUSTOM_GEMINI_KEY', geminiVal);
    localStorage.setItem('CUSTOM_OPENAI_KEY', openaiVal);
    localStorage.setItem('CUSTOM_GROQ_KEY', groqVal);
    localStorage.setItem('CUSTOM_POLLINATIONS_KEY', pollinationsVal);
    localStorage.setItem('CUSTOM_OPENROUTER_KEY', openRouterVal);
    
    localStorage.setItem('AI_PROVIDER_CHAIN', chainJsonStr);

    try {
        let res = await fetch(window.location.href, {
            method: 'POST',
            body: formData,
            credentials: 'same-origin',
            headers: {'X-Requested-With':'XMLHttpRequest'}
        });

        let raw = await res.text();
        let result;
        try { result = JSON.parse(raw); }
        catch (_) { throw new Error(`HTTP ${res.status}: server tidak mengembalikan JSON.`); }

        if (result.status === 'success') {
            localStorage.setItem('CUSTOM_GEMINI_KEY', geminiVal);
            localStorage.setItem('CUSTOM_OPENAI_KEY', openaiVal);
            localStorage.setItem('CUSTOM_GROQ_KEY', groqVal);
            localStorage.setItem('CUSTOM_POLLINATIONS_KEY', pollinationsVal);
            localStorage.setItem('CUSTOM_OPENROUTER_KEY', openRouterVal);
            
                          localStorage.setItem('AI_PROVIDER_CHAIN', chainJsonStr);

            alert("API Key berhasil disimpan. Konfigurasi lokal aktif dan key juga tersimpan di Database Server.");
            if (typeof $ !== 'undefined') $('#apiKeyModal').modal('hide');
            location.reload();
        } else {
            alert("Gagal menyimpan: " + result.message);
        }
    } catch (err) {
        alert("Gagal koneksi ke server: " + err.message);
    }
}

function getApiKeys(provider) {
    let keyStr = SERVER_KEYS[provider] || localStorage.getItem(`CUSTOM_${provider.toUpperCase()}_KEY`) || '';
    if (!keyStr) return [];
    return smartSortKeys(provider, keyStr.split(',').map(k => k.trim()).filter(k => k.length > 0));
}

if (typeof window !== 'undefined' && 'pdfjsLib' in window) {
    window.pdfjsLib.GlobalWorkerOptions.workerSrc = window.PDFJS_WORKER_SRC || '<?= base_url() ?>/assets/plugins/converter/pdf.worker.min.js';
}

let parsedData = [];
// Tabel dan numbering hasil import/copy-paste disimpan terpisah agar parser teks tidak merusaknya.
let EDITOR_TABLES = {};
let EDITOR_TABLE_SEQ = 0;

// Gambar pada editor diganti token [[IMG_xxxx]] saat parsing (innerText membuang <img>),
// lalu dikembalikan saat Preview/Word. Token berbasis isi gambar, jadi stabil untuk cache.
let EDITOR_IMAGES = {};
let EDITOR_IMAGE_SIZES = {}; // token -> {w,h} (px) bila gambar diatur/ditempel dengan ukuran tertentu

// Lampiran gambar terpisah per nomor soal (maksimal 4 gambar per soal).
let QUESTION_ATTACHMENTS = {}; // nomor soal -> [{token, src, name}]
const IMG_TOKEN_CACHE = new Map();
const PREVIEW_IMAGE_SCALE = 0.5; // gambar di Preview tampil 50% dari ukuran aslinya

function safeImageSrc(src) {
    src = String(src || '').trim();
    if (/^data:image\/(?:png|jpe?g|gif|webp|bmp|svg\+xml);base64,[a-z0-9+\/=\s]+$/i.test(src)) return src;
    if (/^https?:\/\//i.test(src)) return src;
    return '';
}

function makeImageToken(src) {
    let token = IMG_TOKEN_CACHE.get(src);
    if (token) return token;
    let h1 = 0x811c9dc5, h2 = 0x01000193 ^ src.length;
    for (let i = 0; i < src.length; i++) {
        const c = src.charCodeAt(i);
        h1 ^= c; h1 = Math.imul(h1, 16777619);
        h2 = Math.imul(h2 ^ c, 2246822519); h2 ^= h2 >>> 13;
    }
    token = '[[IMG_' + (h1 >>> 0).toString(16).padStart(8, '0') + (h2 >>> 0).toString(16).padStart(8, '0') + src.length.toString(36) + ']]';
    IMG_TOKEN_CACHE.set(src, token);
    return token;
}

function replaceImageTokens(html, inline) {
    return String(html || '').replace(/\[\[IMG_([0-9a-z]+)\]\]/g, (m, id) => {
        const src = EDITOR_IMAGES['[[IMG_' + id + ']]'];
        if (!src) return '';
        const safe = String(src).replace(/&/g, '&amp;').replace(/"/g, '&quot;');
        const sz = EDITOR_IMAGE_SIZES['[[IMG_' + id + ']]'];
        if (sz && sz.w > 0) {
            return '<img class="preview-img" data-fixed="1" src="' + safe + '" alt="Gambar soal" style="display:' + (inline ? 'inline-block' : 'block') + ';width:' + Math.round(sz.w) + 'px;max-width:100%;height:auto">';
        }
        return '<img class="preview-img" src="' + safe + '" alt="Gambar soal" style="display:' + (inline ? 'inline-block' : 'block') + '">';
    });
}

// Tampilkan semua gambar Preview pada 50% dari ukuran aslinya (tetap tidak melebihi lebar kotak).
function applyPreviewImageScale(root) {
    if (!root) return;
    root.querySelectorAll('img:not([data-fixed])').forEach(img => {
        const apply = () => {
            if (img.naturalWidth) {
                img.style.width = Math.max(1, Math.round(img.naturalWidth * PREVIEW_IMAGE_SCALE)) + 'px';
            } else {
                img.style.width = (PREVIEW_IMAGE_SCALE * 100) + '%';
            }
            img.style.maxWidth = '100%';
            img.style.height = 'auto';
        };
        if (img.complete && img.naturalWidth) apply();
        else img.addEventListener('load', apply, { once: true });
    });
}

// Jika AI menghapus token gambar, kembalikan ke posisi soal/opsi yang sama.
function restoreMissingImageTokens(sourceItems, aiItems) {
    if (!Array.isArray(aiItems) || !Array.isArray(sourceItems) || aiItems.length !== sourceItems.length) return aiItems;
    const tokensOf = v => String(v || '').match(/\[\[IMG_[0-9a-z]+\]\]/g) || [];
    aiItems.forEach((ai, i) => {
        if (!ai || typeof ai !== 'object') return;
        const src = sourceItems[i] || {};
        const qMiss = tokensOf(src.question).filter(t => !String(ai.question || '').includes(t));
        if (qMiss.length) ai.question = (String(ai.question || '').trim() + ' ' + qMiss.join(' ')).trim();
        if (Array.isArray(src.options) && Array.isArray(ai.options) && src.options.length === ai.options.length) {
            src.options.forEach((o, j) => {
                const miss = tokensOf(o).filter(t => !String(ai.options[j] || '').includes(t));
                if (miss.length) ai.options[j] = (String(ai.options[j] || '').trim() + ' ' + miss.join(' ')).trim();
            });
        }
    });
    return aiItems;
}

let isAiProcessed = false;
let activeQuestionFingerprint = '';
let cacheLoaded = false;
let cacheLookupTimer = null;
let cacheLookupInProgress = false;

function normalizeForFingerprint(value) {
    return String(value ?? '')
        .replace(/\r\n/g, '\n')
        .replace(/\r/g, '\n')
        .replace(/[\u00A0\u1680\u180E\u2000-\u200B\u202F\u205F\u3000\t]+/g, ' ')
        .replace(/\s+/g, ' ')
        .trim();
}

function getFingerprintSource(data) {
    return (data || []).map(item => ({
        question: normalizeForFingerprint(item.question),
        options: Array.isArray(item.options) ? item.options.map(normalizeForFingerprint) : [],
        jenisDraft: item.jenis ? String(item.jenis) : '',
        answer: item.answer ? normalizeForFingerprint(item.answer) : ''
    }));
}

async function sha256Hex(text) {
    if (window.crypto && window.crypto.subtle) {
        const buffer = await crypto.subtle.digest('SHA-256', new TextEncoder().encode(text));
        return Array.from(new Uint8Array(buffer)).map(b => b.toString(16).padStart(2, '0')).join('');
    }
    let h1 = 0x811c9dc5, h2 = 0x01000193;
    for (let i = 0; i < text.length; i++) {
        const c = text.charCodeAt(i);
        h1 ^= c; h1 = Math.imul(h1, 16777619);
        h2 ^= c + i; h2 = Math.imul(h2, 16777619);
    }
    return (h1 >>> 0).toString(16).padStart(8,'0') + (h2 >>> 0).toString(16).padStart(8,'0') +
           (text.length >>> 0).toString(16).padStart(8,'0') + '00000000000000000000000000000000';
}

async function getQuestionFingerprint(data = parsedData) {
    const canonical = JSON.stringify(getFingerprintSource(data));
    return await sha256Hex(canonical);
}

function setCacheStatus(message, type = 'muted') {
    const el = document.getElementById('cacheStatus');
    if (!el) return;
    el.className = `small text-${type} mb-2`;
    el.style.display = message ? 'block' : 'none';
    el.innerHTML = message;
}

// Hapus angka "peninggalan batch" yang salah menempel di AKHIR opsi D saja.
// No. 10-20 -> hapus 1; No. 21-30 -> hapus 2; No. 31-40 -> hapus 3; dst.
// Opsi A/B/C/E dan nomor di luar aturan TIDAK diubah.
function cleanTrailingBatchMarker(item) {
    if (!item || !Array.isArray(item.options) || item.options.length < 4) return item;

    const no = Number(item.number || 0);
    const dIndex = 3;
    let d = String(item.options[dIndex] ?? '').trim();

    // Hapus angka sisa yang ditempelkan generator/batch di belakang pilihan D.
    // Prioritaskan marker berdasarkan nomor soal; jika tidak cocok, bersihkan
    // pola nomor batch yang berdiri sendiri di ujung teks.
    let marker = null;
    if (no >= 10 && no <= 20) marker = '1';
    else if (no >= 21) marker = String(Math.floor((no - 1) / 10));

    if (marker !== null) {
        const reMarker = new RegExp('\\s*' + marker.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + '\\s*$', 'u');
        d = d.replace(reMarker, '').trim();
    }

    // JANGAN menghapus angka terakhir secara umum.
    // Angka seperti "1, 4, dan 5" pada pilihan D adalah bagian sah dari jawaban.
    // Marker batch hanya boleh dihapus jika nomor soal memang menentukan marker
    // di atas (no. >= 10), sehingga pilihan pada soal 1-9 tetap utuh.
    item.options[dIndex] = d;
    return item;
}

function normalizeAiResult(data) {
    if (!Array.isArray(data)) return [];
    return data.map((item, index) => {
        item = item || {};
        item.number = index + 1;
        item.question = String(item.question || '').replace(/^\d+[\.\)]\s*/, '').trim();
        item.images = Array.isArray(item.images) ? item.images : [];
        return cleanTrailingBatchMarker(item);
    });
}

async function lookupConverterCache(showMiss = false) {
    if (cacheLookupInProgress || !parsedData || parsedData.length === 0 || isAiProcessed) return false;
    cacheLookupInProgress = true;
    try {
        const fingerprint = await getQuestionFingerprint(parsedData);
        activeQuestionFingerprint = fingerprint;
        const fd = new FormData();
        fd.append('action', 'cache_lookup');
        fd.append('fingerprint', fingerprint);
        const csrfName = '<?= $this->security->get_csrf_token_name(); ?>';
        const csrfHash = '<?= $this->security->get_csrf_hash(); ?>';
        if (csrfName) fd.append(csrfName, csrfHash);

        const res = await fetch(window.location.href, { method: 'POST', body: fd });
        const result = await res.json();
        if (result.status === 'hit' && Array.isArray(result.data) && result.data.length) {
            parsedData = normalizeAiResult(result.data);
            isAiProcessed = true;
            cacheLoaded = true;
            document.getElementById('btnAiAnswer').style.display = 'none';
            setCacheStatus(`<i class="fas fa-database mr-1"></i> Hasil ditemukan di cache. ${result.question_count || parsedData.length} soal siap diunduh tanpa convert ulang. <span class="text-muted">${result.cached_at || ''}</span>`, 'success');
            renderPreview();
            return true;
        }
        if (showMiss) setCacheStatus('<i class="fas fa-info-circle mr-1"></i> Belum ada hasil tersimpan. Klik AI Generator untuk memproses.', 'muted');
    } catch (e) {
        console.warn('Cache lookup gagal:', e);
        if (showMiss) setCacheStatus('<i class="fas fa-exclamation-triangle mr-1"></i> Cache server tidak dapat diperiksa. Anda tetap dapat menjalankan AI.', 'warning');
    } finally {
        cacheLookupInProgress = false;
    }
    return false;
}

function scheduleCacheLookup() {
    clearTimeout(cacheLookupTimer);
    cacheLookupTimer = setTimeout(() => lookupConverterCache(false), 900);
}

async function saveConverterCache(fingerprint, data) {
    if (!fingerprint || !Array.isArray(data) || !data.length) return false;
    try {
        const fd = new FormData();
        fd.append('action', 'cache_save');
        fd.append('fingerprint', fingerprint);
        fd.append('payload', JSON.stringify(data));
        const csrfName = '<?= $this->security->get_csrf_token_name(); ?>';
        const csrfHash = '<?= $this->security->get_csrf_hash(); ?>';
        if (csrfName) fd.append(csrfName, csrfHash);
        const res = await fetch(window.location.href, { method: 'POST', body: fd });
        const result = await res.json();
        if (result.status === 'success') {
            setCacheStatus(`<i class="fas fa-database mr-1"></i> Hasil AI tersimpan. Import ulang soal yang sama akan menggunakan cache tanpa convert ulang.`, 'success');
            return true;
        }
    } catch (e) {
        console.warn('Cache save gagal:', e);
    }
    return false;
}

// =========================================================================
// TEMPEL DARI WORD (teks + tabel + GAMBAR)
// Word menaruh gambar di clipboard sebagai <img src="file:///..."> (tidak bisa dibaca browser)
// DAN sebagai data RTF (\pict ... \pngblip / \jpegblip). Gambar aslinya diambil dari RTF,
// lalu dipasang sebagai gambar sungguhan (data URI) pada posisi yang sama seperti di Word.
// =========================================================================
function rtfHexToDataUri(hex, mime) {
    hex = String(hex || '').replace(/[^0-9a-fA-F]/g, '');
    if (hex.length % 2) hex = hex.slice(0, -1);
    const parts = [];
    for (let i = 0; i < hex.length; i += 16384) {
        const chunk = hex.slice(i, i + 16384);
        const codes = [];
        for (let k = 0; k < chunk.length; k += 2) codes.push(parseInt(chunk.substr(k, 2), 16));
        parts.push(String.fromCharCode.apply(null, codes));
    }
    return 'data:' + mime + ';base64,' + btoa(parts.join(''));
}

function rtfParsePictGroup(top) {
    const fmt = /\\pngblip/.test(top) ? 'png'
        : /\\jpegblip/.test(top) ? 'jpeg'
        : /\\emfblip/.test(top) ? 'emf'
        : /\\wmetafile\d*/.test(top) ? 'wmf' : 'other';
    const num = name => { const m = top.match(new RegExp('\\\\' + name + '(-?\\d+)')); return m ? parseInt(m[1], 10) : 0; };
    const wg = num('picwgoal'), hg = num('pichgoal');
    const sx = num('picscalex') || 100, sy = num('picscaley') || 100;
    const w = wg ? Math.round(wg * sx / 100 / 15) : 0;   // twips -> px (1 px = 15 twips)
    const h = hg ? Math.round(hg * sy / 100 / 15) : 0;
    let src = null;
    if (fmt === 'png' || fmt === 'jpeg') {
        const hex = top.replace(/\\[a-zA-Z]+-?\d* ?/g, '').replace(/[^0-9a-fA-F]/g, '');
        if (hex.length > 16) src = rtfHexToDataUri(hex, 'image/' + fmt);
    }
    return { fmt: fmt, w: w, h: h, src: src };
}

// Daftar gambar pada RTF berurutan sesuai posisinya (salinan cadangan \nonshppict dilewati).
function rtfExtractPictures(rtf) {
    const pics = [];
    rtf = String(rtf || '');
    if (rtf.indexOf('\\pict') < 0) return pics;
    const n = rtf.length;
    const nonStack = [];
    let nonDepth = 0, i = 0;
    while (i < n) {
        const c = rtf.charCodeAt(i);
        if (c === 92) { i += 2; continue; }               // "\" : lewati control word / karakter escape
        if (c === 123) {                                   // "{"
            if (rtf.startsWith('\\pict', i + 1)) {
                let depth = 0, j = i, top = '';
                while (j < n) {
                    const cj = rtf.charCodeAt(j);
                    if (cj === 92) {
                        const nx = rtf.charAt(j + 1);
                        if (nx === '{' || nx === '}' || nx === '\\') { if (depth === 1) top += rtf.substr(j, 2); j += 2; continue; }
                    }
                    if (cj === 123) { depth++; j++; continue; }
                    if (cj === 125) { depth--; j++; if (depth === 0) break; continue; }
                    if (depth === 1) top += rtf.charAt(j);
                    j++;
                }
                if (nonDepth === 0) pics.push(rtfParsePictGroup(top));
                i = j; continue;
            }
            const isNon = rtf.startsWith('\\*\\nonshppict', i + 1) || rtf.startsWith('\\nonshppict', i + 1);
            nonStack.push(isNon); if (isNon) nonDepth++;
            i++; continue;
        }
        if (c === 125) { if (nonStack.length && nonStack.pop()) nonDepth--; i++; continue; }
        i++;
    }
    return pics;
}

function isWordLikeHtml(html) {
    html = String(html || '');
    return /<img\b|<table\b/i.test(html)
        || /urn:schemas-microsoft-com:office|class=["']?Mso|mso-|ProgId["']?\s+content=["']?Word|generator["']?\s+content=["']?(?:LibreOffice|Microsoft Word)/i.test(html);
}

// HTML hasil salin Word -> HTML bersih untuk kotak input (nomor, pilihan a-d, tabel, gambar tetap utuh).
function wordHtmlToEditorHtml(html, rtf) {
    const fr = String(html).match(/<!--StartFragment-->([\s\S]*?)<!--EndFragment-->/i);
    const frag = fr ? fr[1] : ((String(html).match(/<body[^>]*>([\s\S]*)<\/body>/i) || [0, html])[1]);
    const doc = new DOMParser().parseFromString('<!doctype html><html><body>' + frag + '</body></html>', 'text/html');
    const root = doc.body;
    root.querySelectorAll('style,script,meta,link,title,xml,iframe,object,embed').forEach(el => el.remove());
    // Elemen namespace Office (o:p, v:shape, ...) dibuang; gambar sungguhan ada pada <img>.
    Array.from(root.querySelectorAll('*')).forEach(el => { if (el.tagName.indexOf(':') > -1) el.remove(); });

    const cssPx = (style, prop) => {
        const m = String(style || '').match(new RegExp('(?:^|;)\\s*' + prop + '\\s*:\\s*([\\d.]+)\\s*(px|pt|in|cm)?', 'i'));
        if (!m) return 0;
        const v = parseFloat(m[1]); const u = (m[2] || 'px').toLowerCase();
        return u === 'pt' ? v * 96 / 72 : u === 'in' ? v * 96 : u === 'cm' ? v * 96 / 2.54 : v;
    };

    // ---- gambar: ambil data asli dari RTF bila src berupa file:/// ----
    const pics = rtfExtractPictures(rtf);
    let pi = 0, lost = 0, fromRtf = 0;
    Array.from(root.querySelectorAll('img')).forEach(img => {
        let src = (img.getAttribute('src') || '').trim();
        const st = img.getAttribute('style') || '';
        let w = parseFloat(img.getAttribute('width')) || cssPx(st, 'width');
        let h = parseFloat(img.getAttribute('height')) || cssPx(st, 'height');
        if (safeImageSrc(src)) {
            // data: / http(s): dipakai langsung
        } else {
            const p = pics[pi++];
            if (p && p.src) {
                src = p.src; fromRtf++;
                if (!w && p.w) { w = p.w; h = p.h; }
            } else { lost++; img.remove(); return; }
        }
        img.setAttribute('src', src);
        if (w > 0) {
            img.setAttribute('data-w', Math.round(w));
            if (h > 0) img.setAttribute('data-h', Math.round(h));
        }
    });

    // ---- tabel: pertahankan garis bila di Word memakai garis ----
    root.querySelectorAll('table').forEach(t => {
        const b = parseFloat(t.getAttribute('border'));
        if (b > 0) t.setAttribute('data-border', '1'); else t.removeAttribute('data-border');
    });

    // ---- bersihkan atribut ----
    const keepAttr = { IMG: ['src', 'data-w', 'data-h'], TD: ['colspan', 'rowspan'], TH: ['colspan', 'rowspan'], TABLE: ['data-border'] };
    root.querySelectorAll('*').forEach(el => {
        const keep = keepAttr[el.tagName] || [];
        Array.from(el.attributes).forEach(a => { if (keep.indexOf(a.name) < 0) el.removeAttribute(a.name); });
    });
    root.querySelectorAll('table[data-border]').forEach(t => { t.removeAttribute('data-border'); t.setAttribute('border', '1'); });
    // tampilkan gambar pada ukuran aslinya di Word (px), tidak melebihi lebar kotak input
    root.querySelectorAll('img[data-w]').forEach(im => {
        im.style.width = im.getAttribute('data-w') + 'px';
        im.style.height = 'auto';
        im.style.maxWidth = '100%';
    });

    // ---- ratakan tag: p/h1.. -> div, span/font/a dst dibuka ----
    const allowed = new Set(['B', 'STRONG', 'I', 'EM', 'U', 'SUB', 'SUP', 'BR', 'IMG', 'TABLE', 'THEAD', 'TBODY', 'TFOOT', 'TR', 'TD', 'TH', 'DIV', 'OL', 'UL', 'LI']);
    Array.from(root.querySelectorAll('*')).reverse().forEach(el => {
        const tag = el.tagName;
        if (!el.parentNode) return;
        if (/^(P|H[1-6]|BLOCKQUOTE|CENTER|PRE|SECTION|ARTICLE)$/.test(tag)) {
            const d = doc.createElement('div');
            while (el.firstChild) d.appendChild(el.firstChild);
            el.replaceWith(d); return;
        }
        if (tag === 'COL' || tag === 'COLGROUP') { el.remove(); return; }
        if (!allowed.has(tag)) {
            while (el.firstChild) el.parentNode.insertBefore(el.firstChild, el);
            el.remove();
        }
    });

    // komentar kondisional Word (<!--[if gte vml 1]> dst.) tidak diperlukan lagi
    const cw = doc.createTreeWalker(root, NodeFilter.SHOW_COMMENT);
    const comments = []; while (cw.nextNode()) comments.push(cw.currentNode);
    comments.forEach(c => c.remove());

    // ---- teks: baris baru sumber HTML hanyalah pembungkus, bukan enter ----
    const tw = doc.createTreeWalker(root, NodeFilter.SHOW_TEXT);
    const texts = []; while (tw.nextNode()) texts.push(tw.currentNode);
    texts.forEach(tn => { tn.nodeValue = tn.nodeValue.replace(/[\r\n]+\s*/g, ' '); });

    // ---- buka pembungkus div (WordSection1 dsb.) supaya tiap paragraf menjadi anak langsung ----
    let again = true, guard = 0;
    while (again && guard++ < 20) {
        again = false;
        Array.from(root.querySelectorAll('div')).forEach(d => {
            if (!d.parentNode || d.closest('td,th,li') ) return;
            const kids = Array.from(d.childNodes);
            const hasBlock = kids.some(c => c.nodeType === 1 && /^(DIV|TABLE|OL|UL)$/.test(c.tagName));
            const onlyEl = kids.every(c => c.nodeType === 1 || (c.nodeType === 3 && !c.nodeValue.trim()));
            if (hasBlock && onlyEl) { while (d.firstChild) d.parentNode.insertBefore(d.firstChild, d); d.remove(); again = true; }
        });
    }
    // paragraf kosong = baris kosong (pemisah antar soal)
    root.querySelectorAll('div').forEach(d => {
        if (!d.querySelector('img,table,ol,ul,div') && !(d.textContent || '').replace(/\u00A0/g, ' ').trim()) d.innerHTML = '<br>';
    });

    // ---- samakan dengan jalur impor .docx ----
    normalizeImportedDocxLists(root);
    normalizeTwoColumnOptionRows(root);
    return { html: root.innerHTML.trim(), lost: lost, fromRtf: fromRtf, rtfPics: pics.length };
}

function insertPlainTextIntoEditor(textData) {
    textData = String(textData || '').replace(/[\u00A0\u1680\u180E\u2000-\u200B\u202F\u205F\u3000\t]/g, ' ');
    document.execCommand('insertText', false, textData);
}

function handleRichPaste(html, rtf) {
    let out;
    try { out = wordHtmlToEditorHtml(html, rtf); }
    catch (err) { console.warn('Tempel Word gagal diproses, memakai teks biasa:', err); return false; }
    if (!out || !out.html) return false;
    const ed = document.getElementById('inputText');
    ed.focus();
    document.execCommand('insertHTML', false, out.html);
    if (out.lost > 0) {
        setTimeout(() => setCacheStatus(out.lost + ' gambar tidak bisa dibaca (format EMF/WMF/objek gambar Word). ' +
            'Di Word: klik kanan gambar > Simpan sebagai gambar (PNG), lalu tempel/unggah gambar itu di sini.', 'warning'), 400);
    }
    return true;
}

const inputArea = document.getElementById('inputText');
if (inputArea) {
    inputArea.addEventListener('paste', function(e) {
        let clipboardData = e.clipboardData || window.clipboardData;
        if (!clipboardData) return;

        const html = clipboardData.getData('text/html') || '';
        const rtf = clipboardData.getData('text/rtf') || '';
        let hasImageItem = false;
        if (clipboardData.items) {
            for (let i = 0; i < clipboardData.items.length; i++) {
                if (clipboardData.items[i].type.indexOf('image') !== -1) hasImageItem = true;
            }
        }

        // 1) Salinan dari Word / dokumen kaya (teks, nomor, pilihan, tabel, gambar)
        if (html && isWordLikeHtml(html)) {
            const visible = html.replace(/<!--[\s\S]*?-->/g, '').replace(/<style[\s\S]*?<\/style>/gi, '')
                .replace(/<[^>]*>/g, ' ').replace(/&nbsp;|\s+/g, ' ').trim();
            if (visible.length > 0 || !hasImageItem) {
                e.preventDefault();
                if (!handleRichPaste(html, rtf)) {
                    const t = clipboardData.getData('text/plain');
                    if (t) insertPlainTextIntoEditor(t);
                }
                setTimeout(() => autoCleanAndParse(), 100);
                return;
            }
        }

        // 2) Gambar saja (screenshot / salin gambar) dan teks biasa -> perilaku sebelumnya
        let hasImage = false;
        if (clipboardData.items) {
            for (let i = 0; i < clipboardData.items.length; i++) {
                if (clipboardData.items[i].type.indexOf('image') !== -1) {
                    e.preventDefault();
                    hasImage = true;
                    let blob = clipboardData.items[i].getAsFile();
                    let reader = new FileReader();
                    reader.onload = function(event) { insertImageToInput(event.target.result); };
                    reader.readAsDataURL(blob);
                }
            }
        }
        if (!hasImage) {
            let textData = clipboardData.getData('text/plain');
            if (textData) {
                e.preventDefault();
                insertPlainTextIntoEditor(textData);
            }
        }
        setTimeout(() => autoCleanAndParse(), 100);
    });
}

// =========================================================================
// EDIT GAMBAR di kotak input: klik gambar -> kecilkan / besarkan / ukuran asli / hapus.
// Ukuran disimpan (data-w/data-h) dan dipakai di Preview serta file Word (.docx, gambar tetap
// gambar sungguhan yang bisa diedit di Word).
// =========================================================================
(function initEditorImageTools() {
    const ed = document.getElementById('inputText');
    if (!ed) return;
    let bar = null, cur = null;
    const btn = 'background:#222;color:#fff;border:1px solid #00ff9c;border-radius:4px;padding:1px 8px;margin:0 2px;cursor:pointer;font-size:12px';

    function ensureBar() {
        if (bar) return bar;
        bar = document.createElement('div');
        bar.style.cssText = 'position:absolute;z-index:99999;display:none;background:#111;color:#fff;border:1px solid #00ff9c;border-radius:6px;padding:3px 4px;font-size:12px;box-shadow:0 2px 8px rgba(0,0,0,.5);white-space:nowrap;user-select:none';
        bar.innerHTML = '<button type="button" data-a="dec" style="' + btn + '" title="Kecilkan">&minus;</button>' +
            '<span data-r="w" style="display:inline-block;min-width:54px;text-align:center"></span>' +
            '<button type="button" data-a="inc" style="' + btn + '" title="Besarkan">+</button>' +
            '<button type="button" data-a="orig" style="' + btn + '" title="Ukuran asli">100%</button>' +
            '<button type="button" data-a="del" style="' + btn + ';border-color:#ff5c5c;color:#ff8a8a" title="Hapus gambar">Hapus</button>';
        bar.addEventListener('mousedown', ev => ev.preventDefault());
        bar.addEventListener('click', ev => {
            const a = ev.target && ev.target.getAttribute && ev.target.getAttribute('data-a');
            if (!a || !cur) return;
            if (a === 'del') { const im = cur; hide(); im.remove(); autoCleanAndParse(); return; }
            resize(a === 'inc' ? 1.15 : a === 'dec' ? 1 / 1.15 : null);
        });
        document.body.appendChild(bar);
        return bar;
    }
    function place() {
        if (!cur || !bar) return;
        const r = cur.getBoundingClientRect();
        const bh = bar.offsetHeight || 26;
        const top = r.top > bh + 12 ? r.top - bh - 6 : r.bottom + 6;
        bar.style.left = Math.max(4, r.left + window.scrollX) + 'px';
        bar.style.top = (top + window.scrollY) + 'px';
        const lab = bar.querySelector('[data-r="w"]');
        if (lab) lab.textContent = Math.round(r.width) + ' px';
    }
    function select(img) {
        if (cur && cur !== img) cur.style.outline = '';
        cur = img;
        cur.style.outline = '2px solid #00ff9c';
        ensureBar().style.display = 'block';
        place();
    }
    function hide() {
        if (cur) cur.style.outline = '';
        cur = null;
        if (bar) bar.style.display = 'none';
    }
    function resize(factor) {
        if (!cur) return;
        const r = cur.getBoundingClientRect();
        const ratio = (cur.naturalWidth && cur.naturalHeight) ? cur.naturalHeight / cur.naturalWidth : (r.width ? r.height / r.width : 1);
        const nw = factor ? Math.max(16, Math.round(r.width * factor)) : Math.round(cur.naturalWidth || r.width);
        cur.style.width = nw + 'px';
        cur.style.height = 'auto';
        cur.style.maxWidth = 'none';
        cur.setAttribute('data-w', nw);
        cur.setAttribute('data-h', Math.round(nw * ratio));
        place();
        autoCleanAndParse();
    }
    ed.addEventListener('click', e => {
        const t = e.target;
        if (t && t.tagName === 'IMG') select(t); else hide();
    });
    document.addEventListener('mousedown', e => {
        if (bar && bar.contains(e.target)) return;
        if (!ed.contains(e.target)) hide();
    });
    window.addEventListener('resize', place);
    ed.addEventListener('scroll', place);
})();

function insertImageToInput(base64Src) {
    let imgHTML = `<br><img src="${base64Src}" style="max-width:220px; display:block; margin:5px 0;"><br>`;
    document.execCommand('insertHTML', false, imgHTML);
    autoCleanAndParse();
}

function showProgressState(message) {
    const container = document.getElementById('previewRenderArea');
    stopAILoader();
    container.innerHTML = `
        <div class="ai-ld" id="aiLoaderFile">
            <div class="ai-ld-head">
                <div class="ai-ld-orb"><i class="fas fa-file-import"></i></div>
                <div class="ai-ld-title"><b>${message}</b><span>Mohon tunggu, proses mengekstrak file…</span></div>
            </div>
            <div class="ai-ld-bar indet"><span></span></div>
            <div class="ai-ld-foot">Teks, nomor soal, tabel, dan gambar sedang dibaca.</div>
        </div>`;
}


// ===== Word Asli Editor (tambahan) =====
// Buffer asli disimpan terpisah agar parser/AI lama tetap berjalan seperti sebelumnya.
let ORIGINAL_WORD_BUFFER = null;
let ORIGINAL_WORD_NAME = '';
let ORIGINAL_WORD_XML = null;
let ORIGINAL_WORD_ITEMS = [];

// Equation hasil impor DOCX untuk parser soal. Word menyimpan Equation sebagai OMML
// (m:oMath), sedangkan Mammoth tidak selalu mengeluarkannya sebagai teks. Karena itu
// sebelum dikirim ke Mammoth, Equation diubah sementara menjadi token [[EQ_n]].
// File Word asli TIDAK diubah; token hanya dipakai pada salinan untuk pembacaan soal.
let IMPORTED_DOCX_EQUATIONS = {};

async function injectDocxEquationPlaceholders(arrayBuffer) {
    try {
        if (typeof JSZip === 'undefined' || typeof DOMParser === 'undefined') return arrayBuffer;
        const zip = await JSZip.loadAsync(arrayBuffer);
        const docFile = zip.file('word/document.xml');
        if (!docFile) return arrayBuffer;

        const W = 'http://schemas.openxmlformats.org/wordprocessingml/2006/main';
        const M = 'http://schemas.openxmlformats.org/officeDocument/2006/math';
        const XMLNS = 'http://www.w3.org/XML/1998/namespace';
        const parser = new DOMParser();
        const doc = parser.parseFromString(await docFile.async('string'), 'application/xml');
        if (doc.getElementsByTagName('parsererror').length) return arrayBuffer;

        IMPORTED_DOCX_EQUATIONS = {};
        let seq = 0;
        const mathNodes = Array.from(doc.getElementsByTagNameNS(M, 'oMath'));

        mathNodes.forEach(mathNode => {
            const latex = ommlToLatex(mathNode).trim();
            if (!latex) return;
            const token = `[[EQ_${++seq}]]`;
            IMPORTED_DOCX_EQUATIONS[token] = latex;

            const r = doc.createElementNS(W, 'w:r');
            const t = doc.createElementNS(W, 'w:t');
            t.setAttributeNS(XMLNS, 'xml:space', 'preserve');
            t.textContent = ` ${token} `;
            r.appendChild(t);
            mathNode.parentNode.replaceChild(r, mathNode);
        });

        if (!seq) return arrayBuffer;
        zip.file('word/document.xml', new XMLSerializer().serializeToString(doc));
        return await zip.generateAsync({type:'arraybuffer', compression:'DEFLATE'});
    } catch (err) {
        console.warn('Equation DOCX tidak diubah menjadi placeholder:', err);
        IMPORTED_DOCX_EQUATIONS = {};
        return arrayBuffer;
    }
}

function replaceImportedEquationTokens(html) {
    let out = String(html || '');
    Object.keys(IMPORTED_DOCX_EQUATIONS).forEach(token => {
        const latex = IMPORTED_DOCX_EQUATIONS[token];
        const safeToken = token.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        const safeLatex = escapeHtml(latex);
        const fallbackHtml = (typeof renderEquationHtml === 'function') ? renderEquationHtml(latex) : safeLatex;
        const replacement = `<span class="imported-equation" data-equation-latex="${safeLatex}" contenteditable="false" title="Equation Word — ${safeLatex}">${fallbackHtml}</span>`;
        out = out.replace(new RegExp(safeToken, 'g'), replacement);
    });
    return out;
}

function restoreImportedEquationText(clone) {
    clone.querySelectorAll('[data-equation-latex]').forEach(el => {
        const latex = el.getAttribute('data-equation-latex') || '';
        el.replaceWith(document.createTextNode(' ' + latex + ' '));
    });
}

// ================================================================
// EDIT LANGSUNG RUMUS/PECAHAN DI KOTAK INPUT UTAMA
// Klik pecahan/rumus hasil Word -> editor kecil muncul tepat di bawahnya.
// Nilai LaTeX disimpan kembali ke data-equation-latex sehingga parser,
// preview, AI, dan export tetap memakai rumus yang sudah diedit.
// ================================================================
let activeInlineEquation = null;
let inlineEquationEditor = null;

function ensureInlineEquationEditor() {
    if (inlineEquationEditor) return inlineEquationEditor;
    const box = document.createElement('div');
    box.className = 'eq-inline-editor';
    box.innerHTML = `
        <div class="eq-inline-title"><i class="fas fa-square-root-alt mr-1"></i>Edit Pecahan / Rumus Word</div>
        <textarea spellcheck="false" aria-label="LaTeX rumus"></textarea>
        <div class="eq-inline-preview"></div>
        <div class="eq-inline-actions">
            <span class="eq-inline-help">Contoh: \frac{1}{3} &nbsp; x^2 &nbsp; \sqrt{x}</span>
            <button type="button" data-eq-act="cancel">Batal</button>
            <button type="button" data-eq-act="apply" class="primary">Terapkan</button>
        </div>`;
    document.body.appendChild(box);
    inlineEquationEditor = box;

    const ta = box.querySelector('textarea');
    const pv = box.querySelector('.eq-inline-preview');
    const update = () => {
        pv.innerHTML = (typeof renderEquationHtml === 'function') ? renderEquationHtml(ta.value || '') : ('\\(' + escapeHtml(ta.value || '') + '\\)');
        if (window.MathJax && MathJax.typesetPromise) MathJax.typesetPromise([pv]).catch(() => {});
    };
    ta.addEventListener('input', update);
    box.addEventListener('mousedown', e => e.stopPropagation());
    box.addEventListener('click', e => {
        const act = e.target && e.target.getAttribute('data-eq-act');
        if (!act) return;
        if (act === 'cancel') closeInlineEquationEditor();
        if (act === 'apply') applyInlineEquationEditor();
    });
    box._updatePreview = update;
    return box;
}

function positionInlineEquationEditor(target) {
    const box = ensureInlineEquationEditor();
    const r = target.getBoundingClientRect();
    box.style.display = 'block';
    box.style.left = '0px';
    box.style.top = '0px';
    const bw = box.offsetWidth || 430;
    const bh = box.offsetHeight || 190;
    let left = r.left + window.scrollX;
    let top = r.bottom + window.scrollY + 6;
    const maxLeft = window.scrollX + document.documentElement.clientWidth - bw - 12;
    if (left > maxLeft) left = Math.max(window.scrollX + 8, maxLeft);
    if (top + bh > window.scrollY + window.innerHeight - 8) {
        top = r.top + window.scrollY - bh - 6;
    }
    if (top < window.scrollY + 8) top = window.scrollY + 8;
    box.style.left = left + 'px';
    box.style.top = top + 'px';
}

function openInlineEquationEditor(target) {
    const latex = target.getAttribute('data-equation-latex') || '';
    const box = ensureInlineEquationEditor();
    activeInlineEquation = target;
    target.classList.add('editing');
    const ta = box.querySelector('textarea');
    ta.value = latex;
    positionInlineEquationEditor(target);
    box._updatePreview();
    setTimeout(() => { ta.focus(); ta.select(); }, 0);
}

function closeInlineEquationEditor() {
    if (activeInlineEquation) activeInlineEquation.classList.remove('editing');
    activeInlineEquation = null;
    if (inlineEquationEditor) inlineEquationEditor.style.display = 'none';
}

function applyInlineEquationEditor() {
    if (!activeInlineEquation || !inlineEquationEditor) return;
    const ta = inlineEquationEditor.querySelector('textarea');
    const latex = String(ta.value || '').trim();
    if (!latex) {
        alert('Rumus tidak boleh kosong.');
        ta.focus();
        return;
    }
    activeInlineEquation.setAttribute('data-equation-latex', latex);
    activeInlineEquation.setAttribute('title', 'Equation Word — ' + latex);
    activeInlineEquation.innerHTML = (typeof renderEquationHtml === 'function') ? renderEquationHtml(latex) : ('\\(' + escapeHtml(latex) + '\\)');
    activeInlineEquation.classList.remove('editing');
    if (window.MathJax && MathJax.typesetPromise) {
        MathJax.typesetPromise([activeInlineEquation]).catch(() => {});
    }
    closeInlineEquationEditor();
    // Sinkronkan isi input dan preview tanpa mengganti fitur parser yang sudah ada.
    setTimeout(() => autoCleanAndParse(), 50);
}

function initInlineEquationEditing() {
    const ed = document.getElementById('inputText');
    if (!ed || ed.dataset.eqEditBound === '1') return;
    ed.dataset.eqEditBound = '1';
    ed.addEventListener('click', function(e) {
        const eq = e.target && e.target.closest ? e.target.closest('.imported-equation') : null;
        if (eq && ed.contains(eq)) {
            e.preventDefault();
            e.stopPropagation();
            openInlineEquationEditor(eq);
        }
    });
    document.addEventListener('click', function(e) {
        if (!inlineEquationEditor || inlineEquationEditor.style.display === 'none') return;
        if (inlineEquationEditor.contains(e.target)) return;
        if (activeInlineEquation && activeInlineEquation.contains(e.target)) return;
        closeInlineEquationEditor();
    });
    window.addEventListener('resize', () => {
        if (activeInlineEquation && inlineEquationEditor && inlineEquationEditor.style.display !== 'none') positionInlineEquationEditor(activeInlineEquation);
    });
}

function typesetImportedEquations(root) {
    if (window.MathJax && MathJax.typesetPromise && root) {
        MathJax.typesetPromise([root]).catch(() => {});
    }
}

function setOriginalWordAvailable(available) {
    const btn = document.getElementById('btnEditOriginalWord');
    if (btn) btn.style.display = available ? 'block' : 'none';
    const name = document.getElementById('wordOriginalName');
    if (name && ORIGINAL_WORD_NAME) name.textContent = ORIGINAL_WORD_NAME;
}

function getWordEditorParagraphInfo(pEl) {
    const W = 'http://schemas.openxmlformats.org/wordprocessingml/2006/main';
    const M = 'http://schemas.openxmlformats.org/officeDocument/2006/math';
    const textNodes = Array.from(pEl.getElementsByTagNameNS(W, 't'));
    const text = textNodes.map(n => n.textContent || '').join('');
    const mathNodes = Array.from(pEl.getElementsByTagNameNS(M, 'oMath'));
    const hasMath = mathNodes.length > 0 || pEl.getElementsByTagNameNS(M, 'oMathPara').length > 0;
    const hasImage = pEl.getElementsByTagNameNS(W, 'drawing').length > 0
        || pEl.getElementsByTagNameNS('urn:schemas-microsoft-com:vml', 'imagedata').length > 0;
    const hasBreak = pEl.getElementsByTagNameNS(W, 'br').length > 0;
    const parentTable = !!pEl.parentNode && !!pEl.parentNode.closest && !!pEl.parentNode.closest('w\\:tbl, tbl');
    return { textNodes, text, mathNodes, hasMath, hasImage, hasBreak, parentTable };
}

// Mengubah OMML Word menjadi notasi LaTeX yang mudah dibaca/diedit.
function ommlToLatex(node) {
    if (!node) return '';
    const M = 'http://schemas.openxmlformats.org/officeDocument/2006/math';
    const W = 'http://schemas.openxmlformats.org/wordprocessingml/2006/main';
    const tag = node.localName || node.nodeName.split(':').pop();
    if (tag === 't') return node.textContent || '';
    if (tag === 'r') return Array.from(node.childNodes).map(ommlToLatex).join('');
    if (tag === 'oMath' || tag === 'oMathPara' || tag === 'e' || tag === 'num' || tag === 'den' || tag === 'sub' || tag === 'sup' || tag === 'deg' || tag === 'base') {
        return Array.from(node.childNodes).map(ommlToLatex).join('');
    }
    if (tag === 'f') {
        const num = node.getElementsByTagNameNS(M, 'num')[0];
        const den = node.getElementsByTagNameNS(M, 'den')[0];
        return `\\frac{${ommlToLatex(num)}}{${ommlToLatex(den)}}`;
    }
    if (tag === 'rad') {
        const deg = node.getElementsByTagNameNS(M, 'deg')[0];
        const body = node.getElementsByTagNameNS(M, 'e')[0];
        const d = deg ? ommlToLatex(deg).trim() : '';
        return d ? `\\sqrt[${d}]{${ommlToLatex(body)}}` : `\\sqrt{${ommlToLatex(body)}}`;
    }
    if (tag === 'sSup') {
        const base = node.getElementsByTagNameNS(M, 'e')[0];
        const sup = node.getElementsByTagNameNS(M, 'sup')[0];
        return `${ommlToLatex(base)}^{${ommlToLatex(sup)}}`;
    }
    if (tag === 'sSub') {
        const base = node.getElementsByTagNameNS(M, 'e')[0];
        const sub = node.getElementsByTagNameNS(M, 'sub')[0];
        return `${ommlToLatex(base)}_{${ommlToLatex(sub)}}`;
    }
    if (tag === 'sSubSup') {
        const base = node.getElementsByTagNameNS(M, 'e')[0];
        const sub = node.getElementsByTagNameNS(M, 'sub')[0];
        const sup = node.getElementsByTagNameNS(M, 'sup')[0];
        return `${ommlToLatex(base)}_{${ommlToLatex(sub)}}^{${ommlToLatex(sup)}}`;
    }
    if (tag === 'd') {
        const beg = node.getElementsByTagNameNS(M, 'dPr')[0];
        const begCh = beg ? beg.getAttribute('begChr') || '(' : '(';
        const endCh = beg ? beg.getAttribute('endChr') || ')' : ')';
        const e = node.getElementsByTagNameNS(M, 'e')[0];
        return `\\left${begCh}${ommlToLatex(e)}\\right${endCh}`;
    }
    if (tag === 'nary') {
        const pr = node.getElementsByTagNameNS(M, 'naryPr')[0];
        const chr = pr ? pr.getAttribute('chr') || '∑' : '∑';
        const sub = node.getElementsByTagNameNS(M, 'sub')[0];
        const sup = node.getElementsByTagNameNS(M, 'sup')[0];
        const e = node.getElementsByTagNameNS(M, 'e')[0];
        return `${chr}${sub ? `_{${ommlToLatex(sub)}}` : ''}${sup ? `^{${ommlToLatex(sup)}}` : ''}${e ? ommlToLatex(e) : ''}`;
    }
    if (tag === 'acc') {
        const pr = node.getElementsByTagNameNS(M, 'accPr')[0];
        const chr = pr ? pr.getAttribute('chr') || '^' : '^';
        const e = node.getElementsByTagNameNS(M, 'e')[0];
        return `\\${chr}{${ommlToLatex(e)}}`;
    }
    return Array.from(node.childNodes).map(ommlToLatex).join('');
}

function ommlText(node) { return ommlToLatex(node).replace(/\\frac\{([^{}]*)\}\{([^{}]*)\}/g, '($1)/($2)'); }

function createMathRun(doc, text) {
    const r = doc.createElementNS('http://schemas.openxmlformats.org/officeDocument/2006/math', 'm:r');
    const t = doc.createElementNS('http://schemas.openxmlformats.org/officeDocument/2006/math', 'm:t');
    t.textContent = text;
    r.appendChild(t);
    return r;
}
function mathArg(doc, content) {
    const e = doc.createElementNS('http://schemas.openxmlformats.org/officeDocument/2006/math', 'm:e');
    e.appendChild(content);
    return e;
}
function stripOuterGroup(s) {
    s = String(s || '').trim();
    if (s.startsWith('{') && s.endsWith('}')) {
        let depth=0, ok=true;
        for(let i=0;i<s.length;i++){ if(s[i]==='{')depth++; else if(s[i]==='}')depth--; if(depth===0 && i<s.length-1){ok=false;break;} }
        if(ok) return s.slice(1,-1);
    }
    return s;
}
function readLatexGroup(s, pos) {
    while(pos<s.length && /\s/.test(s[pos])) pos++;
    if(s[pos]==='{'){
        let start=++pos, depth=1;
        while(pos<s.length && depth){ if(s[pos]==='{')depth++; else if(s[pos]==='}')depth--; pos++; }
        return {value:s.slice(start,pos-1),pos};
    }
    return {value:s[pos]||'',pos:pos+1};
}
function latexAtom(doc, s) {
    s=String(s||'').trim();
    if(!s) return createMathRun(doc,'');
    if(s.startsWith('\\frac')){
        let p=5, a=readLatexGroup(s,p); let b=readLatexGroup(s,a.pos);
        const f=doc.createElementNS('http://schemas.openxmlformats.org/officeDocument/2006/math','m:f');
        const num=doc.createElementNS('http://schemas.openxmlformats.org/officeDocument/2006/math','m:num');
        const den=doc.createElementNS('http://schemas.openxmlformats.org/officeDocument/2006/math','m:den');
        num.appendChild(latexExpression(doc,a.value)); den.appendChild(latexExpression(doc,b.value)); f.appendChild(num); f.appendChild(den); return f;
    }
    if(s.startsWith('\\sqrt')){
        let p=5, deg=null, body;
        if(s[p]==='['){ let q=s.indexOf(']',p); deg=s.slice(p+1,q); p=q+1; }
        const a=readLatexGroup(s,p); body=a.value;
        const r=doc.createElementNS('http://schemas.openxmlformats.org/officeDocument/2006/math','m:rad');
        if(deg!==null){const d=doc.createElementNS('http://schemas.openxmlformats.org/officeDocument/2006/math','m:deg'); d.appendChild(latexExpression(doc,deg)); r.appendChild(d);}
        r.appendChild(mathArg(doc,latexExpression(doc,body))); return r;
    }
    return latexExpression(doc,s);
}
function latexExpression(doc,s){
    s=String(s||'').trim();
    if(!s) return createMathRun(doc,'');
    // Remove simple LaTeX spacing commands.
    s=s.replace(/\\[,;:!]/g,' ');
    // Handle a single base with sub/superscript. This intentionally keeps more complex expressions readable.
    let parts=[]; let i=0;
    while(i<s.length){
        if(/\s/.test(s[i])){i++;continue;}
        if(s[i]==='\\'){
            let m=s.slice(i).match(/^\\([a-zA-Z]+|.)/); if(m){ let cmd=m[1];
                if(cmd==='frac'||cmd==='sqrt'){ let a=latexAtom(doc,s.slice(i)); parts.push(a); // advance approximately by reparsing
                    let rest=s.slice(i); let consumed=cmd==='frac'?5:5; let aa=readLatexGroup(rest,consumed); let bb=readLatexGroup(rest,aa.pos); consumed=bb.pos; parts[parts.length-1]=latexAtom(doc,rest.slice(0,consumed)); i+=consumed; continue; }
                const map={alpha:'α',beta:'β',gamma:'γ',delta:'δ',theta:'θ',pi:'π',mu:'μ',times:'×',cdot:'·',div:'÷',le:'≤',ge:'≥',neq:'≠'}; parts.push(createMathRun(doc,map[cmd]||cmd)); i+=m[0].length; continue;
            }
        }
        let base='';
        if(s[i]==='{'){let a=readLatexGroup(s,i); base=a.value; i=a.pos;} else {base=s[i++];}
        let baseNode=latexExpression(doc,base);
        let sub=null,sup=null;
        while(i<s.length && (s[i]==='_'||s[i]==='^')){
            const type=s[i++]; const a=readLatexGroup(s,i); i=a.pos; if(type==='_') sub=latexExpression(doc,a.value); else sup=latexExpression(doc,a.value);
        }
        if(sub && sup){const n=doc.createElementNS('http://schemas.openxmlformats.org/officeDocument/2006/math','m:sSubSup'); n.appendChild(mathArg(doc,baseNode)); n.appendChild(doc.createElementNS('http://schemas.openxmlformats.org/officeDocument/2006/math','m:sub')).appendChild(sub); n.appendChild(doc.createElementNS('http://schemas.openxmlformats.org/officeDocument/2006/math','m:sup')).appendChild(sup); parts.push(n);}
        else if(sub){const n=doc.createElementNS('http://schemas.openxmlformats.org/officeDocument/2006/math','m:sSub'); n.appendChild(mathArg(doc,baseNode)); n.appendChild(doc.createElementNS('http://schemas.openxmlformats.org/officeDocument/2006/math','m:sub')).appendChild(sub); parts.push(n);}
        else if(sup){const n=doc.createElementNS('http://schemas.openxmlformats.org/officeDocument/2006/math','m:sSup'); n.appendChild(mathArg(doc,baseNode)); n.appendChild(doc.createElementNS('http://schemas.openxmlformats.org/officeDocument/2006/math','m:sup')).appendChild(sup); parts.push(n);}
        else parts.push(baseNode);
    }
    const wrap=doc.createDocumentFragment(); parts.forEach(x=>wrap.appendChild(x)); return wrap;
}
function latexToOmml(doc, latex){
    const M='http://schemas.openxmlformats.org/officeDocument/2006/math';
    const om=doc.createElementNS(M,'m:oMath');
    const frag=latexExpression(doc, stripOuterGroup(latex));
    om.appendChild(frag); return om;
}

function wordEditorTagHtml(item) {
    const tags = [];
    if (item.hasMath) tags.push('<span class="word-editor-tag"><i class="fas fa-square-root-alt mr-1"></i>Rumus</span>');
    if (item.hasImage) tags.push('<span class="word-editor-tag"><i class="fas fa-image mr-1"></i>Gambar</span>');
    if (item.parentTable) tags.push('<span class="word-editor-tag"><i class="fas fa-table mr-1"></i>Tabel</span>');
    if (item.hasBreak) tags.push('<span class="word-editor-tag"><i class="fas fa-level-down-alt mr-1"></i>Line break</span>');
    return tags.join('');
}

async function openOriginalWordEditor() {
    if (!ORIGINAL_WORD_BUFFER) return alert('Silakan upload file .docx terlebih dahulu.');
    if (typeof JSZip === 'undefined') return alert('Library JSZip belum siap.');
    const list = document.getElementById('wordOriginalEditorList');
    if (!list) return;
    list.innerHTML = '<div class="text-center text-muted py-5"><i class="fas fa-spinner fa-spin mr-2"></i>Membaca struktur Word dan Equation...</div>';
    try {
        const zip = await JSZip.loadAsync(ORIGINAL_WORD_BUFFER);
        const docFile = zip.file('word/document.xml');
        if (!docFile) throw new Error('word/document.xml tidak ditemukan.');
        const xmlText = await docFile.async('string');
        const xmlDoc = new DOMParser().parseFromString(xmlText, 'application/xml');
        if (xmlDoc.querySelector('parsererror')) throw new Error('XML Word tidak valid.');
        ORIGINAL_WORD_XML = {zip, xmlDoc};
        const W='http://schemas.openxmlformats.org/wordprocessingml/2006/main';
        const paragraphs=Array.from(xmlDoc.getElementsByTagNameNS(W,'p'));
        ORIGINAL_WORD_ITEMS=paragraphs.map((pEl,index)=>{
            const info=getWordEditorParagraphInfo(pEl);
            return {index,pEl,text:info.text,originalText:info.text,mathNodes:info.mathNodes.map(n=>({node:n,originalLatex:ommlToLatex(n)})),hasMath:info.hasMath,hasImage:info.hasImage,hasBreak:info.hasBreak,parentTable:info.parentTable,editable:info.textNodes.length>0};
        }).filter(item=>item.editable||item.hasMath||item.hasImage);
        renderOriginalWordEditor();
        if(typeof $!=='undefined') $('#wordOriginalEditorModal').modal('show');
    } catch(err){ console.error(err); list.innerHTML='<div class="alert alert-danger">Gagal membaca Word: '+escapeHtml(err.message)+'</div>'; }
}

function renderOriginalWordEditor(filterText='') {
    const list=document.getElementById('wordOriginalEditorList'),count=document.getElementById('wordEditorCount'); if(!list)return;
    const q=String(filterText||'').trim().toLowerCase();
    const visible=ORIGINAL_WORD_ITEMS.filter(item=>!q || item.text.toLowerCase().includes(q) || item.mathNodes.some(m=>m.originalLatex.toLowerCase().includes(q)));
    if(count) count.textContent=`${visible.length} elemen ditampilkan • ${ORIGINAL_WORD_ITEMS.length} elemen terbaca`;
    if(!visible.length){list.innerHTML='<div class="text-center text-muted py-5">Tidak ada elemen yang cocok.</div>';return;}
    list.innerHTML=visible.map(item=>{
        const readonly=item.editable?'':'readonly'; const tag=wordEditorTagHtml(item); const placeholder=item.editable?'Teks paragraf Word...':'[Rumus/gambar]';
        const mathHtml=item.mathNodes.map((m,j)=>`<div class="word-editor-equation" data-eq-wrap="${item.index}-${j}"><div class="word-editor-equation-head"><i class="fas fa-square-root-alt"></i> Equation ${j+1}<span class="word-editor-tag">OMML Word</span></div><div class="word-editor-equation-preview">\\(${escapeHtml(m.originalLatex)}\\)</div><textarea class="word-editor-equation-input" data-word-eq-index="${item.index}" data-eq-pos="${j}" spellcheck="false">${escapeHtml(m.originalLatex)}</textarea><small class="word-editor-info">Edit dengan LaTeX, contoh: <code>\\frac{1}{3}</code>, <code>x^2</code>, <code>x_1</code>, <code>\\sqrt{x}</code>.</small></div>`).join('');
        return `<div class="word-editor-item" data-word-index="${item.index}"><div class="word-editor-meta"><span class="word-editor-no">Paragraf ${item.index+1}</span>${tag}${!item.editable?'<span class="word-editor-tag">Teks tidak ada</span>':''}</div><textarea class="word-editor-text" data-word-editor-index="${item.index}" ${readonly} placeholder="${placeholder}">${escapeHtml(item.text)}</textarea>${mathHtml}</div>`;
    }).join('');
    if(window.MathJax && MathJax.typesetPromise) MathJax.typesetPromise([list]).then(()=>bindWordEquationLivePreview()).catch(()=>bindWordEquationLivePreview()); else bindWordEquationLivePreview();
}
function filterOriginalWordEditor(value){renderOriginalWordEditor(value);}

// Live preview Equation: setiap perubahan LaTeX langsung dirender kembali.
function refreshWordEquationPreview(idx,pos,latex){
    const preview=document.querySelector(`#wordOriginalEditorList [data-eq-preview="${idx}-${pos}"]`);
    if(!preview)return;
    preview.innerHTML='\\('+escapeHtml(String(latex||''))+'\\)';
    if(window.MathJax && MathJax.typesetPromise) MathJax.typesetPromise([preview]).catch(()=>{});
}
function changeWordEquationSize(idx,pos,delta){
    const preview=document.querySelector(`#wordOriginalEditorList [data-eq-preview="${idx}-${pos}"]`);
    const label=document.querySelector(`#wordOriginalEditorList [data-eq-size="${idx}-${pos}"]`);
    if(!preview)return;
    const cur=Number(preview.dataset.eqScale||145);
    const next=Math.max(100,Math.min(240,cur+(Number(delta)||0)*10));
    preview.dataset.eqScale=next;
    preview.style.fontSize=(30*next/145)+'px';
    preview.style.setProperty('--eq-scale',next+'%');
    const mj=preview.querySelector('mjx-container'); if(mj)mj.style.fontSize=next+'%';
    const svg=preview.querySelector('svg'); if(svg)svg.style.height=(1.9*next/145)+'em';
    if(label)label.textContent=next+'%';
}

function bindWordEquationLivePreview(){
    document.querySelectorAll('#wordOriginalEditorList textarea.word-editor-equation-input').forEach(ta=>{
        if(ta.dataset.bound==='1')return;
        ta.dataset.bound='1';
        ta.addEventListener('input',()=>refreshWordEquationPreview(Number(ta.dataset.wordEqIndex),Number(ta.dataset.eqPos),ta.value));
    });
}

function applyOriginalWordEditorChanges(){
    const W='http://schemas.openxmlformats.org/wordprocessingml/2006/main';
    document.querySelectorAll('#wordOriginalEditorList textarea[data-word-editor-index]').forEach(ta=>{
        const idx=Number(ta.getAttribute('data-word-editor-index')); const item=ORIGINAL_WORD_ITEMS.find(x=>x.index===idx); if(!item||!item.editable)return;
        const newText=ta.value, oldNodes=Array.from(item.pEl.getElementsByTagNameNS(W,'t')); if(!oldNodes.length)return;
        const first=oldNodes[0],run=first.parentNode; oldNodes.forEach((node,n)=>{if(n>0)node.textContent='';});
        const brs=Array.from(item.pEl.getElementsByTagNameNS(W,'br')); const lines=String(newText).split(/\r?\n/); first.textContent=lines[0]||'';
        if(/^\s|\s$/u.test(first.textContent))first.setAttributeNS('http://www.w3.org/XML/1998/namespace','space','preserve');else first.removeAttributeNS('http://www.w3.org/XML/1998/namespace','space');
        brs.forEach(br=>{if(br.parentNode===run)br.parentNode.removeChild(br);});
        for(let i=1;i<lines.length;i++){const br=item.pEl.ownerDocument.createElementNS(W,'w:br');const t=item.pEl.ownerDocument.createElementNS(W,'w:t');t.textContent=lines[i];if(/^\s|\s$/u.test(t.textContent))t.setAttributeNS('http://www.w3.org/XML/1998/namespace','space','preserve');run.appendChild(br);run.appendChild(t);}
    });
    document.querySelectorAll('#wordOriginalEditorList textarea[data-word-eq-index]').forEach(ta=>{
        const idx=Number(ta.getAttribute('data-word-eq-index')),pos=Number(ta.getAttribute('data-eq-pos')); const item=ORIGINAL_WORD_ITEMS.find(x=>x.index===idx); if(!item||!item.mathNodes[pos])return;
        const value=String(ta.value||'').trim(), original=item.mathNodes[pos].originalLatex.trim(); if(value===original)return;
        if(!value)return;
        try{const replacement=latexToOmml(item.pEl.ownerDocument,value); const old=item.mathNodes[pos].node; if(old.parentNode)old.parentNode.replaceChild(replacement,old); item.mathNodes[pos].node=replacement; item.mathNodes[pos].originalLatex=value;}catch(e){console.error('Equation conversion gagal',e);throw new Error(`Equation paragraf ${idx+1} tidak valid: ${e.message}`);}
    });
}

async function saveOriginalWordEdits(){
    if(!ORIGINAL_WORD_XML||!ORIGINAL_WORD_XML.zip)return alert('Dokumen Word belum dimuat ke editor.');
    const btn=document.querySelector('#wordOriginalEditorModal .btn-teal'),oldHtml=btn?btn.innerHTML:'';if(btn){btn.disabled=true;btn.innerHTML='<i class="fas fa-spinner fa-spin mr-1"></i>Menyimpan...';}
    try{
        applyOriginalWordEditorChanges();
        const xmlOut=new XMLSerializer().serializeToString(ORIGINAL_WORD_XML.xmlDoc); ORIGINAL_WORD_XML.zip.file('word/document.xml',xmlOut);
        const blob=await ORIGINAL_WORD_XML.zip.generateAsync({type:'blob',compression:'DEFLATE',compressionOptions:{level:6}});
        let baseName=(ORIGINAL_WORD_NAME||'Soal.docx').replace(/\.docx$/i,'').trim()||'Soal'; baseName=baseName.replace(/[\\/:*?"<>|]+/g,'_'); saveAs(blob,baseName+'_edited.docx');
        ORIGINAL_WORD_BUFFER=await blob.arrayBuffer(); ORIGINAL_WORD_NAME=baseName+'_edited.docx'; setOriginalWordAvailable(true);
        alert('Berhasil. Teks dan Equation Word dapat diedit. File asli tidak ditimpa.'); if(typeof $!=='undefined')$('#wordOriginalEditorModal').modal('hide');
    }catch(err){console.error(err);alert('Gagal menyimpan Word: '+err.message);}finally{if(btn){btn.disabled=false;btn.innerHTML=oldHtml||'<i class="fas fa-save mr-1"></i>Simpan & Download Word';}}
}


/* ========================================================================
   OFFLINE WORD ENGINE
   ------------------------------------------------------------------------
   CDN libraries remain the preferred path. When the machine is offline and
   Mammoth/JSZip are unavailable, Word (.docx) import uses the browser's
   built-in DOMParser + DecompressionStream instead. This keeps the existing
   parser/AI/preview/editor pipeline intact and specifically preserves OMML
   equations, tables, and embedded images.
   ======================================================================== */
class OfflineZipReader {
    constructor(buffer){ this.bytes = new Uint8Array(buffer); this.files = new Map(); this.ready = this._index(); }
    u16(o){ return this.bytes[o] | (this.bytes[o+1] << 8); }
    u32(o){ return (this.bytes[o] | (this.bytes[o+1] << 8) | (this.bytes[o+2] << 16) | (this.bytes[o+3] << 24)) >>> 0; }
    async _index(){
        const b=this.bytes;
        let e=-1;
        const start=Math.max(0,b.length-65557);
        for(let i=b.length-22;i>=start;i--){ if(this.u32(i)===0x06054b50){ e=i; break; } }
        if(e<0) throw new Error('Berkas DOCX bukan ZIP yang valid.');
        const count=this.u16(e+10), cdOffset=this.u32(e+16);
        let p=cdOffset;
        for(let n=0;n<count;n++){
            if(this.u32(p)!==0x02014b50) break;
            const method=this.u16(p+10), compSize=this.u32(p+20), uncompSize=this.u32(p+24);
            const nameLen=this.u16(p+28), extraLen=this.u16(p+30), commentLen=this.u16(p+32), localOffset=this.u32(p+42);
            const nameBytes=b.slice(p+46,p+46+nameLen);
            const name=new TextDecoder('utf-8').decode(nameBytes);
            this.files.set(name,{method,compSize,uncompSize,localOffset});
            p += 46+nameLen+extraLen+commentLen;
        }
    }
    async getBytes(name){
        await this.ready;
        const f=this.files.get(name); if(!f) return null;
        const b=this.bytes, o=f.localOffset;
        if(this.u32(o)!==0x04034b50) throw new Error('ZIP local header rusak: '+name);
        const nameLen=this.u16(o+26), extraLen=this.u16(o+28);
        const data=b.slice(o+30+nameLen+extraLen,o+30+nameLen+extraLen+f.compSize);
        if(f.method===0) return data;
        if(f.method!==8) throw new Error('Metode kompresi ZIP tidak didukung: '+f.method);
        if(typeof DecompressionStream==='undefined') throw new Error('Browser tidak mendukung DecompressionStream untuk mode offline.');
        const stream=new Blob([data]).stream().pipeThrough(new DecompressionStream('deflate-raw'));
        return new Uint8Array(await new Response(stream).arrayBuffer());
    }
    async getText(name){ const b=await this.getBytes(name); return b?new TextDecoder('utf-8').decode(b):null; }
}

function offlineMime(path){
    const e=String(path||'').split('.').pop().toLowerCase();
    return ({png:'image/png',jpg:'image/jpeg',jpeg:'image/jpeg',gif:'image/gif',bmp:'image/bmp',svg:'image/svg+xml',webp:'image/webp'})[e]||'application/octet-stream';
}
function offlineDataUri(bytes,mime){
    let bin=''; const step=0x8000;
    for(let i=0;i<bytes.length;i+=step) bin+=String.fromCharCode(...bytes.subarray(i,i+step));
    return 'data:'+mime+';base64,'+btoa(bin);
}
function offlineEsc(s){ return escapeHtml(String(s??'')); }
function offlineNodeText(node){
    const W='http://schemas.openxmlformats.org/wordprocessingml/2006/main';
    let out='';
    function walk(n){
        if(n.nodeType!==1) return;
        const tag=n.localName;
        if(tag==='t') out += n.textContent || '';
        else if(tag==='tab') out += '\\t';
        else if(tag==='br'||tag==='cr') out += '\\n';
        else Array.from(n.childNodes).forEach(walk);
    }
    walk(node); return out;
}
async function offlineDocxToHtml(arrayBuffer){
    const zip=new OfflineZipReader(arrayBuffer); await zip.ready;
    const xml=await zip.getText('word/document.xml');
    if(!xml) throw new Error('word/document.xml tidak ditemukan.');
    const relsXml=await zip.getText('word/_rels/document.xml.rels') || '';
    const parser=new DOMParser();
    const doc=parser.parseFromString(xml,'application/xml');
    if(doc.getElementsByTagName('parsererror').length) throw new Error('XML Word tidak valid.');
    const rels=parser.parseFromString(relsXml,'application/xml');
    const relMap={};
    Array.from(rels.getElementsByTagName('*')).forEach(el=>{
        if(el.localName==='Relationship') relMap[el.getAttribute('Id')]=el.getAttribute('Target')||'';
    });
    const W='http://schemas.openxmlformats.org/wordprocessingml/2006/main';
    const M='http://schemas.openxmlformats.org/officeDocument/2006/math';
    const A='http://schemas.openxmlformats.org/drawingml/2006/main';
    const out=[];
    const listCounters={};
    const body=doc.getElementsByTagNameNS(W,'body')[0];
    if(!body) throw new Error('Body Word tidak ditemukan.');

    async function renderInline(parent){
        let html='';
        for(const n of Array.from(parent.childNodes)){
            if(n.nodeType!==1) continue;
            const tag=n.localName;
            if(tag==='r'){
                html += offlineEsc(offlineNodeText(n));
            } else if(tag==='oMath' || tag==='oMathPara'){
                const latex=ommlToLatex(n).trim();
                if(latex){
                    const safe=offlineEsc(latex);
                    html += `<span class="imported-equation" data-equation-latex="${safe}" contenteditable="false" title="Equation Word — ${safe}">${renderEquationHtml(latex)}</span>`;
                }
            } else if(tag==='hyperlink'){
                html += await renderInline(n);
            } else if(tag==='bookmarkStart' || tag==='bookmarkEnd' || tag==='proofErr' || tag==='permStart' || tag==='permEnd'){
                continue;
            } else if(tag==='drawing'){
                const blips=Array.from(n.getElementsByTagNameNS(A,'blip'));
                const rid=blips[0] && (blips[0].getAttribute('r:embed') || blips[0].getAttributeNS('http://schemas.openxmlformats.org/officeDocument/2006/relationships','embed'));
                const target=rid?relMap[rid]:'';
                if(target){
                    const clean=target.replace(/^\.\//,'');
                    const path=clean.startsWith('media/')?'word/'+clean:('word/'+clean.replace(/^word\//,''));
                    const bytes=await zip.getBytes(path);
                    if(bytes){
                        const src=offlineDataUri(bytes,offlineMime(path));
                        const tok=makeImageToken(src); EDITOR_IMAGES[tok]=src;
                        html += `<img src="${src}" data-editor-image-token="${tok}" style="max-width:220px;display:block;margin:5px 0;">`;
                    }
                }
            }
        }
        return html;
    }
    async function renderParagraph(p){
        const html=await renderInline(p);
        const pPr=p.getElementsByTagNameNS(W,'pPr')[0];
        let cls='';
        let label='';
        if(pPr){
            const jc=pPr.getElementsByTagNameNS(W,'jc')[0];
            if(jc && jc.getAttributeNS(W,'val')) cls+=' text-'+jc.getAttributeNS(W,'val');
            const numPr=pPr.getElementsByTagNameNS(W,'numPr')[0];
            if(numPr){
                const numIdEl=numPr.getElementsByTagNameNS(W,'numId')[0];
                const ilvlEl=numPr.getElementsByTagNameNS(W,'ilvl')[0];
                const numId=numIdEl ? (numIdEl.getAttributeNS(W,'val')||numIdEl.getAttribute('w:val')) : '0';
                const ilvl=ilvlEl ? parseInt(ilvlEl.getAttributeNS(W,'val')||ilvlEl.getAttribute('w:val')||'0',10) : 0;
                if(!listCounters[numId]) listCounters[numId]=[];
                const arr=listCounters[numId];
                arr[ilvl]=(arr[ilvl]||0)+1;
                for(let k=ilvl+1;k<arr.length;k++) arr[k]=0;
                if(ilvl===0) label=arr[0]+'. ';
                else if(ilvl===1) label=String.fromCharCode(64+Math.max(1,arr[1]))+'. ';
                else label=(arr[ilvl]||1)+'. ';
            }
        }
        return `<p${cls?' class="'+cls.trim()+'"':''}>${label?offlineEsc(label):''}${html || '&nbsp;'}</p>`;
    }
    async function renderTable(tbl){
        const rows=Array.from(tbl.getElementsByTagNameNS(W,'tr'));
        let h='<table class="table table-bordered table-sm"><tbody>';
        for(const tr of rows){
            h+='<tr>';
            const cells=Array.from(tr.childNodes).filter(n=>n.nodeType===1 && n.localName==='tc');
            for(const tc of cells){
                let cell='';
                for(const ch of Array.from(tc.childNodes)) if(ch.nodeType===1 && ch.localName==='p') cell+=await renderParagraph(ch);
                h+='<td>'+cell+'</td>';
            }
            h+='</tr>';
        }
        return h+'</tbody></table>';
    }
    for(const child of Array.from(body.childNodes)){
        if(child.nodeType!==1) continue;
        if(child.localName==='p') out.push(await renderParagraph(child));
        else if(child.localName==='tbl') out.push(await renderTable(child));
    }
    return out.join('');
}

function renderEquationHtml(latex){
    const raw=String(latex||'').trim();
    if(!raw) return '';
    // Tampilan dasar memakai renderer HTML lokal agar rumus tetap terlihat
    // meskipun MathJax belum siap atau CDN tidak tersedia.
    if(typeof mathFormatText==='function') return '<span class="eq-math-source">'+mathFormatText(escapeHtml(raw))+'</span>';
    return escapeHtml(raw);
}

// ===== IMPOR PDF BERGAMBAR =====
// Gambar PDF dirender langsung dari halaman lalu dipotong sesuai posisinya (bukan memakai data
// mentah objek gambar), sehingga JPEG/PNG/mask/transparansi tampil persis seperti di PDF.
// Gambar ditaruh sebagai baris sendiri di posisi aslinya (di antara baris teks), bukan ditempel
// ke baris teks terdekat, supaya tidak masuk ke opsi jawaban yang salah.
const PDF_RENDER_SCALE = 3;           // 3x = sekitar 216 dpi, cukup tajam untuk soal
const PDF_IMG_MIN_SIZE = 24;          // abaikan ornamen/garis kecil (satuan poin PDF)
const PDF_IMG_MAX_PAGE_RATIO = 0.85;  // abaikan gambar latar seukuran halaman penuh

function pdfMulMatrix(a, b) {
    return [a[0]*b[0]+a[2]*b[1], a[1]*b[0]+a[3]*b[1], a[0]*b[2]+a[2]*b[3], a[1]*b[2]+a[3]*b[3], a[0]*b[4]+a[2]*b[5]+a[4], a[1]*b[4]+a[3]*b[5]+a[5]];
}

// Kumpulkan kotak (bounding box) semua gambar pada halaman, dalam koordinat viewport (origin kiri-atas).
async function pdfCollectImageBoxes(page, viewport) {
    const boxes = [];
    let opList;
    try { opList = await page.getOperatorList(); } catch (e) { return boxes; }
    const OPS = pdfjsLib.OPS || {};
    const imgOps = [OPS.paintImageXObject, OPS.paintJpegXObject, OPS.paintInlineImageXObject, OPS.paintImageMaskXObject]
        .filter(v => v !== undefined);
    let matrix = [1, 0, 0, 1, 0, 0];
    const stack = [];
    for (let i = 0; i < opList.fnArray.length; i++) {
        const fn = opList.fnArray[i], args = opList.argsArray[i] || [];
        if (fn === OPS.save) stack.push(matrix.slice());
        else if (fn === OPS.restore) { if (stack.length) matrix = stack.pop(); }
        else if (fn === OPS.transform && args.length >= 6) matrix = pdfMulMatrix(matrix, args.slice(0, 6));
        else if (fn === OPS.paintFormXObjectBegin) {
            stack.push(matrix.slice());
            if (args[0] && args[0].length >= 6) matrix = pdfMulMatrix(matrix, args[0].slice(0, 6));
        } else if (fn === OPS.paintFormXObjectEnd) { if (stack.length) matrix = stack.pop(); }
        else if (imgOps.includes(fn)) {
            const xs = [], ys = [];
            [[0, 0], [1, 0], [0, 1], [1, 1]].forEach(([u, v]) => {
                const px = matrix[0]*u + matrix[2]*v + matrix[4];
                const py = matrix[1]*u + matrix[3]*v + matrix[5];
                const vp = viewport.convertToViewportPoint(px, py);
                xs.push(vp[0]); ys.push(vp[1]);
            });
            boxes.push({ left: Math.min(...xs), right: Math.max(...xs), top: Math.min(...ys), bottom: Math.max(...ys) });
        }
    }
    // Buang ornamen kecil dan gambar latar seukuran halaman.
    const pageArea = viewport.width * viewport.height;
    let list = boxes.filter(b => {
        const w = b.right - b.left, h = b.bottom - b.top;
        return w >= PDF_IMG_MIN_SIZE && h >= PDF_IMG_MIN_SIZE && (w * h) < pageArea * PDF_IMG_MAX_PAGE_RATIO;
    });
    return list;
}

// Satukan potongan gambar yang menempel/bertumpuk (satu gambar sering disimpan berkeping-keping).
function pdfMergeBoxes(list) {
    list = list.slice();
    let merged = true;
    while (merged) {
        merged = false;
        outer: for (let i = 0; i < list.length; i++) {
            for (let j = i + 1; j < list.length; j++) {
                const a = list[i], b = list[j], gap = 3;
                if (a.left <= b.right + gap && b.left <= a.right + gap && a.top <= b.bottom + gap && b.top <= a.bottom + gap) {
                    list[i] = { left: Math.min(a.left, b.left), right: Math.max(a.right, b.right), top: Math.min(a.top, b.top), bottom: Math.max(a.bottom, b.bottom) };
                    list.splice(j, 1);
                    merged = true;
                    break outer;
                }
            }
        }
    }
    return list;
}

// Render halaman sekali, lalu potong tiap kotak gambar menjadi PNG.
async function pdfCropImages(page, viewport, boxes) {
    if (!boxes.length) return [];
    const scale = Math.max(1, Math.min(PDF_RENDER_SCALE, 4096 / Math.max(viewport.width, viewport.height)));
    const rv = page.getViewport({ scale });
    const canvas = document.createElement('canvas');
    canvas.width = Math.ceil(rv.width); canvas.height = Math.ceil(rv.height);
    const ctx = canvas.getContext('2d');
    ctx.fillStyle = '#fff'; ctx.fillRect(0, 0, canvas.width, canvas.height);
    try {
        await page.render({ canvasContext: ctx, canvas, viewport: rv }).promise;
    } catch (e) {
        console.warn('Render halaman PDF gagal, gambar dilewati:', e);
        canvas.width = 0;
        return [];
    }
    const out = [];
    for (const b of boxes) {
        const pad = -0.5; // sedikit ke dalam agar tidak ikut memotong teks yang menempel di tepi gambar
        const x = Math.max(0, Math.floor((b.left - pad) * scale));
        const y = Math.max(0, Math.floor((b.top - pad) * scale));
        const w = Math.min(canvas.width - x, Math.ceil((b.right - b.left + pad * 2) * scale));
        const h = Math.min(canvas.height - y, Math.ceil((b.bottom - b.top + pad * 2) * scale));
        if (w < 4 || h < 4) continue;
        const c = document.createElement('canvas'); c.width = w; c.height = h;
        c.getContext('2d').drawImage(canvas, x, y, w, h, 0, 0, w, h);
        let dataUrl = '';
        try { dataUrl = c.toDataURL('image/png'); } catch (e) { continue; }
        c.width = 0;
        out.push({ id: b.id, attached: !!b.attached, top: b.top, bottom: b.bottom, left: b.left, right: b.right, src: dataUrl });
    }
    canvas.width = 0;
    return out;
}

// Susun baris teks + gambar menurut urutan baca. Gambar menjadi baris sendiri.
function pdfInterleaveLines(lines, images) {
    const OPT = /^[a-eA-E][.)]\s+\S/;
    const result = lines.map(l => ({ type: 'text', text: l.text, y: l.y }));
    images.slice().sort((a, b) => a.top - b.top || a.left - b.left).forEach(im => {
        // Sisipkan setelah baris terakhir yang berada di atas tepi atas gambar.
        let at = -1;
        for (let i = 0; i < result.length; i++) {
            if (result[i].type === 'text' && result[i].y <= im.top + 4) at = i;
        }
        // Jangan memotong daftar opsi: gambar yang sejajar opsi diletakkan sebelum opsi pertama.
        if (at >= 0 && at + 1 < result.length && result[at + 1].type === 'text' &&
            OPT.test(result[at].text.trim()) && OPT.test(result[at + 1].text.trim())) {
            while (at >= 0 && result[at].type === 'text' && OPT.test(result[at].text.trim())) at--;
        }
        result.splice(at + 1, 0, { type: 'image', im });
    });
    return result;
}

// Nomor soal yang terpotong (mis. halaman PDF dimulai dari kalimat soal tanpa nomor) membuat
// soal itu tertelan ke opsi soal sebelumnya. Jika setelah satu set opsi lengkap muncul teks tanpa
// nomor yang disusul opsi "a." baru, teks itu diberi nomor otomatis sebagai soal baru.
function pdfRepairMissingNumbers(items, st) {
    const NUM = /^\s*(\d{1,3})[.)]\s*\S/;
    const OPT = /^[a-eA-E][.)]\s+\S/;
    const OPT_A = /^[aA][.)]\s+\S/;
    const texts = items.filter(x => x.type === 'text');
    for (let i = 0; i < texts.length; i++) {
        const it = texts[i], t = it.text.trim();
        const m = t.match(NUM);
        if (m) { st.lastNum = parseInt(m[1], 10); st.opts = 0; st.prevOpt = false; continue; }
        if (OPT.test(t)) {
            st.opts += (t.match(/(?:^|\s)[a-eA-E][.)]\s+/g) || []).length;
            st.prevOpt = true;
            continue;
        }
        if (st.prevOpt && st.opts >= 3 && st.lastNum !== null) {
            let restart = false;
            for (let k = i, n = 0; k < texts.length && n < 8; k++, n++) {
                const tx = texts[k].text.trim();
                if (k > i && NUM.test(tx)) break;
                if (OPT_A.test(tx)) { restart = true; break; }
            }
            if (restart) {
                st.lastNum += 1;
                it.text = st.lastNum + '. ' + it.text;
                st.opts = 0;
            }
        }
        st.prevOpt = false;
    }
}

function pdfBuildPageHtml(items, esc, allImages) {
    const pxPerPt = 96 / 72;
    const imgTag = (im, maxW, extra) => {
        let w = Math.round((im.right - im.left) * pxPerPt);
        let h = Math.round((im.bottom - im.top) * pxPerPt);
        if (w > maxW) { h = Math.round(h * maxW / w); w = maxW; }
        return '<img src="' + im.src + '" alt="Gambar soal" data-w="' + w + '" data-h="' + h + '" style="width:' + w + 'px;max-width:100%;height:auto;' + (extra || '') + '">';
    };
    const html = items.map(it => {
        if (it.type === 'image') {
            return '<div class="imported-pdf-figure" style="margin:6px 0">' + imgTag(it.im, 520) + '</div>';
        }
        // Gambar opsi (a. [gambar]) disisipkan sebaris dengan hurufnya.
        const safe = esc(it.text).replace(/\uE000(\d+)\uE001/g, (m, n) => {
            const im = allImages.find(x => x.id === +n);
            return im ? imgTag(im, 300, 'vertical-align:middle;margin:2px 0') : '';
        });
        return '<div>' + safe + '</div>';
    }).join('');
    return '<div class="imported-pdf-text" style="margin-bottom:18px">' + html + '</div>';
}

async function extractPdfToHtml(file, esc, progress) {
    const data = new Uint8Array(await file.arrayBuffer());
    const pdf = await pdfjsLib.getDocument({ data }).promise;
    const pages = [];
    for (let pageNo = 1; pageNo <= pdf.numPages; pageNo++) {
        if (progress) progress(`Mengekstrak teks dan gambar ${file.name} • halaman ${pageNo}/${pdf.numPages}`);
        const page = await pdf.getPage(pageNo);
        const content = await page.getTextContent();
        const viewport = page.getViewport({ scale: 1 });

        // Kelompokkan teks per baris (koordinat viewport: y makin besar = makin ke bawah).
        const rows = [];
        for (const item of content.items) {
            if (!item.str || !item.str.trim() || !item.transform) continue;
            const vp = viewport.convertToViewportPoint(item.transform[4], item.transform[5]);
            const fs = Math.abs(item.height) || Math.hypot(item.transform[2], item.transform[3]) || 10;
            let row = rows.find(r => Math.abs(r.y - vp[1]) < 4);
            if (!row) { row = { y: vp[1], items: [] }; rows.push(row); }
            row.items.push({ x: vp[0], w: item.width || 0, fs, s: item.str });
        }
        // Gabungkan potongan teks yang bersambung rapat (PDF dari Word sering memecah per huruf/kata),
        // sisanya dipisah jadi segmen. Segmen dipakai untuk mengenali huruf opsi "a." "b." dst.
        rows.forEach(r => {
            r.items.sort((a, b) => a.x - b.x);
            const segs = [];
            r.items.forEach(it => {
                const last = segs[segs.length - 1];
                if (last && (it.x - last.end) <= 0.12 * it.fs && !/\s$/.test(last.s) && !/^\s/.test(it.s)) {
                    last.s += it.s; last.end = it.x + it.w;
                } else {
                    segs.push({ x: it.x, end: it.x + it.w, s: it.s });
                }
            });
            segs.forEach(sg => { sg.s = sg.s.replace(/\s+/g, ' ').trim(); });
            r.segs = segs.filter(sg => sg.s);
        });

        let rawBoxes = [];
        try { rawBoxes = await pdfCollectImageBoxes(page, viewport); } catch (e) { console.warn('Ekstraksi gambar PDF gagal:', e); }

        // Gambar pilihan jawaban: huruf "a." / "b." berada tepat di kiri-atas gambar. Pasangkan agar
        // gambar menjadi isi opsi itu ("a. [gambar]"), termasuk tata letak 2 kolom (a. c. / b. d.).
        // Pemasangan dilakukan SEBELUM penggabungan, supaya kartu opsi yang saling menempel tidak menyatu.
        const LABEL = /^[a-eA-E][.)]$/;
        const cands = [];
        rows.forEach(r => r.segs.forEach(sg => { if (LABEL.test(sg.s)) cands.push({ r, sg }); }));
        rawBoxes.forEach((bx, idx) => {
            bx.id = idx + 1;
            let best = null, bestD = Infinity;
            cands.forEach(c => {
                if (c.sg.marker) return;
                const dx = bx.left - c.sg.x, dy = c.r.y - bx.top;
                if (dx < -2 || dx > 90 || dy < -3 || dy > 45) return;
                const d = dx + Math.abs(dy) * 2;
                if (d < bestD) { bestD = d; best = c; }
            });
            if (best) { best.sg.marker = '\uE000' + bx.id + '\uE001'; bx.attached = true; }
        });
        const keepBoxes = rawBoxes.filter(b => b.attached);
        const freeMerged = pdfMergeBoxes(rawBoxes.filter(b => !b.attached));
        freeMerged.forEach((b, i) => { b.id = 1000 + i; });
        let images = [];
        try { images = await pdfCropImages(page, viewport, keepBoxes.concat(freeMerged)); }
        catch (e) { console.warn('Ekstraksi gambar PDF gagal:', e); }
        const lines = rows
            .map(r => ({ y: r.y, text: r.segs.map(sg => sg.s + (sg.marker ? ' ' + sg.marker : '')).join(' ') }))
            .sort((a, b) => a.y - b.y);
        pages.push({ lines, images });
        if (page.cleanup) { try { page.cleanup(); } catch (_) {} }
    }

    // Gambar yang sama berulang di >=3 halaman = kop/logo/watermark, bukan gambar soal.
    if (pages.length >= 3) {
        const count = new Map();
        pages.forEach(p => new Set(p.images.map(i => i.src)).forEach(s => count.set(s, (count.get(s) || 0) + 1)));
        pages.forEach(p => { p.images = p.images.filter(i => count.get(i.src) < 3); });
    }

    const htmlPages = [];
    const numState = { lastNum: null, opts: 0, prevOpt: false };
    for (const p of pages) {
        const items = pdfInterleaveLines(p.lines, p.images.filter(i => !i.attached));
        pdfRepairMissingNumbers(items, numState);
        if (items.length) htmlPages.push(pdfBuildPageHtml(items, esc, p.images));
    }
    if (!htmlPages.length) throw new Error('PDF tidak memiliki teks yang bisa diekstrak. PDF hasil scan membutuhkan OCR.');
    return htmlPages.join('<hr class="my-3">');
}

async function handleFileUpload(input) {
    const files = Array.from(input && input.files ? input.files : []);
    if (!files.length) return;
    const container = document.getElementById('inputText');
    if (!container) { alert('Area Input Bank Soal Mentah tidak ditemukan.'); input.value = ''; return; }

    const multi = files.length > 1;
    const importedSections = [];
    showProgressState(`Menyiapkan ${files.length} file untuk diimpor...`);
    isAiProcessed = false;

    // Multi-file import is sequential to avoid browser memory spikes on large DOCX/PDF files.
    async function extractOne(file, index) {
        const ext = (file.name.split('.').pop() || '').toLowerCase();
        showProgressState(`Mengimpor ${index + 1}/${files.length}: ${file.name}`);
        if (ext === 'docx') {
            const buffer = await file.arrayBuffer();
            let html = '';
            if (typeof mammoth === 'undefined') {
                html = await offlineDocxToHtml(buffer);
            } else {
                let docxBuffer = await normalizeDocxQuestionLayout(buffer);
                docxBuffer = await injectDocxEquationPlaceholders(docxBuffer);
                const result = await mammoth.convertToHtml({ arrayBuffer: docxBuffer });
                html = result && result.value ? result.value : '';
            }
            if (!html.trim()) throw new Error('Tidak ada teks yang dapat dibaca.');
            const holder = document.createElement('div');
            holder.innerHTML = html.trim();
            if (typeof normalizeImportedDocxLists === 'function') normalizeImportedDocxLists(holder);
            if (typeof normalizeTwoColumnOptionRows === 'function') normalizeTwoColumnOptionRows(holder);
            if (typeof normalizeImportedOptionTables === 'function') normalizeImportedOptionTables(holder);
            if (typeof replaceImportedEquationTokens === 'function') holder.innerHTML = replaceImportedEquationTokens(holder.innerHTML);
            // Simpan file Word asli untuk fitur edit/download Word pada impor satu file.
            if (!multi) {
                ORIGINAL_WORD_BUFFER = buffer.slice(0);
                ORIGINAL_WORD_NAME = file.name || 'Soal.docx';
                setOriginalWordAvailable(true);
            }
            return holder.innerHTML;
        }
        if (ext === 'pdf') {
            if (typeof pdfjsLib === 'undefined') throw new Error('Library PDF.js belum siap. Muat ulang halaman lalu coba lagi.');
            const pdfEscape = v => (typeof escapeHtml === 'function' ? escapeHtml(String(v || '')) : String(v || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'));
            return await extractPdfToHtml(file, pdfEscape, showProgressState);
        }
        throw new Error('Format .' + ext + ' belum didukung. Gunakan file .docx atau .pdf.');
    }

    let succeeded = 0;
    const errors = [];
    for (let i = 0; i < files.length; i++) {
        try {
            const html = await extractOne(files[i], i);
            importedSections.push(`<section class="imported-file-section" data-imported-file="1">${html}</section>`);
            succeeded++;
        } catch (err) {
            console.error('Import gagal:', files[i].name, err);
            errors.push(files[i].name + ': ' + (err && err.message ? err.message : 'gagal dibaca'));
        }
    }

    if (importedSections.length) {
        // Tambahkan hasil baru ke konten yang sudah ada, jangan menimpa soal sebelumnya.
        const existing = (container.innerHTML || '').trim();
        const isPlaceholder = /^(sedang mengekstrak berkas, mohon tunggu\.\.\.|ketik atau tempel soal di sini)$/i.test(container.textContent.trim());
        container.innerHTML = (existing && !isPlaceholder ? existing : '') + importedSections.join('<hr class="my-3">');
        if (typeof initInlineEquationEditing === 'function') initInlineEquationEditing();
        if (typeof typesetImportedEquations === 'function') typesetImportedEquations(container);
        parsedData = [];
        if (typeof parseEditorContent === 'function') parseEditorContent();
        else if (typeof parseLocalInstantly === 'function') parseLocalInstantly(container.innerText || container.textContent || '');
        if (typeof scheduleCacheLookup === 'function') scheduleCacheLookup();
        if (typeof renderPreview === 'function') renderPreview();
    }
    if (errors.length) alert('Sebagian file tidak berhasil diimpor:\n\n' + errors.join('\n'));
    // Jangan panggil showProgressState() setelah renderPreview(): fungsi tersebut mengganti
    // seluruh isi panel preview dengan animasi loading, sehingga preview tampak macet selamanya.
    // Beri notifikasi tanpa mengosongkan/mengganti hasil preview.
    if (succeeded && !errors.length) {
        const preview = document.getElementById('previewRenderArea');
        if (preview) {
            const notice = document.createElement('div');
            notice.className = 'small text-success mb-2 import-success-notice';
            notice.textContent = `${succeeded} file berhasil ditambahkan. Preview sudah diperbarui.`;
            preview.prepend(notice);
            window.setTimeout(() => notice.remove(), 6000);
        }
    }
    if (multi && succeeded) {
        // Satu file Word asli tidak dapat mewakili gabungan beberapa dokumen; jangan tampilkan tombol edit-original yang menyesatkan.
        setOriginalWordAvailable(false);
        ORIGINAL_WORD_BUFFER = null;
        ORIGINAL_WORD_NAME = '';
    }
    input.value = '';
}

function normalizeImportedDocxLists(root) {
    const lists = Array.from(root.querySelectorAll('ol, ul')).filter(list => !list.parentElement.closest('ol, ul'));
    let questionNo = 1;
    // Jika sebelumnya ada nomor soal yang DIKETIK manual (mis. 1. - 20.), daftar bernomor Word
    // setelahnya (mis. soal isian) melanjutkan nomor itu, bukan mulai lagi dari 1.
    const typedParas = Array.from(root.querySelectorAll('p, div')).filter(el => !el.closest('ol, ul') && !el.querySelector('p, div'));
    const lastTypedBefore = list => {
        let last = 0;
        for (const el of typedParas) {
            if (!(el.compareDocumentPosition(list) & Node.DOCUMENT_POSITION_FOLLOWING)) break;
            const m = (el.textContent || '').replace(/\u00A0/g, ' ').trim().match(/^(\d{1,3})[.)]\s+\S/);
            if (m) last = parseInt(m[1], 10);
        }
        return last;
    };

    function makeManualList(list, level) {
        const fragment = document.createDocumentFragment();
        const items = Array.from(list.children).filter(el => el.tagName === 'LI');

        items.forEach((li, index) => {
            const wrapper = document.createElement('div');
            wrapper.className = level === 0 ? 'import-question-line' : 'import-option-line';

            const prefix = level === 0
                ? (questionNo++) + '. '
                : String.fromCharCode(65 + Math.min(index, 25)) + '. ';

            const nestedLists = Array.from(li.children).filter(el => /^(OL|UL)$/i.test(el.tagName));
            const content = document.createElement('span');
            Array.from(li.childNodes).forEach(node => {
                if (node.nodeType === 1 && /^(OL|UL)$/i.test(node.tagName)) return;
                content.appendChild(node.cloneNode(true));
            });
            wrapper.appendChild(document.createTextNode(prefix));
            wrapper.appendChild(content);
            fragment.appendChild(wrapper);

            nestedLists.forEach(nested => {
                fragment.appendChild(makeManualList(nested, level + 1));
            });
        });
        return fragment;
    }

    lists.forEach(list => {
        const lt = lastTypedBefore(list);
        if (lt > 0 && lt + 1 > questionNo) questionNo = lt + 1;
        const fragment = makeManualList(list, 0);
        list.parentNode.replaceChild(fragment, list);
    });
}

// =========================================================================
// FORMAT SOAL WORD "SENI RUPA": nomor soal (1,2,3) dan pilihan (a,b,c,d) berada pada
// daftar numbering Word yang TERPISAH (numId berbeda, sama-sama level 0), dan pilihan
// ditata 2 kolom memakai tab ("a. ... [tab] c. ..." / "b. ... [tab] d. ...").
// Tanpa penanganan ini mammoth menjadikan SEMUA paragraf sebagai satu daftar datar,
// sehingga baris pilihan ikut dinomori sebagai soal. Fungsi ini mengganti numbering
// Word tersebut dengan label manual ("1." / "a.") langsung di document.xml.
// Dokumen berformat lain (pilihan bersarang, tanpa numbering, dll.) TIDAK diubah.
// =========================================================================
async function normalizeDocxQuestionLayout(arrayBuffer) {
    try {
        if (typeof JSZip === 'undefined' || typeof DOMParser === 'undefined') return arrayBuffer;
        const zip = await JSZip.loadAsync(arrayBuffer);
        const docFile = zip.file('word/document.xml');
        const numFile = zip.file('word/numbering.xml');
        if (!docFile || !numFile) return arrayBuffer;

        const W = 'http://schemas.openxmlformats.org/wordprocessingml/2006/main';
        const XMLNS = 'http://www.w3.org/XML/1998/namespace';
        const parser = new DOMParser();
        const numDoc = parser.parseFromString(await numFile.async('string'), 'application/xml');
        const docDoc = parser.parseFromString(await docFile.async('string'), 'application/xml');
        if (numDoc.getElementsByTagName('parsererror').length || docDoc.getElementsByTagName('parsererror').length) return arrayBuffer;

        const wAttr = (el, name) => el ? (el.getAttributeNS(W, name) || el.getAttribute('w:' + name)) : null;
        const child = (el, name) => Array.from(el.childNodes).find(n => n.nodeType === 1 && n.namespaceURI === W && n.localName === name) || null;

        // --- baca definisi numbering: format (decimal / lowerLetter ...) per numId & level ---
        const abstractLv = {};
        Array.from(numDoc.getElementsByTagNameNS(W, 'abstractNum')).forEach(an => {
            const lv = {};
            Array.from(an.getElementsByTagNameNS(W, 'lvl')).forEach(l => {
                const st = parseInt(wAttr(child(l, 'start'), 'val'), 10);
                lv[wAttr(l, 'ilvl')] = { fmt: wAttr(child(l, 'numFmt'), 'val'), start: isNaN(st) ? 1 : st };
            });
            abstractLv[wAttr(an, 'abstractNumId')] = lv;
        });
        const numMap = {};
        Array.from(numDoc.getElementsByTagNameNS(W, 'num')).forEach(n => {
            const over = {};
            Array.from(n.getElementsByTagNameNS(W, 'lvlOverride')).forEach(o => {
                const ov = {};
                const so = child(o, 'startOverride');
                if (so) ov.start = parseInt(wAttr(so, 'val'), 10);
                const lvl = child(o, 'lvl');
                if (lvl) {
                    const f = child(lvl, 'numFmt');
                    if (f) ov.fmt = wAttr(f, 'val');
                    const st = child(lvl, 'start');
                    if (st && ov.start == null) ov.start = parseInt(wAttr(st, 'val'), 10);
                }
                over[wAttr(o, 'ilvl')] = ov;
            });
            numMap[wAttr(n, 'numId')] = { absId: wAttr(child(n, 'abstractNumId'), 'val'), over };
        });
        const levelInfo = (numId, ilvl) => {
            const n = numMap[numId];
            if (!n) return null;
            const base = (abstractLv[n.absId] || {})[ilvl] || {};
            const ov = n.over[ilvl] || {};
            const start = (ov.start != null && !isNaN(ov.start)) ? ov.start : (base.start != null ? base.start : 1);
            return { fmt: ov.fmt || base.fmt || null, start };
        };

        // --- kumpulkan SEMUA paragraf; tandai yang memakai numbering langsung (w:numPr) ---
        const isDec = f => f === 'decimal' || f === 'decimalZero';
        const isLet = f => f === 'lowerLetter' || f === 'upperLetter';
        const textOf = p => Array.from(p.getElementsByTagNameNS(W, 't')).map(t => t.textContent).join('');
        const paras = Array.from(docDoc.getElementsByTagNameNS(W, 'p')).map(p => {
            const rec = { p, txt: textOf(p), item: null };
            const pPr = child(p, 'pPr');
            const numPr = pPr && child(pPr, 'numPr');
            if (!numPr) return rec;
            const numId = wAttr(child(numPr, 'numId'), 'val');
            if (!numId || numId === '0') return rec;
            const ilvl = parseInt(wAttr(child(numPr, 'ilvl'), 'val') || '0', 10);
            const info = levelInfo(numId, String(ilvl));
            if (info) rec.item = { pPr, numPr, numId, ilvl, fmt: info.fmt };
            return rec;
        });
        const items = paras.filter(r => r.item).map(r => r.item);

        // --- deteksi: ada daftar pilihan (a,b,c) yang numId-nya TERPISAH dari daftar nomor soal ---
        // (nomor soal bisa berupa numbering Word di level mana pun, atau diketik manual "7.")
        const decIds = new Set(items.filter(i => isDec(i.fmt)).map(i => i.numId));
        const letSep = items.filter(i => isLet(i.fmt) && !decIds.has(i.numId));
        if (!letSep.length) return arrayBuffer;

        const lvCount = {};          // numId -> counter per level (aturan Word: level lebih dalam di-reset)
        let optIdx = 0;
        const typedQ = /^\s*\d{1,3}[.)]\s/;
        paras.forEach(rec => {
            const it = rec.item;
            if (!it) {
                if (typedQ.test(rec.txt)) optIdx = 0;       // nomor soal diketik manual -> pilihan mulai dari "a" lagi
                return;
            }
            let label, already;
            if (isDec(it.fmt)) {
                const c = lvCount[it.numId] || (lvCount[it.numId] = []);
                const info = levelInfo(it.numId, String(it.ilvl));
                c[it.ilvl] = (c[it.ilvl] == null) ? info.start : c[it.ilvl] + 1;
                c.length = it.ilvl + 1;                     // reset level yang lebih dalam
                label = c[it.ilvl] + '.';
                already = typedQ.test(rec.txt);
                optIdx = 0;                                 // soal baru -> pilihan dihitung ulang
            } else if (isLet(it.fmt)) {
                // Huruf dihitung per soal (bukan mengikuti counter Word) supaya daftar yang
                // "melanjutkan" (mis. m., n., o.) tetap menjadi a, b, c, d pada tiap soal.
                label = String.fromCharCode((it.fmt === 'upperLetter' ? 65 : 97) + Math.min(optIdx, 25)) + '.';
                optIdx++;
                already = /^\s*[a-eA-E][.)]\s/.test(rec.txt);   // label sudah diketik manual -> jangan digandakan
            } else {
                return;                                     // bullet / roman dll. dibiarkan
            }
            it.pPr.removeChild(it.numPr);
            if (!already) {
                const r = docDoc.createElementNS(W, 'w:r');
                const t = docDoc.createElementNS(W, 'w:t');
                t.setAttributeNS(XMLNS, 'xml:space', 'preserve');
                t.textContent = label + ' ';
                r.appendChild(t);
                rec.p.insertBefore(r, it.pPr.nextSibling);
            }
        });

        zip.file('word/document.xml', new XMLSerializer().serializeToString(docDoc));
        return await zip.generateAsync({ type: 'arraybuffer', compression: 'DEFLATE' });
    } catch (err) {
        console.warn('normalizeDocxQuestionLayout dilewati:', err);
        return arrayBuffer;
    }
}

// Pecah baris pilihan 2 kolom menjadi satu pilihan per baris, urut A-B-C-D.

// ========================================================================
// WORD TABLE-DESIGN QUESTIONS
// ------------------------------------------------------------------------
// Sebagian template Word tidak memakai numbering/list untuk pilihan A-D.
// Pilihan ditempatkan di dalam table 2x2 (mis. a,b,c,d pada empat cell).
// Parser lama menyimpan table sebagai token sehingga pilihan tidak ikut terbaca.
// Fungsi ini mengubah HANYA table yang jelas merupakan grid pilihan menjadi
// paragraf A-D; table lain tetap dipertahankan sebagai table asli.
// ========================================================================
function normalizeImportedOptionTables(root) {
    if (!root || !root.querySelectorAll) return;

    const clean = s => String(s || '')
        .replace(/\u00A0/g, ' ')
        .replace(/\s+/g, ' ')
        .trim();

    const labelRe = /^\s*([a-dA-D])[.)]\s*/;

    root.querySelectorAll('table').forEach(table => {
        // Jangan sentuh tabel bertingkat.
        if (table.querySelector('table')) return;

        const directRows = Array.from(table.rows || []);
        if (!directRows.length) return;

        const rowCells = directRows.map(tr =>
            Array.from(tr.cells || []).filter(c => /^(TD|TH)$/i.test(c.tagName))
        ).filter(r => r.length);

        const cells = rowCells.flat();

        // Target utama: Table Design 2x2 (A B / C D).
        // Juga izinkan 1x4 / 4x1 untuk template sejenis.
        const isOptionShape =
            (rowCells.length === 2 && rowCells.every(r => r.length === 2)) ||
            (cells.length === 4 && (rowCells.length === 1 || rowCells.length === 4));

        if (!isOptionShape || cells.length !== 4) return;

        // Ekstrak isi setiap cell TANPA bergantung pada apakah numbering Word
        // terlihat sebagai teks HTML. Posisi fisik cell menjadi fallback utama.
        const extracted = cells.map((cell, idx) => {
            const clone = cell.cloneNode(true);

            // Hapus whitespace kosong di awal.
            const walker = document.createTreeWalker(clone, NodeFilter.SHOW_TEXT);
            let node;
            while ((node = walker.nextNode())) {
                let val = node.nodeValue || '';
                if (!val.trim()) continue;
                // Jika label a./b./c./d. tersimpan sebagai teks, hapus.
                if (/^\s*[a-dA-D][.)]\s*/.test(val)) {
                    node.nodeValue = val.replace(/^\s*[a-dA-D][.)]\s*/, '');
                }
                break;
            }

            // Numbering Word bisa menjadi <ol><li>. Jika hanya satu item,
            // lepaskan wrapper list agar parser tidak mendapat numbering kedua.
            clone.querySelectorAll('ol,ul').forEach(list => {
                const lis = Array.from(list.children).filter(x => x.tagName === 'LI');
                if (lis.length === 1) {
                    const holder = document.createElement('span');
                    holder.innerHTML = lis[0].innerHTML;
                    list.replaceWith(holder);
                }
            });

            const bodyText = clean(clone.textContent || '');
            return {
                index: idx,
                html: clone.innerHTML.trim(),
                text: bodyText
            };
        });

        // Pilihan dapat berupa TEKS, GAMBAR, atau kombinasi keduanya.
        // Jangan menolak cell kosong secara otomatis: pada soal bergambar,
        // cell opsi memang bisa hanya berisi <img> tanpa textContent.
        const hasVisual = x => !!(x && x.html && /<img\b/i.test(x.html));
        const hasContent = x => !!(x && (x.text || hasVisual(x)));
        if (extracted.some(x => !hasContent(x))) return;

        // Deteksi kuat: isi cell pendek dan berbentuk pilihan.
        // Jika numbering tidak terlihat, posisi fisik cell tetap menentukan A-D.
        const labels = extracted.map(x => {
            const m = x.text.match(labelRe);
            return m ? m[1].toUpperCase() : null;
        });
        const hasFourLabels = labels.every(Boolean) && new Set(labels).size === 4;

        // Hindari mengubah tabel data biasa. Tabel 2x2 yang berisi gambar
        // tetap dianggap grid pilihan bila setiap cell memiliki visual/teks.
        const looksDataLike = extracted.some(x =>
            x.text && /^(nama|kelas|no|nomor|keterangan|materi|indikator|tujuan|skor|nilai|jawaban|kunci)$/i.test(x.text)
        );
        if (looksDataLike) return;

        const ordered = hasFourLabels
            ? extracted.slice().sort((a,b) => labels[a.index].localeCompare(labels[b.index]))
            : extracted;

        const frag = document.createDocumentFragment();

        ordered.forEach((item, i) => {
            const label = hasFourLabels ? labels[item.index] : String.fromCharCode(65 + i);
            const d = document.createElement('div');
            d.className = 'import-option-line';
            d.setAttribute('data-table-option', '1');

            // Buat label sebagai text node terpisah. Isi cell dipertahankan,
            // termasuk gambar/equation/formatting.
            d.appendChild(document.createTextNode(label + '. '));

            const body = document.createElement('span');
            body.className = 'import-option-body';
            body.innerHTML = item.html;

            // Buang paragraf kosong dan label ganda yang mungkin tersisa.
            body.querySelectorAll('p').forEach(p => {
                const t = (p.textContent || '').trim();
                if (!t && !p.querySelector('img,.imported-equation')) p.remove();
            });
            const firstText = body.firstChild;
            if (firstText && firstText.nodeType === Node.TEXT_NODE) {
                firstText.nodeValue = firstText.nodeValue.replace(/^\s*[a-dA-D][.)]\s*/, '');
            }

            d.appendChild(body);
            frag.appendChild(d);
        });

        table.replaceWith(frag);
    });
}

function normalizeTwoColumnOptionRows(root) {
    const startsOpt = /^\s*[a-eA-E][.)]\s+\S/;
    const isRow = el => el && /^(P|DIV)$/.test(el.tagName)
        && !el.querySelector('table,ol,ul,p,div')
        && startsOpt.test((el.textContent || '').replace(/\u00A0/g, ' '));

    function splitRow(html) {
        const imgs = [];
        let h = String(html || '')
            .replace(/<img\b[^>]*>/gi, m => { imgs.push(m); return '\uE000' + (imgs.length - 1) + '\uE001'; })
            .replace(/&nbsp;/g, ' ')
            .replace(/\u00A0/g, ' ');
        const re = /(^\s*|[ ]*\t[\t ]*|[ ]{2,})([a-eA-E])[.)](?:\s+|(?=[A-Za-z0-9\uE000"'“‘(](?!\.)))/g;
        const hits = [];
        let m;
        while ((m = re.exec(h)) !== null) hits.push({ start: m.index, end: m.index + m[0].length, label: m[2].toUpperCase() });
        if (!hits.length || hits[0].start !== 0) return null;
        return hits.map((hit, i) => {
            const stop = i + 1 < hits.length ? hits[i + 1].start : h.length;
            const body = h.slice(hit.end, stop).trim()
                .replace(/\uE000(\d+)\uE001/g, (x, n) => imgs[+n]);
            return { label: hit.label, html: body };
        }).filter(sg => sg.html !== '');
    }

    const kids = Array.from(root.children);
    let i = 0;
    while (i < kids.length) {
        if (!isRow(kids[i])) { i++; continue; }
        let j = i;
        while (j < kids.length && isRow(kids[j])) j++;
        const run = kids.slice(i, j);
        const rows = run.map(el => splitRow(el.innerHTML));
        const multi = rows.some(r => r && r.length > 1);
        if (multi && rows.every(r => r && r.length)) {
            let segs = [].concat.apply([], rows);
            const labels = segs.map(x => x.label);
            if (new Set(labels).size === labels.length) segs.sort((a, b) => (a.label < b.label ? -1 : a.label > b.label ? 1 : 0));
            const frag = document.createDocumentFragment();
            segs.forEach(sg => {
                const d = document.createElement('div');
                d.className = 'import-option-line';
                d.innerHTML = sg.label + '. ' + sg.html;
                frag.appendChild(d);
            });
            run[0].parentNode.insertBefore(frag, run[0]);
            run.forEach(el => el.parentNode.removeChild(el));
        }
        i = j;
    }
}

function uploadBatchImages(input) {
    if (input.files && input.files.length > 0) {
        Array.from(input.files).forEach(file => {
            let reader = new FileReader();
            reader.onload = (e) => insertImageToInput(e.target.result);
            reader.readAsDataURL(file);
        });
    }
    input.value = '';
}

function preserveImportedTextFormatting(root) {
    if (!root) return;
    const nodes = Array.from(root.querySelectorAll('strong,b,em,i'));
    nodes.forEach(el => {
        if (!el.parentNode) return;
        const tag = el.tagName.toLowerCase();
        const open = (tag === 'strong' || tag === 'b') ? '[[FMT_B]]' : '[[FMT_I]]';
        const close = (tag === 'strong' || tag === 'b') ? '[[/FMT_B]]' : '[[/FMT_I]]';
        const frag = document.createDocumentFragment();
        frag.appendChild(document.createTextNode(open));
        while (el.firstChild) frag.appendChild(el.firstChild);
        frag.appendChild(document.createTextNode(close));
        el.parentNode.replaceChild(frag, el);
    });
    const styled = Array.from(root.querySelectorAll('span[style]')).filter(el => !el.hasAttribute('data-equation-latex'));
    styled.forEach(el => {
        if (!el.parentNode) return;
        const style = String(el.getAttribute('style') || '').toLowerCase();
        const isBold = /font-weight\s*:\s*(?:bold|[6-9]00)/i.test(style);
        const isItalic = /font-style\s*:\s*italic/i.test(style);
        if (!isBold && !isItalic) return;
        const frag = document.createDocumentFragment();
        if (isBold) frag.appendChild(document.createTextNode('[[FMT_B]]'));
        if (isItalic) frag.appendChild(document.createTextNode('[[FMT_I]]'));
        while (el.firstChild) frag.appendChild(el.firstChild);
        if (isItalic) frag.appendChild(document.createTextNode('[[/FMT_I]]'));
        if (isBold) frag.appendChild(document.createTextNode('[[/FMT_B]]'));
        el.parentNode.replaceChild(frag, el);
    });
}

function renderPreviewFormatting(value) {
    return String(value || '')
        .replace(/\[\[FMT_B\]\]/g, '<strong>')
        .replace(/\[\[\/FMT_B\]\]/g, '</strong>')
        .replace(/\[\[FMT_I\]\]/g, '<em>')
        .replace(/\[\[\/FMT_I\]\]/g, '</em>');
}

function stripPreviewFormattingMarkers(value) {
    return String(value || '')
        .replace(/\[\[FMT_B\]\]/g, '')
        .replace(/\[\[\/FMT_B\]\]/g, '')
        .replace(/\[\[FMT_I\]\]/g, '')
        .replace(/\[\[\/FMT_I\]\]/g, '');
}

function parseEditorContent() {
    const container = document.getElementById('inputText');
    if (!container) return;

    EDITOR_TABLES = {};
    EDITOR_TABLE_SEQ = 0;

    const clone = container.cloneNode(true);

    // Equation hasil Word: jangan ambil teks SVG/MathJax-nya. Ambil LaTeX asli yang
    // disimpan pada data-equation-latex agar parser soal membaca rumus sebagai data.
    restoreImportedEquationText(clone);

    // Simpan Bold/Italic sebelum innerText menghapus format.
    preserveImportedTextFormatting(clone);

    // Gambar (termasuk di dalam tabel) diganti token teks agar tidak hilang oleh innerText.
    clone.querySelectorAll('img').forEach(img => {
        const safe = safeImageSrc(img.getAttribute('src'));
        if (!safe) { img.remove(); return; }
        const token = makeImageToken(safe);
        EDITOR_IMAGES[token] = safe;
        const dw = parseFloat(img.getAttribute('data-w'));
        const dh = parseFloat(img.getAttribute('data-h'));
        if (dw > 0) EDITOR_IMAGE_SIZES[token] = { w: dw, h: dh > 0 ? dh : 0 };
        else delete EDITOR_IMAGE_SIZES[token];
        img.parentNode.replaceChild(document.createTextNode(' ' + token + ' '), img);
    });

    const tables = clone.querySelectorAll('table');
    tables.forEach(table => {
        const id = '[[TABLE_' + (EDITOR_TABLE_SEQ++) + ']]';
        // Bersihkan desain/format visual Word saja. Isi, struktur tabel, gambar,
// colspan/rowspan tetap dipertahankan untuk parser, preview, dan AI.
        EDITOR_TABLES[id] = cleanImportedQuestionTable(table);
        const marker = document.createElement('span');
        marker.textContent = '\n' + id + '\n';
        table.parentNode.replaceChild(marker, table);
    });

    // Browser clipboard kadang mengirim OL/UL. Jika masih ada, ubah juga menjadi manual.
    normalizeImportedDocxLists(clone);

    // PENTING: innerText pada elemen yang TIDAK menempel di DOM bekerja seperti textContent
    // (baris baru dari <div>/<br>/<p> hilang, sehingga "a. ...b. ...c. ..." menyatu).
    // Karena itu clone dipasang sementara di luar layar agar innerText memakai layout asli.
    let rawEditorText = '';
    const host = document.createElement('div');
    host.style.cssText = 'position:fixed;left:-99999px;top:0;width:800px;white-space:pre-wrap;pointer-events:none;';
    host.appendChild(clone);
    document.body.appendChild(host);
    try {
        rawEditorText = clone.innerText || clone.textContent || '';
    } finally {
        document.body.removeChild(host);
    }

    let text = rawEditorText
        .replace(/\u00A0/g, ' ')
        .replace(/[ \t]+\n/g, '\n')
        .replace(/\n{3,}/g, '\n\n')
        .trim();

    // ====================================================================
    // FIX KHUSUS SOAL PILIHAN BERGAMBAR DARI WORD (2 KOLOM)
    // --------------------------------------------------------------------
    // Hasil innerText Word pada beberapa template menjadi:
    //   pertanyaan
    //   [[IMG_A]]
    //   [[IMG_B]]
    //   C.
    //   [[IMG_C]]
    //   D.
    //   [[IMG_D]]
    // Label A/B hilang karena numbering berada di dalam cell. Jika langsung
    // diparse, gambar A+B dianggap satu opsi. Susun ulang berdasarkan urutan
    // fisik empat gambar menjadi A-B-C-D SEBELUM parser soal berjalan.
    // Hanya aktif jika tepat 4 gambar dan label tunggal yang tersisa (C/D),
    // sehingga soal teks dan soal bergambar lain tetap aman.
    {
        const visualTokens = text.match(/\[\[IMG_[0-9a-z]+\]\]/gi) || [];
        const loneLabels = text.match(/(?:^|\n)[ \t]*[A-Ea-e]\.[ \t]*(?=\n|$)/gm) || [];
        if (visualTokens.length === 4 && loneLabels.length >= 1 && loneLabels.length < 4) {
            const firstVisual = text.indexOf(visualTokens[0]);
            if (firstVisual > 0) {
                const beforeVisuals = text.slice(0, firstVisual).trim();
                const afterVisuals = text.slice(firstVisual);
                const residue = afterVisuals
                    .replace(/\[\[IMG_[0-9a-z]+\]\]/gi, '')
                    .replace(/(?:^|\n)[ \t]*[A-Ea-e]\.[ \t]*(?=\n|$)/gm, '')
                    .replace(/\s+/g, '')
                    .trim();
                if (!residue && beforeVisuals) {
                    text = beforeVisuals + '\n' +
                        'A. ' + visualTokens[0] + '\n' +
                        'B. ' + visualTokens[1] + '\n' +
                        'C. ' + visualTokens[2] + '\n' +
                        'D. ' + visualTokens[3];
                }
            }
        }
    }

    parseLocalInstantly(text);
    // Simpan tabel sebagai token selama parsing, tetapi sediakan versi HTML asli
    // untuk prompt AI dan renderer Preview.
}

function restoreEditorTablesRaw(value) {
    let raw = String(value || '');
    Object.keys(EDITOR_TABLES).forEach(token => {
        raw = raw.split(token).join(EDITOR_TABLES[token]);
    });
    return raw;
}

// Membersihkan DESAIN tabel Word tanpa menghapus tabelnya.
// Dipakai sejak tahap import agar format Table Design Word tidak mengganggu
// parsing soal/opsi, tetapi isi sel, gambar, baris/kolom, colspan dan rowspan tetap ada.
function cleanImportedQuestionTable(tableOrHtml) {
    const holder = document.createElement('div');

    if (typeof tableOrHtml === 'string') {
        holder.innerHTML = tableOrHtml;
    } else if (tableOrHtml && tableOrHtml.nodeType) {
        holder.appendChild(tableOrHtml.cloneNode(true));
    }

    const table = holder.querySelector('table');
    if (!table) return '';

    // Hapus elemen yang memang bukan isi soal.
    table.querySelectorAll('script,style,iframe,object,embed').forEach(el => el.remove());

    // Pertahankan hanya atribut yang berhubungan dengan struktur/isi.
    // Semua atribut visual khas Word (w:*, mso-*, border, shading, width,
    // class/style, event handler, dll.) dibuang.
    const keepAttrs = new Set([
        'colspan', 'rowspan', 'scope', 'headers',
        'src', 'alt', 'title', 'data-w', 'data-h'
    ]);

    table.querySelectorAll('*').forEach(el => {
        Array.from(el.attributes).forEach(attr => {
            const name = attr.name.toLowerCase();

            if (name.startsWith('on')) {
                el.removeAttribute(attr.name);
                return;
            }

            const imageOnly = ['src','alt','data-w','data-h'].includes(name);
            if (!keepAttrs.has(name) || (imageOnly && el.tagName !== 'IMG')) {
                el.removeAttribute(attr.name);
            }
        });
    });

    // Lepas pembungkus span/font yang hanya berasal dari styling Word.
    table.querySelectorAll('font,span').forEach(el => {
        if (el.attributes.length === 0) {
            const parent = el.parentNode;
            if (!parent) return;
            while (el.firstChild) parent.insertBefore(el.firstChild, el);
            el.remove();
        }
    });

    // Hapus atribut visual level tabel.
    [
        'style','class','id','width','height','border',
        'cellpadding','cellspacing','align','bgcolor',
        'bordercolor','frame','rules'
    ].forEach(attr => table.removeAttribute(attr));

    // Tampilan netral, bukan desain Word.
    table.style.cssText =
        'border-collapse:collapse;width:100%;margin:8px 0;table-layout:auto;';

    table.querySelectorAll('th,td').forEach(cell => {
        cell.style.cssText =
            'border:1px solid #bfc7c5;padding:4px 8px;vertical-align:top;';
    });

    return table.outerHTML;
}

function sanitizePreviewTable(tableHtml) {
    return cleanImportedQuestionTable(tableHtml) || escapeHtml(String(tableHtml || ''));
}

function renderPreviewContent(value) {
    let raw = String(value || '');
    const tables = [];
    raw = raw.replace(/<table\b[\s\S]*?<\/table>/gi, match => {
        const token = `___PREVIEW_TABLE_${tables.length}___`;
        tables.push(sanitizePreviewTable(match));
        return token;
    });

    // Token tabel juga didukung jika masih ada pada data draft/cache lama.
    Object.keys(EDITOR_TABLES || {}).forEach(token => {
        const safeToken = token.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        raw = raw.replace(new RegExp(safeToken, 'g'), `___EDITOR_TABLE_${tables.length}___`);
        tables.push(sanitizePreviewTable(EDITOR_TABLES[token]));
    });

    raw = escapeHtml(raw).replace(/\n/g, '<br>');
    tables.forEach((tableHtml, idx) => {
        raw = raw.replace(`___PREVIEW_TABLE_${idx}___`, tableHtml);
        raw = raw.replace(`___EDITOR_TABLE_${idx}___`, tableHtml);
    });
    return replaceImageTokens(raw, false);
}

function restoreEditorTables(value) {
    return renderPreviewContent(value);
}

function autoCleanAndParse() {
    let container = document.getElementById('inputText');
    let content = container.innerHTML.trim();
    let hasContent = content.length > 0;

    if (cacheLoaded) {
        cacheLoaded = false;
        isAiProcessed = false;
        activeQuestionFingerprint = '';
        setCacheStatus('', 'muted');
    }

    document.getElementById('btnAiAnswer').style.display = hasContent ? 'block' : 'none';
    
    if (!hasContent) {
        parsedData = [];
        isAiProcessed = false;
        activeQuestionFingerprint = '';
        setCacheStatus('', 'muted');
        renderPreview();
    } else {
        if (!isAiProcessed) {
            parseEditorContent();
            scheduleCacheLookup();
        }
    }
}

function parseLocalInstantly(text) {
    let rawText = String(text || '').trim();
    // Decode literal HTML entities that can appear after copying from a web/Word source.
    // In particular, A.&#x20;Jawaban must be parsed exactly like A. Jawaban.
    rawText = rawText
        .replace(/&amp;#x20;/gi, ' ')
        .replace(/&#x20;/gi, ' ')
        .replace(/&#32;/gi, ' ')
        .replace(/&nbsp;/gi, ' ');
    if (!rawText) {
        parsedData = [];
        renderPreview();
        return;
    }

    rawText = rawText
        .replace(/[\u00A0\u1680\u180E\u2000-\u200B\u202F\u205F\u3000\t]/g, ' ')
        .replace(/\r\n/g, '\n')
        .replace(/\r/g, '\n')
        .replace(/^(?:BERILAH\s+TANDA\s+SILANG[^\n]*|MATA\s+PELAJARAN[^\n]*|PENILAIAN\s+AKHIR[^\n]*|TAHUN\s+PELAJARAN[^\n]*|NAMA\s*:[^\n]*)\n+/gi, '')
        .trim();

    // PENTING untuk template Word dengan Table Design 2x2:
    // Word/renderer offline dapat menghasilkan label opsi dan isi pada baris terpisah:
    //   A.
    //   Al-An'am/6:108.
    // Satukan kembali sebelum parser mencari A-E. Ini hanya berlaku jika label
    // berdiri sendiri pada satu baris, sehingga tidak mengganggu teks biasa.
    rawText = rawText.replace(/(^|\n)[ \t]*([A-E])\.[ \t]*(?:\n[ \t]*)+(?=\S)/gm, '$1$2. ');
    rawText = rawText.replace(/(^|\n)[ \t]*([A-E])\)[ \t]*(?:\n[ \t]*)+(?=\S)/gm, '$1$2. ');

    // Rapikan format lembar soal (kop, judul bagian, label pilihan menempel, dsb.)
    // Lindungi tanda titik penutup pada isi opsi. Pada sebagian template Word
    // (terutama table-design), tanda titik di akhir jawaban dapat terbaca oleh
    // normalisasi nomor/opsi sebagai pemisah. Akibatnya:
    //   A. Al-An'am/6:108.
    // berubah menjadi
    //   A. Al-An'am/6:108
    // atau struktur opsi menjadi rusak.
    // Gunakan token sementara sampai seluruh tahap pemisahan selesai, lalu
    // kembalikan titiknya persis seperti sumber. Hanya titik PALING AKHIR
    // pada baris opsi yang dilindungi; titik di tengah teks tidak disentuh.
    const protectedOptionDots = [];
    rawText = rawText.replace(/(^|\n)([ \t]*[A-Ea-e][.)][ \t]+)([^\n]*?)\.[ \t]*(?=\n|$)/gm, (m, pre, label, body) => {
        const token = `[[OPT_DOT_${protectedOptionDots.length}]]`;
        protectedOptionDots.push('.');
        return pre + label + body + token;
    });

    rawText = examFormatPreprocess(rawText);

    // ===== TEKS/BACAAN BERSAMA ("Teks berikut untuk soal nomor 1 s.d. 5.") =====
    // Judul + paragraf bacaan sampai sebelum nomor soal pertama DIAMBIL dulu agar tidak dianggap
    // soal sendiri / dipecah oleh pemisah nomor ("s.d. 5."), lalu ditempelkan ke soal yang dituju.
    const REPEAT_SHARED_PASSAGE = false; // true = bacaan diulang di setiap soal dalam rentang nomor
    const sharedPassages = [];
    {
        const hdrRe = /\b(?:nomor|no\.?|numbers?|questions?|soal)\s*(\d{1,3})\s*(?:s\.?\s?d\.?|sampai(?:\s+dengan)?|hingga|until|through|to|[-–—])\s*(?:nomor\s*|no\.?\s*|numbers?\s*|questions?\s*)?(\d{1,3})\b/i;
        const startRe = /^(?:teks|bacaan|wacana|cerita|puisi|syair|pantun|data|tabel|gambar|grafik|diagram|bacalah|baca|simak|simaklah|cermati|cermatilah|amati|amatilah|perhatikan|perhatikanlah|untuk|petunjuk|the|this|these|dialog|dialogue|dialoge|conversation|text|passage|read|look|study|use|answer|choose|based|below|following)\b/i;
        const numRe = /^\d{1,3}[\.)]\s+\S/;
        const optRe = /^[a-eA-E][.)]\s+\S/;
        const lines = rawText.split('\n');
        const kept = [];
        for (let i = 0; i < lines.length; i++) {
            const t = lines[i].trim();
            const m = t.match(hdrRe);
            if (m && t.length <= 220 && startRe.test(t.replace(/^(?:\[\[IMG_[0-9a-z]+\]\]\s*)+/i, '')) && !numRe.test(t) && +m[2] > +m[1]) {
                const buf = [t];
                let j = i + 1, ok = true;
                while (j < lines.length && !numRe.test(lines[j].trim())) {
                    if (optRe.test(lines[j].trim())) { ok = false; break; }
                    buf.push(lines[j]);
                    j++;
                }
                if (ok && j < lines.length) {
                    sharedPassages.push({
                        from: +m[1], to: +m[2],
                        text: buf.map(x => x.trim()).join('\n').replace(/\n{2,}/g, '\n').trim()
                    });
                    i = j - 1;
                    continue;
                }
            }
            kept.push(lines[i]);
        }
        rawText = kept.join('\n').trim();
    }

    // Lindungi referensi bab/ayat yang berbentuk "(Bab 5)" sebelum tahap
    // pemisahan nomor soal. Parser lama mengira "5)" di dalam "(Bab 5)"
    // adalah awal soal baru, sehingga soal bisa pecah/kosong ketika diimpor.
    // Token ini hanya sementara dan akan dikembalikan lagi sebelum ekstraksi soal.
    const protectedChapterRefs = [];
    rawText = rawText.replace(/\((?:bab|chapter|bab\.|chapter\.)\s*\d{1,3}\)/gi, m => {
        const token = `[[CHAPTER_REF_${protectedChapterRefs.length}]]`;
        protectedChapterRefs.push(m);
        return token;
    });

    // Nomor soal/opsi dari Word/PDF sering menempel dengan teks sebelumnya,
    // misalnya: "D. jawaban lawan2. Manakah...".
    // Pisahkan nomor/opsi menjadi baris manual agar parser tidak menggabungkan soal.
    // Perbaiki artefak numbering Word yang kadang menyisakan angka "1"
    // tepat sebelum nomor soal 10, 11, 12, dst.
    rawText = rawText
        .replace(/([^\n])\s+1\s+(?=(1\d{1,2}[\.)]\s+))/g, '$1\n\n')
        .replace(/([^\n])\s+1\s*\n\s*(1\d{1,2}[\.)]\s+)/g, '$1\n$2')
        .replace(/([^\n])\s+1\s+(?=0[\.)]\s+)/g, '$1\n10. ')
        .replace(/([^\n])\s+(?=(\d{1,3}\.[ \t]+\S))/g, '$1\n\n')
        .replace(/([.?!])\s+(?=(\d{1,3}\)\s+))/g, '$1\n\n')
        .replace(/([^\n])\s+(?=([a-eA-E][\.)]\s+))/g, '$1\n')
        // Nomor yang menempel pada teks (mis. "lawan2. Manakah"), tapi bukan angka bersambung seperti "2022. Ia".
        // Jangan memisahkan angka bertanda ')' yang berada di dalam opsi, misalnya
        // A. 1) dan 2) / D. 1), 2), dan 3). Sebelumnya aturan ini memotong
        // '2)' menjadi baris baru sehingga isi opsi hanya terbaca '1) dan'.
        // Untuk nomor soal yang menempel pada teks, gunakan bentuk '2.' saja;
        // nomor soal '2)' setelah tanda baca sudah ditangani oleh aturan di atas.
        .replace(/([^\n\d(])(?=(\d{1,3}\.[ \t]+\S))/g, '$1\n\n')
        // CATATAN: pemisahan opsi yang menempel TANPA spasi dihapus, karena memotong kata
        // yang berakhiran a-e + titik (mis. "temannya. Edo" -> "teman-temanny" + "a. Edo").
        .replace(/\s+([a-eA-E])[\.)]\s+/g, '\n$1.  ');

    // ===== BEDAKAN NOMOR SOAL vs SUB-DAFTAR (1) 2) 3) di dalam soal) =====
    // Baris bernomor dianggap soal baru hanya jika:
    //  - soal pertama, atau
    //  - memakai pemisah yang sama dengan soal pertama (mis. "1." ) DAN
    //    soal sebelumnya sudah punya pilihan A-E atau nomornya berurutan.
    // Selain itu dianggap bagian dari soal (pernyataan/daftar) dan diberi
    // penanda tak-terlihat \u2063 agar tidak dipecah oleh tahap berikutnya.
    {
        const isOptLine = t => /^[a-eA-E][.)]\s+\S/.test(t);
        // Mendukung "1." , "1)" dan "1)." (hasil tempel dari Word)
        const parseNum = t => {
            const m = t.match(/^(\d{1,3})(\)\.|[.)])\s+/);
            return m ? { n: parseInt(m[1], 10), d: m[2][0] === ')' ? ')' : '.', len: m[0].length } : null;
        };
        const lines0 = rawText.split('\n');
        const info = lines0.map(l => parseNum(l.trim()));
        const hasAnyOpt = lines0.some(l => isOptLine(l.trim()));

        // Pemisah nomor SOAL: baris bernomor TERDEKAT sebelum tiap kelompok pilihan A-E
        // (itulah nomor soal sungguhan). Jika seri, ikuti baris bernomor pertama di dokumen.
        let qDelim = null;
        {
            const tally = { '.': 0, ')': 0 };
            let lastNumD = null, prevWasOpt = false;
            lines0.forEach((l, i) => {
                const t = l.trim();
                if (!t) return;
                if (info[i]) lastNumD = info[i].d;
                const o = isOptLine(t);
                if (o && !prevWasOpt && lastNumD) tally[lastNumD]++;
                prevWasOpt = o;
            });
            const firstNum = info.find(x => x);
            if (tally['.'] !== tally[')']) qDelim = tally['.'] > tally[')'] ? '.' : ')';
            else if (firstNum) qDelim = firstNum.d;
        }
        const firstLine = (lines0.find(l => l.trim()) || '').trim();
        const startsNumbered = !!parseNum(firstLine);

        // ===== DETEKSI SUB-DAFTAR YANG MEMAKAI NOMOR SAMA DENGAN NOMOR SOAL =====
        // Rangkaian baris bernomor berurutan yang diikuti pilihan A-E dipecah menjadi segmen
        // naik-satu-satu (1,2,3 | 3 ...). Segmen yang panjangnya >=2 dan mulai dari 1 = daftar
        // pernyataan/data; segmen tunggal = nomor soal. Contoh: 1,2,3,3 -> daftar 1-3 + soal "3".
        const forceSub = new Set();
        if (hasAnyOpt && qDelim) {
            const numIdx = [];
            lines0.forEach((l, i) => { if (info[i] && info[i].d === qDelim) numIdx.push(i); });
            const runs = [];
            numIdx.forEach(i => {
                const run = runs[runs.length - 1];
                if (run) {
                    const prev = run[run.length - 1];
                    let gapBlank = true;
                    for (let k = prev + 1; k < i; k++) if (lines0[k].trim() !== '') { gapBlank = false; break; }
                    if (gapBlank) { run.push(i); return; }
                }
                runs.push([i]);
            });
            runs.forEach((run, ri) => {
                if (run.length < 2) return;
                // apakah ada pilihan A-E setelah baris terakhir run (sebelum baris bernomor berikutnya)?
                const nextNum = (ri + 1 < runs.length) ? runs[ri + 1][0] : lines0.length;
                let followedByOpt = false;
                for (let k = run[run.length - 1] + 1; k < nextNum; k++) {
                    if (isOptLine(lines0[k].trim())) { followedByOpt = true; break; }
                }
                if (!followedByOpt) return;
                const segs = [];
                run.forEach(i => {
                    const seg = segs[segs.length - 1];
                    if (seg && info[seg[seg.length - 1]].n + 1 === info[i].n) seg.push(i);
                    else segs.push([i]);
                });
                if (segs.length >= 2) {
                    segs.forEach(seg => {
                        if (seg.length >= 2 && info[seg[0]].n === 1) seg.forEach(i => forceSub.add(i));
                    });
                } else if (segs[0].length >= 3 && info[segs[0][0]].n === 1) {
                    // Jika dokumen diawali dengan nomor soal lalu langsung diikuti
                    // daftar bernomor 1,2,3,... di dalam soal, jangan menganggap
                    // nomor terakhir sebagai soal baru. Contoh:
                    // 1. Perhatikan nama pulau di bawah ini!
                    // 1. Sulawesi
                    // 2. Sumatra
                    // ...
                    // 6. Madura
                    // Persebaran fauna peralihan ...
                    // a. ...
                    // Nomor pertama adalah nomor SOAL, sedangkan nomor 2 dst.
                    // adalah bagian dari daftar di dalam soal.
                    const firstIdx = segs[0][0];
                    const firstIsDocumentStart = lines0.slice(0, firstIdx)
                        .every(l => !l.trim());
                    const firstText = firstIdx >= 0 ? lines0[firstIdx].trim() : '';
                    const looksLikeQuestionIntro = /^(?:perhatikan|perhatikanlah|bacalah|baca|cermati|cermatilah|amati|amatilah|simak|simaklah|diketahui|berdasarkan|lihatlah|perhatikan data|perhatikan tabel|perhatikan gambar)\b/i.test(
                        firstText.replace(/^\d{1,3}[.)]\s+/, '')
                    );
                    if (firstIsDocumentStart && looksLikeQuestionIntro && segs[0].length >= 4) {
                        // Pertahankan nomor pertama sebagai nomor soal.
                        // Semua nomor berikutnya adalah sub-daftar.
                        segs[0].slice(1).forEach(i => forceSub.add(i));
                    } else {
                        segs[0].slice(0, -1).forEach(i => forceSub.add(i));
                    }
                }
            });
        }

        let lastQ = 0, curHasOpt = false, opened = false, implicitOpen = !startsNumbered, subSeen = false;
        rawText = lines0.map((line, idx) => {
            const t = line.trim();
            if (isOptLine(t)) curHasOpt = true;
            const nm = info[idx];
            if (!nm) return line;
            if (qDelim === null) qDelim = nm.d;   // tidak ada info lain: ikuti baris bernomor pertama

            const asSub = () => {
                if (!curHasOpt) subSeen = true;
                return '\u2063' + t.replace(/^(\d{1,3})\)\.\s*/, '$1) ');
            };
            if (nm.d !== qDelim || forceSub.has(idx)) return asSub();

            // Teks pengantar tanpa nomor + daftar, lalu baris bernomor (mis. "3. pernyataan di atas ...")
            // = nomor itu milik soal pengantar tadi, bukan soal baru. Nomornya dibuang, teksnya digabung.
            if (implicitOpen && subSeen && !curHasOpt) {
                implicitOpen = false; opened = true; lastQ = nm.n;
                return t.slice(nm.len);
            }

            const isQ = (!opened && startsNumbered) || curHasOpt || nm.n === lastQ + 1;
            if (isQ) { lastQ = nm.n; curHasOpt = false; opened = true; implicitOpen = false; subSeen = false; return line; }
            return asSub();
        }).join('\n');
    }

    // Jika dokumen sama sekali tidak mempunyai nomor, anggap seluruh teks sebagai soal No. 1.
    if (!/^\s*\d{1,3}[\.)]\s+/.test(rawText)) {
        rawText = '1. ' + rawText.trim();
    }

    // Normalisasi nomor menjadi format manual "1."
    rawText = rawText.replace(/^\s*(\d{1,3})[\.)]\s*/gm, '$1. ');
    // Normalisasi pilihan menjadi format manual A., B., C., D., E.
    rawText = rawText.replace(/^\s*([a-eA-E])[\.)]\s*/gm, (_, ch) => ch.toUpperCase() + '. ');

    // Kembalikan referensi bab yang tadi dilindungi.
    protectedChapterRefs.forEach((value, i) => {
        rawText = rawText.replace(new RegExp('\\[\\[CHAPTER_REF_' + i + '\\]\\]', 'g'), value);
    });

    // Kembalikan titik penutup opsi setelah seluruh normalisasi nomor/opsi selesai.
    // Dengan demikian titik jawaban tidak pernah dianggap sebagai pemisah soal.
    protectedOptionDots.forEach((value, i) => {
        rawText = rawText.replace(new RegExp('\\[\\[OPT_DOT_' + i + '\\]\\]', 'g'), value);
    });

    const lines = rawText.split('\n');
    let processedText = '';
    for (const line of lines) {
        const trimmed = line.trim();
        if (/^\d{1,3}[\.)]\s+/.test(trimmed)) {
            processedText += '\n===SOAL===\n' + trimmed + '\n';
        } else {
            processedText += line + '\n';
        }
    }

    const items = [];
    const srcNos = [];
    let realNumberCounter = 1;
    // ===== SINKRONISASI POSISI GAMBAR =====
    // Gambar yang berdiri sendiri (baris sendiri) SETELAH opsi terakhir sebuah soal
    // sebenarnya milik soal berikutnya (gambar diletakkan di atas nomor soal berikutnya).
    // Tanpa ini, gambar nempel ke opsi terakhir soal sebelumnya (mis. opsi D soal 4).
    // Gambar yang sebaris dengan opsi (mis. "D. [gambar]") tetap menjadi milik opsi itu.
    const rawBlocks = processedText.split('===SOAL===')
        .map(b => b.trim())
        .filter(b => b.length > 3);
    let carryImages = '';
    rawBlocks.forEach((blk, idx) => {
        if (carryImages) {
            blk = blk.replace(/^(\d{1,3}[\.)]\s*)/, '$1' + carryImages + '\n');
            carryImages = '';
        }
        if (idx < rawBlocks.length - 1) {
            const tail = blk.match(/\n[ \t]*((?:\[\[IMG_[0-9a-z]+\]\][ \t\n]*)+)$/);
            if (tail) {
                const body = blk.slice(0, tail.index).trimEnd();
                const lastLine = body.split('\n').pop().trim();
                // hanya dipindah jika opsi terakhir sudah punya teks sendiri
                if (/^[A-E]\.\s+\S/.test(lastLine)) {
                    carryImages = tail[1].trim().replace(/\s+/g, ' ');
                    blk = body;
                }
            }
        }
        rawBlocks[idx] = blk;
    });

    // ===== TEKS PENGANTAR DI ATAS NOMOR BERIKUTNYA =====
    // Contoh: setelah opsi D soal 1 ada "Perhatikan data berikut! (1)... (2)..."
    // lalu baru "2. Ketampakan ...". Teks itu milik soal 2, bukan opsi D soal 1.
    let carryText = '';
    rawBlocks.forEach((blk, idx) => {
        if (carryText) {
            const ct = carryText;
            blk = blk.replace(/^(\d{1,3}[\.)]\s*)/, (m, g) => g + ct + '\n');
            carryText = '';
        }
        if (idx < rawBlocks.length - 1) {
            const ls = blk.split('\n');
            let lastOpt = -1, optCount = 0;
            ls.forEach((l, i) => { if (/^[A-E]\.\s+\S/.test(l.trim())) { lastOpt = i; optCount++; } });
            if (optCount >= 2 && lastOpt < ls.length - 1) {
                const tail = ls.slice(lastOpt + 1);
                const firstIdx = tail.findIndex(l => l.trim() !== '');
                if (firstIdx >= 0) {
                    const firstLine = tail[firstIdx].trim();
                    const hadBlank = firstIdx > 0;
                    const looksIntro = /^(perhatikan|perhatikanlah|bacalah|baca|cermati|cermatilah|amati|amatilah|simak|simaklah|diketahui|data|tabel|teks|bacaan|wacana|gambar)\b/i.test(firstLine.replace(/^(?:\[\[IMG_[0-9a-z]+\]\]\s*)+/i, ''));
                    if (hadBlank || looksIntro) {
                        carryText = tail.slice(firstIdx).map(l => l.trim()).filter(Boolean).join('\n');
                        blk = ls.slice(0, lastOpt + 1).join('\n').trim();
                    }
                }
            }
        }
        rawBlocks[idx] = blk;
    });

    rawBlocks.forEach(block => {
        const cleanBlock = block.trim();
        if (cleanBlock.length <= 3) return;

        const parsedObj = extractQuestionAndOptions(cleanBlock, realNumberCounter);
        if (!parsedObj || !parsedObj.question) return;
        const mSrc = cleanBlock.match(/^(\d{1,3})[\.)]/);
        const srcNo = mSrc ? parseInt(mSrc[1], 10) : null;

        // Kembalikan tabel yang sebelumnya diamankan oleh parseEditorContent().
        const restoreTables = value => String(value || '').replace(/\[\[TABLE_(\d+)\]\]/g, (m, n) => {
            return (typeof EDITOR_TABLES !== 'undefined' && EDITOR_TABLES['[[TABLE_'+Number(n)+']]'])
                ? EDITOR_TABLES['[[TABLE_'+Number(n)+']]']
                : m;
        });

        parsedObj.question = restoreTables(parsedObj.question);
        if (Array.isArray(parsedObj.options)) {
            parsedObj.options = parsedObj.options.map(restoreTables);
        }
        items.push(parsedObj);
        srcNos.push(srcNo);
        realNumberCounter++;
    });

    // Tempelkan bacaan bersama ke soal pertama pada rentang nomornya (atau semua soal bila diaktifkan).
    let minIdx = 0;
    sharedPassages.forEach(ps => {
        let targets = [];
        for (let i = minIdx; i < items.length; i++) {
            if (srcNos[i] !== null && srcNos[i] >= ps.from && srcNos[i] <= ps.to) targets.push(i);
        }
        if (!targets.length) {
            for (let n = ps.from; n <= ps.to; n++) if (items[n - 1]) targets.push(n - 1);
        }
        if (!targets.length) return;
        minIdx = targets[0] + 1;
        if (!REPEAT_SHARED_PASSAGE) targets = targets.slice(0, 1);
        const passageText = String(ps.text).replace(/\[\[TABLE_(\d+)\]\]/g, (m, n) =>
            (typeof EDITOR_TABLES !== 'undefined' && EDITOR_TABLES['[[TABLE_' + Number(n) + ']]']) || m);
        targets.forEach(i => { items[i].question = passageText + '\n' + items[i].question; });
});

    parsedData = items;
    renderPreview();
}

// =========================================================================
// FORMAT LEMBAR SOAL (mis. soal Bahasa Inggris SD):
//  - kop surat (logo, nama sekolah, tabel Nama/Kelas/Nilai) tidak dianggap soal
//  - judul bagian "I. Ayo memilih ...", "II. ISIAN !" + kalimat petunjuknya dibuang dari soal
//  - penutup "SELAMAT MENGERJAKAN" dibuang
//  - label pilihan yang menempel: "d.drank ice tea", "c.i played" -> "d. drank ...", "c. i played ..."
//  - koma nyasar sebelum nomor soal: ",17. Ruri" -> "17. Ruri"
// Hanya dijalankan bila polanya jelas; teks biasa tanpa kop/judul bagian tidak diubah.
// =========================================================================
function examFormatPreprocess(text) {
    let t = String(text || '');

    t = t.replace(/(^|\n)[ \t]*[,;][ \t]*(?=\d{1,3}[.)][ \t]*\S)/g, '$1');

    const IMGONLY = /^(?:\[\[IMG_[0-9a-z]+\]\]\s*)+$/i;
    const stripImg = l => String(l).replace(/\[\[IMG_[0-9a-z]+\]\]/gi, ' ').replace(/\s+/g, ' ').trim();
    const NUMQ = /^\s*\d{1,3}[.)]\s*\S/;
    const OPTL = /^\s*[a-eA-E][.)]\s+\S/;
    let lines = t.split('\n');

    // ---- (1) judul bagian + petunjuk, penutup ----
    const ROMAN = /^\s*[IVX]{1,4}[.)]\s+(\S.{0,100})$/;
    const SEC = /(pilih|ayo|isian|isilah|uraian|essay|esai|menjodohkan|jodohkan|benar|salah|jawab|bagian|part|section|multiple|choice|choose|fill|match|true|false|petunjuk|short answer)/i;
    const INSTR = /^\s*(?:ayo|pilih|pilihlah|berilah|jawablah|jawab|isilah|isi|kerjakan|kerjakanlah|lengkapilah|tentukan|silanglah|lingkarilah|answer|choose|fill|complete|circle|cross|match)\b.{0,160}$/i;
    const FOOT = /^\W*(?:selamat\s+mengerjakan|good\s+luck|semoga\s+sukses|the\s+end)\W*$/i;
    const out1 = [];
    for (let i = 0; i < lines.length; i++) {
        const L = lines[i];
        const x = stripImg(L);
        const rm = x.match(ROMAN);
        if (rm && SEC.test(rm[1]) && !OPTL.test(L)) {
            let j = i + 1;
            while (j < lines.length && !lines[j].trim()) j++;
            let cnt = 0;
            while (j < lines.length && cnt < 2 && INSTR.test(stripImg(lines[j])) && !NUMQ.test(lines[j])) {
                j++; cnt++;
                while (j < lines.length && !lines[j].trim()) j++;
            }
            i = j - 1;
            continue;
        }
        if (FOOT.test(x)) continue;
        out1.push(L);
    }
    lines = out1;

    // ---- (2) kop surat di awal dokumen (hanya bila ciri kop jelas) ----
    const STRONG = /ass?ess?ment|asesmen|penilaian|ulangan|ujian|sumatif|formatif|tahun\s+(?:pelajaran|ajaran)|mata\s*pelajaran|hari\s*\/?\s*tanggal/i;
    const kopTable = l => {
        const tok = l.trim();
        if (!/^\[\[TABLE_\d+\]\]$/.test(tok)) return false;
        const h = (typeof EDITOR_TABLES !== 'undefined' && EDITOR_TABLES[tok]) || '';
        const txt = h.replace(/<[^>]*>/g, ' ').replace(/\s+/g, ' ');
        return /(mata\s*pelajaran|hari\s*\/?\s*tanggal|\bnama\b|\bnilai\b|\bkelas\b|\bsemester\b)/i.test(txt);
    };
    const kind = l => {
        const raw = l.trim();
        if (!raw) return 'blank';
        if (IMGONLY.test(raw)) return 'img';
        if (kopTable(raw)) return 'table';
        const x = stripImg(raw);
        if (x.length > 140) return null;
        if (NUMQ.test(x) || OPTL.test(x)) return null;
        if (STRONG.test(x)) return 'strong';
        if (/^(?:nama|kelas|hari|tanggal|nilai|waktu|alokasi|semester|mata\s*pelajaran)\b\s*[:：]/i.test(x)) return 'strong';
        if (/^(?:sd|smp|sma|smk|mi|mts|ma|upt|upd)\b/i.test(x) || (x === x.toUpperCase() && /[A-Z]{3}/.test(x))) return 'soft';
        return null;
    };
    let k = 0, strong = false;
    while (k < lines.length) {
        const kd = kind(lines[k]);
        if (!kd) break;
        if (kd === 'strong' || kd === 'table') strong = true;
        k++;
    }
    if (strong && k < lines.length) lines = lines.slice(k);

    // ---- (3) label pilihan menempel pada teks ----
    lines = lines.map(line => {
        const ctx = /^\s*[a-eA-E][.)]\s*\S/.test(line) || /\s[a-eA-E][.)]\s+\S/.test(line);
        if (!ctx) return line;
        return line.replace(/(^|\s)([a-eA-E])([.)])(?=[A-Za-z0-9"'“‘(\[](?!\.))/g, '$1$2$3 ');
    });

    return lines.join('\n').replace(/^\s*\n+/, '').trim();
}

// Rapikan teks soal tetapi PERTAHANKAN baris baru (daftar pernyataan, data 1)-3)).
function tidyQuestionText(s) {
    let t = String(s || '')
        .replace(/\u2063/g, '')
        .replace(/\s*(\[\[(?:TABLE|IMG)_[0-9a-z]+\]\])\s*/gi, ' $1 ')
        .replace(/[ \t]+/g, ' ')
        .replace(/ *\n */g, '\n')
        .replace(/\n{2,}/g, '\n')
        .trim();

    // Word kadang menaruh Equation pada paragraf/baris terpisah. Jika sebuah
    // baris hanya berisi rumus, satukan dengan baris soal sebelumnya agar tidak
    // muncul sebagai pecahan yang "menggantung" di bawah pertanyaan.
    t = t.replace(/([^\n])\n[ \t]*(?=(?:\\frac|\\sqrt|\\left|\\d+\s*\/))/g, '$1 ');
    return t.trim();
}

function extractQuestionAndOptions(chunkText, num) {
    if (!chunkText) return null;

    let cleanText = String(chunkText)
        .replace(/[\u00A0\u1680\u180E\u2000-\u200B\u202F\u205F\u3000\t]+/g, ' ')
        .replace(/\r\n/g, '\n')
        .replace(/\r/g, '\n')
        .trim();

    cleanText = cleanText.replace(/^\d{1,3}[\.\)]\s*/, '').trim();

    // Pastikan label opsi yang terpisah dari isi oleh line-break Word disatukan.
    // Contoh Word table-design: "A.\nAl-An'am/6:108." -> "A. Al-An'am/6:108."
    cleanText = cleanText
        .replace(/&amp;#x20;/gi, ' ')
        .replace(/&#x20;/gi, ' ')
        .replace(/&#32;/gi, ' ')
        .replace(/&nbsp;/gi, ' ');
    cleanText = cleanText.replace(/(^|\n)[ \t]*([a-eA-E])\.[ \t]*(?:\n[ \t]*)+(?=\S)/gm, '$1$2. ');
    cleanText = cleanText.replace(/(^|\n)[ \t]*([a-eA-E])\)[ \t]*(?:\n[ \t]*)+(?=\S)/gm, '$1$2. ');

    // Pastikan pilihan yang masih menempel dalam satu baris dipisahkan.
    // Contoh: "A. ... B. ... C. ... D. ..." -> setiap pilihan menjadi baris sendiri.
    // Dua spasi setelah label dipakai agar hasil Input/Preview lebih mudah dibaca.
    cleanText = cleanText
        .replace(/\s+([a-eA-E])[\.\)]\s+/g, '\n$1.  ')
        .replace(/^([a-eA-E])[\.\)]\s+/gm, (_, ch) => ch.toUpperCase() + '.  ')
        .replace(/\n{3,}/g, '\n\n')
        .trim();

    // ===== OPSI BERGAMBAR DARI LAYOUT WORD 2 KOLOM =====
    // Pada beberapa dokumen Word, pilihan A-D berupa empat gambar dalam tabel/
    // layout dua kolom. Saat disalin, browser bisa mengeluarkan urutan seperti:
    //   [[IMG_A]]
    //   [[IMG_B]]
    //   C.
    //   [[IMG_C]]
    //   D.
    //   [[IMG_D]]
    // sehingga parser biasa menganggap gambar A+B sebagai isi opsi A.
    // Jika ditemukan tepat 4 gambar dan label pilihan yang ada hanya berupa
    // label tunggal (mis. C. dan D.), anggap keempat gambar itu adalah A-D
    // berdasarkan urutan visualnya. Ini TIDAK menyentuh soal teks biasa.
    const imgTokens = cleanText.match(/\[\[IMG_[0-9a-z]+\]\]/gi) || [];
    // Label A/B kadang hilang dari hasil copy Word, sedangkan C/D menempel
    // langsung dengan gambar pada baris yang sama. Karena itu jangan hanya
    // mencari label yang berdiri sendiri di satu baris.
    const standaloneOptLabels = cleanText.match(/(?:^|\n)\s*[A-Ea-e]\.\s*(?=\n|$)/gm) || [];
    const anyOptLabels = cleanText.match(/(?:^|\n|[ \t])\s*[A-Ea-e]\.\s*(?=(?:\[\[IMG_|\n|$))/g) || [];

    // ===== PILIHAN BERGAMBAR DARI WORD: GAMBAR SOAL + 4 GAMBAR OPSI =====
    // Pola yang sering keluar dari Word Table Design 2x2 adalah:
    //   pertanyaan
    //   [[IMG_SOAL]]
    //   [[IMG_A]]
    //   [[IMG_B]]
    //   C.
    //   [[IMG_C]]
    //   D.
    //   [[IMG_D]]
    // Label A/B dapat hilang ketika Word menyimpan numbering di dalam cell.
    // Dalam pola ini ada 5 gambar: 1 gambar pertanyaan + 4 gambar opsi.
    // Jangan gabungkan gambar A+B menjadi satu opsi. Gunakan urutan visual
    // empat gambar terakhir sebagai A,B,C,D. Ini hanya aktif jika ada tepat
    // 5 gambar dan minimal satu label opsi tunggal (C./D. pada template ini),
    // sehingga soal bergambar lain tidak ikut berubah.
    if (imgTokens.length === 5 && standaloneOptLabels.length >= 1 && standaloneOptLabels.length < 4) {
        const secondImgPos = cleanText.indexOf(imgTokens[1]);
        if (secondImgPos > 0) {
            const beforeOptions = cleanText.slice(0, secondImgPos).trim();
            const tail = cleanText.slice(secondImgPos)
                .replace(/\[\[IMG_[0-9a-z]+\]\]/gi, '')
                .replace(/(?:^|\n)\s*[A-Ea-e]\.\s*(?=\n|$)/gm, '')
                .replace(/[ \t]+\n/g, '\n')
                .replace(/\n{2,}/g, '\n')
                .trim();
            if (!tail) {
                return {
                    number: num,
                    question: tidyQuestionText(beforeOptions),
                    jenis: 1,
                    options: imgTokens.slice(1, 5),
                    key: null
                };
            }
        }
    }

    // Versi tanpa gambar pada pertanyaan: empat gambar merupakan empat opsi.
    // Pada hasil copy dari Word, label A/B kadang hilang dan hanya C./D. yang
    // tersisa. Jangan mengelompokkan gambar berdasarkan label yang tersisa;
    // gunakan URUTAN GAMBAR sebagai urutan A-B-C-D.
    if (imgTokens.length === 4 && (standaloneOptLabels.length >= 1 || anyOptLabels.length >= 1)) {
        const firstImgPos = cleanText.indexOf(imgTokens[0]);
        if (firstImgPos >= 0) {
            const beforeImages = cleanText.slice(0, firstImgPos).trim();
            const afterImages = cleanText.slice(firstImgPos);
            // Setelah gambar pertama, dokumen contoh MTK(2).docx hanya berisi
            // empat gambar pilihan dan label C./D. yang kadang menempel pada
            // gambar. Jika tidak ada teks lain, empat gambar tersebut pasti
            // merupakan A, B, C, D dalam urutan visual Word.
            const leftoverText = afterImages
                .replace(/\[\[IMG_[0-9a-z]+\]\]/gi, '')
                .replace(/(?:^|[\n \t])[A-Ea-e]\s*[\.)]\s*/g, '')
                .replace(/[ \t]+/g, '')
                .replace(/\n/g, '')
                .trim();
            if (!leftoverText) {
                return {
                    number: num,
                    question: tidyQuestionText(beforeImages),
                    jenis: 1,
                    options: imgTokens.slice(0, 4),
                    key: null
                };
            }
        }
    }

    const optRegex = /(?:^|\n)\s*([a-eA-E])\.\s*/g;
    const matches = [];
    let m;

    while ((m = optRegex.exec(cleanText)) !== null) {
        matches.push({
            index: m.index,
            contentIndex: m.index + m[0].length,
            fullMatchLength: m[0].length,
            label: m[1].toUpperCase()
        });
    }

    let questionText = '';
    let options = [];

    if (matches.length > 0) {
        questionText = cleanText.substring(0, matches[0].index).trim();

        const limitOptions = Math.min(matches.length, 5);
        const tmpOpts = [];
        for (let i = 0; i < limitOptions; i++) {
            const start = matches[i].contentIndex;
            const end = (i + 1 < matches.length) ? matches[i + 1].index : cleanText.length;
            let optVal = cleanText.substring(start, end)
                .replace(/\s+/g, ' ')
                .trim();
            // Jika masih ada label pilihan yang ikut terbawa, buang sekali lagi.
            optVal = optVal.replace(/\u2063/g, '').replace(/^[a-eA-E][\.\)]\s*/u, '').trim();
            if (optVal) tmpOpts.push({ l: matches[i].label, v: optVal });
        }
        // Layout dua kolom ("a. .. c. .." lalu "b. .. d. ..") terbaca A,C,B,D -> urutkan menurut huruf.
        const labelsSeen = tmpOpts.map(x => x.l);
        if (new Set(labelsSeen).size === labelsSeen.length) {
            tmpOpts.sort((x, y) => (x.l < y.l ? -1 : x.l > y.l ? 1 : 0));
        }
        options = tmpOpts.map(x => x.v);
    } else {
        questionText = cleanText;
    }

    questionText = tidyQuestionText(questionText);

    return {
        number: num,
        question: questionText,
        jenis: options.length > 0 ? 1 : 5,
        options: options.length > 0 ? options : null,
        key: null
    };
}

let AI_LOAD = null;   // status animasi loading konversi soal

function aiLdFmt(sec) { sec = Math.max(0, Math.round(sec)); return Math.floor(sec / 60) + ':' + String(sec % 60).padStart(2, '0'); }
function aiLdSnippet(q) {
    const t = String(q || '').replace(/\[\[IMG_[0-9a-z]+\]\]/gi, ' [gambar] ').replace(/\[\[TABLE_\d+\]\]/g, ' [tabel] ')
        .replace(/<[^>]*>/g, ' ').replace(/^\s*\d{1,3}[.)]\s*/, '').replace(/\s+/g, ' ').trim();
    return t.length > 110 ? t.slice(0, 110) + '…' : t;
}
function stopAILoader() { if (AI_LOAD) { clearInterval(AI_LOAD.timer); AI_LOAD = null; } }

function aiLdBuild(container) {
    stopAILoader();
    container.innerHTML = `
        <div class="ai-ld" id="aiLoader">
            <div class="ai-ld-head">
                <div class="ai-ld-orb"><i class="fas fa-brain"></i></div>
                <div class="ai-ld-title"><b id="aiLdTitle">Mengonversi soal…</b><span id="aiLdMsg"></span></div>
                <div class="ai-ld-pct"><span id="aiLdPct">0</span><small>%</small></div>
            </div>
            <div class="ai-ld-bar"><span id="aiLdFill"></span></div>
            <div class="ai-ld-meta">
                <span><i class="far fa-clock mr-1"></i>Berjalan <b id="aiLdEl">0:00</b></span>
                <span>Perkiraan sisa <b id="aiLdEta">menghitung…</b></span>
                <span id="aiLdCnt"></span>
            </div>
            <div class="ai-ld-steps" id="aiLdSteps"></div>
            <div class="ai-ld-chips" id="aiLdChips"></div>
            <div id="aiLdQueue"></div>
            <div class="ai-ld-foot"><i class="fas fa-lock mr-1"></i>Mohon jangan tutup halaman. Kunci jawaban dianalisis lewat Multi-AI Engine.</div>
        </div>`;
    AI_LOAD = { t0: Date.now(), shown: 0, floor: 0, cap: 0, done: false, timer: null };
    AI_LOAD.timer = setInterval(() => {
        const L = AI_LOAD, pct = document.getElementById('aiLdPct');
        if (!L || !pct || !document.getElementById('aiLoader')) { stopAILoader(); return; }
        const target = L.done ? 100 : L.cap;
        L.shown += (target - L.shown) * (L.done ? 0.35 : 0.04);
        if (target - L.shown < 0.05) L.shown = target;
        const v = Math.min(100, Math.max(L.shown, L.floor));
        pct.textContent = Math.floor(v);
        document.getElementById('aiLdFill').style.width = v + '%';
        const el = (Date.now() - L.t0) / 1000;
        document.getElementById('aiLdEl').textContent = aiLdFmt(el);
        const eta = document.getElementById('aiLdEta');
        if (L.done) eta.textContent = 'selesai';
        else if (el > 4 && L.floor > 0) eta.textContent = '± ' + aiLdFmt(el / L.floor * (100 - L.floor));
        else eta.textContent = 'menghitung…';
    }, 200);
}

function updateAIProgress(percent, message) {
    const container = document.getElementById('previewRenderArea');
    percent = Math.max(0, Math.min(100, Number(percent) || 0));
    const m = String(message || '').match(/No\.?\s*(\d+)\s*s\.?\s?d\.?\s*(\d+)\s*dari\s*(\d+)/i);
    const total = m ? +m[3] : (parsedData ? parsedData.length : 0);
    const start = m ? +m[1] : 0, end = m ? +m[2] : 0;
    const done = percent >= 100;
    if (!AI_LOAD || !document.getElementById('aiLoader')) aiLdBuild(container);
    const L = AI_LOAD;
    L.done = done;
    L.floor = Math.max(L.floor, percent);
    const share = (total && end >= start && start > 0) ? ((end - start + 1) / total) * 100 : 0;
    L.cap = done ? 100 : Math.min(99, percent + share * 0.9);   // bar merayap pelan selama 1 batch diproses

    document.getElementById('aiLdTitle').textContent = done ? 'Konversi selesai' : 'Mengonversi soal…';
    document.getElementById('aiLdMsg').textContent = String(message || '');
    document.getElementById('aiLdCnt').innerHTML = total ? ('<i class="far fa-file-alt mr-1"></i>Total <b>' + total + '</b> soal') : '';

    // langkah proses
    const steps = [
        ['fa-search', 'Soal terbaca', 'done'],
        ['fa-robot', 'Analisis AI & kunci jawaban', done ? 'done' : 'active'],
        ['fa-magic', 'Menyusun Preview', done ? 'active' : '']
    ];
    document.getElementById('aiLdSteps').innerHTML = steps.map(st =>
        '<div class="ai-ld-step ' + st[2] + '"><i class="fas ' + (st[2] === 'done' ? 'fa-check-circle' : st[2] === 'active' ? st[0] + ' fa-beat-fade' : st[0]) + '"></i><span>' + st[1] + '</span></div>').join('');

    // chip per batch (10 soal)
    const batch = (m && end >= start) ? (end - start + 1) : 10;
    const chips = [];
    if (total) {
        for (let a = 1; a <= total; a += 10) {
            const z = Math.min(a + 9, total);
            const cls = done || z < start ? 'done' : (a >= start && a <= end ? 'active' : '');
            chips.push('<span class="ai-ld-chip ' + cls + '">' + (cls === 'done' ? '✓ ' : '') + a + '–' + z + '</span>');
        }
    }
    document.getElementById('aiLdChips').innerHTML = chips.join('');

    // antrean soal sungguhan yang sedang dianalisis (maks 3), tampilan mirip kartu Preview
    let q = '';
    if (!done && start > 0 && Array.isArray(parsedData)) {
        const show = Math.min(3, end - start + 1);
        for (let k = 0; k < show; k++) {
            const it = parsedData[start - 1 + k];
            if (!it) continue;
            const nOpt = Array.isArray(it.options) ? Math.min(it.options.length, 4) : 0;
            let sk = '';
            for (let o = 0; o < nOpt; o++) sk += '<div class="ai-ld-sk"><i>' + String.fromCharCode(65 + o) + '.</i><em style="width:' + (38 + ((o * 17 + k * 11) % 45)) + '%"></em></div>';
            q += '<div class="ai-ld-q ' + (k === 0 ? '' : 'wait') + '"><div class="ai-ld-qh"><span class="no">No. ' + (start + k) + '</span>' +
                '<span class="st">' + (k === 0 ? 'dianalisis…' : 'antre') + '</span></div>' +
                '<div class="ai-ld-qt">' + escapeHtml(aiLdSnippet(it.question)) + '</div>' + sk + '</div>';
        }
        if (end - start + 1 > show) q += '<div class="ai-ld-foot" style="margin:0 0 4px">+ ' + (end - start + 1 - show) + ' soal lain dalam batch ini</div>';
    }
    document.getElementById('aiLdQueue').innerHTML = q;
}

async function smartApiError(res, provider, model){
    let body='';
    try{ body=await res.text(); }catch(_){}
    let message=`${provider} ${model} Error HTTP ${res.status}`;
    try{
        const j=JSON.parse(body);
        message=j?.error?.message||j?.message||message;
    }catch(_){}
    const err=new Error(message);
    err.httpStatus=res.status;
    err.body=body;
    throw err;
}

async function callGeminiApi(apiKey, model, promptText) {
    let endpoint = `https://generativelanguage.googleapis.com/v1beta/models/${model}:generateContent?key=${apiKey}`;
    let res = await fetch(endpoint, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            contents: [{ parts: [{ text: promptText }] }],
            generationConfig: { temperature: 0.1, responseMimeType: "application/json" }
        })
    });
    if (!res.ok) await smartApiError(res, "Google Gemini", model);
    let data = await res.json();
    return data.candidates?.[0]?.content?.parts?.[0]?.text;
}

async function callOpenAiApi(apiKey, model, promptText) {
    let res = await fetch("https://api.openai.com/v1/chat/completions", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Authorization": `Bearer ${apiKey}`
        },
        body: JSON.stringify({
            model: model,
            messages: [{ role: "user", content: promptText }],
            temperature: 0.1,
            response_format: { type: "json_object" }
        })
    });
    if (!res.ok) await smartApiError(res, "OpenAI", model);
    let data = await res.json();
    return data.choices?.[0]?.message?.content;
}

async function callGroqApi(apiKey, model, promptText) {
    let res = await fetch("https://api.groq.com/openai/v1/chat/completions", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Authorization": `Bearer ${apiKey}`
        },
        body: JSON.stringify({
            model: model,
            messages: [{ role: "user", content: promptText }],
            temperature: 0.1,
            response_format: { type: "json_object" }
        })
    });
    if (!res.ok) await smartApiError(res, "Groq", model);
    let data = await res.json();
    return data.choices?.[0]?.message?.content;
}

async function callPollinationsApi(apiKey, model, promptText) {
    const selectedModel = model || "openai";
    let res = await fetch("https://gen.pollinations.ai/v1/chat/completions", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Authorization": `Bearer ${apiKey}`
        },
        body: JSON.stringify({
            model: selectedModel,
            messages: [
                { role: "system", content: "You are a helpful assistant that outputs strict JSON." },
                { role: "user", content: promptText }
            ],
            temperature: 0
        })
    });
    if (!res.ok) await smartApiError(res, "Pollinations.ai", selectedModel);
    let data = await res.json();
    return data.choices?.[0]?.message?.content || data.output_text || data.text || null;
}

async function callOpenRouterApi(apiKey, model, promptText) {
    let res = await fetch("https://openrouter.ai/api/v1/chat/completions", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Authorization": `Bearer ${apiKey}`
        },
        body: JSON.stringify({
            model: model,
            messages: [{ role: "user", content: promptText }],
            temperature: 0.1
        })
    });
    if (!res.ok) await smartApiError(res, "OpenRouter", model);
    let data = await res.json();
    return data.choices?.[0]?.message?.content;
}



function parseAiJsonSafe(text) {
    if (typeof text !== 'string') return text;
    text = text.replace(/```json|```/g, '').trim();
    const start = text.search(/[\[{]/);
    const end = Math.max(text.lastIndexOf('}'), text.lastIndexOf(']'));
    if (start === -1 || end === -1) throw new Error('JSON tidak ditemukan');
    text = text.slice(start, end + 1);

    try {
        return JSON.parse(text);
    } catch (e1) {
        // coba tutup JSON yang terpotong
        const tries = [text + '}]', text + ']', text + '"}]'];
        const lastObj = text.lastIndexOf('},');
        if (lastObj > -1) tries.push(text.slice(0, lastObj + 1) + ']');
        for (const t of tries) {
            try { return JSON.parse(t); } catch (e) {}
        }
        throw e1;
    }
}
async function prosesDanRapikanDenganAI() {
    const container = document.getElementById('inputText');
    if (!parsedData || parsedData.length === 0) parseLocalInstantly(container.innerText);
    if (parsedData.length === 0) return alert('Tidak ada soal yang terdeteksi.');

    let providersChain = [];
    try {
        let chainStr = smartChain() || SERVER_KEYS.chain || localStorage.getItem('AI_PROVIDER_CHAIN') || '["gemini","openai","groq","pollinations","openrouter"]';
        providersChain = typeof chainStr === 'string' ? JSON.parse(chainStr) : chainStr;
    } catch(e) {
        providersChain = ["gemini","openai","groq","pollinations","openrouter"];
    }

    let geminiKeys = getApiKeys('gemini');
    let openaiKeys = getApiKeys('openai');
    let groqKeys = getApiKeys('groq');
    let pollinationsKeys = getApiKeys('pollinations');
    let openRouterKeys = getApiKeys('openrouter');
    

    if (geminiKeys.length === 0 && openaiKeys.length === 0 && groqKeys.length === 0 && pollinationsKeys.length === 0 && openRouterKeys.length === 0) {
        alert("Harap masukkan minimal 1 API Key di menu Pengaturan!");
        openApiKeyModal();
        return;
    }

    const cached = await lookupConverterCache(true);
    if (cached) {
        alert(`Hasil ${parsedData.length} soal ditemukan di cache. Tidak ada request AI baru.`);
        return;
    }

    activeQuestionFingerprint = await getQuestionFingerprint(parsedData);
    setCacheStatus('<i class="fas fa-sync-alt fa-spin mr-1"></i> Belum ada cache. Menyiapkan proses AI...', 'info');

    const btn = document.getElementById('btnAiAnswer');
    btn.disabled = true;
    btn.innerHTML = `<i class="fas fa-spinner fa-spin mr-2"></i> Menganalisis via Multi-AI Engine...`;

    const totalSoal = parsedData.length;
    const batchSize = 10; 
    let hasilAI = [];

    const delay = ms => new Promise(res => setTimeout(res, ms));

    try {
        for (let i = 0; i < totalSoal; i += batchSize) {
            let chunkSoal = parsedData.slice(i, i + batchSize);
            let startNo = i + 1;
            let endNo = Math.min(i + batchSize, totalSoal);
            let percent = Math.round((i / totalSoal) * 100);

            updateAIProgress(percent, `Memproses Soal No. ${startNo} s.d ${endNo} dari ${totalSoal}...`);

            let promptText = `Kamu adalah sistem pemroses bank soal. Analisis dan formatkan potongan soal berikut menjadi JSON Array murni. Jika dalam soal terdapat struktur tabel, sertakan struktur tabel tersebut dalam tag HTML <table>...</table> yang bersih di dalam string "question".

Potongan Soal (No ${startNo} - ${endNo}):${JSON.stringify(chunkSoal.map(q => ({...q, question: restoreEditorTablesRaw(q.question)})), null, 2)}

ATURAN KETAT OUTPUT:
1. Kembalikan HANYA JSON Array murni [ ... ].
2. JANGAN tambahkan teks pembuka/penutup, markdown (\`\`\`json), atau keterangan apa pun.
3. Tentukan jenis soal (1=PG Biasa, 2=PG Kompleks, 3=Menjodohkan, 4=Isian, 5=Uraian).
4. Berikan kunci jawaban yang BENAR di field "key" HANYA berupa HURUF KAPITAL TUNGGAL dalam array. Contoh: ["A"] atau ["B"].
5. JANGAN sertakan nomor urut soal di dalam teks "question" (hilangkan "1.", "2.", dst).
6. Pada "options", tuliskan HANYA OPSI YANG ADA SESUAI SOAL ASLI. JANGAN menambahkan opsi jika tidak ada di soal asli!
7. Tuliskan ISI OPSINYA SAJA tanpa huruf "A.", "B.", dst.
8. Pastikan SEMUA teks bersih dari spasi berlebih, spasi tak terlihat (nbsp), dan tab.
9. Token gambar berbentuk [[IMG_xxxxxxxx]] WAJIB dipertahankan PERSIS (jangan diubah, dihapus, dijelaskan, atau dipindah) pada field "question" atau "options" tempat token itu semula berada.
10. Pada "question", PERTAHANKAN pemisah baris (tulis sebagai \\n di JSON) untuk daftar pernyataan/data bernomor seperti "1) ...", "(1) ... (4) ...". Jangan digabung jadi satu baris dan jangan dipindahkan ke "options".`;

            let rawString = null;
            let batchSuccess = false;

            for (let prov of providersChain) {
                if (batchSuccess) break;

                if (prov === 'gemini' && geminiKeys.length > 0) {
                    let models = smartModels('gemini', ["gemini-2.5-flash", "gemini-2.5-pro"]);
                    for (let key of geminiKeys) {
                        if (batchSuccess) break;
                        for (let mod of models) {
                            try {
                                rawString = await smartCall('gemini', key, mod, promptText, true);
                                if (rawString) { batchSuccess = true; break; }
                            } catch (e) {
                                console.warn(`Gemini API/Model ${mod} gagal/limit:`, e.message);
                            }
                        }
                    }
                }
                else if (prov === 'openai' && openaiKeys.length > 0) {
                    let models = smartModels('openai', ["gpt-4o-mini", "gpt-4o"]);
                    for (let key of openaiKeys) {
                        if (batchSuccess) break;
                        for (let mod of models) {
                            try {
                                rawString = await smartCall('openai', key, mod, promptText, true);
                                if (rawString) { batchSuccess = true; break; }
                            } catch (e) {
                                console.warn(`OpenAI Model ${mod} gagal/limit:`, e.message);
                            }
                        }
                    }
                } 
                else if (prov === 'groq' && groqKeys.length > 0) {
                    let models = smartModels('groq', ["llama-3.3-70b-versatile", "llama-3.1-8b-instant"]);
                    for (let key of groqKeys) {
                        if (batchSuccess) break;
                        for (let mod of models) {
                            try {
                                rawString = await smartCall('groq', key, mod, promptText, true);
                                if (rawString) { batchSuccess = true; break; }
                            } catch (e) {
                                console.warn(`Groq Model ${mod} gagal/limit:`, e.message);
                            }
                        }
                    }
                }
                else if (prov === 'pollinations' && pollinationsKeys.length > 0) {
                    let models = smartModels('pollinations', ["openai", "openai-fast", "deepseek"]);
                    for (let key of pollinationsKeys) {
                        if (batchSuccess) break;
                        for (let mod of models) {
                            try {
                                rawString = await smartCall('pollinations', key, mod, promptText, true);
                                if (rawString) { batchSuccess = true; break; }
                            } catch (e) {
                                console.warn(`Pollinations.ai Model ${mod} gagal/limit:`, e.message);
                            }
                        }
                    }
                }
                else if (prov === 'openrouter' && openRouterKeys.length > 0) {
                    let models = smartModels('openrouter', ["deepseek/deepseek-chat", "meta-llama/llama-3.3-70b-instruct"]);
                    for (let key of openRouterKeys) {
                        if (batchSuccess) break;
                        for (let mod of models) {
                            try {
                                rawString = await smartCall('openrouter', key, mod, promptText, true);
                                if (rawString) { batchSuccess = true; break; }
                            } catch (e) {
                                console.warn(`OpenRouter Model ${mod} gagal/limit:`, e.message);
                            }
                        }
                    }
                }
                
            }

            if (!batchSuccess || !rawString) {
                throw new Error(`Semua Provider & API Key yang dikonfigurasi mengalami limit atau error pada soal No ${startNo}-${endNo}. Silakan periksa kembali API Key Anda.`);
            }

            let firstBracket = rawString.indexOf('[');
            let lastBracket = rawString.lastIndexOf(']');

            if (firstBracket === -1 || lastBracket === -1) {
                throw new Error(`Format JSON tidak valid pada No. ${startNo}-${endNo}`);
            }

            let cleanJsonString = rawString.substring(firstBracket, lastBracket + 1);
			console.log('RAW AI:', cleanJsonString);
            hasilAI = hasilAI.concat(restoreMissingImageTokens(chunkSoal, parseAiJsonSafe(cleanJsonString)));

            if (i + batchSize < totalSoal) {
                await delay(1000);
            }
        }

        updateAIProgress(100, `Selesai! Berhasil menganalisis seluruh ${totalSoal} soal.`);

        // Normalisasi hasil AI, termasuk membersihkan angka batch yang salah
        // menempel di belakang opsi D sesuai nomor soal.
        parsedData = normalizeAiResult(hasilAI);
        isAiProcessed = true;
        cacheLoaded = true;
        

        await saveConverterCache(activeQuestionFingerprint, parsedData);

        setTimeout(() => {
            renderPreview();
            alert(`Selesai! Berhasil memproses ${parsedData.length} soal menggunakan Rotasi Multi-AI Engine.`);
        }, 400);

    } catch (err) {
        console.error(err);
        alert('Gagal memproses soal: ' + err.message);
        renderPreview();
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-robot mr-2"></i> Jalankan AI Generator & Kunci Jawaban Otomatis ✨';
        setTimeout(smartRefresh, 900);   // perbarui bar persen limit setelah request tercatat
    }
}

function updateCountBadge(badge, total, verified) {
    if (!badge) return;
    let state = total === 0 ? 'idle' : (verified ? 'ok' : 'draft');
    let icon  = total === 0 ? 'fa-search' : (verified ? 'fa-check' : 'fa-bolt');
    let label = verified && total > 0 ? 'Soal Terverifikasi' : 'Soal Terdeteksi';
    let sub   = total === 0 ? 'MENUNGGU INPUT' : (verified ? 'AI VERIFIED' : 'DRAFT');
    let prev  = parseInt(badge.dataset.count || '0', 10) || 0;
    let wasState = badge.dataset.state || '';

    badge.className = 'count-pill ' + state;
    badge.innerHTML = '<span class="cp-ico"><i class="fas ' + icon + '"></i></span>'
        + '<span class="cp-num">' + prev + '</span>'
        + '<span class="cp-txt"><b>' + label + '</b><small>' + sub + '</small></span>';
    badge.dataset.count = String(total);
    badge.dataset.state = state;

    const numEl = badge.querySelector('.cp-num');
    if (prev !== total) {
        const t0 = performance.now(), dur = 450;
        (function tick(now) {
            const k = Math.min(1, (now - t0) / dur);
            numEl.textContent = Math.round(prev + (total - prev) * k);
            if (k < 1) requestAnimationFrame(tick);
        })(t0);
        badge.classList.remove('pop'); void badge.offsetWidth; badge.classList.add('pop');
    } else {
        numEl.textContent = total;
    }
}

// =========================================================================
// FORMAT MATEMATIKA DI PREVIEW (pecahan, pecahan campuran, ×, pangkat, akar)
//  - 1/3        -> pecahan bertingkat
//  - 1 3/5      -> pecahan campuran (bilangan bulat + pecahan)
//  - \frac{3}{5}-> pecahan gaya LaTeX
//  - 15 X 1/3   -> 15 × 1/3
//  - cm2, m3    -> cm², m³ ;  x^2 -> x² ;  sqrt(25) -> √25
// Hanya teks di LUAR tag HTML yang diubah, jadi tabel, <img>, dan token
// [[IMG_..]] / [[TABLE_..]] tidak tersentuh. Tanggal (12/10/2024) dibiarkan.
// Hanya mengubah TAMPILAN preview; data soal & file Word tidak berubah.
// =========================================================================
function ensureMathStyle() {
    if (document.getElementById('mfMathStyle')) return;
    const st = document.createElement('style');
    st.id = 'mfMathStyle';
    st.textContent =
        '.mf-frac{display:inline-block;vertical-align:middle;text-align:center;margin:0 3px;line-height:1.15;font-size:.95em}' +
        '.mf-frac .mf-n{display:block;padding:0 3px;border-bottom:1.5px solid currentColor}' +
        '.mf-frac .mf-d{display:block;padding:0 3px}' +
        '.mf-mixed{display:inline-flex;align-items:center;gap:2px;vertical-align:middle}' +
        '.imported-equation{display:inline-block;vertical-align:middle;margin:0 3px;cursor:pointer;padding:1px 4px;border:1px dashed transparent;border-radius:5px;user-select:none}' +
        '.imported-equation:hover{border-color:rgba(0,255,156,.65);background:rgba(0,255,156,.07)}' +
        '.imported-equation.editing{border-color:#00ff9c;background:rgba(0,255,156,.10);box-shadow:0 0 10px rgba(0,255,156,.14)}' +
        '.imported-equation mjx-container{margin:0 !important;font-size:145% !important}.imported-equation mjx-mfrac{font-size:1.2em}.imported-equation mjx-mfrac mjx-num,.imported-equation mjx-mfrac mjx-den{font-size:1.08em}.imported-equation svg{height:1.65em !important;max-height:none !important}' +
        '.eq-inline-editor{position:absolute;z-index:100000;display:none;width:min(430px,calc(100vw - 30px));padding:9px;background:#071b18;border:1px solid #00ff9c;border-radius:9px;box-shadow:0 8px 28px rgba(0,0,0,.55),0 0 18px rgba(0,255,156,.14);color:#ecfffa}' +
        '.eq-inline-editor .eq-inline-title{font-size:12px;font-weight:800;color:#00ff9c;margin-bottom:6px}' +
        '.eq-inline-editor textarea{width:100%;min-height:64px;resize:vertical;background:#020b0a;color:#fff;border:1px solid rgba(0,255,156,.45);border-radius:6px;padding:7px 9px;font-family:Consolas,monospace;font-size:16px;line-height:1.4;outline:none}' +
        '.eq-inline-editor textarea:focus{border-color:#00ff9c;box-shadow:0 0 0 2px rgba(0,255,156,.10)}' +
        '.eq-inline-preview{min-height:42px;margin:6px 0;padding:5px 8px;background:#020b0a;border-radius:6px;text-align:center;overflow:auto;font-size:24px;line-height:1.6}' +
        '.eq-inline-actions{display:flex;gap:5px;align-items:center;justify-content:flex-end;margin-top:6px;flex-wrap:wrap}' +
        '.eq-inline-actions button{border:1px solid rgba(0,255,156,.55);background:#09231f;color:#ecfffa;border-radius:5px;padding:4px 9px;font-size:11px;cursor:pointer}' +
        '.eq-inline-actions button.primary{background:#00e5a0;color:#00130e;font-weight:800}' +
        '.eq-inline-help{font-size:10px;color:#8ab7ae;margin-right:auto}';
    document.head.appendChild(st);
}

function mfFrac(n, d, whole) {
    const f = '<span class="mf-frac"><span class="mf-n">' + n + '</span><span class="mf-d">' + d + '</span></span>';
    return whole ? '<span class="mf-mixed"><span class="mf-whole">' + whole + '</span>' + f + '</span>' : f;
}

function readDisplayLatexGroup(str, pos) {
    while (pos < str.length && /\s/.test(str[pos])) pos++;
    if (str[pos] !== '{') return null;
    let start = ++pos, depth = 1;
    while (pos < str.length && depth) {
        if (str[pos] === '{') depth++;
        else if (str[pos] === '}') depth--;
        pos++;
    }
    if (depth !== 0) return null;
    return { value: str.slice(start, pos - 1), pos };
}

function replaceDisplayLatexFractions(t) {
    let out = '', i = 0;
    while (i < t.length) {
        if (t.startsWith('\\frac', i)) {
            const a = readDisplayLatexGroup(t, i + 5);
            if (a) {
                const b = readDisplayLatexGroup(t, a.pos);
                if (b) {
                    out += mfFrac(replaceDisplayLatexFractions(a.value), replaceDisplayLatexFractions(b.value));
                    i = b.pos;
                    continue;
                }
            }
        }
        if (t.startsWith('\\sqrt', i)) {
            const a = readDisplayLatexGroup(t, i + 5);
            if (a) {
                out += '√' + replaceDisplayLatexFractions(a.value);
                i = a.pos;
                continue;
            }
        }
        out += t[i++];
    }
    return out;
}

function mathFormatText(t) {
    if (!t || !/[\d\\\^]|sqrt/i.test(t)) return t;

    // Normalisasi backslash ganda yang kadang muncul dari JSON/AI.
    t = String(t).replace(/\\\\(?=(?:frac|sqrt|times|cdot|div|le|ge|neq|pm|pi|alpha|beta|gamma|theta|mu|sigma|lambda)\b)/g, '\\');

    // tanda kali: "15 X 1/3", "2 x 3"
    t = t.replace(/(?<=\d)[ \t]+[xX][ \t]+(?=\d)/g, ' \u00D7 ');

    // Pecahan/akar LaTeX dengan kurung bertingkat.
    t = replaceDisplayLatexFractions(t);

    t = t.replace(/\\(times|cdot|div|le|ge|neq|pm|pi|alpha|beta|gamma|theta|mu|sigma|lambda)\b/g, (m, c) => ({times:'×',cdot:'·',div:'÷',le:'≤',ge:'≥',neq:'≠',pm:'±',pi:'π',alpha:'α',beta:'β',gamma:'γ',theta:'θ',mu:'μ',sigma:'σ',lambda:'λ'})[c] || c);

    // pecahan biasa / campuran (tidak menyentuh 12/10/2024)
    t = t.replace(/(?<![\d\/])(?:(\d+)[ \t]+)?(\d+)[ \t]*\/[ \t]*(\d+)(?![\d\/])/g,
        (m, w, n, d) => mfFrac(n, d, w));

    // satuan berpangkat: cm2 -> cm², m3 -> m³
    t = t.replace(/\b(mm|cm|dm|km|m)([23])\b/g, '$1<sup>$2</sup>');

    // pangkat: x^2, 10^3
    t = t.replace(/(\w)\^(\d+)/g, '$1<sup>$2</sup>');

    // akar: sqrt(25)
    t = t.replace(/sqrt\((\d+)\)/gi, '\u221A$1');

    return t;
}

// html = string HTML; hanya potongan teks di luar tag yang diformat.
function mathFormatHtml(html) {
    ensureMathStyle();
    return String(html || '').split(/(<[^>]*>)/).map((seg, i) => (i % 2 ? seg : mathFormatText(seg))).join('');
}


/* ========================================================================
   OFFLINE DOCX EXPORT FALLBACK
   Creates a standards-compatible DOCX without docx.js/FileSaver. The normal
   docx.js path is untouched; this is used only when CDN libraries are absent.
   Fractions/square-roots are written as real OMML Word Equations.
   ======================================================================== */
function offlineXmlEsc(v){ return String(v??'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&apos;'); }
function offlineBytesFromDataUri(uri){
    const m=String(uri||'').match(/^data:([^;,]+);base64,(.*)$/s); if(!m) return null;
    const bin=atob(m[2]); const out=new Uint8Array(bin.length); for(let i=0;i<bin.length;i++) out[i]=bin.charCodeAt(i); return {mime:m[1],bytes:out};
}
function offlineOmmlXml(latex){
    try{
        const d=document.implementation.createDocument(null,null);
        const om=latexToOmml(d,String(latex||''));
        return new XMLSerializer().serializeToString(om);
    }catch(e){ return ''; }
}
function offlineFindGroup(str,pos){
    while(pos<str.length && /\s/.test(str[pos])) pos++;
    if(str[pos]!=='{') return {value:str[pos]||'',pos:pos+1};
    let start=++pos, depth=1;
    while(pos<str.length && depth){ if(str[pos]==='{')depth++; else if(str[pos]==='}')depth--; pos++; }
    return {value:str.slice(start,pos-1),pos};
}
function offlineMathSegments(text){
    const s=String(text||''), out=[]; let i=0, last=0;
    function pushText(a,b){ if(b>a) out.push({type:'text',value:s.slice(a,b)}); }
    while(i<s.length){
        if(s[i]==='\\' && (s.startsWith('\\frac',i)||s.startsWith('\\sqrt',i))){
            const cmd=s.startsWith('\\frac',i)?'\\frac':'\\sqrt';
            let p=i+cmd.length;
            if(cmd==='\\sqrt' && s[p]==='['){ const q=s.indexOf(']',p); if(q<0){i++;continue;} p=q+1; }
            const a=offlineFindGroup(s,p); if(!a.value && a.pos<=p+1){i++;continue;}
            let end=a.pos;
            if(cmd==='\\frac'){
                const b=offlineFindGroup(s,a.pos); if(b.pos===a.pos){i++;continue;} end=b.pos;
            }
            pushText(last,i); out.push({type:'math',value:s.slice(i,end)}); i=end; last=i; continue;
        }
        i++;
    }
    pushText(last,s.length); return out;
}
function offlineDocxRunXml(text, rels, mediaParts){
    const segs=offlineMathSegments(stripPreviewFormattingMarkers(String(text||'')));
    let xml='';
    for(const seg of segs){
        if(seg.type==='math'){
            const om=offlineOmmlXml(seg.value);
            if(om) xml+=om; else xml+='<w:r><w:t xml:space="preserve">'+offlineXmlEsc(seg.value)+'</w:t></w:r>';
            continue;
        }
        const parts=seg.value.split(/(\[\[IMG_[0-9a-z]+\]\])/g);
        for(const part of parts){
            if(!part) continue;
            const im=part.match(/^\[\[IMG_([0-9a-z]+)\]\]$/);
            if(im && EDITOR_IMAGES[part]){
                const key=part; const existing=mediaParts[key];
                if(existing){
                    const rid=existing.rId;
                    xml+=`<w:r><w:drawing><wp:inline xmlns:wp="http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing"><wp:extent cx="2286000" cy="1524000"/><wp:docPr id="${existing.id}" name="${key}"/><a:graphic xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main"><a:graphicData uri="http://schemas.openxmlformats.org/drawingml/2006/picture"><pic:pic xmlns:pic="http://schemas.openxmlformats.org/drawingml/2006/picture"><pic:nvPicPr><pic:cNvPr id="0" name="${key}"/><pic:cNvPicPr/></pic:nvPicPr><pic:blipFill><a:blip r:embed="${rid}" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"/><a:stretch><a:fillRect/></a:stretch></pic:blipFill><pic:spPr><a:xfrm><a:off x="0" y="0"/><a:ext cx="2286000" cy="1524000"/></a:xfrm><a:prstGeom prst="rect"><a:avLst/></a:prstGeom></pic:spPr></pic:pic></a:graphicData></a:graphic></wp:inline></w:drawing></w:r>`;
                }
            } else {
                const lines=part.split('\\n');
                lines.forEach((ln,idx)=>{
                    if(idx) xml+='<w:r><w:br/></w:r>';
                    if(ln) xml+='<w:r><w:t xml:space="preserve">'+offlineXmlEsc(ln)+'</w:t></w:r>';
                });
            }
        }
    }
    if(!xml) xml='<w:r><w:t xml:space="preserve"></w:t></w:r>';
    return xml;
}
function offlineCrc32(bytes){
    let c=0xffffffff; for(let i=0;i<bytes.length;i++){ c^=bytes[i]; for(let k=0;k<8;k++) c=(c>>>1)^((c&1)?0xedb88320:0); } return (c^0xffffffff)>>>0;
}
function offlineZipStore(files){
    const enc=new TextEncoder(), chunks=[], central=[]; let offset=0;
    function u16(n){const a=new Uint8Array(2);a[0]=n&255;a[1]=(n>>>8)&255;return a}
    function u32(n){const a=new Uint8Array(4);a[0]=n&255;a[1]=(n>>>8)&255;a[2]=(n>>>16)&255;a[3]=(n>>>24)&255;return a}
    function cat(...arrs){let n=arrs.reduce((a,x)=>a+x.length,0),o=new Uint8Array(n),p=0;arrs.forEach(x=>{o.set(x,p);p+=x.length});return o}
    for(const f of files){
        const name=enc.encode(f.name), data=f.bytes instanceof Uint8Array?f.bytes:enc.encode(String(f.bytes||'')), crc=offlineCrc32(data), start=offset;
        const local=cat(u32(0x04034b50),u16(20),u16(0),u16(0),u16(0),u16(0),u32(crc),u32(data.length),u32(data.length),u16(name.length),u16(0),name,data);
        chunks.push(local); offset+=local.length;
        const cd=cat(u32(0x02014b50),u16(20),u16(20),u16(0),u16(0),u16(0),u16(0),u32(crc),u32(data.length),u32(data.length),u16(name.length),u16(0),u16(0),u16(0),u16(0),u32(0),u32(start),name);
        central.push(cd);
    }
    const cdStart=offset, cdBlob=cat(...central); offset+=cdBlob.length;
    const end=cat(u32(0x06054b50),u16(0),u16(0),u16(files.length),u16(files.length),u32(cdBlob.length),u32(cdStart),u16(0));
    return cat(...chunks,cdBlob,end);
}
function offlineBuildDocx(){
    const rels=[]; const mediaParts={}; let mediaIndex=0, ridIndex=1;
    const relsXml=[];
    const paragraphs=[];
    function addTextParagraph(text){ paragraphs.push('<w:p>'+offlineDocxRunXml(text,relsXml,mediaParts)+'</w:p>'); }
    (parsedData||[]).forEach((item,qi)=>{
        addTextParagraph(String(item.question||''));
        (Array.isArray(item.options)?item.options:[]).forEach((opt,oi)=>addTextParagraph(String.fromCharCode(65+oi)+'. '+String(opt||'')));
    });
    // Collect images after building runs so each token gets a stable relationship/media part.
    Object.keys(EDITOR_IMAGES||{}).forEach(token=>{
        const raw=offlineBytesFromDataUri(EDITOR_IMAGES[token]); if(!raw) return;
        mediaIndex++; ridIndex++;
        mediaParts[token]={rId:'rId'+ridIndex,id:mediaIndex,path:'word/media/image'+mediaIndex+'.'+(raw.mime==='image/jpeg'?'jpg':raw.mime==='image/gif'?'gif':'png'),bytes:raw.bytes};
        relsXml.push(`<Relationship Id="rId${ridIndex}" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image" Target="media/image${mediaIndex}.${raw.mime==='image/jpeg'?'jpg':raw.mime==='image/gif'?'gif':'png'}"/>`);
    });
    // Rebuild paragraphs now that mediaParts has been assigned.
    paragraphs.length=0;
    (parsedData||[]).forEach(item=>{
        addTextParagraph(String(item.question||''));
        (Array.isArray(item.options)?item.options:[]).forEach((opt,oi)=>addTextParagraph(String.fromCharCode(65+oi)+'. '+String(opt||'')));
    });
    const documentXml=`<?xml version="1.0" encoding="UTF-8" standalone="yes"?><w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main" xmlns:m="http://schemas.openxmlformats.org/officeDocument/2006/math"><w:body>${paragraphs.join('')}<w:sectPr><w:pgSz w:w="16838" w:h="11906"/><w:pgMar w:top="1000" w:right="1000" w:bottom="1000" w:left="1000"/><w:cols w:space="720"/></w:sectPr></w:body></w:document>`;
    const files=[
        {name:'[Content_Types].xml',bytes:`<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/><Default Extension="xml" ContentType="application/xml"/><Default Extension="png" ContentType="image/png"/><Default Extension="jpg" ContentType="image/jpeg"/><Default Extension="gif" ContentType="image/gif"/><Override PartName="/word/document.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml"/><Override PartName="/word/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.styles+xml"/></Types>`},
        {name:'_rels/.rels',bytes:`<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="word/document.xml"/></Relationships>`},
        {name:'word/document.xml',bytes:documentXml},
        {name:'word/_rels/document.xml.rels',bytes:`<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">${relsXml.join('')}</Relationships>`},
        {name:'word/styles.xml',bytes:`<?xml version="1.0" encoding="UTF-8" standalone="yes"?><w:styles xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"><w:docDefaults><w:rPrDefault><w:rPr><w:rFonts w:ascii="Calibri" w:hAnsi="Calibri"/><w:sz w:val="24"/></w:rPr></w:rPrDefault></w:docDefaults></w:styles>`}
    ];
    Object.values(mediaParts).forEach(m=>files.push({name:m.path,bytes:m.bytes}));
    return offlineZipStore(files);
}
async function convertToWordOfflineFallback(){
    if(!parsedData || !parsedData.length){alert('Belum ada soal untuk diunduh!');return;}
    try{
        const bytes=offlineBuildDocx();
        const blob=new Blob([bytes],{type:'application/vnd.openxmlformats-officedocument.wordprocessingml.document'});
        const a=document.createElement('a'); a.href=URL.createObjectURL(blob); a.download='Soal_Converter_Offline.docx'; document.body.appendChild(a); a.click(); setTimeout(()=>{URL.revokeObjectURL(a.href);a.remove();},1500);
    }catch(e){ alert('Gagal membuat Word offline: '+e.message); }
}

function renderPreview() {
    const container = document.getElementById('previewRenderArea');
    const badge = document.getElementById('countBadge');

    updateCountBadge(badge, parsedData.length, isAiProcessed);

    if (parsedData.length === 0) {
        container.innerHTML = '<div class="text-center text-muted py-5"><i class="fas fa-magic fa-3x mb-3 text-teal d-block"></i>Tempelkan teks bank soal di sebelah kiri atau unggah dokumen.</div>';
        return;
    }

    let html = '';
    const typeBadges = {
        1: '<span class="badge badge-primary badge-type">Pilihan Ganda (PG 1)</span>',
        2: '<span class="badge badge-info badge-type">PG Kompleks (PG 2)</span>',
        3: '<span class="badge badge-warning badge-type text-dark">Menjodohkan</span>',
        4: '<span class="badge badge-success badge-type">Isian Singkat</span>',
        5: '<span class="badge badge-danger badge-type">Uraian / Essai</span>'
    };

    parsedData.forEach((item) => {
        const qNum = String(item.number);
        const attached = QUESTION_ATTACHMENTS[qNum] || [];
        const attachmentTokens = attached.map(a => a.token).join(' ');
        let questionSource = tidyQuestionText(String(item.question || '').replace(/^\d+[\.\)]\s*/, ''));
        if (attachmentTokens) questionSource += (questionSource ? '\n' : '') + attachmentTokens;
        let cleanQuestion = renderPreviewFormatting(mathFormatHtml(restoreEditorTables(questionSource)));

        html += `<div class="card mb-3 shadow-sm border preview-question-card question-image-card" data-question-number="${escapeHtml(qNum)}" tabindex="0">
            <div class="card-header bg-white py-2 px-3 d-flex justify-content-between align-items-center">
                <span class="font-weight-bold text-teal">No. ${item.number}</span>
                <div class="d-flex align-items-center flex-wrap" style="gap:6px">
                    ${isAiProcessed ? (typeBadges[item.jenis] || '') : '<span class="badge badge-secondary badge-type">Drafting</span>'}
                    <label class="btn btn-outline-info btn-sm mb-0 question-image-upload-label" title="Upload gambar khusus untuk soal nomor ${escapeHtml(qNum)}">
                        <i class="fas fa-image mr-1"></i> Gambar (${attached.length}/4)
                        <input type="file" accept="image/*" multiple hidden onchange="uploadQuestionImages('${escapeHtml(qNum)}', this)">
                    </label>
                    <button type="button" class="btn btn-outline-info btn-sm" onclick="focusQuestionImageCard('${escapeHtml(qNum)}')" title="Klik kartu soal ini lalu tekan Ctrl+V"><i class="fas fa-paste mr-1"></i> Tempel</button>
                </div>
            </div>
            <div class="card-body p-3">
                <div class="question-image-paste-hint"><i class="fas fa-info-circle mr-1"></i> Gambar hanya ditambahkan ke soal nomor ${escapeHtml(qNum)}. Klik kartu ini lalu tekan Ctrl+V, atau gunakan tombol Gambar.</div>
                <div class="preview-question font-weight-bold text-dark">${cleanQuestion}</div>`;

        if (Array.isArray(item.options) && item.options.length) {
            html += '<div class="preview-options">';
            item.options.forEach((opt, oIdx) => {
                const char = String.fromCharCode(65 + oIdx);
                const isCorrect = item.key && Array.isArray(item.key) &&
                    item.key.some(k => String(k).trim().toUpperCase() === char);

                let displayOpt = String(opt ?? '').trim();
                if (oIdx === 3) {
                    const safeItem = cleanTrailingBatchMarker({...item, options: [...item.options]});
                    displayOpt = String(safeItem.options?.[3] || '').trim();
                }

                // Hilangkan label A./B./C./D./E. yang ikut masuk ke isi opsi.
                let cleanOptText = renderPreviewFormatting(mathFormatHtml(replaceImageTokens(escapeHtml(displayOpt)
                    .replace(/^[a-eA-E][.)]\s*/u, '')
                    .trim(), true)));

                html += `<div class="preview-option ${isCorrect ? 'text-success font-weight-bold' : 'text-muted'}">
                    <span class="preview-option-label">${char}.</span><span class="preview-option-text">${cleanOptText}</span>
                    ${isCorrect ? '<i class="fas fa-check-circle text-success ml-1"></i> <b>(Kunci AI)</b>' : ''}
                </div>`;
            });
            html += '</div>';
        }
        html += `</div></div>`;
    });

    container.innerHTML = html;
    applyPreviewImageScale(container);
}

// CONVERT TO WORD GARUDA - RESPONSIVE LOADING, LANDSCAPE PERMANEN & TABEL TURUN
async function convertToWordGaruda() {
    if (!parsedData || parsedData.length === 0) {
        alert("Belum ada soal untuk diunduh!");
        return;
    }

    if (typeof docx === 'undefined' || typeof saveAs === 'undefined') {
        // Offline fallback: tetap menghasilkan DOCX lokal tanpa CDN.
        await convertToWordOfflineFallback();
        return;
    }

    const btnDownload = document.getElementById('btnDownloadWord');
    const originalText = btnDownload.innerHTML;
    btnDownload.disabled = true;
    btnDownload.innerHTML = `<i class="fas fa-spinner fa-spin mr-2"></i> Meng-generate File Word...`;

    await new Promise(resolve => setTimeout(resolve, 100));

    try {
        const { Document, Packer, Paragraph, TextRun, ImageRun, Table, TableRow, TableCell, WidthType, ShadingType, BorderStyle, AlignmentType, VerticalAlign, Math: MathEquation, MathRun, MathFraction, MathRadical, MathSuperScript, MathSubScript, MathSubSuperScript } = docx;

        const FONT_NAME = "Calibri";
        // 24 half-points = 12 pt, mengikuti template Word pada screenshot.
        const FONT_SIZE = 24;
        const FONT_SIZE_SMALL = 22;
        const FONT_SIZE_HEADER = 22;

        // Ukuran kolom dibuat mengikuti template: NO | SOAL | JENIS | OPSI | JAWABAN | KUNCI.
        // Lebar konten sengaja lebih besar pada SOAL dan JAWABAN agar tidak mudah pecah baris.
        // Lebar tetap dalam DXA/TWIPS agar Word/WPS dan importer bank soal membaca grid yang sama.
        // Total = 15300 twips, mengisi area tulis landscape A4 (15398 twips) dengan margin aplikasi.
        const TEMPLATE_COLS = { no: 600, soal: 7500, jenis: 800, opsi: 750, jawaban: 4600, kunci: 1050 };

        // Dukungan gambar pada Word; lebar maksimum mengikuti lebar kolom SOAL/JAWABAN.
        let IMG_DOCX = {};
        const DOCX_Q_MAX_W = 480, DOCX_OPT_MAX_W = 280, DOCX_CELL_MAX_W = 220;

        function loadImageForDocx(src) {
            return new Promise(resolve => {
                const im = new Image();
                im.onload = () => {
                    try {
                        const w = im.naturalWidth || im.width, h = im.naturalHeight || im.height;
                        if (!w || !h) return resolve(null);
                        const c = document.createElement('canvas');
                        c.width = w; c.height = h;
                        c.getContext('2d').drawImage(im, 0, 0);
                        const bin = atob(c.toDataURL('image/png').split(',')[1]);
                        const bytes = new Uint8Array(bin.length);
                        for (let k = 0; k < bin.length; k++) bytes[k] = bin.charCodeAt(k);
                        resolve({ data: bytes, w: w, h: h });
                    } catch (e) { resolve(null); }
                };
                im.onerror = () => resolve(null);
                if (/^https?:/i.test(src)) im.crossOrigin = 'anonymous';
                im.src = src;
            });
        }

        async function prepareDocxImages(items) {
            const tokens = new Set();
            (items || []).forEach(it => {
                const all = [String(it.question || '')].concat(Array.isArray(it.options) ? it.options.map(o => String(o || '')) : []);
                all.forEach(t => (t.match(/\[\[IMG_[0-9a-z]+\]\]/g) || []).forEach(x => tokens.add(x)));
            });
            const out = {};
            for (const t of tokens) {
                if (!EDITOR_IMAGES[t]) continue;
                const img = await loadImageForDocx(EDITOR_IMAGES[t]);
                if (img) out[t] = img;
            }
            return out;
        }

        // Teks Word + Equation Word (OMML) secara native.
        // Tidak lagi membuat DOCX lalu membongkar document.xml dengan JSZip.
        // Ini jauh lebih cepat dan menghindari error "too much recursion".
        function parseLatexGroup(str, pos) {
            while (pos < str.length && /\s/.test(str[pos])) pos++;
            if (str[pos] === '{') {
                let i = pos + 1, depth = 1;
                while (i < str.length && depth) {
                    if (str[i] === '{') depth++;
                    else if (str[i] === '}') depth--;
                    i++;
                }
                return { value: str.slice(pos + 1, i - 1), pos: i };
            }
            return { value: str[pos] || '', pos: pos + 1 };
        }

        function latexMathComponents(latex) {
            const s = String(latex || '').trim();
            const components = [];
            let i = 0, text = '';
            const flush = () => { if (text) { components.push(new MathRun(text)); text = ''; } };
            const symbolMap = { alpha:'α', beta:'β', gamma:'γ', delta:'δ', theta:'θ', pi:'π', mu:'μ', sigma:'σ', lambda:'λ', times:'×', cdot:'·', div:'÷', le:'≤', ge:'≥', neq:'≠', pm:'±' };

            while (i < s.length) {
                if (s[i] === '\\') {
                    const m = s.slice(i).match(/^\\([a-zA-Z]+|.)/);
                    if (!m) { text += s[i++]; continue; }
                    const cmd = m[1];
                    if (cmd === 'frac') {
                        const a = parseLatexGroup(s, i + m[0].length);
                        const b = parseLatexGroup(s, a.pos);
                        flush();
                        components.push(new MathFraction({
                            numerator: latexMathComponents(a.value),
                            denominator: latexMathComponents(b.value)
                        }));
                        i = b.pos;
                        continue;
                    }
                    if (cmd === 'sqrt') {
                        const a = parseLatexGroup(s, i + m[0].length);
                        flush();
                        components.push(new MathRadical({ children: latexMathComponents(a.value) }));
                        i = a.pos;
                        continue;
                    }
                    if (cmd === ',' || cmd === ';' || cmd === ':' || cmd === '!') { i += m[0].length; continue; }
                    if (symbolMap[cmd]) { text += symbolMap[cmd]; i += m[0].length; continue; }
                    text += cmd;
                    i += m[0].length;
                    continue;
                }

                // Superscript / subscript, including grouped values.
                if ((s[i] === '^' || s[i] === '_') && components.length) {
                    const type = s[i++];
                    const a = parseLatexGroup(s, i);
                    i = a.pos;
                    const base = components.pop();
                    const exp = latexMathComponents(a.value);
                    if (type === '^') {
                        components.push(new MathSuperScript({ children: [base], superScript: exp }));
                    } else {
                        components.push(new MathSubScript({ children: [base], subScript: exp }));
                    }
                    continue;
                }
                text += s[i++];
            }
            flush();
            return components.length ? components : [new MathRun(s)];
        }

        function makeInlineMath(latex) {
            return new MathEquation({ children: latexMathComponents(latex) });
        }

        function splitTextWithLatex(str) {
            const out = [];
            const source = String(str || '');
            // Rumus utama yang kita dukung: \frac{...}{...}, \sqrt{...}, x^2/x_1.
            const re = /\\(?:frac|sqrt)\s*\{[^{}]*(?:\{[^{}]*\}[^{}]*)*\}(?:\s*\{[^{}]*(?:\{[^{}]*\}[^{}]*)*\})?/g;
            let last = 0, m;
            while ((m = re.exec(source)) !== null) {
                if (m.index > last) out.push(new TextRun({ text: source.slice(last, m.index), font: FONT_NAME, size: FONT_SIZE }));
                out.push(makeInlineMath(m[0]));
                last = m.index + m[0].length;
            }
            if (last < source.length) out.push(new TextRun({ text: source.slice(last), font: FONT_NAME, size: FONT_SIZE }));
            return out;
        }

        function docxRunsFromText(text, maxW) {
            const str = stripPreviewFormattingMarkers(String(text || ''));
            const re = /\[\[IMG_([0-9a-z]+)\]\]/g;
            const runs = [];
            let last = 0, m, afterImg = false;
            const pushText = t => {
                t = String(t || '');
                if (!t.trim()) return;
                if (afterImg) { runs.push(new TextRun({ text: '', break: 1 })); afterImg = false; }
                t.split('\n').forEach((ln, i) => {
                    if (i > 0) runs.push(new TextRun({ text: '', break: 1 }));
                    if (!ln) return;
                    runs.push(...splitTextWithLatex(ln));
                });
            };
            while ((m = re.exec(str)) !== null) {
                pushText(str.slice(last, m.index));
                last = m.index + m[0].length;
                const img = IMG_DOCX['[[IMG_' + m[1] + ']]'];
                if (!img) continue;
                const userSz = EDITOR_IMAGE_SIZES['[[IMG_' + m[1] + ']]'];
                let w = Math.max(1, Math.round(img.w * 0.5));
                let h = Math.max(1, Math.round(img.h * 0.5));
                if (userSz && userSz.w > 0) {
                    w = Math.max(1, Math.round(userSz.w));
                    h = Math.max(1, Math.round(userSz.h > 0 ? userSz.h : userSz.w * img.h / img.w));
                }
                if (w > maxW) { h = Math.max(1, Math.round(h * maxW / w)); w = maxW; }
                const smallInline = (w <= 40 && h <= 40);
                if (!smallInline && runs.length) runs.push(new TextRun({ text: '', break: 1 }));
                runs.push(new ImageRun({ data: img.data, transformation: { width: w, height: h } }));
                afterImg = !smallInline;
            }
            pushText(str.slice(last));
            if (!runs.length) runs.push(new TextRun({ text: '', font: FONT_NAME, size: FONT_SIZE }));
            return runs;
        }

        IMG_DOCX = await prepareDocxImages(parsedData);

        const tableBorders = {
            top: { style: BorderStyle.SINGLE, size: 4, color: "000000" },
            bottom: { style: BorderStyle.SINGLE, size: 4, color: "000000" },
            left: { style: BorderStyle.SINGLE, size: 4, color: "000000" },
            right: { style: BorderStyle.SINGLE, size: 4, color: "000000" },
            insideHorizontal: { style: BorderStyle.SINGLE, size: 4, color: "000000" },
            insideVertical: { style: BorderStyle.SINGLE, size: 4, color: "000000" }
        };

        const tableRows = [];

        // Format sel mengikuti template Word: padding kecil, rata tengah untuk metadata,
        // dan vertical alignment center supaya baris tidak tampak "meloncat".
        const cellMargins = { top: 55, bottom: 55, left: 70, right: 70 };
        const centerParagraph = (children, size = FONT_SIZE) => new Paragraph({
            children, alignment: AlignmentType.CENTER, spacing: { before: 0, after: 0, line: 240 }
        });
        const normalParagraph = (children, opts = {}) => new Paragraph({
            children, alignment: opts.alignment || AlignmentType.LEFT,
            spacing: { before: 0, after: 0, line: opts.line || 240 }
        });
        const baseCell = (children, opts = {}) => new TableCell({
            children,
            borders: tableBorders,
            margins: cellMargins,
            verticalAlign: opts.verticalAlign || VerticalAlign.CENTER,
            width: opts.width,
            rowSpan: opts.rowSpan,
            shading: opts.shading
        });

        // Header Row
        const headerLabels = [
            ["NO", TEMPLATE_COLS.no], ["SOAL", TEMPLATE_COLS.soal], ["JENIS", TEMPLATE_COLS.jenis],
            ["OPSI", TEMPLATE_COLS.opsi], ["JAWABAN", TEMPLATE_COLS.jawaban], ["KUNCI", TEMPLATE_COLS.kunci]
        ];
        const headerRow = new TableRow({
            cantSplit: true,
            children: headerLabels.map(([label, width]) => baseCell([
                centerParagraph([new TextRun({ text: label, bold: true, font: FONT_NAME, size: FONT_SIZE_HEADER })])
            ], { width: { size: width, unit: WidthType.DXA }, shading: { fill: "B4C6E7", type: ShadingType.CLEAR } }))
        });
        tableRows.push(headerRow);

        function parseHtmlTableToDocx(htmlString) {
            let docxElements = [];
            let tempDiv = document.createElement('div');
            tempDiv.innerHTML = htmlString;

            let tableEl = tempDiv.querySelector('table');
            if (tableEl) {
                let clone = tempDiv.cloneNode(true);
                let t = clone.querySelector('table');
                if (t) t.remove();
                let textBefore = clone.innerText.replace(/^\d+[\.\)]\s*/, '').trim();
                if (textBefore) docxElements.push(new Paragraph({ children: docxRunsFromText(textBefore, DOCX_Q_MAX_W) }));

                let innerRows = [];
                let rows = tableEl.querySelectorAll('tr');
                rows.forEach(tr => {
                    let cells = [];
                    let cols = tr.querySelectorAll('th, td');
                    cols.forEach(td => {
                        cells.push(new TableCell({
                            children: [normalParagraph(docxRunsFromText(td.innerText.trim(), DOCX_CELL_MAX_W))],
                            borders: tableBorders, margins: cellMargins, verticalAlign: VerticalAlign.CENTER
                        }));
                    });
                    if (cells.length > 0) innerRows.push(new TableRow({ children: cells }));
                });

                if (innerRows.length > 0) {
                    // Hitung jumlah kolom terbanyak agar grid kolom tidak memakai default 100 twips.
                    let innerMaxCols = 1;
                    rows.forEach(tr => { innerMaxCols = Math.max(innerMaxCols, tr.querySelectorAll('th, td').length); });
                    const innerTotalW = 7200; // lebar kolom SOAL dikurangi margin sel
                    const innerColW = Math.max(400, Math.floor(innerTotalW / innerMaxCols));
                    docxElements.push(new Table({
                        rows: innerRows,
                        width: { size: innerColW * innerMaxCols, unit: WidthType.DXA },
                        columnWidths: Array(innerMaxCols).fill(innerColW),
                        layout: "fixed",
                        borders: tableBorders
                    }));
                }
            } else {
                let cleanText = tempDiv.innerText.replace(/^\d+[\.\)]\s*/, '').trim();
                docxElements.push(new Paragraph({ children: docxRunsFromText(cleanText, DOCX_Q_MAX_W) }));
            }

            return docxElements;
        }

        function buildTemplateSoalChildren(item, cleanQuestionText) {
            // PENTING: jangan membuat tabel bersarang di dalam kolom SOAL.
            // Importer bank soal membaca tabel utama secara baris/kolom. Tabel opsi
            // bersarang membuat parser server menunggu terus atau membaca struktur
            // sebagai tabel rusak. Opsi tetap ditulis satu per satu pada kolom OPSI
            // dan JAWABAN di bawah ini.
            return parseHtmlTableToDocx(cleanQuestionText);
        }

        parsedData.forEach((item) => {
            cleanTrailingBatchMarker(item);
            let totalOptions = (item.options && item.options.length > 0) ? item.options.length : 5;
            let cleanQuestionText = String(item.question || '').replace(/^\d+[\.\)]\s*/, '').trim();
            const questionAttachmentTokens = (QUESTION_ATTACHMENTS[String(item.number)] || []).map(a => a.token).join(' ');
            if (questionAttachmentTokens) cleanQuestionText += (cleanQuestionText ? '\n' : '') + questionAttachmentTokens;
            let soalChildren = buildTemplateSoalChildren(item, cleanQuestionText);

            for (let oIdx = 0; oIdx < totalOptions; oIdx++) {
                let char = String.fromCharCode(65 + oIdx);
                let isCorrect = item.key && Array.isArray(item.key) && item.key.some(k => String(k).trim().toUpperCase() === char);
                let rawOptText = (item.options && item.options[oIdx]) ? item.options[oIdx] : '';
                if (oIdx === 3) {
                    const safeItem = cleanTrailingBatchMarker({...item, options: [...(item.options || [])]});
                    rawOptText = safeItem.options?.[3] || '';
                }
                let optText = String(rawOptText).replace(/^[a-eA-E][.)]\s*/, '').trim();

                let cells = [];

                if (oIdx === 0) {
                    cells.push(
                        baseCell([centerParagraph([new TextRun({ text: String(item.number), font: FONT_NAME, size: FONT_SIZE })])], { rowSpan: totalOptions }),
                        baseCell(soalChildren, { rowSpan: totalOptions, verticalAlign: VerticalAlign.TOP }),
                        baseCell([centerParagraph([new TextRun({ text: String(item.jenis || 1), font: FONT_NAME, size: FONT_SIZE })])], { rowSpan: totalOptions }),
                        baseCell([centerParagraph([new TextRun({ text: char, font: FONT_NAME, size: FONT_SIZE })])]),
                        baseCell([normalParagraph(docxRunsFromText(optText, DOCX_OPT_MAX_W))], { verticalAlign: VerticalAlign.CENTER }),
                        baseCell([centerParagraph([new TextRun({ text: isCorrect ? "v" : "", bold: !!isCorrect, font: FONT_NAME, size: FONT_SIZE })])])
                    );
                } else {
                    cells.push(
                        baseCell([centerParagraph([new TextRun({ text: char, font: FONT_NAME, size: FONT_SIZE })])]),
                        baseCell([normalParagraph(docxRunsFromText(optText, DOCX_OPT_MAX_W))]),
                        baseCell([centerParagraph([new TextRun({ text: isCorrect ? "v" : "", bold: !!isCorrect, font: FONT_NAME, size: FONT_SIZE })])])
                    );
                }

                tableRows.push(new TableRow({ children: cells }));
            }
        });

        const doc = new Document({
            styles: {
                default: {
                    document: {
                        run: {
                            font: FONT_NAME,
                            size: FONT_SIZE
                        }
                    }
                }
            },
            sections: [{
                properties: {
                    page: {
                        orientation: docx.PageOrientation.LANDSCAPE,
                        size: {
                            width: 16838,  
                            height: 11906  
                        },
                        margin: {
                            top: 720,
                            bottom: 720,
                            left: 720,
                            right: 720
                        }
                    }
                },
                children: [
                    new Paragraph({
                        children: [new TextRun({ text: "I. Soal Pilihan Ganda (satu jawaban benar)", bold: true, font: FONT_NAME, size: FONT_SIZE })],
                        spaceAfter: 100
                    }),
                    new Paragraph({
                        children: [new TextRun({ text: "- Jenis Pilihan Ganda (PG 1) untuk pilihan ganda yang sudah ditentukan pilihan opsinya, misal 4 opsi (A,B,C,D) atau 5 opsi (A,B,C,D,E).", font: FONT_NAME, size: FONT_SIZE })],
                        spaceAfter: 40
                    }),
                    new Paragraph({
                        children: [
                            new TextRun({ text: "- Kolom jenis harus diisi angka ", font: FONT_NAME, size: FONT_SIZE }),
                            new TextRun({ text: "1", bold: true, font: FONT_NAME, size: FONT_SIZE })
                        ],
                        spaceAfter: 40
                    }),
                    new Paragraph({
                        children: [new TextRun({ text: "- Biarkan OPSI JAWABAN kosong jika ada opsi yang tidak digunakan.", font: FONT_NAME, size: FONT_SIZE })],
                        spaceAfter: 40
                    }),
                    new Paragraph({
                        children: [new TextRun({ text: "- NOMOR soal yang tidak digunakan bisa dihapus atau dibiarkan kosong.", font: FONT_NAME, size: FONT_SIZE })],
                        spaceAfter: 40
                    }),
                    new Paragraph({
                        children: [new TextRun({ text: "- Soal dan jawaban bisa disisipi gambar", color: "00B050", font: FONT_NAME, size: FONT_SIZE })],
                        spaceAfter: 40
                    }),
                    new Paragraph({
                        children: [new TextRun({ text: "- KUNCI hanya diisi satu jawaban.", font: FONT_NAME, size: FONT_SIZE })],
                        spaceAfter: 40
                    }),
                    new Paragraph({
                        children: [
                            new TextRun({ text: "- Beri tanda v pada opsi jawaban benar, (", font: FONT_NAME, size: FONT_SIZE }),
                            new TextRun({ text: "v huruf kecil atau V huruf besar", bold: true, font: FONT_NAME, size: FONT_SIZE }),
                            new TextRun({ text: ").", font: FONT_NAME, size: FONT_SIZE })
                        ]
                    }),
                    new Paragraph({ children: [], spaceAfter: 150 }),
                    new Table({
                        rows: tableRows,
                        borders: tableBorders,
                        width: { size: 15300, unit: WidthType.DXA },
                        columnWidths: [TEMPLATE_COLS.no, TEMPLATE_COLS.soal, TEMPLATE_COLS.jenis, TEMPLATE_COLS.opsi, TEMPLATE_COLS.jawaban, TEMPLATE_COLS.kunci],
                        layout: "fixed"
                    })
                ]
            }]
        });

        let blob = await Packer.toBlob(doc);

        // Equation sudah dibuat langsung oleh docx.js sebagai OMML.
        // Tidak perlu membuka ulang ZIP/DOCX dengan JSZip sehingga proses download
        // lebih cepat dan tidak memicu "too much recursion".
        saveAs(blob, "Soal_Converter.docx");

    } catch (err) {
        alert("Gagal mengunduh file Word: " + err.message);
    } finally {
        btnDownload.disabled = false;
        btnDownload.innerHTML = originalText;
    }
}

function clearAll() {
    document.getElementById('inputText').innerHTML = '';
    document.getElementById('btnAiAnswer').style.display = 'none';
    EDITOR_IMAGES = {};
    EDITOR_IMAGE_SIZES = {};
    QUESTION_ATTACHMENTS = {};
    ORIGINAL_WORD_BUFFER = null;
    ORIGINAL_WORD_NAME = '';
    ORIGINAL_WORD_XML = null;
    ORIGINAL_WORD_ITEMS = [];
    setOriginalWordAvailable(false);
    parsedData = [];
    isAiProcessed = false;
    cacheLoaded = false;
    activeQuestionFingerprint = '';
    setCacheStatus('', 'muted');
    renderPreview();
}

function escapeHtml(text) {
    if (!text) return '';
    return text.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;");
}
</script>

<script>
(function(){
  function setAITheme(theme){
    theme = (theme === 'day' || theme === 'read') ? theme : 'night';
    document.documentElement.setAttribute('data-ai-theme', theme);
    try{ localStorage.setItem('AI_CONVERTER_THEME', theme); }catch(e){}
    document.querySelectorAll('[data-ai-theme-btn]').forEach(function(btn){
      btn.classList.toggle('active', btn.getAttribute('data-ai-theme-btn') === theme);
    });
  }
  function initAIThemeSwitcher(){
    var saved='night';
    try{ saved=localStorage.getItem('AI_CONVERTER_THEME') || 'night'; }catch(e){}
    setAITheme(saved);
    document.querySelectorAll('[data-ai-theme-btn]').forEach(function(btn){
      btn.addEventListener('click', function(){ setAITheme(btn.getAttribute('data-ai-theme-btn')); });
    });
  }
  if(document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initAIThemeSwitcher);
  else initAIThemeSwitcher();
})();
</script>
<script>document.addEventListener('DOMContentLoaded', initInlineEquationEditing);</script>

<script>
/* ===== Satu tombol: buka Input + Preview di TAB BARU =====
 * Versi ini membuka halaman AI Converter yang sama di tab baru,
 * bukan menyalin DOM statis. Dengan begitu Import Word/PDF, Preview,
 * Equation, gambar, AI, dan seluruh event JavaScript tetap aktif.
 */
(function () {
    var STORAGE_KEY = 'AI_CONVERTER_WORKSPACE_TAB_STATE_V2';

    function saveWorkspaceState() {
        try {
            var ed = document.getElementById('inputText');
            if (!ed) return;
            sessionStorage.setItem(STORAGE_KEY, JSON.stringify({
                html: ed.innerHTML,
                scrollTop: ed.scrollTop || 0,
                savedAt: Date.now()
            }));
        } catch (e) {
            console.warn('State workspace tidak dapat disimpan:', e);
        }
    }

    function restoreWorkspaceState() {
        try {
            var raw = sessionStorage.getItem(STORAGE_KEY);
            if (!raw) return;
            var state = JSON.parse(raw);
            if (!state || !state.html) return;
            var ed = document.getElementById('inputText');
            if (!ed) return;
            ed.innerHTML = state.html;
            if (typeof autoCleanAndParse === 'function') {
                setTimeout(function () {
                    autoCleanAndParse();
                    setTimeout(function () {
                        if (state.scrollTop != null) ed.scrollTop = state.scrollTop;
                    }, 80);
                }, 120);
            }
        } catch (e) {
            console.warn('State workspace tidak dapat dipulihkan:', e);
        }
    }

    function setupQuestionWorkspaceFullscreen() {
        if (window.__questionWorkspaceFullscreenReady) return;
        window.__questionWorkspaceFullscreenReady = true;

        window.toggleQuestionWorkspaceFullscreen = function () {
            var workspace = document.getElementById('aiQuestionWorkspace');
            if (!workspace) {
                alert('Panel Input Bank Soal dan Preview belum siap.');
                return;
            }
            saveWorkspaceState();

            var url = new URL(window.location.href);
            url.searchParams.set('aiWorkspace', '1');
            url.searchParams.set('_', Date.now().toString());
            var win = window.open(url.toString(), '_blank');
            if (!win) {
                alert('Tab baru tidak dapat dibuka. Izinkan pop-up untuk situs ini lalu klik ikon layar penuh lagi.');
                return;
            }
            try { win.focus(); } catch (e) {}
        };
    }

    function applyWorkspaceTabMode() {
        var params = new URLSearchParams(window.location.search);
        if (params.get('aiWorkspace') !== '1') return;

        document.documentElement.classList.add('ai-workspace-tab-page');
        document.body.classList.add('ai-workspace-tab-page');

        var boot = function () {
            var workspace = document.getElementById('aiQuestionWorkspace');
            if (!workspace) return;

            /* Sembunyikan seluruh shell/sidebar di tab baru. Hanya rantai
               parent yang memuat workspace yang dibiarkan terlihat. */
            var node = workspace;
            while (node && node !== document.body) {
                /* Pastikan setiap parent workspace tetap terlihat. */
                node.parentElement && (node.parentElement.style.display = 'block');
                Array.from(node.parentElement ? node.parentElement.children : []).forEach(function (sib) {
                    if (sib !== node && sib.tagName !== 'SCRIPT' && sib.tagName !== 'STYLE') {
                        sib.style.display = 'none';
                    }
                });
                node = node.parentElement;
            }
            document.documentElement.style.display = 'block';
            document.body.style.display = 'block';
            document.body.style.visibility = 'visible';

            if (!document.getElementById('aiWorkspaceTabClose')) {
                var close = document.createElement('button');
                close.id = 'aiWorkspaceTabClose';
                close.type = 'button';
                close.textContent = '✕ Tutup Tab';
                close.title = 'Tutup tab tampilan penuh';
                close.onclick = function(){ try { window.close(); } catch(e) {} };
                document.body.appendChild(close);
            }

            restoreWorkspaceState();
            setTimeout(function () {
                var input = document.getElementById('inputText');
                if (input) input.focus();
                if (window.MathJax && MathJax.typesetPromise) {
                    MathJax.typesetPromise([workspace]).catch(function () {});
                }
            }, 350);
        };

        if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', boot);
        else boot();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', setupQuestionWorkspaceFullscreen);
    } else {
        setupQuestionWorkspaceFullscreen();
    }
    applyWorkspaceTabMode();
})();
</script>

<style id="ai-workspace-toolbar-final-fix">
/* ================================================================
   FINAL FIX — TAB INPUT + PREVIEW
   Toolbar Import Word/PDF dan Upload Gambar harus selalu utuh.
   Jangan biarkan area soal/content mendorong atau memotong toolbar.
   ================================================================ */
html.ai-workspace-tab-page body.ai-workspace-tab-page,
body.ai-workspace-tab-page {
    overflow:hidden!important;
}

/* Dua kartu utama memakai grid: header + toolbar + area soal.
   Dengan demikian area soal hanya mengambil ruang yang tersisa. */
body.ai-workspace-tab-page #aiQuestionWorkspace > [class*="col-"]:first-child > .card {
    display:grid!important;
    grid-template-rows:auto auto minmax(0,1fr)!important;
    height:calc(100vh - 28px)!important;
    min-height:0!important;
    overflow:hidden!important;
}

body.ai-workspace-tab-page #aiQuestionWorkspace > [class*="col-"]:last-child > .card {
    display:grid!important;
    grid-template-rows:auto minmax(0,1fr) auto!important;
    height:calc(100vh - 28px)!important;
    min-height:0!important;
    overflow:hidden!important;
}

/* Header tidak ikut scroll. */
body.ai-workspace-tab-page #aiQuestionWorkspace > [class*="col-"] > .card > .card-header {
    grid-row:auto!important;
    flex:none!important;
    min-height:52px!important;
    height:auto!important;
    overflow:visible!important;
    box-sizing:border-box!important;
    z-index:10!important;
}

/* TOOLBAR IMPORT/UPLOAD — area ini tidak boleh mengecil, terpotong,
   atau ikut menjadi scroll container. */
body.ai-workspace-tab-page #aiQuestionWorkspace > [class*="col-"]:first-child > .card > .card-body.bg-light.border-bottom,
body.ai-workspace-tab-page #aiQuestionWorkspace > [class*="col-"]:first-child > .card > .card-body:first-of-type {
    grid-row:auto!important;
    flex:none!important;
    flex-shrink:0!important;
    height:auto!important;
    min-height:76px!important;
    max-height:none!important;
    overflow:visible!important;
    box-sizing:border-box!important;
    position:relative!important;
    z-index:20!important;
    isolation:isolate!important;
}

/* Tombol toolbar tidak boleh menyusut. */
body.ai-workspace-tab-page #aiQuestionWorkspace > [class*="col-"]:first-child > .card > .card-body:first-of-type .row {
    min-height:0!important;
    overflow:visible!important;
}
body.ai-workspace-tab-page #aiQuestionWorkspace > [class*="col-"]:first-child > .card > .card-body:first-of-type .col-6 {
    min-width:0!important;
    overflow:visible!important;
}
body.ai-workspace-tab-page #aiQuestionWorkspace #fileImporter + * {
    min-width:0!important;
}
body.ai-workspace-tab-page #aiQuestionWorkspace .word-original-btn {
    white-space:nowrap!important;
    overflow:hidden!important;
    text-overflow:ellipsis!important;
}

/* Area editor saja yang boleh menjadi scroll container. */
body.ai-workspace-tab-page #aiQuestionWorkspace > [class*="col-"]:first-child > .card > .card-body:nth-of-type(2) {
    grid-row:auto!important;
    min-height:0!important;
    height:auto!important;
    max-height:none!important;
    overflow:hidden!important;
    display:flex!important;
    flex-direction:column!important;
    box-sizing:border-box!important;
    position:relative!important;
}
body.ai-workspace-tab-page #aiQuestionWorkspace #inputText {
    flex:1 1 0!important;
    width:100%!important;
    height:auto!important;
    min-height:0!important;
    max-height:none!important;
    overflow:auto!important;
    box-sizing:border-box!important;
}

/* Preview hanya area isi soal yang scroll. Header/footer tetap. */
body.ai-workspace-tab-page #aiQuestionWorkspace #previewRenderArea {
    grid-row:auto!important;
    flex:none!important;
    width:100%!important;
    height:auto!important;
    min-height:0!important;
    max-height:none!important;
    overflow-y:auto!important;
    overflow-x:hidden!important;
    box-sizing:border-box!important;
}
body.ai-workspace-tab-page #aiQuestionWorkspace > [class*="col-"]:last-child > .card > .card-footer {
    flex:none!important;
    min-height:0!important;
    height:auto!important;
    overflow:visible!important;
    z-index:10!important;
}

/* Kartu soal di preview mengikuti isi, bukan memotong isi. */
body.ai-workspace-tab-page #aiQuestionWorkspace #previewRenderArea > * {
    height:auto!important;
    min-height:0!important;
    max-height:none!important;
    overflow:visible!important;
    box-sizing:border-box!important;
}

@media (max-width:900px) {
    body.ai-workspace-tab-page #aiQuestionWorkspace > [class*="col-"] {
        height:calc(100vh - 28px)!important;
    }
    body.ai-workspace-tab-page #aiQuestionWorkspace > [class*="col-"]:first-child > .card,
    body.ai-workspace-tab-page #aiQuestionWorkspace > [class*="col-"]:last-child > .card {
        height:calc(100vh - 28px)!important;
    }
}
</style>

<style id="ai-workspace-tab-hard-fix-v2">
/* HARD FIX v2 — tab workspace benar-benar memenuhi viewport.
   Toolbar Import Word/PDF + Upload Gambar selalu berada di atas;
   yang boleh scroll hanya isi Input dan isi Preview. */
html.ai-workspace-tab-page,
html.ai-workspace-tab-page body,
body.ai-workspace-tab-page {
    width:100%!important;
    height:100%!important;
    min-height:100%!important;
    margin:0!important;
    padding:0!important;
    overflow:hidden!important;
}
body.ai-workspace-tab-page #aiQuestionWorkspace {
    position:fixed!important;
    inset:0!important;
    z-index:99990!important;
    width:100vw!important;
    height:100vh!important;
    min-height:0!important;
    margin:0!important;
    padding:12px!important;
    box-sizing:border-box!important;
    display:flex!important;
    flex-wrap:nowrap!important;
    gap:12px!important;
    overflow:hidden!important;
    background:inherit!important;
}
body.ai-workspace-tab-page #aiQuestionWorkspace > [class*="col-"] {
    float:none!important;
    flex:1 1 0!important;
    width:auto!important;
    max-width:none!important;
    min-width:0!important;
    height:100%!important;
    min-height:0!important;
    padding:0!important;
    display:block!important;
}
body.ai-workspace-tab-page #aiQuestionWorkspace > [class*="col-"] > .card {
    width:100%!important;
    height:100%!important;
    min-height:0!important;
    max-height:none!important;
    margin:0!important;
    display:grid!important;
    overflow:hidden!important;
    box-sizing:border-box!important;
}
body.ai-workspace-tab-page #aiQuestionWorkspace > [class*="col-"]:first-child > .card {
    grid-template-rows:auto auto minmax(0,1fr)!important;
}
body.ai-workspace-tab-page #aiQuestionWorkspace > [class*="col-"]:last-child > .card {
    grid-template-rows:auto minmax(0,1fr) auto!important;
}
body.ai-workspace-tab-page #aiQuestionWorkspace > [class*="col-"] > .card > .card-header,
body.ai-workspace-tab-page #aiQuestionWorkspace > [class*="col-"]:first-child > .card > .card-body.bg-light.border-bottom,
body.ai-workspace-tab-page #aiQuestionWorkspace > [class*="col-"]:first-child > .card > .card-body:first-of-type,
body.ai-workspace-tab-page #aiQuestionWorkspace > [class*="col-"]:last-child > .card > .card-footer {
    min-height:0!important;
    flex:none!important;
    overflow:visible!important;
    box-sizing:border-box!important;
}
body.ai-workspace-tab-page #aiQuestionWorkspace > [class*="col-"]:first-child > .card > .card-body.bg-light.border-bottom,
body.ai-workspace-tab-page #aiQuestionWorkspace > [class*="col-"]:first-child > .card > .card-body:first-of-type {
    min-height:86px!important;
    max-height:110px!important;
    padding:8px 10px!important;
}
body.ai-workspace-tab-page #aiQuestionWorkspace > [class*="col-"]:first-child > .card > .card-body:nth-of-type(2) {
    min-height:0!important;
    height:auto!important;
    overflow:hidden!important;
    display:flex!important;
    flex-direction:column!important;
    padding:10px!important;
}
body.ai-workspace-tab-page #aiQuestionWorkspace #inputText {
    flex:1 1 0!important;
    width:100%!important;
    height:100%!important;
    min-height:0!important;
    max-height:none!important;
    overflow:auto!important;
    white-space:pre-wrap!important;
    overflow-wrap:anywhere!important;
    box-sizing:border-box!important;
}
body.ai-workspace-tab-page #aiQuestionWorkspace #previewRenderArea {
    min-height:0!important;
    height:auto!important;
    max-height:none!important;
    overflow-y:auto!important;
    overflow-x:hidden!important;
    box-sizing:border-box!important;
}
body.ai-workspace-tab-page #aiQuestionWorkspace #previewRenderArea > * {
    height:auto!important;
    max-height:none!important;
    min-height:0!important;
    overflow:visible!important;
}
body.ai-workspace-tab-page #aiWorkspaceTabClose {
    position:fixed!important;
    top:10px!important;
    right:10px!important;
    z-index:100000!important;
}
/* Rumus hasil Word harus tetap besar dan terlihat walaupun MathJax belum siap. */
body.ai-workspace-tab-page .imported-equation,
.imported-equation {
    display:inline-block!important;
    vertical-align:middle!important;
    min-width:28px!important;
    min-height:28px!important;
    font-size:1.08em!important;
    line-height:1.45!important;
}
@media (max-width:900px){
    body.ai-workspace-tab-page #aiQuestionWorkspace {
        flex-direction:column!important;
        overflow-y:auto!important;
    }
    body.ai-workspace-tab-page #aiQuestionWorkspace > [class*="col-"] {
        flex:1 1 50%!important;
        width:100%!important;
        height:50%!important;
    }
}
</style>

<style id="ai-image-paste-enhancement">
.ai-image-paste-hint{
    display:flex;align-items:center;gap:12px;margin:0 0 10px;padding:11px 13px;
    border:1px dashed rgba(0,255,156,.58);border-radius:10px;
    background:linear-gradient(110deg,rgba(0,255,156,.09),rgba(0,229,255,.045));
    color:var(--text,#ecfffa);cursor:pointer;transition:border-color .15s,background .15s;
}
.ai-image-paste-hint:hover,.ai-image-paste-hint:focus{
    outline:none;border-color:#00ff9c;background:rgba(0,255,156,.12);
}
.ai-image-paste-icon{display:grid;place-items:center;width:38px;height:38px;flex:0 0 38px;
    border-radius:9px;background:rgba(0,255,156,.13);color:#00ff9c;font-size:19px}
.ai-image-paste-hint strong{display:block;font-size:13px;line-height:1.4}
.ai-image-paste-hint small{display:block;margin-top:2px;color:var(--mute,#91b8ad);font-size:11px;line-height:1.45}
.ai-image-paste-tag{margin-left:auto;white-space:nowrap;border:1px solid rgba(0,255,156,.35);
    border-radius:6px;padding:5px 8px;color:#00ff9c;font-size:11px;font-weight:800}
#inputText.ai-image-drop-target{outline:2px dashed #00ff9c;outline-offset:-5px;background:rgba(0,255,156,.07)}
@media(max-width:600px){.ai-image-paste-hint{gap:8px;padding:9px}.ai-image-paste-tag{display:none}}
</style>
<script id="ai-image-drag-drop-support">
(function(){
    function initImageDrop(){
        var ed=document.getElementById('inputText');
        if(!ed || ed.dataset.imageDropReady==='1') return;
        ed.dataset.imageDropReady='1';
        ['dragenter','dragover'].forEach(function(type){
            ed.addEventListener(type,function(e){
                var files=e.dataTransfer && e.dataTransfer.files;
                if(!files || !Array.from(files).some(function(f){return /^image\//i.test(f.type)})) return;
                e.preventDefault(); e.stopPropagation(); ed.classList.add('ai-image-drop-target');
            });
        });
        ['dragleave','dragend'].forEach(function(type){
            ed.addEventListener(type,function(){ed.classList.remove('ai-image-drop-target')});
        });
        ed.addEventListener('drop',function(e){
            ed.classList.remove('ai-image-drop-target');
            var files=e.dataTransfer && e.dataTransfer.files;
            if(!files || !files.length) return;
            var images=Array.from(files).filter(function(f){return /^image\//i.test(f.type)});
            if(!images.length) return;
            e.preventDefault(); e.stopPropagation(); ed.focus();
            images.forEach(function(file){
                var reader=new FileReader();
                reader.onload=function(ev){
                    if(ev.target && ev.target.result) insertImageToInput(ev.target.result);
                };
                reader.readAsDataURL(file);
            });
        });
    }
    if(document.readyState==='loading') document.addEventListener('DOMContentLoaded',initImageDrop);
    else initImageDrop();
})();
</script>

<style id="question-specific-image-style">
.question-image-card{outline:none;transition:border-color .15s,box-shadow .15s}
.question-image-card.question-image-active{border:1px solid #00ff9c!important;box-shadow:0 0 0 2px rgba(0,255,156,.12)!important}
.question-image-paste-hint{font-size:11px;color:#8ab8ae;margin:0 0 8px;padding:6px 9px;border:1px dashed rgba(0,255,156,.25);border-radius:6px}
.question-image-upload-label{cursor:pointer}
.question-image-card .preview-question img{display:block;margin:8px 0;max-width:100%;height:auto}
</style>
<script id="question-specific-image-support">
(function(){
    let activeQuestionNumber = null;
    const MAX_IMAGES_PER_QUESTION = 4;
    window.focusQuestionImageCard = function(number){
        activeQuestionNumber = String(number);
        document.querySelectorAll('.question-image-card').forEach(function(card){
            card.classList.toggle('question-image-active', card.dataset.questionNumber === activeQuestionNumber);
        });
        const card = Array.from(document.querySelectorAll('.question-image-card')).find(function(el){return el.dataset.questionNumber === activeQuestionNumber;});
        if(card) card.focus();
    };
    function readImageFile(file, number){
        if(!file || !/^image\//i.test(file.type || '')) return;
        const current = QUESTION_ATTACHMENTS[String(number)] || [];
        if(current.length >= MAX_IMAGES_PER_QUESTION){ alert('Maksimal 4 gambar untuk soal nomor '+number+'.'); return; }
        const reader = new FileReader();
        reader.onload = function(ev){
            const src = ev.target && ev.target.result;
            if(!src || !/^data:image\//i.test(src)) return;
            const list = QUESTION_ATTACHMENTS[String(number)] || [];
            if(list.length >= MAX_IMAGES_PER_QUESTION){ alert('Maksimal 4 gambar untuk soal nomor '+number+'.'); return; }
            const token = makeImageToken(src);
            EDITOR_IMAGES[token] = src;
            list.push({token:token, src:src, name:file.name || 'Gambar soal'});
            QUESTION_ATTACHMENTS[String(number)] = list;
            renderPreview();
            focusQuestionImageCard(number);
        };
        reader.readAsDataURL(file);
    }
    window.uploadQuestionImages = function(number, input){
        const files = Array.from((input && input.files) || []);
        files.forEach(function(file){ readImageFile(file, number); });
        if(input) input.value = '';
    };
    function findCard(target){return target && target.closest ? target.closest('.question-image-card') : null;}
    document.addEventListener('click', function(e){
        const card = findCard(e.target);
        if(card && !e.target.closest('button,label,input')) focusQuestionImageCard(card.dataset.questionNumber);
    });
    document.addEventListener('paste', function(e){
        const card = findCard(e.target) || findCard(document.activeElement);
        const number = card ? card.dataset.questionNumber : activeQuestionNumber;
        if(!number) return;
        const cd = e.clipboardData;
        if(!cd || !cd.items) return;
        const images = [];
        for(let i=0;i<cd.items.length;i++){
            const item = cd.items[i];
            if(item.type && item.type.indexOf('image/') === 0){ const file=item.getAsFile(); if(file) images.push(file); }
        }
        if(!images.length) return;
        e.preventDefault(); e.stopPropagation();
        images.forEach(function(file){ readImageFile(file, number); });
    }, true);
    document.addEventListener('keydown', function(e){
        if(!(e.ctrlKey || e.metaKey) || e.key.toLowerCase() !== 'v') return;
        const card = findCard(document.activeElement);
        if(card) activeQuestionNumber = card.dataset.questionNumber;
    });
})();
</script>