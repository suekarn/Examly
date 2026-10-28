<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Login - GarudaCBT</title>

    <!-- Font & Icons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Roboto, sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: #f0f2f5;
            padding-top: 80px;
        }

        .header-top {
            background: #326698;
            background-image: url('https://pusmendik.kemdikbud.go.id/an/images/bg-top.png');
            background-size: contain;
            background-repeat: no-repeat;
            height: 100px;
            display: flex;
            align-items: center; 
            padding-left: 30px;
            color: white;
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 100; 
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .header-logo img { 
            height: 60px; 
            margin-right: 15px; 
        }

        .container-custom {
            position: relative;
            width: 850px;
            max-width: 95%;
            height: 520px;
            background: #fff;
            border-radius: 30px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin: 20px auto;
        }

        .form-box {
            position: absolute;
            top: 0;
            height: 100%;
            width: 50%;
            background: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 35px;
            transition: all 0.6s ease-in-out;
        }

        /* Form Siswa berada di sisi kiri secara default */
        .form-box.login-siswa {
            left: 0;
            z-index: 2;
            opacity: 1;
        }

        /* Form Admin berada di sisi kiri tapi tertutup overlay/tersembunyi */
        .form-box.login-admin {
            left: 0;
            z-index: 1;
            opacity: 0;
        }

        /* Saat class .active diaktifkan di .container-custom */
        .container-custom.active .form-box.login-siswa {
            transform: translateX(100%);
            opacity: 0;
            z-index: 1;
        }

        .container-custom.active .form-box.login-admin {
            transform: translateX(100%);
            opacity: 1;
            z-index: 5;
        }

        form {
            width: 100%;
        }

        .form-box h1 {
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 15px;
            text-align: center;
            color: #2d3436;
        }

        .input-group-custom { 
            position: relative; 
            margin-bottom: 15px; 
            display: flex; 
            align-items: center; 
            background: #f8fafc; 
            border-radius: 50px; 
            padding: 5px 18px; 
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }
        
        .input-group-custom:focus-within {
            background: #ffffff;
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.15);
        }

        .input-group-custom.admin-input:focus-within {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.15);
        }

        .input-group-custom i { 
            color: #636e72; 
            margin-right: 10px; 
            width: 16px; 
            text-align: center; 
            font-size: 0.95rem;
        }
        
        .input-group-custom .form-control { 
            border: none !important; 
            box-shadow: none !important; 
            padding: 8px 0; 
            height: auto; 
            background: transparent !important; 
            font-size: 0.95rem; 
            color: #334155;
        }
        
        .input-group-custom select.form-control {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            cursor: pointer;
            text-align: center;
            text-align-last: center; 
            padding-right: 20px;
        }

        .select-custom::after {
            content: '\f107';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            position: absolute;
            right: 18px;
            color: #636e72;
            pointer-events: none;
            font-size: 0.9rem;
        }

        .btn-custom { 
            width: 100%; 
            height: 44px; 
            background: #007bff; 
            border: none; 
            color: white; 
            font-weight: 600; 
            font-size: 0.95rem; 
            border-radius: 50px; 
            margin-top: 10px; 
            box-shadow: 0 8px 20px rgba(0, 123, 255, 0.2); 
            transition: 0.3s; 
            cursor: pointer;
        }

        .btn-custom.admin-mode { 
            background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%); 
            box-shadow: 0 8px 20px rgba(239, 68, 68, 0.25); 
        }

        .btn-download {
            display: block;
            text-align: center;
            margin-top: 12px;
            font-size: 0.8rem;
            color: #007bff;
            font-weight: 600;
            text-decoration: none;
        }

        .btn-download:hover {
            text-decoration: underline;
        }

        /* SLIDING OVERLAY PANEL */
        .toggle-container {
            position: absolute;
            top: 0;
            left: 50%;
            width: 50%;
            height: 100%;
            overflow: hidden;
            transition: all 0.6s ease-in-out;
            border-radius: 150px 0 0 100px;
            z-index: 1000;
        }

        .container-custom.active .toggle-container {
            transform: translateX(-100%);
            border-radius: 0 150px 100px 0;
        }

        .toggle {
            background: linear-gradient(135deg, #326698, #007bff);
            height: 100%;
            color: #fff;
            position: relative;
            left: -100%;
            width: 200%;
            transform: translateX(0);
            transition: all 0.6s ease-in-out;
        }

        .container-custom.active .toggle {
            transform: translateX(50%);
            background: linear-gradient(135deg, #1e293b, #ef4444);
        }

        .toggle-panel {
            position: absolute;
            width: 50%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 30px;
            text-align: center;
            top: 0;
            transition: all 0.6s ease-in-out;
        }

        .toggle-left {
            transform: translateX(-200%);
        }

        .container-custom.active .toggle-left {
            transform: translateX(0);
        }

        .toggle-right {
            right: 0;
            transform: translateX(0);
        }

        .container-custom.active .toggle-right {
            transform: translateX(200%);
        }

        .toggle-panel h1 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .toggle-panel p {
            font-size: 13px;
            margin-bottom: 20px;
            opacity: 0.9;
        }

        .toggle-panel .btn-outline {
            width: 150px;
            height: 42px;
            background: transparent;
            border: 2px solid #fff;
            color: #fff;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }

        .toggle-panel .btn-outline:hover {
            background: #fff;
            color: #326698;
        }

        .alert-custom-warning {
            background-color: #fff3cd;
            border: 1px solid #ffeeba;
            color: #856404;
            padding: 8px 12px;
            border-radius: 50px; 
            font-size: 0.78rem;
            text-align: center;
            margin-bottom: 15px;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .container-custom {
                height: 580px;
            }
            .form-box {
                width: 100%;
            }
            .container-custom.active .form-box.login-siswa {
                display: none;
            }
            .container-custom.active .form-box.login-admin {
                transform: translateX(0);
                opacity: 1;
            }
            .toggle-container {
                display: none;
            }
            .mobile-switch {
                display: block !important;
                text-align: center;
                margin-top: 15px;
            }
        }

        .mobile-switch {
            display: none;
        }
    </style>
</head>
<body>

<header class="header-top">
    <div class="header-logo">
        <img src="<?= base_url('assets/img/wall212.png') ?>" alt="Logo" onerror="this.src='https://via.placeholder.com/60'">
    </div>
    <div class="header-text">
        <b style="font-size: 1.2rem;">Application System</b>
        <small style="display: block; opacity: 0.8;">CBT Garuda Exam System</small>
    </div>
</header>

<?php 
$options_html = '';
$is_jadwal_aktif = false;

try {
    include(APPPATH . 'config/database.php');
    $db_config = $db[$active_group];
    
    $pdo = new PDO("mysql:host={$db_config['hostname']};dbname={$db_config['database']};charset=utf8", $db_config['username'], $db_config['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt_jadwal = $pdo->prepare("SELECT id_jadwal, id_bank FROM cbt_jadwal WHERE status = '1'");
    $stmt_jadwal->execute();
    $jadwal_list = $stmt_jadwal->fetchAll(PDO::FETCH_ASSOC);
    
    if (!empty($jadwal_list)) {
        $is_jadwal_aktif = true;
        foreach ($jadwal_list as $row) {
            $id_jadwal = $row['id_jadwal'];
            $id_bank = $row['id_bank'];
            $text_tampil = "Ujian Aktif";
            
            if (!empty($id_bank)) {
                $stmt_bank = $pdo->prepare("SELECT bank_kode FROM cbt_bank_soal WHERE id_bank = ?");
                $stmt_bank->execute([$id_bank]);
                $bank_data = $stmt_bank->fetch(PDO::FETCH_ASSOC);
                
                if (!empty($bank_data['bank_kode'])) {
                    $kode_soal = strtoupper($bank_data['bank_kode']);
                    
                    switch ($kode_soal) {
                        case 'AGA': $text_tampil = "Pendidikan Agama Islam"; break;
                        case 'PJOK': $text_tampil = "Pendidikan Jasmani Olahraga & Kesehatan"; break;
                        case 'IPA': $text_tampil = "Ilmu Pengetahuan Alam (IPA)"; break;
                        case 'MTK': $text_tampil = "Matematika"; break;
                        case 'BIN':
                        case 'INDO': $text_tampil = "Bahasa Indonesia"; break;
                        case 'BIG':
                        case 'ING': $text_tampil = "Bahasa Inggris"; break;
                        default: $text_tampil = $kode_soal; break;
                    }
                }
            }
            $options_html .= '<option value="' . htmlspecialchars($id_jadwal) . '" style="text-align: center;">' . htmlspecialchars($text_tampil) . '</option>';
        }
    }
} catch (Exception $e) {
    $options_html = '<option value="" disabled style="text-align: center;">Gagal memuat daftar ujian</option>';
}
?>

<div class="container-custom" id="container">
    
    <!-- 1. FORM LOGIN SISWA -->
    <div class="form-box login-siswa">
        <?= form_open("auth/cek_login", array('id' => 'login-siswa-form')); ?>
            <h1>Login Siswa</h1>
            
            <div class="infoMessage mb-2">
                <?= isset($message) ? $message : ''; ?>
            </div>

            <input type="hidden" name="role_akses" value="siswa">
            
            <div class="input-group-custom">
                <i class="fas fa-user-circle"></i>
                <?= form_input($identity, '', 'required class="form-control" placeholder="Username Siswa" autocomplete="off"'); ?>
            </div>

            <div class="input-group-custom">
                <i class="fas fa-lock"></i>
                <?= form_password($password, '', 'required class="form-control pass-field" placeholder="Password"'); ?>
                <i class="fas fa-eye toggle-password" style="cursor:pointer; color:#aaa; margin-left: 5px;"></i>
            </div>

            <div class="input-group-custom select-custom mapel-wrapper" <?= !$is_jadwal_aktif ? 'style="display: none;"' : ''; ?>>
                <i class="fas fa-book"></i>
                <select name="id_ujian" class="form-control id_ujian_select" <?= $is_jadwal_aktif ? 'required' : ''; ?>>
                    <?php if ($is_jadwal_aktif): ?>
                        <option value="" disabled selected style="text-align: center;">-- Pilih Mata Pelajaran --</option>
                        <?= $options_html; ?>
                    <?php else: ?>
                        <option value="" disabled selected style="text-align: center;">Belum Ada Ujian Aktif</option>
                    <?php endif; ?>
                </select>
            </div>

            <?php if (!$is_jadwal_aktif): ?>
                <div class="alert-custom-warning">
                    <i class="fas fa-exclamation-triangle mr-1"></i> Tidak ada jadwal ujian aktif.
                </div>
            <?php endif; ?>

            <button type="submit" class="btn-custom submit-btn">Masuk Ujian</button>

            <a href="download/aplikasi.apk" download class="btn-download">
                <i class="fas fa-download mr-1"></i> Unduh Aplikasi Ujian
            </a>

            <div class="mobile-switch">
                <a href="#" class="btn-switch-admin" style="font-size:0.82rem; color:#636e72;">Login sebagai <b>Admin/Guru</b>?</a>
            </div>
        <?= form_close(); ?>
    </div>

    <!-- 2. FORM LOGIN ADMIN (KONTROLER PROSES ADMIN SEPERTI DI FILE ORIGINAL) -->
    <div class="form-box login-admin">
        <form action="<?= base_url('login/proses_admin') ?>" method="post" id="login-admin-form">
            <h1>Login Admin</h1>
            
            <div class="infoMessage mb-2">
                <?php if($this->session->flashdata('message')): ?>
                    <div class="alert alert-warning text-center py-1 small">
                        <?= $this->session->flashdata('message'); ?>
                    </div>
                <?php endif; ?>
            </div>

            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
            
            <div class="input-group-custom admin-input">
                <i class="fas fa-user-shield"></i>
                <input type="text" name="identity" class="form-control" placeholder="Username Admin / Guru" required autocomplete="off">
            </div>

            <div class="input-group-custom admin-input">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" class="form-control pass-field" placeholder="Password Admin" required>
                <i class="fas fa-eye toggle-password" style="cursor:pointer; color:#aaa; margin-left: 5px;"></i>
            </div>

            <button type="submit" class="btn-custom admin-mode submit-btn">Login Admin <i class="fas fa-sign-in-alt ml-1"></i></button>

            <div class="mobile-switch">
                <a href="#" class="btn-switch-siswa" style="font-size:0.82rem; color:#636e72;">Kembali ke <b>Login Siswa</b></a>
            </div>
        </form>
    </div>

    <!-- 3. SLIDING OVERLAY PANEL -->
    <div class="toggle-container">
        <div class="toggle">
            <div class="toggle-panel toggle-left">
                <h1>Halo, Siswa!</h1>
                <p>Siap untuk mengikuti ujian online hari ini?</p>
                <button class="btn-outline" id="btn-to-siswa">Login Siswa</button>
            </div>
            <div class="toggle-panel toggle-right">
                <h1>Halo, Admin/Guru!</h1>
                <p>Ingin mengelola data ujian atau melihat hasil nilai siswa?</p>
                <button class="btn-outline" id="btn-to-admin">Login Admin</button>
            </div>
        </div>
    </div>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    $(document).ready(function(){
        const container = $('#container');

        // Buka Form Admin (Geser)
        $('#btn-to-admin, .btn-switch-admin').on('click', function(e){
            e.preventDefault();
            container.addClass('active');
        });

        // Buka Form Siswa (Kembali)
        $('#btn-to-siswa, .btn-switch-siswa').on('click', function(e){
            e.preventDefault();
            container.removeClass('active');
        });

        // Toggle Password Eye
        $('.toggle-password').on('click', function () {
            const passInput = $(this).siblings('.pass-field');
            const type = passInput.attr('type') === 'password' ? 'text' : 'password';
            passInput.attr('type', type);
            $(this).toggleClass('fa-eye fa-eye-slash');
        });

        // AJAX Form Login Siswa
        $('#login-siswa-form').on('submit', function(e){
            e.preventDefault(); 
            var form = $(this);
            var infobox = form.find('.infoMessage');
            var btn = form.find('.submit-btn');
            
            infobox.html('<div class="alert alert-info py-1 small">Memeriksa data...</div>');
            btn.attr('disabled', 'disabled').text('Tunggu...');

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                success: function(data){
                    btn.removeAttr('disabled').text('Masuk Ujian');
                    try {
                        var res = (typeof data === 'object') ? data : JSON.parse(data);
                        if(res.status){
                            infobox.html('<div class="alert alert-success py-1 small">Login Berhasil</div>');
                            window.location.href = '<?= base_url() ?>' + res.url;
                        } else {
                            infobox.html('<div class="alert alert-danger py-1 small">' + (res.failed ? res.failed : 'Username atau Password Salah!') + '</div>');
                        }
                    } catch (e) {
                        infobox.html('<div class="alert alert-warning py-1 small">Terjadi kesalahan pada respon server.</div>');
                    }
                },
                error: function() {
                    btn.removeAttr('disabled').text('Masuk Ujian');
                    infobox.html('<div class="alert alert-danger py-1 small">Gagal terhubung ke server.</div>');
                }
            });
        });

        // AJAX Form Login Admin
        $('#login-admin-form').on('submit', function(e){
            e.preventDefault(); 
            var form = $(this);
            var infobox = form.find('.infoMessage');
            var btn = form.find('.submit-btn');
            
            infobox.html('<div class="alert alert-info py-1 small"><i class="fas fa-spinner fa-spin mr-1"></i>Memeriksa hak akses...</div>');
            btn.attr('disabled', 'disabled').text('Loading...');

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                dataType: 'json',
                success: function(res){
                    btn.removeAttr('disabled').html('Login Admin <i class="fas fa-sign-in-alt ml-1"></i>');
                    if(res.status){
                        infobox.html('<div class="alert alert-success py-1 small">Verifikasi Sukses! Mengalihkan...</div>');
                        window.location.href = '<?= base_url() ?>' + res.url;
                    } else {
                        var errMsg = res.failed ? res.failed : 'Akses Ditolak! Kredensial tidak valid.';
                        infobox.html('<div class="alert alert-danger py-1 small">' + errMsg + '</div>');
                    }
                },
                error: function(xhr) {
                    btn.removeAttr('disabled').html('Login Admin <i class="fas fa-sign-in-alt ml-1"></i>');
                    console.log("Error Log Server:", xhr.responseText);
                    infobox.html('<div class="alert alert-warning py-1 small">Gagal membaca respon server. Hubungi Teknisi.</div>');
                }
            });
        });
    });
</script>

</body>
</html>