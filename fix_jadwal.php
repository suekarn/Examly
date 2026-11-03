<?php
// fix_jadwal.php - Bypass Update Jam Ujian CBT
define('ENVIRONMENT', 'development');
define('BASEPATH', TRUE);

error_reporting(0);
ini_set('display_errors', 0);

include_once 'application/config/database.php';

$db_config = $db['default'];
$conn = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Koneksi database gagal']));
}

$id_jadwal   = isset($_POST['id_jadwal']) ? intval($_POST['id_jadwal']) : 0;
$tgl_mulai   = isset($_POST['tgl_mulai']) ? trim($_POST['tgl_mulai']) : '';
$tgl_selesai = isset($_POST['tgl_selesai']) ? trim($_POST['tgl_selesai']) : '';

// Validasi & Pastikan format DATETIME lengkap (YYYY-MM-DD HH:MM:SS)
if ($id_jadwal > 0 && !empty($tgl_mulai) && !empty($tgl_selesai)) {
    
    // Jika hanya berisi tanggal (10 karakter), tambahkan jam default agar tidak 00:00:00
    if (strlen($tgl_mulai) == 10) {
        $tgl_mulai .= ' 07:00:00';
    }
    if (strlen($tgl_selesai) == 10) {
        $tgl_selesai .= ' 16:00:00';
    }

    $stmt = $conn->prepare("UPDATE cbt_jadwal SET tgl_mulai = ?, tgl_selesai = ? WHERE id_jadwal = ?");
    $stmt->bind_param("ssi", $tgl_mulai, $tgl_selesai, $id_jadwal);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'updated_mulai' => $tgl_mulai, 'updated_selesai' => $tgl_selesai]);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Parameter tidak valid']);
}
$conn->close();