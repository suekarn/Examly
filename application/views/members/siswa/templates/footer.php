</div>
<?php /* Teks berjalan (running text) di bagian bawah dihapus supaya tidak menutupi tombol. */ ?>

<footer class="main-footer d-none">
    <strong>Copyright &copy; 2014-2019 <a href="http://adminlte.io">AdminLTE.io</a>.</strong>
    All rights reserved.
    <div class="d-none float-right d-none d-sm-inline-block">
        <b>Version</b> 3.0.5
    </div>
</footer>

<aside class="control-sidebar control-sidebar-dark">
    </aside>
<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>
<script src="<?= base_url() ?>/assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>/assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url() ?>/assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url() ?>/assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>

<script src="<?= base_url() ?>/assets/plugins/pace-progress/pace.min.js"></script>
<script src="<?= base_url() ?>/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url() ?>/assets/plugins/summernote/summernote-bs4.min.js"></script>
<script src="<?= base_url() ?>/assets/plugins/summernote/plugin/audio/summernote-audio.js"></script>
<script src="<?= base_url() ?>/assets/plugins/summernote/plugin/file/summernote-file.js"></script>
<script src="<?= base_url() ?>/assets/plugins/summernote/plugin/gallery/dist/summernote-gallery.min.js"></script>
<script src="<?= base_url() ?>/assets/plugins/summernote/plugin/math/summernote-math.js"></script>
<script src="<?= base_url() ?>/assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<script src="<?= base_url() ?>/assets/plugins/toastr/toastr.min.js"></script>
<script src="<?= base_url() ?>/assets/plugins/select2/js/select2.full.min.js"></script>
<script src="<?= base_url() ?>/assets/plugins/multiselect/js/jquery.multi-select.js"></script>
<script src="<?= base_url() ?>/assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<script src="<?= base_url() ?>/assets/plugins/dropify/js/dropify.min.js"></script>
<script src="<?= base_url() ?>/assets/app/js/jquery.toast.min.js"></script>

<script src="<?= base_url() ?>/assets/plugins/jquery-timeago/jquery.timeago.js" type="text/javascript"></script>
<script src="<?= base_url() ?>/assets/adminlte/dist/js/adminlte.js"></script>

<script src="<?= base_url() ?>/assets/app/js/show.toast.js"></script>
<script src="<?= base_url() ?>/assets/app/js/dashboard_guru.js"></script>

<script type="text/javascript">
    $.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings) {
        return {
            "iStart": oSettings._iDisplayStart,
            "iEnd": oSettings.fnDisplayEnd(),
            "iLength": oSettings._iDisplayLength,
            "iTotal": oSettings.fnRecordsTotal(),
            "iFilteredTotal": oSettings.fnRecordsDisplay(),
            "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
            "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
        };
    };

    function ajaxcsrf() {
        var csrfname = '<?= $this->security->get_csrf_token_name() ?>';
        var csrfhash = '<?= $this->security->get_csrf_hash() ?>';
        var csrf = {};
        csrf[csrfname] = csrfhash;
        $.ajaxSetup({
            "data": csrf
        });
    }

    function reload_ajax() {
        table.ajax.reload();
    }

    var initDestroyTimeOutPace = function () {
        var counter = 0;

        var refreshIntervalId = setInterval(function () {
            var progress;

            if (typeof $('.pace-progress').attr('data-progress-text') !== 'undefined') {
                progress = Number($('.pace-progress').attr('data-progress-text').replace("%", ''));
            }

            if (progress === 99) {
                counter++;
            }

            if (counter > 50) {
                clearInterval(refreshIntervalId);
                Pace.stop();
            }
        }, 100);
    };
    initDestroyTimeOutPace();

</script>

</body>

</html>