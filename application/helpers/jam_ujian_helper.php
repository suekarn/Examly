<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| STATUS JADWAL OTOMATIS
| Sebuah jadwal dianggap AKTIF (status = 1) hanya jika:
|   - tanggal hari ini ada di antara Tanggal Mulai dan Tanggal Expired, DAN
|   - jam sekarang ada di antara Jam Mulai dan Jam Selesai.
|     Jam Mulai = diisi manual di form (kosong = saat jadwal disimpan),
|     Jam Selesai = Jam Mulai + Durasi (menit), dihitung otomatis oleh jadwal_jam_dari_durasi.
| Di luar itu status = 0 (nonaktif). Tidak ada lagi pengaturan manual.
| Jam yang dipakai adalah jam server, zona waktu Asia/Jakarta.
| Pengecualian: jadwal tetap aktif selama ada siswa yang dijeda atau diberi tambahan waktu
| dan waktunya belum habis (lihat jadwal_pertahankan_perpanjangan).
|
| CATATAN PERBAIKAN:
| Kolom tgl_mulai / tgl_selesai di database bertipe DATETIME (contoh "2026-11-01 00:00:00"),
| sedangkan tanggal hari ini berformat "2026-11-01". Jika dibandingkan sebagai teks, nilai DATETIME
| selalu dianggap lebih besar sehingga jadwal ditolak tepat di hari mulainya (Kode 003).
| Karena itu semua tanggal & jam dinormalisasi dulu lewat jadwal_normalisasi_tanggal()
| dan jadwal_normalisasi_jam() sebelum dibandingkan.
*/

if (!function_exists('jadwal_normalisasi_tanggal')) {
    // Mengambil bagian tanggal saja (Y-m-d) dari nilai DATE atau DATETIME.
    // Mengembalikan '' jika kosong atau tidak valid.
    function jadwal_normalisasi_tanggal($nilai) {
        $nilai = trim((string) $nilai);
        if ($nilai === '' || strpos($nilai, '0000-00-00') === 0) {
            return '';
        }
        $tgl = substr($nilai, 0, 10);
        return preg_match('/^\d{4}-\d{2}-\d{2}$/', $tgl) ? $tgl : '';
    }
}

if (!function_exists('jadwal_normalisasi_jam')) {
    // Mengubah nilai jam (HH:MM, HH:MM:SS, atau DATETIME) menjadi HH:MM:SS.
    // Mengembalikan '' jika kosong atau tidak valid.
    function jadwal_normalisasi_jam($nilai) {
        $nilai = trim((string) $nilai);
        if ($nilai === '') {
            return '';
        }
        // jika berupa DATETIME, ambil bagian jamnya
        if (strpos($nilai, ' ') !== false) {
            $bagian = explode(' ', $nilai);
            $nilai = end($bagian);
        }
        if (preg_match('/^([01]?\d|2[0-3]):([0-5]\d)(?::([0-5]\d))?$/', $nilai, $m)) {
            return sprintf('%02d:%02d:%02d', (int) $m[1], (int) $m[2], isset($m[3]) ? (int) $m[3] : 0);
        }
        return '';
    }
}

if (!function_exists('jadwal_status_otomatis')) {
    // Mengembalikan '1' atau '0' untuk sebuah jadwal pada waktu sekarang.
    // $tgl_mulai/$tgl_selesai format Y-m-d atau DATETIME, $jam_* format HH:MM atau HH:MM:SS (boleh kosong).
    function jadwal_status_otomatis($tgl_mulai, $tgl_selesai, $jam_mulai = null, $jam_selesai = null) {
        $now = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
        $tgl = $now->format('Y-m-d');
        $jam = $now->format('H:i:s');

        $tgl_mulai   = jadwal_normalisasi_tanggal($tgl_mulai);
        $tgl_selesai = jadwal_normalisasi_tanggal($tgl_selesai);

        if ($tgl_mulai === '' || $tgl_selesai === '') {
            return '0';
        }
        if (!($tgl_mulai <= $tgl && $tgl_selesai >= $tgl)) {
            return '0';
        }

        $jm = jadwal_normalisasi_jam($jam_mulai);
        $js = jadwal_normalisasi_jam($jam_selesai);
        if ($jm !== '' && $js !== '') {
            if (!($jm <= $jam && $js > $jam)) {
                return '0';
            }
        }
        return '1';
    }
}

if (!function_exists('jadwal_jam_dari_durasi')) {
    // Menghitung [jam_mulai, jam_selesai] format HH:MM:SS.
    //   - $jam_mulai (HH:MM / HH:MM:SS) diisi manual di form -> dipakai apa adanya.
    //   - $jam_mulai kosong -> memakai waktu saat jadwal disimpan.
    //   - jam_selesai = jam_mulai + durasi (menit).
    // Tanggal TIDAK diubah, tetap memakai Tanggal Mulai & Tanggal Expired dari form.
    // Jika melewati tengah malam, jam_selesai dibatasi 23:59:59 (jadwal tidak menyeberang hari).
    function jadwal_jam_dari_durasi($durasi, $jam_mulai = null) {
        $durasi = max(1, (int) $durasi);
        $tz = new DateTimeZone('Asia/Jakarta');
        $mulai = new DateTime('now', $tz);
        if (!empty($jam_mulai) && preg_match('/^([01]?\d|2[0-3]):([0-5]\d)(?::([0-5]\d))?$/', trim($jam_mulai), $m)) {
            $mulai->setTime((int) $m[1], (int) $m[2], isset($m[3]) ? (int) $m[3] : 0);
        }
        $selesai = clone $mulai;
        $selesai->modify('+' . $durasi . ' minutes');
        $jam_selesai = ($selesai->format('Y-m-d') === $mulai->format('Y-m-d')) ? $selesai->format('H:i:s') : '23:59:59';
        return [$mulai->format('H:i:s'), $jam_selesai];
    }
}

if (!function_exists('jadwal_pertahankan_perpanjangan')) {
    // Menjaga status jadwal tetap AKTIF selama ada siswa yang:
    //   - sedang DIJEDA oleh pengawas (maksimal 24 jam sejak dijeda), atau
    //   - mendapat TAMBAHAN WAKTU dan waktu barunya (mulai + durasi + tambahan) belum habis, atau
    //   - pernah dijeda lalu dilanjutkan dan sisa waktu pribadinya belum habis.
    // Tanpa ini, jadwal otomatis nonaktif saat Jam Selesai / Tanggal Expired lewat.
    function jadwal_pertahankan_perpanjangan($sekarang) {
        $CI =& get_instance();
        $ada_jeda   = $CI->db->field_exists('jeda', 'cbt_durasi_siswa');
        $ada_tambah = $CI->db->field_exists('waktu_tambahan', 'cbt_durasi_siswa');

        $syarat = [];
        $bind = [];
        if ($ada_jeda) {
            $batas = clone $sekarang;
            $batas->modify('-1 day');
            $syarat[] = "(d.jeda IS NOT NULL AND d.jeda >= ?)";
            $bind[] = $batas->format('Y-m-d H:i:s');
        }
        if ($ada_tambah) {
            $syarat[] = "(d.waktu_tambahan > 0 AND DATE_ADD(d.mulai, INTERVAL (j.durasi_ujian + d.waktu_tambahan) MINUTE) > ?)";
            $bind[] = $sekarang->format('Y-m-d H:i:s');
        }
        // siswa yang PERNAH dijeda lalu dilanjutkan (walau tanpa tambahan waktu): jadwal tetap aktif
        // sampai sisa waktu pribadinya habis. Kolom pernah_jeda diisi saat tombol Jeda ditekan.
        if ($CI->db->field_exists('pernah_jeda', 'cbt_durasi_siswa')) {
            $plus = $ada_tambah ? " + COALESCE(d.waktu_tambahan, 0)" : "";
            $syarat[] = "(d.pernah_jeda = 1 AND DATE_ADD(d.mulai, INTERVAL (j.durasi_ujian" . $plus . ") MINUTE) > ?)";
            $bind[] = $sekarang->format('Y-m-d H:i:s');
        }
        if (empty($syarat)) {
            return 0;
        }

        $sql = "UPDATE cbt_jadwal j SET j.status = '1' WHERE j.status <> '1' AND EXISTS ("
             . "SELECT 1 FROM cbt_durasi_siswa d WHERE d.id_jadwal = j.id_jadwal"
             . " AND d.selesai IS NULL AND d.mulai IS NOT NULL AND (" . implode(' OR ', $syarat) . "))";
        $CI->db->query($sql, $bind);
        return $CI->db->affected_rows();
    }
}

if (!function_exists('jadwal_sinkron_status')) {
    // Menyamakan kolom status SEMUA jadwal dengan tanggal & jam sekarang.
    // Dijalankan maksimal sekali per request.
    // Perbandingan tanggal memakai DATE() agar kolom DATE maupun DATETIME diperlakukan sama
    // dengan jadwal_status_otomatis() di PHP.
    function jadwal_sinkron_status() {
        static $sudah = false;
        if ($sudah) {
            return 0;
        }
        $sudah = true;

        $CI =& get_instance();
        if (!isset($CI->db)) {
            $CI->load->database();
        }
        $now = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
        $tgl = $now->format('Y-m-d');
        $jam = $now->format('H:i:s');

        $ada_jam = $CI->db->field_exists('jam_mulai', 'cbt_jadwal') && $CI->db->field_exists('jam_selesai', 'cbt_jadwal');

        $kondisi = "tgl_mulai IS NOT NULL AND tgl_selesai IS NOT NULL AND tgl_mulai <> '0000-00-00' AND tgl_selesai <> '0000-00-00'"
                 . " AND DATE(tgl_mulai) <= ? AND DATE(tgl_selesai) >= ?";
        $bind = [$tgl, $tgl];
        if ($ada_jam) {
            $kondisi .= " AND (jam_mulai IS NULL OR jam_selesai IS NULL OR (jam_mulai <= ? AND jam_selesai > ?))";
            $bind[] = $jam;
            $bind[] = $jam;
        }

        $CI->db->query("UPDATE cbt_jadwal SET status = CASE WHEN ($kondisi) THEN '1' ELSE '0' END", $bind);
        $hasil = $CI->db->affected_rows();

        // jangan matikan jadwal yang masih dipakai siswa yang dijeda / diberi tambahan waktu
        jadwal_pertahankan_perpanjangan($now);
        return $hasil;
    }
}

if (!function_exists('jadwal_aktif_sekarang')) {
    // Daftar jadwal yang sedang berlangsung (dipakai dropdown login & pengecekan login siswa).
    function jadwal_aktif_sekarang() {
        jadwal_sinkron_status();

        $CI =& get_instance();
        $CI->db->select('id_jadwal, id_bank');
        $CI->db->from('cbt_jadwal');
        $CI->db->where('status', '1');
        $CI->db->order_by('jam_ke', 'ASC');
        return $CI->db->get()->result_array();
    }
}

if (!function_exists('jadwal_login_pesan')) {
    function jadwal_login_pesan() {
        return 'Ujian untuk mata pelajaran ini belum dibuka atau sudah berakhir. Periksa tanggal dan jam ujian.';
    }
}