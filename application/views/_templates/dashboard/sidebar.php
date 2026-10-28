<?php
// ===================================================================
// Gate menu "AI Soal" — dikendalikan dari license-server/admin.php
// lewat kode lisensi instalasi ini (application/config/license_key.php).
// Logikanya di application/helpers/license_helper.php, dipakai bersama
// dengan Converter.php supaya satu sumber kebenaran.
// ===================================================================
if (!function_exists('lg_show_ai_soal_menu')) {
    $this->load->helper('license');
}
$show_ai_soal_menu = lg_show_ai_soal_menu();
?>
<style>
    /* Styling Badge NEW Berkedip */
    .badge-new-blink {
        background-color: #e74c3c !important;
        color: #ffffff !important;
        font-size: 9px !important;
        font-weight: bold !important;
        padding: 2px 6px !important;
        border-radius: 8px !important;
        margin-left: 6px !important;
        display: inline-block !important;
        vertical-align: middle !important;
        letter-spacing: 0.5px !important;
        
        -webkit-animation: blinkAnim 1s infinite alternate !important;
        animation: blinkAnim 1s infinite alternate !important;
    }

    @keyframes blinkAnim {
        0% { opacity: 1; transform: scale(1); }
        100% { opacity: 0.2; transform: scale(0.92); }
    }

    /* EFEK DENYUT UNTUK SEMUA IKON UTAMA */
    @keyframes universalPulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.12); }
        100% { transform: scale(1); }
    }

    /* Efek Denyut Khusus AI Soal (Lebih Cepat & Berkilau) */
    @keyframes aiPulse {
        0% { transform: scale(1); text-shadow: 0 0 0 rgba(155, 89, 182, 0.4); }
        50% { transform: scale(1.22); text-shadow: 0 0 10px rgba(155, 89, 182, 0.9); }
        100% { transform: scale(1); text-shadow: 0 0 0 rgba(155, 89, 182, 0.4); }
    }

    /* Menerapkan animasi denyut ke semua ikon menu utama */
    #tree-menus > .nav-item > .nav-link > .nav-icon {
        animation: universalPulse 3s infinite ease-in-out;
    }

    /* Khusus ikon AI Soal animasinya lebih hidup */
    .icon-alive {
        animation: aiPulse 2s infinite ease-in-out !important;
    }

    /* WARNA-WARNI KHUSUS UNTUK SETIAP IKON MENU UTAMA */
    .color-dashboard { color: #3498db !important; } /* Biru */
    .color-data-umum { color: #1abc9c !important; } /* Tosca */
    .color-elearning  { color: #e67e22 !important; } /* Oranye */
    .color-ujian     { color: #e74c3c !important; } /* Merah */
    .color-pengumuman{ color: #f1c40f !important; } /* Kuning */
    .color-ai        { color: #9b59b6 !important; } /* Ungu AI */
    .color-hasil     { color: #2980b9 !important; } /* Biru Tua */
    .color-rapor     { color: #d35400 !important; } /* Cokelat Bata */
    .color-pengaturan{ color: #7f8c8d !important; } /* Abu-abu */
    .color-qr        { color: #16a085 !important; } /* Hijau QR */

    /* Samakan tinggi header sidebar (logo) dengan navbar atas
       supaya garis bawahnya sejajar/rata */
    .main-header.navbar {
        height: 57px !important;
        min-height: 57px !important;
        padding-top: 0 !important;
        padding-bottom: 0 !important;
        box-sizing: border-box !important;
    }

    .main-sidebar .brand-link {
        height: 57px !important;
        display: flex !important;
        align-items: center !important;
        padding-top: 0 !important;
        padding-bottom: 0 !important;
        box-sizing: border-box !important;
    }

    /* Jarak menu pertama dari header (terutama saat sidebar diciutkan,
       karena tulisan "HOME" ikut tersembunyi) */
    #tree-menus {
        padding-top: 14px;
    }

    /* Ikon rata tengah dan tidak terpotong saat sidebar diciutkan */
    .sidebar-collapse .main-sidebar:not(:hover) #tree-menus .nav-link {
        text-align: center;
        overflow: visible !important;
    }

    /* Info LAN di bagian bawah sidebar */
    .main-sidebar .lan-info {
        position: absolute;
        left: 0; right: 0; bottom: 0;
        padding: 10px 14px;
        font-size: 12px;
        font-weight: 600;
        background: inherit;
        border-top: 1px solid rgba(128, 128, 128, 0.25);
        z-index: 10;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .main-sidebar .lan-info a { color: inherit; }
    .main-sidebar .lan-info i { margin-right: 6px; opacity: .7; }
    .sidebar-collapse .main-sidebar:not(:hover) .lan-info { display: none; }

    /* Transisi menu */
    #tree-menus .nav-item .nav-link {
        position: relative;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        transform: scale(1);
    }

    #tree-menus .nav-item .nav-link {
        border-radius: 6px !important;
    }

    /* Hover: kotak tetap rapi, tidak membesar dan tidak bergeser */
    #tree-menus .nav-item .nav-link:hover {
        background-color: #20c997 !important;
        color: #ffffff !important;
        transform: none !important;
        box-shadow: 0 2px 8px rgba(32, 201, 151, 0.35);
    }

    #tree-menus .nav-item .nav-link .nav-icon,
    #tree-menus .nav-item .nav-link p {
        transition: all 0.3s ease !important;
    }

    #tree-menus .nav-item .nav-link:hover .nav-icon {
        color: #ffffff !important;
        transform: scale(1.1);
        animation: none !important; 
    }
    
    #tree-menus .nav-treeview .nav-item .nav-link:hover {
        background-color: rgba(32, 201, 151, 0.8) !important;
    }

    /* CSS Pengaman Tambahan untuk Sembunyikan Menu Spesifik */
    #tree-menus a[href*="kelasjadwal"],
    #tree-menus a[href*="kelasmateri"],
    #tree-menus a[href*="kelasmaterijadwal"],
    #tree-menus a[href*="kelasstatus"],
    #tree-menus a[href*="kelasabsensi"],
    #tree-menus a[href*="kelasnilai"],
    #tree-menus a[href*="rapor"],
    #tree-menus a[href*="bukurapor"],
    #tree-menus a[href*="dataalumni"] {
        display: none !important;
    }
</style>

<aside class="main-sidebar sidebar-light-teal my-shadow">
    <a href="<?= base_url(); ?>" class="brand-link bg-white">
        <?php $logo_app = base_url() . 'assets/img/examly-icon-animasi.svg'; ?>
        <img src="<?= $logo_app ?>" alt="Examly" class="brand-image" style="opacity: 1; width: 33px; height: 33px;">
        <span class="brand-text font-weight-bold">Examly</span>
    </a>

    <div class="sidebar">
        <?php $avatar = $profile->foto == null ? base_url() . 'assets/adminlte/dist/img/avatar5.png' : base_url() . $profile->foto; ?>
        <?php /* Panel profil (foto + NoName / Admin) disembunyikan.
                 Hapus tanda komentar ini kalau ingin menampilkannya lagi.
        <div class="user-panel border-0 d-flex mt-3 mb-3">
            <div class="image">
                <img src="<?= $avatar ?>" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block" style="line-height: 1">
                    <?= $profile->nama_lengkap == null ? 'NoName' : $profile->nama_lengkap ?>
                    <br>
                    <small class="text-muted">
                        <?= $profile->jabatan == null ? 'Admin' : $profile->jabatan ?>
                    </small>
                </a>
            </div>
        </div>
        */ ?>

        <nav class="mt-2 mb-5">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent"
                id="tree-menus" data-widget="treeview" role="menu" data-accordion="false">
            </ul>
        </nav>
    </div>

    <?php if (!empty($lan_url)) : ?>
    <div class="lan-info" title="Alamat akses dari komputer lain (LAN)">
        <i class="fas fa-network-wired"></i>LAN: <a href="<?= htmlspecialchars($lan_url); ?>"><?= htmlspecialchars($lan_url); ?></a>
    </div>
    <?php endif; ?>
</aside>

<script>
    const page = '<?= $this->uri->segment(1) ?>';
    const pageact = '<?= $this->uri->segment(2); ?>';
    const baseUrlSistem = '<?= base_url(); ?>';
    const currentPath = pageact ? `${page}/${pageact}` : page;

    // Menu Data E-Learning, Hasil E-Learning, RAPOR, dan Alumni telah dibuang
    const menus = [
        {
            'header': 'HOME', 'cbt': '1',
            'menu': [
                {'name': 'Dashboard', 'link': 'dashboard', 'icon': 'fas fa-desktop color-dashboard', 'cbt': '1'},
                {
                    'name': 'Data Umum', 'icon': 'fas fa-server color-data-umum', 'cbt': '1',
                    'submenu': [
                        {'name': "Tahun Pelajaran", 'link': "datatahun", 'icon': 'far fa-calendar-check'},
                        {'name': "Mata Pelajaran", 'link': "datamapel", 'icon': 'fa fa-book'},
                        {'name': "Jurusan", 'link': "datajurusan", 'icon': 'fa fa-flask'},
                        {'name': "Siswa", 'link': "datasiswa", 'icon': 'fa fa-users'},
                        {'name': "Kelas / Rombel", 'link': "datakelas", 'icon': 'fa fa-school'},
                        {'name': "Ekstrakurikuler", 'link': "dataekstra", 'icon': 'fa fa-chess'},
                        {'name': "Guru", 'link': "dataguru", 'icon': 'fa fa-chalkboard-teacher'}
                    ]
                },
                {
                    'name': 'Data Ujian', 'icon': 'fa fa-user-graduate color-ujian', 'cbt': '1',
                    'submenu': [
                        {'name': "Jenis Ujian", 'link': "cbtjenis", 'icon': 'fa fa-project-diagram'},
                        {'name': "Sesi", 'link': "cbtsesi", 'icon': 'far fa-clock'},
                        {'name': "Ruang", 'link': "cbtruang", 'icon': 'fa fa-door-open'},
                        {'name': "Atur Ruang/Sesi", 'link': "cbtsesisiswa", 'icon': 'fa fa-user-clock'},
                        {'name': "Nomor Peserta", 'link': "cbtnomorpeserta", 'icon': 'far fa-id-card'},
                        {'name': "Bank Soal", 'link': "cbtbanksoal", 'icon': 'far fa-folder-open'},
                        {'name': "Jadwal", 'link': "cbtjadwal", 'icon': 'far fa-calendar-alt'},
                        {'name': "Alokasi Waktu", 'link': "cbtalokasi", 'icon': 'fa fa-clock-o'},
                        {'name': "Pengawas", 'link': "cbtpengawas", 'icon': 'fa fa-briefcase'},
                        {'name': "Token", 'link': "cbttoken", 'icon': 'fa fa-key'}
                    ]
                },
                {
                    'name': 'Pengumuman', 'link': 'pengumuman', 'icon': 'fas fa-bullhorn color-pengumuman', 'cbt': '1'
                },
                <?php if ($show_ai_soal_menu): ?>
                {
                    'name': 'AI Soal <span class="badge-new-blink">New</span>', 'link': 'aiimport', 'icon': 'fa fa-magic color-ai icon-alive', 'cbt': '1'
                },
                <?php endif; ?>
                {
                    'name': 'QR Login Siswa', 'link': '#', 'action': 'qrlogin', 'icon': 'fas fa-qrcode color-qr', 'cbt': '1'
                },
            ]
        },
        {
            'header': 'PELAKSANAAN', 'cbt': '1',
            'menu': [
                {
                    'name': 'Pelaksanaan Ujian', 'icon': 'fas fa-graduation-cap color-ujian', 'cbt': '1',
                    'submenu': [
                        {'name': 'Cetak', 'link': "cbtcetak", 'icon': 'fa fa-print'},
                        {'name': 'Status Siswa', 'link': "cbtstatus", 'icon': 'fa fa-user-clock'},
                        {'name': 'Hasil Ujian', 'link': "cbtnilai", 'icon': 'fa fa-file-alt'},
                        {'name': 'Analisis Soal', 'link': "cbtanalisis", 'icon': 'fa fa-chart-line'},
                        {'name': 'Rekap Nilai', 'link': "cbtrekap", 'icon': 'fas fa-trophy'},
                        {'name': 'Log Login', 'link': "loglogin", 'icon': 'fa fa-laptop'},
                    ]
                },
            ]
        },
        {
            'header': 'PENGATURAN', 'cbt': '1',
            'menu': [
                {'name': 'Profile Sekolah', 'link': 'settings', 'icon': 'fas fa-university color-pengaturan', 'cbt': '1'},
                {
                    'name': 'User Management', 'icon': 'fa fa-users-cog color-pengaturan', 'cbt': '1',
                    'submenu': [
                        {'name': 'Administrator', 'link': "useradmin", 'icon': 'fas fa-cog'},
                        {'name': 'Guru', 'link': "userguru", 'icon': 'fas fa-user-tie'},
                        {'name': 'Siswa', 'link': "usersiswa", 'icon': 'fas fa-users'}
                    ]
                },
                {
                    'name': 'Database', 'icon': 'fa fa-database color-pengaturan', 'cbt': '1',
                    'submenu': [
                        {'name': 'Backup', 'link': "dbmanager", 'icon': 'fas fa-database'},
                        {'name': 'Data Manager', 'link': "dbclear", 'icon': 'fas fa-database'},
                    ]
                },
            ]
        },
        {
            'header': null,
            'logout_button': true
        }
    ];

    const isLogin = localStorage.getItem('garudaCBT.login');
    const isCbtMode = isLogin ? isLogin === '1' : false;
    let htmlMenu = '';

    menus.forEach(function (header) {
        if (isCbtMode && header.cbt === '0') {
            return;
        }

        if (header.header) {
            htmlMenu += `<li class="nav-header">${header.header}</li>`;
            
            header.menu.forEach(function (menu) {
                if (isCbtMode && menu.cbt === '0') {
                    return;
                }

                if (menu.submenu) {
                    let subLinks = menu.submenu.map(function (item) {
                        return item.link;
                    });

                    let isTreeOpen = subLinks.includes(currentPath) || subLinks.includes(page);
                    
                    htmlMenu += `
                    <li class="nav-item has-treeview ${isTreeOpen ? "menu-open" : ""}">
                        <a href="#" class="nav-link ${isTreeOpen ? "active" : ""}">
                            <i class="nav-icon ${menu.icon}"></i>
                            <p>${menu.name}<i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">`;

                    menu.submenu.forEach(function (sub) {
                        let isSubActive = (currentPath === sub.link) || (page === sub.link);
                        
                        htmlMenu += `
                            <li class="nav-item">
                                <a href="${baseUrlSistem + sub.link}" class="nav-link ${isSubActive ? "active" : ""}">
                                    <i class="${sub.icon} nav-icon"></i>
                                    <p>${sub.name}</p>
                                </a>
                            </li>`;
                    });

                    htmlMenu += `</ul></li>`;
                } else {
                    let isMenuActive = (page === menu.link);
                    let menuHref = menu.action ? '#' : baseUrlSistem + menu.link;
                    let menuAttr = menu.action ? `data-action="${menu.action}"` : '';
                    htmlMenu += `
                    <li class="nav-item">
                        <a href="${menuHref}" ${menuAttr} class="nav-link ${isMenuActive ? "active" : ""}">
                            <i class="nav-icon ${menu.icon}"></i>
                            <p>${menu.name}</p>
                        </a>
                    </li>`;
                }
            });
        } else if (header.logout_button) {
            htmlMenu += `
            <hr style="border-top: 1px solid #dfdfdf;" />
            <li class="nav-item">
                <a href="${baseUrlSistem}login/logout_admin" class="nav-link bg-danger text-white">
                    <i class="nav-icon fas fa-sign-out-alt"></i>
                    <p>LOGOUT</p>
                </a>
            </li>`;
        }
    });

    $('#tree-menus').html(htmlMenu);
</script>

<!-- ===== Modal QR Login Siswa ===== -->
<div class="modal fade" id="modalQrLogin" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 400px;">
        <div class="modal-content" style="border-radius: 16px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-qrcode text-teal mr-2"></i>QR Login Siswa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Tutup"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body text-center">
                <p class="text-muted mb-3">Scan dengan kamera HP/tablet untuk membuka halaman login ujian.</p>
                <div id="qrLoginBox" style="display:inline-block;padding:12px;background:#fff;border:1px solid #e2e8f0;border-radius:12px;"></div>
                <div class="mt-3 font-weight-bold" id="qrLoginUrl" style="word-break:break-all;color:#0f766e;"></div>
            </div>
            <div class="modal-footer border-0 pt-0 justify-content-center">
                <button type="button" class="btn btn-sm btn-outline-secondary" id="qrLoginCopy"><i class="fas fa-copy mr-1"></i>Salin Link</button>
                <button type="button" class="btn btn-sm btn-success" id="qrLoginPrint"><i class="fas fa-print mr-1"></i>Cetak</button>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/js/qrcode.min.js') ?>"></script>
<script>
    (function () {
        // Alamat yang dipakai siswa = IP LAN server (sama dengan yang tampil di bawah sidebar).
        // Jika IP LAN tidak terdeteksi, pakai alamat yang sedang dibuka.
        var lanUrl = <?= json_encode(!empty($lan_url) ? rtrim($lan_url, '/') . '/' : '') ?>;
        var loginUrl = lanUrl || baseUrlSistem;
        var rendered = false;

        $(document).on('click', 'a[data-action="qrlogin"]', function (e) {
            e.preventDefault();
            $('#modalQrLogin').appendTo('body').modal('show');
        });

        $('#modalQrLogin').on('shown.bs.modal', function () {
            if (rendered) return;
            rendered = true;
            $('#qrLoginUrl').text(loginUrl);
            if (typeof QRCode === 'undefined') {
                $('#qrLoginBox').text('Pustaka QR belum terpasang (assets/js/qrcode.min.js).');
                return;
            }
            new QRCode(document.getElementById('qrLoginBox'), {
                text: loginUrl, width: 240, height: 240,
                correctLevel: QRCode.CorrectLevel.M
            });
        });

        $('#qrLoginCopy').on('click', function () {
            var btn = $(this);
            var done = function () {
                btn.html('<i class="fas fa-check mr-1"></i>Tersalin');
                setTimeout(function () { btn.html('<i class="fas fa-copy mr-1"></i>Salin Link'); }, 1500);
            };
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(loginUrl).then(done);
            } else {
                var t = $('<input>').val(loginUrl).appendTo('body').select();
                document.execCommand('copy'); t.remove(); done();
            }
        });

        $('#qrLoginPrint').on('click', function () {
            var w = window.open('', '_blank', 'width=480,height=620');
            var img = $('#qrLoginBox').find('canvas')[0];
            var src = img ? img.toDataURL('image/png') : $('#qrLoginBox img').attr('src');
            w.document.write('<html><head><title>QR Login Siswa</title></head>' +
                '<body style="font-family:Arial;text-align:center;padding:30px">' +
                '<h2>Login Ujian Siswa</h2><p>Scan QR untuk membuka halaman login</p>' +
                '<img src="' + src + '" style="width:300px;height:300px"><p style="font-weight:bold">' + loginUrl + '</p>' +
                '<script>window.onload=function(){window.print();}<\/script></body></html>');
            w.document.close();
        });
    })();
</script>