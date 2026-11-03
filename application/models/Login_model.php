<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model {

    public function get_jadwal_aktif() {
        // Mengikuti format tanggal di komputer Anda (2026-05-26)
        $hari_ini = date('Y-m-d');

        $this->db->select('a.id_jadwal, b.bank_kode as nama_ujian'); 
        $this->db->from('cbt_jadwal a');
        
        // Sesuai dengan kode enkripsi asli GarudaCBT, relasi ke bank soal menggunakan 'cbt_bank_soal'
        $this->db->join('cbt_bank_soal b', 'a.id_bank = b.id_bank', 'left');
        
        // Filter rentang tanggal berdasarkan data phpMyAdmin Anda
        $this->db->where('a.tgl_mulai <=', $hari_ini);
        $this->db->where('a.tgl_selesai >=', $hari_ini);
        
        $query = $this->db->get();
        return $query->result(); 
    }
}