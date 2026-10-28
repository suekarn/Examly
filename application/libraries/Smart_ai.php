<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Smart_ai {

    protected $CI;
    protected $configData = [];

    public function __construct() {
        $this->CI =& get_instance();
        $this->loadConfig();
    }

    public function loadConfig() {
        if ($this->CI->db->table_exists('ai_config')) {
            $dbConfig = $this->CI->db->get('ai_config')->result_array();
            foreach ($dbConfig as $row) {
                $this->configData[$row['key_name']] = $row['key_value'];
            }
        }
    }

    private function getKeysArray($providerKeyName) {
        $raw = $this->configData[$providerKeyName] ?? '';
        if (empty($raw)) return [];
        $keys = array_map('trim', explode(',', $raw));
        return array_values(array_filter($keys));
    }

    // Pengecekan Cache Massal
    public function checkCacheBulk($daftarSoal) {
        $cachedResults = [];
        $uncachedItems = [];

        foreach ($daftarSoal as $index => $textSoal) {
            $hash = md5(trim($textSoal));
            $cached = $this->CI->db->get_where('ai_cache', ['soal_hash' => $hash])->row();

            if ($cached) {
                $cachedResults[$index] = [
                    'source' => 'cache',
                    'data'   => json_decode($cached->hasil_json, true)
                ];
            } else {
                $uncachedItems[$index] = $textSoal;
            }
        }

        return [
            'cached'   => $cachedResults,
            'uncached' => $uncachedItems
        ];
    }

    // Eksekusi Batch Soal via AI Provider Manager
    public function processBatchSoal($batchSoal) {
        $promptText = "Konversi daftar soal berikut menjadi array JSON berurutan sesuai ID:\n\n";
        foreach ($batchSoal as $id => $soal) {
            $promptText .= "[ID: {$id}]\n" . $soal . "\n\n";
        }

        $chainRaw = $this->configData['chain'] ?? '["gemini","groq","cerebras","github","openrouter","mistral","cloudflare"]';
        $chain = json_decode($chainRaw, true) ?: ["gemini","groq","cerebras","github","openrouter","mistral","cloudflare"];

        $providers = [
            'gemini'     => ['keys' => $this->getKeysArray('gemini'), 'models' => ['gemini-2.5-flash', 'gemini-1.5-flash']],
            'groq'       => ['keys' => $this->getKeysArray('groq'), 'models' => ['llama-3.3-70b-versatile', 'llama-3.1-8b-instant']],
            'cerebras'   => ['keys' => $this->getKeysArray('cerebras'), 'models' => ['llama-3.3-70b', 'llama3.1-8b']],
            'github'     => ['keys' => $this->getKeysArray('github'), 'models' => ['gpt-4o-mini', 'Phi-3.5-mini-instruct']],
            'openrouter' => ['keys' => $this->getKeysArray('openrouter'), 'models' => ['deepseek/deepseek-chat', 'meta-llama/llama-3.3-70b-instruct']],
            'mistral'    => ['keys' => $this->getKeysArray('mistral'), 'models' => ['mistral-small-latest', 'mistral-large-latest']],
            'cloudflare' => ['keys' => $this->getKeysArray('cloudflare_key'), 'account' => $this->configData['cloudflare_account'] ?? '', 'models' => ['@cf/meta/llama-3.3-70b-instruct-fp8-fast', '@cf/meta/llama-3.1-8b-instruct']]
        ];

        foreach ($chain as $prov) {
            if ($this->isCoolingDown($prov)) continue;
            if (!isset($providers[$prov])) continue;

            $pData = $providers[$prov];
            if (empty($pData['keys'])) continue;

            $keys = $pData['keys'];
            shuffle($keys); // Load balancing key

            foreach ($keys as $key) {
                foreach ($pData['models'] as $model) {
                    try {
                        $rawRes = null;
                        if ($prov === 'gemini') $rawRes = $this->callGemini($key, $model, $promptText);
                        elseif ($prov === 'groq') $rawRes = $this->callGroq($key, $model, $promptText);
                        elseif ($prov === 'cerebras') $rawRes = $this->callCerebras($key, $model, $promptText);
                        elseif ($prov === 'github') $rawRes = $this->callGithub($key, $model, $promptText);
                        elseif ($prov === 'openrouter') $rawRes = $this->callOpenRouter($key, $model, $promptText);
                        elseif ($prov === 'mistral') $rawRes = $this->callMistral($key, $model, $promptText);
                        elseif ($prov === 'cloudflare' && !empty($pData['account'])) {
                            $rawRes = $this->callCloudflare($pData['account'], $key, $model, $promptText);
                        }

                        if (!empty($rawRes)) {
                            $parsedJson = json_decode($rawRes, true);

                            // Simpan Ke Cache DB
                            foreach ($batchSoal as $idx => $textSoal) {
                                $hash = md5(trim($textSoal));
                                $itemData = $parsedJson[$idx] ?? $parsedJson; // Fallback structure

                                $this->CI->db->replace('ai_cache', [
                                    'soal_hash'  => $hash,
                                    'soal_raw'   => $textSoal,
                                    'hasil_json' => json_encode($itemData)
                                ]);
                            }

                            return [
                                'success'  => true,
                                'provider' => $prov,
                                'model'    => $model,
                                'data'     => $parsedJson
                            ];
                        }
                    } catch (Exception $e) {
                        $this->setCooldown($prov, 60);
                        log_message('error', "SmartAI Error/Limit [{$prov} - {$model}]: " . $e->getMessage());
                    }
                }
            }
        }

        return ['success' => false, 'message' => 'Seluruh API Key & Provider AI dalam kondisi cooldown/limit.'];
    }

    private function isCoolingDown($provider) {
        $now = date('Y-m-d H:i:s');
        $query = $this->CI->db->query("SELECT id FROM ai_cooldown WHERE provider_key = ? AND until_time > ?", [$provider, $now]);
        return $query->num_rows() > 0;
    }

    private function setCooldown($provider, $seconds = 60) {
        $until = date('Y-m-d H:i:s', time() + $seconds);
        $this->CI->db->query("INSERT INTO ai_cooldown (provider_key, until_time) VALUES (?, ?) ON DUPLICATE KEY UPDATE until_time = ?", [$provider, $until, $until]);
    }

    private function httpPost($url, $headers, $body) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_POSTFIELDS     => json_encode($body),
            CURLOPT_TIMEOUT        => 45
        ]);
        $res = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode >= 400 || $res === false) {
            throw new Exception("HTTP Error {$httpCode}");
        }
        return json_decode($res, true);
    }

    private function callGemini($key, $model, $prompt) {
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$key}";
        $body = [
            'contents' => [['parts' => [['text' => $prompt]]]],
            'generationConfig' => ['temperature' => 0.1, 'responseMimeType' => 'application/json']
        ];
        $res = $this->httpPost($url, ['Content-Type: application/json'], $body);
        return $res['candidates'][0]['content']['parts'][0]['text'] ?? null;
    }

    private function callGroq($key, $model, $prompt) {
        $url = "https://api.groq.com/openai/v1/chat/completions";
        $body = [
            'model' => $model,
            'messages' => [['role' => 'user', 'content' => $prompt]],
            'temperature' => 0.1,
            'response_format' => ['type' => 'json_object']
        ];
        $res = $this->httpPost($url, ["Content-Type: application/json", "Authorization: Bearer {$key}"], $body);
        return $res['choices'][0]['message']['content'] ?? null;
    }

    private function callCerebras($key, $model, $prompt) {
        $url = "https://api.cerebras.ai/v1/chat/completions";
        $body = ['model' => $model, 'messages' => [['role' => 'user', 'content' => $prompt]], 'temperature' => 0.1];
        $res = $this->httpPost($url, ["Content-Type: application/json", "Authorization: Bearer {$key}"], $body);
        return $res['choices'][0]['message']['content'] ?? null;
    }

    private function callGithub($key, $model, $prompt) {
        $url = "https://models.inference.ai.azure.com/chat/completions";
        $body = ['model' => $model, 'messages' => [['role' => 'user', 'content' => $prompt]], 'temperature' => 0.1];
        $res = $this->httpPost($url, ["Content-Type: application/json", "Authorization: Bearer {$key}"], $body);
        return $res['choices'][0]['message']['content'] ?? null;
    }

    private function callOpenRouter($key, $model, $prompt) {
        $url = "https://openrouter.ai/api/v1/chat/completions";
        $body = ['model' => $model, 'messages' => [['role' => 'user', 'content' => $prompt]], 'temperature' => 0.1];
        $res = $this->httpPost($url, ["Content-Type: application/json", "Authorization: Bearer {$key}"], $body);
        return $res['choices'][0]['message']['content'] ?? null;
    }

    private function callMistral($key, $model, $prompt) {
        $url = "https://api.mistral.ai/v1/chat/completions";
        $body = ['model' => $model, 'messages' => [['role' => 'user', 'content' => $prompt]], 'temperature' => 0.1, 'response_format' => ['type' => 'json_object']];
        $res = $this->httpPost($url, ["Content-Type: application/json", "Authorization: Bearer {$key}"], $body);
        return $res['choices'][0]['message']['content'] ?? null;
    }

    private function callCloudflare($account, $key, $model, $prompt) {
        $url = "https://api.cloudflare.com/client/v4/accounts/{$account}/ai/run/{$model}";
        $body = ['messages' => [['role' => 'system', 'content' => 'Output strict JSON only.'], ['role' => 'user', 'content' => $prompt]]];
        $res = $this->httpPost($url, ["Content-Type: application/json", "Authorization: Bearer {$key}"], $body);
        return $res['result']['response'] ?? null;
    }
}