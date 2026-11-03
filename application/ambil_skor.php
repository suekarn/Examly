<?php
// AMBIL_SKOR.PHP - LIVE ENGINE STANDALONE
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');

// 1. Sesuaikan dengan konfigurasi database GarudaCBT Anda
$db_host = 'localhost';
$db_user = 'root';
$db_pass = 'root';        // Kosongkan jika pakai XAMPP bawaan, atau isi password DB Anda
$db_nama = 'cbt_garuda'; // Ganti dengan nama database CBT Anda

$koneksi = new mysqli($db_host, $db_user, $db_pass, $db_nama);

if ($koneksi->connect_error) {
    echo json_encode([]);
    exit();
}

// 2. Deteksi nama kolom secara dinamis agar tidak error di berbagai versi database
$kolom_evaluasi = "ss.status_jawaban";
$cek_kolom = $koneksi->query("SHOW COLUMNS FROM cbt_soal_siswa LIKE 'status_jawaban'");
if ($cek_kolom->num_rows == 0) {
    $cek_kolom_alt = $koneksi->query("SHOW COLUMNS FROM cbt_soal_siswa LIKE 'is_benar'");
    if ($cek_kolom_alt->num_rows > 0) {
        $kolom_evaluasi = "ss.is_benar";
    } else {
        $kolom_evaluasi = "(CASE WHEN ss.jawaban_siswa = ss.jawaban_benar THEN 1 ELSE 0 END)";
    }
}

// 3. Tarik data siswa yang sedang menempuh ujian (status = 1)
$sql = "SELECT 
            s.nama as nama_siswa, 
            COALESCE(COUNT(CASE WHEN {$kolom_evaluasi} = '1' THEN 1 END), 0) as jml_benar,
            COALESCE(COUNT(CASE WHEN {$kolom_evaluasi} = '0' THEN 1 END), 0) as jml_salah
        FROM cbt_durasi_siswa d
        INNER JOIN master_siswa s ON d.id_siswa = s.id_siswa
        LEFT JOIN cbt_soal_siswa ss ON d.id_siswa = ss.id_siswa AND d.id_jadwal = ss.id_jadwal
        WHERE d.status = 1
        GROUP BY d.id_siswa, d.id_jadwal
        ORDER BY d.mulai DESC LIMIT 20";

$result = $koneksi->query($sql);
$data_siswa = [];

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $total_dijawab = $row['jml_benar'] + $row['jml_salah'];
        $row['nilai'] = ($total_dijawab > 0) ? round(($row['jml_benar'] / $total_dijawab) * 100, 1) : 0;
        $data_siswa[] = $row;
    }
}

echo json_encode($data_siswa);
$koneksi->close();
exit();