<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Qrlogin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect('auth');
        }
        if (!$this->ion_auth->is_admin()) {
            show_error('Hanya admin yang boleh mengakses.', 403);
        }
    }

    private function lan_ip()
    {
        // Cara 1: IP server dari request, kalau bukan localhost
        $ip = $_SERVER['SERVER_ADDR'] ?? '';
        if ($ip && !in_array($ip, ['127.0.0.1', '::1'])) {
            return $ip;
        }
        // Cara 2: cari IP LAN lewat koneksi UDP (tidak mengirim data)
        $sock = @socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        if ($sock && @socket_connect($sock, '8.8.8.8', 53)) {
            socket_getsockname($sock, $addr);
            socket_close($sock);
            if ($addr) return $addr;
        }
        // Cara 3: cadangan
        return gethostbyname(gethostname());
    }

    public function index()
    {
        $ip   = $this->lan_ip();
        $path = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\'); // contoh: /ujian
        $port = $_SERVER['SERVER_PORT'] ?? 80;
        $port = in_array($port, [80, 443]) ? '' : ':' . $port;

        $data['url'] = 'http://' . $ip . $port . $path . '/auth';
        $this->load->view('qrlogin_view', $data);
    }
}