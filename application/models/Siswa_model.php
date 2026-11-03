<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Siswa_model extends CI_Model {

    public function get_live_monitoring() {
        $this->db->select('id_siswa, nama, benar, salah, status, skor, waktu');
        $this->db->from('tabel_monitoring'); // <-- Sesuaikan dengan nama tabel monitoring Anda
        $this->db->order_by('skor', 'DESC');
        $this->db->order_by('waktu', 'ASC');
        
        return $this->db->get()->result();
    }
}