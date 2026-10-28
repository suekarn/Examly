<style>
    /* Pita hijau bermotif dihapus: rapikan jarak atas & ubah tulisan nama siswa jadi gelap */
    .content.overlap { margin-top: 0 !important; }
    .content-wrapper .info-box .text-white { color: #0f172a !important; }

    /* Kotak ujian berada di tengah layar (antara nama siswa dan bar bawah) */
    .sd-center { display:flex; flex-direction:column; justify-content:center; min-height:calc(100vh - 260px); }

    /* ===== Tampilan sederhana untuk siswa SD ===== */
    .sd-card { background:#fff; border:1px solid #e2e8f0; border-radius:22px; margin-bottom:1.25rem; overflow:hidden; }
    .sd-card-head { padding:16px 18px 6px; font-size:1.15rem; font-weight:800; color:#0f172a; }
    .sd-card-body { padding:12px 14px 16px; }

    /* Menu: ikon besar, 3 per baris di HP */
    .menu-item-wrapper { display:block; text-decoration:none !important; margin-bottom:.5rem; }
    .menu-icon-container { display:inline-flex; align-items:center; justify-content:center; width:78px; height:78px; background:#f1f5f9; border-radius:22px; }
    .menu-icon-container img { width:54px; height:54px; object-fit:contain; }
    .menu-item-wrapper:active .menu-icon-container { background:#dbeafe; }
    .menu-title { margin-top:.5rem; font-size:.95rem; font-weight:700; color:#1e293b; line-height:1.25; }

    /* Banner ujian sedang berlangsung */
    .sd-go { display:block; text-align:center; text-decoration:none !important; background:#16a34a; color:#fff !important; border-radius:24px; padding:22px 18px; margin-bottom:1.25rem; }
    .sd-go .t1 { font-size:1rem; font-weight:700; opacity:.95; }
    .sd-go .t2 { font-size:1.7rem; font-weight:800; line-height:1.2; margin:6px 0 14px; }
    .sd-go .t3 { font-size:.95rem; }
    .sd-go .clock { font-size:2.6rem; font-weight:800; line-height:1.1; font-variant-numeric:tabular-nums; }
    .sd-btn { display:block; margin-top:16px; padding:16px; border-radius:18px; background:#fff; color:#15803d; font-size:1.35rem; font-weight:800; }

    /* Banner ujian belum mulai */
    .sd-wait { text-align:center; background:#fef9c3; border:2px solid #facc15; color:#713f12; border-radius:24px; padding:20px 16px; margin-bottom:1.25rem; }
    .sd-wait .t1 { font-size:1rem; font-weight:700; }
    .sd-wait .t2 { font-size:1.5rem; font-weight:800; margin:4px 0 8px; line-height:1.2; }
    .sd-wait .t3 { font-size:1rem; }

    /* Daftar jadwal berikutnya */
    .sd-item { display:flex; align-items:center; gap:12px; padding:14px; margin-bottom:10px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:16px; }
    .sd-item-ico { flex:0 0 44px; height:44px; border-radius:14px; background:#e2e8f0; color:#475569; display:flex; align-items:center; justify-content:center; font-size:1.1rem; }
    .sd-item-title { font-size:1.05rem; font-weight:800; color:#0f172a; line-height:1.25; }
    .sd-item-meta { font-size:.9rem; color:#475569; margin-top:2px; }
    .sd-item-meta b { color:#b45309; }
    .sd-empty { text-align:center; padding:16px; color:#64748b; font-size:1rem; font-weight:600; }
    .sd-sub { font-size:.9rem; font-weight:800; color:#64748b; margin-bottom:8px; }

    /* Timeline (komentar) tetap sederhana */
    .social-post { border:1px solid #e2e8f0; border-radius:18px; padding:1rem; margin-bottom:1rem; background:#fff; }
    .comment-bubble { background:#f8fafc; border:1px solid #f1f5f9; border-radius:18px; padding:10px 14px; }
    .reply-bubble { background:#f1f5f9; border-radius:14px; padding:8px 12px; }
    .rounded-circle-custom { border-radius:50%; object-fit:cover; }
    .btn-modern-muted { background:#f1f5f9; color:#475569 !important; border:none; border-radius:12px; font-weight:600; }
    .btn-action-post { background:#f8fafc; border:1px solid #e2e8f0; color:#475569 !important; font-weight:600; border-radius:12px; }

    @media (max-width:576px) {
        .menu-icon-container { width:70px; height:70px; }
        .menu-icon-container img { width:48px; height:48px; }
        .sd-go .t2 { font-size:1.5rem; }
        .sd-go .clock { font-size:2.2rem; }
    }
</style>

<div class="content-wrapper" style="margin-top:-1px; background-color:#f8fafc;">
    <section class="content overlap p-2 p-md-4">
        <div class="container">
            <?php $this->load->view('members/siswa/templates/top'); ?>
            <?php
            // ===== JADWAL ULANGAN & UJIAN (otomatis sesuai kelas siswa) =====
            $CI =& get_instance();
            $hariIni = date('Y-m-d');
            $jadwal_ujian = [];

            $CI->db->select('a.*, b.kode_jenis, b.nama_jenis, c.bank_kode, c.bank_kelas, c.soal_agama, d.nama_mapel');
            $CI->db->from('cbt_jadwal a');
            $CI->db->join('cbt_jenis b', 'b.id_jenis = a.id_jenis', 'left');
            $CI->db->join('cbt_bank_soal c', 'c.id_bank = a.id_bank');
            $CI->db->join('master_mapel d', 'd.id_mapel = c.bank_mapel_id', 'left');
            $CI->db->where('a.id_tp', $tp_active->id_tp);
            $CI->db->where('a.id_smt', $smt_active->id_smt);
            $CI->db->where('c.status', '1');
            $CI->db->where('c.status_soal', '1');
            $CI->db->where('a.status', '1'); // hanya jadwal AKTIF (sama dengan yang dipakai login.php)
            $CI->db->where('DATE(a.tgl_selesai) >= ' . $CI->db->escape($hariIni), NULL, FALSE);
            $CI->db->order_by('a.tgl_mulai', 'ASC');
            $CI->db->order_by('a.jam_ke', 'ASC');
            $rows_ujian = $CI->db->get()->result();

            $agama_siswa = isset($siswa->agama) ? $siswa->agama : null;
            foreach ($rows_ujian as $r) {
                $kb = @unserialize($r->bank_kelas ?? '');
                $ids_kelas = [];
                if (is_array($kb)) {
                    foreach ($kb as $k) {
                        if (isset($k['kelas_id']) && $k['kelas_id'] !== '') $ids_kelas[] = (string)$k['kelas_id'];
                    }
                }
                if (!in_array((string)$siswa->id_kelas, $ids_kelas, true)) continue;
                if ($agama_siswa !== null && !in_array($r->soal_agama, ['-', '0', '', null], true) && $r->soal_agama != $agama_siswa) continue;
                $jadwal_ujian[] = $r;
            }

            // ===== Status berdasarkan JAM =====
            $now_ts = time();
            $next_change = null;
            foreach ($jadwal_ujian as $jj) {
                $d_m = date('Y-m-d', strtotime($jj->tgl_mulai));
                $d_s = date('Y-m-d', strtotime($jj->tgl_selesai));
                $jj->_selesai = false;
                $jj->_berlangsung = false;
                $jj->_tanpa_jam = empty($jj->jam_mulai) || empty($jj->jam_selesai);
                if ($hariIni >= $d_m && $hariIni <= $d_s && !$jj->_tanpa_jam) {
                    $ts_m = strtotime($hariIni . ' ' . $jj->jam_mulai);
                    $ts_s = strtotime($hariIni . ' ' . $jj->jam_selesai);
                    if ($now_ts >= $ts_s) {
                        $jj->_selesai = true;
                    } elseif ($now_ts >= $ts_m) {
                        $jj->_berlangsung = true;
                        $cand = $ts_s;
                    } else {
                        $cand = $ts_m;
                    }
                    if (isset($cand) && ($next_change === null || $cand < $next_change)) $next_change = $cand;
                    unset($cand);
                }
            }
            $jadwal_ujian = array_values(array_filter($jadwal_ujian, function ($x) { return !$x->_selesai; }));
            usort($jadwal_ujian, function ($a, $b) { return ((int)$b->_berlangsung) - ((int)$a->_berlangsung); });
            $jadwal_ujian = array_slice($jadwal_ujian, 0, 6);

            $nm_hari  = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
            $nm_bulan = [1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            $fmt_tgl = function ($tgl) use ($nm_hari, $nm_bulan) {
                $t = strtotime($tgl);
                return $nm_hari[(int)date('w', $t)] . ', ' . date('j', $t) . ' ' . $nm_bulan[(int)date('n', $t)];
            };
            $fmt_lagi = function ($detik) {
                $m = (int)ceil($detik / 60);
                return $m >= 60 ? floor($m / 60) . ' jam ' . ($m % 60) . ' menit' : $m . ' menit';
            };

            // ===== Pisahkan: yang sedang berlangsung, yang berikutnya hari ini, dan sisanya =====
            $banner_now = []; $banner_next = null; $banner_next_ts = null; $sisa_list = [];
            foreach ($jadwal_ujian as $bj) {
                $bm = date('Y-m-d', strtotime($bj->tgl_mulai));
                $bs = date('Y-m-d', strtotime($bj->tgl_selesai));
                $hari_ini_aktif = ($hariIni >= $bm && $hariIni <= $bs);
                if ($hari_ini_aktif && ($bj->_berlangsung || $bj->_tanpa_jam)) {
                    $banner_now[] = $bj;
                    continue;
                }
                if ($hari_ini_aktif) {
                    $tsm = strtotime($hariIni . ' ' . $bj->jam_mulai);
                    if ($tsm > $now_ts && ($banner_next_ts === null || $tsm < $banner_next_ts)) { $banner_next = $bj; $banner_next_ts = $tsm; }
                }
                $sisa_list[] = $bj;
            }
            // jadwal "berikutnya" sudah tampil di kotak kuning, jangan diulang di daftar
            if ($banner_next && empty($banner_now)) {
                $sisa_list = array_values(array_filter($sisa_list, function ($x) use ($banner_next) { return $x !== $banner_next; }));
            }
            ?>

            <div class="sd-center">
            <!-- ===== UJIAN SEDANG BERLANGSUNG ===== -->
            <?php foreach ($banner_now as $bj):
                $b_judul = !empty($bj->nama_mapel) ? $bj->nama_mapel : $bj->bank_kode;
                $b_jenis = !empty($bj->nama_jenis) ? $bj->nama_jenis : 'Ujian';
                $b_sisa  = $bj->_berlangsung ? max(0, strtotime($hariIni . ' ' . $bj->jam_selesai) - $now_ts) : 0;
                ?>
                <a href="<?= !empty($bj->id_jadwal) ? base_url('siswa/konfirmasi/' . $bj->id_jadwal) : base_url('siswa/cbt') ?>" class="sd-go">
                    <div class="t1">📝 <?= html_escape($b_jenis) ?> hari ini sudah dibuka</div>
                    <div class="t2"><?= html_escape($b_judul) ?></div>
                    <?php if ($bj->_berlangsung): ?>
                        <div class="t3">Sisa waktu</div>
                        <div class="clock ju-sisa" data-sisa="<?= $b_sisa ?>"></div>
                    <?php endif; ?>
                    <div class="sd-btn">▶ MULAI MENGERJAKAN</div>
                </a>
            <?php endforeach; ?>

            <!-- ===== UJIAN BELUM MULAI ===== -->
            <?php if (empty($banner_now) && $banner_next):
                $n_judul = !empty($banner_next->nama_mapel) ? $banner_next->nama_mapel : $banner_next->bank_kode;
                $n_jenis = !empty($banner_next->nama_jenis) ? $banner_next->nama_jenis : 'Ujian';
                ?>
                <div class="sd-wait">
                    <div class="t1">⏳ <?= html_escape($n_jenis) ?> belum dimulai</div>
                    <div class="t2"><?= html_escape($n_judul) ?></div>
                    <div class="t3">Mulai jam <b><?= substr($banner_next->jam_mulai, 0, 5) ?></b> (<?= $fmt_lagi($banner_next_ts - $now_ts) ?> lagi).<br>Tombol akan muncul sendiri.</div>
                </div>
            <?php endif; ?>

            <!-- ===== MENU (disembunyikan supaya anak SD tidak bingung; ubah ke true untuk menampilkan lagi) ===== -->
            <?php $tampil_menu = false; ?>
            <?php if ($tampil_menu): ?>
            <div class="sd-card">
                <div class="sd-card-head">🏠 Menu</div>
                <div class="sd-card-body">
                    <div class="row no-gutters text-center">
                        <?php foreach ($menu as $m): ?>
                            <div class="col-4 col-md-3 col-lg-2 mb-3">
                                <a href="<?= base_url($m->link) ?>" class="menu-item-wrapper">
                                    <div class="menu-icon-container">
                                        <img class="img-fluid" src="<?= base_url() ?>/assets/app/img/<?= $m->icon ?>" alt="Ikon"/>
                                    </div>
                                    <div class="menu-title px-1"><?= $m->title ?></div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php
            // Tidak ada ujian yang sedang berlangsung & tidak ada yang akan mulai hari ini
            $tidak_ada_aktif = empty($banner_now) && !$banner_next;
            ?>
            <?php
            // Atur bagian mana yang tampil (ubah ke true untuk menampilkan lagi)
            $tampil_pelajaran = false; // 📚 Pelajaran Hari Ini
            $tampil_info      = false; // 📢 Info dari Guru
            $auto_keluar       = true;  // true = siswa otomatis keluar ke halaman login bila tidak ada jadwal aktif
            $auto_keluar_detik = 2;     // jeda sebelum keluar (detik)
            ?>
            <div class="row">
                <?php if ($tampil_pelajaran): ?>
                <div class="col-12 col-md-5 order-1 order-md-2">
                    <!-- ===== JADWAL PELAJARAN HARI INI ===== -->
                    <div class="sd-card">
                        <div class="sd-card-head d-flex align-items-center justify-content-between">
                            <span>📚 Pelajaran Hari Ini</span>
                            <button type="button" onclick="loadJadwal()" class="btn btn-light btn-sm rounded-circle" style="width:36px;height:36px;padding:0;" aria-label="Muat ulang">
                                <i class="fa fa-sync text-secondary"></i>
                            </button>
                        </div>
                        <div class="sd-card-body">
                            <?php if ($kbms != null) : ?>
                                <?php if (count($jadwals) > 0):
                                    $no = 1;
                                    $arrIst = [];
                                    if (isset($kbms->istirahat)) {
                                        foreach ($kbms->istirahat as $istirahat) {
                                            array_push($arrIst, $istirahat['ist']);
                                            $arrDur[$istirahat['ist']] = $istirahat['dur'];
                                        }
                                    }
                                ?>
                                    <div class="table-responsive" id="list-jadwal">
                                        <table class="table table-borderless mb-0" style="font-size:1rem;">
                                            <tbody>
                                            <?php
                                            $jamMulai = new DateTime($kbms->kbm_jam_mulai);
                                            $jamSampai = new DateTime($kbms->kbm_jam_mulai);
                                            for ($i = 0; $i < $kbms->kbm_jml_mapel_hari; $i++) :
                                                $jamke = $i + 1;
                                                if (in_array($jamke, $arrIst)) :
                                                    $jamSampai->add(new DateInterval('PT' . $arrDur[$jamke] . 'M'));
                                                    ?>
                                                    <tr data-jamke="<?= $jamke ?>" style="background:#fef9c3;">
                                                        <td class="p-3 font-weight-bold"><?= $jamMulai->format('H:i') ?></td>
                                                        <td class="p-3 font-weight-bold">🍎 Istirahat</td>
                                                    </tr>
                                                    <?php
                                                    $jamMulai->add(new DateInterval('PT' . $arrDur[$jamke] . 'M'));
                                                else :
                                                    $jamSampai->add(new DateInterval('PT' . $kbms->kbm_jam_pel . 'M'));
                                                    ?>
                                                    <tr data-jamke="<?= $jamke ?>" style="border-bottom:1px solid #f1f5f9;">
                                                        <td class="p-3 font-weight-bold text-secondary"><?= $jamMulai->format('H:i') ?></td>
                                                        <td class="p-3 font-weight-bold text-dark">
                                                            <?= $jadwals[$jamke]->kode != null ? $jadwals[$jamke]->kode : '<span class="text-muted font-weight-light">--</span>' ?>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                    $jamMulai->add(new DateInterval('PT' . $kbms->kbm_jam_pel . 'M'));
                                                endif; endfor; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <div class="sd-empty">🎉 Tidak ada pelajaran hari ini</div>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="sd-empty">Jadwal kelas <b><?= $siswa->nama_kelas ?></b> belum ada.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="col-12 <?= $tampil_pelajaran ? 'col-md-7 order-2 order-md-1' : '' ?>">
                    <!-- ===== JADWAL UJIAN BERIKUTNYA ===== -->
                    <?php if (count($sisa_list) > 0): ?>
                        <div class="sd-card">
                            <div class="sd-card-head">🗓️ Ujian / Ulangan Berikutnya</div>
                            <div class="sd-card-body">
                                <?php foreach ($sisa_list as $j):
                                    $mulai   = date('Y-m-d', strtotime($j->tgl_mulai));
                                    $nama_jenis = (string)($j->nama_jenis ?? '');
                                    $kode_jenis = strtoupper((string)($j->kode_jenis ?? ''));
                                    $is_ulangan = stripos($nama_jenis, 'ulangan') !== false || strpos($kode_jenis, 'UH') === 0;
                                    $label = $nama_jenis !== '' ? $nama_jenis : ($is_ulangan ? 'Ulangan' : 'Ujian');
                                    $judul = !empty($j->nama_mapel) ? $j->nama_mapel : $j->bank_kode;
                                    $jam_m = !empty($j->jam_mulai) ? substr($j->jam_mulai, 0, 5) : '';
                                    $selisih = (int)floor((strtotime($mulai) - strtotime($hariIni)) / 86400);
                                    if ($selisih <= 0) $kapan = 'Hari ini';
                                    elseif ($selisih === 1) $kapan = 'Besok';
                                    else $kapan = $fmt_tgl($mulai);
                                    ?>
                                    <div class="sd-item">
                                        <div class="sd-item-ico"><i class="fas <?= $is_ulangan ? 'fa-pen' : 'fa-file-alt' ?>"></i></div>
                                        <div>
                                            <div class="sd-item-title"><?= html_escape($judul) ?></div>
                                            <div class="sd-item-meta"><?= html_escape($label) ?> &middot; <b><?= $kapan ?></b><?= $jam_m !== '' ? ' jam ' . $jam_m : '' ?></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php elseif ($tidak_ada_aktif): ?>
                        <div class="sd-card"><div class="sd-card-body"><div class="sd-empty">😊 Belum ada ujian atau ulangan.<?= $auto_keluar ? '<br><small>Kamu akan keluar otomatis...</small>' : '' ?></div></div></div>
                    <?php endif; ?>

                    <?php if ($tampil_info): ?>
                    <!-- ===== INFO & TIMELINE ===== -->
                    <div class="sd-card">
                        <div class="sd-card-head">📢 Info dari Guru</div>
                        <div class="sd-card-body">
                            <div class="konten-pengumuman">
                                <div id="pengumuman"></div>
                                <div id="loadmore-post" onclick="getPosts()" class="text-center mt-3 loadmore d-none">
                                    <div class="btn btn-modern-muted px-4 py-2">Lihat lainnya</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            </div><!-- /sd-center -->
        </div>
    </section>
</div>

<script>
    // hitung mundur sisa waktu ujian yang sedang berlangsung
    (function () {
        var els = document.querySelectorAll('.ju-sisa[data-sisa]');
        if (!els.length) return;
        var t0 = Date.now();
        function pad(n) { return n < 10 ? '0' + n : n; }
        function tick() {
            var passed = Math.floor((Date.now() - t0) / 1000);
            els.forEach(function (el) {
                var r = Math.max(0, parseInt(el.getAttribute('data-sisa'), 10) - passed);
                var h = Math.floor(r / 3600), m = Math.floor((r % 3600) / 60), d = r % 60;
                el.textContent = (h > 0 ? h + ':' + pad(m) : m) + ':' + pad(d);
            });
        }
        tick(); setInterval(tick, 1000);
    })();
</script>
<?php if ($auto_keluar && $tidak_ada_aktif): ?>
<script>
    // Tidak ada jadwal aktif -> keluar sendiri & kembali ke halaman login
    setTimeout(function () {
        window.location.replace('<?= base_url('auth/logout') ?>');
    }, <?= (int)$auto_keluar_detik * 1000 ?>);
</script>
<?php endif; ?>
<?php if ($auto_keluar): ?>
<script>
    // cek ulang jadwal tiap 30 detik; bila jadwal dinonaktifkan admin, siswa otomatis keluar
    setInterval(function () { location.reload(); }, 30000);
</script>
<?php endif; ?>
<?php if ($next_change !== null && ($next_change - $now_ts) < 21600): ?>
<script>
    // muat ulang otomatis tepat saat jam mulai/selesai ujian terdekat
    setTimeout(function () { location.reload(); }, <?= (int)(($next_change - $now_ts) * 1000) + 1500 ?>);
</script>
<?php endif; ?>

<div class="modal fade" id="pengumumanModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-md modal-dialog-centered" role="document">
        <div class="modal-content border-0" style="border-radius:24px;">
            <div class="modal-header border-0 pb-0">
                <div class="user-block d-flex align-items-center">
                    <img id="foto" class="rounded-circle-custom border" src="<?= base_url() ?>/assets/img/user.jpg" alt="User Image" width="42" height="42">
                    <div class="ml-2" style="line-height:1.2;">
                        <span id="username" class="font-weight-bold d-block text-dark">test</span>
                        <small id="tgl" class="text-muted">aja</small>
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body p-4">
                <div id="isi-pengumuman" class="text-secondary" style="line-height:1.6;"></div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-modern-muted w-100" data-dismiss="modal" style="padding:12px;">Tutup</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="komentarModal" tabindex="-1" role="dialog" aria-labelledby="komentarLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0" style="border-radius:24px;">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title font-weight-bold text-dark" id="komentarLabel">Tulis Komentar</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="d-flex align-items-start">
                    <img class="rounded-circle-custom mr-3 border" src="<?= base_url('assets/img/siswa.png') ?>" alt="Foto" width="38" height="38">
                    <div class="flex-grow-1">
                        <?= form_open('create', array('id' => 'komentar')) ?>
                        <input type="hidden" id="id-post" name="id_post" value="">
                        <div class="form-group mb-3">
                            <textarea name="text" rows="3" placeholder="Tulis komentar..." class="form-control" style="border-radius:16px;resize:none;" required></textarea>
                        </div>
                        <div class="text-right">
                            <button type="button" class="btn btn-light mr-1 px-3 font-weight-bold" data-dismiss="modal" style="border-radius:12px;">Batal</button>
                            <button type="submit" class="btn btn-success px-4 font-weight-bold" style="border-radius:12px;">Kirim</button>
                        </div>
                        <?= form_close() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="balasanModal" tabindex="-1" role="dialog" aria-labelledby="balasanLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0" style="border-radius:24px;">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title font-weight-bold text-dark" id="balasanLabel">Tulis Balasan</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="d-flex align-items-start">
                    <img class="rounded-circle-custom mr-3 border" src="<?= base_url('assets/img/siswa.png') ?>" alt="Foto" width="38" height="38">
                    <div class="flex-grow-1">
                        <?= form_open('create', array('id' => 'balasan')) ?>
                        <input type="hidden" id="id-comment" name="id_comment" value="">
                        <div class="form-group mb-3">
                            <textarea name="text" rows="3" placeholder="Tulis balasan..." class="form-control" style="border-radius:16px;resize:none;" required></textarea>
                        </div>
                        <div class="text-right">
                            <button type="button" class="btn btn-light mr-1 px-3 font-weight-bold" data-dismiss="modal" style="border-radius:12px;">Batal</button>
                            <button type="submit" class="btn btn-success px-4 font-weight-bold" style="border-radius:12px;">Balas</button>
                        </div>
                        <?= form_close() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let jadwalKbm;
    var arrIst = [];
    var kelas = '<?=$siswa->id_kelas?>';
    var kodeKelas = '<?=$siswa->kode_kelas?>';
    var pengumuman;

    var halaman = 0;
    var idSiswa = "<?=$siswa->id_siswa?>";

    function createTime(d) {
        var date = new Date(d);
        var jam = date.getHours();
        var menit = date.getMinutes();
        var sJam = jam < 10 ? '0' + jam : '' + jam;
        var sMenit = menit < 10 ? '0' + menit : '' + menit;

        var hari = daysdifference(d);
        if (hari === 0) return sJam + ':' + sMenit;
        if (hari === 1) return 'kemarin ' + sJam + ':' + sMenit;
        return jQuery.timeago(d) + ', ' + sJam + ':' + sMenit;
    }

    function daysdifference(last) {
        var startDay = new Date(last);
        var endDay = new Date();
        var millisBetween = startDay.getTime() - endDay.getTime();
        return Math.round(Math.abs(millisBetween / (1000 * 3600 * 24)));
    }

    function addComments(id, comments, append) {
        var comm = '';
        $.each(comments, function (i, v) {
            var dari, avatar;
            if (v.dari == '0') {
                dari = 'Admin';
                avatar = v.foto != null ? '<img class="rounded-circle-custom border mr-2" src="' + v.foto + '" width="36" height="36">' :
                    '<div class="btn-circle-sm btn-success text-center pt-1 mr-2 font-weight-bold" style="width:36px;height:36px;border-radius:50%">A</div>'
            } else {
                var fotoSrc = (v.dari_group == '2') ?
                    (v.foto != null ? base_url + v.foto : base_url + 'assets/img/siswa.png') :
                    (v.foto_siswa != null ? base_url + v.foto_siswa : base_url + 'assets/img/siswa.png');
                dari = (v.dari_group == '2') ? v.nama_guru : v.nama_siswa;
                avatar = '<img class="rounded-circle-custom border mr-2" src="' + fotoSrc + '" width="36" height="36">';
            }

            comm += '<div class="media mt-3 align-items-start" id="parent-reply' + v.id_comment + '">'
                + avatar +
                '    <div class="media-body">' +
                '        <div class="comment-bubble">' +
                '            <span class="font-weight-bold text-dark d-block mb-1" style="font-size:.85rem;">' + dari + '</span>' +
                '            <div class="comment-text text-secondary" style="font-size:.95rem;">' + v.text + '</div>' +
                '        </div>' +
                '        <div class="mt-1 ml-2 d-flex align-items-center" style="gap:12px;font-size:.8rem;">' +
                '            <span class="text-muted">' + createTime(v.tanggal) + '</span>' +
                '            <span id="trigger-reply' + v.id_comment + '" class="font-weight-bold text-primary action-collapse" style="cursor:pointer;" data-toggle="collapse" aria-expanded="true" href="#collapse-reply' + v.id_comment + '">' + v.jml + ' balasan</span>' +
                '            <span class="font-weight-bold text-success btn-toggle-reply" style="cursor:pointer;" data-id="' + v.id_comment + '" data-toggle="modal" data-target="#balasanModal"><i class="fas fa-reply mr-1"></i>Balas</span>';
            if (v.dari_group === '3' && v.dari === idSiswa) {
                comm += '    <span class="font-weight-bold text-danger" style="cursor:pointer;" data-id="' + v.id_comment + '"><i class="fa fa-trash mr-1"></i>Hapus</span>';
            }
            comm += '        </div>' +
                '<div id="collapse-reply' + v.id_comment + '" class="p-2 collapse toggle-reply" data-id="' + v.id_comment + '" data-parent="#parent-reply' + v.id_comment + '">';
            if (v.jml != '0') {
                comm += '<div id="konten-reply' + v.id_comment + '"></div>' +
                    '<div id="loadmore-reply' + v.id_comment + '" onclick="getReplies(' + v.id_comment + ')" class="text-center my-2 loadmore-reply">' +
                    '       <div class="btn btn-sm btn-light text-muted px-3" style="border-radius:20px;">Lihat balasan lainnya</div>' +
                    '</div>';
            }
            comm += '</div>' +
                '    </div>' +
                '</div>';
        });

        if (append) $(`#konten${id}`).append(comm);
        else $(`#konten${id}`).prepend(comm);

        $('.toggle-reply').on('shown.bs.collapse', function (e) {
            var konten = $(this);
            var id = konten.data('id');
            if ($(this).find('.media').length === 0) $(`#loadmore-reply${id}`).click();
        });

        $('#balasan').off('submit').on('submit', function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            var id = $(this).find('input[name=id_comment]').val();
            $.ajax({
                url: base_url + "siswa/savebalasan",
                data: $(this).serialize(),
                method: 'POST',
                dataType: "JSON",
                success: function (response) {
                    $('#balasanModal').modal('hide');
                    addReplies(id, response, false)
                },
                error: function () {
                    $('#balasanModal').modal('hide');
                    showDangerToast('Error, balasan tidak terkirim');
                }
            });
        });
        wrappTables();
    }

    function addReplies(id, replies, append) {
        var repl = '';
        $.each(replies, function (i, v) {
            if (!$(`.media${v.id_reply}`).length) {
                var dari, avatar;
                if (v.dari == '0') {
                    dari = 'Admin';
                    avatar = v.foto != null ? '<img class="rounded-circle-custom border mr-2" src="' + v.foto + '" width="30" height="30">' :
                        '<div class="btn-circle-sm btn-success text-center pt-1 mr-2 font-weight-bold" style="width:30px;height:30px;border-radius:50%;font-size:10px;">A</div>';
                } else {
                    var fotoSrc = (v.dari_group == '2') ?
                        (v.foto != null ? base_url + v.foto : base_url + 'assets/img/siswa.png') :
                        (v.foto_siswa != null ? base_url + v.foto_siswa : base_url + 'assets/img/siswa.png');
                    dari = (v.dari_group == '2') ? v.nama_guru : v.nama_siswa;
                    avatar = '<img class="rounded-circle-custom border mr-2" src="' + fotoSrc + '" width="30" height="30">';
                }

                repl += '<div class="media mt-2 align-items-start media' + v.id_reply + '">'
                    + avatar +
                    '    <div class="media-body">' +
                    '        <div class="reply-bubble">' +
                    '            <span class="font-weight-bold text-dark d-block mb-1" style="font-size:.8rem;">' + dari + '</span>' +
                    '            <div class="comment-text text-secondary" style="font-size:.9rem;">' + v.text + '</div>' +
                    '        </div>' +
                    '        <div class="mt-1 ml-2 d-flex align-items-center" style="gap:12px;font-size:.75rem;">' +
                    '            <span class="text-muted">' + createTime(v.tanggal) + '</span>';
                if (v.dari_group === '3' && v.dari === idSiswa) {
                    repl += '    <span class="font-weight-bold text-danger" style="cursor:pointer;" data-id="' + v.id_reply + '"><i class="fa fa-trash mr-1"></i>Hapus</span>';
                }
                repl += '        </div>' +
                    '    </div>' +
                    '</div>';
            }
        });

        if (append) $(`#konten-reply${id}`).append(repl);
        else $(`#konten-reply${id}`).prepend(repl);
    }

    function getComments(id) {
        $(`#loadmore${id}`).addClass('d-none');
        var $count = $(`#loadmore${id}`), page = $count.data('count') || 0;

        $.ajax({
            url: base_url + "siswa/getcomment/" + id + "/" + page,
            type: "GET",
            success: function (response) {
                page += 1;
                $count.data('count', page);
                if (response.length === 5) $(`#loadmore${id}`).removeClass('d-none');
                addComments(id, response, true);
            }
        });
    }

    function getReplies(id) {
        $(`#loadmore-reply${id}`).addClass('d-none');
        var $count = $(`#loadmore-reply${id}`), page = $count.data('count') || 0;

        $.ajax({
            url: base_url + "siswa/getreplies/" + id + "/" + page,
            type: "GET",
            success: function (response) {
                page += 1;
                $count.data('count', page);
                if (response.length === 5) $(`#loadmore-reply${id}`).removeClass('d-none');
                addReplies(id, response, true);
            }
        });
    }

    function addPosts(response) {
        var card = '';
        $.each(response, function (i, v) {
            var dari, avatar;
            if (v.dari == '0') {
                dari = 'Admin';
                avatar = v.foto != null ? '<img class="rounded-circle-custom border" src="' + v.foto + '" width="46" height="46">' :
                    '<div class="btn-circle btn-success text-center pt-2 font-weight-bold" style="width:46px;height:46px;border-radius:50%">A</div>';
            } else {
                var fotoSrc = (v.dari_group == '2') ?
                    (v.foto != null ? base_url + v.foto : base_url + 'assets/img/siswa.png') :
                    (v.foto_siswa != null ? base_url + v.foto_siswa : base_url + 'assets/img/siswa.png');
                dari = (v.dari_group == '2') ? v.nama_guru : v.nama_siswa;
                avatar = '<img class="rounded-circle-custom border" src="' + fotoSrc + '" width="46" height="46">';
            }

            card += '<div class="social-post">' +
                '    <div id="parent' + v.id_post + '">' +
                '        <div class="d-flex align-items-center mb-3">' +
                avatar +
                '            <div class="ml-3" style="line-height:1.2;">' +
                '                <span class="font-weight-bold text-dark d-block" style="font-size:1rem;">' + dari + '</span>' +
                '                <small class="text-muted">' + createTime(v.tanggal) + '</small>' +
                '            </div>' +
                '        </div>' +
                '        <div class="text-secondary mb-3" style="line-height:1.6;font-size:1rem;">' + v.text + '</div>' +
                '        <div class="d-flex" style="gap:10px;border-top:1px solid #f1f5f9;padding-top:12px;">' +
                '            <button type="button" class="btn btn-action-post flex-grow-1" data-id="' + v.id_post + '" data-toggle="modal" data-target="#komentarModal">' +
                '                <i class="far fa-comment-alt mr-1 text-primary"></i> Komentar' +
                '            </button>' +
                '            <button type="button" id="trigger' + v.id_post + '" class="btn btn-action-post flex-grow-1 action-collapse" data-toggle="collapse" href="#collapse-' + v.id_post + '">' +
                '                <i class="far fa-comments mr-1 text-success"></i> ' + v.jml +
                '            </button>' +
                '        </div>' +
                '    </div>' +
                '    <div id="collapse-' + v.id_post + '" class="collapse toggle-comment" data-id="' + v.id_post + '" data-parent="#parent' + v.id_post + '">' +
                '        <div id="konten' + v.id_post + '" class="pt-2 px-1"></div>';
            if (v.jml == '0') {
                card += '<div class="text-center py-3 text-muted font-italic">Belum ada komentar</div>';
            } else {
                card += '<div id="loadmore' + v.id_post + '" onclick="getComments(' + v.id_post + ')" class="text-center mt-3 loadmore">' +
                    '    <div class="btn btn-sm btn-light text-muted px-3" style="border-radius:20px;font-weight:600;">Lihat komentar</div>' +
                    '</div>';
            }
            card += '    </div>' +
                '</div>';
        });

        $('#pengumuman').html(card);

        $('.toggle-comment').on('shown.bs.collapse', function (e) {
            var id = $(this).data('id');
            if ($(this).find('.media').length === 0) $(`#loadmore${id}`).click();
        });

        $('#komentarModal').off('show.bs.modal').on('show.bs.modal', function (e) {
            var id = $(e.relatedTarget).data('id');
            $("#id-post").val(id);
            if (!$(`#collapse-${id}`).hasClass('show')) $(`#trigger${id}`).click();
        });

        $('#balasanModal').off('show.bs.modal').on('show.bs.modal', function (e) {
            var id = $(e.relatedTarget).data('id');
            $("#id-comment").val(id);
            if (!$(`#collapse-reply${id}`).hasClass('show')) $(`#trigger-reply${id}`).click();
        });

        $('#komentar').off('submit').on('submit', function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            var id = $(this).find('input[name=id_post]').val();
            $.ajax({
                url: base_url + "siswa/savekomentar",
                data: $(this).serialize(),
                method: 'POST',
                dataType: "JSON",
                success: function (response) {
                    $('#komentarModal').modal('hide');
                    addComments(id, response, false);
                },
                error: function () {
                    $('#komentarModal').modal('hide');
                    showDangerToast('Error, komentar tidak terkirim');
                }
            });
        });
        wrappTables();
    }

    function getPosts() {
        $(`#loadmore-post`).addClass('d-none');
        $.ajax({
            url: base_url + "siswa/getpost?halaman=" + halaman + "&kelas=" + kodeKelas,
            type: "GET",
            success: function (response) {
                halaman += 1;
                if (response.length === 5) $(`#loadmore-post`).removeClass('d-none');
                addPosts(response);
            }
        });
    }

    function loadPengumuman() {
        $.ajax({
            type: 'GET',
            url: base_url + 'dashboard/getpengumuman/3',
            success: function (data) {
                pengumuman = data;
                var ul = '<div class="list-group list-group-flush">';
                var pos = 0;
                $.each(data, function (key, value) {
                    var tgl = formatTanggal(value.date);
                    ul += '<a href="javascript:void(0)" data-toggle="modal" data-target="#pengumumanModal" data-pos="' + pos + '" class="list-group-item list-group-item-action border-0 px-1 py-3">' +
                        '    <div class="d-flex align-items-center">' +
                        '      <img class="rounded-circle-custom border" src="' + base_url + '/assets/img/user.jpg" width="38" height="38" />' +
                        '      <div class="ml-2 flex-grow-1" style="line-height:1.2;">' +
                        '        <span class="float-right text-xs text-muted">' + tgl + '</span>' +
                        '        <span class="d-block font-weight-bold text-dark">' + value.judul + '</span>' +
                        '        <small class="text-muted">' + value.nama_guru + '</small>' +
                        '      </div>' +
                        '    </div>' +
                        '</a>';
                    pos++;
                });
                ul += '<a href="" class="text-center font-weight-bold pt-3 d-block text-primary">Lihat semua pengumuman</a></div>';
                $('#list-pengumuman').html(ul);
            }
        });
    }

    function loadJadwal() {
        var date = new Date();
        var hari = date.getDay();

        $.ajax({
            type: 'GET',
            url: base_url + 'dashboard/getjadwalhariini/' + kelas + '/' + hari,
            success: function (data) {
                if (data.length !== 0) {
                    var tableJadwal = '<table class="table table-borderless mb-0" style="font-size:1rem;"><tbody>';
                    var jamMapel = jadwalKbm.jadwal.kbm_jam_pel;
                    var waktu = jadwalKbm.jadwal.kbm_jam_mulai;
                    var jmlMapel = jadwalKbm.jadwal.kbm_jml_mapel_hari;
                    var istirahat = jadwalKbm.istirahat;
                    var wktPel = 0;
                    var wktIst = 0;

                    for (let i = 0; i < jmlMapel; i++) {
                        var jamKe = i + 1;
                        var isIst = jQuery.inArray('' + jamKe, arrIst) > -1;

                        if (isIst) {
                            tableJadwal += '<tr style="background:#fef9c3;">' +
                                '<td class="p-3 font-weight-bold">' + waktu + '</td>' +
                                '<td class="p-3 font-weight-bold">🍎 Istirahat</td>' +
                                '</tr>';
                            waktu = addTimes(waktu, '00:' + istirahat[wktIst].dur);
                            wktIst++;
                        } else {
                            var mpl = data[wktPel].nama_mapel == null ? '- -' : data[wktPel].nama_mapel;
                            tableJadwal += '<tr style="border-bottom:1px solid #f1f5f9;">' +
                                '<td class="p-3 font-weight-bold text-secondary">' + waktu + '</td>' +
                                '<td class="p-3 font-weight-bold text-dark">' + mpl + '</td>' +
                                '</tr>';
                            wktPel++;
                            waktu = addTimes(waktu, '00:' + jamMapel);
                        }
                    }
                    tableJadwal += '</tbody></table>';
                    $('#list-jadwal').html(tableJadwal);
                } else {
                    $('#list-jadwal').html('<div class="sd-empty">🎉 Tidak ada pelajaran hari ini</div>');
                }
            }
        });
    }

    $(document).ready(function () {
        // bagian yang disembunyikan tidak perlu memuat data
        if ($('#list-jadwal').length) {
            $.ajax({
                type: 'GET',
                url: base_url + 'dashboard/getjadwalkbm/' + kelas,
                success: function (data) {
                    jadwalKbm = data;
                    $.each(data.istirahat, function (key, value) {
                        arrIst.push(value.ist);
                    });
                }
            });
        }

        $('#pengumumanModal').on('show.bs.modal', function (e) {
            var pos = $(e.relatedTarget).data('pos');
            var item = pengumuman[pos];
            $('#username').text(item.nama_guru);
            $('#tgl').text(formatTanggal(item.date));
            $('#foto').attr('src', base_url + '/assets/img/' + item.foto);
            $('#isi-pengumuman').html(' <h5 class="font-weight-bold text-dark mb-3">' + item.judul + '</h5>' + item.text);
        });

        if ($('#pengumuman').length) getPosts();
    });

    function addTimes(startTime, endTime) {
        var times = [0, 0, 0];
        var a = (startTime || '').split(':');
        var b = (endTime || '').split(':');
        for (var i = 0; i < 3; i++) {
            a[i] = isNaN(parseInt(a[i])) ? 0 : parseInt(a[i]);
            b[i] = isNaN(parseInt(b[i])) ? 0 : parseInt(b[i]);
            times[i] = a[i] + b[i];
        }
        var hours = times[0], minutes = times[1], seconds = times[2];
        if (seconds >= 60) { minutes += (seconds / 60) << 0; seconds -= 60 * ((seconds / 60) << 0); }
        if (minutes >= 60) { hours += (minutes / 60) << 0; minutes -= 60 * ((minutes / 60) << 0); }
        return ('0' + hours).slice(-2) + ':' + ('0' + minutes).slice(-2);
    }

    function wrappTables() {
        $('table').each(function () {
            if (!$(this).parents('.table-responsive').length) {
                $(this).wrap('<div class="table-responsive"></div>');
            }
        });
    }
</script>