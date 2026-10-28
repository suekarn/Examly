<?php
// ====================================================================
// DETEKSI IP LAN SERVER
// ====================================================================
function get_server_lan_ip() {
    // Cara 1: tanya OS lewat UDP (tidak mengirim paket, hanya memilih interface aktif)
    $sock = @stream_socket_client('udp://8.8.8.8:53', $errno, $errstr, 1);
    if ($sock) {
        $name = stream_socket_get_name($sock, false); // contoh: 192.168.1.10:54321
        fclose($sock);
        $ip = explode(':', $name)[0];
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) && $ip !== '127.0.0.1') {
            return $ip;
        }
    }

    // Cara 2: hostname OS
    $ip = gethostbyname(gethostname());
    if ($ip !== '127.0.0.1' && filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
        return $ip;
    }

    // Cara 3: SERVER_ADDR
    if (!empty($_SERVER['SERVER_ADDR']) && $_SERVER['SERVER_ADDR'] !== '::1') {
        return $_SERVER['SERVER_ADDR'];
    }

    return '127.0.0.1';
}

// ====================================================================
// DETEKSI NAMA FOLDER APLIKASI (otomatis ikut jika di-rename)
// ====================================================================
function get_app_folder_path() {
    // Path folder di URL, misal "namaapp" dari /namaapp/index.php
    $script = isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '';
    // Di Windows dirname('/index.php') menghasilkan '\\' (backslash), jadi ubah ke '/' SESUDAH dirname
    $dir = str_replace('\\', '/', dirname($script));
    $dir = trim($dir, '/');
    return ($dir === '.' || $dir === '') ? '' : $dir;
}

function get_app_folder_name() {
    $path = get_app_folder_path();
    if ($path !== '') {
        return basename($path);
    }
    // Fallback: nama folder fisik (jika aplikasi di root webserver)
    if (defined('FCPATH')) {
        return basename(rtrim(FCPATH, '/\\'));
    }
    return '-';
}

$host_lan_ip     = get_server_lan_ip();
$app_folder_name = get_app_folder_name();
$app_path        = get_app_folder_path();

$scheme   = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$port     = isset($_SERVER['SERVER_PORT']) ? (int) $_SERVER['SERVER_PORT'] : 80;
$port_str = in_array($port, [80, 443]) ? '' : ':' . $port;
$lan_url  = $scheme . '://' . $host_lan_ip . $port_str . ($app_path !== '' ? '/' . $app_path : '');

// Penanda halaman dashboard (dipakai beberapa kali di bawah)
$is_dashboard = ($this->uri->segment(1) === 'dashboard' || (isset($judul) && stripos($judul, 'Dashboard') !== false));
?>

<style>
    /* Tombol buka/tutup sidebar (tiga garis) versi premium */
    .menu-toggle {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: flex-start;
        gap: 5px;
        width: 40px;
        height: 40px;
        padding: 0 11px;
        border-radius: 12px;
        border: 1px solid rgba(32, 201, 151, 0.35);
        background: linear-gradient(135deg, rgba(32, 201, 151, 0.14), rgba(29, 111, 216, 0.14));
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.08);
        cursor: pointer;
        text-decoration: none !important;
        transition: all .3s cubic-bezier(.4, 0, .2, 1);
    }
    .menu-toggle .bar {
        display: block;
        height: 2.5px;
        border-radius: 2px;
        background: #0f172a;
        transition: all .3s cubic-bezier(.4, 0, .2, 1);
    }
    .menu-toggle .b1 { width: 18px; }
    .menu-toggle .b2 { width: 12px; }
    .menu-toggle .b3 { width: 15px; }

    /* Saat sidebar diciutkan, panjang garis berbalik */
    body.sidebar-collapse .menu-toggle .b1 { width: 12px; }
    body.sidebar-collapse .menu-toggle .b2 { width: 18px; }
    body.sidebar-collapse .menu-toggle .b3 { width: 12px; }

    .menu-toggle:hover {
        background: linear-gradient(135deg, #20c997, #1d6fd8);
        border-color: transparent;
        box-shadow: 0 6px 16px rgba(29, 111, 216, 0.35);
        transform: translateY(-1px);
    }
    .menu-toggle:hover .bar,
    body.sidebar-collapse .menu-toggle:hover .bar {
        background: #ffffff;
        width: 18px;
    }
    .menu-toggle:active { transform: scale(.94); }

    /* Tema malam */
    html[data-theme="dark"] .menu-toggle {
        border-color: rgba(45, 212, 191, 0.35);
        background: linear-gradient(135deg, rgba(32, 201, 151, 0.18), rgba(29, 111, 216, 0.22));
    }
    html[data-theme="dark"] .menu-toggle .bar { background: #e2e8f0; }
</style>

<nav class="main-header navbar navbar-expand navbar-white navbar-light shadow">
    <ul class="navbar-nav align-items-center">
        <li class="nav-item mr-2">
            <a class="menu-toggle" data-widget="pushmenu" href="#" role="button" aria-label="Buka / tutup menu">
                <span class="bar b1"></span><span class="bar b2"></span><span class="bar b3"></span>
            </a>
        </li>

        <?php if ($this->uri->segment(1) !== 'dashboard') : ?>
            <li class="nav-item">
                <span class="nav-link text-dark"><b>TP: <?= isset($tp_active) ? $tp_active->tahun : "Belum di set" ?> Smt: <?= isset($smt_active) ? $smt_active->nama_smt : "Belum di set" ?></b></span>
            </li>
        <?php endif; ?>
    </ul>

    <ul class="navbar-nav ml-auto align-items-center">

        <?php if ($is_dashboard) : ?>
            <li class="nav-item d-none d-md-flex align-items-center mr-2">
                <div class="d-flex align-items-center bg-white px-3 py-1.5 rounded shadow-sm mr-2" style="border: 1px solid #e2e8f0; border-radius: 10px !important;">
                    <i class="far fa-clock text-secondary mr-2" style="font-size: 14px;"></i>
                    <span id="live-clock" class="font-weight-bold text-dark" style="font-size: 13.5px;">00:00:00</span>
                </div>

                <div class="d-flex align-items-center bg-white px-3 py-1.5 rounded shadow-sm" style="border: 1px solid #e2e8f0; border-radius: 10px !important;">
                    <i class="far fa-calendar-alt text-secondary mr-2" style="font-size: 14px;"></i>
                    <span id="live-date" class="font-weight-bold text-dark" style="font-size: 13.5px;"></span>
                </div>
            </li>
        <?php endif; ?>

        <!-- TOMBOL TEMA SIANG / MALAM -->
        <li class="nav-item ml-2">
            <div class="theme-switch" role="group" aria-label="Pilih tema">
                <button type="button" data-theme-set="light" title="Tema siang"><i class="fas fa-sun"></i></button>
                <button type="button" data-theme-set="dark" title="Tema malam"><i class="fas fa-moon"></i></button>
            </div>
        </li>

        <?php if ($this->uri->segment(1) !== 'dashboard') : ?>
            <li class="nav-item">
                <div id="live-clock-fallback" class="text-right font-weight-bold pr-2 text-dark" style="font-size: 14px;">00:00:00</div>
            </li>
        <?php endif; ?>

    </ul>
</nav>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // SCRIPT UPDATE LIVE CLOCK
    function updateClock() {
        const now = new Date();

        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        const timeString = `${hours}:${minutes}:${seconds}`;

        const clockEl = document.getElementById('live-clock');
        if (clockEl) clockEl.textContent = timeString;

        const clockFallbackEl = document.getElementById('live-clock-fallback');
        if (clockFallbackEl) clockFallbackEl.textContent = timeString;

        const dateEl = document.getElementById('live-date');
        if (dateEl && dateEl.textContent === "") {
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            dateEl.textContent = now.toLocaleDateString('id-ID', options);
        }
    }

    setInterval(updateClock, 1000);
    updateClock();
});
</script>