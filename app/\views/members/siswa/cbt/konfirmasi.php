<?php
/**
 * Konfirmasi Ujian (siswa) - tampilan sederhana untuk anak SD.
 * Logika (form, validasi token, redirect ke penilaian) tidak diubah.
 */
?>
<style>
    /* Pita hijau bermotif dihapus: rapikan jarak atas & ubah tulisan nama siswa jadi gelap */
    .content.overlap { margin-top: 0 !important; }
    .content-wrapper .info-box .text-white { color: #0f172a !important; }

    .kf-card { margin:50px 0 40px; background:#fff; border:1px solid #e2e8f0; border-radius:24px; overflow:hidden; }
    .kf-head { padding:24px 18px; text-align:center; color:#fff; background:#16a34a; }
    .kf-eyebrow { font-size:.9rem; font-weight:600; opacity:.9; }
    .kf-title { margin-top:4px; font-size:1.7rem; font-weight:800; line-height:1.2; }
    .kf-body { padding:20px 18px 22px; }

    .kf-tiles { display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:16px; }
    .kf-tile { text-align:center; padding:16px 8px; border-radius:18px; background:#f1f5f9; }
    .kf-tile-ico { font-size:1.6rem; }
    .kf-tile-val { font-size:1.9rem; font-weight:800; color:#0f172a; line-height:1.1; font-variant-numeric:tabular-nums; }
    .kf-tile-lbl { font-size:1rem; color:#475569; font-weight:600; }

    .kf-proctor { text-align:center; margin-bottom:16px; font-size:1rem; color:#475569; }
    .kf-proctor b { color:#0f172a; }

    .kf-token { margin-bottom:16px; padding:16px; border-radius:18px; background:#fef9c3; border:2px solid #facc15; text-align:center; }
    .kf-token-lbl { font-size:1.05rem; font-weight:800; color:#713f12; margin-bottom:10px; }
    .kf-token input#input-token {
        height:60px; border:2px solid #facc15 !important; border-radius:14px !important; background:#fff;
        font-size:1.6rem !important; font-weight:800 !important; letter-spacing:6px; text-transform:uppercase; text-align:center; color:#0f172a;
    }
    .kf-token input#input-token:focus { border-color:#16a34a !important; box-shadow:0 0 0 4px rgba(22,163,74,.2) !important; outline:none; }

    .kf-doa { margin-bottom:16px; padding:14px 16px; border-radius:18px; background:#e0f2fe; border:2px solid #7dd3fc; text-align:center; color:#075985; font-size:1.15rem; font-weight:800; line-height:1.4; }

    .kf-btn {
        width:100%; padding:18px; border:0; border-radius:18px; cursor:pointer;
        font-size:1.4rem; font-weight:800; color:#fff; background:#16a34a;
    }
    .kf-btn:active { transform:scale(.98); }
    .kf-btn[disabled] { opacity:.6; cursor:progress; }

    .kf-state { text-align:center; padding:10px 6px; }
    .kf-state-ico { font-size:3.5rem; line-height:1; margin-bottom:12px; }
    .kf-state h4 { font-size:1.4rem; font-weight:800; color:#0f172a; margin-bottom:8px; }
    .kf-state p { font-size:1.05rem; color:#475569; line-height:1.6; margin:0; }
    .kf-btn-ghost { margin-top:16px; padding:12px 22px; border-radius:999px; background:#fff; border:2px solid #cbd5e1; color:#334155; font-size:1rem; font-weight:700; cursor:pointer; }

    @media (max-width:576px) {
        .content-wrapper { padding-left:10px !important; padding-right:10px !important; }
        .kf-card { margin-top:30px; }
        .kf-title { font-size:1.45rem; }
    }

/* ===== Premium ramah anak SD: warna ceria, teks jelas, tombol mudah disentuh ===== */
:root { --kid-ink:#17324d; --kid-green:#159b62; --kid-blue:#2878e8; --kid-yellow:#ffcf45; }
html { font-size:16px; }
body { color:var(--kid-ink); }
.kf-card {
    margin:32px 0 36px; border:2px solid #e5efff; border-radius:28px;
    box-shadow:0 16px 38px rgba(38,84,140,.12); background:#fff;
}
.kf-head {
    position:relative; overflow:hidden; padding:28px 20px 26px;
    background:linear-gradient(135deg,#168f60 0%,#20b879 58%,#52cf9a 100%);
}
.kf-head:before,.kf-head:after {
    content:""; position:absolute; border-radius:50%; background:rgba(255,255,255,.13);
    pointer-events:none;
}
.kf-head:before { width:150px;height:150px;right:-35px;top:-60px; }
.kf-head:after { width:85px;height:85px;left:-25px;bottom:-38px; }
.kf-eyebrow { position:relative; z-index:1; font-size:1rem; letter-spacing:.04em; }
.kf-title { position:relative; z-index:1; margin-top:8px; font-size:clamp(1.65rem,4vw,2.1rem); text-shadow:0 2px 0 rgba(0,0,0,.08); }
.kf-body { padding:24px; }
.kf-tiles { gap:14px; margin-bottom:20px; }
.kf-tile { padding:18px 10px; border:2px solid #eaf2ff; border-radius:22px; background:linear-gradient(180deg,#f7fbff,#edf6ff); }
.kf-tile:nth-child(2) { background:linear-gradient(180deg,#fffaf0,#fff3cc); border-color:#ffe6a1; }
.kf-tile-ico { font-size:2rem; margin-bottom:6px; }
.kf-tile-val { font-size:2.1rem; color:#163b68; }
.kf-tile-lbl { color:#405c78; font-size:1.05rem; }
.kf-proctor { padding:14px 16px; border-radius:16px; background:#f1f7ff; color:#294d72; line-height:1.5; }
.kf-token { padding:20px; border-radius:22px; background:#fff9df; border:2px solid #ffd65b; box-shadow:0 5px 0 rgba(233,177,24,.12); }
.kf-token-lbl { font-size:1.15rem; color:#684b00; }
.kf-token input#input-token {
    height:66px; border:3px solid #ffc928 !important; border-radius:18px !important;
    font-size:1.8rem !important; letter-spacing:8px; box-shadow:inset 0 2px 5px rgba(20,40,70,.05);
}
.kf-token input#input-token:focus { border-color:#159b62 !important; box-shadow:0 0 0 5px rgba(21,155,98,.15) !important; }
.kf-doa { border:2px solid #b5e4ff; background:#eaf7ff; color:#13537c; border-radius:20px; padding:17px; }
.kf-btn {
    min-height:64px; padding:18px 22px; border-radius:20px;
    background:linear-gradient(180deg,#20b879,#118953); box-shadow:0 6px 0 #0b7042,0 10px 18px rgba(17,137,83,.18);
    transition:transform .15s ease,box-shadow .15s ease; letter-spacing:.01em;
}
.kf-btn:hover { filter:brightness(1.04); color:#fff; }
.kf-btn:active { transform:translateY(3px); box-shadow:0 3px 0 #0b7042; }
.kf-btn-ghost { min-height:48px; border-color:#c8d9ec; color:#294d72; }
.kf-state { padding:18px 8px; }
.kf-state-ico { font-size:4rem; }
.kf-state h4 { font-size:1.55rem; }
.kf-state p { font-size:1.1rem; }
@media (max-width:576px) {
    .kf-card { margin-top:22px; border-radius:24px; }
    .kf-body { padding:17px; }
    .kf-tiles { gap:10px; }
    .kf-tile { padding:15px 6px; }
    .kf-tile-val { font-size:1.8rem; }
    .kf-token { padding:15px; }
    .kf-token input#input-token { letter-spacing:5px; }
}
@media (prefers-reduced-motion: reduce) { .kf-btn { transition:none; } }

</style>

<div class="content-wrapper" style="margin-top:-1px;">
    <section class="content overlap pt-4 pb-4 px-2 px-sm-4">
        <div class="container">
            <div class="row">
                <div class="col-12 mb-2">
                    <?php $this->load->view('members/siswa/templates/top'); ?>
                </div>
            </div>

            <div class="container-fluid h-100">
                <div class="row h-100 justify-content-center">
                    <div class="col-md-8 col-lg-6">
                        <div class="kf-card">

                            <?php if ($support && $valid) : ?>
                                <?php
                                $jumlahSoal = $bank->tampil_pg + $bank->tampil_kompleks + $bank->tampil_jodohkan + $bank->tampil_isian + $bank->tampil_esai;
                                ?>

                                <div class="kf-head">
                                    <div class="kf-eyebrow"><?= html_escape($bank->kode_jenis) ?></div>
                                    <div class="kf-title"><?= html_escape($bank->nama_mapel) ?></div>
                                </div>

                                <div class="kf-body">
                                    <?= form_open('', array('id' => 'konfir')) ?>
                                    <input type="hidden" name="siswa" value="<?= $siswa->id_siswa ?>">
                                    <input type="hidden" name="jadwal" value="<?= $bank->id_jadwal ?>">
                                    <input type="hidden" name="bank" value="<?= $bank->id_bank ?>">

                                    <div class="kf-tiles">
                                        <div class="kf-tile">
                                            <div class="kf-tile-ico">⏰</div>
                                            <div class="kf-tile-val"><?= (int)$bank->durasi_ujian ?></div>
                                            <div class="kf-tile-lbl">menit</div>
                                        </div>
                                        <div class="kf-tile">
                                            <div class="kf-tile-ico">📝</div>
                                            <div class="kf-tile-val"><?= (int)$jumlahSoal ?></div>
                                            <div class="kf-tile-lbl">soal</div>
                                        </div>
                                    </div>

                                    <?php if (!empty($pengawas)) : ?>
                                        <div class="kf-proctor">
                                            Pengawas:
                                            <b><?= html_escape(implode(', ', array_map(function ($p) { return $p->nama_guru; }, $pengawas))) ?></b>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($bank->token === '1') : ?>
                                        <div class="kf-token">
                                            <div class="kf-token-lbl">🔑 Ketik token dari Bapak/Ibu guru</div>
                                            <input type="text" id="input-token" class="form-control" name="token" placeholder="TOKEN" autocomplete="off"/>
                                        </div>
                                    <?php endif; ?>

                                    <div class="kf-doa">🤲 Berdoalah sebelum mengerjakan</div>

                                    <button id="load-soal" type="submit" class="kf-btn">▶ MULAI UJIAN</button>
                                    <?= form_close(); ?>
                                </div>

                            <?php elseif (!$valid) : ?>
                                <div class="kf-body pt-4">
                                    <div class="kf-state">
                                        <div class="kf-state-ico">🔒</div>
                                        <h4>Ujian dikunci</h4>
                                        <p>Panggil Bapak/Ibu guru<br>agar ujian dibuka.</p>
                                        <button onclick="window.location.reload();" class="kf-btn-ghost">🔄 Coba lagi</button>
                                    </div>
                                </div>

                            <?php elseif (!$support) : ?>
                                <div class="kf-body pt-4">
                                    <div class="kf-state">
                                        <div class="kf-state-ico">⚠️</div>
                                        <h4>Aplikasi tidak cocok</h4>
                                        <p>Gunakan <b>Google Chrome</b> atau <b>Mozilla Firefox</b>,<br>atau panggil Bapak/Ibu guru.</p>
                                    </div>
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="<?= base_url() ?>/assets/app/js/redirect.js"></script>
<script>
    $('#konfir').submit(function (e) {
        e.stopPropagation();
        e.preventDefault();

        var jadwal = $(this).find('input[name="jadwal"]').val();
        var $btn = $('#load-soal');
        $btn.prop('disabled', true);

        $.ajax({
            type: 'POST',
            url: base_url + 'siswa/validasisiswa',
            data: $(this).serialize(),
            success: function (data) {
                if (data.token === true) {
                    if (data.support === false) {
                        swal.fire({
                            "title": "Oops!",
                            "html": "Aplikasi ini tidak cocok.<br>Gunakan Chrome atau Firefox.<br><small>Kode 005</small>",
                            "icon": "error"
                        });
                    } else {
                        if (data.izinkan === true) {
                            if (data.ada_waktu === true) {
                                if (data.jml_soal > 0) {
                                    window.location.href = base_url + 'siswa/penilaian/' + jadwal;
                                } else {
                                    swal.fire({
                                        "title": "Oops!",
                                        "html": "Soal belum ada.<br>Panggil Bapak/Ibu guru.<br><small>Kode 004</small>",
                                        "icon": "error"
                                    });
                                }
                            } else {
                                swal.fire({
                                    "title": "Oops!",
                                    "html": data.warn.msg + "<br>Panggil Bapak/Ibu guru.<br><small>Kode 003</small>",
                                    "icon": "error"
                                });
                            }
                        } else {
                            swal.fire({
                                "title": "Oops!",
                                "html": "Kamu sedang ujian di perangkat lain.<br>Panggil Bapak/Ibu guru.<br><small>Kode 002</small>",
                                "icon": "error"
                            });
                        }
                    }
                } else {
                    swal.fire({
                        "title": "Token salah",
                        "html": "Coba ketik lagi dengan teliti.<br>Atau tanya Bapak/Ibu guru.<br><small>Kode 001</small>",
                        "icon": "error"
                    });
                }
            },
            error: function (xhr, error, status) {
                swal.fire({
                    "title": "Oops!",
                    "html": "Kembali ke beranda, lalu coba lagi.<br><small>Kode 006</small>",
                    "icon": "error"
                });
                console.log(xhr.responseText);
            },
            complete: function () {
                $btn.prop('disabled', false);
            }
        });
    });
</script>


<!-- Premium UI refresh: visual styling only; form fields, validation and redirects are preserved. -->
<style>
:root {
  --kf-ink:#172033; --kf-muted:#64748b; --kf-green:#087f5b; --kf-green2:#10b981;
  --kf-line:#e2e8f0; --kf-surface:#ffffff; --kf-soft:#f3f8f6;
}
.content-wrapper { background:radial-gradient(ellipse at 10% 0%,#e1f5ec 0,transparent 38%),linear-gradient(180deg,#f7faf9 0%,#eef4f7 100%) !important; }
.kf-card {
  margin:34px 0 40px; border:1px solid rgba(148,163,184,.24); border-radius:28px;
  background:var(--kf-surface); overflow:hidden;
  box-shadow:0 24px 70px rgba(15,35,55,.12),0 3px 12px rgba(15,35,55,.04);
  transition:box-shadow .25s ease,transform .25s ease;
}
.kf-head {
  position:relative; isolation:isolate; overflow:hidden; padding:30px 22px 28px;
  color:#fff; text-align:center;
  background:linear-gradient(135deg,#064e3b 0%,#087f5b 52%,#10b981 100%);
}
.kf-head:before,.kf-head:after { content:"";position:absolute;z-index:-1;border-radius:50%;background:rgba(255,255,255,.08); }
.kf-head:before { width:210px;height:210px;top:-125px;right:-45px; }
.kf-head:after { width:150px;height:150px;bottom:-105px;left:-35px; }
.kf-eyebrow { display:inline-block;padding:6px 12px;border:1px solid rgba(255,255,255,.26);border-radius:999px;background:rgba(255,255,255,.12);font-size:.78rem;letter-spacing:.09em;text-transform:uppercase;font-weight:800; }
.kf-title { margin-top:12px;font-size:clamp(1.55rem,3vw,2rem);font-weight:850;letter-spacing:-.035em;line-height:1.2;text-shadow:0 2px 12px rgba(0,0,0,.12); }
.kf-body { padding:24px; }
.kf-tiles { gap:14px;margin-bottom:20px; }
.kf-tile { padding:19px 12px;border:1px solid #e5eee9;border-radius:20px;background:linear-gradient(145deg,#f8fcfa,#eff8f3);box-shadow:inset 0 1px 0 #fff; }
.kf-tile-ico { display:inline-flex;align-items:center;justify-content:center;width:42px;height:42px;margin-bottom:8px;border-radius:14px;background:#d9f5e7;font-size:1.35rem; }
.kf-tile-val { color:#075c43;font-size:2rem;font-weight:850;letter-spacing:-.04em; }
.kf-tile-lbl { color:#647568;font-size:.88rem;font-weight:700;letter-spacing:.02em; }
.kf-proctor { padding:12px 14px;border:1px solid #e6edf2;border-radius:15px;background:#f8fafc;font-size:.93rem; }
.kf-proctor b { color:var(--kf-ink); }
.kf-token { padding:18px;border:1px solid #f5d66a;border-radius:20px;background:linear-gradient(135deg,#fffbea,#fff7d6);box-shadow:0 5px 16px rgba(180,130,0,.06); }
.kf-token-lbl { color:#854d0e;font-size:.95rem; }
.kf-token input#input-token { height:58px;border:1.5px solid #e9c64b !important;border-radius:15px !important;font-size:1.45rem !important;letter-spacing:.32em;box-shadow:0 2px 5px rgba(0,0,0,.03); }
.kf-token input#input-token:focus { border-color:#0d9468 !important;box-shadow:0 0 0 4px rgba(16,185,129,.15) !important; }
.kf-doa { border:1px solid #c5e7fa;background:linear-gradient(135deg,#eff9ff,#e4f5ff);color:#075985;border-radius:17px;font-size:1rem;font-weight:750;padding:15px; }
.kf-btn { padding:17px 20px;border-radius:17px;background:linear-gradient(135deg,#087f5b,#10a875);box-shadow:0 9px 20px rgba(8,127,91,.23);font-size:1.08rem;letter-spacing:.025em;transition:transform .18s ease,box-shadow .18s ease,filter .18s ease; }
.kf-btn:hover { filter:brightness(1.04);box-shadow:0 12px 26px rgba(8,127,91,.28);transform:translateY(-1px); }
.kf-btn:active { transform:scale(.99); }
.kf-btn[disabled] { box-shadow:none; }
.kf-state { padding:24px 12px 18px; }
.kf-state-ico { display:flex;align-items:center;justify-content:center;width:82px;height:82px;margin:0 auto 18px;border-radius:26px;background:#f1f5f9;font-size:2.7rem; }
.kf-state h4 { font-size:1.5rem;letter-spacing:-.025em; }
.kf-state p { max-width:330px;margin:0 auto;color:#64748b; }
.kf-btn-ghost { border:1px solid #d5dee8;box-shadow:0 3px 10px rgba(15,23,42,.04);transition:background .2s ease; }
.kf-btn-ghost:hover { background:#f8fafc; }
@media(max-width:576px) {
 .kf-card { margin-top:20px;border-radius:23px; }
 .kf-head { padding:25px 18px 24px; }
 .kf-body { padding:18px 16px 20px; }
 .kf-tile { padding:15px 8px;border-radius:17px; }
 .kf-tile-val { font-size:1.8rem; }
 .kf-btn { padding:16px 12px;font-size:1.02rem; }
}
@media(prefers-reduced-motion:reduce) { .kf-card,.kf-btn,.kf-btn-ghost { transition:none; } }
</style>

