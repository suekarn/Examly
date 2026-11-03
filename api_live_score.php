<?php
// ====================================================================
// BYPASS ENDPOINT REAL-TIME LIVE SCORE - GARUDACBT
// ====================================================================
define('BASEPATH', true);

// 1. Mengintip konfigurasi database asli bawaan GarudaCBT Anda
$config_file = __DIR__ . '/application/config/database.php';
if (!file_exists($config_file)) {
    die(json_encode([]));
}

include($config_file);
$db_config = $db['default'];

$conn = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);
if ($conn->connect_error) {
    die(json_encode([]));
}

// 2. Deteksi nama kolom evaluasi secara dinamis (is_benar atau status_jawaban)
$kolom_evaluasi = "ss.status_jawaban";
$check_column = $conn->query("SHOW COLUMNS FROM `cbt_soal_siswa` LIKE 'status_jawaban'");
if ($check_column->num_rows == 0) {
    $check_is_benar = $conn->query("SHOW COLUMNS FROM `cbt_soal_siswa` LIKE 'is_benar'");
    if ($check_is_benar->num_rows > 0) {
        $kolom_evaluasi = "ss.is_benar";
    } else {
        $kolom_evaluasi = "(CASE WHEN ss.jawaban_siswa = ss.jawaban_benar THEN 1 ELSE 0 END)";
    }
}

// 3. Query utama mengambil data pengerjaan siswa yang sedang aktif ujian
$query = "SELECT 
            s.nama as nama_siswa, 
            COALESCE(COUNT(CASE WHEN {$kolom_evaluasi} = '1' THEN 1 END), 0) as jml_benar,
            COALESCE(COUNT(CASE WHEN {$kolom_evaluasi} = '0' THEN 1 END), 0) as jml_salah
          FROM cbt_durasi_siswa d
          INNER JOIN master_siswa s ON d.id_siswa = s.id_siswa
          LEFT JOIN cbt_soal_siswa ss ON d.id_siswa = ss.id_siswa AND d.id_jadwal = ss.id_jadwal
          WHERE d.status = 1
          GROUP BY d.id_siswa, d.id_jadwal
          ORDER BY d.mulai DESC LIMIT 20";

$result = $conn->query($query);
$data = [];

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $total_dijawab = $row['jml_benar'] + $row['jml_salah'];
        $row['nilai'] = ($total_dijawab > 0) ? round(($row['jml_benar'] / $total_dijawab) * 100, 1) : 0;
        $data[] = $row;
    }
}

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Mencegah blokir CORS browser
echo json_encode($data);
$conn->close();