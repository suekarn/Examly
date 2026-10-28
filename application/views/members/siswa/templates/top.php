<?php
// Pilih gambar bawaan (dipakai kalau foto siswa tidak ada) sesuai jenis kelamin.
// L -> siswa-l.png | P -> siswa-p.png | tidak diketahui -> siswa.png
$jk_raw = '';
foreach (['jenis_kelamin', 'jk', 'gender', 'kelamin'] as $kolom_jk) {
    if (isset($siswa->$kolom_jk) && trim((string)$siswa->$kolom_jk) !== '') {
        $jk_raw = strtoupper(substr(trim((string)$siswa->$kolom_jk), 0, 1));
        break;
    }
}
if ($jk_raw === 'L') $avatar_default = 'assets/img/siswa-l.png';
elseif ($jk_raw === 'P') $avatar_default = 'assets/img/siswa-p.png';
else $avatar_default = 'assets/img/siswa.png';
?>
<div class="info-box bg-transparent shadow-none">
    <img class="avatar" src="<?= base_url($siswa->foto) ?>" width="120" height="120">
    <div class="info-box-content">
        <h5 class="info-box-text text-white text-wrap"><b><?= $siswa->nama ?></b></h5>
        <span class="info-box-text text-white"><?= $siswa->nis ?></span>
        <span class="info-box-text text-white mb-1"><?= $siswa->nama_kelas ?></span>
    </div>
</div>

<script>
    $(`.avatar`).each(function () {
        $(this).on("error", function () {
            var src = $(this).attr('src').replace('profiles', 'foto_siswa');
            $(this).attr("src", src);
            $(this).on("error", function () {
                $(this).attr("src", base_url + '<?= $avatar_default ?>');
            });

        });
    });
</script>