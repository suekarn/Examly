<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Converter extends CI_Controller {
    // Verifikasi sertifikat SSL untuk request server -> provider AI (proxy / health check).
    // Biarkan true. Ubah ke false HANYA di XAMPP lokal jika muncul "cURL error 60"
    // dan curl.cainfo di php.ini belum diatur (lebih aman: unduh cacert.pem lalu set curl.cainfo).
    private $ssl_verify = true;

    private $providers = [
        'gemini' => ['name'=>'Google Gemini','models'=>['gemini-3.5-flash','gemini-3.1-flash-lite']],
        'openai' => ['name'=>'OpenAI','models'=>['gpt-4.1-mini','gpt-4.1']],
        'groq' => ['name'=>'Groq','models'=>['openai/gpt-oss-120b','openai/gpt-oss-20b']],
        'pollinations' => ['name'=>'Pollinations.ai','models'=>['openai','openai-fast','deepseek']],
        'openrouter' => ['name'=>'OpenRouter','models'=>['deepseek/deepseek-chat','meta-llama/llama-3.3-70b-instruct']],
    ];

    public function __construct() {
        parent::__construct();

        // Bersihkan permanen konfigurasi/key provider yang sudah dihapus.
        // Pemeriksaan table_exists menjaga kompatibilitas dengan instalasi lama.
        foreach (['ai_config' => ['key_name', ['cloudflare_account','cloudflare_key','nvidia_nim','nvidia_nim_key','nvidia_nim_api_key','mistral','mistral_key','mistral_api_key','CUSTOM_MISTRAL_KEY']],
                  'ai_keys' => ['provider_key', ['cloudflare','nvidia_nim','nvidia-nim','nvidia','mistral']],
                  'ai_providers' => ['provider_key', ['cloudflare','nvidia_nim','nvidia-nim','nvidia','mistral']],
                  'ai_models' => ['provider_key', ['cloudflare','nvidia_nim','nvidia-nim','nvidia','mistral']]] as $table => $cleanup) {
            if ($this->db->table_exists($table) && $this->db->field_exists($cleanup[0], $table)) {
                $this->db->where_in($cleanup[0], $cleanup[1])->delete($table);
            }
        }
        if ($this->db->table_exists('ai_config')) {
            $chainRow = $this->db->get_where('ai_config', ['key_name' => 'chain'])->row();
            if ($chainRow) {
                $chain = json_decode((string)$chainRow->key_value, true);
                if (is_array($chain)) {
                    $chain = array_values(array_filter($chain, function($provider) {
                        return !in_array(strtolower((string)$provider), ['cloudflare','nvidia_nim','nvidia-nim','nvidia','mistral'], true);
                    }));
                    $this->db->where('key_name', 'chain')->update('ai_config', ['key_value' => json_encode($chain)]);
                }
            }
        }
        // Hapus juga label provider lama bila tersimpan pada tabel provider.
        if ($this->db->table_exists('ai_providers') && $this->db->field_exists('provider_name', 'ai_providers')) {
            $this->db->like('provider_name', 'Cloudflare')->or_like('provider_name', 'NVIDIA NIM');
            $this->db->delete('ai_providers');
        }


        // Bersihkan seluruh sisa konfigurasi Mistral lama dari database.
        foreach (['ai_keys','ai_providers','ai_models','ai_health','ai_usage','ai_logs'] as $table) {
            if ($this->db->table_exists($table) && $this->db->field_exists('provider_key', $table)) {
                $this->db->where('provider_key', 'mistral')->delete($table);
            }
        }
        if ($this->db->table_exists('ai_config')) {
            $this->db->like('key_name', 'mistral')->delete('ai_config');
            $chainRow = $this->db->get_where('ai_config', ['key_name' => 'chain'])->row();
            if ($chainRow) {
                $chain = json_decode((string)$chainRow->key_value, true);
                if (is_array($chain)) {
                    $chain = array_values(array_filter($chain, function($provider) { return strtolower((string)$provider) !== 'mistral'; }));
                    $this->db->where('key_name', 'chain')->update('ai_config', ['key_value' => json_encode($chain)]);
                }
            }
        }

        // =========================================================================
        // PENGECEKAN LISENSI — delegasikan sepenuhnya ke license_helper.php
        // (satu sumber kebenaran bersama sidebar.php, supaya logika tidak
        // dobel/berbeda antara yang mengunci modul vs yang sembunyikan menu)
        // =========================================================================
        $this->load->helper('license');
        $lg = lg_evaluate_access();

        if ($lg['block']) {
            echo $this->load->view('errors/html/error_license', $lg['error'], TRUE);
            exit();
        }
        // =========================================================================

        if (!$this->ion_auth->logged_in()) redirect('auth/login', 'refresh');
        $this->load->model('Dashboard_model', 'dashboard');
        $this->load->model('Cbt_model', 'cbt');
        $this->ensure_ai_manager_schema();
    }

    private function ensure_ai_manager_schema() {
        $sql = [
            "CREATE TABLE IF NOT EXISTS ai_config (id INT AUTO_INCREMENT PRIMARY KEY,key_name VARCHAR(80) UNIQUE,key_value TEXT) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            "CREATE TABLE IF NOT EXISTS ai_providers (id INT AUTO_INCREMENT PRIMARY KEY,provider_key VARCHAR(40) UNIQUE,provider_name VARCHAR(100),enabled TINYINT(1) DEFAULT 1,priority INT DEFAULT 100,created_at DATETIME NULL,updated_at DATETIME NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            "CREATE TABLE IF NOT EXISTS ai_keys (id INT AUTO_INCREMENT PRIMARY KEY,provider_key VARCHAR(40) NOT NULL,key_index INT DEFAULT 1,key_value TEXT NOT NULL,enabled TINYINT(1) DEFAULT 1,status VARCHAR(20) DEFAULT 'unknown',fail_count INT DEFAULT 0,success_count INT DEFAULT 0,total_requests INT DEFAULT 0,cooldown_until DATETIME NULL,last_error TEXT NULL,last_used_at DATETIME NULL,last_success_at DATETIME NULL,created_at DATETIME NULL,updated_at DATETIME NULL,INDEX(provider_key),INDEX(status),UNIQUE KEY uq_ai_key(provider_key,key_index)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            "CREATE TABLE IF NOT EXISTS ai_models (id INT AUTO_INCREMENT PRIMARY KEY,provider_key VARCHAR(40),model_name VARCHAR(150),enabled TINYINT(1) DEFAULT 1,priority INT DEFAULT 100,success_count INT DEFAULT 0,fail_count INT DEFAULT 0,created_at DATETIME NULL,updated_at DATETIME NULL,UNIQUE KEY uq_ai_model(provider_key,model_name)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            "CREATE TABLE IF NOT EXISTS ai_health (id INT AUTO_INCREMENT PRIMARY KEY,provider_key VARCHAR(40),key_index INT DEFAULT 1,status VARCHAR(30) DEFAULT 'unknown',model_name VARCHAR(150) NULL,http_status INT NULL,response_ms INT NULL,message TEXT NULL,checked_at DATETIME NULL,UNIQUE KEY uq_ai_health(provider_key,key_index)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            "CREATE TABLE IF NOT EXISTS ai_usage (id BIGINT AUTO_INCREMENT PRIMARY KEY,provider_key VARCHAR(40),key_index INT DEFAULT 1,model_name VARCHAR(150),success TINYINT(1) DEFAULT 0,http_status INT NULL,response_ms INT NULL,question_count INT DEFAULT 0,error_message TEXT NULL,created_at DATETIME NULL,INDEX(provider_key),INDEX(created_at)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            "CREATE TABLE IF NOT EXISTS ai_logs (id BIGINT AUTO_INCREMENT PRIMARY KEY,level VARCHAR(20) DEFAULT 'info',event VARCHAR(60),provider_key VARCHAR(40) NULL,key_index INT NULL,model_name VARCHAR(150) NULL,message TEXT,meta LONGTEXT NULL,created_at DATETIME NULL,INDEX(created_at),INDEX(provider_key)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            "CREATE TABLE IF NOT EXISTS ai_settings (setting_key VARCHAR(80) PRIMARY KEY,setting_value TEXT) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            "CREATE TABLE IF NOT EXISTS converter_cache (id BIGINT AUTO_INCREMENT PRIMARY KEY,fingerprint CHAR(64) NOT NULL,payload LONGTEXT NOT NULL,question_count INT DEFAULT 0,created_at DATETIME NULL,updated_at DATETIME NULL,UNIQUE KEY uq_converter_cache_fingerprint(fingerprint),INDEX(updated_at)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
        ];
        foreach ($sql as $q) { @$this->db->query($q); }

        if ($this->db->table_exists('ai_providers')) {
            @$this->db->where('provider_key','github')->update('ai_providers',['enabled'=>0,'updated_at'=>date('Y-m-d H:i:s')]);
        }
        if ($this->db->table_exists('ai_keys')) {
            @$this->db->where('provider_key','github')->delete('ai_keys');
        }
        if ($this->db->table_exists('ai_health')) {
            @$this->db->where('provider_key','github')->delete('ai_health');
        }
        if ($this->db->table_exists('ai_models')) {
            @$this->db->where('provider_key','github')->delete('ai_models');
        }
        if ($this->db->table_exists('ai_providers')) {
            @$this->db->where('provider_key','github')->delete('ai_providers');
        }
        // Provider yang sudah dihapus dari aplikasi: bersihkan sisa datanya.
        foreach (['deepseek','cerebras','claude','kimi'] as $gone) {
            foreach (['ai_providers','ai_keys','ai_health','ai_models','ai_usage','ai_logs'] as $tbl) {
                if ($this->db->table_exists($tbl)) @$this->db->where('provider_key',$gone)->delete($tbl);
            }
            if ($this->db->table_exists('ai_config')) @$this->db->where('key_name',$gone)->delete('ai_config');
        }

        // Buang nama provider yang sudah dihapus dari urutan rantai (chain) yang tersimpan.
        if ($this->db->table_exists('ai_config')) {
            $chainRow = $this->db->get_where('ai_config',['key_name'=>'chain'])->row();
            if ($chainRow) {
                $oldChain = json_decode((string)$chainRow->key_value, true);
                if (is_array($oldChain)) {
                    $newChain = array_values(array_diff($oldChain, ['claude','kimi','deepseek','cerebras','github']));
                    if ($newChain !== $oldChain) $this->db->where('key_name','chain')->update('ai_config',['key_value'=>json_encode($newChain)]);
                }
            }
        }

        if ($this->db->table_exists('ai_models')) {
            foreach (['last_status'=>'VARCHAR(30) NULL','last_http'=>'INT NULL','last_ms'=>'INT NULL','last_message'=>'TEXT NULL','last_checked_at'=>'DATETIME NULL'] as $col=>$def) {
                if (!$this->db->field_exists($col, 'ai_models')) @$this->db->query("ALTER TABLE ai_models ADD $col $def");
            }
        }
        if ($this->db->table_exists('ai_health') && !$this->db->field_exists('model_name', 'ai_health')) {
            @$this->db->query("ALTER TABLE ai_health ADD model_name VARCHAR(150) NULL AFTER status");
        }

        $now = date('Y-m-d H:i:s');
        foreach ($this->providers as $key=>$p) {
            $exists = $this->db->get_where('ai_providers',['provider_key'=>$key])->row();
            if (!$exists) $this->db->insert('ai_providers',['provider_key'=>$key,'provider_name'=>$p['name'],'enabled'=>1,'priority'=>100,'created_at'=>$now,'updated_at'=>$now]);
            
            $allowedModels = $p['models'];
            if ($this->db->table_exists('ai_models')) {
                $this->db->where('provider_key',$key)->where_not_in('model_name',$allowedModels)->delete('ai_models');
            }
            foreach ($allowedModels as $i=>$model) {
                $existsM = $this->db->get_where('ai_models',['provider_key'=>$key,'model_name'=>$model])->row();
                // Hanya tambahkan model baru. Model yang sudah ada TIDAK di-reset,
                // supaya pilihan aktif/nonaktif dan urutan dari Model Manager tetap tersimpan.
                if (!$existsM) {
                    $this->db->insert('ai_models',['provider_key'=>$key,'model_name'=>$model,'enabled'=>1,'priority'=>$i+1,'created_at'=>$now,'updated_at'=>$now]);
                }
            }
            if ($this->db->table_exists('ai_health')) {
                $this->db->where('provider_key',$key)->where_not_in('model_name',$allowedModels)->delete('ai_health');
            }
        }
    }

    private function cut($s, $len) {
        $s = (string)$s;
        return function_exists('mb_substr') ? mb_substr($s, 0, $len, 'UTF-8') : substr($s, 0, $len);
    }

    private function valid_model($m) {
        return is_string($m) && preg_match('#^[A-Za-z0-9@._:/\-]{1,150}$#', $m) && strpos($m, '..') === false;
    }

    private function ai_log($level, $event, $provider, $model, $message, $meta = []) {
        $this->db->insert('ai_logs', [
            'level'=>$level, 'event'=>$event, 'provider_key'=>$provider, 'key_index'=>NULL,
            'model_name'=>$model, 'message'=>$message, 'meta'=>json_encode($meta), 'created_at'=>date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Panggil provider AI dari SERVER (cURL). Dipakai untuk provider yang tidak bisa dipanggil
     * langsung dari browser dan sebagai fallback
     * jika request langsung dari browser gagal karena jaringan/CORS.
     */
    private function call_provider($provider, $apiKey, $model, $prompt, $account = '') {
        $t0 = microtime(true);
        $fail = function($http, $msg) use ($t0) {
            return ['ok'=>false,'http'=>(int)$http,'message'=>$msg,'ms'=>(int)round((microtime(true)-$t0)*1000)];
        };
        if (!function_exists('curl_init')) return $fail(0, 'Ekstensi cURL PHP belum aktif di server (aktifkan extension=curl di php.ini).');

        $headers = ['Content-Type: application/json'];
        if ($provider === 'gemini') {
            $url = 'https://generativelanguage.googleapis.com/v1beta/models/'.$model.':generateContent';
            $headers[] = 'x-goog-api-key: '.$apiKey;
            $body = ['contents'=>[['parts'=>[['text'=>$prompt]]]], 'generationConfig'=>['temperature'=>0.1,'responseMimeType'=>'application/json']];
        } else {
            $endpoints = [
                'openai'=>'https://api.openai.com/v1/chat/completions',
                'groq'=>'https://api.groq.com/openai/v1/chat/completions',
                'pollinations'=>'https://gen.pollinations.ai/v1/chat/completions',
                'openrouter'=>'https://openrouter.ai/api/v1/chat/completions',
            ];
            if (!isset($endpoints[$provider])) return $fail(0, 'Provider tidak dikenal: '.$provider);
            $url = $endpoints[$provider];
            $headers[] = 'Authorization: Bearer '.$apiKey;
            if ($provider === 'pollinations') {
                $body = ['model'=>$model,'temperature'=>0,'messages'=>[
                    ['role'=>'system','content'=>'You are a helpful assistant that outputs strict JSON.'],
                    ['role'=>'user','content'=>$prompt]
                ]];
            } else {
                $body = ['model'=>$model,'temperature'=>0.1,'messages'=>[['role'=>'user','content'=>$prompt]]];
                if (in_array($provider, ['openai','groq'], true)) $body['response_format'] = ['type'=>'json_object'];
            }
        }

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST=>true,
            CURLOPT_POSTFIELDS=>json_encode($body, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES),
            CURLOPT_HTTPHEADER=>$headers,
            CURLOPT_RETURNTRANSFER=>true,
            CURLOPT_CONNECTTIMEOUT=>10,
            CURLOPT_TIMEOUT=>90,
            CURLOPT_SSL_VERIFYPEER=>$this->ssl_verify,
            CURLOPT_SSL_VERIFYHOST=>$this->ssl_verify ? 2 : 0
        ]);
        $raw = curl_exec($ch);
        $http = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $errNo = curl_errno($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($raw === false) {
            if (in_array($errNo, [35, 51, 58, 60, 77], true)) {
                return $fail(0, 'Server gagal memverifikasi sertifikat SSL (cURL error '.$errNo.'). Di XAMPP: unduh cacert.pem lalu isi curl.cainfo di php.ini, atau set $ssl_verify=false di Converter.php (hanya untuk lokal).');
            }
            return $fail(0, 'Server gagal terhubung ke provider: '.$err);
        }

        $j = json_decode($raw, true);
        if ($http < 200 || $http >= 300) {
            $msg = '';
            if (is_array($j)) {
                $msg = $j['error']['message'] ?? ($j['errors'][0]['message'] ?? ($j['message'] ?? ''));
                if (!$msg && isset($j['error']) && is_string($j['error'])) $msg = $j['error'];
            }
            if (!$msg) $msg = trim($this->cut(strip_tags((string)$raw), 200));
            return $fail($http, $msg ?: ('HTTP '.$http));
        }

        $text = '';
        if (is_array($j)) {
            if ($provider === 'gemini') {
                foreach (($j['candidates'][0]['content']['parts'] ?? []) as $part) {
                    if (!empty($part['thought'])) continue;
                    $text .= (string)($part['text'] ?? '');
                }
            } else {
                $text = (string)($j['choices'][0]['message']['content'] ?? '');
            }
        }
        if (trim($text) === '') return $fail($http, 'Respons HTTP '.$http.' tetapi isi jawaban kosong.');
        return ['ok'=>true,'http'=>$http,'content'=>$text,'ms'=>(int)round((microtime(true)-$t0)*1000)];
    }

    private function json($data) {
        $flags = JSON_UNESCAPED_UNICODE;
        if (defined('JSON_INVALID_UTF8_SUBSTITUTE')) $flags |= JSON_INVALID_UTF8_SUBSTITUTE;
        return $this->output->set_content_type('application/json')->set_output(json_encode($data, $flags));
    }

    private function sync_keys_to_manager($keysData) {
        $now = date('Y-m-d H:i:s');
        foreach ($this->providers as $provider=>$p) {
            $raw = $keysData[$provider] ?? '';
            $keys = array_values(array_filter(array_map('trim', preg_split('/\s*,\s*/', (string)$raw))));

            foreach ($keys as $idx=>$key) {
                $n = $idx + 1;
                $old = $this->db->get_where('ai_keys',['provider_key'=>$provider,'key_index'=>$n])->row();

                $changed = $old && trim((string)$old->key_value) !== trim((string)$key);
                $data = [
                    'provider_key'=>$provider,
                    'key_index'=>$n,
                    'key_value'=>$key,
                    'enabled'=>1,
                    'updated_at'=>$now
                ];

                if ($old) {
                    if ($changed) {
                        $data['status'] = 'unknown';
                        $data['cooldown_until'] = NULL;
                        $data['last_error'] = NULL;
                        @$this->db->where(['provider_key'=>$provider,'key_index'=>$n])->delete('ai_health');
                    }
                    $this->db->where('id',$old->id)->update('ai_keys',$data);
                } else {
                    $data['status']='unknown';
                    $data['created_at']=$now;
                    $this->db->insert('ai_keys',$data);
                }
            }

            if (count($keys) > 0) {
                $this->db->where('provider_key',$provider)
                    ->where('key_index >',count($keys))
                    ->update('ai_keys',[
                        'enabled'=>0,
                        'status'=>'disabled',
                        'updated_at'=>$now
                    ]);
                $this->db->where('provider_key',$provider)
                    ->where('key_index >',count($keys))
                    ->delete('ai_health');
            } else {
                $this->db->where('provider_key',$provider)
                    ->update('ai_keys',[
                        'enabled'=>0,
                        'status'=>'disabled',
                        'updated_at'=>$now
                    ]);
                $this->db->where('provider_key',$provider)->delete('ai_health');
            }
        }
    }

    private function read_saved_keys() {
        $savedKeys = ['gemini'=>'','openai'=>'','groq'=>'','pollinations'=>'','openrouter'=>'','chain'=>'["gemini","openai","groq","pollinations","openrouter"]'];
        if ($this->db->table_exists('ai_config')) {
            foreach ($this->db->get('ai_config')->result_array() as $row) if (array_key_exists($row['key_name'],$savedKeys)) $savedKeys[$row['key_name']]=$row['key_value'];
        }
        $chain=json_decode((string)$savedKeys['chain'],true);
        if (is_array($chain)) $savedKeys['chain']=json_encode(array_values(array_intersect($chain,array_keys($this->providers))));
        return $savedKeys;
    }

    private function manager_snapshot() {
        $saved = $this->read_saved_keys();
        $this->sync_keys_to_manager($saved);

        $chain=json_decode((string)($saved['chain'] ?? ''),true);
        if(!is_array($chain) || !$chain) $chain=array_keys($this->providers);
        foreach($this->providers as $provider=>$cfg){
            $pos=array_search($provider,$chain,true);
            $this->db->where('provider_key',$provider)->update('ai_providers',[
                'enabled'=>($pos!==false)?1:0,
                'priority'=>($pos!==false)?($pos+1):999,
                'updated_at'=>date('Y-m-d H:i:s')
            ]);
        }

        $providers = $this->db->order_by('priority','ASC')->get('ai_providers')->result_array();
        $keys = $this->db->order_by('provider_key','ASC')->order_by('key_index','ASC')
            ->where('key_value !=', '')
            ->get('ai_keys')->result_array();

        $usage = $this->db->select('COUNT(*) total,COALESCE(SUM(success),0) success,COALESCE(SUM(CASE WHEN success=0 THEN 1 ELSE 0 END),0) failed,COALESCE(AVG(response_ms),0) avg_ms')
            ->get('ai_usage')->row_array();
        $usage['total']=(int)($usage['total']??0);
        $usage['success']=(int)($usage['success']??0);
        $usage['failed']=(int)($usage['failed']??0);
        $usage['avg_ms']=(float)($usage['avg_ms']??0);

        // Pemakaian per provider HARI INI (dasar perhitungan bar persen limit).
        $usageByProvider = [];
        $ubpRows = $this->db->select('provider_key, COUNT(*) total, COALESCE(SUM(success),0) success, COALESCE(AVG(response_ms),0) avg_ms')
            ->where('created_at >=', date('Y-m-d 00:00:00'))
            ->group_by('provider_key')
            ->get('ai_usage')->result_array();
        foreach ($ubpRows as $r) {
            $usageByProvider[$r['provider_key']] = [
                'total'=>(int)$r['total'],
                'success'=>(int)$r['success'],
                'avg_ms'=>(float)$r['avg_ms']
            ];
        }

        $logs = $this->db->order_by('id','DESC')->limit(50)->get('ai_logs')->result_array();
        $healthRows = $this->db->order_by('provider_key','ASC')->order_by('key_index','ASC')->get('ai_health')->result_array();

        $healthMap=[];
        foreach ($healthRows as $h) {
            $healthMap[$h['provider_key'].'#'.$h['key_index']]=$h;
        }

        $health=[];
        foreach ($keys as &$k) {
            if ($k['status']==='unknown' && (int)$k['total_requests']>0) {
                $k['status']=((int)$k['success_count']>0)?'healthy':'error';
            }

            $id=$k['provider_key'].'#'.$k['key_index'];
            if (isset($healthMap[$id])) {
                $h=$healthMap[$id];
                if (($h['status']==='unknown' || !$h['status']) && $k['status']!=='unknown') $h['status']=$k['status'];
                $health[]=$h;
            } else {
                $health[]=[
                    'id'=>NULL,
                    'provider_key'=>$k['provider_key'],
                    'key_index'=>$k['key_index'],
                    'status'=>$k['status'] ?: 'unknown',
                    'model_name'=>NULL,
                    'http_status'=>NULL,
                    'response_ms'=>NULL,
                    'message'=>'Belum pernah dilakukan Health Check.',
                    'checked_at'=>NULL
                ];
            }
        }
        unset($k);

        return [
            'providers'=>$providers,
            'keys'=>$keys,
            'active_keys'=>count(array_filter($keys,function($k){ return (int)$k['enabled']===1 && in_array($k['status'],['healthy','unknown'],true); })),
            'health'=>$health,
            'usage'=>$usage,
            'usage_by_provider'=>$usageByProvider,
            'logs'=>$logs,
            'models'=>$this->db->order_by('provider_key','ASC')->order_by('priority','ASC')->order_by('id','ASC')->get('ai_models')->result_array(),
            'settings'=>$this->db->get('ai_settings')->result_array()
        ];
    }

    public function index() {
        if ($this->input->post('action') === 'save_keys') {
            $keysData=[];
            foreach (['gemini','openai','groq','pollinations','openrouter','chain'] as $k) $keysData[$k]=$this->input->post($k, TRUE);
            if (!$this->db->table_exists('ai_config')) $this->ensure_ai_manager_schema();
            foreach ($keysData as $k=>$v) $this->db->replace('ai_config',['key_name'=>$k,'key_value'=>$v]);
            $this->sync_keys_to_manager($keysData);

            $chain=json_decode((string)($keysData['chain'] ?? ''),true);
            if(!is_array($chain) || !$chain) $chain=array_keys($this->providers);
            foreach($this->providers as $provider=>$cfg){
                $pos=array_search($provider,$chain,true);
                $enabled=($pos!==false)?1:0;
                $priority=($pos!==false)?($pos+1):999;
                $this->db->where('provider_key',$provider)->update('ai_providers',[
                    'enabled'=>$enabled,
                    'priority'=>$priority,
                    'updated_at'=>date('Y-m-d H:i:s')
                ]);
            }
            return $this->json(['status'=>'success','message'=>'API Key, Provider Chain, Models, Health, Usage, Logs dan Settings berhasil disinkronkan.','snapshot'=>$this->manager_snapshot()]);
        }

        $action=$this->input->post('action', TRUE);

        if ($action==='cache_lookup') {
            $fingerprint=trim((string)$this->input->post('fingerprint', TRUE));
            if (!preg_match('/^[a-f0-9]{64}$/i',$fingerprint)) {
                return $this->json(['status'=>'miss','message'=>'Fingerprint tidak valid.']);
            }
            $row=$this->db->get_where('converter_cache',['fingerprint'=>strtolower($fingerprint)])->row();
            if (!$row) return $this->json(['status'=>'miss']);
            $data=json_decode((string)$row->payload,true);
            if (!is_array($data) || !$data) {
                @$this->db->where('id',$row->id)->delete('converter_cache');
                return $this->json(['status'=>'miss']);
            }
            return $this->json([
                'status'=>'hit',
                'data'=>$data,
                'question_count'=>(int)$row->question_count,
                'cached_at'=>$row->updated_at ?: $row->created_at
            ]);
        }

        if ($action==='cache_save') {
            $fingerprint=trim((string)$this->input->post('fingerprint', TRUE));
            $payload=(string)$this->input->post('payload', FALSE);
            if (!preg_match('/^[a-f0-9]{64}$/i',$fingerprint)) {
                return $this->json(['status'=>'error','message'=>'Fingerprint tidak valid.']);
            }
            $data=json_decode($payload,true);
            if (!is_array($data) || !$data) {
                return $this->json(['status'=>'error','message'=>'Payload cache tidak valid.']);
            }
            $now=date('Y-m-d H:i:s');
            $row=[
                'fingerprint'=>strtolower($fingerprint),
                'payload'=>json_encode($data,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES),
                'question_count'=>count($data),
                'updated_at'=>$now
            ];
            $exists=$this->db->get_where('converter_cache',['fingerprint'=>strtolower($fingerprint)])->row();
            if ($exists) {
                $this->db->where('id',$exists->id)->update('converter_cache',$row);
            } else {
                $row['created_at']=$now;
                $this->db->insert('converter_cache',$row);
            }
            return $this->json(['status'=>'success','question_count'=>count($data)]);
        }

        if ($action==='cache_delete') {
            $fingerprint=trim((string)$this->input->post('fingerprint', TRUE));
            if (preg_match('/^[a-f0-9]{64}$/i',$fingerprint)) {
                $this->db->where('fingerprint',strtolower($fingerprint))->delete('converter_cache');
            }
            return $this->json(['status'=>'success']);
        }

        if ($action==='manager_snapshot') return $this->json(['status'=>'success']+$this->manager_snapshot());
        
        if ($action==='manager_event') {
            $provider=$this->input->post('provider_key',TRUE);$keyIndex=(int)$this->input->post('key_index');$model=$this->input->post('model_name',TRUE);$success=(int)$this->input->post('success');$status=(int)$this->input->post('http_status');$ms=(int)$this->input->post('response_ms');$message=$this->input->post('message',TRUE);$event=$this->input->post('event',TRUE) ?: ($success?'success':'error');$now=date('Y-m-d H:i:s');
            $this->db->insert('ai_usage',['provider_key'=>$provider,'key_index'=>$keyIndex,'model_name'=>$model,'success'=>$success,'http_status'=>$status ?: NULL,'response_ms'=>$ms ?: NULL,'question_count'=>(int)$this->input->post('question_count'),'error_message'=>$message,'created_at'=>$now]);
            $key=$this->db->get_where('ai_keys',['provider_key'=>$provider,'key_index'=>$keyIndex])->row();
            if ($key) { $upd=['total_requests'=>$key->total_requests+1,'success_count'=>$key->success_count+$success,'fail_count'=>$key->fail_count+($success?0:1),'last_used_at'=>$now,'updated_at'=>$now,'last_error'=>$success?NULL:$message]; if($success)$upd['last_success_at']=$now; if(in_array($status,[429,408,500,502,503,504])){$upd['status']='cooldown';$upd['cooldown_until']=date('Y-m-d H:i:s',time()+60);}elseif($success){$upd['status']='healthy';$upd['cooldown_until']=NULL;}elseif(in_array($status,[401,403])){$upd['status']='disabled';}else{$upd['status']='error';}$this->db->where('id',$key->id)->update('ai_keys',$upd); }
            $this->db->insert('ai_logs',['level'=>$success?'info':'error','event'=>$event,'provider_key'=>$provider,'key_index'=>$keyIndex,'model_name'=>$model,'message'=>$message,'meta'=>json_encode(['http_status'=>$status,'response_ms'=>$ms]),'created_at'=>$now]);
            return $this->json(['status'=>'success']);
        }

        if ($action==='manager_health') {
            $provider=$this->input->post('provider_key',TRUE);
            $keyIndex=(int)$this->input->post('key_index');
            $status=$this->input->post('health_status',TRUE) ?: 'unknown';
            $http=(int)$this->input->post('http_status');
            $ms=(int)$this->input->post('response_ms');
            $msg=$this->input->post('message',TRUE);
            $model=$this->input->post('model_name',TRUE);
            $attemptsRaw=$this->input->post('attempts',TRUE);
            $attempts=json_decode((string)$attemptsRaw,true);
            if(!is_array($attempts))$attempts=[];
            $now=date('Y-m-d H:i:s');

            if ($status !== 'healthy') {
                if (in_array($http,[401,403],true)) $status='invalid_key';
                elseif ($http===429 || $status==='rate_limited') $status='rate_limited';
                elseif ($http===404 && $model) $status='model_error';
                elseif (!$status || $status==='unknown') $status='error';
            }

            $healthData=[
                'provider_key'=>$provider,
                'key_index'=>$keyIndex,
                'status'=>$status,
                'http_status'=>$http?:NULL,
                'response_ms'=>$ms?:NULL,
                'message'=>$msg,
                'checked_at'=>$now
            ];
            if($this->db->field_exists('model_name','ai_health'))$healthData['model_name']=$model ?: NULL;
            $this->db->replace('ai_health',$healthData);

            $keyStatus = $status;
            if ($status === 'healthy') $keyStatus = 'healthy';
            elseif ($status === 'rate_limited') $keyStatus = 'cooldown';
            elseif (in_array($status,['invalid_key','disabled'],true)) $keyStatus = 'disabled';
            else $keyStatus = 'error';

            $keyUpd = [
                'status'=>$keyStatus,
                'updated_at'=>$now,
                'last_used_at'=>$now,
                'last_error'=>($keyStatus==='healthy' ? NULL : $msg)
            ];
            if ($keyStatus==='healthy') {
                $keyUpd['last_success_at']=$now;
                $keyUpd['cooldown_until']=NULL;
            } elseif ($keyStatus==='cooldown') {
                $keyUpd['cooldown_until']=date('Y-m-d H:i:s',time()+60);
            }
            $this->db->where(['provider_key'=>$provider,'key_index'=>$keyIndex])->update('ai_keys',$keyUpd);

            if(count($attempts)){
                foreach($attempts as $a){
                    $aModel=isset($a['model'])?(string)$a['model']:'';
                    $aHttp=isset($a['http'])?(int)$a['http']:0;
                    $aStatus=isset($a['status'])?(string)$a['status']:'';
                    $aMsg=isset($a['message'])?(string)$a['message']:'';
                    $aOk=($aStatus==='healthy');

                    if($aModel){
                        if($aOk){
                            $this->db->where(['provider_key'=>$provider,'model_name'=>$aModel])
                                ->set('success_count','success_count+1',false)->update('ai_models');
                        } else {
                            $this->db->where(['provider_key'=>$provider,'model_name'=>$aModel])
                                ->set('fail_count','fail_count+1',false)->update('ai_models');
                        }
                    }

                    $level=$aOk?'info':'error';
                    $event=$aOk?'health_success':'health_model_failed';
                    $this->db->insert('ai_logs',[
                        'level'=>$level,
                        'event'=>$event,
                        'provider_key'=>$provider,
                        'key_index'=>$keyIndex,
                        'model_name'=>$aModel,
                        'message'=>$aMsg ?: ($aStatus.' HTTP '.$aHttp),
                        'meta'=>json_encode(['http_status'=>$aHttp,'health_status'=>$aStatus]),
                        'created_at'=>$now
                    ]);
                }
            } else {
                $this->db->insert('ai_logs',[
                    'level'=>$status==='healthy'?'info':'error',
                    'event'=>'health_check',
                    'provider_key'=>$provider,
                    'key_index'=>$keyIndex,
                    'model_name'=>$model,
                    'message'=>$msg,
                    'meta'=>json_encode(['http_status'=>$http,'response_ms'=>$ms,'health_status'=>$status]),
                    'created_at'=>$now
                ]);
            }

            $key=$this->db->get_where('ai_keys',['provider_key'=>$provider,'key_index'=>$keyIndex])->row();
            if($key){
                if($status==='healthy'){
                    $this->db->where('id',$key->id)->update('ai_keys',[
                        'status'=>'healthy',
                        'cooldown_until'=>NULL,
                        'last_error'=>NULL,
                        'updated_at'=>$now
                    ]);

                    if($model){
                        $this->db->where('provider_key',$provider)->where('model_name',$model)
                            ->update('ai_models',['priority'=>1,'updated_at'=>$now]);
                        $others=$this->db->order_by('priority','ASC')
                            ->get_where('ai_models',['provider_key'=>$provider,'enabled'=>1])->result();
                        $rank=2;
                        foreach($others as $m){
                            if($m->model_name===$model)continue;
                            $this->db->where('id',$m->id)->update('ai_models',[
                                'priority'=>$rank++,
                                'updated_at'=>$now
                            ]);
                        }
                    }
                } elseif($status==='invalid_key'){
                    $this->db->where('id',$key->id)->update('ai_keys',[
                        'status'=>'disabled',
                        'last_error'=>$msg ?: ('HTTP '.$http),
                        'cooldown_until'=>NULL,
                        'updated_at'=>$now
                    ]);
                } elseif($status==='rate_limited'){
                    $cooldown=(int)($this->db->get_where('ai_settings',['setting_key'=>'cooldown'])->row('setting_value') ?: 60);
                    $this->db->where('id',$key->id)->update('ai_keys',[
                        'status'=>'cooldown',
                        'cooldown_until'=>date('Y-m-d H:i:s',time()+max(1,$cooldown)),
                        'last_error'=>$msg ?: ('HTTP 429'),
                        'updated_at'=>$now
                    ]);
                } elseif($status==='model_error'){
                    $this->db->where('id',$key->id)->update('ai_keys',[
                        'status'=>'healthy',
                        'cooldown_until'=>NULL,
                        'last_error'=>$msg ?: ('Model tidak tersedia. HTTP '.$http),
                        'updated_at'=>$now
                    ]);
                } else {
                    $this->db->where('id',$key->id)->update('ai_keys',[
                        'status'=>'error',
                        'cooldown_until'=>NULL,
                        'last_error'=>$msg ?: ($http ? 'HTTP '.$http : 'Network/Unknown error'),
                        'updated_at'=>$now
                    ]);
                }
            }

            return $this->json([
                'status'=>'success',
                'health_status'=>$status,
                'http_status'=>$http,
                'message'=>$msg
            ]);
        }

        // ---- Proxy server-side ke provider AI (anti CORS) ----------------------------------
        if ($action==='ai_proxy') {
            $provider=(string)$this->input->post('provider_key',TRUE);
            $apiKey=trim((string)$this->input->post('api_key',FALSE));
            $model=(string)$this->input->post('model_name',FALSE);
            $prompt=(string)$this->input->post('prompt',FALSE);
            $account=trim((string)$this->input->post('account_id',FALSE));
            if (!isset($this->providers[$provider]) || $apiKey==='' || $prompt==='' || !$this->valid_model($model)) {
                return $this->json(['status'=>'error','http'=>0,'message'=>'Parameter proxy tidak valid.']);
            }
            // Lepas kunci session agar request lain (mis. tes paralel) tidak antre menunggu proxy.
            @session_write_close();
            @set_time_limit(120);
            $r=$this->call_provider($provider,$apiKey,$model,$prompt,$account);
            if ($r['ok']) return $this->json(['status'=>'success','http'=>$r['http'],'content'=>$r['content'],'ms'=>$r['ms']]);
            return $this->json(['status'=>'error','http'=>$r['http'],'message'=>$r['message'],'ms'=>$r['ms']]);
        }

        // ---- Model Manager: aktif/nonaktif, urutan, hasil tes -------------------------------
        if ($action==='manager_model_toggle') {
            $provider=(string)$this->input->post('provider_key',TRUE);
            $model=(string)$this->input->post('model_name',FALSE);
            $enabled=((int)$this->input->post('enabled')===1)?1:0;
            if (!isset($this->providers[$provider]) || !$this->valid_model($model)) return $this->json(['status'=>'error','message'=>'Provider/model tidak valid.']);
            $this->db->where(['provider_key'=>$provider,'model_name'=>$model])->update('ai_models',['enabled'=>$enabled,'updated_at'=>date('Y-m-d H:i:s')]);
            $this->ai_log('info','model_toggle',$provider,$model,$enabled?'Model diaktifkan.':'Model dinonaktifkan.');
            return $this->json(['status'=>'success','enabled'=>$enabled]);
        }

        if ($action==='manager_model_move') {
            $provider=(string)$this->input->post('provider_key',TRUE);
            $model=(string)$this->input->post('model_name',FALSE);
            $dir=$this->input->post('direction',TRUE)==='up'?'up':'down';
            if (!isset($this->providers[$provider]) || !$this->valid_model($model)) return $this->json(['status'=>'error','message'=>'Provider/model tidak valid.']);
            $rows=$this->db->order_by('priority','ASC')->order_by('id','ASC')->get_where('ai_models',['provider_key'=>$provider])->result();
            $idx=NULL;
            foreach ($rows as $i=>$r) { if ($r->model_name===$model) $idx=$i; }
            if ($idx===NULL) return $this->json(['status'=>'error','message'=>'Model tidak ditemukan.']);
            $to=($dir==='up')?$idx-1:$idx+1;
            if ($to>=0 && $to<count($rows)) { $tmp=$rows[$idx]; $rows[$idx]=$rows[$to]; $rows[$to]=$tmp; }
            $now=date('Y-m-d H:i:s');
            foreach ($rows as $i=>$r) $this->db->where('id',$r->id)->update('ai_models',['priority'=>$i+1,'updated_at'=>$now]);
            return $this->json(['status'=>'success']);
        }

        if ($action==='manager_model_result') {
            $provider=(string)$this->input->post('provider_key',TRUE);
            $model=(string)$this->input->post('model_name',FALSE);
            $status=(string)$this->input->post('status',TRUE);
            if (!in_array($status,['healthy','rate_limited','invalid_key','model_error','error'],true)) $status='error';
            $http=(int)$this->input->post('http_status');
            $ms=(int)$this->input->post('response_ms');
            $msg=$this->cut((string)$this->input->post('message',TRUE),500);
            if (!isset($this->providers[$provider]) || !$this->valid_model($model)) return $this->json(['status'=>'error','message'=>'Provider/model tidak valid.']);
            $this->db->where(['provider_key'=>$provider,'model_name'=>$model])
                ->set('last_status',$status)->set('last_http',$http?:NULL)->set('last_ms',$ms?:NULL)
                ->set('last_message',$msg)->set('last_checked_at',date('Y-m-d H:i:s'))
                ->set($status==='healthy'?'success_count':'fail_count',($status==='healthy'?'success_count':'fail_count').'+1',false)
                ->update('ai_models');
            $this->ai_log($status==='healthy'?'info':'error','model_test',$provider,$model,$msg?:$status,['http_status'=>$http,'response_ms'=>$ms,'status'=>$status]);
            return $this->json(['status'=>'success']);
        }

        if ($action==='manager_model_bulk') {
            $mode=(string)$this->input->post('mode',TRUE);
            $now=date('Y-m-d H:i:s');
            if ($mode==='enable_all') {
                $this->db->update('ai_models',['enabled'=>1,'updated_at'=>$now]);
            } elseif ($mode==='disable_failed') {
                $this->db->where_in('last_status',['error','model_error'])->update('ai_models',['enabled'=>0,'updated_at'=>$now]);
            } else {
                return $this->json(['status'=>'error','message'=>'Mode tidak dikenal.']);
            }
            $this->ai_log('info','model_bulk','',NULL,'Aksi massal model: '.$mode);
            return $this->json(['status'=>'success']);
        }

        if ($action==='manager_settings') {
            $settings=$this->input->post('settings',TRUE);$arr=json_decode($settings,true);if(!is_array($arr))$arr=[];foreach($arr as $k=>$v)$this->db->replace('ai_settings',['setting_key'=>$k,'setting_value'=>is_scalar($v)?(string)$v:json_encode($v)]);return $this->json(['status'=>'success']);
        }
        
        if ($action==='manager_reset_logs') {
            $this->db->truncate('ai_logs');return $this->json(['status'=>'success']);
        }

        $user=$this->ion_auth->user()->row();
        $setting=$this->db->get('setting')->row();
        $profile=$this->ion_auth->is_admin() ? (method_exists($this->dashboard,'getProfileAdmin')?$this->dashboard->getProfileAdmin($user->id):$user) : (method_exists($this->dashboard,'getProfileGuru')?$this->dashboard->getProfileGuru($user->id):$user);
        $tp=$this->db->get_where('master_tp',['active'=>1])->row();
        $smt=$this->db->get_where('master_smt',['active'=>1])->row();
        $savedKeys=$this->read_saved_keys();

        $data=['user'=>$user,'title'=>'Smart AI API Manager','sub_title'=>'AI Converter Soal + Smart Multi-Provider Engine','setting'=>$setting,'profile'=>$profile,'tp'=>$tp,'smt'=>$smt,'tp_active'=>$tp,'smt_active'=>$smt,'savedKeys'=>$savedKeys];
        
        $this->load->view('_templates/dashboard/_header.php',$data);
        $this->load->view('converter/index',$data);
        $this->load->view('_templates/dashboard/_footer.php');
    }
}