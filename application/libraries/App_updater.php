<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * App_updater - mesin update online untuk aplikasi CodeIgniter 3.
 *
 * Alur run(): cek versi -> unduh -> verifikasi hash/tanda tangan -> backup file & DB
 *             -> tulis file -> hapus file lama -> migrasi SQL -> catat versi.
 * Jika langkah penulisan/migrasi gagal, file otomatis dikembalikan dari backup.
 */
class App_updater {

    protected $CI;
    protected $dir;            // folder data updater (backup, cache, log)
    protected $steps = array();
    protected $lockfp = null;
    protected $state = array('running' => false, 'percent' => 0, 'phase' => '', 'dl' => array(0, 0), 'wi' => 0, 'wt' => 0,
                             'has_sql' => null, 'files' => array(), 'same' => 0, 'ok' => null, 'error' => '', 'version' => '', 'started' => 0);
    protected $lastFlush = 0;

    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->config->load('version');
        $this->CI->config->load('updater');
        $this->dir = APPPATH . 'updater_data/';
        $this->ensure_dir();
    }

    /* ------------------------------------------------------------ util */

    protected function cfg($key, $default = null) {
        $v = $this->CI->config->item($key);
        return ($v === null) ? $default : $v;
    }

    public function current_version() {
        return (string) $this->cfg('app_version', '0.0.0');
    }

    public function steps() {
        return $this->steps;
    }

    protected function say($msg) {
        $this->steps[] = $msg;
        @file_put_contents($this->dir . 'updater.log', '[' . date('Y-m-d H:i:s') . '] ' . $msg . "\n", FILE_APPEND);
        $this->flush(true);
    }

    /* ------------------------------------------------------- progress */

    // Simpan status proses ke progress.json supaya bisa dibaca halaman updater selama update berjalan
    protected function flush($force = false) {
        $now = microtime(true);
        if (!$force && ($now - $this->lastFlush) < 0.3) return;
        $this->lastFlush = $now;
        $s = $this->state;
        $s['steps'] = $this->steps;
        $s['updated'] = time();
        @file_put_contents($this->dir . 'progress.json', json_encode($s, JSON_PARTIAL_OUTPUT_ON_ERROR), LOCK_EX);
    }

    protected function fresh_state() {
        return array('running' => true, 'percent' => 0, 'phase' => 'download', 'dl' => array(0, 0), 'wi' => 0, 'wt' => 0,
                     'has_sql' => null, 'files' => array(), 'same' => 0, 'ok' => null, 'error' => '', 'version' => '', 'started' => time());
    }

    protected function pct($p) {
        $p = (int) $p;
        if ($p > $this->state['percent']) $this->state['percent'] = min(100, $p);
        $this->flush(false);
    }

    protected function file_log($name, $status) {
        $this->state['files'][] = array('n' => $name, 's' => $status);
    }

    protected function counts() {
        $c = array('baru' => 0, 'diubah' => 0, 'dihapus' => 0);
        foreach ($this->state['files'] as $f) { if (isset($c[$f['s']])) $c[$f['s']]++; }
        return $c;
    }

    // Dipanggil controller jika terjadi error di luar Exception biasa
    public function mark_failed($msg) {
        $this->state['running'] = false;
        $this->state['ok'] = false;
        $this->state['error'] = (string) $msg;
        $this->say('GAGAL: ' . $msg);
    }

    // Dibaca halaman updater (endpoint updater/progress)
    public function progress_state() {
        $f = $this->dir . 'progress.json';
        $s = is_file($f) ? json_decode((string) @file_get_contents($f), true) : null;
        if (!is_array($s) || !isset($s['updated'])) {
            return array('running' => false, 'percent' => 0, 'phase' => '', 'steps' => array(), 'files' => array(), 'same' => 0, 'ok' => null);
        }
        if (!empty($s['running']) && (time() - (int) $s['updated']) > 600) $s['running'] = false; // proses mati di tengah jalan
        return $s;
    }

    protected function ensure_dir() {
        if (!is_dir($this->dir)) @mkdir($this->dir, 0755, true);
        if (!file_exists($this->dir . '.htaccess')) {
            @file_put_contents($this->dir . '.htaccess',
                "<IfModule mod_authz_core.c>\nRequire all denied\n</IfModule>\n" .
                "<IfModule !mod_authz_core.c>\nDeny from all\n</IfModule>\n");
        }
        if (!file_exists($this->dir . 'index.html')) @file_put_contents($this->dir . 'index.html', '');
    }

    protected function lock() {
        $this->lockfp = fopen($this->dir . 'update.lock', 'c');
        if (!$this->lockfp || !flock($this->lockfp, LOCK_EX | LOCK_NB)) {
            throw new Exception('Proses update lain sedang berjalan. Coba lagi beberapa menit.');
        }
    }

    protected function unlock() {
        if ($this->lockfp) {
            flock($this->lockfp, LOCK_UN);
            fclose($this->lockfp);
            $this->lockfp = null;
        }
    }

    protected function valid_version($v) {
        return is_string($v) && preg_match('/^\d+(\.\d+){1,3}$/', $v);
    }

    // Path relatif harus aman: tanpa "..", tanpa path absolut / drive letter
    protected function safe_path($p) {
        $p = str_replace('\\', '/', $p);
        if ($p === '' || $p[0] === '/' || strpos($p, ':') !== false || strpos($p, "\0") !== false) return false;
        foreach (explode('/', $p) as $seg) {
            if ($seg === '..') return false;
        }
        return true;
    }

    protected function is_protected($path) {
        $path = str_replace('\\', '/', $path);
        foreach ((array) $this->cfg('updater_protected', array()) as $ex) {
            $ex = str_replace('\\', '/', $ex);
            if (substr($ex, -1) === '/') {
                if (stripos($path, $ex) === 0) return true;
            } elseif (strcasecmp($path, $ex) === 0) {
                return true;
            }
        }
        return false;
    }

    /* ------------------------------------------------------------ HTTP */

    protected function assert_https($url) {
        $p = parse_url($url);
        $scheme = isset($p['scheme']) ? strtolower($p['scheme']) : '';
        $host   = isset($p['host']) ? strtolower($p['host']) : '';
        if ($scheme === 'https') return;
        if ($scheme === 'http' && in_array($host, array('localhost', '127.0.0.1'), true)) return; // untuk uji lokal
        throw new Exception('Alamat update harus HTTPS: ' . $url);
    }

    protected function absolute_url($base, $loc) {
        if (preg_match('#^https?://#i', $loc)) return $loc;
        $p = parse_url($base);
        $root = $p['scheme'] . '://' . $p['host'] . (isset($p['port']) ? ':' . $p['port'] : '');
        if ($loc !== '' && $loc[0] === '/') return $root . $loc;
        $path = isset($p['path']) ? preg_replace('#/[^/]*$#', '/', $p['path']) : '/';
        return $root . $path . $loc;
    }

    /**
     * GET ke $url. Jika $dest diisi, hasil disimpan ke file, jika tidak dikembalikan sebagai string.
     * Redirect diikuti manual (maks 5) supaya kode lisensi tidak bocor ke host lain.
     */
    protected function http($url, $dest = null) {
        if (!function_exists('curl_init')) throw new Exception('Ekstensi PHP curl belum aktif.');
        $origin_host = parse_url($url, PHP_URL_HOST);

        for ($hop = 0; $hop < 6; $hop++) {
            $this->assert_https($url);
            $host = parse_url($url, PHP_URL_HOST);

            $headers = array(
                'User-Agent: AppUpdater/1.0',
                'X-App-Version: ' . $this->current_version(),
                'X-App-Host: ' . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : gethostname()),
            );
            $key = (string) $this->cfg('updater_license_key', '');
            if ($key !== '' && $host === $origin_host) $headers[] = 'X-License-Key: ' . $key;

            $location = '';
            $fp = $dest ? fopen($dest, 'wb') : null;
            if ($dest && !$fp) throw new Exception('Tidak bisa menulis file sementara: ' . $dest);

            $ch = curl_init($url);
            curl_setopt_array($ch, array(
                CURLOPT_FOLLOWLOCATION => false,
                CURLOPT_TIMEOUT        => 300,
                CURLOPT_CONNECTTIMEOUT => 15,
                CURLOPT_HTTPHEADER     => $headers,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_SSL_VERIFYHOST => 2,
                CURLOPT_HEADERFUNCTION => function ($c, $h) use (&$location) {
                    if (stripos($h, 'location:') === 0) $location = trim(substr($h, 9));
                    return strlen($h);
                },
            ));
            if ($fp) {
                curl_setopt($ch, CURLOPT_FILE, $fp);
                curl_setopt($ch, CURLOPT_NOPROGRESS, false);
                curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, function () {
                    $a = func_get_args(); // ($ch, $dltotal, $dlnow, ...)
                    if (isset($a[1], $a[2]) && $a[1] > 0) {
                        $this->state['dl'] = array((int) $a[2], (int) $a[1]);
                        $this->pct(10 + (int) (25 * $a[2] / $a[1]));
                    }
                    return 0;
                });
            } else {
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            }

            $body = curl_exec($ch);
            $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $err  = curl_error($ch);
            curl_close($ch);
            if ($fp) fclose($fp);

            if ($body === false) {
                if ($dest) @unlink($dest);
                throw new Exception('Koneksi ke server update gagal: ' . $err);
            }
            if (in_array($code, array(301, 302, 303, 307, 308), true) && $location !== '') {
                $url = $this->absolute_url($url, $location);
                continue;
            }
            if ($code !== 200) {
                if ($dest) @unlink($dest);
                throw new Exception('Server update membalas HTTP ' . $code);
            }
            return $dest ? true : $body;
        }
        throw new Exception('Terlalu banyak redirect dari server update.');
    }

    /* -------------------------------------------------------- manifest */

    protected function valid_manifest($d) {
        return is_array($d) && !empty($d['version']) && $this->valid_version($d['version']);
    }

    public function manifest($force = false) {
        $cache = $this->dir . 'manifest_cache.json';
        $ttl = (int) $this->cfg('updater_cache_ttl', 21600);
        if (!$force && file_exists($cache) && (time() - filemtime($cache)) < $ttl) {
            $d = json_decode(file_get_contents($cache), true);
            if ($this->valid_manifest($d)) return $d;
        }
        $url = (string) $this->cfg('updater_manifest_url', '');
        if ($url === '' || strpos($url, 'USERNAME') !== false) {
            throw new Exception('updater_manifest_url belum diisi di application/config/updater.php');
        }
        $body = $this->http($url);
        $d = json_decode($body, true);
        if (!$this->valid_manifest($d)) throw new Exception('Format version.json tidak valid.');
        @file_put_contents($cache, $body);
        return $d;
    }

    public function check($force = false) {
        $m = $this->manifest($force);
        $cur = $this->current_version();
        $blocked = '';
        if (!empty($m['min_version']) && version_compare($cur, $m['min_version'], '<')) {
            $blocked = 'Versi terpasang terlalu lama. Pasang dulu versi ' . $m['min_version'] . ' terlebih dahulu.';
        }
        if (!empty($m['requires_php']) && version_compare(PHP_VERSION, $m['requires_php'], '<')) {
            $blocked = 'Update ini butuh PHP ' . $m['requires_php'] . ' atau lebih baru (terpasang ' . PHP_VERSION . ').';
        }
        return array(
            'current'     => $cur,
            'latest'      => $m['version'],
            'available'   => version_compare($m['version'], $cur, '>'),
            'changelog'   => isset($m['changelog']) ? (string) $m['changelog'] : '',
            'released_at' => isset($m['released_at']) ? (string) $m['released_at'] : '',
            'blocked'     => $blocked,
        );
    }

    /* ------------------------------------------------------ verification */

    protected function verify_signature($m) {
        $pub = trim((string) $this->cfg('updater_public_key', ''));
        if ($pub === '') {
            if ($this->cfg('updater_require_signature', false)) {
                throw new Exception('updater_require_signature aktif, tetapi updater_public_key kosong.');
            }
            $this->say('Peringatan: tanda tangan digital tidak diperiksa (hanya hash SHA-256).');
            return;
        }
        if (!function_exists('sodium_crypto_sign_verify_detached')) {
            throw new Exception('Ekstensi sodium belum aktif (php.ini: extension=sodium).');
        }
        $sig = isset($m['signature']) ? base64_decode($m['signature'], true) : false;
        if (!$sig || !sodium_crypto_sign_verify_detached($sig, $m['sha256'] . '|' . $m['version'], base64_decode($pub))) {
            throw new Exception('Tanda tangan update TIDAK valid. Update dibatalkan.');
        }
        $this->say('Tanda tangan digital valid.');
    }

    /* ------------------------------------------------------------ plan */

    // Baca isi ZIP dan susun rencana update
    protected function plan($zip) {
        $plan = array('write' => array(), 'delete' => array(), 'sql' => '', 'skipped' => array());
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $raw  = $zip->getNameIndex($i);
            $name = str_replace('\\', '/', $raw);
            if (substr($name, -1) === '/') continue;

            if ($name === '_update/migrate.sql') { $plan['sql'] = (string) $zip->getFromIndex($i); continue; }
            if ($name === '_update/delete.txt') {
                foreach (preg_split('/\r?\n/', (string) $zip->getFromIndex($i)) as $line) {
                    $line = trim($line);
                    if ($line !== '' && $line[0] !== '#') $plan['delete'][] = str_replace('\\', '/', $line);
                }
                continue;
            }
            if (strpos($name, '_update/') === 0) continue;

            if (!$this->safe_path($name)) throw new Exception('Path tidak aman di dalam paket update: ' . $raw);
            if ($this->is_protected($name)) { $plan['skipped'][] = $name; continue; }
            $plan['write'][$name] = $i;
        }
        $del = array();
        foreach ($plan['delete'] as $name) {
            if ($this->safe_path($name) && !$this->is_protected($name)) $del[] = $name;
            else $plan['skipped'][] = $name;
        }
        $plan['delete'] = $del;
        return $plan;
    }

    /* ---------------------------------------------------------- backup */

    protected function backup_files($plan, $from, $to) {
        $file = $this->dir . 'files_' . $from . '_to_' . $to . '_' . date('Ymd_His') . '.zip';
        $z = new ZipArchive;
        if ($z->open($file, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new Exception('Gagal membuat file backup.');
        }
        $added = array();
        foreach (array_keys($plan['write']) as $name) {
            $abs = FCPATH . $name;
            if (is_file($abs)) $z->addFile($abs, 'files/' . $name); else $added[] = $name;
        }
        foreach ($plan['delete'] as $name) {
            if (is_file(FCPATH . $name)) $z->addFile(FCPATH . $name, 'files/' . $name);
        }
        $z->addFromString('__meta.json', json_encode(array(
            'from' => $from, 'to' => $to, 'added' => $added, 'created' => date('c'),
        )));
        if (!$z->close()) throw new Exception('Gagal menulis file backup.');
        return $file;
    }

    protected function backup_db($to) {
        $this->CI->load->dbutil();
        $sql = $this->CI->dbutil->backup(array('format' => 'txt', 'add_drop' => true, 'add_insert' => true));
        if (!is_string($sql) || $sql === '') throw new Exception('Hasil backup database kosong.');
        $file = $this->dir . 'db_before_' . $to . '_' . date('Ymd_His') . '.sql.gz';
        if (file_put_contents($file, gzencode($sql, 6)) === false) throw new Exception('Gagal menulis backup database.');
        return $file;
    }

    // Kembalikan file dari ZIP backup. Mengembalikan versi asal.
    protected function restore_zip($path) {
        $z = new ZipArchive;
        if ($z->open($path) !== true) throw new Exception('File backup tidak bisa dibuka.');
        $meta = json_decode((string) $z->getFromName('__meta.json'), true);
        if (!is_array($meta) || !isset($meta['from']) || !$this->valid_version($meta['from'])) {
            throw new Exception('Metadata backup tidak valid.');
        }
        for ($i = 0; $i < $z->numFiles; $i++) {
            $n = str_replace('\\', '/', $z->getNameIndex($i));
            if (strpos($n, 'files/') !== 0 || substr($n, -1) === '/') continue;
            $name = substr($n, 6);
            if (!$this->safe_path($name) || $this->is_protected($name)) continue;
            $abs = FCPATH . $name;
            if (!is_dir(dirname($abs))) @mkdir(dirname($abs), 0755, true);
            file_put_contents($abs, $z->getFromIndex($i));
        }
        foreach ((array) $meta['added'] as $name) {
            if ($this->safe_path($name) && !$this->is_protected($name) && is_file(FCPATH . $name)) @unlink(FCPATH . $name);
        }
        $z->close();
        return $meta['from'];
    }

    public function backups() {
        $out = array();
        foreach ((array) glob($this->dir . 'files_*.zip') as $f) {
            $b = basename($f);
            $row = array('file' => $b, 'size' => filesize($f), 'time' => date('Y-m-d H:i', filemtime($f)), 'from' => '', 'to' => '');
            if (preg_match('/^files_(.+?)_to_(.+?)_\d{8}_\d{6}\.zip$/', $b, $m)) { $row['from'] = $m[1]; $row['to'] = $m[2]; }
            $out[$f] = $row;
        }
        uksort($out, function ($a, $b) { return filemtime($b) - filemtime($a); });
        return array_values($out);
    }

    protected function prune_backups() {
        $keep = max(1, (int) $this->cfg('updater_keep_backups', 5));
        foreach (array('files_*.zip', 'db_before_*.sql.gz') as $pattern) {
            $list = (array) glob($this->dir . $pattern);
            usort($list, function ($a, $b) { return filemtime($b) - filemtime($a); });
            foreach (array_slice($list, $keep) as $old) @unlink($old);
        }
    }

    /* ------------------------------------------------------- migration */

    protected function migrate($sql, $version) {
        $db = $this->CI->db;
        $db->db_debug = false; // tangkap error sendiri, jangan tampilkan halaman error
        $parts = preg_split('/;\s*(?:\r?\n|$)/', $sql);
        $n = 0;
        foreach ($parts as $stmt) {
            $stmt = trim(preg_replace('/^\s*--.*$/m', '', $stmt));
            if ($stmt === '') continue;
            if ($db->query($stmt) === false) {
                $e = $db->error();
                throw new Exception('Migrasi SQL gagal: ' . (isset($e['message']) ? $e['message'] : 'unknown') . ' | ' . substr($stmt, 0, 120));
            }
            $n++;
        }
        return $n;
    }

    protected function record_update($version, $note) {
        try {
            $db = $this->CI->db;
            $db->db_debug = false;
            $db->query('CREATE TABLE IF NOT EXISTS app_updates (id INT AUTO_INCREMENT PRIMARY KEY, version VARCHAR(20) NOT NULL, applied_at DATETIME NOT NULL, note TEXT NULL)');
            $db->insert('app_updates', array('version' => $version, 'applied_at' => date('Y-m-d H:i:s'), 'note' => $note));
        } catch (Exception $e) { /* pencatatan bersifat opsional */ }
    }

    protected function write_version($v) {
        if (!$this->valid_version($v)) throw new Exception('Nomor versi tidak valid.');
        $php = "<?php\ndefined('BASEPATH') OR exit('No direct script access allowed');\n\$config['app_version'] = '" . $v . "';\n";
        if (file_put_contents(APPPATH . 'config/version.php', $php) === false) {
            throw new Exception('Gagal menulis application/config/version.php');
        }
    }

    protected function after_change() {
        @unlink($this->dir . 'manifest_cache.json');
        if (function_exists('opcache_reset')) @opcache_reset();
    }

    /* ------------------------------------------------------------- run */

    public function run() {
        @set_time_limit(600);
        @ignore_user_abort(true);
        $this->steps = array();
        $this->lock();
        $this->state = $this->fresh_state();
        $this->flush(true);

        $tmp = null; $backup = null; $extracting = false;
        try {
            if (!class_exists('ZipArchive')) throw new Exception('Ekstensi PHP zip belum aktif (php.ini: extension=zip).');

            $m   = $this->manifest(true);
            $cur = $this->current_version();
            $new = $m['version'];
            if (!version_compare($new, $cur, '>')) throw new Exception('Sudah versi terbaru (' . $cur . ').');
            if (!empty($m['min_version']) && version_compare($cur, $m['min_version'], '<')) {
                throw new Exception('Versi ' . $cur . ' terlalu lama. Pasang dulu versi ' . $m['min_version'] . '.');
            }
            if (!empty($m['requires_php']) && version_compare(PHP_VERSION, $m['requires_php'], '<')) {
                throw new Exception('Update butuh PHP ' . $m['requires_php'] . '.');
            }
            if (empty($m['url']) || empty($m['sha256'])) throw new Exception('version.json tidak berisi url/sha256.');

            $this->say("Memulai update $cur -> $new");
            $this->pct(8);

            // 1. Unduh & verifikasi
            $tmp = $this->dir . 'tmp_' . $new . '.zip';
            $this->say('Mengunduh paket update...');
            $this->http($m['url'], $tmp);
            $this->state['phase'] = 'verify';
            $this->flush(true);
            if (!hash_equals(strtolower($m['sha256']), hash_file('sha256', $tmp))) {
                throw new Exception('Hash SHA-256 tidak cocok. Paket mungkin rusak atau dipalsukan. Update dibatalkan.');
            }
            $this->say('Hash SHA-256 cocok.');
            $this->pct(36);
            $this->verify_signature($m);

            // 2. Baca paket
            $zip = new ZipArchive;
            if ($zip->open($tmp) !== true) throw new Exception('Paket ZIP tidak bisa dibuka.');
            $plan = $this->plan($zip);
            $this->say(count($plan['write']) . ' file akan diperbarui, ' . count($plan['delete']) . ' file dihapus'
                . ($plan['sql'] !== '' ? ', ada migrasi database.' : '.'));
            if ($plan['skipped']) $this->say('Dilewati (dilindungi): ' . implode(', ', $plan['skipped']));
            $this->state['phase'] = 'backup';
            $this->state['has_sql'] = ($plan['sql'] !== '');
            $this->pct(42);

            // 3. Backup
            $mode = $this->cfg('updater_backup_db', 'auto');
            if ($mode === true || ($mode === 'auto' && $plan['sql'] !== '')) {
                try {
                    $this->say('Backup database: ' . basename($this->backup_db($new)));
                } catch (Exception $e) {
                    if ($plan['sql'] !== '') throw new Exception('Backup database gagal, update dibatalkan: ' . $e->getMessage());
                    $this->say('Peringatan: backup database gagal (' . $e->getMessage() . ')');
                }
            }
            $this->pct(48);
            $backup = $this->backup_files($plan, $cur, $new);
            $this->say('Backup file: ' . basename($backup));
            $this->pct(55);

            // 4. Tulis file
            $extracting = true;
            $total = max(1, count($plan['write'])); $i = 0;
            $this->state['phase'] = 'write';
            $this->state['wt'] = count($plan['write']);
            foreach ($plan['write'] as $name => $idx) {
                $i++;
                $this->state['wi'] = $i;
                $abs  = FCPATH . $name;
                $data = $zip->getFromIndex($idx);
                if ($data === false) throw new Exception('Gagal membaca ' . $name . ' dari paket update');
                $exists = is_file($abs);
                if ($exists && filesize($abs) === strlen($data) && md5_file($abs) === md5($data)) {
                    $this->state['same']++;           // isi sama persis, tidak perlu ditulis ulang
                } else {
                    if (!is_dir(dirname($abs)) && !@mkdir(dirname($abs), 0755, true)) throw new Exception('Gagal membuat folder untuk ' . $name);
                    if (file_put_contents($abs, $data) === false) throw new Exception('Gagal menulis ' . $name);
                    $this->file_log($name, $exists ? 'diubah' : 'baru');
                }
                $this->pct(55 + (int) (35 * $i / $total));
            }
            foreach ($plan['delete'] as $name) {
                if (is_file(FCPATH . $name)) { @unlink(FCPATH . $name); $this->file_log($name, 'dihapus'); }
            }
            $zip->close();
            $c = $this->counts();
            $this->pct(92);
            $this->say('File aplikasi diperbarui: ' . $c['baru'] . ' baru, ' . $c['diubah'] . ' diubah, ' . $c['dihapus'] . ' dihapus, ' . $this->state['same'] . ' tidak berubah.');

            // 5. Migrasi database
            if ($plan['sql'] !== '') {
                $this->state['phase'] = 'migrate';
                $this->flush(true);
                $n = $this->migrate($plan['sql'], $new);
                $this->say("Migrasi database selesai ($n perintah SQL).");
            }
            $this->pct(96);

            // 6. Selesai
            $this->write_version($new);
            $this->record_update($new, isset($m['changelog']) ? (string) $m['changelog'] : '');
            $this->after_change();
            @unlink($tmp);
            $this->prune_backups();
            $this->state['percent'] = 100; $this->state['ok'] = true; $this->state['running'] = false; $this->state['version'] = $new; $this->state['phase'] = 'done';
            $this->say("Update ke versi $new BERHASIL.");

            $this->unlock();
            return array('version' => $new, 'steps' => $this->steps, 'backup' => basename($backup),
                         'files' => $this->state['files'], 'same' => $this->state['same']);

        } catch (Exception $e) {
            if ($extracting && $backup) {
                try {
                    $this->restore_zip($backup);
                    $this->after_change();
                    $this->say('Terjadi kesalahan, file dikembalikan otomatis dari backup.');
                } catch (Exception $e2) {
                    $this->say('GAGAL mengembalikan file otomatis: ' . $e2->getMessage() . ' Pulihkan manual dari ' . basename($backup));
                }
            }
            if ($tmp && is_file($tmp)) @unlink($tmp);
            $this->state['ok'] = false; $this->state['running'] = false; $this->state['error'] = $e->getMessage();
            $this->say('GAGAL: ' . $e->getMessage());
            $this->unlock();
            throw $e;
        }
    }

    /* -------------------------------------------------------- rollback */

    public function rollback($basename) {
        $this->steps = array();
        if (!preg_match('/^files_[0-9A-Za-z._-]+\.zip$/', $basename)) throw new Exception('Nama file backup tidak valid.');
        $path = $this->dir . $basename;
        if (!is_file($path)) throw new Exception('File backup tidak ditemukan.');

        $this->lock();
        try {
            $from = $this->restore_zip($path);
            $this->write_version($from);
            $this->after_change();
            $this->say("Aplikasi dikembalikan ke versi $from.");
            $this->say('Catatan: database TIDAK ikut dikembalikan. Jika update memuat migrasi SQL, pulihkan dari file db_before_*.sql.gz bila perlu.');
            $this->unlock();
            return array('version' => $from, 'steps' => $this->steps);
        } catch (Exception $e) {
            $this->unlock();
            throw $e;
        }
    }
}