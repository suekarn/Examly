<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Updater extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('app_updater');
        if (!is_cli() && !$this->is_allowed()) {
            // Skrip banner dipasang di footer bersama; untuk non-admin cukup kosong (tanpa error di console)
            if ($this->router->fetch_method() === 'notice') {
                header('Content-Type: application/javascript; charset=utf-8');
                echo '/* updater: tidak ada akses */';
                exit;
            }
            show_404();
        }
    }

    // Siapa yang boleh membuka halaman updater
    private function is_allowed() {
        if ($this->config->item('updater_allow_localhost') !== FALSE
            && in_array($this->input->ip_address(), array('127.0.0.1', '::1'))) {
            return TRUE;
        }
        // Ion Auth (dipakai banyak aplikasi CI3)
        if (file_exists(APPPATH . 'libraries/Ion_auth.php')) {
            try {
                $this->load->library('ion_auth');
                if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) return TRUE;
            } catch (Exception $e) { /* lanjut ke cek session */ }
        }
        // Cadangan: cek session
        $key = $this->config->item('updater_admin_session_key');
        if ($key) {
            $vals = (array) $this->config->item('updater_admin_session_values');
            if (in_array($this->session->userdata($key), $vals, TRUE)) return TRUE;
        }
        return FALSE;
    }

    private function json($data, $status = 200) {
        $data['csrf'] = $this->security->get_csrf_hash();
        $this->output->set_status_header($status)
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    // Halaman dashboard updater
    public function index() {
        $this->load->view('updater/index', array(
            'current'   => $this->app_updater->current_version(),
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash(),
        ));
    }

    // GET /updater/check?force=1
    public function check() {
        try {
            $info = $this->app_updater->check((bool) $this->input->get('force'));
            $info['backups'] = $this->app_updater->backups();
            $this->json(array('ok' => TRUE, 'info' => $info));
        } catch (Exception $e) {
            $this->json(array('ok' => FALSE, 'error' => $e->getMessage()));
        }
    }

    // GET /updater/status -> dipakai banner notifikasi (memakai cache, ringan, tidak pernah error ke pengguna)
    public function status() {
        try {
            $i = $this->app_updater->check(FALSE);
            $this->json(array(
                'ok'        => TRUE,
                'available' => (bool) $i['available'],
                'current'   => $i['current'],
                'latest'    => $i['latest'],
                'changelog' => $i['changelog'],
            ));
        } catch (Exception $e) {
            $this->json(array('ok' => FALSE));
        }
    }

    // GET /updater/notice -> JavaScript banner "Pembaruan tersedia". Dipasang di template admin.
    public function notice() {
        $js = <<<'JS'
(function () {
  var STATUS = __STATUS__, PAGE = __PAGE__;
  function dismissed() { try { return localStorage.getItem('upd_dismiss') || ''; } catch (e) { return ''; } }
  function dismiss(v) { try { localStorage.setItem('upd_dismiss', v); } catch (e) {} }
  function el(tag, css, text) {
    var n = document.createElement(tag);
    if (css) n.style.cssText = css;
    if (text) n.textContent = text;
    return n;
  }
  function show(j) {
    var box = el('div', 'position:fixed;right:16px;bottom:16px;z-index:99999;max-width:340px;padding:14px 16px;' +
      'background:#1f2937;color:#fff;border-radius:10px;box-shadow:0 6px 24px rgba(0,0,0,.3);' +
      'font:14px/1.45 system-ui,-apple-system,Segoe UI,sans-serif');
    box.appendChild(el('div', 'font-weight:600;margin-bottom:4px', 'Pembaruan tersedia: versi ' + j.latest));
    box.appendChild(el('div', 'opacity:.8;font-size:13px;margin-bottom:10px',
      'Terpasang: ' + j.current + (j.changelog ? ' \u2022 ' + String(j.changelog).slice(0, 110) : '')));
    var row = el('div', 'display:flex;gap:8px');
    var go = el('a', 'padding:7px 12px;background:#3b82f6;color:#fff;border-radius:7px;text-decoration:none;font-size:13px', 'Lihat & update');
    go.href = PAGE;
    var later = el('button', 'padding:7px 12px;background:transparent;color:#fff;border:1px solid rgba(255,255,255,.35);border-radius:7px;cursor:pointer;font-size:13px', 'Nanti');
    later.onclick = function () { dismiss(j.latest); box.parentNode.removeChild(box); };
    row.appendChild(go); row.appendChild(later); box.appendChild(row);
    document.body.appendChild(box);
  }
  function run() {
    fetch(STATUS, { credentials: 'same-origin', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
      .then(function (r) { return r.json(); })
      .then(function (j) { if (j && j.ok && j.available && dismissed() !== j.latest) show(j); })
      .catch(function () {});
  }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', run); else run();
})();
JS;
        $js = str_replace(
            array('__STATUS__', '__PAGE__'),
            array(json_encode(site_url('updater/status')), json_encode(site_url('updater'))),
            $js
        );
        $this->output->set_content_type('application/javascript')->set_output($js);
    }

    // POST /updater/run
    public function run() {
        if ($this->input->method() !== 'post') {
            return $this->json(array('ok' => FALSE, 'error' => 'Gunakan POST'), 405);
        }
        try {
            $this->json(array_merge(array('ok' => TRUE), $this->app_updater->run()));
        } catch (Exception $e) {
            $this->json(array('ok' => FALSE, 'error' => $e->getMessage(), 'steps' => $this->app_updater->steps()));
        } catch (Throwable $e) {
            $this->json(array('ok' => FALSE, 'error' => 'Kesalahan: ' . $e->getMessage(), 'steps' => $this->app_updater->steps()));
        }
    }

    // POST /updater/rollback  (file=files_x_to_y_....zip)
    public function rollback() {
        if ($this->input->method() !== 'post') {
            return $this->json(array('ok' => FALSE, 'error' => 'Gunakan POST'), 405);
        }
        try {
            $this->json(array_merge(array('ok' => TRUE), $this->app_updater->rollback((string) $this->input->post('file'))));
        } catch (Exception $e) {
            $this->json(array('ok' => FALSE, 'error' => $e->getMessage()));
        }
    }

    // Command line: php index.php updater cli_check
    public function cli_check() {
        if (!is_cli()) show_404();
        try {
            $i = $this->app_updater->check(TRUE);
            echo "Terpasang: {$i['current']} | Terbaru: {$i['latest']} | " . ($i['available'] ? 'ADA UPDATE' : 'sudah terbaru') . "\n";
        } catch (Exception $e) {
            echo 'Gagal: ' . $e->getMessage() . "\n";
        }
    }

    // Command line (Task Scheduler): php index.php updater cli_run
    public function cli_run() {
        if (!is_cli()) show_404();
        try {
            $i = $this->app_updater->check(TRUE);
            if (!$i['available']) { echo "Sudah versi terbaru ({$i['current']})\n"; return; }
            if (!$this->config->item('updater_auto_apply')) {
                echo "Update {$i['latest']} tersedia, tetapi updater_auto_apply nonaktif.\n"; return;
            }
            $hours = (array) $this->config->item('updater_auto_hours');
            if (!in_array((int) date('G'), $hours, TRUE)) { echo "Di luar jam update otomatis, ditunda.\n"; return; }
            $r = $this->app_updater->run();
            echo implode("\n", $r['steps']) . "\n";
        } catch (Exception $e) {
            echo 'Gagal: ' . $e->getMessage() . "\n";
        }
    }
}