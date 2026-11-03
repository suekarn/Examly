<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cbtsoal extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url'); 
        $this->load->helper('form');

        if (!$this->ion_auth->logged_in()) {
            redirect('auth');
        }
        
        $this->load->library('upload');
        $this->load->model('Master_model', 'master');
        $this->load->model('Dashboard_model', 'dashboard');
        $this->load->model('Cbt_model', 'cbt');
        
        $this->user = $this->ion_auth->user()->row();
    }

    public function buatsoal($id_bank = null) {
        if ($id_bank === null || empty($id_bank)) {
            $id_bank = $this->input->get('bank_id') ? $this->input->get('bank_id') : $this->input->get('id_bank');
        }
        if ($id_bank === null || empty($id_bank)) {
            $id_bank = $this->uri->segment(3);
        }

        if ($id_bank === null || empty($id_bank)) {
            $this->session->set_flashdata('error', 'ID Bank Soal tidak ditemukan.');
            redirect('dashboard', 'refresh');
            return;
        }

        $user = $this->ion_auth->user()->row();
        $tab_active = $this->input->get('tab') ? $this->input->get('tab') : '1';

        // Ambil data murni dari database untuk bypass proteksi ketat model admin
        $bank = $this->db->get_where('cbt_bank_soal', ['id_bank' => $id_bank])->row();
        
        // Ambil semua data butir soal yang ada
        $soal_ada = $this->db->get_where('cbt_soal', ['id_bank' => $id_bank])->result();

        // Fallback jika data bank kosong di database agar tidak memicu error non-object
        if (!$bank) {
            $bank = new stdClass();
            $bank->id_bank = $id_bank;
            $bank->id_bank_soal = $id_bank;
            $bank->bank_kode = 'KOSONG';
            $bank->nama_bank = 'Tidak Diketahui';
            $bank->tampil_pg = 0;
            $bank->tampil_kompleks = 0;
            $bank->tampil_jodohkan = 0;
            $bank->tampil_isian = 0;
            $bank->tampil_essai = 0;
            $bank->status = 0;
            $bank->opsi = 4;
            $bank->bank_mapel_id = 0;
        }

        if (!isset($bank->opsi)) $bank->opsi = 4;
        if (!isset($bank->tampil_pg)) $bank->tampil_pg = 0;
        if (!isset($bank->tampil_kompleks)) $bank->tampil_kompleks = 0;
        if (!isset($bank->tampil_jodohkan)) $bank->tampil_jodohkan = 0;
        if (!isset($bank->tampil_isian)) $bank->tampil_isian = 0;
        if (!isset($bank->tampil_essai)) $bank->tampil_essai = 0;

        // Cari butir soal spesifik yang sedang aktif diedit (jika ada parameter no)
        $p_no = $this->input->get('no') ? $this->input->get('no') : '1';
        $soal_spesifik = $this->db->get_where('cbt_soal', ['id_bank' => $id_bank, 'jenis' => $tab_active, 'nomor_soal' => $p_no])->row();

        // Cari soal yang belum komplit
        $soal_belum_komplit = $this->db->get_where('cbt_soal', ['id_bank' => $id_bank, 'tampilkan' => '0'])->result();

        // Ambil jumlah per jenis soal untuk merender tombol nomor di view
        $jml_pg = $this->db->select('MAX(nomor_soal) as nomor_soal')->get_where('cbt_soal', ['id_bank' => $id_bank, 'jenis' => '1'])->row();
        $jml_pg2 = $this->db->select('MAX(nomor_soal) as nomor_soal')->get_where('cbt_soal', ['id_bank' => $id_bank, 'jenis' => '2'])->row();
        $jml_jodohkan = $this->db->select('MAX(nomor_soal) as nomor_soal')->get_where('cbt_soal', ['id_bank' => $id_bank, 'jenis' => '3'])->row();
        $jml_isian = $this->db->select('MAX(nomor_soal) as nomor_soal')->get_where('cbt_soal', ['id_bank' => $id_bank, 'jenis' => '4'])->row();
        $jml_essai = $this->db->select('MAX(nomor_soal) as nomor_soal')->get_where('cbt_soal', ['id_bank' => $id_bank, 'jenis' => '5'])->row();

        $data = [
            'user'               => $user,
            'judul'              => 'Butir Soal',
            'subjudul'           => 'Kelola Butir Soal',
            'setting'            => $this->dashboard->getSetting(),
            'bank'               => $bank,
            'tab_active'         => $tab_active,
            'soal'               => $soal_spesifik, // Melempar object tunggal butir soal aktif
            'soal_ada'           => $soal_ada,      // Melempar array daftar semua soal
            'p_no'               => $p_no,
            'soal_belum_komplit' => $soal_belum_komplit,
            'jml_pg'             => $jml_pg,
            'jml_pg2'            => $jml_pg2,
            'jml_jodohkan'       => $jml_jodohkan,
            'jml_isian'          => $jml_isian,
            'jml_essai'          => $jml_essai
        ];

        $data['tp_active'] = $this->dashboard->getTahunActive();
        $data['smt_active'] = $this->dashboard->getSemesterActive();

        if ($this->ion_auth->is_admin()) {
            $data['profile'] = $this->dashboard->getProfileAdmin($user->id);
            $this->load->view('_templates/dashboard/_header', $data);
            $this->load->view('cbt/banksoal/soal', $data);
            $this->load->view('_templates/dashboard/_footer');
        } else {
            $profile_guru = $this->db->get_where('master_guru', ['id_user' => $user->id])->row();
            if (!$profile_guru) {
                $profile_guru = $this->dashboard->getProfileGuru($user->id);
            }
            $data['profile'] = $profile_guru;

            // Menggunakan template GURU secara mutlak agar session tidak tertendang middleware admin
            $this->load->view('_templates/guru/_header', $data);
            $this->load->view('cbt/banksoal/soal', $data);
            $this->load->view('_templates/guru/_footer');
        }
    }
}