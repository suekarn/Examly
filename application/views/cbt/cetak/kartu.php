<?php
// CSS kartu: dipakai di preview, di halaman cetak, dan ikut tercetak (lihat createPrintPreview).
// Jangan taruh gaya halaman (tombol, panel, stage) di sini.
$kpCss = <<<'CSS'
.kp-card{position:relative;box-sizing:border-box;width:10cm;height:6.4cm;background-color:#fffdf8 !important;color:#14213d !important;border:1.5px solid #16315f;border-radius:9px;overflow:hidden;display:flex;flex-direction:column;font-family:"Segoe UI",Arial,Helvetica,sans-serif;text-align:left;-webkit-print-color-adjust:exact;print-color-adjust:exact;page-break-inside:avoid;break-inside:avoid}
.kp-card *{box-sizing:border-box}
.kp-card::after{content:"";position:absolute;left:2.5px;top:2.5px;right:2.5px;bottom:2.5px;border:1px solid rgba(184,145,63,.9);border-radius:6.5px;pointer-events:none;z-index:3}
.kp-strip{flex:none;height:5px;background:linear-gradient(90deg,#102650 0%,#21468a 50%,#102650 100%) !important}
.kp-head{flex:none;width:100%;table-layout:fixed;border-collapse:collapse;background:linear-gradient(180deg,#fffdf8 0%,#f6efdf 100%) !important;border-bottom:1.5px solid #16315f}
.kp-head td{padding:2px 6px;vertical-align:middle}
.kp-logo{width:16%;text-align:center}
.kp-head td.kp-logo{padding:2px 3px}
.kp-logo img{width:13mm;height:13mm;object-fit:contain;margin:0}
.kp-title{width:68%;text-align:center;line-height:1.1}
.kp-title .h1,.kp-title .h2,.kp-title .h3,.kp-title .h4{margin:0 !important;padding:0 !important;line-height:1.1 !important;border:0;background:none}
.kp-title .h1{font-size:7.6pt;font-weight:600;color:#16315f;letter-spacing:.3px}
.kp-title .h2{font-size:10pt;font-weight:700;font-family:"Palatino Linotype","Book Antiqua",Palatino,Georgia,serif;color:#0f2247;letter-spacing:.6px;white-space:nowrap}
.kp-title .h3,.kp-title .h4{font-size:6.6pt;font-weight:400;color:#46557a}
.kp-body{flex:1;display:flex;gap:9px;padding:4px 9px 2px 9px;margin-top:2px;min-height:0;border-top:1px solid #b8913f;background-image:linear-gradient(100deg,rgba(255,253,248,1) 0%,rgba(255,253,248,.88) 50%,rgba(255,253,248,0) 100%),repeating-radial-gradient(circle at 96% 108%,rgba(22,49,95,.075) 0,rgba(22,49,95,.075) .7px,transparent .7px,transparent 5px)}
.kp-foto{flex:none;width:24mm;position:relative;align-self:flex-start}
.kp-foto img{width:24mm;height:31mm;object-fit:cover;object-position:center top;padding:2px;background:#fff;border:1px solid #16315f;outline:1px solid #b8913f;outline-offset:1.5px;border-radius:2px;display:block}
.kp-cam{position:absolute;right:5px;bottom:5px;width:16px;height:16px;border-radius:50%;background:#b8913f;border:1px solid #fff;color:#fff;font-size:8px;line-height:14px;text-align:center}
@media print{.kp-cam{display:none !important}}
.kp-main{flex:1;display:flex;flex-direction:column;justify-content:space-between;min-width:0}
.kp-data{width:100%;border-collapse:collapse;font-size:8pt;line-height:1.22}
.kp-data td{padding:0 2px 0 0;vertical-align:top;color:#14213d;word-break:break-word}
.kp-data td:first-child{width:22mm;white-space:nowrap;color:#5a6a88;font-size:7.4pt;padding-top:.5px}
.kp-data td:nth-child(2){width:3mm;text-align:center;color:#5a6a88}
.kp-data tr:first-child td{padding-bottom:1px;border-bottom:1px solid #d8c38d}
.kp-data tr:nth-child(2) td{padding-top:2px}
.kp-data tr:first-child td:nth-child(3) b{font-family:Georgia,"Times New Roman",serif;font-size:9.2pt;line-height:1.12;color:#0f2247}
.kp-data tr:nth-child(n+4) td:nth-child(3) b{display:inline-block;padding:0 5px;background:#eef2fa;border:0;border-left:2px solid #b8913f;border-radius:2px;font-family:Consolas,"Courier New",monospace;font-size:8pt;line-height:1.2;letter-spacing:.4px;color:#0f2247}
.kp-ttd{align-self:flex-end;width:44mm;text-align:center;font-size:6.8pt;line-height:1.12;color:#14213d;background-repeat:no-repeat;background-position:center;background-size:auto 30px}
.kp-ttd .sp{display:block;height:13px}
.kp-ttd .nm{display:inline-block;padding:0 2px;font-weight:700;color:#0f2247;line-height:1.1;border-bottom:1px solid #16315f}
.kp-foot{position:relative;z-index:1;flex:none;background:#16315f !important;color:#ecd9a2;text-align:center;font-size:5.8pt;letter-spacing:.5px;padding:2px 0;border-top:1px solid #b8913f}
.kp-foot::before,.kp-foot::after{content:"\25C6";font-size:4pt;color:#b8913f;margin:0 6px;vertical-align:middle}
CSS;
?>
<style><?= $kpCss ?></style>
<style>
    /* ===== Gaya halaman (tidak ikut tercetak) ===== */
    .kp-page {
        --kp-navy: #16315f;
        --kp-navy-deep: #0d1f40;
        --kp-gold: #b8913f;
        --kp-gold-soft: #ecd9a2;
        --kp-ink: #14213d;
        --kp-mute: #66748f;
        --kp-line: #e1e6f0;
        background: #f4f6fb !important;
        color: var(--kp-ink);
    }

    .kp-page .kp-pagehead {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: .5rem;
    }

    .kp-page .kp-heading {
        margin: 0;
        font-family: Georgia, "Times New Roman", serif;
        font-size: 1.7rem;
        font-weight: 700;
        line-height: 1.2;
        letter-spacing: -.01em;
        color: var(--kp-navy);
    }

    .kp-page .kp-sub {
        margin: .25rem 0 0;
        font-size: .9rem;
        color: var(--kp-mute);
    }

    /* Tombol */
    .kp-page .kp-btn {
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        padding: .45rem .95rem;
        border: 1px solid transparent;
        border-radius: 8px;
        font-weight: 600;
        line-height: 1.4;
        transition: background-color .15s, box-shadow .15s, filter .15s;
    }

    .kp-page .kp-btn:focus-visible {
        outline: 2px solid var(--kp-gold);
        outline-offset: 2px;
    }

    .kp-page .kp-btn-ghost {
        background: #fff;
        border-color: var(--kp-line);
        color: var(--kp-navy);
    }

    .kp-page .kp-btn-ghost:hover {
        background: #eef2fa;
        color: var(--kp-navy);
    }

    .kp-page .kp-btn-navy {
        background: var(--kp-navy);
        color: #fff;
    }

    .kp-page .kp-btn-navy i {
        color: var(--kp-gold-soft);
    }

    .kp-page .kp-btn-navy:hover {
        background: #21468a;
        color: #fff;
    }

    .kp-page .kp-btn-gold {
        background: linear-gradient(180deg, #dcc17a 0%, #b8913f 100%);
        border-color: #a3802f;
        color: #1a2540;
        padding-left: 1.2rem;
        padding-right: 1.2rem;
    }

    .kp-page .kp-btn-gold:hover {
        filter: brightness(1.06);
        color: #1a2540;
    }

    /* Panel */
    .kp-page .kp-panel {
        margin-bottom: 1.25rem;
        overflow: hidden;
        background: #fff;
        border: 1px solid var(--kp-line);
        border-radius: 12px;
        box-shadow: 0 1px 2px rgba(13, 31, 64, .05), 0 10px 30px -18px rgba(13, 31, 64, .28);
    }

    .kp-page .kp-panel > .card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: .9rem 1.25rem;
        background: #fff;
        border-bottom: 1px solid var(--kp-line);
    }

    .kp-page .kp-panel > .card-header::after {
        display: none;
    }

    .kp-page .kp-panel .card-title {
        float: none;
        margin: 0;
        font-size: 1rem;
        font-weight: 700;
        color: var(--kp-navy);
    }

    .kp-page .kp-panel-meta {
        font-size: .78rem;
        color: var(--kp-mute);
    }

    .kp-page .kp-panel > .card-body {
        padding: 1.25rem;
    }

    /* Form */
    .kp-page .kp-group-title {
        margin: 0 0 .15rem;
        font-size: .9rem;
        font-weight: 700;
        color: var(--kp-navy);
    }

    .kp-page .kp-group-desc {
        margin: 0 0 .9rem;
        font-size: .8rem;
        color: var(--kp-mute);
    }

    .kp-page .kp-group + .kp-group {
        margin-top: 1.4rem;
        padding-top: 1.2rem;
        border-top: 1px dashed #d3dbea;
    }

    .kp-page .form-group {
        margin-bottom: .95rem;
    }

    .kp-page .form-group > label {
        margin-bottom: .25rem;
        font-size: .85rem;
        font-weight: 600;
        color: var(--kp-ink);
    }

    .kp-page .kp-hint {
        display: block;
        margin-top: .25rem;
        font-size: .76rem;
        color: var(--kp-mute);
    }

    .kp-page .form-control {
        background: #fbfcfe;
        border: 1px solid #d5dcea;
        border-radius: 8px;
        box-shadow: none;
        font-size: .92rem;
        color: var(--kp-ink);
        transition: border-color .15s, box-shadow .15s, background-color .15s;
    }

    .kp-page .form-control:focus {
        background: #fff;
        border-color: var(--kp-navy);
        box-shadow: 0 0 0 3px rgba(184, 145, 63, .28);
    }

    .kp-page textarea.form-control {
        resize: vertical;
        min-height: 3.1rem;
    }

    /* Panggung pratinjau kartu */
    .kp-page .kp-stage {
        padding: 34px 16px 22px;
        overflow-x: auto;
        background-color: #16315f;
        background-image: radial-gradient(120% 90% at 50% 0%, #2b4f8f 0%, rgba(22, 49, 95, 0) 70%),
        radial-gradient(rgba(236, 217, 162, .13) 1px, transparent 1px);
        background-size: auto, 16px 16px;
    }

    .kp-page .kp-stage-inner {
        display: flex;
    }

    .kp-page .kp-stage .kp-card {
        flex: none;
        margin: 0 auto;
        box-shadow: 0 22px 40px -14px rgba(0, 0, 0, .65), 0 3px 8px rgba(0, 0, 0, .35);
    }

    .kp-page .kp-stage-note {
        margin: 18px 0 0;
        text-align: center;
        font-size: .78rem;
        color: #b4c2e0;
    }

    /* Panel cetak */
    .kp-page .kp-print-row {
        display: flex;
        gap: 10px;
        align-items: stretch;
    }

    .kp-page .kp-print-select {
        flex: 1;
        min-width: 0;
    }

    .kp-page .kp-print-select .select2-container {
        width: 100% !important;
    }

    .kp-page .select2-container--bootstrap4 .select2-selection {
        border: 1px solid #d5dcea;
        border-radius: 8px;
    }

    /* Lembar cetak */
    .kp-page .kp-sheet-stage {
        display: flex;
        align-items: flex-start;
        min-height: 420px;
        padding: 28px 12px;
        overflow-x: auto;
        border-radius: 8px;
        background-color: #e6eaf3;
        background-image: radial-gradient(rgba(22, 49, 95, .13) 1px, transparent 1px);
        background-size: 18px 18px;
    }

    .kp-page #print-preview {
        margin: 0 auto !important;
    }

    .kp-page #print-preview:empty {
        display: none;
    }

    .kp-page #print-preview > div.border {
        border: 0 !important;
        box-shadow: 0 14px 34px -14px rgba(13, 31, 64, .5), 0 1px 3px rgba(13, 31, 64, .22) !important;
    }

    .kp-page .kp-empty {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: .35rem;
        max-width: 320px;
        margin: auto;
        padding: 60px 0;
        text-align: center;
        font-size: .86rem;
        color: var(--kp-mute);
    }

    .kp-page .kp-empty strong {
        font-size: 1rem;
        color: var(--kp-navy);
    }

    .kp-page .kp-empty-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 52px;
        height: 52px;
        margin-bottom: .4rem;
        border: 1px solid #cdd6e8;
        border-radius: 50%;
        background: #fff;
        font-size: 1.2rem;
        color: var(--kp-gold);
    }

    .kp-page #print-preview:not(:empty) + .kp-empty {
        display: none;
    }

    @media (max-width: 575.98px) {
        .kp-page .kp-heading {
            font-size: 1.4rem;
        }

        .kp-page .kp-print-row {
            flex-direction: column;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .kp-page * {
            transition: none !important;
        }
    }
</style>
<div class="content-wrapper kp-page pt-4">
    <section class="content-header">
        <div class="container-fluid">
            <div class="kp-pagehead">
                <div>
                    <h1 class="kp-heading"><?= $judul ?></h1>
                    <p class="kp-sub">Atur kop dan tanggal kartu, cek tampilannya, lalu cetak per kelas.</p>
                </div>
                <a href="<?= base_url('cbtcetak') ?>" class="kp-btn kp-btn-ghost btn btn-sm">
                    <i class="fas fa-arrow-circle-left"></i><span class="d-none d-sm-inline-block">Kembali</span>
                </a>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-5">
                    <div class="card kp-panel">
                        <div class="card-header">
                            <h6 class="card-title">Setting kartu</h6>
                            <button type="button" class="kp-btn kp-btn-navy btn btn-sm" onclick="submitKartu()">
                                <i class="fas fa-save"></i><span>Simpan</span>
                            </button>
                        </div>
                        <div class="card-body">
                            <?= form_open('', array('id' => 'set-kartu')) ?>
                            <div class="kp-group">
                                <p class="kp-group-title">Kop kartu</p>
                                <p class="kp-group-desc">Empat baris teks di bagian atas kartu, lengkap dengan logo kiri dan kanan.</p>
                                <div class="form-group">
                                    <label for="header-1">Header 1</label>
                                    <textarea id="header-1" class="form-control" name="header_1" rows="2"
                                              placeholder="Header baris 1"
                                              required><?= isset($kartu->header_1) ? $kartu->header_1 : '' ?></textarea>
                                    <small class="kp-hint">Baris pembuka, ukuran sedang.</small>
                                </div>
                                <div class="form-group">
                                    <label for="header-2">Header 2</label>
                                    <textarea id="header-2" class="form-control" name="header_2" rows="2"
                                              placeholder="Header baris 2"
                                              required><?= isset($kartu->header_2) ? $kartu->header_2 : '' ?></textarea>
                                    <small class="kp-hint">Baris utama, tampil paling besar dan tebal.</small>
                                </div>
                                <div class="form-group">
                                    <label for="header-3">Header 3</label>
                                    <textarea id="header-3" class="form-control" name="header_3" rows="2"
                                              placeholder="Header baris 3"
                                              required><?= isset($kartu->header_3) ? $kartu->header_3 : '' ?></textarea>
                                    <small class="kp-hint">Baris pendukung, ukuran kecil.</small>
                                </div>
                                <div class="form-group">
                                    <label for="header-4">Header 4</label>
                                    <textarea id="header-4" class="form-control" name="header_4" rows="2"
                                              placeholder="Header baris 4"
                                              required><?= isset($kartu->header_4) ? $kartu->header_4 : '' ?></textarea>
                                    <small class="kp-hint">Baris pendukung, ukuran kecil.</small>
                                </div>
                            </div>
                            <div class="kp-group">
                                <p class="kp-group-title">Tanda tangan</p>
                                <p class="kp-group-desc">Tanggal tampil di atas nama kepala <?= $setting->satuan_pendidikan == '2' ? 'madrasah' : 'sekolah' ?>.</p>
                                <div class="form-group mb-0">
                                    <label for="input-tanggal">Tanggal</label>
                                    <input id="input-tanggal" class="form-control" name="tanggal" placeholder="Titimangsa"
                                           value="<?= isset($kartu->tanggal) ? $kartu->tanggal : '' ?>" required>
                                    <small class="kp-hint">Contoh: 2 November 2026</small>
                                </div>
                            </div>
                            <?= form_close() ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="card kp-panel">
                        <div class="card-header">
                            <div class="card-title">Pratinjau kartu</div>
                            <span class="kp-panel-meta">Ukuran asli 10 × 6,4 cm</span>
                        </div>
                        <div class="kp-stage">
                            <div class="kp-stage-inner">
                                <div class="kp-card">
                                    <div class="kp-strip"></div>
                                    <table class="kp-head">
                                        <tr>
                                            <td class="kp-logo"><img alt="Logo kiri" id="prev-logo-kiri" src="<?= base_url() . $setting->logo_kiri ?>"></td>
                                            <td class="kp-title">
                                                <div id="prev-header-1" class="h1"><?= isset($kartu->header_1) ? $kartu->header_1 : '' ?></div>
                                                <div id="prev-header-2" class="h2"><?= isset($kartu->header_2) ? $kartu->header_2 : '' ?></div>
                                                <div id="prev-header-3" class="h3"><?= isset($kartu->header_3) ? $kartu->header_3 : '' ?></div>
                                                <div id="prev-header-4" class="h4"><?= isset($kartu->header_4) ? $kartu->header_4 : '' ?></div>
                                            </td>
                                            <td class="kp-logo"><img alt="Logo kanan" id="prev-logo-kanan" src="<?= base_url() . $setting->logo_kanan ?>"></td>
                                        </tr>
                                    </table>
                                    <div class="kp-body">
                                        <div class="kp-foto"><img alt="Foto" src="<?= base_url('assets/img/siswa.png') ?>"></div>
                                        <div class="kp-main">
                                            <table class="kp-data">
                                                <tr><td>Nama</td><td>:</td><td><b>Nama Siswa</b></td></tr>
                                                <tr><td>Kelas</td><td>:</td><td>IXA</td></tr>
                                                <tr><td>Jenis Kelamin</td><td>:</td><td>Laki-laki</td></tr>
                                                <tr><td>Username</td><td>:</td><td><b>umbk001</b></td></tr>
                                                <tr><td>Password</td><td>:</td><td><b>umbk001</b></td></tr>
                                            </table>
                                            <div id="prev-tandatangan" class="kp-ttd"
                                                 style="<?= !empty($setting->tanda_tangan) ? 'background-image:url(' . base_url() . $setting->tanda_tangan . ')' : '' ?>">
                                                <span id="prev-kota"><?= (!empty(trim($setting->kota)) && trim($setting->kota) !== '-') ? $setting->kota . ', ' : '' ?></span><span id="prev-tanggal"><?= isset($kartu->tanggal) ? $kartu->tanggal : '' ?></span><br>
                                                Kepala <?= $setting->satuan_pendidikan == '2' ? 'Madrasah' : 'Sekolah' ?>
                                                <span class="sp"></span>
                                                <span id="prev-kepsek" class="nm"><?= $setting->kepsek ?></span><br>
                                                <span id="prev-nip">NIP: <?= !empty($setting->nip) ? $setting->nip : '-' ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="kp-foot">Kartu ini wajib dibawa selama ujian berlangsung</div>
                                </div>
                            </div>
                            <p class="kp-stage-note">Perubahan di form langsung tampil di kartu ini.</p>
                        </div>
                    </div>
                    <div class="card kp-panel">
                        <div class="card-header">
                            <div class="card-title">Cetak</div>
                            <span class="kp-panel-meta">A4, 8 kartu per halaman</span>
                        </div>
                        <div class="card-body">
                            <div class="kp-print-row">
                                <div class="kp-print-select">
                                    <?php
                                    echo form_dropdown(
                                        'kelas',
                                        $kelas,
                                        null,
                                        'id="kelas" class="form-control"'
                                    ); ?>
                                    <?php
                                    echo form_dropdown(
                                        'ruang',
                                        $ruang,
                                        null,
                                        'id="ruang" class="form-control d-none"'
                                    ); ?>
                                </div>
                                <button type="button" class="kp-btn kp-btn-gold btn" id="btn-print">
                                    <i class="fa fa-print"></i><span>Cetak</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card kp-panel">
                        <div class="card-header">
                            <div class="card-title">Pratinjau halaman cetak</div>
                        </div>
                        <div class="card-body">
                            <div class="kp-sheet-stage">
                                <div id="print-preview" class="m-2"></div>
                                <div class="kp-empty">
                                    <span class="kp-empty-icon"><i class="fas fa-id-card"></i></span>
                                    <strong>Belum ada kartu untuk dicetak</strong>
                                    <span>Pilih kelas di panel Cetak, lalu kartu seluruh peserta tampil di sini.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<input type="file" id="kp-file" accept="image/png,image/jpeg" style="display: none">
<script src="<?= base_url() ?>/assets/app/js/print-area.js"></script>
<script>
    var oldVal1 = "<?= isset($kartu->header_1) ? $kartu->header_1 : "" ?>";
    var oldVal2 = "<?= isset($kartu->header_2) ? $kartu->header_2 : "" ?>";
    var oldVal3 = "<?= isset($kartu->header_3) ? $kartu->header_3 : "" ?>";
    var oldVal4 = "<?= isset($kartu->header_4) ? $kartu->header_4 : "" ?>";
    var oldTgl = "<?= isset($kartu->tanggal) ? $kartu->tanggal : "" ?>";
    var oldKota = "<?= (!empty(trim($setting->kota)) && trim($setting->kota) !== '-') ? $setting->kota . ', ' : '' ?>";
    var logoKanan = "<?=base_url() . $setting->logo_kanan?>";
    var logoKiri = "<?=base_url() . $setting->logo_kiri?>";
    var tandatangan = "<?=base_url() . $setting->tanda_tangan?>";
    var kepsek = "<?= $setting->kepsek ?>";
    var nip = "<?= $setting->nip ?>";
    var satuanPend = "<?= $setting->satuan_pendidikan ?>" === "2" ? "Madrasah" : "Sekolah";
    var printBy = 1;
    var kpCss = <?= json_encode($kpCss) ?>;

    var nipKepsek = nip !== '' ? nip : '-';
    console.log('nip', nipKepsek)

    function submitKartu() {
        $('#set-kartu').submit();
    }

    // Normalisasi jenis kelamin: terima L / P / 1 / 2 / "Laki-laki" / "Perempuan" (huruf besar/kecil bebas)
    function normalizeJK(raw) {
        var v = String(raw === null || raw === undefined ? '' : raw).trim().toUpperCase();
        if (v === 'L' || v === '1' || v.indexOf('LAKI') === 0) return 'L';
        if (v === 'P' || v === '2' || v.indexOf('PEREMPUAN') === 0 || v.indexOf('WANITA') === 0) return 'P';
        return '';
    }

    function labelJK(raw) {
        var jk = normalizeJK(raw);
        return jk === 'L' ? 'Laki-laki' : (jk === 'P' ? 'Perempuan' : '-');
    }

    function buildCard(d) {
        var adaTtd = tandatangan && tandatangan !== base_url;
        var ttdStyle = adaTtd ? ' style="background-image:url(' + tandatangan + ')"' : '';
        return '<div class="kp-card">' +
            '<div class="kp-strip"></div>' +
            '<table class="kp-head"><tr>' +
            '<td class="kp-logo"><img src="' + logoKiri + '"></td>' +
            '<td class="kp-title">' +
            '<div class="h1">' + oldVal1 + '</div>' +
            '<div class="h2">' + oldVal2 + '</div>' +
            '<div class="h3">' + oldVal3 + '</div>' +
            '<div class="h4">' + oldVal4 + '</div>' +
            '</td>' +
            '<td class="kp-logo"><img src="' + logoKanan + '"></td>' +
            '</tr></table>' +
            '<div class="kp-body">' +
            '<div class="kp-foto kp-upload" data-id="' + d.id_siswa + '" title="Klik untuk ganti foto" style="cursor: pointer;">' +
            '<img class="avatar" src="' + base_url + d.foto + '" data-jk="' + (d.jenis_kelamin || '') + '">' +
            '<span class="kp-cam"><i class="fas fa-camera"></i></span></div>' +
            '<div class="kp-main">' +
            '<table class="kp-data">' +
            '<tr><td>Nama</td><td>:</td><td><b>' + d.nama + '</b></td></tr>' +
            '<tr><td>Kelas</td><td>:</td><td>' + d.nama_kelas + '</td></tr>' +
            '<tr><td>Jenis Kelamin</td><td>:</td><td>' + labelJK(d.jenis_kelamin) + '</td></tr>' +
            '<tr><td>Username</td><td>:</td><td><b>' + d.username + '</b></td></tr>' +
            '<tr><td>Password</td><td>:</td><td><b>' + d.password + '</b></td></tr>' +
            '</table>' +
            '<div class="kp-ttd"' + ttdStyle + '>' +
            '<span>' + oldKota + '</span><span>' + oldTgl + '</span><br>' +
            'Kepala ' + satuanPend +
            '<span class="sp"></span>' +
            '<span class="nm">' + kepsek + '</span><br>' +
            'NIP: ' + nipKepsek +
            '</div>' +
            '</div>' +
            '</div>' +
            '<div class="kp-foot">Kartu ini wajib dibawa selama ujian berlangsung</div>' +
            '</div>';
    }

    function createPrintPreview(data) {
        var perPage = 8;
        var pages = Math.ceil(data.length / perPage);
        var konten = '<style>' + kpCss + '</style>';

        for (var a = 0; a < pages; a++) {
            var cards = data.slice(a * perPage, (a + 1) * perPage);
            var table = '<table>';
            for (var j = 0; j < cards.length; j++) {
                if (j % 2 === 0) table += '<tr>';
                table += '<td style="padding: 5px; vertical-align: top;">' + buildCard(cards[j]) + '</td>';
                if (j % 2 === 1 || j === cards.length - 1) table += '</tr>';
            }
            table += '</table>';

            konten += '<div class="border my-shadow mb-3 pt-3 bg-white"><div class="pt-4" ' +
                'style="display: flex; justify-content: center; align-items: flex-start; background: white; width: 210mm; height: 297mm; padding: 1mm">' +
                table + '</div></div>';
            if (a < pages - 1) {
                konten += '<div style="page-break-after: always"></div>';
            }
        }

        $("#print-preview").html(konten);

        // Foto gagal dimuat: coba folder foto_siswa sekali, lalu pakai avatar sesuai jenis kelamin
        $('.avatar').on('error', function () {
            var $img = $(this);
            var tries = $img.data('tries') || 0;
            $img.data('tries', tries + 1);
            var src = $img.attr('src') || '';

            if (tries === 0 && src.indexOf('profiles') !== -1) {
                $img.attr('src', src.replace('profiles', 'foto_siswa'));
                return;
            }

            var jk = normalizeJK($img.data('jk'));
            var fallback = jk === 'L' ? 'siswa-l.png' : (jk === 'P' ? 'siswa-p.png' : 'siswa.png');
            $img.off('error').attr('src', base_url + 'assets/img/' + fallback);
        });
    }

    $(document).ready(function () {
        $("#header-1").on("change keyup paste", function () {
            var currentVal = $(this).val();
            if (currentVal === oldVal1) {
                return;
            }

            oldVal1 = currentVal;
            $('#prev-header-1').html(currentVal);
        });
        $("#header-2").on("change keyup paste", function () {
            var currentVal = $(this).val();
            if (currentVal === oldVal2) {
                return;
            }

            oldVal2 = currentVal;
            $('#prev-header-2').text(currentVal);
        });
        $("#header-3").on("change keyup paste", function () {
            var currentVal = $(this).val();
            if (currentVal === oldVal3) {
                return;
            }

            oldVal3 = currentVal;
            $('#prev-header-3').text(currentVal);
        });
        $("#header-4").on("change keyup paste", function () {
            var currentVal = $(this).val();
            if (currentVal === oldVal4) {
                return;
            }

            oldVal4 = currentVal;
            $('#prev-header-4').text(currentVal);
        });

        $('#input-tanggal').on('input', function (e) {
            var tgl = $(this).val();
            $('#prev-tanggal').text(tgl);
            oldTgl = tgl;
        });

        $('#set-kartu').on('submit', function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();

            swal.fire({
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
            let form = new FormData($('#set-kartu')[0]);
            $.ajax({
                url: base_url + 'cbtcetak/savekartu',
                type: 'POST',
                processData: false,
                contentType: false,
                data: form,
                success: function (response) {
                    console.log(response);
                    swal.fire({
                        title: 'Sukses',
                        text: "Header kartu berhasil disimpan",
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonColor: "#3085d6",
                    }).then(result => {
                        if (result.value) {
                            window.location.href = base_url + 'cbtcetak/kartupeserta';
                        }
                    });
                },
                error: function (xhr, error, status) {
                    console.log(xhr.responseText);
                    const err = JSON.parse(xhr.responseText)
                    swal.fire({
                        title: "Error",
                        text: err.Message,
                        icon: "error"
                    });
                }
            });
        });

        function uploadAttach(action, data) {
            $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: action,
                data: data,
                processData: false,
                contentType: false,
                cache: false,
                timeout: 600000,
                success: function (data) {
                    if (data.src.includes('kanan')) {
                        logoKanan = data.src;
                    } else if (data.src.includes('kiri')) {
                        logoKiri = data.src;
                    } else if (data.src.includes('tanda')) {
                        tandatangan = data.src;
                    }
                },
                error: function (xhr, error, status) {
                    console.log("error", xhr.responseText);
                    const err = JSON.parse(xhr.responseText)
                    swal.fire({
                        title: "Error",
                        text: err.Message,
                        icon: "error"
                    });
                }
            });
        }

        function deleteImage(src) {
            $.ajax({
                data: {src: src},
                type: "POST",
                url: base_url + "cbtcetak/deletefile",
                cache: false,
                success: function (response) {
                    console.log(response);
                }
            });
        }

        function loadSiswaKelas(kelas) {
            $.ajax({
                type: "GET",
                url: base_url + "cbtcetak/getsiswakelas?kelas=" + kelas,
                success: function (response) {
                    createPrintPreview(response.siswa);
                }
            });
        }

        function loadSiswaRuang(ruang) {
            $.ajax({
                type: "GET",
                url: base_url + "cbtcetak/getsiswaruang?ruang=" + ruang,
                success: function (response) {
                    createPrintPreview(response.siswa);
                }
            });
        }

        $("#kelas").prepend("<option value='' selected='selected' disabled>Pilih Kelas</option>");
        $("#ruang").prepend("<option value='' selected='selected' disabled>Pilih Ruang</option>");
        $("#kelas").change(function () {
            loadSiswaKelas($(this).val());
        });

        $("#ruang").change(function () {
            loadSiswaRuang($(this).val());
        });

        $('#kelas').select2({theme: 'bootstrap4'});

        // ===== Ganti foto siswa: klik foto pada kartu =====
        var kpTarget = null;
        $(document).on('click', '.kp-upload', function () {
            kpTarget = $(this);
            $('#kp-file').val('').trigger('click');
        });

        $('#kp-file').on('change', function () {
            var file = this.files && this.files[0];
            if (!file || !kpTarget) return;

            if (!/^image\/(jpeg|png)$/.test(file.type)) {
                Swal.fire({title: 'Error', text: 'Format foto harus JPG atau PNG', icon: 'error'});
                return;
            }
            if (file.size > 2 * 1024 * 1024) {
                Swal.fire({title: 'Error', text: 'Ukuran foto maksimal 2 MB', icon: 'error'});
                return;
            }

            var $box = kpTarget;
            var fd = new FormData();
            fd.append('id_siswa', $box.data('id'));
            fd.append('foto', file);
            // ikutkan token CSRF (jika aktif) dari form Setting Kartu
            $('#set-kartu input[type=hidden]').each(function () {
                fd.append(this.name, this.value);
            });

            swal.fire({
                text: "Mengunggah foto....",
                allowOutsideClick: false,
                allowEscapeKey: false,
                onOpen: () => {
                    swal.showLoading();
                }
            });

            $.ajax({
                url: base_url + 'usersiswa/upload_foto',
                type: 'POST',
                data: fd,
                processData: false,
                contentType: false,
                success: function (res) {
                    if (typeof res === 'string') {
                        try { res = JSON.parse(res); } catch (e) { res = {status: false, msg: 'Respon server tidak dikenali'}; }
                    }
                    if (res.status) {
                        var $img = $box.find('img.avatar');
                        $img.data('tries', 0).attr('src', base_url + res.foto + '?t=' + Date.now());
                        Swal.fire({title: 'Sukses', text: res.msg, icon: 'success', timer: 1500, showConfirmButton: false});
                    } else {
                        Swal.fire({title: 'Gagal', text: res.msg, icon: 'error'});
                    }
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                    Swal.fire({title: 'Error', text: 'Gagal mengunggah foto (kode ' + xhr.status + ')', icon: 'error'});
                }
            });
        });

        $("#btn-print").click(function () {
            if ($('#kelas').val() === '') {
                Swal.fire({
                    title: "ERROR",
                    text: "Pilih kelas dulu",
                    icon: "error"
                })
            } else {
                $('#print-preview').print();
            }
        });

        $('#selector button').click(function () {
            $(this).addClass('active').siblings().addClass('btn-outline-primary').removeClass('active btn-primary');

            if (!$('#kelas').is(':hidden')) {
                $('#kelas').addClass('d-none');
                $('#ruang').removeClass('d-none');
                printBy = 2;
            } else {
                $('#kelas').removeClass('d-none');
                $('#ruang').addClass('d-none');
                printBy = 1;
            }
        });
    })

</script>