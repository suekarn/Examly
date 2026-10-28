<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Buang akhiran "Build/XXXX" dari nama perangkat Android agar rapi
$bersih = function ($t) {
    return preg_replace('/\s+Build\/.*$/i', '', (string) $t);
};
?>

<div class="content-wrapper bg-white">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?= $judul ?></h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            <div id="sig" class="d-none" data-sig="<?= html_escape($sig) ?>"></div>

            <!-- RINGKASAN -->
            <div class="row">
                <div class="col-md-4 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="fas fa-list"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Percobaan</span>
                            <span class="info-box-number" id="sum-total"><?= (int)$total ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-success"><i class="fas fa-check"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Berhasil</span>
                            <span class="info-box-number" id="sum-berhasil"><?= (int)$berhasil ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-danger"><i class="fas fa-times"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Gagal</span>
                            <span class="info-box-number" id="sum-gagal"><?= (int)$gagal ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PERANGKAT BERMASALAH -->
            <div class="card card-danger my-shadow mb-4">
                <div class="card-header">
                    <h6 class="card-title">Perangkat Bermasalah (gagal login dan belum pernah berhasil)</h6>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-bordered table-striped mb-0">
                        <thead>
                        <tr>
                            <th>Username</th>
                            <th>IP</th>
                            <th>MAC</th>
                            <th>Perangkat</th>
                            <th>Browser</th>
                            <th class="text-center">Gagal</th>
                            <th>Terakhir</th>
                            <th>Pesan</th>
                        </tr>
                        </thead>
                        <tbody id="tbody-bermasalah">
                        <?php foreach ($bermasalah as $r): ?>
                            <tr>
                                <td><?= html_escape($r->identity) ?></td>
                                <td><?= html_escape($r->ip_address) ?></td>
                                <td><?= html_escape($r->mac_address) ?></td>
                                <td><?= html_escape($bersih($r->perangkat)) ?></td>
                                <td><?= html_escape($r->browser) ?></td>
                                <td class="text-center"><?= (int)$r->jumlah_gagal ?></td>
                                <td><?= html_escape($r->terakhir) ?></td>
                                <td><?= html_escape(strip_tags((string)$r->pesan)) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($bermasalah)): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted">Tidak ada data</td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- SEMUA PERCOBAAN LOGIN -->
            <div class="card card-default my-shadow mb-4">
                <div class="card-header">
                    <h6 class="card-title">Percobaan Login <small class="text-muted">(200 terakhir, dihapus otomatis setelah 24 jam)</small></h6>
                    <div class="card-tools d-flex align-items-center">
                        <small id="info-update" class="text-muted mr-2"></small>
                        <label class="mb-0 mr-2" style="font-weight: normal; cursor: pointer;">
                            <input type="checkbox" id="auto-refresh" checked> Auto
                        </label>
                        <input type="text" id="cari-log" class="form-control form-control-sm mr-2"
                               style="width: 180px" placeholder="Cari nama / IP / MAC">
                        <select id="filter-status" class="form-control form-control-sm mr-2" style="width: 110px">
                            <option value="">Semua</option>
                            <option value="berhasil">Berhasil</option>
                            <option value="gagal">Gagal</option>
                        </select>
                        <button type="button" id="btn-refresh" class="btn btn-success btn-sm mr-2">
                            <i class="fa fa-sync mr-1"></i> Refresh
                        </button>
                        <form action="<?= base_url('loglogin/hapus') ?>" method="post" class="d-inline"
                              onsubmit="return confirm('Hapus SEMUA log login? Tindakan ini tidak bisa dibatalkan.');">
                            <input type="hidden"
                                   name="<?= $this->security->get_csrf_token_name() ?>"
                                   value="<?= $this->security->get_csrf_hash() ?>">
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fa fa-trash mr-1"></i> Hapus Log
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-bordered table-striped mb-0">
                        <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Username</th>
                            <th>Status</th>
                            <th>IP</th>
                            <th>MAC</th>
                            <th>Perangkat</th>
                            <th>Browser</th>
                            <th>Keterangan</th>
                        </tr>
                        </thead>
                        <tbody id="tbody-log">
                        <?php foreach ($logs as $r): ?>
                            <tr data-status="<?= html_escape($r->status) ?>">
                                <td><?= html_escape($r->waktu) ?></td>
                                <td><?= html_escape($r->identity) ?></td>
                                <td>
                                    <?php if ($r->status == 'berhasil'): ?>
                                        <span class="badge badge-success">berhasil</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">gagal</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= html_escape($r->ip_address) ?></td>
                                <td><?= html_escape($r->mac_address) ?></td>
                                <td><?= html_escape($bersih($r->perangkat)) ?></td>
                                <td><?= html_escape($r->browser) ?></td>
                                <td><?= html_escape(strip_tags((string)$r->keterangan)) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($logs)): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted">
                                    Belum ada data. Log akan terisi saat siswa mencoba login.
                                </td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </section>
</div>

<script>
    (function () {
        var cari   = document.getElementById('cari-log');
        var filter = document.getElementById('filter-status');
        var auto   = document.getElementById('auto-refresh');
        var info   = document.getElementById('info-update');
        var sigEl  = document.getElementById('sig');
        var urlCek = '<?= base_url('loglogin/cek') ?>';
        var urlHal = '<?= base_url('loglogin') ?>';
        var sibuk  = false;

        // Pencarian / filter di tabel (dihitung ulang tiap dipanggil karena isi tabel bisa berganti)
        function terapkan() {
            var q  = cari.value.toLowerCase();
            var st = filter.value;
            var rows = document.querySelectorAll('#tbody-log tr[data-status]');
            for (var i = 0; i < rows.length; i++) {
                var tr = rows[i];
                var ok = tr.textContent.toLowerCase().indexOf(q) !== -1 &&
                         (st === '' || tr.getAttribute('data-status') === st);
                tr.style.display = ok ? '' : 'none';
            }
        }

        // Ambil halaman terbaru, lalu ganti hanya angka ringkasan dan isi tabel
        function muat() {
            return fetch(urlHal, {credentials: 'same-origin', cache: 'no-store'})
                .then(function (r) { return r.text(); })
                .then(function (html) {
                    var doc  = new DOMParser().parseFromString(html, 'text/html');
                    var baru = doc.getElementById('sig');
                    if (!baru) { return; } // sesi habis / halaman error: abaikan

                    sigEl.setAttribute('data-sig', baru.getAttribute('data-sig'));

                    ['sum-total', 'sum-berhasil', 'sum-gagal', 'tbody-bermasalah', 'tbody-log']
                        .forEach(function (id) {
                            var a = document.getElementById(id);
                            var b = doc.getElementById(id);
                            if (a && b) { a.innerHTML = b.innerHTML; }
                        });

                    terapkan();
                    info.textContent = 'Diperbarui ' + new Date().toLocaleTimeString('id-ID');
                });
        }

        // Cek ringan: hanya memuat ulang tabel jika ada perubahan
        function cek() {
            if (sibuk || document.hidden || !auto.checked) { return; }
            sibuk = true;

            fetch(urlCek, {credentials: 'same-origin', cache: 'no-store'})
                .then(function (r) { return r.json(); })
                .then(function (d) {
                    if (d && d.sig && d.sig !== sigEl.getAttribute('data-sig')) {
                        // tunggu sebentar agar beberapa login beruntun cukup 1x muat ulang
                        return new Promise(function (res) { setTimeout(res, 1500); }).then(muat);
                    }
                })
                .catch(function () {})
                .then(function () { sibuk = false; });
        }

        setInterval(cek, 5000);

        document.addEventListener('visibilitychange', function () {
            if (!document.hidden) { cek(); }
        });

        cari.addEventListener('input', terapkan);
        filter.addEventListener('change', terapkan);
        document.getElementById('btn-refresh').addEventListener('click', function () {
            muat().catch(function () {});
        });

        info.textContent = 'Diperbarui ' + new Date().toLocaleTimeString('id-ID');
    })();
</script>