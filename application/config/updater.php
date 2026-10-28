<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Alamat version.json di GitHub
$config['updater_manifest_url'] = 'https://raw.githubusercontent.com/suekarn/Examly/main/version.json';

// Cek GitHub paling cepat tiap berapa detik (21600 = 6 jam)
$config['updater_cache_ttl'] = 21600;

// Jumlah backup file / database yang disimpan
$config['updater_keep_backups'] = 5;

// Backup database sebelum update: TRUE / FALSE / 'auto' (hanya jika paket memuat migrasi SQL)
$config['updater_backup_db'] = 'auto';

// File & folder yang TIDAK boleh ditimpa paket update
$config['updater_protected'] = array(
    'application/config/database.php',
    'application/config/config.php',
    'application/config/updater.php',
    'application/config/version.php',
    'application/updater_data/',
    'application/cache/',
    'application/logs/',
    'uploads/',
    'backups/',
    'license.txt',
);

// Daftar putih: updater HANYA boleh mengubah file di folder-folder ini.
// Kosongkan (array()) jika ingin memperbarui seluruh isi paket.
// File di updater_protected tetap tidak disentuh walaupun ada di dalam folder ini.
$config['updater_only'] = array(
    'application/config/',
    'application/controllers/',
    'application/helpers/',
    'application/libraries/',
    'application/models/',
    'application/users/',
    'application/views/',
);

// Tidak dipakai untuk GitHub publik. Biarkan kosong.
$config['updater_license_key'] = '';

// Tanda tangan digital (opsional). Kosongkan jika paket tidak ditandatangani.
$config['updater_public_key']        = '';
$config['updater_require_signature'] = FALSE;

// TRUE = halaman updater boleh dibuka dari komputer server sendiri (127.0.0.1) tanpa login
$config['updater_allow_localhost'] = FALSE;

// Cadangan jika aplikasi TIDAK memakai Ion Auth: isi nama key session admin dan nilainya.
$config['updater_admin_session_key']    = '';
$config['updater_admin_session_values'] = array();

// Mode CLI / Task Scheduler (php index.php updater cli_run)
$config['updater_auto_apply'] = FALSE;
$config['updater_auto_hours'] = array(2, 3);