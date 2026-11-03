<?php
/**
 * Created by IntelliJ IDEA.
 * User: multazam
 * Date: 18/07/20
 * Time: 20:15
 */
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DEBUG</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f4f7fe;
            color: #2b3674;
            padding-top: 20px;
            padding-bottom: 40px;
        }
        
        /* Desain Card Modern */
        .custom-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0px 14px 40px rgba(112, 144, 176, 0.08);
            background: #ffffff;
            margin-bottom: 24px;
            overflow: hidden;
        }
        
        .custom-card .card-header {
            background: #ffffff;
            border-bottom: 1px solid #f1f5f9;
            padding: 18px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .custom-card .card-title {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 0;
            letter-spacing: 0.5px;
        }
        
        /* Variasi Warna Aksen Header */
        .accent-info .card-title { color: #0284c7; }
        .accent-menu .card-title { color: #4318ff; }
        .accent-jadwal .card-title { color: #ea580c; }
        
        /* Tombol Reload Modern */
        .btn-reload {
            background-color: #f4f7fe;
            color: #a3aed0;
            border: none;
            border-radius: 10px;
            padding: 6px 14px;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }
        .btn-reload:hover {
            background-color: #e2e8f0;
            color: #2b3674;
        }

        /* Menu Grid Style */
        .menu-item {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            cursor: pointer;
            padding: 12px;
            border-radius: 12px;
        }
        .menu-item:hover {
            transform: translateY(-4px);
            background-color: #f8fafc;
        }
        .menu-icon {
            width: 64px;
            height: 64px;
            object-fit: contain;
            margin-bottom: 10px;
            filter: drop-shadow(0px 4px 8px rgba(0,0,0,0.04));
        }
        .menu-title {
            font-size: 0.85rem;
            font-weight: 600;
            color: #475569;
            line-height: 1.3;
        }

        /* Mengoptimalkan tampilan data debug di paling atas */
        .debug-alert {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
            border-radius: 12px;
            padding: 12px 20px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

<div class="container">
    
    <?php if (!empty($data)): ?>
        <div class="debug-alert">
            <i class="fas fa-exclamation-triangle mr-2"></i> <?= $data ?>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-12">
            <div class="card custom-card accent-info">
                <div class="card-header">
                    <div class="card-title">
                        <i class="fas fa-info-circle mr-2"></i>INFO
                    </div>
                    <button type="button" onclick="location.reload();" class="btn btn-reload">
                        <i class="fa fa-sync-alt mr-1"></i> <span>Reload</span>
                    </button>
                </div>
                <div class="card-body py-4">
                    <p class="text-muted text-center my-2">Belum ada informasi terbaru saat ini.</p>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card custom-card accent-menu">
                <div class="card-header">
                    <div class="card-title">
                        <i class="fas fa-th-large mr-2"></i>MENU UTAMA
                    </div>
                </div>
                <div class="card-body px-3 pt-4">
                    <div class="row no-gutters">
                        <?php if (isset($menu) && is_array($menu)): ?>
                            <?php foreach ($menu as $m): ?>
                                <div class="col-4 col-sm-3 col-md-2 text-center mb-4">
                                    <div class="menu-item">
                                        <img class="img-fluid menu-icon" src="<?= base_url() ?>/assets/app/img/<?= $m->icon ?>" alt="Icon" />
                                        <div class="menu-title text-truncate-2"><?= $m->title ?></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-4 col-sm-3 col-md-2 text-center mb-4">
                                <div class="menu-item">
                                    <img class="img-fluid menu-icon" src="https://cdn-icons-png.flaticon.com/512/1946/1946488.png" alt="Icon" />
                                    <div class="menu-title">Sample Menu</div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card custom-card accent-jadwal">
                <div class="card-header">
                    <div class="card-title">
                        <i class="fas fa-calendar-day mr-2"></i>JADWAL HARI INI
                    </div>
                    <button type="button" onclick="location.reload();" class="btn btn-reload">
                        <i class="fa fa-sync-alt mr-1"></i> <span>Reload</span>
                    </button>
                </div>
                <div class="card-body py-4">
                    <p class="text-muted text-center my-2">Tidak ada jadwal untuk hari ini.</p>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>