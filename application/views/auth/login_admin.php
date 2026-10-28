<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Panel Admin - GarudaCBT</title>
    
    <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/fontawesome-free/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%) !important;
            font-family: 'Segoe UI', Roboto, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-wrapper {
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }

        .login-logo {
            text-align: center;
            margin-bottom: 25px;
        }

        .login-logo a {
            color: #ffffff !important;
            font-size: 1.8rem;
            font-weight: 300;
            text-decoration: none !important;
            letter-spacing: 0.5px;
        }

        .login-logo b {
            font-weight: 700;
            color: #ef4444; 
        }

        .login-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            border: none;
            overflow: hidden;
        }

        .login-card-body {
            padding: 45px 35px !important;
        }

        .login-box-msg {
            text-align: center;
            color: #64748b;
            font-size: 0.95rem;
            font-weight: 500;
            margin-bottom: 35px;
        }

        .input-group-custom {
            position: relative;
            margin-bottom: 28px;
            border-bottom: 2px solid #e2e8f0;
            display: flex;
            align-items: center;
            padding-bottom: 6px;
            transition: all 0.3s ease;
        }

        .input-group-custom:focus-within {
            border-bottom-color: #ef4444;
        }

        .input-group-custom .icon-left {
            color: #94a3b8;
            margin-right: 15px;
            width: 20px;
            text-align: center;
            font-size: 1.2rem;
        }

        .input-group-custom:focus-within .icon-left {
            color: #ef4444;
        }

        .input-group-custom .form-control {
            border: none !important;
            box-shadow: none !important;
            padding: 6px 0;
            height: auto;
            background: transparent !important;
            font-size: 1.05rem;
            color: #1e293b;
        }

        .input-group-custom .form-control::placeholder {
            color: #cbd5e1;
        }

        .input-group-custom .btn-toggle-pwd {
            cursor: pointer;
            color: #cbd5e1;
            margin-left: 10px;
        }

        .custom-control-label {
            color: #64748b;
            font-size: 0.9rem;
            cursor: pointer;
        }

        .btn-admin-login {
            width: 100%;
            height: 50px;
            background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%);
            border: none;
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
            border-radius: 50px;
            box-shadow: 0 8px 20px rgba(239, 68, 68, 0.3);
            transition: all 0.3s ease;
        }

        .btn-admin-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(239, 68, 68, 0.45);
            color: white;
        }

        .btn-admin-login:disabled {
            background: #cbd5e1;
            box-shadow: none;
            cursor: not-allowed;
        }
    </style>
</head>
<body>

<div class="login-wrapper">
    <div class="login-logo">
        <a href="<?= base_url() ?>"><b>PANEL</b> ADMIN</a>
    </div>
    
    <div class="card login-card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Khusus Administrator & Dewan Guru</p>

            <div id="infoMessage" class="mb-3">
                <?php if($this->session->flashdata('message')): ?>
                    <div class="alert alert-warning text-center py-2 small">
                        <?= $this->session->flashdata('message'); ?>
                    </div>
                <?php endif; ?>
            </div>

            <form action="<?= base_url('login/proses_admin') ?>" method="post" id="loginFormAdmin">
                
                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

                <div class="input-group-custom">
                    <i class="fas fa-user-shield icon-left"></i>
                    <input type="text" name="identity" class="form-control" placeholder="Username Admin / Guru" required autocomplete="off" autofocus>
                </div>

                <div class="input-group-custom">
                    <i class="fas fa-lock icon-left"></i>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                    <i id="toggle-password" class="fas fa-eye btn-toggle-pwd"></i>
                </div>

                <div class="row align-items-center mt-4">
                    <div class="col-6">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="remember" class="custom-control-input" id="remember">
                            <label class="custom-control-label" for="remember">Ingat Saya</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <button type="submit" id="btnSubmit" class="btn btn-admin-login">
                            Masuk <i class="fas fa-sign-in-alt ml-1"></i>
                        </button>
                    </div>
                </div>
            </form>
            
        </div>
    </div>
</div>

<script src="<?= base_url() ?>assets/plugins/jquery/jquery.min.js"></script>
<script>
    $(document).ready(function(){
        // 1. Fitur Show/Hide Password
        $('#toggle-password').on('click', function () {
            const passwordField = $('#password');
            const fieldType = passwordField.attr('type') === 'password' ? 'text' : 'password';
            passwordField.attr('type', fieldType);
            $(this).toggleClass('fa-eye fa-eye-slash');
        });

        // 2. Pemrosesan Pengiriman Data Form secara Async (AJAX JSON)
        $('#loginFormAdmin').on('submit', function(e) {
            e.preventDefault(); 
            
            var infobox = $('#infoMessage');
            var btn = $('#btnSubmit');
            
            infobox.html('<div class="alert alert-info text-center py-2"><i class="fas fa-spinner fa-spin mr-2"></i>Memeriksa hak akses...</div>');
            btn.attr('disabled', 'disabled').text('Loading...');

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json', 
                success: function(res){
                    btn.removeAttr('disabled').html('Masuk <i class="fas fa-sign-in-alt ml-1"></i>');
                    
                    if(res.status){
                        infobox.html('<div class="alert alert-success text-center py-2">Verifikasi Sukses! Mengalihkan...</div>');
                        window.location.href = '<?= base_url() ?>' + res.url;
                    } else {
                        var errMsg = res.failed ? res.failed : 'Akses Ditolak! Kredensial tidak valid.';
                        infobox.html('<div class="alert alert-danger text-center py-2">' + errMsg + '</div>');
                    }
                },
                error: function(xhr) {
                    btn.removeAttr('disabled').html('Masuk <i class="fas fa-sign-in-alt ml-1"></i>');
                    console.log("Error Log Server:", xhr.responseText);
                    infobox.html('<div class="alert alert-warning text-center py-2">Gagal membaca respon server. Hubungi Teknisi.</div>');
                }
            });
        });
    });
</script>

</body>
</html>