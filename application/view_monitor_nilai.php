<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Live Score Ujian Real-Time</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Monitoring Live Score Ujian</h4>
            <span class="badge badge-success">Terbuka Otomatis</span>
        </div>
        <div class="card-body">
            <table class="table table-striped table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Siswa</th>
                        <th>Waktu Mulai</th>
                        <th>Benar</th>
                        <th>Salah</th>
                        <th>Skor Akhir</th>
                    </tr>
                </thead>
                <tbody id="konten-live-score">
                    <tr>
                        <td colspan="6" class="text-center text-muted">Memuat data nilai...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function ambilDataTerbaru() {
    $.ajax({
        // Menembak langsung ke Controller 'Monitor' dan fungsi 'ambil_nilai' yang baru dibuat
        url: '<?= base_url("index.php/monitor/ambil_nilai") ?>', 
        type: 'GET',
        dataType: 'json',
        success: function(dataSiswa) {
            let baris = '';
            if(dataSiswa.length === 0) {
                baris = '<tr><td colspan="6" class="text-center">Belum ada siswa yang mengerjakan.</td></tr>';
            } else {
                $.each(dataSiswa, function(index, siswa) {
                    baris += `<tr>
                        <td>${index + 1}</td>
                        <td>${siswa.nama.toUpperCase()}</td>
                        <td>${siswa.mulai || '--:--'}</td>
                        <td><span class="badge badge-success p-2">${siswa.pg_benar || 0}</span></td>
                        <td><span class="badge badge-danger p-2">${siswa.pg_salah || 0}</span></td>
                        <td><strong>${siswa.nilai_asli || 0}</strong></td>
                    </tr>`;
                });
            }
            $('#konten-live-score').html(baris);
        },
        error: function(xhr) {
            console.log("Koneksi gagal atau database belum sesuai: ", xhr.responseText);
        }
    });
}

// Perbarui tabel secara otomatis setiap 4 detik tanpa reload halaman
setInterval(ambilDataTerbaru, 4000);

// Panggil fungsi langsung saat halaman pertama kali dibuka
$(document).ready(function() {
    ambilDataTerbaru();
});
</script>

</body>
</html>