<?php
// Memperbaiki validasi core framework CodeIgniter agar halaman tidak blank putih
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/fontawesome-free/css/all.min.css">

<style>
    /* ==========================================================================
       OVERRIDE TEMA UTAMA: MODERN CBT LIGHT THEME (SESUAI GAMBAR)
       ========================================================================== */
    body, html {
        width: 100%;
        height: 100%;
        background-color: #f0f4f8 !important; 
        font-family: 'Inter', 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    }

    .content-wrapper.bg-custom-theme {
        background: #f0f4f8 !important;
        position: relative;
        min-height: 100vh;
        overflow-x: hidden;
        padding: 0 25px;
    }

    .content-wrapper.bg-custom-theme::before,
    .content-wrapper.bg-custom-theme::after,
    .orange-glow-left,
    .bottom-light-bar {
        display: none !important;
    }

    .card-stat-custom {
        border: none !important;
        border-radius: 16px !important;
        padding: 20px 24px !important;
        min-height: 145px;
        color: #ffffff !important;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -4px rgba(0, 0, 0, 0.05) !important;
        transition: transform 0.2s;
    }
    .card-stat-custom:hover {
        transform: translateY(-2px);
    }
    .card-stat-custom .stat-title {
        font-size: 14px;
        font-weight: 700;
        opacity: 0.95;
        margin-bottom: 4px;
        letter-spacing: 0.3px;
    }
    .card-stat-custom .stat-value {
        font-size: 34px;
        font-weight: 800;
        line-height: 1.1;
        margin-bottom: 4px;
    }
    .card-stat-custom .stat-value-sub {
        font-size: 18px !important;
        font-weight: 600;
        opacity: 0.8;
    }
    .card-stat-custom .stat-desc {
        font-size: 12px;
        opacity: 0.85;
        font-weight: 500;
    }
    .card-stat-custom .stat-icon-bg {
        position: absolute;
        right: 24px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 50px;
        opacity: 0.25;
    }

    .bg-card-orange { background: linear-gradient(135deg, #ff6b3d 0%, #ff8e6c 100%) !important; }
    .bg-card-blue   { background: linear-gradient(135deg, #1488cc 0%, #2b32b2 100%) !important; }
    .bg-card-green  { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%) !important; }
    .bg-card-amber  { background: linear-gradient(135deg, #f5a623 0%, #f8b739 100%) !important; }

    .circle-progress-card {
        position: absolute;
        right: 24px;
        top: 50%;
        transform: translateY(-50%);
        width: 65px;
        height: 65px;
        border: 6px solid rgba(255,255,255,0.2);
        border-radius: 50%;
        border-top: 6px solid #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
    }

    .check-circle-card-white {
        position: absolute;
        right: 24px;
        top: 50%;
        transform: translateY(-50%);
        width: 50px;
        height: 50px;
        background: #ffffff !important;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    .check-circle-card-white i { color: #11998e !important; }

    .card-bottom-progress {
        width: 85%;
        height: 6px;
        background: rgba(255, 255, 255, 0.3);
        border-radius: 10px;
        margin-top: 10px;
        overflow: hidden;
    }
    .card-bottom-progress-fill {
        width: 0%;
        height: 100%;
        background: #ffffff;
        border-radius: 10px;
        transition: width 0.5s ease-in-out;
    }

    .gauge-score-container {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-top: 5px;
        position: relative;
    }
    .semi-gauge {
        position: relative;
        width: 90px;
        height: 45px;
        overflow: hidden;
        border-top-left-radius: 90px;
        border-top-right-radius: 90px;
        background: rgba(255, 255, 255, 0.2);
    }
    .semi-gauge::after {
        content: '';
        position: absolute;
        bottom: 0; left: 10px;
        width: 70px; height: 35px;
        border-top-left-radius: 70px;
        border-top-right-radius: 70px;
        background: #f5a623; 
    }
    .semi-gauge-fill {
        position: absolute;
        top: 0; left: 0;
        width: 90px; height: 90px;
        border-radius: 50%;
        background: #ffffff;
        transform: rotate(0deg);
        transform-origin: center bottom;
        transition: transform 0.5s ease-in-out;
    }
    .gauge-text {
        position: absolute;
        bottom: -2px; left: 0; right: 0;
        font-size: 19px;
        font-weight: 800;
        color: #ffffff;
        z-index: 10;
        text-align: center;
        line-height: 1.1;
    }
    .gauge-text span.gauge-label {
        font-size: 7px;
        display: block;
        font-weight: 500;
        opacity: 0.85;
        text-transform: uppercase;
        margin-top: 1px;
    }

    .sparkline-wave-bg {
        position: absolute;
        right: 20px;
        bottom: 30px;
        font-size: 45px;
        opacity: 0.35;
        color: #ffffff;
    }

    .main-table-card {
        background: #ffffff !important;
        border-radius: 16px !important;
        border: none !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05) !important;
    }
    .main-table-header {
        background-color: #ffffff !important;
        border-bottom: 1px solid #f1f5f9 !important;
        padding: 20px 24px !important;
    }

    #wadah-tabel-live table th {
        background-color: #f8fafc !important;
        color: #64748b !important;
        font-size: 11px !important;
        text-transform: uppercase !important;
        font-weight: 700 !important;
        padding: 14px 16px !important;
        border-bottom: 1px solid #e2e8f0 !important;
    }
    #wadah-tabel-live table td {
        vertical-align: middle !important;
        padding: 14px 16px !important;
        font-size: 13px !important;
        color: #334155 !important;
        border-bottom: 1px solid #f1f5f9 !important;
    }

    @media (min-width: 992px) {
        .col-lg-custom-4 { flex: 0 0 25%; max-width: 25%; }
    }

    /* ==========================================================================
       STYLE BARU: POP-UP BANNER PROMO SELAMAT DATANG (PERBAIKAN FONT MENUMPUK)
       ========================================================================== */
    .banner-modal .modal-content {
        border: none !important;
        border-radius: 20px !important;
        background: linear-gradient(135deg, #1e3a8a, #3b82f6);
        color: #ffffff;
        overflow: hidden;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4) !important;
        position: relative;
    }

    .banner-modal .close-banner {
        position: absolute;
        top: 15px;
        right: 20px;
        color: rgba(255, 255, 255, 0.8);
        font-size: 30px;
        font-weight: 300;
        cursor: pointer;
        background: none;
        border: none;
        outline: none;
        z-index: 1051;
        transition: color 0.2s;
    }
    .banner-modal .close-banner:hover {
        color: #ffffff;
    }

    .banner-body {
        padding: 45px 35px !important;
        text-align: center;
    }

    .banner-badge {
        background: #f43f5e;
        color: white;
        font-weight: 800;
        padding: 6px 18px;
        border-radius: 30px;
        display: inline-block;
        font-size: 0.75rem;
        letter-spacing: 1.5px;
        margin-bottom: 15px;
        box-shadow: 0 4px 10px rgba(244, 63, 94, 0.4);
    }

    .banner-title {
        font-size: 1.45rem;
        font-weight: 700;
        line-height: 1.3;
        margin-bottom: 5px;
        color: rgba(255, 255, 255, 0.95);
    }

    .banner-sub {
        font-size: 1.85rem; /* Dikecilkan sedikit agar lebih pas saat wrap text */
        font-weight: 800;
        color: #fdba74; 
        margin: 12px 0 18px 0;
        text-shadow: 0 2px 5px rgba(0,0,0,0.2);
        line-height: 1.35 !important; /* Mencegah tulisan menumpuk saat terpecah ke baris baru */
        word-wrap: break-word;
    }

    .banner-desc {
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.85);
        margin-bottom: 25px;
        line-height: 1.6;
    }

    .btn-banner-action {
        background: #22c55e !important; 
        color: white !important;
        font-weight: 700;
        padding: 12px 45px;
        border-radius: 10px;
        border: none;
        font-size: 0.95rem;
        box-shadow: 0 4px 12px rgba(34, 197, 94, 0.4);
        transition: all 0.2s;
    }
    .btn-banner-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(34, 197, 94, 0.6);
        background: #16a34a !important;
    }
</style>

<!-- HTML STRUKTUR: POP-UP BANNER SELAMAT DATANG -->
<div class="modal fade banner-modal" id="welcomePromoModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <button type="button" class="close-banner" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="modal-body banner-body">
                <div class="banner-badge">INFO LIVE STATUS</div>
                <h3 class="banner-title">Selamat Datang</h3>
                <div class="banner-sub">SD Negeri 14 Sungai Kunyit</div>
                <p class="banner-desc">Sistem pemantauan ujian terintegrasi sedang berjalan. Semua pergerakan nilai, durasi, dan status siswa diperbarui secara real-time.</p>
                <button type="button" class="btn btn-banner-action" data-dismiss="modal">Masuk Dashboard</button>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="csrf-live-token" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

<div class="content-wrapper bg-custom-theme">
    <div class="pt-4 pb-5 mx-auto" style="max-width: 1650px;">
        
        <!-- CARD WIDGET STATISTIK -->
        <div class="row px-2 mb-4">
            <div class="col-12 col-sm-6 col-lg-custom-4 mb-3">
                <div class="card-stat-custom bg-card-orange">
                    <div class="stat-title">Total Siswa</div>
                    <div class="stat-value" id="card-total-peserta">0</div>
                    <div class="stat-desc">Siswa Mendaftar</div>
                    <div class="stat-icon-bg"><i class="fa fa-users"></i></div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-custom-4 mb-3">
                <div class="card-stat-custom bg-card-blue">
                    <div class="stat-title">Siswa Aktif</div>
                    <div class="stat-value">
                        <span id="card-sedang-ujian">0</span><span class="stat-value-sub" id="card-total-append-blue"> / 0</span>
                    </div>
                    <div class="stat-desc" id="pct-sedang-ujian">Sedang Mengerjakan</div>
                    <div class="circle-progress-card" id="circle-pct-val">0%</div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-custom-4 mb-3">
                <div class="card-stat-custom bg-card-green">
                    <div class="stat-title">Siswa Selesai</div>
                    <div class="stat-value d-flex align-items-center gap-2">
                        <span id="card-selesai-ujian">0</span>
                        <i class="fa-solid fa-circle-check" style="font-size: 18px; opacity: 0.9;"></i>
                    </div>
                    <div class="stat-desc" id="pct-selesai-ujian">Selesai & Kumpul Ujian</div>
                    <div class="check-circle-card-white"><i class="fa fa-check"></i></div>
                    <div class="card-bottom-progress">
                        <div class="card-bottom-progress-fill" id="progress-hijau-fill"></div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-custom-4 mb-3">
                <div class="card-stat-custom bg-card-amber">
                    <div class="stat-title">Skor Rata-Rata</div>
                    <div class="gauge-score-container">
                        <div class="semi-gauge">
                            <div class="semi-gauge-fill" id="gauge-fill-id"></div>
                            <div class="gauge-text">
                                <span id="card-rata-nilai" style="display:block; margin-bottom:2px;">0.0</span>
                                <span class="gauge-label">Current Avg Score</span>
                            </div>
                        </div>
                    </div>
                    <div class="stat-desc" style="margin-top: 5px;">Nilai Rata-rata Ujian</div>
                    <div class="sparkline-wave-bg"><i class="fa-solid fa-chart-line"></i></div>
                </div>
                <span id="card-belum-mulai" style="display:none;">0</span>
                <span id="pct-belum-mulai" style="display:none;">0%</span>
            </div>
        </div>

        <!-- LIVE TABLE DATA VIEW -->
        <div class="row px-2 connectedSortable">
            <div class="col-12">
                <div class="card main-table-card overflow-hidden">
                    <div class="card-body p-0">
                        <div class="table-responsive w-full">
                            <div id="wadah-tabel-live" class="w-full">
                                <?php $this->load->view('live_tabel'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    adaJadwalUjian = '<?= isset($ada_ujian) ? count($ada_ujian) : 0 ?>';
    localStorage.setItem('ada_jadwal_ujian', adaJadwalUjian);
</script>
<script src="<?= base_url() ?>/assets/app/js/jquery.rowspanizer.js"></script>
<script src="<?= base_url() ?>/assets/app/js/dashboard.js"></script>

<script>
    var runtime_val_kelas  = "";
    var runtime_txt_kelas  = "Semua Kelas";
    var runtime_val_jadwal = "";
    var runtime_txt_jadwal = "Semua Jadwal Ujian";
    var master_backup_jadwal = [];
    var is_user_interacting = false; 

    function hitungUlangStatistikMurniDariDOM() {
        if (!window.jQuery) return;
        
        var tbody = jQuery('#wadah-tabel-live table tbody');
        if (!tbody.length) return;
        
        var baris = tbody.find('tr');
        if (baris.length === 0 || baris.hasClass('no-data') && baris.length === 1) return;

        var totalPeserta = 0;
        var sedangUjian = 0;
        var selesaiUjian = 0;
        var belumMulai = 0;
        var totalNilai = 0;
        var jumlahSiswaPunyaNilai = 0;

        var idxStatus = 9; 
        var idxNilai = 8;  

        baris.each(function() {
            var tr = jQuery(this);
            if (tr.hasClass('no-data') || tr.find('td').length <= 1) return;
            if (tr.css('display') === 'none') return;

            totalPeserta++;
            
            var txtStatus = tr.find('td').eq(idxStatus).text().toLowerCase().trim();
            var isSelesai = txtStatus.indexOf('selesai') > -1 || txtStatus.indexOf('complete') > -1 || txtStatus.indexOf('sudah') > -1;
            var isAktif   = txtStatus.indexOf('online') > -1 || txtStatus.indexOf('kerja') > -1 || txtStatus.indexOf('active') > -1 || txtStatus.indexOf('mulai') > -1;
            
            if (isSelesai) { 
                selesaiUjian++; 
            } else if (isAktif) { 
                sedangUjian++; 
            } else { 
                belumMulai++; 
            }

            var txtNilai = tr.find('td').eq(idxNilai).text().replace(/[^0-9.,]/g, '').replace(',', '.').trim();
            var nilaiFloat = parseFloat(txtNilai);

            if (!isNaN(nilaiFloat) && (isSelesai || isAktif)) {
                totalNilai += nilaiFloat;
                jumlahSiswaPunyaNilai++;
            }
        });

        var rataRata = jumlahSiswaPunyaNilai > 0 ? (totalNilai / jumlahSiswaPunyaNilai).toFixed(1) : "0.0";

        jQuery('#card-total-peserta').text(totalPeserta.toLocaleString('id-ID'));
        jQuery('#card-sedang-ujian').text(sedangUjian.toLocaleString('id-ID'));
        jQuery('#card-total-append-blue').text(' / ' + totalPeserta.toLocaleString('id-ID'));
        jQuery('#card-selesai-ujian').text(selesaiUjian.toLocaleString('id-ID'));
        jQuery('#card-rata-nilai').text(rataRata);

        var pctAktif = totalPeserta > 0 ? ((sedangUjian / totalPeserta) * 100).toFixed(0) : 0;
        jQuery('#circle-pct-val').text(pctAktif + '%');
        
        var pctSelesai = totalPeserta > 0 ? ((selesaiUjian / totalPeserta) * 100).toFixed(0) : 0;
        jQuery('#progress-hijau-fill').css('width', pctSelesai + '%');

        var nilaiMaksimalUjian = 100;
        var hitungSudutDerajat = (parseFloat(rataRata) / nilaiMaksimalUjian) * 180;
        if(hitungSudutDerajat > 180) hitungSudutDerajat = 180;
        jQuery('#gauge-fill-id').css('transform', 'rotate(' + hitungSudutDerajat + 'deg)');
    }

    function dapatkanDataTabelDanUpdateGrafik() {
        hitungUlangStatistikMurniDariDOM();
    }

    function terapkanRowspanizerOtomatis() {
        if (window.jQuery && jQuery('#wadah-tabel-live table').length) {
            jQuery('#wadah-tabel-live table td').removeAttr('rowspan').show();
            jQuery('#wadah-tabel-live table').rowspanizer({ vertical_align: 'middle' });
        }
    }

    function urutkanDanBeriRankingOtomatis() {
        if (!window.jQuery) return;
        var tBody = jQuery('#wadah-tabel-live table tbody');
        if (!tBody.length || tBody.find('tr').length === 0) return;

        var indexKolomNilai = 8, indexKolomRanking = 0;
        var barisSiswa = tBody.find('tr').get();
        var urutanLama = barisSiswa.slice();
        barisSiswa.sort(function(a, b) {
            var rawA = jQuery(a).find('td').eq(indexKolomNilai).text().replace(/[^0-9.,]/g, '').replace(',', '.');
            var rawB = jQuery(b).find('td').eq(indexKolomNilai).text().replace(/[^0-9.,]/g, '').replace(',', '.');
            return (parseFloat(rawB) || 0) - (parseFloat(rawA) || 0);
        });

        // Hanya susun ulang bila urutannya benar-benar berubah. Menyusun ulang baris setiap kali
        // (tiap AJAX sukses) membuat klik pada tombol di dalam baris sering hilang.
        var urutanBerubah = false;
        for (var u = 0; u < barisSiswa.length; u++) {
            if (barisSiswa[u] !== urutanLama[u]) { urutanBerubah = true; break; }
        }
        if (urutanBerubah) {
            jQuery.each(barisSiswa, function(index, row) { tBody.append(row); });
        }

        var nomorRank = 1;
        tBody.find('tr').each(function() {
            var tr = jQuery(this);
            if (tr.css('display') !== 'none' && !tr.hasClass('no-data') && tr.find('td').length > 1) {
                var selRank = tr.find('td').eq(indexKolomRanking);
                if (selRank.text().trim() !== String(nomorRank)) {
                    selRank.html('<b class="text-muted">' + nomorRank + '</b>');
                }
                nomorRank++;
            }
        });
        hitungUlangStatistikMurniDariDOM();
    }

    // Menerapkan filter Kelas & Mapel dari dropdown #filter-kelas / #filter-mapel (live_tabel.php).
    // Dipanggil ulang setiap AJAX sukses (refresh 4 detik), jadi harus membaca pilihan dropdown saat ini,
    // kalau tidak semua baris ditampilkan lagi dan filter Mapel/Kelas seolah ter-reset.
    function saringKelompokKelasSecaraPresisi() {
        if (!window.jQuery) return;
        var kelasDipilih = jQuery('#filter-kelas').val() || '';
        var mapelDipilih = jQuery('#filter-mapel').val() || '';

        jQuery('#wadah-tabel-live table tbody tr').each(function() {
            var trSis = jQuery(this);
            if (trSis.attr('data-id') === undefined) return; // lewati baris 'tidak ada data'

            var cocokKelas = (kelasDipilih === '' || trSis.attr('data-kelas') === kelasDipilih);
            var cocokMapel = (mapelDipilih === '' || trSis.attr('data-mapel') === mapelDipilih);

            if (cocokKelas && cocokMapel) trSis.show(); else trSis.hide();
        });
        urutkanDanBeriRankingOtomatis();
    }

    if (window.jQuery) {
        jQuery(document).ajaxSuccess(function(event, xhr, settings) {
            setTimeout(function() {
                saringKelompokKelasSecaraPresisi();
                hitungUlangStatistikMurniDariDOM();
            }, 80); 
        });
    }

    setInterval(function() {
        hitungUlangStatistikMurniDariDOM();
    }, 1000);

    document.addEventListener("DOMContentLoaded", function() {
        if (window.jQuery) {
            jQuery("#loading").hide();
            jQuery(".overlay").hide();
            
            saringKelompokKelasSecaraPresisi();
            hitungUlangStatistikMurniDariDOM();

            // SCRIPT PEMICU BANNER: Hanya muncul 1 kali per login/sesi aktif tab browser.
            if (!sessionStorage.getItem('dashboard_banner_shown')) {
                jQuery('#welcomePromoModal').modal('show');
                sessionStorage.setItem('dashboard_banner_shown', 'true');
            }
        }
    });
</script>