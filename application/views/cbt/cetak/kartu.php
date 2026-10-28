<?php $kpCss = '.kp-card{box-sizing:border-box;width:10cm;height:6.4cm;background:#fff !important;color:#000 !important;border:1.5px solid #1f3a6e;border-radius:8px;overflow:hidden;display:flex;flex-direction:column;font-family:Arial,Helvetica,sans-serif;text-align:left;-webkit-print-color-adjust:exact;print-color-adjust:exact;page-break-inside:avoid}.kp-card *{box-sizing:border-box}.kp-strip{flex:none;height:5px;background:#1f3a6e !important}.kp-head{flex:none;width:100%;border-collapse:collapse;border-bottom:2px solid #1f3a6e}.kp-head td{padding:3px 4px;vertical-align:middle}.kp-logo{width:19%;text-align:center}.kp-logo img{width:15mm;height:15mm;object-fit:contain;margin:0}.kp-title{width:62%;text-align:center;line-height:1.1}.kp-title .h1,.kp-title .h2,.kp-title .h3,.kp-title .h4{margin:0 !important;padding:0 !important;line-height:1.1 !important}.kp-title .h1{font-size:8.5pt}.kp-title .h2{font-size:10.5pt;font-weight:bold}.kp-title .h3,.kp-title .h4{font-size:7.5pt}.kp-body{flex:1;display:flex;gap:8px;padding:4px 8px 2px 8px;min-height:0}.kp-foto{flex:none;width:22mm;position:relative}.kp-foto img{width:22mm;height:29mm;object-fit:cover;object-position:center;border:1px solid #333;display:block}.kp-main{flex:1;display:flex;flex-direction:column;justify-content:space-between;min-width:0}.kp-data{border-collapse:collapse;font-size:9pt;line-height:1.25}.kp-data td{padding:0 2px 0 0;vertical-align:top}.kp-data td:first-child{width:25mm;white-space:nowrap}.kp-data td:nth-child(2){width:3mm;text-align:center}.kp-cam{position:absolute;right:2px;bottom:2px;width:16px;height:16px;border-radius:50%;background:rgba(31,58,110,.9);color:#fff;font-size:9px;line-height:16px;text-align:center}@media print{.kp-cam{display:none !important}}.kp-ttd{align-self:flex-end;width:46mm;text-align:center;font-size:7.5pt;line-height:1.15;background-repeat:no-repeat;background-position:center;background-size:auto 34px}.kp-ttd .sp{display:block;height:18px}.kp-ttd .nm{font-weight:bold;text-decoration:underline}.kp-foot{flex:none;background:#e8eef8 !important;color:#1f3a6e;text-align:center;font-size:6pt;font-style:italic;padding:2px 0;border-top:1px solid #1f3a6e}'; ?>
<style><?= $kpCss ?></style>
<div class="content-wrapper bg-white pt-4">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-6">
                    <h1><?= $judul ?></h1>
                </div>
                <div class="col-6">
                    <a href="<?= base_url('cbtcetak') ?>" type="button" class="btn btn-sm btn-danger float-right">
                        <i class="fas fa-arrow-circle-left"></i><span
                                class="d-none d-sm-inline-block ml-1">Kembali</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-5">
                    <div class="card my-shadow card-primary card-outline">
                        <div class="card-header">
                            <h6 class="card-title">Setting Kartu</h6>
                            <button class="card-tools btn btn-sm bg-primary text-white" onclick="submitKartu()">
                                <i class="fas fa-save mr-1"></i> Simpan
                            </button>
                        </div>
                        <div class="card-body">
                            <?= form_open('', array('id' => 'set-kartu')) ?>
                            <div class="form-group">
                                <label>Header 1</label>
                                <textarea id="header-1" class="form-control" name="header_1" rows="2"
                                          placeholder="Header baris 1"
                                          required><?= isset($kartu->header_1) ? $kartu->header_1 : '' ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Header 2</label>
                                <textarea id="header-2" class="form-control" name="header_2" rows="2"
                                          placeholder="Header baris 2"
                                          required><?= isset($kartu->header_2) ? $kartu->header_2 : '' ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Header 3</label>
                                <textarea id="header-3" class="form-control" name="header_3" rows="2"
                                          placeholder="Header baris 3"
                                          required><?= isset($kartu->header_3) ? $kartu->header_3 : '' ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Header 4</label>
                                <textarea id="header-4" class="form-control" name="header_4" rows="2"
                                          placeholder="Header baris 4"
                                          required><?= isset($kartu->header_4) ? $kartu->header_4 : '' ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Tanggal</label>
                                <input id="input-tanggal" class="form-control" name="tanggal" placeholder="Titimangsa"
                                       value="<?= isset($kartu->tanggal) ? $kartu->tanggal : '' ?>" required>
                            </div>
                            <?= form_close() ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="card my-shadow">
                        <div class="card-header">
                            <div class="card-title">
                                Preview
                            </div>
                        </div>
                        <div class="card-body pb-4">
                            <div style="display: flex; justify-content: center; align-items: center;">
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
                            <br>
                        </div>
                    </div>
                    <div class="card my-shadow">
                        <div class="card-header">
                            <div class="card-title">
                                Cetak
                            </div>
                        </div>
                        <div class="card-body pb-4">
                            <div class="row">
                                <div class="col-8">
                                    <div class="input-group">
                                        <?php
                                        echo form_dropdown(
                                            'kelas',
                                            $kelas,
                                            null,
                                            'id="kelas" class="form-control"'
                                        ); ?>
                                    </div>
                                    <div class="input-group">
                                        <?php
                                        echo form_dropdown(
                                            'ruang',
                                            $ruang,
                                            null,
                                            'id="ruang" class="form-control d-none"'
                                        ); ?>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <button class="btn bg-success text-white" id="btn-print">
                                        <i class="fa fa-print"></i><span class="ml-1">Cetak</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card my-shadow">
                        <div class="card-header">
                            <div class="card-title">
                                Preview
                            </div>
                        </div>
                        <div class="card-body pb-4">
                            <div class="d-flex justify-content-center bg-gray-light" style="min-height: 300mm">
                                <div id="print-preview" class="m-2">
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