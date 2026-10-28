<div class="info-box bg-transparent shadow-none">
    <img class="avatar" src="<?= !empty($guru->foto) ? base_url($guru->foto) : base_url('assets/img/siswa.png') ?>" width="120" height="120">
    <div class="info-box-content">
        <h5 class="info-box-text text-white text-wrap"><b><?= isset($guru->nama_guru) ? $guru->nama_guru : 'Guru' ?></b></h5>
        <span class="info-box-text text-white">NIP: <?= isset($guru->nip) ? $guru->nip : '-' ?></span>
        <span class="info-box-text text-white mb-1">Tenaga Pendidik</span>
    </div>
</div>

<script>
    $(`.avatar`).each(function () {
        $(this).on("error", function () {
            $(this).attr("src", base_url + 'assets/img/siswa.png');
        });
    });
</script>