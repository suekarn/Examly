<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
     
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?php $logo_app = $setting->logo_kiri == null ? base_url() . 'assets/img/favicon.png' : base_url() . $setting->logo_kiri; ?>
    <link rel="shortcut icon" href="<?= $logo_app ?>" type="image/x-icon">

    <link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/fontawesome-free/css/fontawesome.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/fontawesome-free/css/v4-shims.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/Ionicons/css/ionicons.min.css">
    
    <link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/multiselect/css/multi-select.css">
    <link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/summernote/summernote-bs4.css">
    <link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>/assets/app/css/jquery.toast.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/toastr/toastr.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/dropify/css/dropify.min.css">

    <link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

    <link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/katex/katex.css">
    <link rel="stylesheet" href="<?= base_url() ?>/assets/adminlte/dist/css/poppins.css">
    <link rel="stylesheet" href="<?= base_url() ?>/assets/adminlte/dist/css/calibri.css">

    <link rel="stylesheet" href="<?= base_url() ?>/assets/adminlte/dist/css/adminlte.min.css">

    <link rel="stylesheet" href="<?= base_url() ?>/assets/app/css/mystyle.css">
    <link rel="stylesheet" href="<?= base_url() ?>/assets/app/css/show.toast.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>/assets/plugins/fields-linker/fieldsLinker.css">

    <script src="<?= base_url() ?>/assets/plugins/jquery/jquery.min.js"></script>
    <script src="<?= base_url() ?>/assets/plugins/jquery-ui/jquery-ui.min.js"></script>

    <script src="<?= base_url() ?>/assets/plugins/sweetalert2/sweetalert2.min.js"></script>
    <script defer src="<?= base_url() ?>/assets/plugins/katex/contrib/auto-render.min.js" onload="renderMathInElement(document.body);"></script>

    <style>
        .linker-list p {
            margin-bottom: .5rem;
            margin-top: .5rem;
        }

        /* ===== Bar tombol di bawah layar (Beranda & Keluar) ===== */
        :root { --bottom-bar-h: 0px; }
        body.has-bottom-bar { --bottom-bar-h: 76px; padding-bottom: var(--bottom-bar-h); }
        .bottom-bar {
            position: fixed; left: 0; right: 0; bottom: 0; z-index: 10000;
            display: flex; gap: 12px; align-items: stretch;
            padding: 10px 12px calc(10px + env(safe-area-inset-bottom));
            background: #fff; border-top: 1px solid #e2e8f0;
            box-shadow: 0 -6px 18px rgba(15, 23, 42, .08);
        }
        .bottom-bar .bb-item { flex: 1 1 0; min-width: 0; }
        .bottom-bar .bb-btn {
            width: 100%; height: 56px; display: flex; align-items: center; justify-content: center; gap: 10px;
            border: 0; border-radius: 16px; cursor: pointer; text-decoration: none !important;
            font-size: 1.15rem; font-weight: 800; color: #fff !important;
        }
        .bottom-bar .bb-btn i { font-size: 1.3rem; }
        .bottom-bar .bb-home { background: #2563eb; }
        .bottom-bar .bb-out  { background: #dc2626; }
        .bottom-bar .bb-btn:active { transform: scale(.97); }
    </style>

    <script type="text/javascript">
        let base_url = '<?=base_url()?>';
    </script>
</head>

<?php
function buat_tanggal($str)
{
    $str = str_replace("Jan", "Januari", $str);
    $str = str_replace("Feb", "Februari", $str);
    $str = str_replace("Mar", "Maret", $str);
    $str = str_replace("Apr", "April", $str);
    $str = str_replace("May", "Mei", $str);
    $str = str_replace("Jun", "Juni", $str);
    $str = str_replace("Jul", "Juli", $str);
    $str = str_replace("Aug", "Agustus", $str);
    $str = str_replace("Sep", "September", $str);
    $str = str_replace("Oct", "Oktober", $str);
    $str = str_replace("Nov", "Nopember", $str);
    $str = str_replace("Dec", "Desember", $str);
    $str = str_replace("Mon", "Senin", $str);
    $str = str_replace("Tue", "Selasa", $str);
    $str = str_replace("Wed", "Rabu", $str);
    $str = str_replace("Thu", "Kamis", $str);
    $str = str_replace("Fri", "Jumat", $str);
    $str = str_replace("Sat", "Sabtu", $str);
    $str = str_replace("Sun", "Minggu", $str);
    return $str;
}

function singkat_tanggal($str)
{
    $str = str_replace("Jan", "Jan", $str);
    $str = str_replace("Feb", "Feb", $str);
    $str = str_replace("Mar", "Mar", $str);
    $str = str_replace("Apr", "Apr", $str);
    $str = str_replace("May", "Mei", $str);
    $str = str_replace("Jun", "Jun", $str);
    $str = str_replace("Jul", "Jul", $str);
    $str = str_replace("Aug", "Aug", $str);
    $str = str_replace("Sep", "Sep", $str);
    $str = str_replace("Oct", "Okt", $str);
    $str = str_replace("Nov", "Nov", $str);
    $str = str_replace("Dec", "Des", $str);
    $str = str_replace("Mon", "Sen", $str);
    $str = str_replace("Tue", "Sel", $str);
    $str = str_replace("Wed", "Rab", $str);
    $str = str_replace("Thu", "Kam", $str);
    $str = str_replace("Fri", "Jum", $str);
    $str = str_replace("Sat", "Sab", $str);
    $str = str_replace("Sun", "Min", $str);
    return $str;
}

$dash = $this->uri->segment(1);
$cbt = $this->uri->segment(2);
$exludes = ["dashboard", "penilaian"];
$dnone = in_array($dash, $exludes) || in_array($cbt, $exludes) ? 'invisible' : '';

$display_clock = $this->uri->segment(2) == "penilaian" ? '' : 'd-none';
$display_logout = $this->uri->segment(2) == "penilaian" ? 'd-none' : '';

// Tombol Beranda & Keluar sekarang ada di bar bawah layar
$tampil_beranda = ($dnone === '');
$tampil_logout  = ($display_logout === '') && $dash !== 'dashboard'; // tombol Keluar tidak tampil di Dashboard
$tampil_bar     = $tampil_beranda || $tampil_logout;
?>

<body class="layout-top-nav layout-navbar-fixed<?= $tampil_bar ? ' has-bottom-bar' : '' ?>">
<div class="wrapper">
    <nav class="main-header navbar navbar-expand-md navbar-transparent border-bottom-0">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item <?= $display_clock ?>">
                <div id="live-clock" class="nav-link text-white font-weight-bold"></div>
            </li>
        </ul>
    </nav>

    <?php if ($tampil_bar) : ?>
    <div class="bottom-bar">
        <?php if ($tampil_beranda) : ?>
            <div class="bb-item" id="back">
                <a href="<?= base_url('dashboard') ?>" class="bb-btn bb-home" id="url-back">
                    <i class="fas fa-home"></i> Beranda
                </a>
            </div>
        <?php endif; ?>
        <?php if ($tampil_logout) : ?>
            <div class="bb-item">
                <button type="button" onclick="konfirmasiLogout()" class="bb-btn bb-out">
                    <i class="fas fa-sign-out-alt"></i> Keluar
                </button>
            </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <script type="text/javascript">
        const isLogin = localStorage.getItem('garudaCBT.login');
        const isCbtMode = isLogin ? isLogin === '1' : false;
        if (isCbtMode) {
            $("a#url-back").attr("href", base_url + 'siswa/cbt');
            if ('<?= $cbt ?>' === 'cbt') {
                $('#back').addClass('d-none');
            }
        }

        // Tanya dulu supaya anak tidak keluar tanpa sengaja
        function konfirmasiLogout() {
            Swal.fire({
                title: 'Mau keluar?',
                text: 'Kamu akan keluar dari aplikasi.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, keluar',
                cancelButtonText: 'Tidak',
                reverseButtons: true
            }).then(function (r) {
                // r.isConfirmed = SweetAlert2 versi baru, r.value = versi lama
                if (r.isConfirmed || r.value) {
                    if (typeof logout === 'function') logout();
                    else window.location.href = base_url + 'auth/logout';
                }
            });
        }
    </script>