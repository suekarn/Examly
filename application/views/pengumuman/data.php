<?php
/**
 * Created by IntelliJ IDEA.
 * User: multazam
 * Date: 07/07/20
 * Time: 17:20
 */
?>

<style>
    .modern-card {
        border: 1px solid #eef2f5 !important;
        border-radius: 16px !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05) !important;
        overflow: hidden;
    }
    .modern-card .card-header {
        background-color: #fff;
        border-bottom: 1px solid #f1f5f9;
        font-weight: 700;
        color: #334155;
        padding: 16px 20px;
    }
    .announcement-item {
        border: 1px solid #f1f5f9;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 16px;
        background-color: #fff;
        transition: transform 0.2s;
    }
    .announcement-item:hover {
        transform: translateY(-2px);
    }
    .avatar-admin {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.1rem;
    }
    .avatar-img {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e2e8f0;
    }
    .comment-bubble {
        border-radius: 16px !important;
        background-color: #f8fafc !important;
        padding: 10px 14px !important;
        border: none !important;
    }
    .reply-bubble {
        border-radius: 16px !important;
        background-color: #f1f5f9 !important;
        padding: 10px 14px !important;
        border: none !important;
    }
    .btn-action-sm {
        font-size: 0.85rem !important;
        color: #64748b !important;
        font-weight: 500;
        padding: 6px 12px;
        border-radius: 8px;
        transition: all 0.2s;
    }
    .btn-action-sm:hover {
        background-color: #f1f5f9;
        color: #1e293b !important;
    }
    .btn-action-delete:hover {
        background-color: #fef2f2;
        color: #ef4444 !important;
    }
    /* Optimasi Tampilan HP */
    @media (max-width: 576px) {
        .content-wrapper { padding-top: 1rem !important; }
        .modern-card .card-header { padding: 12px 16px; }
        .announcement-item { padding: 12px; }
        .btn-action-sm { padding: 4px 8px; font-size: 0.8rem !important; }
    }
</style>

<div class="content-wrapper bg-light pt-4">
    <section class="content">
        <div class="container">
            <div class="row">
                
                <?php
                $col = '';
                if ($this->ion_auth->is_admin()) :
                    $col = 'col-md-7';
                    ?>
                    <div class="col-12 col-md-5 mb-4">
                        <div class="card modern-card">
                            <div class="card-header d-flex align-items-center">
                                <i class="fas fa-bullhorn text-primary mr-2"></i> Running Text Berjalan
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info border-0 shadow-sm mb-3" style="border-radius: 10px; background-color: #e0f2fe; color: #0369a1;">
                                    <i class="fas fa-info-circle mr-1"></i> Running Text akan muncul di bagian bawah layar siswa.
                                </div>

                                <div class="table-responsive">
                                    <table id="tb-text" class="table table-hover border-0 mb-3">
                                        <tbody>
                                        <?php
                                        for ($i = 0; $i < 5; $i++) :
                                            $text = isset($running_text[$i]) ? $running_text[$i]->text : '';
                                            ?>
                                            <tr style="background: transparent;">
                                                <td width="60" class="text-center align-middle" style="border-top: 1px solid #f1f5f9;">
                                                    <span class="badge badge-pill badge-secondary p-2 d-block" style="font-size: 0.9rem; font-weight: 600;"><?= $i + 1 ?></span>
                                                </td>
                                                <td class="text-sm editable p-3 align-middle" style="border-top: 1px solid #f1f5f9; background: #f8fafc; border-radius: 8px; min-height: 40px; display: block; margin-bottom: 4px; border: 1px solid #e2e8f0;"><?= $text ?></td>
                                            </tr>
                                        <?php endfor; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?= form_open('', array('id' => 'formrunningtext')) ?>
                                <button type="submit" class="btn btn-primary btn-block shadow-sm" style="border-radius: 10px;">
                                    <i class="fa fa-save mr-1"></i> Simpan Running Text
                                </button>
                                <?= form_close(); ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="col-12 <?= $col ?> mb-4">
                    <div class="card modern-card">
                        <?= form_open('', array('id' => 'formpengumuman')) ?>
                        <div class="card-header d-flex align-items-center">
                            <i class="fas fa-pen-nib text-success mr-2"></i> Buat Info / Pengumuman Baru
                        </div>
                        <div class="card-body">
                            <div class="form-group row mb-3">
                                <label class="col-sm-2 col-form-font font-weight-bold text-muted align-middle">Kepada:</label>
                                <div class="col-sm-10">
                                    <select id="opsi-kepada" name="kepada[]" class="select2 form-control" multiple="multiple" data-placeholder=" Pilih Penerima Pengumuman" required style="width: 100%;">
                                        <option value='guru'>Semua Guru</option>
                                        <option value='siswa'>Semua Siswa</option>
                                        <?php foreach ($kelas as $key => $value) : ?>
                                            <option class="opsi-kelas" value="<?= $key ?>"><?= $value ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group mb-3">
                                <textarea class="w-100" id="text-pengumuman" name="text" required></textarea>
                            </div>

                            <?php if ($this->ion_auth->is_admin()): ?>
                                <input type="hidden" name="dari" value="0">
                            <?php else: ?>
                                <input type="hidden" name="dari" value="<?= $guru->id_guru ?>">
                            <?php endif; ?>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-success px-4 shadow-sm" style="border-radius: 10px;">
                                    <i class="fa fa-paper-plane mr-1"></i> Bagikan Pengumuman
                                </button>
                            </div>
                        </div>
                        <?= form_close(); ?>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card modern-card">
                        <div class="card-header bg-white">
                            <h5 class="m-0 font-weight-bold text-dark"><i class="fas fa-stream mr-2 text-muted"></i><?= $subjudul ?></h5>
                        </div>
                        <div class="card-body bg-light-card p-3">
                            
                            <?php foreach ($pengumumans as $pengumuman) : ?>
                                <div class="announcement-item shadow-sm">
                                    <div class="card-body p-0" id="parent<?= $pengumuman->id_post ?>">
                                        
                                        <div class="media align-items-center mb-3">
                                            <?php if ($pengumuman->dari == '0') : ?>
                                                <div class="avatar-admin mr-3">A</div>
                                                <div class="media-body">
                                                    <h6 class="m-0 font-weight-bold text-dark">Admin System</h6>
                                                    <small class="text-muted"><i class="far fa-clock mr-1"></i><?= buat_tanggal(date('D, d M Y H:i', strtotime($pengumuman->tanggal))) ?></small>
                                                </div>
                                            <?php else: ?>
                                                <img class="avatar-img mr-3" src="<?= $pengumuman->foto != null ? base_url() . $pengumuman->foto : base_url('assets/img/siswa.png') ?>" alt="Foto Guru"/>
                                                <div class="media-body">
                                                    <h6 class="m-0 font-weight-bold text-dark"><?= $pengumuman->nama_guru ?></h6>
                                                    <small class="text-muted"><i class="far fa-clock mr-1"></i><?= $pengumuman->tanggal ?></small>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="text-dark my-3 px-1" style="font-size: 1.05rem; line-height: 1.6;">
                                            <?= $pengumuman->text ?>
                                        </div>
                                        
                                        <hr class="my-2" style="border-color: #f1f5f9;">

                                        <div class="d-flex justify-content-between flex-wrap">
                                            <div class="d-flex">
                                                <button type="button" class="btn btn-action-sm mr-2 btn-toggle" data-id="<?= $pengumuman->id_post ?>" data-toggle="modal" data-target="#komentarModal">
                                                    <i class="fas fa-comment-alt mr-1 text-primary"></i> Komentar
                                                </button>
                                                <button type="button" id="trigger<?= $pengumuman->id_post ?>" class="btn btn-action-sm action-collapse" data-toggle="collapse" aria-expanded="true" aria-controls="collapse-<?= $pengumuman->id_post ?>" href="#collapse-<?= $pengumuman->id_post ?>">
                                                    <i class="fa fa-comments mr-1 text-success"></i> <span><?= $pengumuman->jml ?></span> Komentar
                                                </button>
                                            </div>
                                            <div>
                                                <button type="button" class="btn btn-action-sm btn-action-delete" onclick="hapusPost(<?= $pengumuman->id_post ?>)" data-id="<?= $pengumuman->id_post ?>">
                                                    <i class="fa fa-trash-alt mr-1 text-danger"></i> Hapus
                                                </button>
                                            </div>
                                        </div>

                                    </div>

                                    <div id="collapse-<?= $pengumuman->id_post ?>" class="collapse toggle-comment mt-3 pt-2" style="border-top: 1px dashed #e2e8f0;" data-id="<?= $pengumuman->id_post ?>" data-parent="#parent<?= $pengumuman->id_post ?>">
                                        <div id="konten<?= $pengumuman->id_post ?>" class="pl-2 pl-md-4">
                                            </div>
                                        <div id="loading<?= $pengumuman->id_post ?>" class="text-center d-none my-3">
                                            <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                                        </div>
                                        <?php if ($pengumuman->jml == '0') : ?>
                                            <div class="text-center text-muted py-3" style="font-size: 0.9rem; font-style: italic;">Belum ada komentar. Jadilah yang pertama berkomentar!</div>
                                        <?php else: ?>
                                            <div id="loadmore<?= $pengumuman->id_post ?>" onclick="getComments(<?= $pengumuman->id_post ?>)" class="text-center mt-3 loadmore">
                                                <button class="btn btn-sm btn-outline-light text-primary border" style="border-radius: 20px;">Muat komentar lainnya...</button>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="komentarModal" tabindex="-1" role="dialog" aria-labelledby="komentarLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 16px; border: none;">
            <div class="modal-header" style="border-bottom: 1px solid #f1f5f9;">
                <h5 class="modal-title font-weight-bold" id="komentarLabel"><i class="fas fa-comment text-primary mr-2"></i>Tulis Komentar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body py-4">
                <div class="d-flex align-items-start">
                    <img class="avatar-img mr-3" src="<?= base_url('assets/img/siswa.png') ?>" alt="Avatar" style="width: 40px; height: 40px;">
                    <div class="flex-grow-1">
                        <?= form_open('create', array('id' => 'komentar')) ?>
                        <input type="hidden" id="id-post" name="id_post" value="">
                        <div class="form-group mb-3">
                            <textarea name="text" rows="3" placeholder="Ketik komentar Anda disini..." class="form-control" style="border-radius: 12px; resize: none;" required></textarea>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-light mr-2" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
                            <button type="submit" class="btn btn-success px-4" style="border-radius: 8px;">Kirim Komentar</button>
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
        <div class="modal-content" style="border-radius: 16px; border: none;">
            <div class="modal-header" style="border-bottom: 1px solid #f1f5f9;">
                <h5 class="modal-title font-weight-bold" id="balasanLabel"><i class="fas fa-reply text-success mr-2"></i>Tulis Balasan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body py-4">
                <div class="d-flex align-items-start">
                    <img class="avatar-img mr-3" src="<?= base_url('assets/img/siswa.png') ?>" alt="Avatar" style="width: 40px; height: 40px;">
                    <div class="flex-grow-1">
                        <?= form_open('create', array('id' => 'balasan')) ?>
                        <input type="hidden" id="id-comment" name="id_comment" value="">
                        <div class="form-group mb-3">
                            <textarea name="text" rows="3" placeholder="Ketik balasan Anda disini..." class="form-control" style="border-radius: 12px; resize: none;" required></textarea>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-light mr-2" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
                            <button type="submit" class="btn btn-success px-4" style="border-radius: 8px;">Kirim Balasan</button>
                        </div>
                        <?= form_close() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var guru = '<?=isset($guru) ? $guru->id_guru : ""?>';

    function createTime(d) {
        var date = new Date(d);
        var jam = date.getHours();
        var menit = date.getMinutes();
        var sJam = jam < 10 ? '0' + jam : '' + jam;
        var sMenit = menit < 10 ? '0' + menit : '' + menit;

        var hari = daysdifference(d);
        var time;

        if (hari === 0) {
            time = sJam + ':' + sMenit;
        } else if (hari === 1) {
            time = 'kemarin ' + sJam + ':' + sMenit;
        } else {
            time = jQuery.timeago(d) + ', ' + sJam + ':' + sMenit;
        }
        return time;
    }

    function daysdifference(last) {
        var startDay = new Date(last);
        var endDay = new Date();
        var millisBetween = startDay.getTime() - endDay.getTime();
        var days = millisBetween / (1000 * 3600 * 24);
        return Math.round(Math.abs(days));
    }

    function addComments(id, comments, append) {
        var comm = '';
        $.each(comments, function (i, v) {
            var dari = v.dari_group === '1' ? 'Admin' : (v.dari_group === '2' ? v.nama_guru : v.nama_siswa);
            var foto = v.dari_group === '2' ? (v.foto != null ? base_url + v.foto : base_url + 'assets/img/siswa.png') :
                (v.foto_siswa != null ? base_url + v.foto_siswa : base_url + 'assets/img/siswa.png');
            
            var avatar = v.dari == '0' ? 
                '<div class="avatar-admin mr-2 align-self-start shadow-sm" style="width: 36px; height: 36px; font-size:0.9rem;">A</div>' : 
                '<img class="avatar-img mr-2 align-self-start shadow-sm" src="' + foto + '" alt="Img" style="width: 36px; height: 36px;">';
            
            comm += '<div class="media mt-3" id="parent-reply' + v.id_comment + '">'
                + avatar +
                '    <div class="w-100">' +
                '        <div class="media-body comment-bubble shadow-sm w-100">' +
                '            <span class="text-xs font-weight-bold text-dark d-block mb-1">' + dari + '</span>' +
                '            <div class="comment-text text-sm text-secondary">' + v.text + '</div>' +
                '        </div>' +
                '        <div class="ml-2 mt-1 d-flex align-items-center flex-wrap">' +
                '            <small class="text-muted mr-3"><i class="far fa-clock mr-1"></i>' + createTime(v.tanggal) + '</small>' +
                '            <span id="trigger-reply' + v.id_comment + '" class="btn btn-sm mr-2 text-primary action-collapse p-0 font-weight-bold style="font-size:0.8rem;" data-toggle="collapse" aria-expanded="true"' +
                '                              aria-controls="collapse-reply' + v.id_comment + '"' +
                '                              href="#collapse-reply' + v.id_comment + '"><i class="far fa-comment-dots mr-1"></i>' + v.jml + ' balasan</span>' +
                '            <span class="btn btn-sm mr-2 text-success btn-toggle-reply p-0 font-weight-bold" style="font-size:0.8rem;"' +
                '                  data-id="' + v.id_comment + '" data-toggle="modal" data-target="#balasanModal">' +
                '                <i class="fas fa-reply mr-1"></i>Balas</span>' +
                '            <span class="btn btn-sm text-danger p-0 font-weight-bold" style="font-size:0.8rem;" onclick="hapusKomentar(' + v.id_comment + ')" data-id="' + v.id_comment + '"><i class="fa fa-trash-alt mr-1"></i>Hapus</span>' +
                '        </div>' +
                '        <div id="collapse-reply' + v.id_comment + '" class="p-2 collapse toggle-reply ml-3 mt-2" style="border-left: 2px solid #e2e8f0;" data-id="' + v.id_comment + '" data-parent="#parent-reply' + v.id_comment + '">';
            if (v.jml != '0') {
                comm += '<div id="konten-reply' + v.id_comment + '"></div>' +
                    '<div id="loadmore-reply' + v.id_comment + '" onclick="getReplies(' + v.id_comment + ')" class="text-center my-2 loadmore-reply">' +
                    '       <button class="btn btn-xs btn-outline-light text-primary border">Muat balasan lainnya...</button>' +
                    '</div>';
            }
            comm += '    <div id="loading-reply' + v.id_comment + '" class="text-center d-none">' +
                '        <div class="spinner-border spinner-border-sm text-primary" role="status"></div>' +
                '    </div>' +
                '</div>' +
                '    </div>' +
                '</div>';
        });

        if (append) {
            $(`#konten${id}`).append(comm);
        } else {
            $(`#konten${id}`).prepend(comm);
        }

        $('.toggle-reply').on('shown.bs.collapse', function (e) {
            var konten = $(this);
            var id = konten.data('id');
            var list = $(this).find('.media').length;
            if (list === 0) $(`#loadmore-reply${id}`).click();
        });
    }

    function addReplies(id, replies, append) {
        var repl = '';
        $.each(replies, function (i, v) {
            var sudahAda = $(`.media${v.id_reply}`).length;
            if (!sudahAda) {
                var dari = v.dari_group == '1' ? 'Admin' : (v.dari_group == '2' ? v.nama_guru : v.nama_siswa);
                var foto = v.dari_group == '2' ? (v.foto != null ? base_url + v.foto : base_url + 'assets/img/siswa.png') :
                    (v.foto_siswa != null ? base_url + v.foto_siswa : base_url + 'assets/img/siswa.png');
                
                var avatar = v.dari == '0' ? 
                    '<div class="avatar-admin mr-2" style="width: 30px; height: 30px; font-size:0.8rem;">A</div>' : 
                    '<img class="img-circle mr-2 border" src="' + foto + '" alt="Img" width="30px" height="30px">';
                
                repl +=
                    '<div class="media mt-2 media' + v.id_reply + '">'
                    + avatar +
                    '    <div class="w-100">' +
                    '        <div class="media-body reply-bubble shadow-sm">' +
                    '            <span class="text-xs font-weight-bold text-dark d-block mb-1">' + dari + '</span>' +
                    '            <div class="comment-text text-xs text-secondary">' + v.text + '</div>' +
                    '        </div>' +
                    '        <div class="ml-2 mt-1 d-flex align-items-center">' +
                    '            <small class="text-muted mr-3">' + createTime(v.tanggal) + '</small>' +
                    '            <span class="btn btn-sm text-danger p-0" style="font-size:0.75rem; font-weight:600;" onclick="hapusReply(' + v.id_reply + ')" data-id="' + v.id_reply + '">' +
                    '                <i class="fa fa-trash-alt mr-1"></i> Hapus' +
                    '            </span>' +
                    '        </div>' +
                    '    </div>' +
                    '</div>';
            }
        });

        if (append) {
            $(`#konten-reply${id}`).append(repl);
        } else {
            $(`#konten-reply${id}`).prepend(repl);
        }
    }

    function getComments(id) {
        $(`#loading${id}`).removeClass('d-none');
        $(`#loadmore${id}`).addClass('d-none');
        var $count = $(`#loadmore${id}`), page = $count.data('count');
        if (!page) page = 0;

        setTimeout(function () {
            $.ajax({
                url: base_url + "pengumuman/getcomment/" + id + "/" + page,
                type: "GET",
                success: function (response) {
                    page += 1;
                    $count.data('count', page);

                    if (response.length === 5) {
                        $(`#loadmore${id}`).removeClass('d-none');
                    }
                    $(`#loading${id}`).addClass('d-none');
                    addComments(id, response, true)
                }, error: function (xhr, status, error) {
                    console.log("error", xhr.responseText);
                }
            });
        }, 500);
    }

    function getReplies(id) {
        $(`#loading-reply${id}`).removeClass('d-none');
        $(`#loadmore-reply${id}`).addClass('d-none');
        var $count = $(`#loadmore-reply${id}`), page = $count.data('count');
        if (!page) page = 0;

        setTimeout(function () {
            $.ajax({
                url: base_url + "pengumuman/getreplies/" + id + "/" + page,
                type: "GET",
                success: function (response) {
                    page += 1;
                    $count.data('count', page);

                    if (response.length === 5) {
                        $(`#loadmore-reply${id}`).removeClass('d-none');
                    }
                    $(`#loading-reply${id}`).addClass('d-none');
                    addReplies(id, response, true)
                }, error: function (xhr, status, error) {
                    console.log("error", xhr.responseText);
                }
            });
        }, 500);
    }

    function hapusPost(idPost) {
        swal.fire({
            title: "Hapus Pengumuman?",
            text: "Pengumuman ini akan dihapus secara permanen.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: "Ya, Hapus!"
        }).then(result => {
            if (result.value) {
                swal.fire({
                    text: "Silahkan tunggu....",
                    button: false,
                    allowOutsideClick: false,
                    onOpen: () => { swal.showLoading(); }
                });
                $.ajax({
                    url: base_url + "pengumuman/hapuspost/" + idPost,
                    type: "GET",
                    success: function (data) {
                        if (data) {
                            swal.fire({
                                title: "Sukses",
                                text: "Pengumuman berhasil dihapus",
                                icon: "success",
                            }).then(() => { window.location.reload(); });
                        } else {
                            swal.fire({ title: "ERROR", text: "Gagal menghapus pengumuman", icon: "error" });
                        }
                    }, error: function (xhr) {
                        const err = JSON.parse(xhr.responseText);
                        swal.fire({ title: "Error", text: err.Message, icon: "error" });
                    }
                });
            }
        });
    }

    function hapusKomentar(idKomentar) {
        swal.fire({
            title: "Hapus Komentar?",
            text: "Komentar terpilih akan dihapus.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: "Hapus"
        }).then(result => {
            if (result.value) {
                $.ajax({
                    url: base_url + "pengumuman/hapuskomentar/" + idKomentar,
                    type: "GET",
                    success: function (data) {
                        if (data) {
                            swal.fire({ title: "Sukses", text: "Komentar dihapus", icon: "success", timer: 1500, showConfirmButton: false });
                            $(`#parent-reply${idKomentar}`).fadeOut();
                        }
                    }
                });
            }
        });
    }

    function hapusReply(idReply) {
        swal.fire({
            title: "Hapus Balasan?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: "Hapus"
        }).then(result => {
            if (result.value) {
                $.ajax({
                    url: base_url + "pengumuman/hapusbalasan/" + idReply,
                    type: "GET",
                    success: function (data) {
                        if (data) {
                            swal.fire({ title: "Sukses", text: "Balasan dihapus", icon: "success", timer: 1500, showConfirmButton: false });
                            $(`.media${idReply}`).fadeOut();
                        }
                    }
                });
            }
        });
    }

    $(document).ready(function () {
        $('.editable').attr('contentEditable', true);
        $('.select2').select2();

        $('#text-pengumuman').summernote({
            placeholder: 'Tulis isi pengumuman atau info penting disini...',
            tabsize: 2,
            minHeight: 120,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'picture']],
            ]
        });

        $('.toggle-comment').on('shown.bs.collapse', function (e) {
            var konten = $(this);
            var id = konten.data('id');
            var list = $(this).find('.media').length;
            if (list === 0) $(`#loadmore${id}`).click();
        });

        $('#formpengumuman').submit(function (e) {
            e.preventDefault();
            swal.fire({
                text: "Menyimpan data....",
                button: false,
                allowOutsideClick: false,
                onOpen: () => { swal.showLoading(); }
            });
            $.ajax({
                url: base_url + "pengumuman/save",
                type: "POST",
                dataType: "JSON",
                data: $(this).serialize(),
                success: function (data) {
                    if (data) {
                        swal.fire({ title: "Sukses", text: "Pengumuman berhasil disimpan", icon: "success" })
                        .then(() => { window.location.href = base_url + 'pengumuman'; });
                    }
                }, error: function (xhr) {
                    const err = JSON.parse(xhr.responseText);
                    swal.fire({ title: "Error", text: err.Message, icon: "error" });
                }
            });
        });

        $('#komentarModal').on('show.bs.modal', function (e) {
            var id = $(e.relatedTarget).data('id');
            $("#id-post").val(id);
            if (!$(`#collapse-${id}`).hasClass('show')) {
                $(`#trigger${id}`).click();
            }
        });

        $('#balasanModal').on('show.bs.modal', function (e) {
            var id = $(e.relatedTarget).data('id');
            $("#id-comment").val(id);
            if (!$(`#collapse-reply${id}`).hasClass('show')) {
                $(`#trigger-reply${id}`).click();
            }
        });

        $('#komentar').on('submit', function (e) {
            e.preventDefault();
            var id = $(this).find('input[name=id_post]').val();
            $.ajax({
                url: base_url + "pengumuman/savekomentar",
                data: $(this).serialize(),
                method: 'POST',
                dataType: "JSON",
                success: function (response) {
                    $('#komentarModal').modal('hide');
                    $('#komentar')[0].reset();
                    addComments(id, response, false);
                },
                error: function () {
                    $('#komentarModal').modal('hide');
                    showDangerToast('Error, komentar gagal terkirim');
                }
            });
        });

        $('#balasan').on('submit', function (e) {
            e.preventDefault();
            var id = $(this).find('input[name=id_comment]').val();
            $.ajax({
                url: base_url + "pengumuman/savebalasan",
                data: $(this).serialize(),
                method: 'POST',
                dataType: "JSON",
                success: function (response) {
                    $('#balasanModal').modal('hide');
                    $('#balasan')[0].reset();
                    addReplies(id, response, false);
                },
                error: function () {
                    $('#balasanModal').modal('hide');
                    showDangerToast('Error, balasan gagal terkirim');
                }
            });
        });

        $('#formrunningtext').on('submit', function (e) {
            e.preventDefault();
            var no = 1;
            var jsonObj = [];
            const $rows = $('#tb-text').find('tr');
            $rows.each((i, row) => {
                const text = $(row).find('.editable').text();
                let item = {};
                item ["id_text"] = no;
                item ["text"] = text;
                jsonObj.push(item);
                no++;
            });

            var dataPost = $(this).serialize() + "&text=" + JSON.stringify(jsonObj);
            $.ajax({
                url: base_url + "pengumuman/saverunningtext",
                type: "POST",
                data: dataPost,
                success: function (response) {
                    if (response.status[0]) {
                        window.location.reload();
                    } else {
                        showDangerToast('Gagal menyimpan running text');
                    }
                }
            });
        });
    });
</script>