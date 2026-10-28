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
    @keyframes live-pulse {
        0% { background-color: rgba(40, 167, 69, 0.3); }
        50% { background-color: rgba(40, 167, 69, 0.1); }
        100% { background-color: transparent; }
    }
    .score-updated {
        animation: live-pulse 2s ease-in-out;
    }
    @keyframes blinker {
        50% { opacity: 0.4; }
    }
    .live-blink {
        animation: blinker 1.5s linear infinite;
    }
</style>

<div class="content-wrapper bg-white">
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