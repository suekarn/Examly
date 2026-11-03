<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
    }

    public function index() {
        // 1. Hancurkan session dan cookie di tingkat server backend secara total
        $this->session->sess_destroy();
        
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }

        // 2. Alihkan secara bersih ke fungsi login admin bawaan asli GarudaCBT
        redirect('auth/login', 'refresh');
    }
}