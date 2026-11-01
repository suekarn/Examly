<?php
/**
 * Created by IntelliJ IDEA.
 * User: multazam
 * Date: 07/07/20
 * Time: 17:20
 */

$XA = isset($convert) ? $convert['xa'] : 0; 
$XB = isset($convert) ? $convert['xb'] : 20;  
$YA = isset($convert) ? $convert['ya'] : 100; 
$YB = isset($convert) ? $convert['yb'] : 60;  

$colWidth = '';
$cols_name = ["PG", "PK", "JOD", "IS", "ES"];
?>

<style>
    /* Tema premium ringan: 100% CSS lokal. Tanpa font/gambar/library eksternal,
       tanpa blur, tanpa filter, tanpa animasi berulang -> aman untuk offline & PC spesifikasi rendah.
       Semua warna memakai variabel, sehingga teks tetap terbaca di mode siang maupun mode malam
       (mode malam aktif lewat class "dark-mode" milik AdminLTE pada <body>). */
    :root {
        --p-navy: #14264a;          /* latar header tabel & tombol utama */
        --p-head: #14264a;          /* teks judul / nama */
        --p-blue: #2456a6;          /* garis fokus & hover */
        --p-blue-text: #2456a6;     /* teks aksen biru */
        --p-blue-soft: #e9f0fb;
        --p-blue-line: #cfdcf3;
        --p-blue-hover: #d4e3fa;
        --p-gold: #c99a2e;
        --p-gold-text: #8a5f06;
        --p-ink: #1f2c44;
        --p-table-text: #2f3f58;
        --p-muted: #6b7a92;
        --p-line: #e2e8f1;
        --p-row-line: #edf1f7;
        --p-input-line: #d5deea;
        --p-bg: #f2f5fa;
        --p-surface: #ffffff;
        --p-surface-2: #f8faff;
        --p-stripe: #f7f9fd;
        --p-hover: #eaf1fc;
        --p-flash: #fff3c4;
        --p-overlay: rgba(255, 255, 255, .8);
        --p-shadow: rgba(20, 38, 74, .06);
        --p-ok: #15803d;
        --p-bad: #c2413b;

        --p-live-bg: #fff1f0;
        --p-live-text: #c62828;
        --p-live-line: #ffd0cc;

        --p-info-bg: #e8f1ff;    --p-info-line: #c9dcf8;    --p-info-text: #1d58a6;    --p-info-hover: #d9e8ff;
        --p-succ-bg: #e7f7ee;    --p-succ-line: #c4e8d3;    --p-succ-text: #15803d;    --p-succ-hover: #d6f1e2;
        --p-dang-bg: #fff0ee;    --p-dang-line: #ffd3ce;    --p-dang-text: #b4352f;    --p-dang-hover: #ffe2de;
        --p-warn-bg: #fff6e2;    --p-warn-line: #f3dca6;    --p-warn-text: #85580a;    --p-warn-hover: #ffedc4;

        --p-right-bg: #d9f6e4;   --p-right-text: #136b3a;
        --p-wrong-bg: #ffe1d6;   --p-wrong-text: #a8381a;

        --p-font: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    /* ---------- Mode malam ---------- */
    body.dark-mode,
    .dark-mode,
    html[data-theme="dark"],
    html[data-bs-theme="dark"],
    body[data-theme="dark"] {
        --p-navy: #1f3a6e;
        --p-head: #e8eefb;
        --p-blue: #5b8fe0;
        --p-blue-text: #8db6f5;
        --p-blue-soft: #24395e;
        --p-blue-line: #38558a;
        --p-blue-hover: #2e4a7a;
        --p-gold: #e0b44a;
        --p-gold-text: #f2c75c;
        --p-ink: #e5eaf3;
        --p-table-text: #d9e1ef;
        --p-muted: #a3b2c9;
        --p-line: #334158;
        --p-row-line: #2a3649;
        --p-input-line: #43536e;
        --p-bg: #111827;
        --p-surface: #1b2433;
        --p-surface-2: #202b3d;
        --p-stripe: #1f2938;
        --p-hover: #28364d;
        --p-flash: #5a4a14;
        --p-overlay: rgba(17, 24, 39, .8);
        --p-shadow: rgba(0, 0, 0, .35);
        --p-ok: #4ade80;
        --p-bad: #f87171;

        --p-live-bg: #4a1f24;    --p-live-text: #ffb4ae;    --p-live-line: #7a3a40;

        --p-info-bg: #1f3a63;    --p-info-line: #35588f;    --p-info-text: #a9ccff;    --p-info-hover: #28487a;
        --p-succ-bg: #173a2a;    --p-succ-line: #2b6046;    --p-succ-text: #7be0a5;    --p-succ-hover: #1f4a36;
        --p-dang-bg: #4a2220;    --p-dang-line: #7a3b37;    --p-dang-text: #ffaaa3;    --p-dang-hover: #5a2a27;
        --p-warn-bg: #4a3a14;    --p-warn-line: #7a6125;    --p-warn-text: #ffd27a;    --p-warn-hover: #5a4719;

        --p-right-bg: #17402b;   --p-right-text: #8df0b3;
        --p-wrong-bg: #4d2616;   --p-wrong-text: #ffb59a;
    }

    .content-wrapper {
        background: var(--p-bg) !important;
        color: var(--p-ink);
        font-family: var(--p-font);
        min-height: 100%;
    }

    /* ---------- Judul halaman ---------- */
    .content-header { padding: 24px 1.25rem 10px; }
    .content-header h1 {
        color: var(--p-head);
        font-family: var(--p-font);
        font-size: 1.6rem;
        font-weight: 800;
        letter-spacing: -.03em;
        margin: 0;
        padding-left: 14px;
        border-left: 5px solid var(--p-gold);
        line-height: 1.2;
    }
    #live-indicator {
        border-radius: 999px;
        padding: 5px 12px;
        background: var(--p-live-bg) !important;
        color: var(--p-live-text) !important;
        border: 1px solid var(--p-live-line);
        font-size: 12px !important;
        font-weight: 800;
        letter-spacing: .06em;
        box-shadow: none;
    }

    /* ---------- Kartu utama ---------- */
    #main-card-container {
        border: 1px solid var(--p-line);
        border-top: 4px solid var(--p-navy);
        border-radius: 14px;
        background: var(--p-surface);
        color: var(--p-ink);
        box-shadow: 0 2px 10px var(--p-shadow) !important;
        overflow: hidden;
    }
    #main-card-container > .card-header {
        background: var(--p-surface-2);
        border-bottom: 1px solid var(--p-line);
        padding: 16px 22px;
    }
    #main-card-container > .card-header .card-title {
        color: var(--p-head);
        font-size: .95rem;
        font-weight: 700;
        letter-spacing: .01em;
        margin: 0;
    }
    #main-card-container > .card-body { padding: 22px; color: var(--p-ink); }
    #ajax-content-area .text-muted { color: var(--p-muted) !important; }

    /* ---------- Filter (Kelas / Jadwal) ---------- */
    #main-card-container .input-group {
        border: 1px solid var(--p-input-line);
        border-radius: 10px;
        overflow: hidden;
        background: var(--p-surface);
    }
    #main-card-container .input-group:focus-within { border-color: var(--p-blue); }
    #main-card-container .input-group-prepend .input-group-text {
        min-width: 80px;
        justify-content: center;
        background: var(--p-blue-soft);
        border: 0;
        border-right: 1px solid var(--p-input-line);
        color: var(--p-blue-text);
        font-size: .78rem;
        font-weight: 800;
        letter-spacing: .06em;
        text-transform: uppercase;
    }
    #main-card-container .form-control {
        min-height: 40px;
        border: 0;
        background: var(--p-surface);
        color: var(--p-ink);
        box-shadow: none;
        font-size: .9rem;
    }
    #main-card-container .form-control option { background: var(--p-surface); color: var(--p-ink); }
    #main-card-container .select2-container--bootstrap4 .select2-selection {
        border: 0 !important;
        min-height: 40px;
        background: var(--p-surface) !important;
    }
    .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered,
    .select2-container--bootstrap4 .select2-selection--single .select2-selection__placeholder {
        color: var(--p-ink) !important;
    }
    .select2-container--bootstrap4 .select2-selection--single .select2-selection__arrow b { border-top-color: var(--p-muted) !important; }
    .select2-container--bootstrap4 .select2-dropdown {
        background: var(--p-surface);
        border-color: var(--p-input-line);
        color: var(--p-ink);
    }
    .select2-container--bootstrap4 .select2-results__option { color: var(--p-ink); background: var(--p-surface); }
    .select2-container--bootstrap4 .select2-results__option[aria-selected=true] { background: var(--p-blue-soft); color: var(--p-blue-text); }
    .select2-container--bootstrap4 .select2-results__option--highlighted,
    .select2-container--bootstrap4 .select2-results__option--highlighted[aria-selected] { background: var(--p-blue) !important; color: #fff !important; }
    .select2-container--bootstrap4 .select2-search--dropdown .select2-search__field {
        background: var(--p-surface);
        color: var(--p-ink);
        border-color: var(--p-input-line);
    }

    /* ---------- Tombol ---------- */
    #main-card-container .btn {
        min-height: 38px;
        border-radius: 9px;
        padding: 7px 14px;
        font-size: .83rem;
        font-weight: 700;
        letter-spacing: .01em;
        box-shadow: none;
        transition: background-color .12s ease, border-color .12s ease;
    }
    #main-card-container .btn-info    { background: var(--p-info-bg); border: 1px solid var(--p-info-line); color: var(--p-info-text); }
    #main-card-container .btn-success { background: var(--p-succ-bg); border: 1px solid var(--p-succ-line); color: var(--p-succ-text); }
    #main-card-container .btn-primary { background: var(--p-navy); border: 1px solid var(--p-navy); color: #fff; }
    #main-card-container .btn-danger  { background: var(--p-dang-bg); border: 1px solid var(--p-dang-line); color: var(--p-dang-text); }
    #main-card-container .btn-warning { background: var(--p-warn-bg); border: 1px solid var(--p-warn-line); color: var(--p-warn-text); }
    #main-card-container .btn-info:hover:not(:disabled)    { background: var(--p-info-hover); color: var(--p-info-text); }
    #main-card-container .btn-success:hover:not(:disabled) { background: var(--p-succ-hover); color: var(--p-succ-text); }
    #main-card-container .btn-primary:hover:not(:disabled) { background: var(--p-blue); border-color: var(--p-blue); color: #fff; }
    #main-card-container .btn-danger:hover:not(:disabled)  { background: var(--p-dang-hover); color: var(--p-dang-text); }
    #main-card-container .btn-warning:hover:not(:disabled) { background: var(--p-warn-hover); color: var(--p-warn-text); }
    #main-card-container .btn:disabled,
    #main-card-container .btn.btn-disabled { opacity: .5; cursor: not-allowed; }
    #main-card-container hr { border-top: 1px solid var(--p-line); margin: 18px 0; }

    /* ---------- Toolbar ekspor ---------- */
    #group-export {
        align-items: center;
        gap: 8px;
        margin: 0 0 14px;
        float: none !important;
        justify-content: flex-end;
    }
    #group-export .btn { margin-right: 0 !important; }
    #group-export label { color: var(--p-muted); font-size: .84rem; font-weight: 700; }
    #nilai-detail { accent-color: var(--p-blue); cursor: pointer; }
    #info { clear: both; margin-top: 12px !important; }
    #info-ujian { margin-bottom: 12px; color: var(--p-ink); }

    /* ---------- Tabel nilai ---------- */
    #table-status {
        width: 100%;
        margin-bottom: 0;
        background: var(--p-surface);
        border: 1px solid var(--p-line) !important;
        border-collapse: separate !important;
        border-spacing: 0;
        border-radius: 10px;
        overflow: hidden;
        color: var(--p-table-text);
        font-family: var(--p-font);
        font-size: .85rem;
    }
    #table-status thead th {
        background: var(--p-navy) !important;
        color: #fff !important;
        border: 0 !important;
        border-right: 1px solid rgba(255, 255, 255, .12) !important;
        border-bottom: 1px solid rgba(255, 255, 255, .12) !important;
        padding: 11px 8px;
        font-size: .72rem;
        font-weight: 800;
        letter-spacing: .07em;
        text-transform: uppercase;
        white-space: nowrap;
    }
    #table-status thead th:last-child { border-right: 0 !important; }
    #table-status tbody td {
        border: 0 !important;
        border-bottom: 1px solid var(--p-row-line) !important;
        padding: 9px 10px !important;
        vertical-align: middle;
        color: var(--p-table-text);
    }
    #table-status.table-striped tbody tr:nth-of-type(odd)  { background: var(--p-surface); }
    #table-status.table-striped tbody tr:nth-of-type(even) { background: var(--p-stripe); }
    #table-status tbody tr:hover { background: var(--p-hover) !important; }
    #table-status tbody tr:last-child td { border-bottom: 0 !important; }
    #table-status td:nth-child(2) {
        color: var(--p-head);
        font-weight: 700;
        min-width: 160px;
        text-align: left !important;
    }
    #table-status td:nth-child(1) { color: var(--p-muted); font-weight: 600; }

    /* Warna angka */
    #table-status .text-success { color: var(--p-ok) !important; }
    #table-status .text-danger  { color: var(--p-bad) !important; }
    #table-status .text-info    { color: var(--p-blue-text) !important; }

    /* Kotak jawaban PG (hijau = benar, oranye = salah), tetap kompatibel dengan ekspor Excel */
    #table-status td.no-soal-siswa { font-size: .78rem; font-weight: 700; padding: 6px 4px !important; min-width: 26px; }
    #table-status td[data-fill-color="00E676"] { background: var(--p-right-bg) !important; color: var(--p-right-text); }
    #table-status td[data-fill-color="FF7043"] { background: var(--p-wrong-bg) !important; color: var(--p-wrong-text); }

    /* Nilai akhir menonjol */
    #table-status td.siswa-skor-total b {
        display: inline-block;
        min-width: 46px;
        padding: 3px 10px;
        border-radius: 999px;
        background: var(--p-blue-soft);
        color: var(--p-head);
        font-size: .9rem;
        font-weight: 800;
    }
    #table-status td.siswa-skor-total b:empty { display: none; }
    #table-status td.siswa-skor-total + td b { color: var(--p-gold-text); font-weight: 800; }

    /* ---------- Kolom aksi ---------- */
    #table-status .kolom-aksi { min-width: 170px; white-space: nowrap; }
    #table-status .kolom-aksi .btn {
        min-height: 30px;
        padding: 4px 11px !important;
        border-radius: 999px;
        font-size: .74rem !important;
        font-weight: 700 !important;
    }
    #table-status .kolom-aksi .btn-success,
    #table-status .kolom-aksi .bg-success {
        background: var(--p-succ-bg) !important;
        border: 1px solid var(--p-succ-line) !important;
        color: var(--p-succ-text) !important;
    }
    #table-status .kolom-aksi .btn-primary {
        background: var(--p-blue-soft) !important;
        border: 1px solid var(--p-blue-line) !important;
        color: var(--p-blue-text) !important;
    }
    #table-status .kolom-aksi .btn:hover { background: var(--p-blue-hover) !important; }
    #table-status .kolom-aksi .btn.disabled { opacity: .45; }

    /* ---------- Efek update live (sekali, ringan) ---------- */
    .score-updated { animation: score-highlight .7s ease-out 1; }
    @keyframes score-highlight {
        0%   { background-color: var(--p-flash); }
        100% { background-color: transparent; }
    }
    .live-blink { animation: none; }
    #loading { background: var(--p-overlay); }
    #loading .spinner-grow { color: var(--p-blue-text) !important; }

    /* ---------- Modal Katrol ---------- */
    #perbaikanModal .modal-content {
        background: var(--p-surface);
        color: var(--p-ink);
        border: 1px solid var(--p-line);
        border-top: 4px solid var(--p-navy);
        border-radius: 14px;
        box-shadow: 0 10px 30px rgba(20, 38, 74, .18);
        overflow: hidden;
    }
    #perbaikanModal .modal-header { background: var(--p-surface-2); border-bottom: 1px solid var(--p-line); }
    #perbaikanModal .modal-title  { color: var(--p-head); font-weight: 800; }
    #perbaikanModal .modal-body   { color: var(--p-ink); }
    #perbaikanModal .modal-footer { border-top: 1px solid var(--p-line); background: var(--p-surface-2); }
    #perbaikanModal .col-form-label { color: var(--p-ink); }
    #perbaikanModal .close { color: var(--p-ink); text-shadow: none; opacity: .75; }
    #perbaikanModal .close:hover { color: var(--p-head); opacity: 1; }
    #perbaikanModal .form-control {
        background: var(--p-surface);
        color: var(--p-ink);
        border: 1px solid var(--p-input-line);
        border-radius: 8px;
    }
    #perbaikanModal .form-control:focus { border-color: var(--p-blue); box-shadow: none; }
    #perbaikanModal small { color: var(--p-muted); }
    #perbaikanModal .btn-secondary {
        background: var(--p-blue-soft);
        border: 1px solid var(--p-blue-line);
        color: var(--p-blue-text);
        font-weight: 700;
    }
    #perbaikanModal .btn-primary { background: var(--p-navy); border: 1px solid var(--p-navy); color: #fff; font-weight: 700; }

    /* ---------- Responsif ---------- */
    @media (max-width: 767.98px) {
        .content-header { padding: 16px 12px 8px; }
        .content-header h1 { font-size: 1.25rem; }
        #main-card-container > .card-body { padding: 14px; }
        #group-export { justify-content: flex-start; }
        #info { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        #table-status { min-width: 780px; }
    }
    @media (prefers-reduced-motion: reduce) {
        .score-updated, .live-blink { animation: none !important; }
        #main-card-container .btn { transition: none; }
    }
    @media print {
        .content-wrapper { background: #fff !important; }
        #main-card-container { box-shadow: none !important; border: 0; }
    }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?= $judul ?> <span class="badge badge-danger live-blink" id="live-indicator" style="font-size: 13px; display:none; vertical-align: middle; margin-left: 10px;">🔴 LIVE ACTIVE</span></h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-default my-shadow mb-4" id="main-card-container">
                <div class="card-header">
                    <h6 class="card-title"><?= $subjudul ?></h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php $dnone = $kelas_selected == null ? 'class="d-none"' : ''; ?>
                        <div class="col-md-4" id="by-kelas">
                            <div class="input-group">
                                <div class="input-group-prepend w-30">
                                    <span class="input-group-text">Kelas</span>
                                </div>
                                <?php echo form_dropdown('kelas', $kelas, $kelas_selected, 'id="kelas" class="form-control"'); ?>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="input-group">
                                <div class="input-group-prepend w-30">
                                    <span class="input-group-text">Jadwal</span>
                                </div>
                                <?php echo form_dropdown('jadwal', $jadwal, $jadwal_selected, 'id="jadwal" class="form-control"'); ?>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="row">
                                <button type="button" id="import-essai"
                                        class="btn btn-info align-text-bottom btn-disabled col-5"
                                        data-toggle="tooltip"
                                        title="Input nilai manual">
                                    <i class="fa fa-pencil ml-1 mr-1"></i> Input Nilai
                                </button>
                                <div class="col-1"></div>
                                <button type="button" id="mark-all"
                                        class="btn btn-success align-text-bottom btn-disabled col-6"
                                        data-toggle="tooltip"
                                        title="Tandai semua siswa sudah dikoreksi" disabled>
                                    <i class="fa fa-check ml-1 mr-1"></i> Tandai semua
                                </button>
                            </div>
                        </div>
                    </div>
                    <hr>
                    
                    <div id="ajax-content-area">
                        <div class="float-right row <?= $dnone ?>" id="group-export">
                            <?php if (isset($convert)) : ?>
                                <button type="button" id="rollback" class="btn btn-warning align-text-bottom mr-2">
                                    <i class="fa fa-undo ml-1 mr-1"></i> Nilai Asli
                                </button>
                            <?php else: ?>
                                <button type="button" class="btn btn-danger align-text-bottom mr-2"
                                        data-toggle="modal" data-target="#perbaikanModal">
                                    <i class="fa fa-balance-scale-right ml-1 mr-1"></i> Katrol
                                </button>
                            <?php endif; ?>
                            
                            <button type="button" id="buka-proyektor" class="btn btn-primary align-text-bottom mr-2" title="Tampilkan di Proyektor">
                                <i class="fa fa-tv ml-1 mr-1"></i> Mode Proyektor Live
                            </button>

                            <input style="width: 24px; height: 24px" class="m-1" id="nilai-detail" type="checkbox" checked>
                            <label for="nilai-detail" class="mt-1 mr-1">Detail</label>
                            
                            <button type="button" id="download-excel" class="btn btn-success align-text-bottom mr-2"
                                    data-toggle="tooltip"
                                    title="Download Excel">
                                <i class="fa fa-file-excel ml-1 mr-1"></i> Ekspor Excel
                            </button>
                        </div>
                    <?php
                    $nilaiTertinggi = 0;
                    $nilaiTerrendah = -1;
                    if (count($siswas) > 0) :
                        $cols = [$info->tampil_pg, $info->tampil_kompleks, $info->tampil_jodohkan, $info->tampil_isian, $info->tampil_esai];
                        $cols = array_filter($cols);
                        $rows = count($cols) > 1 ? 1 : 2;

                        $colWidth = '5,40,15,15';
                        for ($s = 0; $s < $info->tampil_pg; $s++) {
                            $colWidth .= ',4';
                        }
                        $colWidth .= ',10,10,10';

                        $totalSoal = $info->tampil_pg + $info->tampil_kompleks + $info->tampil_jodohkan + $info->tampil_isian + $info->tampil_esai;
                        ?>
                        
                        <div id="info" class="mt-5">
                            <div id="info-ujian"></div>
                            <?= form_open('', array('id' => 'koreksi-semua')) ?>
                            <table class="table table-bordered table-striped" id="table-status" data-cols-width="<?= $colWidth ?>">
                                <thead>
                                    <tr>
                                        <th rowspan="2" class="text-center align-middle bg-blue" width="40"
                                            data-fill-color="b8daff" data-a-v="middle" data-a-h="center" data-b-a-s="thin"
                                            data-f-bold="true"
                                            style="border: 1px solid black;border-collapse: collapse; text-align: center;">
                                            No.
                                        </th>
                                        <th rowspan="2" class="text-center align-middle bg-blue" data-fill-color="b8daff"
                                            data-a-v="middle" data-a-h="center" data-b-a-s="thin" data-f-bold="true"
                                            style="border: 1px solid black;border-collapse: collapse; text-align: center;">
                                            Nama
                                        </th>
                                        <th rowspan="2" class="text-center align-middle bg-blue"
                                            style="border: 1px solid black;border-collapse: collapse; text-align: center;"
                                            data-fill-color="b8daff" data-a-v="middle" data-a-h="center" data-b-a-s="thin"
                                            data-f-bold="true">Mulai
                                        </th>
                                        <th rowspan="2" class="text-center align-middle bg-blue"
                                            style="border: 1px solid black;border-collapse: collapse; text-align: center;"
                                            data-fill-color="b8daff" data-a-v="middle" data-a-h="center" data-b-a-s="thin"
                                            data-f-bold="true">Durasi
                                        </th>
                                        <?php if ($info->tampil_pg > 0) : ?>
                                            <th id="no-soal-tile" colspan="<?= $info->tampil_pg ?>"
                                                class="text-center align-middle bg-blue d-none" data-fill-color="b8daff"
                                                data-a-v="middle" data-a-h="center" data-b-a-s="thin" data-f-bold="true"
                                                style="border: 1px solid black;border-collapse: collapse; text-align: center;">
                                                Nomor Soal PG
                                            </th>
                                            <th colspan="2" class="text-center align-middle bg-blue"
                                                data-fill-color="b8daff"
                                                data-a-v="middle" data-a-h="center" data-b-a-s="thin" data-f-bold="true"
                                                style="border: 1px solid black;border-collapse: collapse; text-align: center;">
                                                PG
                                            </th>
                                        <?php endif; ?>

                                        <th colspan="<?= count($cols) ?>" rowspan="<?= $rows ?>"
                                            class="text-center align-middle bg-blue"
                                            data-fill-color="b8daff"
                                            data-a-v="middle" data-a-h="center" data-b-a-s="thin" data-f-bold="true"
                                            style="border: 1px solid black;border-collapse: collapse; text-align: center;">
                                            Skor <?= count($cols) > 1 ? "" : $cols_name[array_key_first($cols)] ?>
                                        </th>
                                        <th colspan="2" class="text-center align-middle bg-blue" data-fill-color="b8daff"
                                            data-a-v="middle" data-a-h="center" data-b-a-s="thin" data-f-bold="true"
                                            style="border: 1px solid black;border-collapse: collapse; text-align: center;">
                                            Nilai
                                        </th>
                                        <th rowspan="2" class="text-center align-middle bg-blue" data-exclude="true"
                                            data-fill-color="b8daff" data-a-v="middle" data-a-h="center" data-b-a-s="thin"
                                            data-f-bold="true"
                                            style="border: 1px solid black;border-collapse: collapse; text-align: center;">
                                            Aksi
                                        </th>
                                    </tr>
                                    <tr>
                                        <?php for ($js = 0; $js < $info->tampil_pg; $js++) : ?>
                                            <th class="no-soal text-center align-middle bg-blue p-1 d-none"
                                                data-fill-color="b8daff"
                                                data-a-v="middle" data-a-h="center" data-b-a-s="thin" data-f-bold="true"
                                                style="border: 1px solid black;border-collapse: collapse; text-align: center;">
                                                <?= $js + 1 ?>
                                            </th>
                                        <?php endfor; ?>
                                        <?php if ($info->tampil_pg > 0) : ?>
                                            <th class="text-center align-middle bg-blue" data-fill-color="b8daff"
                                                data-a-v="middle" data-a-h="center" data-b-a-s="thin" data-f-bold="true"
                                                style="border: 1px solid black;border-collapse: collapse; text-align: center;">
                                                B
                                            </th>
                                            <th class="text-center align-middle bg-blue"
                                                style="border: 1px solid black;border-collapse: collapse; text-align: center;"
                                                data-fill-color="b8daff" data-a-v="middle" data-a-h="center"
                                                data-b-a-s="thin"
                                                data-f-bold="true">S
                                            </th>
                                        <?php endif; ?>
                                        <?php
                                        if ($rows == 1) :
                                            if ($info->tampil_pg > 0) : ?>
                                                <th class="text-center align-middle bg-blue p-1" data-fill-color="b8daff"
                                                    data-a-v="middle" data-a-h="center" data-b-a-s="thin" data-f-bold="true"
                                                    style="border: 1px solid black;border-collapse: collapse; text-align: center;">
                                                    PG
                                                </th>
                                            <?php endif;
                                            if ($info->tampil_kompleks > 0) :?>
                                                <th class="text-center align-middle bg-blue" data-fill-color="b8daff"
                                                    data-a-v="middle" data-a-h="center" data-b-a-s="thin" data-f-bold="true"
                                                    style="border: 1px solid black;border-collapse: collapse; text-align: center;">
                                                    PK
                                                </th>
                                            <?php endif;
                                            if ($info->tampil_jodohkan > 0) :?>
                                                <th class="text-center align-middle bg-blue" data-fill-color="b8daff"
                                                    data-a-v="middle" data-a-h="center" data-b-a-s="thin" data-f-bold="true"
                                                    style="border: 1px solid black;border-collapse: collapse; text-align: center;">
                                                    JO
                                                </th>
                                            <?php endif;
                                            if ($info->tampil_isian > 0) :?>
                                                <th class="text-center align-middle bg-blue" data-fill-color="b8daff"
                                                    data-a-v="middle" data-a-h="center" data-b-a-s="thin" data-f-bold="true"
                                                    style="border: 1px solid black;border-collapse: collapse; text-align: center;">
                                                    IS
                                                </th>
                                            <?php endif;
                                            if ($info->tampil_esai > 0) :?>
                                                <th class="text-center align-middle bg-blue" data-fill-color="b8daff"
                                                    data-a-v="middle" data-a-h="center" data-b-a-s="thin" data-f-bold="true"
                                                    style="border: 1px solid black;border-collapse: collapse; text-align: center;">
                                                    ES
                                                </th>
                                            <?php endif;
                                        endif;
                                        ?>
                                        <th class="text-center align-middle bg-blue" data-fill-color="b8daff"
                                            data-a-v="middle" data-a-h="center" data-b-a-s="thin" data-f-bold="true"
                                            style="border: 1px solid black;border-collapse: collapse; text-align: center;">
                                            Asli
                                        </th>
                                        <th class="text-center align-middle bg-blue" data-fill-color="b8daff"
                                            data-a-v="middle" data-a-h="center" data-b-a-s="thin" data-f-bold="true"
                                            style="border: 1px solid black;border-collapse: collapse; text-align: center;">
                                            Katrol
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="live-tbody-siswa">
                                <?php
                                $no = 1;
                                foreach ($siswas as $siswa) :
                                    $idSiswa = $siswa->id_siswa;
                                    $disable = strpos($siswa->mulai_ujian, '-') !== false;
                                    $disabled = $disable ? 'disabled' : '';
                                    ?>
                                    <tr id="siswa-<?= $idSiswa ?>">
                                        <td class="text-center align-middle" data-a-v="middle" data-a-h="center"
                                            data-b-a-s="thin"
                                            style="border: 1px solid black;border-collapse: collapse; text-align: center;"> <?= $no ?> </td>
                                        <td class="align-middle" data-a-v="middle" data-b-a-s="thin"
                                            style="border: 1px solid black;border-collapse: collapse; padding: 2px 6px"> <?= $siswa->nama ?> </td>
                                        <td class="text-center align-middle" data-a-v="middle" data-a-h="center"
                                            data-b-a-s="thin"
                                            style="border: 1px solid black;border-collapse: collapse; text-align: center;"><?= $siswa->mulai_ujian ?></td>
                                        <td class="text-center align-middle" data-a-v="middle" data-a-h="center"
                                            data-b-a-s="thin"
                                            style="border: 1px solid black;border-collapse: collapse; text-align: center;"><?= $siswa->lama_ujian ?></td>
                                        <?php
                                        $benar_pg = 0;
                                        $salah_pg = 0;
                                        foreach ($siswa->jawaban_pg as $num => $jwb) :
                                            if ($jwb['benar']) {
                                                $bg = 'data-fill-color="00E676"';
                                                $benar_pg++;
                                            } else {
                                                $bg = 'data-fill-color="FF7043"';
                                                $salah_pg++;
                                            }
                                            if (round($siswa->skor_total, 2) > $nilaiTertinggi) {
                                                $nilaiTertinggi = round($siswa->skor_total, 2);
                                            }
                                            if (round($siswa->skor_total, 2) > 0 && $siswa->skor_total < $nilaiTerrendah) {
                                                $nilaiTerrendah = round($siswa->skor_total, 2);
                                            }
                                            ?>
                                            <td class="no-soal-siswa d-none" <?= $bg ?> data-a-v="middle"
                                                data-a-h="center"
                                                data-b-a-s="thin"
                                                style="border: 1px solid black;border-collapse: collapse; text-align: center;"><?= $jwb['jawaban'] ?></td>
                                        <?php endforeach; ?>

                                        <?php if ($info->tampil_pg > 0) : ?>
                                            <td class="text-success" data-a-v="middle" data-a-h="center"
                                                data-b-a-s="thin"
                                                style="border: 1px solid black;border-collapse: collapse; text-align: center;">
                                                <b><?= $disable ? '' : $benar_pg ?></b></td>
                                            <td class="text-danger" data-a-v="middle" data-a-h="center"
                                                data-b-a-s="thin"
                                                style="border: 1px solid black;border-collapse: collapse; text-align: center;">
                                                <b><?= $disable ? '' : $salah_pg ?></b></td>

                                            <td class="text-center text-info align-middle" data-a-v="middle"
                                                data-a-h="center" data-b-a-s="thin"
                                                style="border: 1px solid black;border-collapse: collapse; text-align: center;">
                                                <b> <?= $disable ? '' : $siswa->skor_pg ?> </b></td>
                                        <?php endif;
                                        if ($info->tampil_kompleks > 0) : ?>
                                            <td class="text-center text-success align-middle" data-a-v="middle"
                                                data-a-h="center" data-b-a-s="thin"
                                                style="border: 1px solid black;border-collapse: collapse; text-align: center;">
                                                <b> <?= $disable ? '' : $siswa->skor_kompleks ?> </b></td>
                                        <?php endif;
                                        if ($info->tampil_jodohkan > 0) : ?>
                                            <td class="text-center text-success align-middle" data-a-v="middle"
                                                data-a-h="center" data-b-a-s="thin"
                                                style="border: 1px solid black;border-collapse: collapse; text-align: center;">
                                                <b> <?= $disable ? '' : $siswa->skor_jodohkan ?> </b></td>
                                        <?php endif;
                                        if ($info->tampil_isian > 0) : ?>
                                            <td class="text-center text-success align-middle" data-a-v="middle"
                                                data-a-h="center" data-b-a-s="thin"
                                                style="border: 1px solid black;border-collapse: collapse; text-align: center;">
                                                <b> <?= $disable ? '' : $siswa->skor_isian ?> </b></td>
                                        <?php endif;
                                        if ($info->tampil_esai > 0) : ?>
                                            <td class="text-center text-success align-middle" data-a-v="middle"
                                                data-a-h="center" data-b-a-s="thin"
                                                style="border: 1px solid black;border-collapse: collapse; text-align: center;">
                                                <b> <?= $disable ? '' : $siswa->skor_essai ?> </b></td>
                                        <?php endif; ?>

                                        <td class="text-center align-middle siswa-skor-total" data-a-v="middle" data-a-h="center"
                                            data-b-a-s="thin"
                                            style="border: 1px solid black;border-collapse: collapse; text-align: center;">
                                            <b> <?= $disable ? '' : round($siswa->skor_total, 2) ?> </b></td>
                                        <td class="text-center align-middle" data-a-v="middle" data-a-h="center"
                                            data-b-a-s="thin"
                                            style="border: 1px solid black;border-collapse: collapse; text-align: center;">
                                            <b> <?= $disable ? '' : ($siswa->skor_katrol == '' ? '' : round($siswa->skor_katrol, 2)) ?> </b>
                                        </td>
                                        
                                        <td class="text-center align-middle kolom-aksi" data-exclude="true" data-a-v="middle"
                                            data-a-h="center" data-b-a-s="thin"
                                            style="border: 1px solid black;border-collapse: collapse; text-align: center; vertical-align: middle;">
                                            <div class="btn-group" style="gap: 4px; display: inline-flex; align-items: center;">
                                                <!-- TOMBOL KOREKSI -->
                                                <button type="button" class="btn btn-xs bg-success <?= $disabled ?>"
                                                        onclick="lihatJawaban(<?= $siswa->id_siswa ?>)"
                                                        data-toggle="tooltip" title="Detail Jawaban Siswa" <?= $disabled ?> style="padding: 4px 8px; font-size: 12px; font-weight: 600;">
                                                    Koreksi
                                                </button>

                                                <!-- TOMBOL EDIT SOAL (DI SAMPING KOREKSI) -->
                                                <?php 
                                                    $id_bank = '';
                                                    if (isset($info)) {
                                                        if (isset($info->id_bank_soal)) $id_bank = $info->id_bank_soal;
                                                        elseif (isset($info->id_bank)) $id_bank = $info->id_bank;
                                                        elseif (isset($info->id_soal)) $id_bank = $info->id_soal;
                                                    }
                                                ?>
                                                <a href="<?= base_url('cbtbanksoal/buatsoal/' . $id_bank) ?>" class="btn btn-xs btn-primary" style="padding: 4px 8px; font-size: 12px; font-weight: 600;">
                                                    <i class="fa fa-pencil"></i> Edit
                                                </a>
                                                
                                                <?php if (isset($siswa->dikoreksi) && $siswa->dikoreksi === '1') : ?>
                                                    <i title="Sudah dikoreksi" class="fa fa-check-circle text-green ml-1"></i>
                                                <?php else: ?>
                                                    <i title="Belum dikoreksi" class="fa fa-warning text-warning ml-1"></i>
                                                <?php endif; ?>
                                            </div>
                                            <input type="hidden" name="ids[<?= $idSiswa ?>]"
                                                   value="<?= $disabled ? '2' : (isset($siswa->dikoreksi) && $siswa->dikoreksi ? '1' : '0') ?>">
                                        </td>
                                    </tr>
                                        <?php $no++; endforeach; ?>
                                    </tbody>
                                </table>
                                <?= form_close() ?>
                            </div>
                        <?php endif; ?>
                    </div> </div>
                <div class="overlay d-none" id="loading" style="display: none !important;">
                    <div class="spinner-grow text-primary"></div>
                </div>
            </div>
        </div>
    </section>
</div>

<?= form_open('update', array('id' => 'perbaikan-nilai')) ?>
<div class="modal fade" id="perbaikanModal" tabindex="-1" role="dialog" aria-labelledby="perbaikanModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="perbaikanModalLabel">Perbaikan Nilai</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label class="col-md-4 col-form-label">Nilai Tertinggi</label>
                    <div class="col-md-8">
                        <input type="number" class="form-control" id="input_ya" name="ya" value="<?= $YA ?>" min="1" max="100" required>
                        <small>Nilai puluhan maksimal 100, misal 80 sampai 100</small>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-4 col-form-label">Nilai Terrendah</label>
                    <div class="col-md-8">
                        <input type="number" class="form-control" id="input_yb" name="yb" value="<?= $YB ?>" min="0" max="99" required>
                        <small>Nilai puluhan di bawah nilai tertinggi, misal 60</small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary" id="convert">Katrol <i class="fa fa-arrow-right"></i></button>
            </div>
        </div>
    </div>
</div>
<?= form_close() ?>

<script type="text/javascript" src="<?= base_url() ?>/assets/app/js/tableToExcel.js"></script>

<script>
    var colWidthMin = '5,40,15,15,10,10,10';
    var colWidth = '<?= $colWidth ?>';
    var idJadwal = '<?=$jadwal_selected?>';
    var idKelas = '<?=$kelas_selected?>';
    var isSelected = <?= count($siswas) > 0 ? 1 : 0?>;
    var namaMapel = '<?=isset($info) ? $info->kode : ''?>';
    var jenisUjian = '<?=isset($info) ? $info->kode_jenis : ''?>';
    var nilai_max = <?=$XA?>;
    var nilai_min = <?=$XB?>;
    
    var liveInterval;
    var lastHtmlSnapshot = ""; 

    function startLiveMonitoring() {
        if (!idKelas || !idJadwal) return;
        $('#live-indicator').show();

        function syncScores() {
            let fetchUrl = base_url + 'cbtnilai?kelas=' + idKelas + '&jadwal=' + idJadwal;
            $.ajax({
                type: "GET",
                url: fetchUrl,
                dataType: "html",
                cache: false,
                success: function (htmlData) {
                    var freshRows = $(htmlData).find('#live-tbody-siswa tr');
                    if (freshRows.length > 0) {
                        let currentSnapshot = freshRows.text();
                        if (lastHtmlSnapshot === currentSnapshot) return;

                        var isDetailChecked = $("#nilai-detail").is(':checked');

                        freshRows.each(function() {
                            let rId = $(this).attr('id');
                            let targetRow = $('#' + rId);

                            if (targetRow.length > 0) {
                                let oldTotal = targetRow.find('.siswa-skor-total').text().trim();
                                let newTotal = $(this).find('.siswa-skor-total').text().trim();

                                if (oldTotal !== newTotal) {
                                    targetRow.replaceWith($(this).addClass('score-updated'));
                                    setTimeout(function() { $('#' + rId).removeClass('score-updated'); }, 2000);
                                } else {
                                    targetRow.replaceWith($(this));
                                }
                            }
                        });

                        lastHtmlSnapshot = currentSnapshot;
                        
                        $('#no-soal-tile, .no-soal, .no-soal-siswa').attr('data-exclude', isDetailChecked ? 'false' : 'true');
                        if(isDetailChecked) { 
                            $('#no-soal-tile, .no-soal, .no-soal-siswa').show(); 
                        } else { 
                            $('#no-soal-tile, .no-soal, .no-soal-siswa').hide(); 
                        }
                    }
                }
            });
        }

        clearInterval(liveInterval);
        liveInterval = setInterval(syncScores, 4000);
    }

    function lihatJawaban(idSiswa) {
        window.location.href = base_url + 'cbtnilai/detail?siswa=' + idSiswa + '&jadwal=' + idJadwal;
    }

    function getKelasJadwal(idKelasSelected) {
        $.ajax({
            type: "GET",
            url: base_url + "cbtstatus/getjadwalujianbykelas?id_kelas=" + idKelasSelected,
            cache: false,
            success: function (response) {
                var selJadwal = $('#jadwal');
                selJadwal.html('');
                selJadwal.append('<option value="">Pilih Jadwal</option>');
                $.each(response, function (k, v) {
                    if (v != null) {
                        selJadwal.append('<option value="' + k + '">' + v + '</option>');
                    }
                });
                const enb = isSelected && $("#jadwal").val() === idJadwal;
                $('#import-essai, #mark-all').attr('disabled', !enb).toggleClass('btn-disabled', !enb);
            }
        });
    }

    function loadSiswaKelasAjax(kelas, jadwal, optionalParams = "") {
        if (!kelas || !jadwal) return;
        
        clearInterval(liveInterval); 
        $('#loading').removeClass('d-none'); 

        idKelas = kelas;
        idJadwal = jadwal;
        
        var requestUrl = base_url + 'cbtnilai?kelas=' + kelas + '&jadwal=' + jadwal + optionalParams;

        $.ajax({
            type: "GET",
            url: requestUrl,
            dataType: "html",
            cache: false,
            success: function(dataHtml) {
                var freshContent = $(dataHtml).find('#ajax-content-area').html();
                
                if(freshContent) {
                    $('#ajax-content-area').html(freshContent);
                    isSelected = 1;
                    
                    $("#nilai-detail").trigger('change');
                    $('#import-essai, #mark-all').attr('disabled', false).removeClass('btn-disabled');
                    
                    startLiveMonitoring();
                } else {
                    $('#ajax-content-area').html('<div class="text-center p-4 text-muted">Data siswa tidak ditemukan untuk kombinasi kelas ini.</div>');
                    $('#import-essai, #mark-all').attr('disabled', true).addClass('btn-disabled');
                    $('#live-indicator').hide();
                }
                
                $('#loading').addClass('d-none'); 
            },
            error: function() {
                $('#loading').addClass('d-none');
                Swal.fire({ title: "Error", text: "Gagal memuat data dari server.", icon: "error" });
            }
        });
    }

    $(document).ready(function () {
        ajaxcsrf();
        var opsiJadwal = $("#jadwal");
        var opsiKelas = $("#kelas");

        var selected = isSelected === 0 ? "selected='selected'" : "";
        opsiJadwal.prepend("<option value='' " + selected + " disabled>Pilih Jadwal</option>");
        opsiKelas.prepend("<option value='' " + selected + " disabled>Pilih Kelas</option>");
        
        $('#import-essai, #mark-all').attr('disabled', !isSelected).toggleClass('btn-disabled', !isSelected);

        if (isSelected) {
            startLiveMonitoring();
        }

        $(document).on('click', '#rollback', function () { 
            loadSiswaKelasAjax(opsiKelas.val(), opsiJadwal.val());
        });

        opsiKelas.change(function () { 
            getKelasJadwal($(this).val()); 
        });

        opsiJadwal.change(function () { 
            loadSiswaKelasAjax(opsiKelas.val(), $(this).val()); 
        });

        $(document).on('click', '#download-excel', function () {
            var table = document.querySelector("#table-status");
            if (table != null) {
                TableToExcel.convert(table, {
                    name: `Nilai ${jenisUjian} ${$("#kelas option:selected").text()} ${namaMapel}.xlsx`,
                    sheet: { name: "Sheet 1" }
                });
            } else {
                Swal.fire({ title: "ERROR", text: "Isi JADWAL dan KELAS terlebih dulu", icon: "error" })
            }
        });

        $(document).on("click", "#nilai-detail", function () {
            var isChecked = this.checked;
            $('#no-soal-tile, .no-soal, .no-soal-siswa').attr('data-exclude', isChecked ? 'false' : 'true');
            
            var width = $('#table-status').attr('data-cols-width');
            $('#table-status').attr('data-cols-width', width == colWidth ? colWidthMin : colWidth);
            
            if(isChecked) { 
                $('#no-soal-tile, .no-soal, .no-soal-siswa').show(); 
            } else { 
                $('#no-soal-tile, .no-soal, .no-soal-siswa').hide(); 
            }
        });

        $('#perbaikan-nilai').on('submit', function (e) {
            e.preventDefault();
            var ya = parseInt($('#input_ya').val());
            var yb = parseInt($('#input_yb').val());

            if (yb >= ya) {
                Swal.fire({
                    title: "Input Tidak Valid",
                    text: "Nilai Terrendah harus lebih kecil daripada Nilai Tertinggi!",
                    icon: "warning"
                });
                return false;
            }

            $('#perbaikanModal').modal('hide');
            var katrolParams = '&xa=' + nilai_max + '&xb=' + nilai_min + '&ya=' + ya + '&yb=' + yb;
            loadSiswaKelasAjax(opsiKelas.val(), opsiJadwal.val(), katrolParams);
        });

        $(document).on("click", "#import-essai", function () {
            if (opsiKelas.val() == null || opsiJadwal.val() == null) return;
            window.location.href = base_url + 'cbtnilai/inputessai?kelas=' + opsiKelas.val() + '&jadwal=' + opsiJadwal.val();
        });

        $(document).on('click', '#mark-all', function () {
            let dataPost = new FormData($('#koreksi-semua')[0]);
            dataPost.append('id_jadwal', idJadwal);
            $('#loading').removeClass('d-none');
            swal.fire({
                text: "Silahkan tunggu....",
                button: false,
                allowOutsideClick: false,
                onOpen: () => { swal.showLoading(); }
            });
            $.ajax({
                url: base_url + "cbtnilai/tandaisemua",
                type: "POST",
                data: dataPost,
                processData: false,
                contentType: false,
                success: function (data) {
                    if (data.success > 0) {
                        swal.fire({ title: "Berhasil", text: "Koreksi nilai berhasil disimpan", icon: "success" })
                        .then(() => { loadSiswaKelasAjax(opsiKelas.val(), opsiJadwal.val()); });
                    } else {
                        $('#loading').addClass('d-none');
                        swal.fire({ title: "Gagal", text: 'Tidak ada nilai yang disimpan', icon: "error" });
                    }
                }, error: function (xhr) {
                    $('#loading').addClass('d-none');
                    const err = JSON.parse(xhr.responseText);
                    swal.fire({ title: "Error", text: err.Message, icon: "error" });
                }
            });
        });

        $(document).on('click', '#buka-proyektor', function () {
            if (!opsiKelas.val() || !opsiJadwal.val()) {
                Swal.fire({ title: "Perhatian", text: "Pilih KELAS dan JADWAL terlebih dahulu!", icon: "warning" });
                return;
            }

            var printWindow = window.open('', '_blank');
            var txtMapel = "LIVE MONITORING: " + ($("#jadwal option:selected").text() || "UJIAN CBT");
            var txtKelas = "KELAS: " + $("#kelas option:selected").text();

            function dapatkanTabelHtml() {
                var tableClone = $("#table-status").clone();
                
                tableClone.find('[data-exclude="true"]').remove();
                tableClone.find('.no-soal, .no-soal-siswa, #no-soal-tile').remove();
                
                var headerSempurna = `
                    <thead>
                        <tr>
                            <th rowspan="2" width="50">NO</th>
                            <th rowspan="2">NAMA LENGKAP SISWA</th>
                            <th rowspan="2" width="100">MULAI</th>
                            <th rowspan="2" width="100">DURASI</th>
                            <th colspan="2">PG</th>
                            <th rowspan="2" width="100">SKOR PG</th>
                            <th colspan="2">NILAI</th>
                        </tr>
                        <tr>
                            <th width="50">B</th>
                            <th width="50">S</th>
                            <th width="95">ASLI</th>
                            <th width="95">KATROL</th>
                        </tr>
                    </thead>
                `;
                
                tableClone.find('thead').remove();
                tableClone.prepend(headerSperfect = headerSempurna);
                
                tableClone.find('tbody tr').each(function() {
                    $(this).find('td:last-child').remove();
                });

                tableClone.find('th').css({
                    'background-color': '#2f384e', 
                    'color': '#00e676', 
                    'border': '1px solid #3f4d6b',
                    'padding': '12px 8px',
                    'text-transform': 'uppercase',
                    'font-size': '1rem',
                    'text-align': 'center',
                    'vertical-align': 'middle'
                });
                
                tableClone.find('td').css({
                    'border': '1px solid #2f384e', 
                    'padding': '14px 10px',
                    'vertical-align': 'middle',
                    'font-weight': '600',
                    'text-align': 'center',
                    'color': '#ffffff'
                });
                
                tableClone.find('tbody tr').each(function() {
                    $(this).find('td:eq(1)').css({'text-align': 'left', 'color': '#ffffff'});
                    $(this).find('td:eq(4)').css({'color': '#81c784'});
                    $(this).find('td:eq(5)').css({'color': '#e57373'});
                    $(this).find('td:eq(6)').css({'font-weight': 'bold', 'color': '#ffffff'});
                    $(this).find('td:eq(7)').css({
                        'color': '#00e676',
                        'font-size': '1.35rem',
                        'font-weight': 'bold'
                    });

                    var cellKatrol = $(this).find('td:eq(8)');
                    if (cellKatrol.text().trim() !== "") {
                        cellKatrol.css({
                            'color': '#ffd700', 
                            'font-size': '1.35rem',
                            'font-weight': 'bold'
                        });
                    }
                });

                return tableClone[0].outerHTML;
            }

            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>LIVE SCOREBOARD PROYECTOR</title>
                    <link rel="stylesheet" href="${base_url}/assets/dist/css/adminlte.min.css">
                    <style>
                        body { background-color: #1a1e29; color: #fff; font-family: 'Segoe UI', Tahoma, Geneva, sans-serif; overflow: hidden; padding: 0; margin: 0; }
                        .header-live { background: linear-gradient(145deg, #1e3c72, #2a5298); border-bottom: 4px solid #00e676; padding: 15px; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.3); position: relative; }
                        .blink-dot { height: 12px; width: 12px; background-color: #ff1744; border-radius: 50%; display: inline-block; animation: blinker 1s linear infinite; vertical-align: middle; margin-right: 8px; }
                        @keyframes blinker { 50% { opacity: 0; } }
                        
                        .container-scroll { 
                            max-height: 75vh; 
                            overflow-y: hidden; 
                            margin: 55px 20px 20px 20px; 
                            background-color: #242b3d; 
                            border-radius: 12px; 
                            box-shadow: 0 8px 16px rgba(0,0,0,0.2); 
                            padding: 20px; 
                        }
                        table { width: 100%; color: #fff; font-size: 1.3rem; border-collapse: collapse; }
                    </style>
                </head>
                <body>
                    <div class="header-live">
                        <div style="position: absolute; left: 20px; top: 12px; text-align: left;">
                            <h5 style="margin:0; color:#ffd700; font-weight: bold;">${txtMapel}</h5>
                            <small style="color:#a0aec0; font-weight: 500;">${txtKelas}</small>
                        </div>
                        <h3 style="margin:0; font-weight:bold; letter-spacing: 1px;"><span class="blink-dot"></span> LAYAR MONITORING NILAI UTAMA</h3>
                        <div style="position: absolute; right: 20px; top: 22px; color: #00e676; font-weight: bold; font-size: 1.3rem;" id="live-clock">00:00:00</div>
                    </div>
                    
                    <div class="container-scroll" id="scroller">
                        <div id="table-wrapper">
                            ${dapatkanTabelHtml()}
                        </div>
                    </div>

                    <script src="${base_url}/assets/plugins/jquery/jquery.min.js"><\/script>
                    <script>
                        setInterval(function() {
                            var d = new Date();
                            document.getElementById('live-clock').innerText = d.toLocaleTimeString('id-ID');
                        }, 1000);

                        function aktifkanLayarPenuh() {
                            var docElm = document.documentElement;
                            if (docElm.requestFullscreen) {
                                docElm.requestFullscreen();
                            } else if (docElm.mozRequestFullScreen) { 
                                docElm.mozRequestFullScreen();
                            } else if (docElm.webkitRequestFullscreen) { 
                                docElm.webkitRequestFullscreen();
                            } else if (docElm.msRequestFullscreen) { 
                                docElm.msRequestFullscreen();
                            }
                        }

                        window.onload = function() {
                            setTimeout(aktifkanLayarPenuh, 500);
                        };
                        
                        document.body.addEventListener('click', function () {
                            aktifkanLayarPenuh();
                        });

                        function startAutoScroll() {
                            var dev = document.getElementById("scroller");
                            setInterval(function() {
                                dev.scrollTop = dev.scrollTop + 1;
                                if ((dev.scrollTop + dev.clientHeight) >= dev.scrollHeight) {
                                    setTimeout(function() { dev.scrollTop = 0; }, 2000);
                                }
                            }, 40);
                        }
                        setTimeout(startAutoScroll, 3000);
                    <\/script>
                </body>
                </html>
            `);
            printWindow.document.close();

            if (window.liveProyektorInterval) clearInterval(window.liveProyektorInterval);
            
            window.liveProyektorInterval = setInterval(function() {
                if (printWindow.closed) {
                    clearInterval(window.liveProyektorInterval);
                    return;
                }
                var freshTableHtml = dapatkanTabelHtml();
                var targetWrapper = $(printWindow.document).find('#table-wrapper');
                if (targetWrapper.html() !== freshTableHtml) {
                    targetWrapper.html(freshTableHtml);
                }
            }, 4000);
        });

        opsiKelas.select2({theme: 'bootstrap4'});
        opsiJadwal.select2({theme: 'bootstrap4'});
        $('#loading').addClass('d-none');
    });
</script>