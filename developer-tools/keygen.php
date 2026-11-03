<?php
/**
 * Membuat pasangan kunci tanda tangan digital (Ed25519). Jalankan SEKALI di komputer developer:
 *   php keygen.php
 * - secret.key  : RAHASIA. Simpan aman, JANGAN diunggah ke GitHub / dibagikan.
 * - Kunci publik: ditempel ke application/config/updater.php ($config['updater_public_key']) di setiap instalasi.
 */
if (PHP_SAPI !== 'cli') exit("Jalankan lewat command line.\n");
if (!function_exists('sodium_crypto_sign_keypair')) exit("Ekstensi sodium belum aktif (php.ini: extension=sodium).\n");

$file = __DIR__ . '/secret.key';
if (file_exists($file)) exit("secret.key sudah ada. Hapus manual bila benar-benar ingin membuat kunci baru (semua instalasi lama harus diganti kunci publiknya).\n");

$kp = sodium_crypto_sign_keypair();
file_put_contents($file, base64_encode(sodium_crypto_sign_secretkey($kp)));

echo "Kunci dibuat.\n\n";
echo "Kunci PUBLIK (tempel ke updater.php):\n";
echo base64_encode(sodium_crypto_sign_publickey($kp)) . "\n\n";
echo "Kunci rahasia disimpan di: $file\n";
