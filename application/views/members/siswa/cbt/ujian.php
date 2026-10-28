<?php
/**
 * Created by IntelliJ IDEA.
 * User: multazam
 * Date: 23/08/20
 * Time: 23:18
 */
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<!-- Font dimuat non-blocking supaya tidak menahan eksekusi script & pemuatan soal -->
<link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet"></noscript>

<link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/katex/katex.min.css">
<style>
/* Tampilan rumus matematika */
.katex { font-size: 1.08em; }
.katex-display { overflow-x: auto; overflow-y: hidden; padding: 3px 0; margin: .5em 0; }
/* =========================================================================
   1. RESET TOTAL & KONFIGURASI ENGINE GESTURE (BEBAS BEKU)
   ========================================================================= */
html, body {
    padding: 0 !important;
    margin: 0 !important;
    background: #f4f6f9 !important;
    overflow-x: hidden !important;
    overflow-y: auto !important; 
    touch-action: auto !important;
    -webkit-tap-highlight-color: transparent !important;
    width: 100%;
}

.wrapper {
    padding-top: 0px !important;
    margin-top: 0px !important;
    padding-bottom: 0px !important;
    margin-bottom: 0px !important;
    top: 0px !important;
    background: #f4f6f9 !important;
    transform-origin: center center !important; 
    will-change: transform;
    width: 100% !important;
    position: relative !important;
}

.content-wrapper, .main-header, .content-header {
    padding-top: 0px !important;
    margin-top: 0px !important;
    padding-bottom: 0px !important;
    margin-bottom: 0px !important;
    background: transparent !important;
}

.main-header.navbar {
    display: none !important;
    height: 0px !important;
    margin-top: 0px !important;
    padding-top: 0px !important;
}

/* =========================================================================
   2. HEADER UTAMA ANBK
   ========================================================================= */
.anbk-header-fixed {
    background: #326698 !important;
    background-size: contain !important;
    background-image: url('https://pusmendik.kemendikdasmen.go.id/an/images/bg-top.png') !important;
    background-repeat: no-repeat !important;
    background-position: left !important; 
    height: 150px !important;
    position: relative !important;
    width: 100% !important;
    z-index: 10 !important; 
    box-shadow: 0 4px 10px rgba(0,0,0,0.1) !important;
}

/* =========================================================================
   3. KONTAINER TABEL SOAL & NAVIGASI STICKY (PINNED)
   ========================================================================= */
.content.overlap {
    position: relative !important;
    margin-top: 15px !important; 
    padding-bottom: 40px !important;
    width: 100% !important;
    z-index: 20 !important; 
    background: transparent !important;
}

.content.overlap .container {
    max-width: 95% !important; 
    margin: 0 auto !important;  
    padding-left: 15px !important;
    padding-right: 15px !important;
}

.card.my-shadow {
    margin-top: 0px !important;
    border-radius: 20px !important;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
    border: none !important; 
    background: #ffffff !important;
    position: relative;
    overflow: hidden; 
}

/* --- FITUR STICKY HEADER NAVIGASI --- */
.card-header.sticky-nav-top {
    position: sticky !important;
    top: 0 !important;
    z-index: 100 !important;
    background-color: #ffffff !important;
    border-bottom: 2px solid #e2e8f0 !important;
    box-shadow: 0 4px 8px rgba(0,0,0,0.06) !important;
}

/* --- FITUR STICKY FOOTER NAVIGASI --- */
.card-footer.sticky-nav-bottom {
    position: sticky !important;
    bottom: 0 !important;
    z-index: 100 !important;
    background-color: #ffffff !important;
    border-top: 2px solid #e2e8f0 !important;
    box-shadow: 0 -4px 8px rgba(0,0,0,0.06) !important;
}

/* Sisa waktu tepat di tengah (kolom tengah dipusatkan dengan flex, kebal terhadap text-align !important) */
.sticky-nav-top .col-4.text-center {
    display: flex !important;
    justify-content: center !important;
    align-items: center !important;
}
.sticky-nav-top #wrapper-timer {
    margin: 0 !important;
}

#wrapper-timer:hover, 
#wrapper-timer:focus, 
#wrapper-timer:active {
    background-color: transparent !important;
    color: #212529 !important;
    border-color: #343a40 !important;
    box-shadow: none !important;
}

/* =========================================================================
   4. ANIMASI KUSTOM ICON DI SEBELAH KANAN ATAS
   ========================================================================= */
.animated-user-icon {
    font-size: 38px;
    color: #00e676;
    animation: pulseGlow 2s infinite ease-in-out, floatIcon 3s infinite ease-in-out;
    display: inline-block;
    filter: drop-shadow(0px 0px 8px rgba(0, 230, 118, 0.6));
}

@keyframes pulseGlow {
    0% { transform: scale(1); opacity: 0.9; }
    50% { transform: scale(1.1); opacity: 1; filter: drop-shadow(0px 0px 14px rgba(0, 230, 118, 0.9)); }
    100% { transform: scale(1); opacity: 0.9; }
}

@keyframes floatIcon {
    0% { translate: 0 0px; }
    50% { translate: 0 -4px; }
    100% { translate: 0 0px; }
}

@media (max-width: 575px) {
    .header-user-info { font-size: 11px !important; }
    .animated-user-icon { font-size: 28px !important; }
}

/* =========================================================================
   5. FORMAT KOREKSI TEKS, TABEL SOAL & INTERNAL SCROLLBAR
   ========================================================================= */
.konten-soal-jawab, #konten-soal, #konten-jawaban, 
.container-jawaban, .custom-control-label,
p:not(.katex *), span:not(.katex):not(.katex *), div:not(.katex *), label, li, td, th {
    text-align: left !important;
    word-spacing: normal !important;
    letter-spacing: normal !important;
    white-space: normal !important;
    font-family: 'Roboto', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
    font-weight: 400 !important;
    font-size: 18px !important;
    line-height: 1.7 !important;
}

#konten-soal, 
#konten-soal p, 
#konten-soal span:not(.katex):not(.katex *), 
#konten-soal div, 
#konten-soal li,
#konten-jawaban,
#konten-jawaban label,
#konten-jawaban span:not(.katex):not(.katex *),
#konten-jawaban div {
    font-size: 18px !important;
}

/* Soal panjang tampil utuh (tanpa kotak scroll internal); halaman yang digulir.
   overflow-x tetap auto supaya tabel/gambar yang lebar masih bisa digeser ke samping. */
#konten-soal {
    height: auto !important;
    max-height: none !important;
    overflow-x: auto !important;
    overflow-y: visible !important;
    padding-bottom: 15px;
    padding-right: 0;
    width: 100%;
}

#konten-soal::-webkit-scrollbar {
    width: 6px;
}
#konten-soal::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 10px;
}
#konten-soal::-webkit-scrollbar-thumb {
    background-color: #cbd5e1;
    border-radius: 10px;
}
#konten-soal::-webkit-scrollbar-thumb:hover {
    background-color: #94a3b8;
}

#konten-soal table {
    width: 100% !important;
    max-width: 100% !important;
    margin-bottom: 1.5rem;
    background-color: #ffffff !important;
    word-spacing: normal !important;
    text-align: left !important;
    font-family: 'Roboto', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
    border-collapse: separate !important;
    border-spacing: 0 !important;
    border-radius: 15px !important; 
    overflow: hidden !important;
    border: 2px solid #326698 !important; 
}

#konten-soal th, #konten-soal td {
    word-spacing: normal !important;
    text-align: left !important;
    font-family: 'Roboto', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
    padding: 12px 18px !important; 
    border: 1px solid #E2E8F0 !important;
    font-size: 18px !important;
}

#konten-soal th {
    background-color: #f1f5f9 !important;
    font-weight: bold !important;
    color: #1e293b !important;
    border-top: none !important;
}

#konten-soal tr:first-child th:first-child, #konten-soal tr:first-child td:first-child { border-top-left-radius: 13px !important; padding-left: 22px !important; }
#konten-soal tr:first-child th:last-child, #konten-soal tr:first-child td:last-child { border-top-right-radius: 13px !important; padding-right: 22px !important; }
#konten-soal tr:last-child td:first-child { border-bottom-left-radius: 13px !important; padding-left: 22px !important; }
#konten-soal tr:last-child td:last-child { border-bottom-right-radius: 13px !important; padding-right: 22px !important; }

/* Dialog SweetAlert: semua teks rata tengah (mengalahkan aturan div {text-align:left !important} di atas) */
.swal2-popup,
.swal2-popup .swal2-title,
.swal2-popup .swal2-content,
.swal2-popup .swal2-html-container,
.swal2-popup #swal2-content,
.swal2-popup #swal2-html-container,
.swal2-popup .selamat-judul,
.swal2-popup .selamat-teks {
    text-align: center !important;
}
.swal2-popup .selamat-judul {
    display: block !important;
    font-size: 24px !important;
    font-weight: 700 !important;
    margin-bottom: 6px !important;
}
.swal2-popup .selamat-teks {
    display: block !important;
    font-size: 16px !important;
}

.checkmark {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    padding: 0 !important;
    text-align: center !important;
}

/* =========================================================================
   6. ANIMASI TOMBOL PREV & NEXT
   ========================================================================= */
/* Rapikan soal & pilihan jawaban */
#konten-soal p, #konten-soal div { margin-top: 0 !important; margin-bottom: 8px !important; }
#konten-soal p:empty, #konten-soal div:empty { display: none !important; }
.container-jawaban p, .custom-control-label p { margin: 0 !important; }
#konten-jawaban .katex { font-size: 1.15em; }
#konten-soal { padding-bottom: 0 !important; }
/* Lingkaran huruf selalu di tengah vertikal terhadap isi pilihan */
#konten-jawaban .container-jawaban { position: relative; }
#konten-jawaban .container-jawaban .checkmark { top: 50% !important; transform: translateY(-50%); }
.container-jawaban .katex, .container-jawaban img, .container-jawaban math { vertical-align: middle; }

#prev { animation: pulsePrev 2.2s infinite ease-in-out; transition: all 0.3s ease; }
#next { animation: pulseNext 2.2s infinite ease-in-out; transition: all 0.3s ease; }
#prev:active, #next:active { transform: scale(0.95); }

@keyframes pulsePrev {
    0% { box-shadow: 0 0 0 0 rgba(0, 0, 0, 0.65); }
    60% { box-shadow: 0 0 0 14px rgba(0, 0, 0, 0); }
    100% { box-shadow: 0 0 0 0 rgba(0, 0, 0, 0); }
}
@keyframes pulseNext {
    0% { box-shadow: 0 0 0 0 rgba(0, 0, 0, 0.65); }
    60% { box-shadow: 0 0 0 14px rgba(0, 0, 0, 0); }
    100% { box-shadow: 0 0 0 0 rgba(0, 0, 0, 0); }
}

.timer-pulse { animation: softBreathe 2s infinite ease-in-out; }
@keyframes softBreathe {
    0% { opacity: 0.85; }
    50% { opacity: 1; filter: drop-shadow(0 0 2px rgba(0, 0, 0, 0.2)); }
    100% { opacity: 0.85; }
}

.timer-critical {
    color: #000000 !important; 
    font-weight: 900 !important;
    animation: fastBlink 1s infinite alternate ease-in-out !important;
    text-shadow: 0 0 6px rgba(0, 0, 0, 0.2);
}
@keyframes fastBlink {
    0% { opacity: 0.4; transform: scale(1); }
    100% { opacity: 1; transform: scale(1.05); }
}

/* =========================================================================
   7. STYLING SIDEBAR DAFTAR SOAL
   ========================================================================= */
.sidebar-daftar-soal {
    position: fixed;
    top: 0;
    right: -360px;
    width: 340px;
    height: 100%;
    background: #ffffff;
    box-shadow: -5px 0 25px rgba(0,0,0,0.15);
    z-index: 9999;
    transition: right 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    display: flex;
    flex-direction: column;
}

.sidebar-daftar-soal.buka {
    right: 0;
}

.sidebar-header {
    background: #326698;
    color: #fff;
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.sidebar-body {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
}

.sidebar-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.2);
    z-index: 9998;
    display: none;
    backdrop-filter: blur(1px);
}

.grid-nomor-soal {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 10px;
}

.btn-nomor-nav {
    aspect-ratio: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    border-radius: 8px;
    border: 2px solid #bdc3c7;
    background: #ffffff;
    color: #333;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 14px !important;
    padding: 0;
}

.btn-nomor-nav.aktif {
    border-color: #326698 !important;
    background: #ebf3fa !important;
    color: #326698 !important;
    box-shadow: inset 0 0 0 2px #326698;
}

.btn-nomor-nav.terjawab {
    background: #326698 !important;
    border-color: #326698 !important;
    color: #ffffff !important;
}

.btn-nomor-nav:hover {
    transform: scale(1.05);
}

.anbk-progress-container {
    background-color: #e9ecef;
    border-radius: 50px;
    height: 10px;
    overflow: hidden;
    width: 100%;
}
.anbk-progress-bar {
    background-color: #28a745;
    height: 100%;
    width: 0%;
    transition: width 0.4s ease;
}
</style>

<div class="sidebar-overlay" id="overlaySidebar" onclick="tutupDaftarSoal()"></div>
<div class="sidebar-daftar-soal" id="sidebarSoal">
    <div class="sidebar-header">
        <h5 class="m-0" style="font-weight: bold; font-size: 16px;"><i class="fa fa-th mr-2"></i> DAFTAR SOAL</h5>
        <button class="btn btn-sm btn-light" onclick="tutupDaftarSoal()" style="border-radius: 50px; width: 30px; height: 30px; padding: 0; display: flex; align-items: center; justify-content: center;">
            <i class="fa fa-times text-dark"></i>
        </button>
    </div>
    <div class="sidebar-body">
        <div class="grid-nomor-soal" id="place-nomor-nav"></div>
    </div>
</div>

<div class="wrapper">
    <div class="container-fluid text-white anbk-header-fixed">
        <div class="row align-items-center h-100" style="padding-top: 15px;">
            <div class="col-6 pl-4 pl-md-5" style="padding-left: 40px !important;">
                <table>
                    <tbody>
                        <tr>
                            <td style="vertical-align: top;">
                                <img style="margin: -70px 5px 5px 5px; height:70px;" src="<?= base_url('assets/img/wall212.png') ?>" alt="Logo">
                            </td>
                            <td style="padding-left: 3px; vertical-align: top; padding-top: 2px;">
                                <div style="line-height: 1.2; margin-top: -46px;"><b>PUSMENDIK</b></div>
                                <div style="line-height: 1.2;"><small>APLIKASI</small></div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="col-6 pr-4 pr-md-5 text-right" style="margin-top: -15px;"> 
                <div class="d-inline-flex align-items-center text-right" style="gap: 15px;">
                    <!-- NAMA, NISN DAN ICON HIJAU ANIMASI DIHAPUS -->
                </div>
            </div>
        </div>
    </div>

    <section class="content overlap">
        <div class="container" style="-moz-user-select: none; -webkit-user-select: none; -ms-user-select:none; user-select:none;-o-user-select:none;" unselectable="on">
            <div class="row">
                <div class="col-12">
                    <div class="card my-shadow">
                        
                        <!-- HEADER NAVIGASI MELAYANG (STICKY TOP) -->
                        <div class="card-header p-2 p-md-3 sticky-nav-top">
                            <div class="row no-gutters w-100 m-0 mb-2 align-items-center">
                                <div class="col-12 px-1">
                                    <div class="anbk-progress-container">
                                        <div class="anbk-progress-bar" id="progress-bar"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row no-gutters align-items-center w-100 m-0">
                                <div class="col-4 text-left">
                                    <div id="nomor-soal" class="btn btn-primary no-hover m-0 text-lg d-inline-flex align-items-center justify-content-center" style="padding: 0 14px; border-radius: 12px; line-height: 1; min-width: 44px; font-weight: bold; height: 32px;">
                                        <span class="d-none d-md-inline mr-1">Nomor</span> 1
                                    </div>
                                </div>
                                <div class="col-4 text-center">
                                    <button id="wrapper-timer" class="btn btn-outline-dark text-dark btn-oval-sm no-hover d-inline-flex align-items-center justify-content-center" style="padding: 2px 12px; border-radius: 50px; white-space: nowrap; height: 32px; margin: 0 auto;">
                                        <span class='mr-2 d-none d-md-inline-block'><b>Sisa Waktu</b></span>
                                        <span id="timer" class="text-bold timer-pulse" style="font-family: monospace; font-size: 14px; letter-spacing: 0.5px;">00:00:00</span>
                                    </button>
                                </div>
                                <div class="col-4 text-right">
                                    <button class="btn btn-primary no-hover m-0 text-lg d-inline-flex align-items-center justify-content-center" onclick="bukaDaftarSoal()" style="background-color: #007bff; border: none; border-radius: 12px; font-weight: bold; height: 32px; width: auto; float: right; padding: 0 14px;">
                                        <span class="d-none d-md-inline mr-2">Daftar Soal</span> 
                                        <i class="fa fa-th-large" style="margin: 0;"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- BODY AREA SOAL DAN PILIHAN JAWABAN -->
                        <div class="card-body p-3">
                            <div style="border: 1px solid; border-color: #D3D3D3; padding: 15px; border-radius: 10px;">
                                <div class="konten-soal-jawab">
                                    <div class="row p-2 mb-2 ml-1">
                                        <div id="konten-soal"><span style="color:#888;">Memuat soal...</span></div>
                                    </div>
                                    <?= form_open('jawab', array('id' => 'jawab')) ?>
                                    <input type="hidden" name="siswa" value="<?= isset($siswa->id_siswa) ? $siswa->id_siswa : '' ?>">
                                    <input type="hidden" name="jadwal" value="<?= isset($jadwal->id_jadwal) ? $jadwal->id_jadwal : '' ?>">
                                    <input type="hidden" name="bank" value="<?= isset($jadwal->id_bank) ? $jadwal->id_bank : '' ?>">
                                    <div class="row p-3">
                                        <div id="konten-jawaban" class="col-12"></div>
                                    </div>
                                    <?= form_close() ?>
                                </div>
                            </div>
                        </div>

                        <!-- FOOTER NAVIGASI MELAYANG (STICKY BOTTOM) -->
                        <div class="card-footer p-3 sticky-nav-bottom">
                            <div class="d-flex justify-content-between bd-highlight">
                                <div class="bd-highlight">
                                    <button class="btn btn-danger btn-lg" id="prev" onclick="prevSoal()" style="padding: 10px 24px; border-radius: 50px; font-size: 15px; display: inline-flex; align-items: center; gap: 8px;">
                                        <i class="fa fa-arrow-circle-left" style="font-size: 18px;"></i>
                                        <span class="ml-2 d-none d-md-inline-block"><b>Soal Sebelumnya</b></span>
                                    </button>
                                </div>
                                <div class="bd-highlight">
                                    <button class="btn btn-danger btn-disabled d-none" id="timer-selesai" disabled style="padding: 10px 24px; border-radius: 50px; font-size: 15px;"></button>
                                    <button class="btn btn-primary btn-lg" id="next" onclick="nextSoal()" style="padding: 10px 24px; border-radius: 50px; font-size: 15px; display: inline-flex; align-items: center; gap: 8px;">
                                        <span id="text-next" class="mr-2 d-none d-md-inline-block"></span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="overlay d-none" id="loading" style="display: none !important;">
                            <div class="spinner-grow text-primary"></div>
                            <div class="pl-3"><b>MEMUAT SOAL</b></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?= form_open('', array('id' => 'up')) ?>
<input type="hidden" name="siswa" value="<?= isset($siswa->id_siswa) ? $siswa->id_siswa : '' ?>">
<input type="hidden" name="jadwal" value="<?= isset($jadwal->id_jadwal) ? $jadwal->id_jadwal : '' ?>">
<input type="hidden" name="bank" value="<?= isset($jadwal->id_bank) ? $jadwal->id_bank : '' ?>">
<?= form_close() ?>

<script>
/* PREFETCH SOAL: kirim request segera, tanpa menunggu script eksternal & document.ready */
(function () {
    try {
        var sid = '<?= isset($siswa->id_siswa) ? $siswa->id_siswa : "anon" ?>';
        var jid = '<?= isset($jadwal->id_jadwal) ? $jadwal->id_jadwal : "anon" ?>';
        var form = document.getElementById('up');
        if (!form || typeof base_url === 'undefined' || !window.URLSearchParams || !window.FormData) return;
        var n = parseInt(sessionStorage.getItem('last_nomor_' + sid + '_' + jid)) || 1;
        var body = new URLSearchParams(new FormData(form));
        body.append('nomor', n);
        body.append('timer', '00:00:00');
        body.append('elapsed', '0');
        var pf = window.__prefetchSoal = { nomor: n, done: false, data: null, error: false, cb: null };
        var finish = function () { pf.done = true; if (pf.cb) pf.cb(); };
        var xhr = new XMLHttpRequest();
        xhr.open('POST', base_url + 'siswa/loadnomorsoal', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onload = function () {
            try {
                if (xhr.status !== 200) throw new Error('status');
                pf.data = JSON.parse(xhr.responseText);
            } catch (e) { pf.error = true; }
            finish();
        };
        xhr.onerror = function () { pf.error = true; finish(); };
        xhr.send(body.toString());
    } catch (e) { window.__prefetchSoal = null; }
})();
</script>
<script src="<?= base_url() ?>/assets/app/js/redirect.js"></script>
<script src="<?= base_url() ?>/assets/app/js/linker-list.js"></script>
<script src="<?= base_url() ?>/assets/plugins/element-queries/ElementQueries.js"></script>
<script src="<?= base_url() ?>/assets/plugins/element-queries/ResizeSensor.js"></script>
<script src="<?= base_url() ?>/assets/plugins/katex/katex.min.js"></script>
<script>
/* Renderer rumus: membaca format LaTeX di soal, pilihan, tabel, dan jawaban.
   Tetap memakai KaTeX lokal agar tidak membutuhkan koneksi internet. */
(function () {
    window.renderRumusMatematika = function (root) {
        if (!root || !window.katex) return;
        var walker = document.createTreeWalker(root, NodeFilter.SHOW_TEXT, {
            acceptNode: function (node) {
                if (!node.nodeValue || !node.nodeValue.trim()) return NodeFilter.FILTER_REJECT;
                var parent = node.parentElement;
                if (!parent || /^(SCRIPT|STYLE|TEXTAREA|INPUT|CODE|PRE|OPTION)$/.test(parent.tagName) || parent.closest('.katex')) return NodeFilter.FILTER_REJECT;
                return /\\\(|\\\[|\$\$|\\frac|\\sqrt|\\times|\\div|\\sum|\\int/.test(node.nodeValue) ? NodeFilter.FILTER_ACCEPT : NodeFilter.FILTER_REJECT;
            }
        });
        var nodes = [], node;
        while ((node = walker.nextNode())) nodes.push(node);
        nodes.forEach(function (textNode) {
            var source = textNode.nodeValue;
            var re = /\\\(([\s\S]*?)\\\)|\\\[([\s\S]*?)\\\]|\$\$([\s\S]*?)\$\$/g;
            var match, last = 0, frag = document.createDocumentFragment(), found = false;
            while ((match = re.exec(source)) !== null) {
                found = true;
                if (match.index > last) frag.appendChild(document.createTextNode(source.slice(last, match.index)));
                var formula = match[1] !== undefined ? match[1] : (match[2] !== undefined ? match[2] : match[3]);
                var display = match[2] !== undefined || match[3] !== undefined;
                var span = document.createElement('span');
                try {
                    span.innerHTML = katex.renderToString(formula.trim(), {displayMode: display, throwOnError: false, strict: 'ignore'});
                    frag.appendChild(span);
                } catch (e) {
                    frag.appendChild(document.createTextNode(match[0]));
                }
                last = re.lastIndex;
            }
            if (found) {
                if (last < source.length) frag.appendChild(document.createTextNode(source.slice(last)));
                textNode.parentNode.replaceChild(frag, textNode);
            }
        });
    };
})();
</script>
<script>
    var elem = document.documentElement;
    history.pushState(null, null, '<?php echo $_SERVER["REQUEST_URI"]; ?>');
    window.addEventListener('popstate', function (event) {
        let lastNomor = parseInt(sessionStorage.getItem('last_nomor_' + idSiswaGlobal + '_' + idJadwalGlobal)) || 1;
        loadSoalNomor(lastNomor);
    });
    const infoJadwal = JSON.parse(JSON.stringify(<?= json_encode($jadwal) ?>));
    let nomorSoal = 0;
    let idSoal, idSoalSiswa, jenisSoal, modelSoal, typeSoal;
    let jawabanSiswa, jawabanBaru = null, jsonJawaban = {};
    let nav = 0;
    let soalTerjawab = 0, soalTotal = 0;
    let timerOut;
    let timerSelesai;
    let elapsed = '0';
    let h, m, s;
    var message = "Jangan menggunakan klik kanan!";

    let tick = 0;
    const _second = 1000, _minute = _second * 60, _hour = _minute * 60, _day = _hour * 24;
    const durasiUujian = Number(infoJadwal.durasi_ujian);

    let autosaveDebounceTimer = null;
    
    const idSiswaGlobal = '<?= isset($siswa->id_siswa) ? $siswa->id_siswa : "anon" ?>';
    const idJadwalGlobal = '<?= isset($jadwal->id_jadwal) ? $jadwal->id_jadwal : "anon" ?>';
    const storageKey = 'status_soal_' + idSiswaGlobal + '_' + idJadwalGlobal;

    let listStatusSoal = JSON.parse(localStorage.getItem(storageKey)) || {}; 

    /* ===== CACHE SOAL (sessionStorage) agar soal langsung tampil setelah refresh ===== */
    const cachePrefix = 'cache_soal_' + idSiswaGlobal + '_' + idJadwalGlobal + '_';
    let tampilDariCache = false;
    let dataCache = null;

    function simpanCacheSoal(data) {
        try {
            if (!data || !data.durasi || !data.soal_nomor) return;
            sessionStorage.setItem(cachePrefix + parseInt(data.soal_nomor), JSON.stringify(data));
        } catch (e) {
            try { clearSoalCache(); } catch (e2) {}
        }
    }

    function bacaCacheSoal(nomor) {
        try {
            var raw = sessionStorage.getItem(cachePrefix + nomor);
            return raw ? JSON.parse(raw) : null;
        } catch (e) { return null; }
    }

    function hapusCacheNomor(nomor) {
        try { sessionStorage.removeItem(cachePrefix + nomor); } catch (e) {}
    }

    function clearSoalCache() {
        try {
            var keys = [];
            for (var i = 0; i < sessionStorage.length; i++) {
                var k = sessionStorage.key(i);
                if (k && k.indexOf(cachePrefix) === 0) keys.push(k);
            }
            keys.forEach(function (k) { sessionStorage.removeItem(k); });
        } catch (e) {}
    }

    function bandingkanData(a, b) {
        function strip(d) {
            var c = $.extend({}, d);
            delete c.durasi;
            delete c.soal_terjawab;
            return JSON.stringify(c);
        }
        try { return strip(a) === strip(b); } catch (e) { return false; }
    }

    function checkAndSaveJawaban() {
        var jwb1 = jawabanSiswa;
        var jwb2 = jawabanBaru;
        if ($.isArray(jwb1)) jwb1 = JSON.stringify(jwb1);
        if (jwb2 != null && $.isArray(jwb2)) jwb2 = JSON.stringify(jwb2);

        if (jawabanBaru != null && jwb1 !== jwb2) {
            clearTimeout(autosaveDebounceTimer);
            
            var siswa = $('#up').find('input[name="siswa"]').val();
            var bank = $('#up').find('input[name="bank"]').val();

            let formData = new FormData($('#jawab')[0]);
            formData.append('siswa', siswa);
            formData.append('bank', bank);

            const jns = jsonJawaban['jenis'];
            for (const key in jsonJawaban) {
                if ((jns==='2' || jns==='3') && key === 'jawaban_siswa') {
                    formData.append('data['+key+']', JSON.stringify(jsonJawaban[key]));
                } else {
                    formData.append('data['+key+']', jsonJawaban[key]);
                }
            }

            $.ajax({
                url: base_url + 'siswa/savejawaban',
                method: 'POST',
                processData: false,
                contentType: false,
                data: formData,
                success: function (response) {
                    if (response && response.soal_terjawab !== undefined) {
                        soalTerjawab = parseInt(response.soal_terjawab);
                        perbaruiProgressBar();
                    }
                }
            });
            
            jawabanSiswa = jawabanBaru;
        }
    }

    function perbaruiProgressBar() {
        let total = parseInt(soalTotal) || 0;
        let terjawab = parseInt(soalTerjawab) || 0;
        let persentase = total > 0 ? Math.round((terjawab / total) * 100) : 0;
        
        $('#progress-bar').css('width', persentase + '%');
    }

    let sedangMemuatSoal = false;   // ada request soal yang sedang berjalan
    let navDikunci = false;         // tombol Next/Prev sedang dikunci oleh nextSoal/prevSoal
    let failsafeNav = null;

    let scale = 1;
    let baseScale = 1;
    let startDist = 0;
    let isDragging = false;
    let startX = 0, startY = 0;
    let translateX = 0, translateY = 0;
    let baseTranslateX = 0, baseTranslateY = 0;
    let hasMovedSignificant = false;

    const targetElement = document.querySelector('.wrapper');

    function applyTransform() {
        if (scale < 1) scale = 1;
        if (scale > 3) scale = 3; 

        if (scale === 1) {
            translateX = 0;
            translateY = 0;
        } else {
            const maxTx = window.innerWidth * (scale - 1) / 2;
            const minTx = -maxTx;
            const maxTy = targetElement.offsetHeight * (scale - 1) / 2;
            const minTy = -maxTy;

            if (translateX > maxTx) translateX = maxTx;
            if (translateX < minTx) translateX = minTx;
            if (translateY > maxTy) translateY = maxTy;
            if (translateY < minTy) translateY = minTy;
        }
        
        // Saat tidak di-zoom, hapus transform sama sekali. Transform (walau identitas) membuat
        // elemen fixed/tombol salah posisi tap di sebagian HP ketika layar fullscreen.
        if (scale === 1) {
            targetElement.style.transform = '';
        } else {
            targetElement.style.transform = `translate(${translateX}px, ${translateY}px) scale(${scale})`;
        }
    }

    window.addEventListener('touchstart', function(e) {
        if (e.touches.length === 2) {
            startDist = Math.hypot(e.touches[0].pageX - e.touches[1].pageX, e.touches[0].pageY - e.touches[1].pageY);
            baseScale = scale;
            startX = (e.touches[0].pageX + e.touches[1].pageX) / 2;
            startY = (e.touches[0].pageY + e.touches[1].pageY) / 2;
            baseTranslateX = translateX;
            baseTranslateY = translateY;
            isDragging = true;
        } else if (e.touches.length === 1) {
            isDragging = true;
            startX = e.touches[0].pageX;
            startY = e.touches[0].pageY;
            baseTranslateX = translateX;
            baseTranslateY = translateY;
            hasMovedSignificant = false;
        }
    }, { passive: true });

    window.addEventListener('touchmove', function(e) {
        if (e.touches.length === 2 && isDragging) {
            if (e.cancelable) e.preventDefault();
            let dist = Math.hypot(e.touches[0].pageX - e.touches[1].pageX, e.touches[0].pageY - e.touches[1].pageY);
            scale = baseScale * (dist / startDist);
            let currentMidX = (e.touches[0].pageX + e.touches[1].pageX) / 2;
            let currentMidY = (e.touches[0].pageY + e.touches[1].pageY) / 2;
            translateX = baseTranslateX + (currentMidX - startX);
            translateY = baseTranslateY + (currentMidY - startY);
            applyTransform();
        } else if (e.touches.length === 1 && isDragging) {
            let dx = e.touches[0].pageX - startX;
            let dy = e.touches[0].pageY - startY;
            if (Math.abs(dx) > 6 || Math.abs(dy) > 6) hasMovedSignificant = true;

            if (scale > 1) {
                if (e.cancelable) e.preventDefault();
                translateX = baseTranslateX + dx;
                translateY = baseTranslateY + dy;
                applyTransform();
            } else {
                window.scrollBy(0, -dy * 0.1);
            }
        }
    }, { passive: false });

    window.addEventListener('touchend', function(e) {
        if (e.touches.length < 2) baseScale = scale;
        isDragging = false;
        // reset setelah klik sempat diproses, supaya tap berikutnya tidak ikut terblokir
        setTimeout(function () { hasMovedSignificant = false; }, 350);
    });

    // Sentuhan dibatalkan sistem (mis. swipe dari tepi layar / notifikasi saat fullscreen)
    window.addEventListener('touchcancel', function () {
        isDragging = false;
        hasMovedSignificant = false;
        baseScale = scale;
    });

    // Klik hanya diblokir saat soal sedang di-zoom lalu digeser (bukan saat tap biasa)
    window.addEventListener('click', function(e) {
        if (hasMovedSignificant && scale > 1) {
            e.preventDefault();
            e.stopPropagation();
        }
    }, { capture: true });

    // Pulihkan tombol kalau layar berubah (fullscreen masuk/keluar, rotasi, balik dari aplikasi lain)
    function pulihkanSetelahPerubahanLayar() {
        isDragging = false;
        hasMovedSignificant = false;
        setTimeout(function () {
            if (navDikunci && !sedangMemuatSoal) unlockNavigasi();
        }, 400);
    }
    ['fullscreenchange', 'webkitfullscreenchange', 'mozfullscreenchange', 'MSFullscreenChange', 'orientationchange']
        .forEach(function (ev) { document.addEventListener(ev, pulihkanSetelahPerubahanLayar); });
    window.addEventListener('orientationchange', pulihkanSetelahPerubahanLayar);
    window.addEventListener('pageshow', pulihkanSetelahPerubahanLayar);
    document.addEventListener('visibilitychange', function () {
        if (!document.hidden) pulihkanSetelahPerubahanLayar();
    });

    function bukaDaftarSoal() {
        renderGridNomor(); 
        $('#overlaySidebar').fadeIn(150);
        $('#sidebarSoal').addClass('buka');
    }

    function tutupDaftarSoal() {
        $('#overlaySidebar').fadeOut(150);
        $('#sidebarSoal').removeClass('buka');
    }

    function renderGridNomor() {
        let container = $('#place-nomor-nav');
        container.empty(); 
        let total = parseInt(soalTotal) || 1;

        let fragment = document.createDocumentFragment();

        for (let i = 1; i <= total; i++) {
            let btn = document.createElement('button');
            btn.className = 'btn-nomor-nav';
            btn.innerHTML = i;
            
            if (i === nomorSoal) {
                btn.classList.add('aktif');
            } else if (listStatusSoal[i] === true) {
                btn.classList.add('terjawab');
            }

            btn.onclick = function() {
                tutupDaftarSoal();
                nav = i;
                checkAndSaveJawaban();
                loadSoalNomor(i);
            };
            fragment.appendChild(btn);
        }
        container.append(fragment);
    }

    $(document).ready(function () {$(document).keydown(function (event) {
            var charCode = event.charCode || event.keyCode || event.which;
            if (charCode == 27 || charCode == 91 || charCode == 92) return false;
        });

        document.onmousedown = rtclickcheck;
        
        const rulesKey = 'rules_accepted_' + idSiswaGlobal + '_' + idJadwalGlobal;
        if (!sessionStorage.getItem(rulesKey)) {
            swal.fire({
                title: 'Peraturan Ujian',
                html: 'Kerjakan soal dengan serius,<br>jangan nyontek!',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Iya',
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) {
                    sessionStorage.setItem(rulesKey, 'true');
                    try { openFullscreen(); } catch(err) {}
                }
            });
        } else if (!(document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement)) {
            // Setelah refresh layar penuh hilang & browser hanya mengizinkannya lewat klik siswa
            swal.fire({
                title: 'Lanjutkan Ujian',
                html: 'Tekan tombol di bawah untuk kembali ke layar penuh.',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Lanjutkan',
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then((result) => {
                if (result.value) {
                    try { openFullscreen(); } catch(err) {}
                }
            });
        }

        let startNomor = parseInt(sessionStorage.getItem('last_nomor_' + idSiswaGlobal + '_' + idJadwalGlobal)) || 1;
        muatSoalAwal(startNomor);

        $('#jawab').on('submit', function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            checkAndSaveJawaban();
        });
    });

    function muatSoalAwal(startNomor) {
        // 1) Tampilkan dari cache (jika ada) -> langsung muncul
        var cached = bacaCacheSoal(startNomor);
        if (cached && cached.durasi) {
            try {
                setKonten(cached);
                tampilDariCache = true;
                dataCache = cached;
            } catch (e) {
                tampilDariCache = false;
                dataCache = null;
            }
        }

        // 2) Ambil data terbaru dari server (pakai hasil prefetch kalau ada)
        var pf = window.__prefetchSoal;
        var pakaiPrefetch = function () {
            if (pf.error || !pf.data) {
                loadSoalNomor(startNomor, true);
            } else {
                terimaSoal(pf.data, true);
            }
        };
        if (pf && pf.nomor === startNomor) {
            if (pf.done) pakaiPrefetch();
            else pf.cb = pakaiPrefetch;
        } else {
            loadSoalNomor(startNomor, true);
        }
    }

    function terimaSoal(data, isInitial) {
        $('#loading').addClass('d-none');

        if (isInitial && tampilDariCache) {
            tampilDariCache = false;
            // Jika siswa sudah menjawab saat tampilan cache, atau data sama persis: jangan render ulang
            if (jawabanBaru !== null || bandingkanData(dataCache, data)) {
                soalTotal = data.soal_total;
                soalTerjawab = data.soal_terjawab;
                perbaruiProgressBar();
                if (jawabanBaru === null) simpanCacheSoal(data);
                if (data.durasi) applyDurasi(data); else window.location.href = base_url + 'siswa/cbt';
                return;
            }
        }

        simpanCacheSoal(data);
        setKonten(data);

        scale = 1;
        translateX = 0;
        translateY = 0;
        applyTransform();

        window.scrollTo({ top: 0, behavior: 'auto' });
    }

    // Selalu buka kembali kunci tombol navigasi & sembunyikan loading
    function unlockNavigasi() {
        clearTimeout(failsafeNav);
        sedangMemuatSoal = false;
        navDikunci = false;
        $('#loading').addClass('d-none');
        $('#next').removeAttr('disabled');
        if (nomorSoal > 1) $('#prev').removeAttr('disabled');
    }

    // Pengaman: kalau 40 detik tombol masih terkunci, buka paksa
    function pasangFailsafeNav() {
        navDikunci = true;
        clearTimeout(failsafeNav);
        failsafeNav = setTimeout(unlockNavigasi, 40000);
    }

    function loadSoalNomor(nomor, force, percobaan) {
        percobaan = percobaan || 1;
        if (!force && nomor == nomorSoal) { if (navDikunci) unlockNavigasi(); return; }
        $('#loading').removeClass('d-none');

        var dataPost = $('#up').serialize() + '&nomor=' + nomor + '&timer=' + $('#timer').text() + '&elapsed=' + elapsed;
        if (soalTotal === 0 || nomor <= parseInt(soalTotal)) {
            sedangMemuatSoal = true;
            $.ajax({
                type: 'POST',
                url: base_url + 'siswa/loadnomorsoal',
                data: dataPost,
                timeout: 15000, // jangan menggantung selamanya di sinyal lemah
                success: function (data) {
                    clearTimeout(failsafeNav);
                    sedangMemuatSoal = false;
                    navDikunci = false;
                    try {
                        terimaSoal(data, !!force);
                    } catch (e) {
                        console.error(e);
                        unlockNavigasi();
                        showDangerToast('Gagal menampilkan soal, silakan klik lagi');
                    }
                },
                error: function (xhr, error, status) {
                    if (percobaan < 3) {
                        // coba ulang otomatis (maks. 3x) untuk koneksi HP yang tidak stabil
                        setTimeout(function () { loadSoalNomor(nomor, force, percobaan + 1); }, 1000);
                    } else {
                        unlockNavigasi();
                        showDangerToast('Koneksi lemah, soal gagal dimuat. Silakan klik lagi.');
                    }
                }
            });
        } else {
            selesai();
        }
    }

    function loadSoal(datas) {
        nav = $(datas).data('nomorsoal');
        checkAndSaveJawaban();
        loadSoalNomor(nav);
    }

    function setKonten(data) {
        idSoal = data.soal_id;
        idSoalSiswa = data.soal_siswa_id;
        nomorSoal = parseInt(data.soal_nomor);
        jenisSoal = data.soal_jenis;
        soalTerjawab = data.soal_terjawab;
        soalTotal = data.soal_total;
        
        sessionStorage.setItem('last_nomor_' + idSiswaGlobal + '_' + idJadwalGlobal, nomorSoal);
        perbaruiProgressBar();

        jsonJawaban = {};
        jawabanBaru = null;
        jawabanSiswa = data.soal_jawaban_siswa != null ? data.soal_jawaban_siswa : '';
        if ($.isArray(jawabanSiswa)) jawabanSiswa.sort();

        if(jawabanSiswa !== "" && jawabanSiswa !== null && (!$.isArray(jawabanSiswa) || jawabanSiswa.length > 0)) {
            listStatusSoal[nomorSoal] = true;
        } else {
            listStatusSoal[nomorSoal] = false;
        }
        localStorage.setItem(storageKey, JSON.stringify(listStatusSoal));

        if (nomorSoal === 1) {
            $('#prev').attr('disabled', 'disabled');
        } else {
            $('#prev').removeAttr('disabled');
        }

        $('#nomor-soal').html('<span class="d-none d-md-inline mr-1">Nomor</span> ' + nomorSoal);
        $('#konten-soal').html(data.soal_soal);
        var jenis = data.soal_jenis;
        var html = '';
        
        if (jenis == "1") {
            $.each(data.soal_opsi, function (key, opsis) {
                if (opsis.valAlias != "") {
                    html += '<label class="container-jawaban font-weight-normal">' + opsis.opsi +
                        '<input type="radio" name="jawaban" value="' + opsis.value.toUpperCase() + '" data-jawabansiswa="' + opsis.value.toUpperCase() + '" data-jawabanalias="' + opsis.valAlias.toUpperCase() + '" onclick="submitJawaban(this)" ' + opsis.checked + '>' +
                        '<span class="checkmark shadow text-center align-middle">' + opsis.valAlias.toUpperCase() + '</span>' +
                        '</label>';
                }
            });
            $('#konten-jawaban').html(html);
        } else if (jenis == "2") {
            $.each(data.soal_opsi, function (key, opsis) {
                html += '<div class="custom-control custom-checkbox checkbox-xl">' +
                    '<input type="checkbox" class="check2 custom-control-input" id="check'+key+'" name="jawaban" value="' + opsis.value.toUpperCase() + '" data-max="' + data.max_jawaban[0] + '" data-jawabansiswa="' + opsis.value.toUpperCase() + '" onclick="submitJawaban(this)" ' + opsis.checked + '>' +
                    '<label class="custom-control-label font-weight-normal" for="check'+key+'">' + opsis.opsi +'</label>' +
                    '</div>';
            });
            $('#konten-jawaban').html(html);
        } else if (jenis == "3") {
            modelSoal = data.soal_opsi.model;
            typeSoal = data.soal_opsi.type;
            let konten = $('#konten-jawaban');
            konten.html('');

            const dataJawab = data.soal_opsi;
            const copy = $.extend(true, {}, dataJawab);
            let arrData = [copy.tabel[0]];
            
            if (Array.isArray(copy.tbody)) {
                for (let i = 0; i < copy.tbody.length; i++) {
                    let val = copy.tbody[i];
                    for (let j = 0; j < val.length; j++) {
                        if (j === 0) val[j] = copy.tabel[i+1][0];
                    }
                    arrData.push(val);
                }
            } else {
                for (let i = 0; i < copy.tabel.length; i++) {
                    let val = copy.tbody[i];
                    if (val) {
                        for (let j = 0; j < val.length; j++) {
                            if (j === 0) val[j] = copy.tabel[i][0];
                        }
                        arrData.push(val);
                    }
                }
            }

            let keys = 0;
            let dataMax = {};
            $.each(data.max_jawaban, function (key, val) {
                dataMax[keys] = val;
                keys++;
            });

            let objJawaban = { jawaban: arrData, max: dataMax, model: modelSoal, type: typeSoal };
            konten.linkerList({
                enableEditor: false,
                data: objJawaban,
                viewMode: '2',
                id: nomorSoal,
                callback: function (id, data, hasLinks, isOffset) {
                    if (isOffset !== '0') {
                        $.toast({ heading: 'Warning', text: 'Maksimal <b>' + isOffset + ' jawaban', showHideTransition: 'slide', icon: 'error', loaderBg: '#f2a654', position: 'bottom-center' });
                    } else {
                        submitJawaban(data);
                    }
                }
            });
        } else if (jenis == "4") {
            html += '<div class="pr-4"><span class="">JAWABAN:</span><br><div class="row"><div class="col-12 col-sm-8 col-md-6 col-lg-4"><input id="jawaban-isian" class="pl-1 form-control" type="text" name="jawaban" value="' + jawabanSiswa + '" placeholder="Tulis jawaban disini"/><br></div></div></div>';
            $('#konten-jawaban').html(html);
            $("#jawaban-isian").on('input', function () { submitJawaban(null); });
        } else {
            html += '<div class="pr-4"><label>JAWABAN:</label><br><textarea id="jawaban-essai" class="w-100 pl-1" name="jawaban" rows="4">' + jawabanSiswa + '</textarea><br></div>';
            $('#konten-jawaban').html(html);
            $('#jawaban-essai').summernote({
                placeholder: 'Tulis Jawaban disini...',
                tabsize: 2,
                minHeight: 100,
                toolbar: [
                    ['style', ['style']], ['font', ['bold', 'underline', 'clear']], ['fontname', ['fontname']], ['fontsize', ['fontsize']], ['color', ['color']], ['para', ['ul', 'ol', 'paragraph']], ['table', ['table']], ['cleaner',['cleaner']]
                ],
                callbacks: {
                    onKeyup: function() { submitJawaban(null); },
                    onChange: function() { submitJawaban(null); }
                }
            });
        }

        try { rapikanKonten(); } catch (e) {}
        // Render rumus setelah seluruh isi soal dan pilihan selesai dibuat.
        try { window.renderRumusMatematika(document.getElementById('konten-soal')); } catch (e) {}
        try { window.renderRumusMatematika(document.getElementById('konten-jawaban')); } catch (e) {}
        try { perbaikiPecahanPilihan(); } catch (e) {}

        var $imgs =$('.konten-soal-jawab').find('img');
        $.each($imgs, function () {
            var curSrc = $(this).attr('src');
            if (!curSrc.includes("uploads")) return;
            var newSrc = '';
            if (curSrc.indexOf("http") === -1 && curSrc.indexOf("data:image") === -1) {
                newSrc = base_url + curSrc;
                $(this).attr('src', newSrc);
            } else if (curSrc.indexOf(base_url) === -1) {
                var forReplace = curSrc.split('uploads');
                newSrc = base_url + 'uploads' + forReplace[1];
                $(this).attr('src', newSrc);
            }
            $(this).on('load', function () {
                if ($(this).height() > 50)$(this).addClass('img-fluid');
            });
        });

        $('video').css({'width': '100%', 'max-height': '100%'});

        applyDurasi(data);
    }

    /* Hapus paragraf/baris kosong dari data soal yang menyebabkan jarak besar */
    function rapikanKonten() {
        $('#konten-soal, .container-jawaban').find('p, div, span').each(function () {
            var $e = $(this);
            if ($e.hasClass('checkmark')) return;
            // kosong = tidak ada teks dan tidak ada gambar/rumus/tabel/input di dalamnya
            var adaIsi = $e.find('img,svg,math,table,video,audio,iframe,input,canvas,object,.katex').length > 0;
            if (!adaIsi && $.trim($e.text().replace(/\u00a0/g, '')) === '') $e.remove();
        });
        $('#konten-soal br').filter(function () {
            return !this.previousSibling || !this.nextSibling || this.nextSibling.nodeName === 'BR';
        }).remove();
    }

    /* Gambar ulang pecahan di pilihan jawaban yang formatnya rusak (angka bertumpuk tanpa garis).
       Hanya berlaku bila isi pilihan terdiri dari 2 angka (a/b) atau 3 angka (campuran: a b/c)
       yang berada di elemen terpisah. Pilihan lain tidak disentuh. */
    function perbaikiPecahanPilihan() {
        if (!window.katex) return;
        $('#konten-jawaban .container-jawaban, #konten-jawaban .custom-control-label').each(function () {
            var label = this;
            var $k = $(label).find('.katex');
            if ($k.length) {
                // Rumus KaTeX yang sudah ada: gambar ulang dengan gaya penuh & sans-serif
                $k.each(function () {
                    var el = this;
                    if ($(el).closest('.katex-display').length) return;
                    var ann = el.querySelector('annotation[encoding="application/x-tex"]');
                    if (!ann) return;
                    var src = ann.textContent.trim();
                    if (/\\displaystyle|\\mathsf/.test(src)) return;
                    try {
                        var tmp = document.createElement('span');
                        tmp.innerHTML = katex.renderToString('\\displaystyle\\mathsf{' + src + '}', {throwOnError: false, strict: 'ignore'});
                        if (tmp.firstChild) el.parentNode.replaceChild(tmp.firstChild, el);
                    } catch (e) {}
                });
                return;
            }
            var nodes = $.makeArray(label.childNodes).filter(function (n) {
                return !(n.nodeType === 1 && (n.tagName === 'INPUT' || $(n).hasClass('checkmark')));
            });
            if (!nodes.some(function (n) { return n.nodeType === 1; })) return;
            var leaves = [];
            nodes.forEach(function (n) {
                if (n.nodeType === 3) {
                    var v0 = n.nodeValue.replace(/\u00a0/g, ' ').trim();
                    if (v0) leaves.push(v0);
                    return;
                }
                var w = document.createTreeWalker(n, NodeFilter.SHOW_TEXT, null), t;
                while ((t = w.nextNode())) {
                    var v = t.nodeValue.replace(/\u00a0/g, ' ').trim();
                    if (v) leaves.push(v);
                }
            });
            if (leaves.length < 2 || leaves.length > 3) return;
            if (!leaves.every(function (x) { return /^\d+$/.test(x); })) return;
            var tex = leaves.length === 2
                ? '\\frac{' + leaves[0] + '}{' + leaves[1] + '}'
                : leaves[0] + '\\frac{' + leaves[1] + '}{' + leaves[2] + '}';
            // gaya tampilan penuh + huruf sans-serif agar ukurannya sama dengan pecahan di soal
            tex = '\\displaystyle\\mathsf{' + tex + '}';
            var span = document.createElement('span');
            try {
                span.innerHTML = katex.renderToString(tex, {throwOnError: false, strict: 'ignore'});
            } catch (e) { return; }
            label.insertBefore(span, nodes[0]);
            nodes.forEach(function (n) { label.removeChild(n); });
        });
    }

    function applyDurasi(data) {
        if (!data.durasi) {
            window.location.href = base_url + 'siswa/cbt';
        } else {
            setElapsed(data.durasi);
            if (timerSelesai) { clearTimeout(timerSelesai); timerSelesai = null; }

            var next = $('#next');
            next.removeAttr('disabled');
            var txtnext = $('#text-next');
            $('#ic-btn').remove();

            if (soalTotal === nomorSoal && data.durasi.mulai != null && data.durasi.mulai != '0') {
                next.removeClass('btn-primary').addClass('btn-success').append('<i id="ic-btn" class="fa fa-check-circle" style="font-size: 18px;"></i>');
                txtnext.html('<b>Selesai</b>');
                setTimerSelesai(next, data.durasi);
            } else {
                $('#timer-selesai').addClass('d-none');
                next.removeClass('d-none btn-success').addClass('btn-primary').append('<i id="ic-btn" class="fa fa-arrow-circle-right" style="font-size: 18px;"></i>');
                txtnext.html('<b>Soal Berikutnya</b>');
            }
        }
    }

    function setElapsed(durasi) {
        elapsed = durasi.lama_ujian == null || durasi.lama_ujian == '0' ? "00:00:00" : durasi.lama_ujian;
        createTimerCountdown(durasiUujian, elapsed.split(':'), function (isOver, remaining, onGoing) {
            $('#timer').html(remaining);
            elapsed = onGoing;
            if (isOver) {
                $('#prev, #next').attr('disabled', 'disabled');
                var siswa = $('#up').find('input[name="siswa"]').val();
                var bank = $('#up').find('input[name="bank"]').val();
                var jadwal = $('#up').find('input[name="jadwal"]').val();

                clearTimeout(autosaveDebounceTimer);

                $.ajax({
                    url: base_url + 'siswa/savejawaban',
                    method: 'POST',
                    data: $('#jawab').serialize() + '&jadwal=' + jadwal + '&siswa=' + siswa + '&bank=' + bank + '&waktu=' + $('#timer').text() + '&elapsed=' + elapsed + '&data=' + JSON.stringify(jsonJawaban),
                    success: function () {
                        $('.konten-soal-jawab').html('');
                        localStorage.removeItem(storageKey); 
                        sessionStorage.removeItem('last_nomor_' + idSiswaGlobal + '_' + idJadwalGlobal);
                        clearSoalCache();
                        sessionStorage.removeItem('rules_accepted_' + idSiswaGlobal + '_' + idJadwalGlobal);
                        dialogWaktu();
                    }
                });
            }
        });
    }

    function nextSoal() {
        $('#next').attr('disabled', 'disabled');
        pasangFailsafeNav();
        nav = (nomorSoal + 1);
        checkAndSaveJawaban();
        loadSoalNomor(nav);
    }

    function prevSoal() {
        $('#prev').attr('disabled', 'disabled');
        pasangFailsafeNav();
        nav = (nomorSoal - 1);
        checkAndSaveJawaban();
        loadSoalNomor(nav);
    }

    function setTimerSelesai(next, durasi) {
        const perdetik = 1000, permenit = 60 * perdetik, perjam = 60 * permenit;
        const t_dur = Number(infoJadwal.jarak) * (1000 * 60);
        const elapsed = (durasi.lama_ujian == null || durasi.lama_ujian == '0' ? "00:00:00" : durasi.lama_ujian).split(':');
        let t_jam = Number(elapsed[0]), t_mnt = Number(elapsed[1]), t_dtk = Number(elapsed[2]);
        const btnTimer = $('#timer-selesai');

        setTimer();
        function setTimer() {
            if (timerSelesai) { clearTimeout(timerSelesai); timerSelesai = null; }
            const elapsedMicro = (t_jam * perjam) + (t_mnt * permenit) + (t_dtk * perdetik);
            const t_remaining = t_dur - elapsedMicro;
            if (t_remaining <= 0) {
                next.removeClass('d-none');
                btnTimer.addClass('d-none');
            } else {
                t_dtk++;
                if (t_dtk > 59) { t_dtk = 0; t_mnt++; }
                if (t_mnt > 59) { t_mnt = 0; t_jam++; }

                const r_jam = Math.floor(t_remaining / perjam);
                const r_mnt = Math.floor((t_remaining % perjam) / permenit);
                const r_dtk = Math.floor((t_remaining % permenit) / perdetik);
                next.addClass('d-none');
                btnTimer.removeClass('d-none').html(zeroPad(r_jam) + ':' + zeroPad(r_mnt) + ':' + zeroPad(r_dtk));
                timerSelesai = setTimeout(setTimer, 1000);
            }
        }
    }

    function submitJawaban(opsi) {
        hapusCacheNomor(nomorSoal);
        var jawaban_Siswa = '', jawaban_Alias = '';
        if (jenisSoal == 1) {
            jawaban_Siswa = $(opsi).data('jawabansiswa');
            jawaban_Alias = $(opsi).data('jawabanalias');
        } else if (jenisSoal == 2) {
            var selected = [];
            $('#konten-jawaban input:checked').each(function () { selected.push($(this).val()); });
            jawaban_Siswa = selected;
        } else if (jenisSoal == 3) {
            jawaban_Siswa = opsi;
        } else if (jenisSoal == 4){
            jawaban_Siswa = $('#jawaban-isian').val();
        } else {
            jawaban_Siswa = $('#jawaban-essai').summernote('code');
        }
        jawabanBaru = jawaban_Siswa;
        if (jenisSoal == 2 && $.isArray(jawabanBaru)) jawabanBaru.sort();

        jsonJawaban = createJsonJawaban(jawaban_Alias, jawaban_Siswa);

        if(jawabanBaru !== "" && jawabanBaru !== null && (!$.isArray(jawabanBaru) || jawabanBaru.length > 0)) {
            listStatusSoal[nomorSoal] = true;
        } else {
            listStatusSoal[nomorSoal] = false;
        }
        localStorage.setItem(storageKey, JSON.stringify(listStatusSoal));

        clearTimeout(autosaveDebounceTimer);

        if (jenisSoal == 1 || jenisSoal == 2) {
            checkAndSaveJawaban();
        } else {
            autosaveDebounceTimer = setTimeout(function() {
                checkAndSaveJawaban();
            }, 1000);
        }
    }

    function createJsonJawaban(jawab_Alias, jawab_Siswa) {
        var siswa = $('#up').find('input[name="siswa"]').val();
        var jadwal = $('#up').find('input[name="jadwal"]').val();
        var bank = $('#up').find('input[name="bank"]').val();

        var item = {};
        item ["no_soal_alias"] = nomorSoal;
        item ["jawaban_alias"] = jawab_Alias;
        item ["jawaban_siswa"] = jawab_Siswa;
        item ["jenis"] = jenisSoal;
        item ["id_soal"] = idSoal;
        item ["id_soal_siswa"] = idSoalSiswa;
        item ["id_jadwal"] = jadwal;
        item ["id_bank"] = bank;
        item ["id_siswa"] = siswa;
        return item;
    }

    /* MODIFIKASI: Menambahkan notifikasi soal yang belum dikerjakan beserta nomor soalnya */
    function selesai() {
        if (soalTotal === soalTerjawab) {
            swal.fire({
                title: "Kamu yakin?",
                text: "Kamu akan menyelesaikan ujian",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Selesaikan!"
            }).then(result => {
                if (result.value) {
                    $('#loading').removeClass('d-none');
                    clearTimeout(autosaveDebounceTimer);

                    $.ajax({
                        url: base_url + 'siswa/selesaiujian',
                        method: "POST",
                        data: $('#up').serialize(),
                        success: function (respon) {
                            $('#next').removeAttr('disabled');
                            $('#loading').addClass('d-none');
                            if (respon.status) {
                                localStorage.removeItem(storageKey); 
                                sessionStorage.removeItem('last_nomor_' + idSiswaGlobal + '_' + idJadwalGlobal);
                        clearSoalCache();
                                sessionStorage.removeItem('rules_accepted_' + idSiswaGlobal + '_' + idJadwalGlobal);
                                // Pesan selamat, setelah OK logout lalu ke halaman login siswa
                                swal.fire({
                                    title: "",
                                    html:
                                        '<style>' +
                                        '@keyframes jempolMuncul{0%{transform:scale(0) rotate(-30deg);opacity:0}60%{transform:scale(1.25) rotate(10deg);opacity:1}100%{transform:scale(1) rotate(0)}}' +
                                        '@keyframes jempolGoyang{0%,100%{transform:rotate(0) translateY(0)}25%{transform:rotate(-14deg) translateY(-6px)}75%{transform:rotate(10deg) translateY(-3px)}}' +
                                        '.jempol-wrap{display:flex;justify-content:center;margin:8px 0 14px}' +
                                        '.jempol-ikon{font-size:84px;line-height:1;color:#28a745;display:inline-block;transform-origin:bottom left;' +
                                        'animation:jempolMuncul .7s ease-out both,jempolGoyang 1.4s ease-in-out .8s infinite}' +
                                        '</style>' +
                                        '<div class="jempol-wrap"><i class="fa fa-thumbs-up jempol-ikon"></i></div>' +
                                        '<div class="selamat-judul">Selamat!</div>' +
                                        '<div class="selamat-teks">Anda telah menyelesaikan ujian.</div>',
                                    confirmButtonText: "OK",
                                    allowOutsideClick: false,
                                    allowEscapeKey: false
                                }).then(function () {
                                    window.location.href = base_url + 'logout';
                                });
                            } else {
                                swal.fire({ title: "Gagal", text: "Tidak bisa menyelesaikan ujian", icon: "error" });
                            }
                        },
                        error: function () {
                            // Koneksi gagal: tombol tetap bisa dipakai lagi
                            $('#next').removeAttr('disabled');
                            $('#loading').addClass('d-none');
                            swal.fire({ title: "Gagal", text: "Koneksi bermasalah, coba lagi", icon: "error" });
                        }
                    });
                } else {
                    // Siswa menekan Batal / menutup dialog: aktifkan kembali tombol Selesai
                    $('#next').removeAttr('disabled');
                    $('#loading').addClass('d-none');
                }
            });
        } else {
            // Mengambil daftar nomor soal yang belum dikerjakan
            let belumDijawab = [];
            for (let i = 1; i <= soalTotal; i++) {
                if (!listStatusSoal[i]) {
                    belumDijawab.push(i);
                }
            }
            let pesan = "Masih ada soal yang belum dikerjakan.<br><br>Nomor yang belum dijawab:<br><b style='color:red;'>" + belumDijawab.join(', ') + "</b>";
            swal.fire({
                title: "BELUM SELESAI!",
                html: pesan,
                icon: "warning",
                confirmButtonText: "OK",
                allowOutsideClick: false
            }).then(function () {
                // Aktifkan kembali tombol (sempat dinonaktifkan oleh nextSoal)
                $('#next').removeAttr('disabled');
                $('#loading').addClass('d-none');
                // Langsung arahkan ke soal pertama yang belum dijawab
                if (belumDijawab.length > 0) {
                    nav = belumDijawab[0];
                    checkAndSaveJawaban();
                    loadSoalNomor(nav);
                }
            });
        }
    }

    function dialogWaktu() {
        swal.fire({ title: "Sudah Habis", text: "Waktu Ujian sudah habis", icon: "warning", allowOutsideClick: false, confirmButtonText: "OK" }).then(() => {
            window.location.href = base_url + 'logout';
        });
    }

    function rtclickcheck(keyp) {
        if (navigator.appName == "Netscape" && keyp.which == 3) { alert(message); return false; }
        if (navigator.appVersion.indexOf("MSIE") != -1 && event.button == 2) { alert(message); return false; }
    }

    function openFullscreen() {
        if (elem.requestFullscreen) elem.requestFullscreen();
        else if (elem.mozRequestFullScreen) elem.mozRequestFullScreen();
        else if (elem.webkitRequestFullscreen) elem.webkitRequestFullscreen();
        else if (elem.msRequestFullscreen) { elem = window.top.document.body; elem.msRequestFullscreen(); }
    }

    function createTimerCountdown(durasi, elapsed, func) {
        const perdetik = 1000, permenit = 60 * perdetik, perjam = 60 * permenit;
        const t_dur = durasi * (1000 * 60);
        let t_jam = Number(elapsed[0]), t_mnt = Number(elapsed[1]), t_dtk = Number(elapsed[2]);

        testTimer();
        function testTimer() {
            if (timerOut) { clearTimeout(timerOut); timerOut = null; }
            const elapsedMicro = (t_jam * perjam) + (t_mnt * permenit) + (t_dtk * perdetik);
            const t_remaining = t_dur - elapsedMicro;
            if (t_remaining <= 0) {
                if (func) func(true, 'Waktu habis', zeroPad(t_jam) + ':' + zeroPad(t_mnt) + ':' + zeroPad(t_dtk));
            } else {
                t_dtk++;
                if (t_dtk > 59) { t_dtk = 0; t_mnt++; }
                if (t_mnt > 59) { t_mnt = 0; t_jam++; }

                const r_jam = Math.floor(t_remaining / perjam);
                const r_mnt = Math.floor((t_remaining % perjam) / permenit);
                const r_dtk = Math.floor((t_remaining % permenit) / perdetik);

                if (r_jam === 0 && r_mnt < 10) {
                    $('#timer').addClass('timer-critical');
                } else {
                    $('#timer').removeClass('timer-critical');
                }

                if (func) func(false, zeroPad(r_jam) + ':' + zeroPad(r_mnt) + ':' + zeroPad(r_dtk), zeroPad(t_jam) + ':' + zeroPad(t_mnt) + ':' + zeroPad(t_dtk));
                timerOut = setTimeout(testTimer, 1000);
            }
        }
    }

    function zeroPad(no) { return no < 10 ? '0' + no : no; }
</script>