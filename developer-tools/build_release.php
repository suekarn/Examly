<?php
/**
 * Pembuat paket rilis. Dijalankan di komputer DEVELOPER (bukan di server sekolah).
 *
 * Contoh:
 *   php build_release.php --version=1.0.1 --files=./release_files ^
 *       --base-url=https://github.com/USERNAME/REPO/releases/download/v1.0.1 ^
 *       --changelog="Perbaikan live score" [--sql=migrate.sql] [--delete=a.php,b.php] ^
 *       [--min-version=1.0.0] [--requires-php=7.2] [--out=./dist]
 *
 * --files    folder berisi HANYA file yang berubah, dengan struktur sama seperti htdocs
 *            (mis. release_files/application/controllers/Ujian.php)
 * --sql      file SQL migrasi (dijalankan sekali di sisi pengguna)
 * --delete   daftar file yang harus dihapus di sisi pengguna, pisahkan dengan koma
 */
if (PHP_SAPI !== 'cli') exit("Jalankan lewat command line.\n");

$o = getopt('', array('version:', 'files:', 'changelog:', 'base-url:', 'sql:', 'delete:', 'min-version:', 'requires-php:', 'out:'));

function fail($m) { fwrite(STDERR, "ERROR: $m\n"); exit(1); }

if (empty($o['version']) || !preg_match('/^\d+(\.\d+){1,3}$/', $o['version'])) fail('--version wajib, format 1.0.1');
if (empty($o['base-url'])) fail('--base-url wajib (alamat folder tempat ZIP akan diunggah, https)');
if (stripos($o['base-url'], 'https://') !== 0) fail('--base-url harus https://');

$version = $o['version'];
$files   = isset($o['files']) ? realpath($o['files']) : false;
$sql     = isset($o['sql']) ? $o['sql'] : null;
$delete  = isset($o['delete']) ? $o['delete'] : null;
$out     = isset($o['out']) ? $o['out'] : __DIR__ . '/dist';

if (isset($o['files']) && !$files) fail('Folder --files tidak ditemukan');
if ($sql && !is_file($sql)) fail('File --sql tidak ditemukan');
if (!$files && !$sql && !$delete) fail('Tidak ada isi paket. Berikan --files, --sql, atau --delete');

if (!is_dir($out)) mkdir($out, 0755, true);
$zipPath = rtrim($out, '/\\') . "/update-$version.zip";
if (file_exists($zipPath)) unlink($zipPath);

$z = new ZipArchive;
if ($z->open($zipPath, ZipArchive::CREATE) !== true) fail('Gagal membuat ZIP');

$count = 0;
if ($files) {
    $root = rtrim($files, '/\\');
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS));
    foreach ($it as $f) {
        if (!$f->isFile()) continue;
        $rel = str_replace('\\', '/', substr($f->getPathname(), strlen($root) + 1));
        if (strpos($rel, '_update/') === 0) fail('Folder _update/ dicadangkan untuk sistem: ' . $rel);
        $z->addFile($f->getPathname(), $rel);
        $count++;
    }
}
if ($sql)    $z->addFile($sql, '_update/migrate.sql');
if ($delete) $z->addFromString('_update/delete.txt', implode("\n", array_map('trim', explode(',', $delete))) . "\n");
if (!$z->close()) fail('Gagal menyimpan ZIP');

$sha = hash_file('sha256', $zipPath);

// Tanda tangan (opsional, bila tools/secret.key ada)
$signature = '';
$keyFile = __DIR__ . '/secret.key';
if (is_file($keyFile)) {
    if (!function_exists('sodium_crypto_sign_detached')) fail('Ekstensi sodium belum aktif di PHP developer');
    $sk = base64_decode(trim(file_get_contents($keyFile)));
    $signature = base64_encode(sodium_crypto_sign_detached($sha . '|' . $version, $sk));
} else {
    echo "Peringatan: secret.key tidak ada, paket TIDAK ditandatangani. Jalankan keygen.php dulu.\n";
}

$manifest = array(
    'version'     => $version,
    'url'         => rtrim($o['base-url'], '/') . "/update-$version.zip",
    'sha256'      => $sha,
    'signature'   => $signature,
    'changelog'   => isset($o['changelog']) ? $o['changelog'] : '',
    'released_at' => date('Y-m-d'),
);
if (!empty($o['min-version']))  $manifest['min_version']  = $o['min-version'];
if (!empty($o['requires-php'])) $manifest['requires_php'] = $o['requires-php'];

file_put_contents(rtrim($out, '/\\') . '/version.json',
    json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n");

echo "Selesai.\n";
echo "  ZIP         : $zipPath ($count file" . ($sql ? ' + migrasi SQL' : '') . ")\n";
echo "  SHA-256     : $sha\n";
echo "  Tanda tangan: " . ($signature ? 'ya' : 'tidak') . "\n";
echo "  Manifest    : " . rtrim($out, '/\\') . "/version.json\n\n";
echo "Langkah berikutnya:\n";
echo "  1. Buat Release v$version di GitHub dan unggah update-$version.zip\n";
echo "  2. Commit isi dist/version.json ke repo (menggantikan version.json lama)\n";
