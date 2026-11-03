<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cekstatus extends CI_Controller {

    public function siswa()
    {
        echo json_encode([
            'online' => true
        ]);
    }

}