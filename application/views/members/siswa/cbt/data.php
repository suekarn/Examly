<?php
/**
 * Created by IntelliJ IDEA.
 * User: multazam
 * Date: 07/08/20
 * Time: 22:29
 */

// PENGAMAN GLOBAL UTAMA
if (!isset($reset)) $reset = 0;
if (!isset($selesai)) $selesai = false;
if (!isset($lanjutkan)) $lanjutkan = false;

$arrGuru = [];
foreach ($guru as $g) {
    $arrGuru[$g->id_guru] = $g->nama_guru;
}

$jam_pertama = null;
$jadwal_selesai = [];
$jamSesi = '0'; 

// Normalisasi Tanggal Hari Ini untuk Pencocokan Fleksibel
$todayStr = date('Y-m-d');
$todayTime = strtotime($todayStr);

$jadwalHariIni = [];
$jadwalRiwayat = [];

// Pisahkan Jadwal Secara Akurat Berdasarkan Waktu Nyata
if (isset($cbt_jadwal) && is_array($cbt_jadwal)) {
    foreach ($cbt_jadwal as $tgl => $jadwals) {
        if (strtotime($tgl) === $todayTime) {
            $jadwalHariIni = $jadwals;
        } else {
            $jadwalRiwayat[$tgl] = $jadwals;
        }
    }
}

// ===== JAM DARI JADWAL (kolom jam_mulai & jam_selesai di Data Ujian > Jadwal) =====
// $jamJadwal[id_jadwal] = "HH:MM - HH:MM" (kosong jika jam jadwal tidak diisi)
$jamJadwal = [];
$jamDariJadwal = null;
$jamSampaiJadwal = null;
if (count($jadwalHariIni) > 0) {
    $idsJadwalHariIni = [];
    foreach ($jadwalHariIni as $jd) { $idsJadwalHariIni[] = $jd->id_jadwal; }

    $CI_jam =& get_instance();
    $CI_jam->db->select('id_jadwal, jam_mulai, jam_selesai');
    $CI_jam->db->where_in('id_jadwal', $idsJadwalHariIni);
    foreach ($CI_jam->db->get('cbt_jadwal')->result() as $rj) {
        if (!empty($rj->jam_mulai) && !empty($rj->jam_selesai)) {
            $jamJadwal[$rj->id_jadwal] = substr($rj->jam_mulai, 0, 5) . ' - ' . substr($rj->jam_selesai, 0, 5);
            if ($jamDariJadwal === null || $rj->jam_mulai < $jamDariJadwal) $jamDariJadwal = $rj->jam_mulai;
            if ($jamSampaiJadwal === null || $rj->jam_selesai > $jamSampaiJadwal) $jamSampaiJadwal = $rj->jam_selesai;
        }
    }
}
// Kartu profil: pakai rentang jam jadwal; kalau jam jadwal kosong, kembali ke jam sesi
$profilDari   = $jamDariJadwal   !== null ? substr($jamDariJadwal, 0, 5)   : (isset($cbt_info->waktu_mulai) ? substr($cbt_info->waktu_mulai, 0, -3) : '');
$profilSampai = $jamSampaiJadwal !== null ? substr($jamSampaiJadwal, 0, 5) : (isset($cbt_info->waktu_akhir) ? substr($cbt_info->waktu_akhir, 0, -3) : '');
?>

<style>
    .custom-card {
        border: none !important;
        border-radius: 16px !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04) !important;
        overflow: hidden;
    }
    .custom-badge-list .list-group-item {
        border-left: 0 !important;
        border-right: 0 !important;
        border-top: 0 !important;
        padding: 14px 8px;
        background: transparent;
    }
    .btn-modern {
        border-radius: 12px !important;
        padding: 12px 20px !important;
        font-weight: 600 !important;
        letter-spacing: 0.5px;
        transition: all 0.2s ease-in-out !important;
    }
    .btn-modern:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        opacity: 0.95;
    }
    .table-modern thead th {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        font-weight: 700;
        color: #718096;
        border-bottom: 1px solid #edf2f7 !important;
    }
    .table-modern tbody td {
        padding: 16px 12px !important;
        border-bottom: 1px solid #edf2f7 !important;
    }
</style>

<div class="content-wrapper" style="margin-top: -1px; background-color: #f7fafc;">
    <div class="sticky"></div>
    <section class="content overlap p-2 p-md-4">
        <div class="container-fluid">
            
            <div class="row mb-3">
                <div class="col-12">
                    <?php
                    $cbt_setting = [];
                    $this->load->view('members/siswa/templates/top'); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-5 mb-4">
                    <?php if ($cbt_info == null) : ?>
                        <div class="card custom-card bg-white text-center p-4 h-100 d-flex align-items-center justify-content-center">
                            <i class="fas fa-exclamation-circle text-warning fa-2x mb-2"></i>
                            <h6 class="font-weight-bold text-muted mb-0">Tidak ada jadwal penilaian aktif</h6>
                        </div>
                    <?php else: ?>
                        <div class="card custom-card h-100 bg-white">
                            <div class="card-header bg-white border-0 pt-4 pb-0 text-center">
                                <span class="badge badge-soft px-3 py-2 mb-2 font-weight-bold" style="border-radius: 30px; background-color: #ebf8ff; color: #2b6cb0;">PROFIL PESERTA</span>
                                <h4 class="card-title font-weight-bold text-wrap d-block w-100 mb-0" style="color: #2d3748;"><?= $siswa->nama ?></h4>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush custom-badge-list">
                                    <?php
                                    $arrTitle = ['No. Peserta', 'Ruang', 'Sesi', 'Dari', 'Sampai'];
                                    $arrSub = [$cbt_info->no_peserta->nomor_peserta ?? '', $cbt_info->nama_ruang ?? '', $cbt_info->nama_sesi ?? '', $profilDari, $profilSampai];
                                    foreach ($arrTitle as $key => $title) :
                                        if ($arrSub[$key] == null) array_push($cbt_setting, $title)
                                        ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span class="text-muted small font-weight-bold"><?= $title ?></span>
                                            <span class="text-dark font-weight-bold"><?= $arrSub[$key] ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-md-7 mb-4">
                    <div class="card custom-card text-white h-100" style="background: linear-gradient(135deg, #e53e3e 0%, #dd6b20 100%);">
                        <div class="card-body p-4 d-flex flex-column justify-content-center">
                            <div class="d-flex align-items-center justify-content-center mb-3">
                                <i class="fas fa-shield-alt fa-2x mr-2"></i>
                                <h5 class="mb-0 font-weight-bold" style="letter-spacing: 0.5px;">TATA TERTIB UTAMA</h5>
                            </div>
                            <p class="small text-center mb-3" style="opacity: 0.9;">Selama melaksanakan ULANGAN/UJIAN berlangsung, Siswa <b>DILARANG:</b></p>
                            <ul class="pl-3 mx-auto mb-0" style="line-height: 1.8; font-weight: 500; font-size: 0.95rem; max-width: 500px;">
                                <li>Meninggalkan ruang ujian tanpa izin pengawas</li>
                                <li>Logout/Keluar dari aplikasi tanpa izin dari pengawas</li>
                                <li>Saling memberitahukan jawaban sesama peserta</li>
                                <li>Membawa makanan dan minuman ke meja ujian</li>
                                <li>Membawa handphone/gadget pribadi ke ruangan ujian</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-12">
                    <div class="card custom-card bg-white">
                        <div class="card-header bg-white border-0 text-center pt-4 pb-2">
                            <h6 class="text-muted text-uppercase font-weight-bold mb-1" style="letter-spacing: 1.5px; font-size: 0.8rem;">AGENDA UTAMA</h6>
                            <h4 class="font-weight-bold text-dark mb-0" style="letter-spacing: -0.5px;">JADWAL PENILAIAN HARI INI</h4>
                            <span class="badge badge-light mt-2 px-3 py-2 text-muted font-weight-bold" style="border-radius: 8px; font-size: 0.85rem;"><i class="far fa-calendar-alt mr-2 text-primary"></i><?= buat_tanggal(date('D, d M Y')) ?></span>
                        </div>
                        <div class="card-body px-3 px-md-4">
                            <div class="row justify-content-center" id="jadwal-content">
                                <?php
                                if ($cbt_info == null || count($cbt_setting) > 0) : ?>
                                    <div class="col-12">
                                        <div class="alert alert-warning text-center border-0 p-4" style="border-radius: 12px; background-color: #fffaf0; color: #dd6b20;">
                                            <i class="fas fa-lock fa-2x mb-2 d-block"></i>
                                            <h6 class="font-weight-bold mb-1">Akses Ujian Belum Terbuka</h6>
                                            <span class="small">Tidak ada jadwal aktif atau data administrasi Anda belum lengkap. Hubungi Proktor/Admin.</span>
                                        </div>
                                    </div>
                                <?php else:
                                    $jamSesi = $cbt_info == null ? '0' : (isset($cbt_info->sesi_id) ? $cbt_info->sesi_id : $cbt_info->id_sesi);
                                    if (count($jadwalHariIni) > 0) :
                                        foreach ($jadwalHariIni as $key => $jadwal)  :
                                            $kk = unserialize($jadwal->bank_kelas ?? '');
                                            $arrKelasCbt = [];
                                            foreach ($kk as $k) { array_push($arrKelasCbt, $k['kelas_id']); }

                                            $startDay = strtotime($jadwal->tgl_mulai);
                                            $endDay = strtotime($jadwal->tgl_selesai);
                                            $today = strtotime(date('Y-m-d'));

                                            $waktuMulaiSesi = isset($sesi[$jamSesi]['mulai']) ? $sesi[$jamSesi]['mulai'] : date('H:i');
                                            $waktuAkhirSesi = isset($sesi[$jamSesi]['akhir']) ? $sesi[$jamSesi]['akhir'] : date('H:i');

                                            $sesiMulai = new DateTime($waktuMulaiSesi);
                                            $sesiSampai = new DateTime($waktuAkhirSesi);
                                            $now = strtotime(date('H:i'));

                                            $durasi = isset($elapsed[$jadwal->id_jadwal]) ? $elapsed[$jadwal->id_jadwal] : null;
                                            $jadwal_selesai[$jadwal->tgl_mulai][$jadwal->jam_ke] = $durasi != null ? $durasi->status == '2' : false;

                                            if ($durasi != null) {
                                                $curr_selesai = isset($durasi->selesai) && $durasi->selesai != null;
                                                $curr_lanjutkan = isset($durasi->lama_ujian) && $durasi->lama_ujian != null;
                                                $curr_reset = isset($durasi->reset) ? $durasi->reset : 0;
                                                
                                                if ($curr_lanjutkan && !$curr_selesai) {
                                                    $bg = 'linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%)'; 
                                                    $textColor = '#3730a3'; $subColor = '#4338ca';
                                                } elseif ($curr_selesai) {
                                                    $bg = 'linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%)'; 
                                                    $textColor = '#065f46'; $subColor = '#047857';
                                                } else {
                                                    $bg = 'linear-gradient(135deg, #fee2e2 0%, #fecaca 100%)'; 
                                                    $textColor = '#991b1b'; $subColor = '#b91c1c';
                                                }
                                            } else {
                                                $curr_selesai = false; $curr_lanjutkan = false; $curr_reset = 0;
                                                $bg = 'linear-gradient(135deg, #ebf8ff 0%, #bee3f8 100%)'; 
                                                $textColor = '#2b6cb0'; $subColor = '#2c5282';
                                            }
                                            $jam_ke = $jadwal->jam_ke == '0' ? '1' : $jadwal->jam_ke;
                                            ?>
                                            <div class="col-md-6 col-lg-5 mb-4">
                                                <div class="card h-100 border-0 p-4 text-center d-flex flex-column align-items-center" style="background: <?= $bg ?>; border-radius: 20px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.02);">
                                                    
                                                    <div class="d-flex flex-column align-items-center w-100 mb-3">
                                                        <span class="badge font-weight-bold px-4 py-1.5 mb-2" style="border-radius: 30px; background-color: rgba(255,255,255,0.6); color: <?= $textColor ?>; font-size:0.75rem;">JAM KE-<?= $jam_ke ?></span>
                                                        <?php if (!empty($jamJadwal[$jadwal->id_jadwal])) : ?>
                                                            <span class="font-weight-bold mb-1" style="color: <?= $textColor ?>; font-size: 1rem;"><i class="far fa-clock mr-1"></i><?= $jamJadwal[$jadwal->id_jadwal] ?></span>
                                                        <?php endif; ?>
                                                        <span class="small font-weight-bold" style="color: <?= $subColor ?>;"><i class="fas fa-hourglass-half mr-1"></i>Durasi <?= $jadwal->durasi_ujian ?> Mnt</span>
                                                    </div>
                                                    
                                                    <div class="mb-4 w-100">
                                                        <h4 class="font-weight-bold text-wrap mb-2 mx-auto" style="color: <?= $textColor ?>; max-width: 95%; line-height: 1.4; font-size: 1.35rem;"><?= $jadwal->nama_mapel ?></h4>
                                                        <p class="small font-weight-bold mb-0 text-uppercase" style="letter-spacing: 0.5px; color: <?= $subColor ?>; opacity: 0.8; font-size: 0.8rem;"><?= $jadwal->nama_jenis ?></p>
                                                    </div>

                                                    <div class="mt-auto w-100">
                                                        <?php if (!$curr_lanjutkan && $curr_reset == 0 && !$curr_selesai) : ?>
                                                            <?php if ($today < $startDay) : ?>
                                                                <div class="text-center p-2 font-weight-bold small rounded-lg" style="background-color: rgba(0,0,0,0.04); color: <?= $subColor ?>;">BELUM DIMULAI</div>
                                                            <?php elseif ($today > $endDay) : ?>
                                                                <div class="text-center p-2 font-weight-bold small rounded-lg" style="background-color: rgba(0,0,0,0.04); color: #e53e3e;">SUDAH BERAKHIR</div>
                                                            <?php else: ?>
                                                                <?php if ($now < strtotime($sesiMulai->format('H:i'))) : ?>
                                                                    <div class="text-center p-2 font-weight-bold small rounded-lg" style="background-color: rgba(0,0,0,0.04); color: <?= $subColor ?>;"><?= strtoupper($cbt_info->nama_sesi ?? '') ?> BELUM MULAI</div>
                                                                <?php elseif ($now > strtotime($sesiSampai->format('H:i'))) : ?>
                                                                    <div class="text-center p-2 font-weight-bold small rounded-lg" style="background-color: rgba(0,0,0,0.04); color: #e53e3e;"><?= strtoupper($cbt_info->nama_sesi ?? '') ?> BERAKHIR</div>
                                                                <?php else : ?>
                                                                    <?php if (isset($jadwal_selesai[$jadwal->tgl_mulai][$jadwal->jam_ke - 1]) && $jadwal_selesai[$jadwal->tgl_mulai][$jadwal->jam_ke - 1] == false) : ?>
                                                                        <button class="btn btn-secondary btn-block btn-modern opacity-50" disabled>MENUNGGU</button>
                                                                    <?php else : ?>
                                                                        <button onclick="location.href='<?= base_url('siswa/konfirmasi/' . $jadwal->id_jadwal) ?>'" class="btn btn-block btn-modern text-white shadow-sm" style="background-color: #3182ce; border:none;">KERJAKAN <i class="fas fa-arrow-right ml-2 small"></i></button>
                                                                    <?php endif; endif; endif; ?>
                                                        <?php elseif ($curr_lanjutkan && !$curr_selesai) : ?>
                                                            <button class="btn btn-block btn-modern text-white shadow-sm d-flex align-items-center justify-content-center mx-auto" style="background-color: #4f46e5; border: none; max-width: 85%;" onclick="location.href='<?= base_url('siswa/konfirmasi/' . $jadwal->id_jadwal) ?>'">
                                                                <span>LANJUTKAN</span>
                                                                <i class="fas fa-play ml-2" style="font-size: 0.75rem;"></i>
                                                            </button>
                                                        <?php else : ?>
                                                            <div class="text-center p-2 font-weight-bold bg-white text-success rounded-lg small shadow-sm mx-auto" style="border: 1px solid #a7f3d0; max-width: 85%;"><i class="fas fa-check-circle mr-1"></i> SUDAH SELESAI</div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php
                                        endforeach;
                                    else: ?>
                                        <div class="col-12"><div class="alert text-center border-0 p-4 font-weight-bold mb-0" style="background-color: #f7fafc; color:#a0aec0;"><i class="far fa-calendar-times fa-2xl d-block mb-2"></i>Tidak ada jadwal penilaian untuk hari ini.</div></div>
                                    <?php endif;
                                endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card custom-card bg-white">
                        <div class="card-header bg-white border-0 pt-4 pb-2 text-center">
                            <h5 class="font-weight-bold text-dark mb-0" style="letter-spacing: -0.3px;">RIWAYAT JADWAL SEBELUMNYA</h5>
                        </div>
                        <div class="card-body p-0 table-responsive">
                            <table class="table table-hover table-modern align-middle mb-0">
                                <?php
                                if (count($jadwalRiwayat) > 0) :
                                    foreach ($jadwalRiwayat as $tgl => $jadwals)  : ?>
                                        <thead>
                                            <tr>
                                                <th colspan="4" class="text-center py-3 text-dark font-weight-bold" style="background-color: #f7fafc; border-top: 1px solid #edf2f7;">
                                                    <i class="far fa-calendar-check mr-2 text-primary"></i><?= buat_tanggal(date('D, d M Y', strtotime($tgl))) ?>
                                                </th>
                                            </tr>
                                            <tr class="bg-white text-center">
                                                <th class="text-center border-0" style="width: 15%;">Sesi/Jam</th>
                                                <th class="text-center border-0" style="width: 45%;">Mata Pelajaran</th>
                                                <th class="text-center d-none d-md-table-cell border-0" style="width: 25%;">Jenis Penilaian</th>
                                                <th class="text-center border-0" style="width: 15%;">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        foreach ($jadwals as $key => $jadwal)  :
                                            $jam_ke = $jadwal->jam_ke == '0' ? '1' : $jadwal->jam_ke;
                                            $startDay = strtotime($jadwal->tgl_mulai);
                                            $endDay = strtotime($jadwal->tgl_selesai);
                                            $today = strtotime(date('Y-m-d'));

                                            $waktuMulaiSesiPrev = isset($sesi[$jamSesi]['mulai']) ? $sesi[$jamSesi]['mulai'] : date('H:i');
                                            $waktuAkhirSesiPrev = isset($sesi[$jamSesi]['akhir']) ? $sesi[$jamSesi]['akhir'] : date('H:i');

                                            $sesiMulai = new DateTime($waktuMulaiSesiPrev);
                                            $sesiSampai = new DateTime($waktuAkhirSesiPrev);
                                            $now = strtotime(date('H:i'));

                                            $durasi = isset($elapsed[$jadwal->id_jadwal]) ? $elapsed[$jadwal->id_jadwal] : null;
                                            $jadwal_selesai[$jadwal->tgl_mulai][$jadwal->jam_ke] = $durasi != null ? $durasi->status == '2' : false;

                                            if ($durasi != null) {
                                                $curr_selesai = isset($durasi->selesai) && $durasi->selesai != null;
                                                $curr_lanjutkan = isset($durasi->lama_ujian) && $durasi->lama_ujian != null;
                                                $curr_reset = isset($durasi->reset) ? $durasi->reset : 0;
                                            } else {
                                                $curr_selesai = false; $curr_lanjutkan = false; $curr_reset = 0;
                                            }

                                            $status = '';
                                            if (!$curr_lanjutkan && $curr_reset == 0 && !$curr_selesai) {
                                                if ($today < $startDay) {
                                                    $status = '<span class="badge p-2 px-3 text-muted" style="background:#edf2f7; border-radius:6px;">Belum Mulai</span>';
                                                } elseif ($today > $endDay) {
                                                    $status = '<span class="badge p-2 px-3 text-muted" style="background:#edf2f7; border-radius:6px;">Berakhir</span>';
                                                } else {
                                                    if ($now < strtotime($sesiMulai->format('H:i'))) {
                                                        $status = '<span class="badge p-2 px-3 text-muted" style="background:#edf2f7; border-radius:6px;">Sesi Belum Mulai</span>';
                                                    } elseif ($now > strtotime($sesiSampai->format('H:i'))) {
                                                        $status = '<span class="badge badge-soft-danger p-2 px-3" style="background:#fff5f5; color:#c53030; border-radius:6px;">Sesi Berakhir</span>';
                                                    } else {
                                                        if (isset($jadwal_selesai[$jadwal->tgl_mulai][$jadwal->jam_ke - 1]) && $jadwal_selesai[$jadwal->tgl_mulai][$jadwal->jam_ke - 1] == false) {
                                                            $status = '<button class="btn btn-xs btn-secondary opacity-50" disabled>Menunggu</button>';
                                                        } else {
                                                            $status = '<button onclick="location.href=\'' . base_url() . 'siswa/konfirmasi/' . $jadwal->id_jadwal . '\'" class="btn btn-xs btn-primary font-weight-bold px-3" style="border-radius:6px;">Kerjakan</button>';
                                                        }
                                                    }
                                                }
                                            } elseif ($curr_lanjutkan && !$curr_selesai) {
                                                $status = '<button class="btn btn-xs btn-indigo text-white font-weight-bold px-3" style="background-color:#4f46e5; border-radius:6px;" onclick="location.href=\'' . base_url() . 'siswa/konfirmasi/' . $jadwal->id_jadwal . '\'">Lanjutkan</button>';
                                            } else {
                                                $status = '<span class="text-success small font-weight-bold"><i class="fas fa-check-circle mr-1"></i>Selesai</span>';
                                            } ?>
                                            <tr class="text-center">
                                                <td class="text-center align-middle font-weight-bold text-dark">
                                                    Ke-<?= $jam_ke ?><br><small class="text-muted font-weight-normal"><?= $jadwal->durasi_ujian ?> mnt</small>
                                                </td>
                                                <td class="align-middle text-dark font-weight-bold text-center">
                                                    <?= $jadwal->nama_mapel ?>
                                                    <small class="d-block d-md-none text-muted text-uppercase mt-1 font-weight-bold" style="font-size:0.7rem; letter-spacing:0.5px;"><?= $jadwal->nama_jenis ?></small>
                                                </td>
                                                <td class="d-none d-md-table-cell align-middle text-secondary small font-weight-bold text-center"><?= $jadwal->nama_jenis ?></td>
                                                <td class="align-middle text-center"><?= $status ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                    <?php endforeach;
                                else: ?>
                                    <tbody>
                                        <tr>
                                            <td colspan="4" class="text-center p-4 text-muted font-weight-bold"><i class="far fa-folder-open mr-2"></i>Tidak ada riwayat jadwal sebelumnya.</td>
                                        </tr>
                                    </tbody>
                                <?php endif; ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>