public function live_skor_api() {
        // Meloloskan AJAX jika user terbukti memiliki session username/role di sistem GarudaCBT
        if (!$this->session->userdata('username')) {
            echo json_encode(array());
            exit();
        }

        header('Content-Type: application/json');
        header('Cache-Control: no-cache, must-revalidate');
        
        // Deteksi otomatis kolom evaluasi jawaban siswa
        $kolom_evaluasi = "ss.status_jawaban";
        if (!$this->db->field_exists('status_jawaban', 'cbt_soal_siswa')) {
            if ($this->db->field_exists('is_benar', 'cbt_soal_siswa')) {
                $kolom_evaluasi = "ss.is_benar";
            } else {
                $kolom_evaluasi = "(CASE WHEN ss.jawaban_siswa = ss.jawaban_benar THEN 1 ELSE 0 END)";
            }
        }

        // Query mengambil data siswa yang berstatus ujian aktif (1) atau selesai (2)
        $query = $this->db->query("SELECT 
                    s.nama as nama_siswa, 
                    COALESCE(COUNT(CASE WHEN {$kolom_evaluasi} = '1' THEN 1 END), 0) as jml_benar,
                    COALESCE(COUNT(CASE WHEN {$kolom_evaluasi} = '0' THEN 1 END), 0) as jml_salah
                  FROM cbt_durasi_siswa d
                  INNER JOIN master_siswa s ON d.id_siswa = s.id_siswa
                  LEFT JOIN cbt_soal_siswa ss ON d.id_siswa = ss.id_siswa AND d.id_jadwal = ss.id_jadwal
                  WHERE d.status IN (1, 2) 
                  GROUP BY d.id_siswa, d.id_jadwal
                  ORDER BY jml_benar DESC, s.nama ASC LIMIT 40");

        $list_siswa = $query->result_array();
        $hasil = array();

        foreach($list_siswa as $siswa) {
            $total_jawab = $siswa['jml_benar'] + $siswa['jml_salah'];
            $siswa['nilai'] = ($total_jawab > 0) ? round(($siswa['jml_benar'] / $total_jawab) * 100, 1) : 0;
            $hasil[] = $siswa;
        }

        echo json_encode($hasil);
        exit();
    }