<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper bg-light">
    <!-- Enhanced Header Banner with modern overlay -->
    <section class="content-header p-0 d-flex align-items-end position-relative"
             style="height: 320px; background: url('<?= base_url('assets/img/wall2.png') ?>') center/cover no-repeat;">
        <div class="position-absolute w-100 h-100" style="background: linear-gradient(to bottom, rgba(0,0,0,0.2), rgba(0,0,0,0.6));"></div>
        
        <div class="container-fluid p-4 position-relative w-100">
            <div class="row m-0 justify-content-center">
                <?php foreach ($info_box as $info) : ?>
                    <div class="col-md-2 col-6 mb-3 mb-md-0">
                        <div class="card card-body border-0 shadow-sm rounded-lg mb-0 bg-white text-dark h-100 p-3 position-relative overflow-hidden">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h3 class="mb-0 font-weight-bold text-dark"><?= $info->total; ?></h3>
                                    <span class="text-muted text-sm"><?= $info->title; ?></span>
                                </div>
                                <div class="text-<?= $info->box ?> opacity-75">
                                    <i class="fa fa-<?= $info->icon ?>" style="font-size:32px;"></i>
                                </div>
                            </div>
                            <div class="position-absolute" style="bottom: 0; left: 0; right: 0; height: 4px; background-color: var(--<?= $info->box == 'primary' ? 'blue' : ($info->box == 'success' ? 'green' : ($info->box == 'warning' ? 'yellow' : 'red')) ?>);"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="content mt-4">
        <div class="container-fluid">
            <div class="row">
                <!-- Left Column -->
                <div class="col-md-4">
                    <!-- Jadwal Card (Clean Outline Style) -->
                    <div class="card card-outline card-primary shadow-sm border-0 mb-4">
                        <div class="card-header bg-white border-0 pt-3 pb-0 d-flex justify-content-between align-items-center">
                            <h5 class="card-title font-weight-bold text-secondary">
                                <i class="fa fa-calendar-check-o mr-2 text-primary"></i>JADWAL HARI INI
                            </h5>
                            <a href="<?= base_url('kelasjadwal') ?>" class="btn btn-sm btn-light rounded-circle shadow-sm">
                                <i class="fa fa-arrow-right text-muted"></i>
                            </a>
                        </div>
                        <div class="card-body px-0 pt-2">
                            <div class="card border-0 shadow-none m-0">
                                <div class="card-header d-flex p-0 border-0 bg-transparent px-3">
                                    <ul class="nav nav-pills gap-2 pb-2">
                                        <?php
                                        $no = 1;
                                        foreach ($kelases as $ky => $kelas) :
                                            $active = $no == 1 ? 'active bg-primary text-white' : 'bg-light text-muted';
                                            ?>
                                            <li class="nav-item mr-1 mb-1">
                                                <a class="nav-link rounded-pill text-xs font-weight-bold shadow-sm <?= $active ?>"
                                                   href="#tab_<?= $ky ?>" data-toggle="tab"><?= $kelas ?></a>
                                            </li>
                                            <?php $no++; endforeach; ?>
                                    </ul>
                                </div>
                                
                                <?php if (count($jadwals) > 0 && count($kbms) > 0): ?>
                                    <div class="card-body p-0 mt-2">
                                        <div class="tab-content">
                                            <?php
                                            $no = 1;
                                            foreach ($kelases as $ky => $kelas) :
                                                $arrIst = [];
                                                if (isset($kbms[$ky]->istirahat)) {
                                                    foreach ($kbms[$ky]->istirahat as $istirahat) {
                                                        array_push($arrIst, $istirahat['ist']);
                                                        $arrDur[$istirahat['ist']] = $istirahat['dur'];
                                                    }
                                                }
                                                $active = $no == 1 ? 'active' : '';
                                                ?>
                                                <div class="tab-pane <?= $active ?>" id="tab_<?= $ky ?>">
                                                    <?php if (isset($kbms[$ky])) : ?>
                                                        <div class="table-responsive">
                                                            <table class="table table-hover mb-0">
                                                                <thead class="bg-light text-xs text-muted">
                                                                    <tr>
                                                                        <th class="pl-4 border-0">WAKTU</th>
                                                                        <th class="border-0">MATA PELAJARAN / AGENDA</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                <?php
                                                                $jamMulai = new DateTime($kbms[$ky]->kbm_jam_mulai);
                                                                $jamSampai = new DateTime($kbms[$ky]->kbm_jam_mulai);
                                                                for ($i = 0; $i < $kbms[$ky]->kbm_jml_mapel_hari; $i++) :
                                                                    $jamke = $i + 1;
                                                                    if (in_array($jamke, $arrIst)) :
                                                                        $jamSampai->add(new DateInterval('PT' . $arrDur[$jamke] . 'M'));
                                                                        ?>
                                                                        <tr class="table-warning opacity-75">
                                                                            <td class="align-middle pl-4 text-xs font-weight-bold text-warning">
                                                                                <i class="fa fa-coffee mr-2"></i><?= $jamMulai->format('H:i') ?> - <?= $jamSampai->format('H:i') ?>
                                                                            </td>
                                                                            <td class="align-middle font-weight-bold text-xs text-warning">ISTIRAHAT</td>
                                                                        </tr>
                                                                        <?php
                                                                        $jamMulai->add(new DateInterval('PT' . $arrDur[$jamke] . 'M'));
                                                                    else :
                                                                        $jamSampai->add(new DateInterval('PT' . $kbms[$ky]->kbm_jam_pel . 'M'));
                                                                        ?>
                                                                        <tr>
                                                                            <td class="align-middle pl-4 text-sm font-weight-bold text-secondary">
                                                                                <i class="fa fa-clock-o mr-2 text-light"></i><?= $jamMulai->format('H:i') ?> - <?= $jamSampai->format('H:i') ?>
                                                                            </td>
                                                                            <td class="align-middle font-weight-bold text-dark">
                                                                                <?= $jadwals[$ky][$jamke]->kode != null ? '<span class="badge badge-subtle-primary p-2">'.$jadwals[$ky][$jamke]->kode.'</span>' : '<span class="text-muted">--</span>' ?>
                                                                            </td>
                                                                        </tr>
                                                                        <?php
                                                                        $jamMulai->add(new DateInterval('PT' . $kbms[$ky]->kbm_jam_pel . 'M'));
                                                                    endif;
                                                                endfor; ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="p-4 text-center text-muted">
                                                            <i class="fa fa-info-circle fa-2x mb-2 text-light"></i>
                                                            <p class="mb-0">Jadwal untuk kelas <?= $kelas ?> belum dibuat</p>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <?php $no++; endforeach; ?>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="card-body text-center text-muted py-4">
                                        <i class="fa fa-calendar-times-o fa-2x mb-2 text-light"></i>
                                        <p class="mb-0">Tidak ada jadwal hari ini</p>
                                    </div>
                                <?php endif; ?>
							</div>
                        </div>
                    </div>

                    <!-- Aktivitas Card -->
                    <div class="card card-outline card-secondary shadow-sm border-0 mb-4">
                        <div class="card-header bg-white border-0 pt-3">
                            <h5 class="card-title font-weight-bold text-secondary">
                                <i class="fa fa-history mr-2 text-secondary"></i>Aktivitas Terbaru
                            </h5>
                        </div>
                        <div class="card-body p-0" style="max-height: 380px; overflow-y: auto;">
                            <div id="log-list" class="px-3"></div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-md-8">
                    <!-- Penilaian Card -->
                    <div class="card card-outline card-danger shadow-sm border-0 mb-4">
                        <div class="card-header bg-white border-0 pt-3">
                            <h5 class="card-title font-weight-bold text-danger">
                                <i class="fa fa-file-text-o mr-2"></i>Sistem Penilaian
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-stretch">
                                <?php foreach ($ujian_box as $info) : ?>
                                    <div class="col-md-4 col-6 mb-3">
                                        <div class="card h-100 border bg-light shadow-none rounded p-2 mb-0">
                                            <div class="text-center py-2">
                                                <span class="text-muted text-xs text-uppercase tracking-wider"><?= $info->title; ?></span>
                                                <h4 class="mb-0 font-weight-bold text-dark mt-1"><?= $info->total; ?></h4>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                                
                                <!-- Modern Dynamic Token Card Box -->
                                <div class="col-md-4 col-12 mb-3">
                                    <div class="card h-100 border border-danger shadow-none rounded p-2 mb-0 bg-light">
                                        <div class="text-center py-2 position-relative">
                                            <span class="text-danger text-xs font-weight-bold text-uppercase">TOKEN AKTIF</span>
                                            <div class="d-flex align-items-center justify-content-center mt-1">
                                                <h4 class="mb-0 font-weight-bold text-danger tracking-widest mr-2" id="token-view">
                                                    <?= $token->token != null ? $token->token : '- - - - - -' ?>
                                                </h4>
                                                <button class="btn btn-xs btn-outline-danger rounded-circle d-none" id="refresh-token" style="width: 24px; height:24px; padding:0;">
                                                    <i class="fa fa-refresh" style="font-size: 10px"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <hr class="my-4 border-light">
                            
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <h6 class="text-secondary font-weight-bold text-center tracking-wide">
                                        <i class="fa fa-list-alt mr-2 text-danger"></i>DAFTAR PENILAIAN HARI INI
                                    </h6>
                                </div>
                                <div class="col-12 table-responsive">
                                    <?php
                                    $no = 1;
                                    $jadwal_ujian = $jadwals_ujian[date('Y-m-d')] ?? [];
                                    if (count($jadwal_ujian) > 0) : ?>
                                        <table id="tbl-penilaian" class="table table-striped table-bordered text-sm text-center">
                                            <thead class="bg-danger text-white text-xs">
                                                <tr>
                                                    <th class="align-middle py-3" style="width: 5%">NO</th>
                                                    <th class="align-middle py-3">RUANG</th>
                                                    <th class="align-middle py-3">SESI</th>
                                                    <th class="align-middle py-3">MATA PELAJARAN</th>
                                                    <th class="align-middle py-3" style="width: 35%">PENGAWAS</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            foreach ($ruangs as $ruang => $sesis) :
                                                foreach ($sesis as $sesi) :
                                                    foreach ($jadwal_ujian as $jadwal) :
                                                        $id_guru = isset($pengawas[$jadwal[0]->id_jadwal])
                                                        && isset($pengawas[$jadwal[0]->id_jadwal][$ruang]) &&
                                                        isset($pengawas[$jadwal[0]->id_jadwal][$ruang][$sesi->sesi_id])
                                                            ? explode(',', $pengawas[$jadwal[0]->id_jadwal][$ruang][$sesi->sesi_id]->id_guru ?? '')
                                                            : [];

                                                        $badge_kelas = '';
                                                        $total_peserta = 0;
                                                        foreach ($jadwal as $jdw) {
                                                            $bank_kelass = $jdw->bank_kelas;
                                                            foreach ($bank_kelass as $bank_kelas) {
                                                                foreach ($jdw->peserta as $peserta) {
                                                                    $cnt = isset($peserta[$ruang]) && isset($peserta[$ruang][$sesi->sesi_id]) ?
                                                                        count($peserta[$ruang][$sesi->sesi_id]) : 0;
                                                                    if ($bank_kelas['kelas_id'] != null && $cnt > 0) {
                                                                        $total_peserta += $cnt;
                                                                        $badge_kelas .= ' <span class="badge badge-info">' . $kelases[$bank_kelas['kelas_id']] . ' ' . $cnt . ' siswa</span>';
                                                                    }
                                                                }
                                                            }
                                                        }

                                                        if ($total_peserta > 0) :
                                                            ?>
                                                            <tr>
                                                                <td class="align-middle font-weight-bold text-muted"><?= $no ?></td>
                                                                <td class="align-middle font-weight-bold text-dark"><?= $sesi->nama_ruang ?></td>
                                                                <td class="align-middle"><span class="badge badge-secondary p-1 px-2 font-weight-normal"><?= $sesi->nama_sesi ?></span></td>
                                                                <td class="align-middle font-weight-bold text-primary"><?= $jadwal[0]->kode ?></td>
                                                                <td class="align-middle text-left pl-3 text-muted">
                                                                    <?php foreach ($id_guru as $ig) {
                                                                        echo isset($gurus[$ig]) ? '<div class="d-flex align-items-center mb-1"><i class="fa fa-user-circle text-light mr-2"></i>' . $gurus[$ig] . '</div>' : '';
                                                                    } ?>
                                                                </td>
                                                            </tr>
                                                        <?php endif; endforeach; endforeach;
                                                $no++; endforeach;?>
                                            </tbody>
                                        </table>
                                    <?php else: ?>
                                        <div class="card card-body text-center py-4 border text-muted">
                                            <i class="fa fa-exclamation-circle fa-2x mb-2 text-light"></i>
                                            Tidak ada jadwal penilaian untuk hari ini.
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pengumuman Card -->
                    <div class="card card-outline card-light shadow-sm border-0 mb-3">
                        <div class="card-header bg-white border-0 pt-3">
                            <h5 class="card-title font-weight-bold text-secondary">
                                <i class="fa fa-bullhorn mr-2 text-warning"></i>INFO & PENGUMUMAN
                            </h5>
                        </div>
                        <div class="card-body pt-0">
                            <div class="konten-pengumuman">
                                <div id="pengumuman"></div>
                                <p id="loading-post" class="text-center d-none py-3 text-muted">
                                    <i class="fa fa-spin fa-circle-o-notch mr-2"></i>Memuat data....
                                </p>
                                <div id="loadmore-post" onclick="getPosts()" class="text-center mt-4 d-none">
                                    <button class="btn btn-outline-primary btn-sm rounded-pill px-4">Muat Pengumuman Lainnya</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
</div>