<?php
/**
 * DIAGNOSA SEMENTARA — taruh di: C:\xampp\htdocs\ujian\lgtest.php
 * Buka: http://localhost/ujian/lgtest.php
 * HAPUS file ini setelah selesai (menampilkan kode lisensi).
 */
header('Content-Type: text/plain; charset=utf-8');

define('BASEPATH', __DIR__ . '/system/');
define('APPPATH', __DIR__ . '/application/');

$helper = APPPATH . 'helpers/license_helper.php';
echo "== FILE ==\n";
echo "helper path    : $helper\n";
echo "helper ada     : " . (is_file($helper) ? 'ya' : 'TIDAK') . "\n";
if (is_file($helper)) {
    $src = file_get_contents($helper);
    echo "ukuran / mtime : " . strlen($src) . " byte / " . date('Y-m-d H:i:s', filemtime($helper)) . "\n";
    echo "versi penanda  : " . (preg_match('/LG_HELPER_VERSION:\s*(\d+)/', $src, $m) ? $m[1] : '(tidak ada = file lama)') . "\n";
}
$kf = APPPATH . 'config/license_key.php';
echo "license_key.php: " . (is_file($kf) ? 'ada' : 'TIDAK ADA') . "\n";
if (is_file($kf)) {
    $raw = file_get_contents($kf);
    echo "isi baris \$key : " . (preg_match('/^.*\$key.*$/m', $raw, $m) ? trim($m[0]) : '(tidak ditemukan)') . "\n";
    echo "BOM di awal    : " . (substr($raw, 0, 3) === "\xEF\xBB\xBF" ? 'YA' : 'tidak') . "\n";
}

echo "\n== PHP ==\n";
echo "versi PHP         : " . PHP_VERSION . "\n";
echo "allow_url_fopen   : " . (ini_get('allow_url_fopen') ? 'on' : 'OFF') . "\n";
echo "curl              : " . (function_exists('curl_init') ? 'ada' : 'tidak ada') . "\n";
echo "opcache           : " . (function_exists('opcache_get_status') && @opcache_get_status(false) ? 'AKTIF' : 'nonaktif') . "\n";
echo "opcache validate  : " . ini_get('opcache.validate_timestamps') . " (freq " . ini_get('opcache.revalidate_freq') . ")\n";

if (!is_file($helper)) exit;
require_once $helper;

echo "\n== HASIL HELPER ==\n";
$key = lg_license_key();
echo "kode terbaca      : " . ($key === '' ? '(KOSONG)' : $key) . "\n";
echo "domain dikirim    : " . lg_site_host() . "\n";
$url = (function_exists('lg_server_url') ? lg_server_url() : LG_LICENSE_SERVER_URL);
echo "URL server        : $url\n";
$full = $url . '?key=' . urlencode($key) . '&domain=' . urlencode(lg_site_host());
echo "URL lengkap       : $full\n";

echo "\n== PANGGIL SERVER ==\n";
$raw = function_exists('lg_http_get') ? lg_http_get($full) : @file_get_contents($full);
echo "balasan mentah    : " . ($raw === false ? '(GAGAL / tidak ada balasan)' : $raw) . "\n";

echo "\n== KONSTANTA ==\n";
echo "LG_LICENSE_SERVER_URL = " . LG_LICENSE_SERVER_URL . "\n";
echo "LG_CACHE_FILE         = " . LG_CACHE_FILE . "\n";
