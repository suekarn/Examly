# Updater Online untuk Aplikasi CodeIgniter 3 (VERSI FINAL)

Developer merilis update sekali, semua instalasi (sekolah) bisa memasangnya lewat internet dari halaman admin.

## Isi paket

```
htdocs/                       -> salin ke C:\xampp\htdocs (struktur sudah sama)
  application/config/updater.php      pengaturan (tidak ditimpa update)
  application/config/version.php      nomor versi terpasang
  application/controllers/Updater.php halaman & endpoint
  application/libraries/App_updater.php  mesin update
  application/views/updater/index.php    dashboard
developer-tools/              -> HANYA di komputer developer
  build_release.php           membuat ZIP + version.json
  keygen.php                  membuat kunci tanda tangan digital
  version.json.example
```

## Fitur

- Cek versi, unduh, dan pasang dari satu tombol di dashboard
- Verifikasi hash SHA-256 + tanda tangan digital Ed25519 (opsional, sangat disarankan)
- Backup otomatis file yang akan ditimpa, serta backup database bila ada migrasi SQL
- Rollback otomatis bila penulisan file atau migrasi gagal, rollback manual dari dashboard
- Migrasi database (`_update/migrate.sql`) dan penghapusan file lama (`_update/delete.txt`)
- File penting tidak pernah ditimpa: `database.php`, `config.php`, `updater.php`, `uploads/`, `backups/`
- Perlindungan path berbahaya (zip-slip), kunci proses (tidak bisa jalan ganda), HTTPS wajib
- Update terjadwal opsional lewat Task Scheduler, dibatasi jam tertentu

## A. Pemasangan (sekali, di tiap server)

1. **Backup** `C:\xampp\htdocs` dan database. Coba dulu di salinan, bukan server ujian.
2. Pastikan di `php.ini` aktif: `extension=zip`, `extension=curl`, `extension=openssl` (opsional: `extension=sodium` untuk tanda tangan). Restart Apache.
3. Salin isi folder `htdocs/` ke `C:\xampp\htdocs\` (gabungkan dengan folder `application` yang ada).
4. Edit `application/config/version.php` sesuai versi aplikasi saat ini.
5. Edit `application/config/updater.php`, minimal `updater_manifest_url`.
6. Buka `http://localhost/updater`. Seharusnya tampil dashboard.

Jika muncul 404, pengecekan hak akses belum cocok dengan sistem login aplikasi Anda. Buka dari komputer server (localhost), lalu sesuaikan `updater_admin_session_key` / `updater_admin_session_values`, atau cek metode login di `Updater.php` (`is_allowed()`). Setelah login admin terbukti berfungsi, set `updater_allow_localhost` ke `FALSE`.

## B. Menyiapkan server update (GitHub, gratis)

1. Buat repo GitHub (mis. `garudacbt-update`). Repo public paling mudah. Isinya hanya `version.json`.
2. `updater_manifest_url` = alamat **Raw** dari `version.json`:
   `https://raw.githubusercontent.com/USERNAME/REPO/main/version.json`
3. Paket ZIP diunggah lewat **Releases** di repo yang sama.

## C. Kunci tanda tangan (sekali, di komputer developer)

```
cd developer-tools
php keygen.php
```

- Tempel **kunci publik** yang tampil ke `updater_public_key` di `updater.php` pada semua instalasi.
- `secret.key` jangan pernah diunggah atau dibagikan. Simpan cadangannya di tempat aman. Jika hilang, kunci harus diganti di semua instalasi.
- Setelah semua instalasi punya kunci publik, set `updater_require_signature` ke `TRUE`.

Manfaatnya: walau akun GitHub Anda diretas, penyerang tidak bisa membuat update palsu yang diterima, karena tidak punya `secret.key`.

## D. Merilis update (alur kerja developer)

1. Siapkan folder `release_files/` berisi **hanya file yang berubah**, dengan struktur seperti htdocs:
   ```
   release_files/application/controllers/Ujian.php
   release_files/assets/js/app.js
   ```
2. Jika ada perubahan database, tulis `migrate.sql`. Contoh:
   ```sql
   ALTER TABLE ujian ADD COLUMN catatan TEXT NULL;
   CREATE TABLE IF NOT EXISTS pengumuman (id INT AUTO_INCREMENT PRIMARY KEY, isi TEXT);
   ```
   Tulis dengan aman diulang (`IF NOT EXISTS`) bila memungkinkan. Pisahkan perintah dengan `;` di akhir baris.
3. Bangun paket:
   ```
   php build_release.php --version=1.0.1 --files=./release_files ^
     --base-url=https://github.com/USERNAME/REPO/releases/download/v1.0.1 ^
     --changelog="Perbaikan live score" --sql=migrate.sql --min-version=1.0.0
   ```
   Opsi lain: `--delete=file1.php,file2.php` untuk menghapus file lama.
4. Hasil di folder `dist/`: `update-1.0.1.zip` dan `version.json`.
5. Di GitHub: buat Release dengan tag `v1.0.1`, unggah ZIP-nya.
6. Ganti `version.json` di repo dengan yang baru dari `dist/`, lalu commit.
7. Di sisi sekolah, buka `/updater`, klik **Cek ulang**, lalu **Update sekarang**.

`min_version` penting: kalau sebuah rilis butuh versi tertentu lebih dulu, sekolah yang terlalu lama tidak ikut terpasang loncat dan diminta memasang versi perantara.

## E. Update otomatis (opsional)

Di `updater.php`: `updater_auto_apply = TRUE`, dan atur `updater_auto_hours` (jam yang aman, di luar jadwal ujian).

Task Scheduler Windows, jalankan tiap jam:
```
C:\xampp\php\php.exe C:\xampp\htdocs\index.php updater cli_run
```
Hanya mengecek saja (tanpa memasang): `updater cli_check`.

## E2. Notifikasi "Pembaruan tersedia" (banner di aplikasi)

Supaya admin otomatis diberi tahu saat ada versi baru, pasang satu baris ini di **template/layout halaman admin**
(file yang memuat `</body>` dan dipakai semua halaman admin), tepat sebelum `</body>`:

```php
<script src="<?php echo site_url('updater/notice'); ?>"></script>
```

- Banner muncul di pojok kanan bawah: "Pembaruan tersedia: versi X" dengan tombol **Lihat & update** dan **Nanti**.
- **Nanti** menyembunyikan banner untuk versi itu saja; saat ada versi lebih baru, banner muncul lagi.
- Pengecekan memakai cache (`updater_cache_ttl`, bawaan 6 jam), jadi tidak membebani server atau koneksi internet.
- Jangan pasang di halaman siswa. Endpoint ini hanya untuk admin dan akan menampilkan 404 bagi yang lain.
- Jika server tidak ada internet atau GitHub tidak bisa dijangkau, banner tidak muncul dan tidak ada pesan error.

## F. Pemulihan

- Dashboard `/updater` menampilkan daftar backup. Tombol **Kembalikan** memulihkan file dan nomor versi.
- Database **tidak** ikut dikembalikan otomatis. Backup DB (bila ada migrasi) tersimpan di `application/updater_data/db_before_*.sql.gz`, dan bisa di-import lewat phpMyAdmin.
- Log proses: `application/updater_data/updater.log`.

## Catatan penting

- **Instalasi lama** yang belum punya updater harus dipasang manual satu kali (bagian A). Setelah itu update berikutnya otomatis.
- **Jangan update saat ujian berlangsung.** Aplikasi tidak bisa mendeteksi ujian aktif.
- Pembaruan file di Windows bisa gagal jika file sedang terkunci program lain; update akan dibatalkan dan dikembalikan otomatis.
- Perintah DDL MySQL (ALTER/CREATE) tidak bisa di-rollback; itu sebabnya backup database dibuat sebelum migrasi.
- Kode ini belum dites di instalasi Anda. **Uji di salinan dulu**, termasuk skenario gagal (hash salah, internet putus).
- Pastikan lisensi aplikasi dasar mengizinkan Anda memodifikasi dan mendistribusikan versi Anda.
