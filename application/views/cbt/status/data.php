<?php
/**
 * Created by IntelliJ IDEA.
 * User: multazam
 * Date: 07/07/20
 * Time: 17:20
 */
?>

<style>
    .cbt-status,
    .modal-premium,
    .select2-container--bootstrap4 {
        --line: #e3e8ee;
        --muted: #6b7a8c;
        --text: #12263a;
        --text-soft: #3b4c5e;
        --surface: #ffffff;
        --field-line: #cdd6e0;
        --row-hover: #f6f8fb;
        --muted-strong: #8a98a8;
        --icon-muted: #b6c2d0;
        --placeholder: #7b8a9a;
        --ok-fg: #12805c;
        --gold-ink: #7a5614;
        --gold-line: #ecd9ac;
        --info-soft: #e8f0f8;
        --btn-solid: #12263a;
        --btn-solid-hover: #1d3a52;
        --hl: #1d3a52;
        --chip: #12263a;
        --overlay: rgba(255, 255, 255, .7);
        --ink: #12263a;
        --ink-2: #1d3a52;
        --paper: #f3f5f8;
        --line: #e3e8ee;
        --muted: #6b7a8c;
        --gold: #b8862b;
        --gold-soft: #fbf3e2;
        --ok: #12805c;
        --ok-soft: #e5f5ee;
        --bad: #c2374f;
        --bad-soft: #fdecef;
        background: var(--paper) !important;
        font-family: -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }
    .cbt-status { color: var(--text); }

    /* ---------- Judul ---------- */
    .cbt-status .page-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem; flex-wrap: wrap; }
    .cbt-status .page-head h1 { font-size: 1.65rem; font-weight: 700; letter-spacing: -.015em; color: var(--text); margin: 0; }
    .cbt-status .page-sub { margin: .3rem 0 0; color: var(--muted); font-size: .92rem; }

    .cbt-status .panel-premium {
        background: var(--surface); border: 1px solid var(--line); border-radius: 16px;
        box-shadow: 0 1px 2px rgba(18, 38, 58, .04), 0 18px 36px -20px rgba(18, 38, 58, .22);
    }
    .cbt-status .panel-premium > .card-body { padding: 1.5rem; }

    /* ---------- Token (elemen utama halaman) ---------- */
    .cbt-status .token-panel {
        background: linear-gradient(135deg, var(--ink) 0%, var(--ink-2) 100%);
        border-radius: 14px; padding: .9rem 1.25rem; margin-bottom: 1.5rem;
        box-shadow: inset 0 0 0 1px rgba(255, 255, 255, .06);
    }
    .cbt-status .token-panel .input-group { flex-wrap: nowrap; align-items: center; }
    .cbt-status .token-panel .input-group-text {
        background: transparent; border: 0; color: #9fb3c8; font-weight: 600; font-size: .9rem; padding-left: 0;
    }
    .cbt-status .token-panel #token-input {
        background: transparent !important; border: 0 !important; box-shadow: none !important;
        color: #fff; font-size: 2.1rem; font-weight: 700; letter-spacing: .4em; text-indent: .4em;
        height: auto; padding: .1rem .5rem; font-variant-numeric: tabular-nums;
    }
    .cbt-status .token-panel #interval {
        color: #e8c777; font-weight: 600; font-size: .95rem !important;
        font-variant-numeric: tabular-nums; padding-right: 0;
    }
    .cbt-status .token-panel .input-group-append .btn {
        background: rgba(255, 255, 255, .1); border: 1px solid rgba(255, 255, 255, .18); color: #fff; border-radius: 10px;
    }
    .cbt-status .token-panel .input-group-append .btn:hover { background: rgba(255, 255, 255, .2); }

    /* ---------- Filter ---------- */
    .cbt-status .filter-grid {
        display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 1rem;
        padding-bottom: 1.4rem; border-bottom: 1px solid var(--line); margin-bottom: 1.4rem;
    }
    .cbt-status .span-2 { grid-column: span 2; }
    .cbt-status .filter-field label {
        display: block; margin: 0 0 .35rem; font-size: .82rem; font-weight: 600; color: var(--muted);
    }
    .cbt-status .field-row { display: flex; gap: .6rem; align-items: stretch; }
    .cbt-status .field-main { flex: 1 1 auto; min-width: 0; }
    .cbt-status .field-aside { flex: 0 0 9.5rem; }
    .cbt-status .form-control { border-radius: 10px; border-color: var(--field-line); background: var(--surface); color: var(--text); height: 42px; }
    .cbt-status .field-aside .form-control { background: var(--paper); font-weight: 600; }

    .cbt-status .select2-container--bootstrap4 .select2-selection--single {
        height: 42px !important; border: 1px solid var(--field-line); border-radius: 10px; background: var(--surface);
    }
    .cbt-status .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
        line-height: 40px; padding-left: .85rem; color: var(--text); font-weight: 500;
    }
    .cbt-status .select2-container--bootstrap4 .select2-selection--single .select2-selection__arrow {
        position: absolute !important; top: 0 !important; bottom: 0 !important; right: .35rem !important;
        height: 100% !important; width: 1.6rem !important;
        display: flex; align-items: center; justify-content: center;
    }
    .cbt-status .select2-container--bootstrap4 .select2-selection--single .select2-selection__arrow b {
        position: static !important; margin: 0 !important; top: auto !important; left: auto !important;
        border-style: solid; border-width: 5px 4px 0 4px; border-color: var(--muted) transparent transparent transparent;
    }
    .cbt-status .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered { padding-right: 2rem; }
    .cbt-status .select2-container--bootstrap4.select2-container--open .select2-selection--single .select2-selection__arrow b {
        border-width: 0 4px 5px 4px; border-color: transparent transparent var(--muted) transparent;
    }
    .cbt-status .select2-container--bootstrap4.select2-container--focus .select2-selection {
        border-color: var(--ink-2); box-shadow: 0 0 0 3px rgba(29, 58, 82, .12);
    }
    .select2-container--bootstrap4 .select2-results__option--highlighted[aria-selected] { background: var(--hl); color: #fff; }

    /* ---------- Tombol ---------- */
    .cbt-status .nbtn, .modal-premium .nbtn {
        display: inline-flex; align-items: center; justify-content: center; gap: .5rem;
        padding: .6rem 1.05rem; border-radius: 10px; border: 1px solid transparent;
        font-size: .88rem; font-weight: 600; line-height: 1.2; white-space: nowrap; cursor: pointer;
        transition: background .15s, border-color .15s, color .15s;
    }
    .cbt-status .nbtn-solid, .modal-premium .nbtn-solid { background: var(--btn-solid); color: #fff; }
    .cbt-status .nbtn-solid:hover, .modal-premium .nbtn-solid:hover { background: var(--btn-solid-hover); color: #fff; }
    .cbt-status .nbtn-outline, .modal-premium .nbtn-outline { background: var(--surface); color: var(--text); border-color: var(--field-line); }
    .cbt-status .nbtn-outline:hover, .modal-premium .nbtn-outline:hover { border-color: var(--text); }
    .cbt-status .nbtn-ok { background: var(--ok); color: #fff; }
    .cbt-status .nbtn-ok:hover { background: #0e6b4d; color: #fff; }
    .cbt-status :focus-visible, .modal-premium :focus-visible { outline: 2px solid var(--gold); outline-offset: 2px; }

    /* ---------- Info ujian & panduan ---------- */
    .cbt-status .info-grid { display: grid; grid-template-columns: 5fr 7fr; gap: 1rem; align-items: start; margin-bottom: 1rem; }
    .cbt-status .info-card { border: 1px solid var(--line); border-radius: 12px; padding: 1rem 1.2rem; background: var(--surface); }
    .cbt-status .info-card h6 { font-size: .95rem; font-weight: 700; margin: 0 0 .7rem; }
    .cbt-status dl.kv { display: grid; grid-template-columns: 7rem 1fr; gap: .45rem .75rem; margin: 0; font-size: .9rem; }
    .cbt-status dl.kv dt { color: var(--muted); font-weight: 500; }
    .cbt-status dl.kv dd { margin: 0; font-weight: 700; }
    .cbt-status details.guide summary { cursor: pointer; font-weight: 700; font-size: .95rem; list-style: none; display: flex; align-items: center; justify-content: space-between; }
    .cbt-status details.guide summary::-webkit-details-marker { display: none; }
    .cbt-status details.guide summary::after { content: "+"; font-size: 1.2rem; color: var(--muted); }
    .cbt-status details.guide[open] summary::after { content: "–"; }
    .cbt-status details.guide ul { margin: .8rem 0 0; padding-left: 1.1rem; font-size: .86rem; color: var(--text-soft); line-height: 1.5; }
    .cbt-status details.guide li { margin-bottom: .4rem; }

    /* ---------- Ringkasan status ---------- */
    .cbt-status .stat-strip {
        display: grid; grid-template-columns: repeat(4, 1fr); margin: 0 0 1rem; background: var(--surface);
        border: 1px solid var(--line); border-radius: 12px;
    }
    .cbt-status .stat { padding: .9rem 1.15rem; border-right: 1px solid var(--line); }
    .cbt-status .stat:last-child { border-right: 0; }
    .cbt-status .stat b { display: block; font-size: 1.6rem; font-weight: 700; line-height: 1.15; font-variant-numeric: tabular-nums; }
    .cbt-status .stat span { font-size: .8rem; color: var(--muted); }
    .cbt-status .stat-ok b { color: var(--ok-fg); }
    .cbt-status .stat-gold b { color: var(--gold); }
    .cbt-status .stat-muted b { color: var(--muted-strong); }

    /* ---------- Toolbar ---------- */
    .cbt-status .toolbar { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: .75rem; margin-bottom: 1rem; }
    .cbt-status .toolbar-right { display: flex; align-items: center; gap: .6rem; flex-wrap: wrap; }
    .cbt-status .toolbar-right label { margin: 0; }
    .cbt-status #cari-status-siswa::placeholder { color: var(--placeholder); opacity: 1; }
    .cbt-status #cari-status-siswa { height: 42px; min-width: 15rem; border-radius: 10px; padding: 0 .9rem; font-size: .9rem; }

    /* ---------- Tabel ---------- */
    .cbt-status .table-shell { overflow-x: auto; border: 1px solid var(--line); border-radius: 12px; background: var(--surface); }
    .cbt-status #table-status {
        width: 100%; margin: 0; border-collapse: separate; border-spacing: 0;
        font-variant-numeric: tabular-nums; color: var(--text);
    }
    .cbt-status #table-status thead th {
        background: linear-gradient(180deg, var(--ink-2), var(--ink)) !important;
        color: #eef3f8 !important; border: 0 !important; border-right: 1px solid rgba(255, 255, 255, .09) !important;
        padding: .7rem .5rem !important; font-size: .82rem; font-weight: 600; text-align: center;
        vertical-align: middle !important; line-height: 1.35;
    }
    .cbt-status #table-status thead tr:nth-child(2) th { background: var(--ink-2) !important; color: #c9d6e3 !important; }
    .cbt-status #table-status thead tr:first-child th:nth-child(3) { text-align: left; padding-left: 1rem !important; }
    .cbt-status #table-status thead th:last-child { border-right: 0 !important; }
    .cbt-status #table-status thead input.check { accent-color: var(--gold); margin-top: .25rem; }

    .cbt-status #table-status tbody td {
        border: 0 !important; border-bottom: 1px solid var(--line) !important;
        padding: .65rem .5rem !important; vertical-align: middle !important; font-size: .9rem; background: transparent; color: inherit;
    }
    .cbt-status #table-status tbody tr:last-child td { border-bottom: 0 !important; }
    .cbt-status #table-status tbody tr:hover { background: var(--row-hover); }
    .cbt-status #table-status tbody td:nth-child(3) { font-weight: 600; padding-left: 1rem !important; }
    .cbt-status #table-status tbody td[colspan] { padding: 2.5rem 1rem !important; color: var(--muted); text-align: center; }

    .cbt-status #table-status tbody tr td:first-child { box-shadow: inset 3px 0 0 transparent; }
    .cbt-status #table-status tbody tr.row-jalan  td:first-child { box-shadow: inset 3px 0 0 var(--ok); }
    .cbt-status #table-status tbody tr.row-jeda   td:first-child { box-shadow: inset 3px 0 0 var(--gold); }
    .cbt-status #table-status tbody tr.row-selesai td:first-child { box-shadow: inset 3px 0 0 var(--text); }
    .cbt-status #table-status tbody tr.row-belum td { color: var(--text-soft); }
    .cbt-status #table-status tbody tr.row-belum td:nth-child(3) { color: var(--text); font-weight: 600; }

    .cbt-status #table-status .badge { border-radius: 999px; padding: .28em .65em; font-weight: 600; }
    .cbt-status #table-status .badge-warning { background: var(--gold-soft); color: var(--gold-ink); box-shadow: inset 0 0 0 1px var(--gold-line); }
    .cbt-status #table-status .badge-info { background: var(--info-soft); color: var(--text); }
    .cbt-status #table-status input.check { width: 18px; height: 18px; accent-color: var(--hl); cursor: pointer; }
    .cbt-status #table-status input.check:disabled { cursor: not-allowed; opacity: .35; }

    .cbt-status .btn-icon, .cbt-status .btn-mini {
        display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; border: 1px solid transparent;
        cursor: pointer; transition: background .15s, border-color .15s; line-height: 1.2;
    }
    .cbt-status .btn-icon { width: 34px; height: 34px; background: var(--surface); color: var(--text); border-color: var(--line); }
    .cbt-status .btn-icon:hover:not(:disabled) { border-color: var(--text); }
    .cbt-status .btn-mini { padding: .35rem .75rem; font-size: .8rem; font-weight: 600; }
    .cbt-status .btn-mini-gold { background: var(--gold-soft); color: var(--gold-ink); border-color: var(--gold-line); }
    .cbt-status .btn-mini-gold:hover:not(:disabled) { border-color: var(--gold); }
    .cbt-status .btn-mini-ok { background: var(--ok); color: #fff; border-radius: 0 8px 8px 0; padding: .35rem .65rem; }
    .cbt-status .btn-icon:disabled, .cbt-status .btn-mini:disabled { opacity: .35; cursor: not-allowed; }
    .cbt-status .input-tambah { border-radius: 8px 0 0 8px; min-width: 3.5rem; height: auto; }

    /* ---------- Keadaan kosong ---------- */
    .cbt-status .empty-state { text-align: center; padding: 3rem 1rem 2.5rem; }
    .cbt-status .empty-state i { font-size: 2.4rem; color: var(--icon-muted); margin-bottom: 1rem; }
    .cbt-status .empty-state h3 { font-size: 1.15rem; font-weight: 700; margin: 0 0 .35rem; }
    .cbt-status .empty-state p { color: var(--muted); margin: 0 auto; max-width: 30rem; }

    /* ---------- Modal ---------- */
    .modal-premium .modal-content { background: var(--surface); color: var(--text); border: 0; border-radius: 16px; box-shadow: 0 24px 60px -20px rgba(18, 38, 58, .45); }
    .modal-premium .modal-header, .modal-premium .modal-footer { border-color: var(--line); padding: 1.1rem 1.5rem; }
    .modal-premium .modal-title { font-weight: 700; color: var(--text); }
    .modal-premium .modal-body { padding: 1.3rem 1.5rem; }
    .modal-premium .kode-error td { border: 0; border-bottom: 1px solid var(--line); color: var(--text); padding: .7rem .5rem; vertical-align: middle; }
    .modal-premium .kode-error tr:last-child td { border-bottom: 0; }
    .modal-premium .code-chip {
        display: inline-block; min-width: 3rem; text-align: center; padding: .2rem .6rem; border-radius: 8px;
        background: var(--chip); color: #fff; font-weight: 700; font-variant-numeric: tabular-nums;
    }

    /* ---------- Responsif ---------- */
    @media (max-width: 991px) {
        .cbt-status .filter-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .cbt-status .info-grid { grid-template-columns: 1fr; }
        .cbt-status .stat-strip { grid-template-columns: repeat(2, 1fr); }
        .cbt-status .stat { border-bottom: 1px solid var(--line); }
    }
    @media (max-width: 575px) {
        .cbt-status .filter-grid { grid-template-columns: 1fr; }
        .cbt-status .span-2 { grid-column: auto; }
        .cbt-status .field-aside { flex-basis: 7.5rem; }
        .cbt-status .token-panel #token-input { font-size: 1.5rem; letter-spacing: .25em; }
        .cbt-status .panel-premium > .card-body { padding: 1rem; }
        .cbt-status #cari-status-siswa { min-width: 0; width: 100%; }
    }

    /* ---------- Kontras tambahan (siang & malam) ---------- */
    .cbt-status .form-control { background: var(--surface); color: var(--text); }
    .cbt-status .form-control option { background: var(--surface); color: var(--text); }
    .cbt-status .form-control:focus { background: var(--surface); color: var(--text); }
    .cbt-status .token-panel #token-input { color: #fff; background: transparent !important; }
    .cbt-status .overlay { background: var(--overlay); }
    .cbt-status .overlay .spinner-grow { color: var(--hl); }
    .cbt-status .text-muted { color: var(--muted) !important; }
    .modal-premium .table { color: var(--text); background: transparent; }
    .modal-premium label { color: var(--text); }
    .modal-premium .close { color: var(--text); text-shadow: none; opacity: .75; }
    .modal-premium .close:hover { color: var(--text); opacity: 1; }
    .modal-premium .form-control { background: var(--surface); color: var(--text); border-color: var(--field-line); }
    .select2-container--bootstrap4 .select2-dropdown { background: var(--surface); border-color: var(--field-line); color: var(--text); }
    .select2-container--bootstrap4 .select2-results__option { color: var(--text); background: var(--surface); }
    .select2-container--bootstrap4 .select2-results__option[aria-selected=true] { background: var(--row-hover); color: var(--text); }
    .select2-container--bootstrap4 .select2-results__option--highlighted[aria-selected] { background: var(--hl); color: #fff; }
    .select2-container--bootstrap4 .select2-search--dropdown .select2-search__field { background: var(--surface); color: var(--text); border-color: var(--field-line); }
    .select2-container--bootstrap4 .select2-selection--single .select2-selection__placeholder { color: var(--placeholder); }

    /* ---------- Mode malam (class dark-mode milik AdminLTE) ---------- */
    .dark-mode .cbt-status, .dark-mode .modal-premium, .dark-mode .select2-container--bootstrap4,
    [data-theme="dark"] .cbt-status, [data-theme="dark"] .modal-premium, [data-theme="dark"] .select2-container--bootstrap4,
    [data-bs-theme="dark"] .cbt-status, [data-bs-theme="dark"] .modal-premium, [data-bs-theme="dark"] .select2-container--bootstrap4 {
        --text: #e6ecf5;
        --text-soft: #b4c2d4;
        --surface: #1b2635;
        --paper: #111a26;
        --line: #31405a;
        --field-line: #46587a;
        --row-hover: #243349;
        --muted: #a6b5c9;
        --muted-strong: #94a6bd;
        --icon-muted: #6f8299;
        --placeholder: #9aabc0;
        --gold: #e3b955;
        --gold-soft: #4a3a14;
        --gold-ink: #ffd98a;
        --gold-line: #7a6125;
        --ok-fg: #4fd8a6;
        --ok-soft: #173a2e;
        --bad: #ff8a9c;
        --bad-soft: #4a2029;
        --info-soft: #28405f;
        --btn-solid: #2c5b8c;
        --btn-solid-hover: #3a6fa5;
        --hl: #3a6fa5;
        --chip: #2c5b8c;
        --overlay: rgba(17, 26, 38, .75);
    }
    .dark-mode .cbt-status .token-panel, [data-theme="dark"] .cbt-status .token-panel, [data-bs-theme="dark"] .cbt-status .token-panel {
        box-shadow: inset 0 0 0 1px rgba(255, 255, 255, .14);
    }
</style>

<div class="content-wrapper cbt-status">
    <section class="content-header">
        <div class="container-fluid">
            <div class="page-head">
                <div>
                    <h1><?= $judul ?></h1>
                    <p class="page-sub"><?= $subjudul ?></p>
                </div>
                <button type="button" class="nbtn nbtn-outline btn-toggle" data-toggle="modal"
                        data-target="#infoModal"><i class="fas fa-info-circle"></i> Kode error
                </button>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-default panel-premium mb-4">
                <div class="card-body">
                    <div class="token-panel">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-center">Token ujian</span>
                            </div>
                            <input id="token-input" class="form-control text-bold text-center" readonly="readonly"/>
                            <div class="input-group-append" id="kolom-kanan">
                                <span id="interval" class="input-group-text text-xs d-none">-- : -- : --</span>
                            </div>
                        </div>
                    </div>

                    <div class="filter-grid">
                        <div class="filter-field span-2">
                            <label for="jadwal">Jadwal</label>
                            <div class="field-row">
                                <div class="field-main">
                                    <?php
                                    echo form_dropdown(
                                        'jadwal',
                                        $jadwal,
                                        null,
                                        'id="jadwal" class="form-control"'
                                    ); ?>
                                </div>
                                <div class="field-aside">
                                    <select name="printby" id="printby" class="form-control" aria-label="Tampilkan menurut">
                                        <option value="1" selected="selected">By Ruang</option>
                                        <option value="2">By Kelas</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="filter-field span-2 d-none" id="by-kelas">
                            <label for="kelas">Kelas</label>
                            <select name="kelas" id="kelas" class="form-control">
                            </select>
                        </div>
                        <div class="filter-field by-ruang">
                            <label for="ruang">Ruang</label>
                            <select name="ruang" id="ruang" class="form-control"></select>
                        </div>
                        <div class="filter-field by-ruang">
                            <label for="sesi">Sesi</label>
                            <select name="sesi" id="sesi" class="form-control"></select>
                        </div>
                    </div>

                    <div class="empty-state" id="empty-pilih">
                        <i class="fa fa-users"></i>
                        <h3>Pilih jadwal untuk mulai memantau</h3>
                        <p>Setelah jadwal, ruang, dan sesi (atau kelas) dipilih, status setiap siswa akan tampil di sini.</p>
                    </div>

                    <div id="info" class="d-none">
                        <div class="info-grid">
                            <div class="info-card">
                                <h6>Info ujian</h6>
                                <div id="info-ujian"></div>
                            </div>
                            <details class="info-card guide" id="info-penggunaan">
                                <summary>Panduan aksi</summary>
                                <ul>
                                    <li>Gunakan tombol <b>Refresh</b> untuk memuat ulang data siswa.</li>
                                    <li>
                                        <b>Reset waktu.</b> Jika siswa logout sebelum selesai dan tidak melanjutkan
                                        sampai waktu ujian habis maka akan ditolak, jika ingin melanjutkan maka
                                        harus reset waktu.
                                    </li>
                                    <li>
                                        <b>Jeda.</b> Menghentikan waktu siswa yang sedang mengerjakan. Klik
                                        tombol lanjutkan untuk melanjutkan, boleh sekaligus menambah menit.
                                    </li>
                                    <li>
                                        <b>Reset izin</b> untuk mengizinkan siswa mengerjakan ujian di
                                        perangkat berbeda.
                                    </li>
                                    <li><b>Paksa selesai</b> untuk memaksa siswa menyelesaikan ujian.</li>
                                    <li><b>Ulang</b> untuk mengulang ujian siswa dari awal.</li>
                                    <li>
                                        <b>Terapkan aksi</b> untuk menerapkan aksi terpilih ke setiap siswa yang dipilih.
                                    </li>
                                </ul>
                            </details>
                        </div>

                        <div class="stat-strip">
                            <div class="stat stat-muted"><b id="sum-belum">0</b><span>Belum mulai</span></div>
                            <div class="stat stat-ok"><b id="sum-jalan">0</b><span>Sedang mengerjakan</span></div>
                            <div class="stat stat-gold"><b id="sum-jeda">0</b><span>Dijeda</span></div>
                            <div class="stat"><b id="sum-selesai">0</b><span>Selesai</span></div>
                        </div>

                        <div class="toolbar">
                            <button type="button" class="nbtn nbtn-ok"
                                    onclick="refreshStatus()"
                                    data-toggle="tooltip"
                                    title="Refresh">
                                <i class="fa fa-sync"></i> Refresh
                            </button>
                            <?php
                            $dnone = $this->ion_auth->is_admin() ? '' : 'd-none';
                            ?>
                            <div class="toolbar-right">
                                <label><input type="search" id="cari-status-siswa"
                                              class="form-control form-control-sm" placeholder="Cari nama atau nomor peserta">
                                </label>
                                <button type="button" class="nbtn nbtn-solid <?= $dnone ?>"
                                        onclick="terapkanAksi()"
                                        data-toggle="tooltip"
                                        title="Terapkan Aksi pada siswa terpilih">
                                    <i class="fa fa-check"></i> Terapkan aksi
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="table-shell d-none" id="table-shell">
                        <table class="table" id="table-status">
                        </table>
                    </div>
                </div>
                <div class="overlay d-none" id="loading">
                    <div class="spinner-grow"></div>
                </div>
            </div>
        </div>
    </section>
</div>

<?= form_open('', array('id' => 'reset')) ?>
<div class="modal fade modal-premium" id="resetModal" tabindex="-1" role="dialog" aria-labelledby="resetLabel" aria-hidden="true">
    <div class="modal-dialog modal-xs" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resetLabel">Reset waktu siswa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-0">
                    <div class="icheck-success">
                        <input class="radio" type="radio" name="reset" id="reset1" value="1">
                        <label for="reset1">Reset waktu dari awal</label>
                    </div>
                    <div class="icheck-success">
                        <input class="radio" type="radio" name="reset" id="reset2" value="2">
                        <label for="reset2">Lanjutkan sisa waktu</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="nbtn nbtn-outline" data-dismiss="modal">Batal</button>
                <button type="submit" class="nbtn nbtn-solid">Reset waktu</button>
            </div>
        </div>
    </div>
</div>
<?= form_close() ?>

<div class="modal fade modal-premium" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="infoLabel" aria-hidden="true">
    <div class="modal-dialog modal-xs" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="infoLabel">Kode error siswa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table w-100 kode-error mb-0">
                    <tr>
                        <td><span class="code-chip">001</span></td>
                        <td>Token salah atau token belum digenerate</td>
                    </tr>
                    <tr>
                        <td><span class="code-chip">002</span></td>
                        <td>Harus reset izin</td>
                    </tr>
                    <tr>
                        <td><span class="code-chip">003</span></td>
                        <td>Harus reset waktu</td>
                    </tr>
                    <tr>
                        <td><span class="code-chip">004</span></td>
                        <td>Soal belum dibuat atau belum dipilih</td>
                    </tr>
                    <tr>
                        <td><span class="code-chip">005</span></td>
                        <td>Siswa menggunakan browser yang tidak mendukung</td>
                    </tr>
                    <tr>
                        <td><span class="code-chip">006</span></td>
                        <td>Internet down atau error dari database/server</td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="nbtn nbtn-outline" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    const ruangs = JSON.parse('<?=json_encode($ruang)?>');
    const arrRuang = JSON.parse('<?=json_encode($ruangs)?>');
    var dnone = '<?= $this->ion_auth->is_admin() ? "" : "d-none" ?>';
    var printBy = '1';
    var url = '';

    var kelas;
    var jadwal, ruang, sesi;
    var jedaMap = {};

    function terapkanAksi() {
        const $rows = $('#table-status').find('tr'), headers = $rows.splice(0, 2);
        let item = {};
        item ["reset"] = [];
        //item ["id_logs"] = [];
        item ["force"] = [];
        item ["log"] = [];
        item ["ulang"] = [];
        item ["hapus"] = [];
        $rows.each((i, row) => {
            var siswa_id = $(row).attr("data-id");

            const $colReset = $(row).find('.input-reset');
            const $colForce = $(row).find('.input-force');
            const $colUlang = $(row).find('.input-ulang');
            if ($colReset.prop("checked") === true) {
                item ["reset"].push(siswa_id + '0' + jadwal + '1');
                //item ["id_logs"].push(siswa_id+''+jadwal);
            }
            if ($colForce.prop("checked") === true) {
                item ["force"].push(siswa_id + '0' + jadwal);
                item ["log"].push(siswa_id);
            }
            if ($colUlang.prop("checked") === true) {
                item ["ulang"].push(siswa_id);
                item ["hapus"].push(siswa_id + '0' + jadwal);
            }
        });

        var dataSiswa = $('#reset').serialize() + '&jadwal=' + jadwal + "&aksi=" + JSON.stringify(item);
        console.log(dataSiswa);

        var jmlReset = item.reset.length === 0 ? '' : '<b>' + item.reset.length + '</b> siswa akan direset<br>';
        var jmlForce = item.force.length === 0 ? '' : '<b>' + item.force.length + '</b> siswa akan dipaksa selesai<br>';
        var jmlUlang = item.ulang.length === 0 ? '' : '<b>' + item.ulang.length + '</b> siswa akan mengulang ujian';

        if (item.reset.length === 0 && item.force.length === 0 && item.ulang.length === 0) {
            showWarningToast('Silahkan pilih siswa terlebih dulu');
            return;
        }

        swal.fire({
            title: "Terapkan Aksi",
            html: jmlReset + jmlForce + jmlUlang,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Apply"
        }).then(result => {
            if (result.value) {
                $.ajax({
                    url: base_url + "siswa/applyaction",
                    type: 'POST',
                    data: dataSiswa,
                    success: function (data) {
                        console.log(data);
                        if (printBy === '1') {
                            url = base_url + "cbtstatus/getsiswaruang?ruang=" + ruang + '&sesi=' + sesi + '&jadwal=' + jadwal;
                        } else {
                            url = base_url + "cbtstatus/getsiswakelas?kelas=" + kelas + '&jadwal=' + jadwal;
                        }
                        refreshStatus();
                    }, error: function (xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });
            }
        });
    }

    function refreshStatus() {
        $('#cari-status-siswa').val('')
        $('#loading').removeClass('d-none');
        setTimeout(function () {
            $.ajax({
                type: "GET",
                url: url,
                success: function (response) {
                    console.log(response);
                    $.ajax({
                        type: "GET",
                        url: base_url + "siswa/getjeda?jadwal=" + response.info.id_jadwal,
                        dataType: "json",
                        cache: false,
                        success: function (m) {
                            jedaMap = $.isArray(m) ? {} : (m || {});
                            createPreview(response);
                        },
                        error: function () {
                            jedaMap = {};
                            createPreview(response);
                        }
                    });
                }
            });
        }, 500);
    }

    function createPreview(data) {
        var tbody = '<thead>' +
            '<tr>' +
            '<th rowspan="2" class="text-center align-middle" width="40">No.</th>' +
            '<th rowspan="2" class="text-center align-middle" width="100">No. Peserta</th>' +
            '<th rowspan="2" class="text-center align-middle">Nama</th>' +
            '<th rowspan="2" class="text-center align-middle">Kelas</th>' +
            '<th colspan="2" class="text-center align-middle">Status</th>' +
            '<th rowspan="2" class="text-center align-middle ' + dnone + '">Reset<br>Waktu</th>' +
            '<th rowspan="2" class="text-center align-middle ' + dnone + '" width="130">Jeda<br>Waktu</th>' +
            '<th colspan="3" class="text-center align-middle ' + dnone + '">Aksi</th>' +
            '</tr>' +
            '<tr>' +
            '<th class="text-center align-middle p-1">Mulai</th>' +
            '<th class="text-center align-middle">Durasi</th>' +
            '<th class="text-center align-middle ' + dnone + '">Reset<br>Izin<br><input id="input-reset-all" class="check" type="checkbox"></th>' +
            '<th class="text-center align-middle ' + dnone + '">Paksa<br>Selesai<br><input id="input-force-all" class="check" type="checkbox"></th>' +
            '<th class="text-center align-middle ' + dnone + '">Ulang<br><input id="input-ulang-all" class="check" type="checkbox"></th>' +
            '</tr></thead><tbody>';

        var cnt = {'row-belum': 0, 'row-jalan': 0, 'row-jeda': 0, 'row-selesai': 0};
        if (data.siswa.length > 0) {
            for (let i = 0; i < data.siswa.length; i++) {
                var idSiswa = data.siswa[i].id_siswa;
                var durasi = data.durasi[idSiswa].dur != null ? data.durasi[idSiswa].dur.lama_ujian : ' - -';
                var adaWaktu = data.durasi[idSiswa].dur != null ? data.durasi[idSiswa].dur.ada_waktu : true;

                var logging = data.durasi[idSiswa].log;
                var mulai = '- -  :  - -';
                var selesai = '- -  :  - -';
                var reset = null;
                for (let k = 0; k < logging.length; k++) {
                    if (logging[k].log_type === '1') {
                        if (logging[k] != null) {
                            reset = logging[k].reset;
                            var t = logging[k].log_time.split(/[- :]/);
                            //var d = new Date(Date.UTC(t[0], t[1]-1, t[2], t[3], t[4], t[5]));
                            mulai = t[3] + ':' + t[4];
                        }
                    } else {
                        if (logging[k] != null) {
                            var ti = logging[k].log_time.split(/[- :]/);
                            selesai = ti[3] + ':' + ti[4];
                        }
                    }
                }

                var belumUjian = data.durasi[idSiswa].dur == null;
                var sudahSelesai = !belumUjian && data.durasi[idSiswa].dur.selesai != null;
                var loading = belumUjian ? '' : (sudahSelesai ? "" : '<i class="fa fa-spinner fa-spin mr-2"></i>');

                var disabledResetWaktu = !sudahSelesai && !adaWaktu ? '' : 'disabled';
                var disabledReset = !sudahSelesai && reset != null && reset == '0' ? '' : 'disabled';
                var disabledSelesai = !sudahSelesai && !belumUjian ? '' : 'disabled';
                var disabledUlang = belumUjian ? 'disabled' : (sudahSelesai ? '' : 'disabled');
                // jeda hanya aktif untuk siswa yang sedang mengerjakan
                var disabledJeda = (belumUjian || sudahSelesai) ? 'disabled' : '';
                var jd = jedaMap[idSiswa] || null;
                var sedangJeda = !!(jd && jd.jeda) && !sudahSelesai;
                var wt = jd && jd.waktu_tambahan ? parseInt(jd.waktu_tambahan) : 0;
                var badgeTambah = (sedangJeda ? ' <span class="badge badge-warning">Dijeda</span>' : '') +
                    (wt > 0 ? ' <span class="badge badge-info">+' + wt + ' m</span>' : '');

                var rowState = belumUjian ? 'row-belum' : (sudahSelesai ? 'row-selesai' : (sedangJeda ? 'row-jeda' : 'row-jalan'));
                cnt[rowState]++;

                // jika ingin selalu aktif
                // var disabledResetWaktu = '';     // reset waktu selalu aktif
                // var disabledReset = '';          // reset izin selalu aktif
                // var disabledSelesai = '';        // paksa selesai selalu aktif
                // var disabledUlang = '';         // ulangi selalu aktif

                var kelas = data.siswa[i].kode_kelas;

                tbody += '<tr class="' + rowState + '" data-id="' + idSiswa + '">' +
                    '<td class="text-center align-middle">' + (i + 1) + '</td>' +
                    '<td class="text-center align-middle">' + data.siswa[i].nomor_peserta + '</td>' +
                    '<td class="align-middle">' + data.siswa[i].nama + '</td>' +
                    '<td class="text-center align-middle">' + kelas + '</td>' +
                    '<td class="text-center align-middle">' + mulai + '</td>' +
                    '<td class="text-center align-middle">' + loading + durasi + badgeTambah + '</td>' +
                    '<td class="text-center align-middle '+dnone+'">' +
                    '	<button type="button" class="btn-icon" ' +
                    'data-siswa="' + idSiswa + '" data-jadwal="' + data.info.id_jadwal + '" ' +
                    'data-toggle="modal" data-target="#resetModal" ' + disabledReset + '><i class="fa fa-sync"></i></button>' +
                    '</td>' +
                    '<td class="text-center align-middle ' + dnone + '">' +
                    (sedangJeda
                        ? '<div class="input-group input-group-sm" title="Isi tambahan menit (boleh 0), lalu klik lanjutkan">' +
                          '<input type="number" min="0" max="240" value="0" class="form-control input-tambah">' +
                          '<div class="input-group-append">' +
                          '<button type="button" class="btn-mini btn-mini-ok btn-lanjut-waktu" ' +
                          'data-siswa="' + idSiswa + '" data-jadwal="' + data.info.id_jadwal + '"><i class="fa fa-play"></i></button>' +
                          '</div></div>'
                        : '<button type="button" class="btn-mini btn-mini-gold btn-jeda-waktu" ' +
                          'data-siswa="' + idSiswa + '" data-jadwal="' + data.info.id_jadwal + '" ' + disabledJeda + '>' +
                          '<i class="fa fa-pause mr-1"></i> Jeda</button>') +
                    '</td>' +
                    '<td class="text-center text-success align-middle ' + dnone + '">' +
                    '<input class="check input-reset" type="checkbox" ' + disabledReset + '>' +
                    '</td>' +
                    '<td class="text-center text-danger align-middle ' + dnone + '">' +
                    '<input class="check input-force" type="checkbox" ' + disabledSelesai + '>' +
                    '</td>' +
                    '<td class="text-center text-danger align-middle ' + dnone + '">' +
                    '<input class="check input-ulang" type="checkbox" ' + disabledUlang + '>' +
                    '</td>' +
                    '</tr>';
            }
        } else {
            tbody += '<tr><td colspan="11" class="text-center">Tidak ada siswa tergabung disini!</td></tr>'
        }

        tbody += '</tbody>';
        $('#table-status').html(tbody);
        $('#info').removeClass('d-none');
        $('#table-shell').removeClass('d-none');
        $('#empty-pilih').addClass('d-none');
        $('#sum-belum').text(cnt['row-belum']);
        $('#sum-jalan').text(cnt['row-jalan']);
        $('#sum-jeda').text(cnt['row-jeda']);
        $('#sum-selesai').text(cnt['row-selesai']);

        var jmlSoal = parseInt(data.info.tampil_pg) + parseInt(data.info.tampil_kompleks) +
            parseInt(data.info.tampil_jodohkan) + parseInt(data.info.tampil_isian) +
            parseInt(data.info.tampil_esai);
        var infoJadwal = '<dl class="kv">' +
            '<dt>Mapel</dt><dd>' + data.info.nama_mapel + '</dd>' +
            '<dt>Guru</dt><dd>' + data.info.nama_guru + '</dd>' +
            '<dt>Jenis ujian</dt><dd>' + data.info.kode_jenis + '</dd>' +
            '<dt>Jml. soal</dt><dd>' + jmlSoal + '</dd>' +
            '</dl>';

        $('#info-ujian').html(infoJadwal);
        $('#loading').addClass('d-none');

        $("#input-reset-all").on("click", function () {
            if (this.checked) {
                $(".input-reset").each(function () {
                    if (!$(this).prop('disabled')) {
                        this.checked = true;
                    }
                    $("#input-reset-all").prop("checked", true);
                });
            } else {
                $(".input-reset").each(function () {
                    if (!$(this).prop('disabled')) {
                        this.checked = false;
                    }
                    $("#input-reset-all").prop("checked", false);
                });
            }
        });

        $("#input-force-all").on("click", function () {
            if (this.checked) {
                $(".input-force").each(function () {
                    if (!$(this).prop('disabled')) {
                        this.checked = true;
                    }
                    $("#input-force-all").prop("checked", true);
                });
            } else {
                $(".input-force").each(function () {
                    if (!$(this).prop('disabled')) {
                        this.checked = false;
                    }
                    $("#input-force-all").prop("checked", false);
                });
            }
        });

        $("#input-ulang-all").on("click", function () {
            if (this.checked) {
                $(".input-ulang").each(function () {
                    if (!$(this).prop('disabled')) {
                        this.checked = true;
                    }
                    $("#input-ulang-all").prop("checked", true);
                });
            } else {
                $(".input-ulang").each(function () {
                    if (!$(this).prop('disabled')) {
                        this.checked = false;
                    }
                    $("#input-ulang-all").prop("checked", false);
                });
            }
        });

        $('#cari-status-siswa').quicksearch('#table-status tbody tr');
    }

    function getDetailJadwal(idJadwal) {
        $.ajax({
            type: "GET",
            url: base_url + "cbtstatus/getjadwalujianbyjadwal?id_jadwal=" + idJadwal,
            cache: false,
            success: function (response) {
                console.log(response);
                var selKelas = $('#kelas');
                selKelas.html('');
                selKelas.append('<option value="">Pilih Kelas</option>');
                $.each(response, function (k, v) {
                    if (v != null) {
                        selKelas.append('<option value="' + k + '">' + v + '</option>');
                    }
                });
            }
        });
    }

    $(document).ready(function () {
        ajaxcsrf();

        var opsiPrintBy = $("#printby");
        var opsiJadwal = $("#jadwal");
        var opsiRuang = $("#ruang");
        var opsiSesi = $("#sesi");
        var opsiKelas = $("#kelas");

        opsiJadwal.prepend("<option value='' selected='selected'>Pilih Jadwal</option>");
        opsiRuang.prepend("<option value='' selected='selected'>Pilih Ruang</option>");
        opsiSesi.prepend("<option value='' selected='selected'>Pilih Sesi</option>");
        opsiKelas.prepend("<option value='' selected='selected'>Pilih Kelas</option>");

        function loadSiswaRuang(ruang, sesi, jadwal) {
            var empty = ruang === '' || sesi === '' || jadwal === '';
            if (!empty) {
                url = base_url + "cbtstatus/getsiswaruang?ruang=" + ruang + '&sesi=' + sesi + '&jadwal=' + jadwal;
                refreshStatus();
            } else {
                console.log('empty')
            }
        }

        function loadSiswaKelas(kelas, jadwal) {
            var empty = kelas === '' || jadwal === '';
            if (!empty) {
                url = base_url + "cbtstatus/getsiswakelas?kelas=" + kelas + '&jadwal=' + jadwal;
                refreshStatus();
            } else {
                console.log('empty')
            }
        }

        opsiPrintBy.change(function () {
            printBy = $(this).val();
            if (printBy === '1') {
                $('#by-kelas').addClass('d-none');
                $('.by-ruang').removeClass('d-none');
                if (ruang && sesi && jadwal) loadSiswaRuang(ruang, sesi, jadwal)
            } else {
                $('#by-kelas').removeClass('d-none');
                $('.by-ruang').addClass('d-none');
                if (kelas && jadwal) loadSiswaKelas(kelas, jadwal)
            }
        });

        opsiJadwal.change(function () {
            getDetailJadwal($(this).val());
            opsiRuang.html("<option value='' selected='selected'>Pilih Ruang</option>");
            if ($(this).val()) {
                $.each(arrRuang, function (k, v) {
                    opsiRuang.append("<option value='"+k+"'>"+ruangs[k]+"</option>");
                })
            }
        });

        opsiKelas.change(function () {
            kelas = $(this).val();
            jadwal = opsiJadwal.val();
            loadSiswaKelas(kelas, jadwal)
        });

        opsiRuang.change(function () {
            opsiSesi.html("<option value='' selected='selected'>Pilih Sesi</option>");
            if ($(this).val()) {
                $.each(arrRuang[$(this).val()], function (k, v) {
                    opsiSesi.append("<option value='"+k+"'>"+v.nama_sesi+"</option>");
                })
            }
        });

        opsiSesi.change(function () {
            sesi = $(this).val();
            ruang = opsiRuang.val();
            jadwal = opsiJadwal.val();
            loadSiswaRuang(ruang, $(this).val(), jadwal)
        })

        var idSiswa = '';
        var idJadwal = '';
        $('#resetModal').on('show.bs.modal', function (e) {
            idSiswa = $(e.relatedTarget).data('siswa');
            idJadwal = $(e.relatedTarget).data('jadwal');

            console.log('siswa:' + idSiswa, 'jadwal:' + idJadwal);
        });

        $('#reset').on('submit', function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();

            $('#resetModal').modal('hide').data('bs.modal', null);
            $('#resetModal').on('hidden', function () {
                $(this).data('modal', null);
            });

            $.ajax({
                url: base_url + "siswa/resettimer",
                type: 'POST',
                data: $(this).serialize() + '&id_durasi=' + idSiswa + '0' + idJadwal,
                success: function (data) {
                    console.log(data.status);
                    if (data.status) refreshStatus();
                    else showDangerToast('tidak bisa mereset waktu');
                }, error: function (xhr, status, error) {
                    console.log('error');
                }
            });
        });

        // Jeda waktu siswa
        $('#table-status').on('click', '.btn-jeda-waktu', function () {
            var $btn = $(this);
            var idDurasi = $btn.data('siswa') + '0' + $btn.data('jadwal');
            $btn.prop('disabled', true);
            $.ajax({
                url: base_url + "siswa/jedawaktu",
                type: 'POST',
                dataType: 'json',
                data: {id_durasi: idDurasi},
                success: function (data) {
                    if (data.status) refreshStatus();
                    else {
                        showDangerToast(data.msg || 'tidak bisa menjeda');
                        $btn.prop('disabled', false);
                    }
                }, error: function (xhr) {
                    console.log(xhr.responseText);
                    showDangerToast('terjadi kesalahan');
                    $btn.prop('disabled', false);
                }
            });
        });

        // Lanjutkan waktu siswa (boleh sekalian tambah menit)
        $('#table-status').on('click', '.btn-lanjut-waktu', function () {
            var $btn = $(this);
            var menit = parseInt($btn.closest('tr').find('.input-tambah').val());
            if (isNaN(menit)) menit = 0;
            if (menit < 0 || menit > 240) {
                showWarningToast('Tambahan waktu 0 - 240 menit');
                return;
            }
            var idDurasi = $btn.data('siswa') + '0' + $btn.data('jadwal');
            $btn.prop('disabled', true);
            $.ajax({
                url: base_url + "siswa/lanjutwaktu",
                type: 'POST',
                dataType: 'json',
                data: {id_durasi: idDurasi, menit: menit},
                success: function (data) {
                    if (data.status) refreshStatus();
                    else {
                        showDangerToast(data.msg || 'tidak bisa melanjutkan');
                        $btn.prop('disabled', false);
                    }
                }, error: function (xhr) {
                    console.log(xhr.responseText);
                    showDangerToast('terjadi kesalahan');
                    $btn.prop('disabled', false);
                }
            });
        });

        opsiKelas.select2({theme: 'bootstrap4', width: '100%'});
        opsiRuang.select2({theme: 'bootstrap4', width: '100%'});
        opsiSesi.select2({theme: 'bootstrap4', width: '100%'});
        opsiJadwal.select2({theme: 'bootstrap4', width: '100%'});

    });

</script>