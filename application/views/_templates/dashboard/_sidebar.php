<style>
    /* Mengincar langsung link di dalam tree-menus agar transisi super mulus */
    #tree-menus .nav-item .nav-link {
        position: relative;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
        transform: scale(1);
    }

    /* Efek Utama saat Menu Disentuh (Hover) */
    #tree-menus .nav-item .nav-link:hover {
        padding-left: 1.5rem !important; /* Dorong menu ke kanan lebih jauh */
        background-color: #20c997 !important; /* Ubah latar belakang jadi hijau teal penuh */
        color: #ffffff !important; /* Ubah warna teks menjadi putih bersih */
        transform: scale(1.04); /* Menu membesar sebanyak 4% */
        box-shadow: 0 4px 15px rgba(32, 201, 151, 0.4); /* Efek bayangan menyala (glow effect) */
        z-index: 5;
    }

    /* Memastikan teks p dan ikon di dalamnya ikut transisi halus */
    #tree-menus .nav-item .nav-link .nav-icon,
    #tree-menus .nav-item .nav-link p {
        transition: all 0.25s ease !important;
    }

    /* Efek khusus untuk ikon saat menu disentuh */
    #tree-menus .nav-item .nav-link:hover .nav-icon {
        color: #ffffff !important; /* Ikon ikut jadi putih */
        transform: scale(1.2) rotate(5deg); /* Ikon membesar 20% dan sedikit berputar */
    }
    
    /* Efek untuk sub-menu agar ada pembeda sedikit */
    #tree-menus .nav-treeview .nav-item .nav-link:hover {
        background-color: rgba(32, 201, 151, 0.8) !important;
        padding-left: 2rem !important; /* Sub-menu didorong lebih dalam ke kanan */
    }
</style>

<aside class="main-sidebar sidebar-light-teal my-shadow">
    <a href="<?= base_url(); ?>" class="brand-link bg-white">
        <?php $logo_app = $setting->logo_kiri == null ? base_url() . 'assets/img/favicon.png' : base_url() . $setting->logo_kiri; ?>
        <img src="<?= $logo_app ?>" alt="App Logo" class="brand-image" style="opacity: .8">
        <span class="brand-text font-weight-light"><?= $setting->nama_aplikasi ?></span>
    </a>

    <div class="sidebar">
        <?php $avatar = $profile->foto == null ? base_url() . 'assets/adminlte/dist/img/avatar5.png' : base_url() . $profile->foto; ?>
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

        <nav class="mt-2 mb-5">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent"
                id="tree-menus" data-widget="treeview" role="menu" data-accordion="false">
            </ul>
        </nav>
    </div>
</aside>

<script>
    // Inisialisasi Segment URL CodeIgniter ke JavaScript
    const page = '<?= $this->uri->segment(1) ?>';
    const pageact = '<?= $this->uri->segment(2); ?>';
    const baseUrlSistem = '<?= base_url(); ?>';

    // Gabungan segment saat ini untuk pencocokan yang lebih akurat
    const currentPath = pageact ? `${page}/${pageact}` : page;

    // Data Objek Navigasi Menu
    const menus = [
        {
            'header': 'HOME', 'cbt': '1',
            'menu': [
                {'name': 'Dashboard', 'link': 'dashboard', 'icon': 'fas fa-desktop', 'cbt': '1'},
                {
                    'name': 'Data Umum', 'icon': 'fas fa-server', 'cbt': '1',
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
                    'name': 'Data E-Learning', 'icon': 'fas fa-chalkboard', 'cbt': '0', 
                    'submenu': [
                        {'name': "Jadwal Pelajaran", 'link': "kelasjadwal", 'icon': 'fa fa-calendar-alt'},
                        {'name': "Materi", 'link': "kelasmateri/materi", 'icon': 'fa fa-pencil-ruler'},
                        {'name': "Tugas", 'link': "kelasmateri/tugas", 'icon': 'fa fa-drafting-compass'},
                        {'name': "Jadwal Materi/Tugas", 'link': "kelasmaterijadwal", 'icon': 'fa fa-calendar-alt'}, 
                    ] 
                },
                {
                    'name': 'Data Ujian', 'icon': 'fa fa-user-graduate', 'cbt': '1',
                    'submenu': [
                        {'name': "Jenis Ujian", 'link': "cbtjenis", 'icon': 'fa fa-project-diagram'},
                        {'name': "Sesi", 'link': "cbtsesi", 'icon': 'far fa-clock'},
                        {'name': "Ruang", 'link': "cbtruang", 'icon': 'fa fa-door-open'},
                        {'name': "Atur Ruang/Sesi", 'link': "cbtsesisiswa", 'icon': 'fa fa-user-clock'},
                        {'name': "Nomor Peserta", 'link': "cbtnomorpeserta", 'icon': 'far fa-id-card'},
                        {'name': "Bank Soal", 'link': "cbtbanksoal", 'icon': 'far fa-folder-open'},
                        {'name': "Converter Soal", 'link': "converter", 'icon': 'fas fa-sync-alt'},
                        {'name': "Jadwal", 'link': "cbtjadwal", 'icon': 'far fa-calendar-alt'},
                        {'name': "Alokasi Waktu", 'link': "cbtalokasi", 'icon': 'fa fa-clock-o'},
                        {'name': "Pengawas", 'link': "cbtpengawas", 'icon': 'fa fa-briefcase'},
                        {'name': "Token", 'link': "cbttoken", 'icon': 'fa fa-key'}
                    ]
                },
                {
                    'name': 'Pengumuman', 'link': 'pengumuman', 'icon': 'fas fa-bullhorn', 'cbt': '1'
                },
            ]
        },
        {
            'header': 'PELAKSANAAN', 'cbt': '1',
            'menu': [
                {
                    'name': 'Hasil E-Learning', 'icon': 'fas fa-microscope', 'cbt': '0',
                    'submenu': [
                        {'name': 'Nilai Harian', 'link': "kelasstatus", 'icon': 'far fa-clipboard'},
                        {'name': 'Kehadiran Harian', 'link': "kelasabsensiharian", 'icon': 'fa fa-user-check'},
                        {'name': 'Kehadiran Bulanan', 'link': "kelasabsensibulanan", 'icon': 'fa fa-tasks'},
                        {'name': 'Rekap Nilai', 'link': "kelasnilai", 'icon': 'fa fa-trophy'},
                    ]
                },
                {
                    'name': 'Pelaksanaan Ujian', 'icon': 'fas fa-graduation-cap', 'cbt': '1',
                    'submenu': [
                        {'name': 'Cetak', 'link': "cbtcetak", 'icon': 'fa fa-print'},
                        {'name': 'Status Siswa', 'link': "cbtstatus", 'icon': 'fa fa-user-clock'},
                        {'name': 'Hasil Ujian', 'link': "cbtnilai", 'icon': 'fa fa-file-alt'},
                        {'name': 'Analisis Soal', 'link': "cbtanalisis", 'icon': 'fa fa-chart-line'},
                        {'name': 'Rekap Nilai', 'link': "cbtrekap", 'icon': 'fas fa-trophy'},
                    ]
                },
            ]
        },
        {
            'header': 'RAPOR', 'cbt': '1',
            'menu': [
                {'name': 'Setting Rapor', 'link': 'rapor', 'icon': 'fas fa-book', 'cbt': '1'},
                {'name': 'Kumpulan Nilai Rapor', 'link': 'bukurapor', 'icon': 'fas fa-book', 'cbt': '0'},
                {'name': 'Alumni', 'link': 'dataalumni', 'icon': 'fa fa-users', 'cbt': '0'},
            ]
        },
        {
            'header': 'PENGATURAN', 'cbt': '1',
            'menu': [
                {'name': 'Profile Sekolah', 'link': 'settings', 'icon': 'fas fa-university', 'cbt': '1'},
                {
                    'name': 'User Management', 'icon': 'fa fa-users-cog', 'cbt': '1',
                    'submenu': [
                        {'name': 'Administrator', 'link': "useradmin", 'icon': 'fas fa-cog'},
                        {'name': 'Guru', 'link': "userguru", 'icon': 'fas fa-user-tie'},
                        {'name': 'Siswa', 'link': "usersiswa", 'icon': 'fas fa-users'}
                    ]
                },
                {
                    'name': 'Database', 'icon': 'fa fa-database', 'cbt': '1',
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
                    htmlMenu += `
                    <li class="nav-item">
                        <a href="${baseUrlSistem + menu.link}" class="nav-link ${isMenuActive ? "active" : ""}">
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