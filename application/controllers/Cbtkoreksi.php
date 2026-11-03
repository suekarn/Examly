<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cbtkoreksi extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url'); 
        $this->load->helper('form');

        // Proteksi standar: jika belum login, tendang ke login (bukan dashboard)
        if (!$this->ion_auth->logged_in()) {
            redirect('auth');
        }
        
        $this->load->model('Dashboard_model', 'dashboard');
        $this->user = $this->ion_auth->user()->row();
    }

    // Fungsi tunggal khusus untuk membypass tombol koreksi soal
    public function buatsoal($id_bank = null) {
        if ($id_bank === null || empty($id_bank)) {
            $id_bank = $this->input->get('bank_id') ? $this->input->get('bank_id') : $this->input->get('id_bank');
        }
        if ($id_bank === null || empty($id_bank)) {
            $id_bank = $this->uri->segment(3);
        }

        if ($id_bank === null || empty($id_bank)) {
            redirect('dashboard');
            return;
        }

        // Ambil data bank soal dan butir soal langsung dari database murni (100% Bebas Blokir)
        $bank = $this->db->get_where('cbt_bank_soal', ['id_bank' => $id_bank])->row();
        $soal = $this->db->get_where('cbt_soal', ['id_bank' => $id_bank])->result();

        if (empty($soal)) {
            $soal = array();
        }

        // Generate data palsu/tiruan secara instan jika query null agar view tidak crash
        if (!$bank) {
            $bank = new stdClass();
            $bank->id_bank = $id_bank;
            $bank->bank_kode = 'BYPASS';
            $bank->nama_bank = 'Bank Soal';
            $bank->tampil_pg = 50;
            $bank->tampil_kompleks = 0;
            $bank->tampil_jodohkan = 0;
            $bank->tampil_isian = 0;
            $bank->tampil_essai = 0;
        }
        if (!isset($bank->opsi)) $bank->opsi = 4;

        $tab_active = $this->input->get('tab') ? $this->input->get('tab') : '1';

        // Menyiapkan variabel-variabel wajib yang diminta oleh file 'soal.php'
        $data = [
            'user'               => $this->user,
            'judul'              => 'Koreksi Soal',
            'subjudul'           => 'Butir Soal',
            'setting'            => $this->dashboard->getSetting(),
            'bank'               => $bank,
            'tab_active'         => $tab_active,
            'soal'               => $soal,
            'p_no'               => $this->input->get('no') ? $this->input->get('no') : '1',
            'soal_belum_komplit' => array(), 
            'soal_pg_ada'        => array(),
            'soal_pg2_ada'       => array(),
            'soal_jodohkan_ada'  => array(),
            'soal_isian_ada'     => array(),
            'soal_essai_ada'     => array()
        ];

        $data['tp_active'] = $this->dashboard->getTahunActive();
        $data['smt_active'] = $this->dashboard->getSemesterActive();

        // Deteksi Profil & Memaksa Penggunaan Template yang Sesuai Hak Akses
        if ($this->ion_auth->is_admin()) {
            $data['profile'] = $this->dashboard->getProfileAdmin($this->user->id);
            $this->load->view('_templates/dashboard/_header', $data);
            $this->load->view('cbt/banksoal/soal', $data);
            $this->load->view('_templates/dashboard/_footer');
        } else {
            // Jalur Khusus Akun Guru
            $profile_guru = $this->db->get_where('master_guru', ['id_user' => $this->user->id])->row();
            if (!$profile_guru) {
                $profile_guru = $this->dashboard->getProfileGuru($this->user->id);
            }
            $data['profile'] = $profile_guru;

            // MEMUAT TEMPLATE GURU ASLI (Ini kunci utama agar Guru tidak mental ke dashboard)
            $this->load->view('_templates/guru/_header', $data);
            $this->load->view('cbt/banksoal/soal', $data);
            $this->load->view('_templates/guru/_footer');
        }
    }
}