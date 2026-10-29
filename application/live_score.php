<?php
// ====================================================================
// SMART BYPASS REAL-TIME LIVE SCORE - AUTO DETECT DATABASE
// ====================================================================
define('BASEPATH', true);

// 1. Otomatis intip konfigurasi database asli bawaan GarudaCBT kamu
$config_file = __DIR__ . '/application/config/database.php';

if (!file_exists($config_file)) {
    die(json_encode([]));
}

// Baca file config bawaan
include($config_file);
$db_config = $db['default'];

$host = $db_config['localhost'];
$user = $db_config['root'];
$pass = $db_config['root'];
$database = $db_config['cbt_garuda'];

// 2. Hubungkan ke database hasil contekan di atas
$conn = new mysqli($host, $user, $pass, $database);
if ($conn->connect_error) {
    die(json_encode([]));
}

// 3. Ambil data siswa yang SEDANG mengeklik jawaban saat ini
$query = "SELECT 
            s.nama as nama_siswa, 
            k.nama_kelas, 
            m.nama_mapel,
            COALESCE(COUNT(CASE WHEN ss.status_jawaban = '1' THEN 1 END), 0) as jml_benar,
            COALESCE(COUNT(CASE WHEN ss.status_jawaban = '0' THEN 1 END), 0) as jml_salah
          FROM cbt_durasi_siswa d
          INNER JOIN master_siswa s ON d.id_siswa = s.id_siswa
          LEFT JOIN master_kelas k ON s.id_kelas = k.id_kelas
          LEFT JOIN cbt_jadwal j ON d.id_jadwal = j.id_jadwal
          LEFT JOIN master_mapel m ON j.id_mapel = m.id_mapel
          LEFT JOIN cbt_soal_siswa ss ON d.id_siswa = ss.id_siswa AND d.id_jadwal = ss.id_jadwal
          WHERE d.status = 1
          GROUP BY d.id_siswa, d.id_jadwal
          ORDER BY d.mulai DESC LIMIT 15";

$result = $conn->query($query);
$data = [];

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $total_dijawab = $row['jml_benar'] + $row['jml_salah'];
        if ($total_dijawab > 0) {
            $row['nilai'] = round(($row['jml_benar'] / $total_dijawab) * 100, 1);
        } else {
            $row['nilai'] = 0;
        }
        $data[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($data);
$conn->close();