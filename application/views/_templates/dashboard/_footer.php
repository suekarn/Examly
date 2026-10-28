</div>
</footer>

<aside class="control-sidebar control-sidebar-dark">
    </aside>
</div>

<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
<script src="<?= base_url() ?>/assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>/assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url() ?>/assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url() ?>/assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?= base_url() ?>/assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url() ?>/assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?= base_url() ?>/assets/plugins/jszip/jszip.min.js"></script>
<script src="<?= base_url() ?>/assets/plugins/pdfmake/pdfmake.min.js"></script>
<script src="<?= base_url() ?>/assets/plugins/pdfmake/vfs_fonts.js"></script>
<script src="<?= base_url() ?>/assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="<?= base_url() ?>/assets/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="<?= base_url() ?>/assets/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

<script src="<?= base_url() ?>/assets/plugins/sparklines/sparkline.js"></script>

<script src="<?= base_url() ?>/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url() ?>/assets/plugins/chart.js/Chart.min.js"></script>
<script src="<?= base_url() ?>/assets/plugins/chart.js/chartjs-plugin-labels.min.js"></script>
<script src="<?= base_url() ?>/assets/plugins/jquery-knob/jquery.knob.min.js"></script>
<script src="<?= base_url() ?>/assets/plugins/moment/moment.min.js"></script>
<script src="<?= base_url() ?>/assets/plugins/moment/moment-with-locales.min.js"></script>
<script src="<?= base_url() ?>/assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<script src="<?= base_url() ?>/assets/plugins/summernote/summernote-bs4.min.js"></script>
<script src="<?= base_url() ?>/assets/plugins/summernote/plugin/audio/summernote-audio.js"></script>
<script src="<?= base_url() ?>/assets/plugins/summernote/plugin/file/summernote-file.js"></script>
<script src="<?= base_url() ?>/assets/plugins/summernote/plugin/gallery/dist/summernote-gallery.min.js"></script>
<script src="<?= base_url() ?>/assets/plugins/summernote/plugin/math/summernote-math.js"></script>
<script src="<?= base_url() ?>/assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<script src="<?= base_url() ?>/assets/plugins/sweetalert2/sweetalert2.min.js"></script>
<script src="<?= base_url() ?>/assets/plugins/toastr/toastr.min.js"></script>
<script src="<?= base_url() ?>/assets/plugins/select2/js/select2.full.min.js"></script>
<script src="<?= base_url() ?>/assets/plugins/multiselect/js/jquery.multi-select.js"></script>
<script type="text/javascript" src="<?= base_url() ?>/assets/plugins/multiselect/js/jquery.quicksearch.js"></script>
<script src="<?= base_url() ?>/assets/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
<script src="<?= base_url() ?>/assets/plugins/inputmask/min/jquery.inputmask.bundle.min.js"></script>

<script src="<?= base_url() ?>/assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<script src="<?= base_url() ?>/assets/plugins/dropify/js/dropify.min.js"></script>
<script src="<?= base_url() ?>/assets/app/js/jquery.toast.min.js"></script>

<script src="<?= base_url() ?>/assets/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>

<script src="<?= base_url() ?>/assets/adminlte/dist/js/adminlte.js"></script>
<script src="<?= base_url() ?>/assets/adminlte/dist/js/pages/dashboard.js"></script>
<script src="<?= base_url() ?>/assets/adminlte/dist/js/demo.js"></script>
<script src="<?= base_url() ?>/assets/plugins/jquery-datetimepicker/jquery.datetimepicker.full.js"></script>
<script src="<?= base_url() ?>/assets/plugins/jquery-timeago/jquery.timeago.js" type="text/javascript"></script>
<script src="<?= base_url() ?>/assets/app/js/show.toast.js"></script>

<script src="<?= base_url() ?>/assets/app/js/jquery-thumbnail-cut.js"></script>
<script src="<?= base_url() ?>/assets/app/js/theme.js"></script>

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

    function logout() {
        swal.fire({
            title: "Logout",
            text: "Anda yakin ingin logout?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Logout!'
        }).then((result) => {
            if (result.value) {
                location.href = base_url + "logout";
            }
        });
    }

</script>

<script src="<?= site_url('updater/notice') ?>"></script>

</body>

</html>