<?php
/* ________________________________________
    |                 GarudaCBT              |
    |    https://github.com/garudacbt/cbt    |
    |________________________________________|
*/

defined('BASEPATH') or exit('No direct script access allowed');

class Ion_auth_model extends CI_Model {

    const MAX_COOKIE_LIFETIME = 63072000;
    const MAX_PASSWORD_SIZE_BYTES = 4096;

    public $tables = array();
    public $activation_code;
    public $new_password;
    public $identity;
    
    public $_ion_where = array();
    public $_ion_select = array();
    public $_ion_like = array();
    public $_ion_limit = NULL;
    public $_ion_offset = NULL;
    public $_ion_order_by = NULL;
    public $_ion_order = NULL;
    
    protected $_ion_hooks;
    protected $response = NULL;
    protected $messages;
    protected $errors;
    protected $error_start_delimiter;
    protected $error_end_delimiter;
    public $_cache_user_in_group = array();
    protected $_cache_groups = array();

    public function __construct() {
        parent::__construct();
        
        $this->config->load('ion_auth', TRUE);
        $this->load->database();
        $this->load->library(array('form_validation', 'session'));
        $this->load->helper(array('cookie', 'date'));

        $this->tables = $this->config->item('tables', 'ion_auth');
        $this->error_start_delimiter = $this->config->item('error_start_delimiter', 'ion_auth');
        $this->error_end_delimiter = $this->config->item('error_end_delimiter', 'ion_auth');

        $this->_ion_hooks = new stdClass();
        $this->trigger_events('model_constructor');
    }

    public function hash_password($password, $identity = NULL) {
        if (empty($password) || strpos($password, "\x0") !== FALSE || strlen($password) > self::MAX_PASSWORD_SIZE_BYTES) {
            return FALSE;
        }

        $algo = $this->_get_hash_algo();
        $params = $this->_get_hash_parameters($identity);

        if ($algo !== FALSE && $params !== FALSE) {
            return password_hash($password, $algo, $params);
        }

        return FALSE;
    }

    public function verify_password($password, $hash_password_db, $identity = NULL) {
        if (empty($password) || empty($hash_password_db) || strpos($password, "\0") !== FALSE || strlen($password) > self::MAX_PASSWORD_SIZE_BYTES) {
            return FALSE;
        }

        if (strpos($hash_password_db, '$') === 0) {
            return password_verify($password, $hash_password_db);
        }

        return $this->_password_verify_sha1_legacy($identity, $password, $hash_password_db);
    }

    public function login($identity, $password, $remember = FALSE) {
        $this->trigger_events('pre_login');

        if (empty($identity) || empty($password)) {
            $this->set_error('login_unsuccessful');
            return FALSE;
        }

        $this->trigger_events('extra_where');
        $query = $this->db->select($this->config->item('identity', 'ion_auth') . ', email, id, password, active, last_login')
                          ->where($this->config->item('identity', 'ion_auth'), $identity)
                          ->limit(1)
                          ->get($this->tables['users']);

        if ($this->config->item('track_login_attempts', 'ion_auth')) {
            $ip_address = $this->input->ip_address();
            $login_attempts = $this->get_attempts_num($identity, $ip_address);
            if ($login_attempts >= $this->config->item('max_login_attempts', 'ion_auth')) {
                if ($this->config->item('lockout_time', 'ion_auth') > 0) {
                    if ($this->is_max_login_attempts_exceeded($identity, $ip_address)) {
                        $this->set_error('login_timeout');
                        return FALSE;
                    }
                }
            }
        }

        if ($query->num_rows() === 1) {
            $user = $query->row();
            
            if ($this->verify_password($password, $user->password, $identity)) {
                if ($user->active == 0) {
                    $this->trigger_events('post_login_unsuccessful');
                    $this->set_error('login_unsuccessful_not_active');
                    return FALSE;
                }

                $this->set_session($user);
                $this->update_last_login($user->id);
                $this->clear_login_attempts($identity);
                
                if ($remember && $this->config->item('remember_users', 'ion_auth')) {
                    $this->remember_user($user->id);
                }

                $this->trigger_events('post_login_successful');
                $this->set_message('login_successful');
                return TRUE;
            }
        }

        if ($this->config->item('track_login_attempts', 'ion_auth')) {
            $this->increase_login_attempts($identity);
        }

        $this->trigger_events('post_login_unsuccessful');
        $this->set_error('login_unsuccessful');
        return FALSE;
    }

    public function logout() {
        $this->trigger_events('logout');
        
        $identity = $this->config->item('identity', 'ion_auth');
        $this->session->unset_userdata(array($identity, 'id', 'user_id', 'email', 'last_check'));

        if ($this->config->item('remember_users', 'ion_auth')) {
            delete_cookie('remember_code');
        }

        $this->set_message('logout_successful');
        return TRUE;
    }

    public function user($id = NULL) {
        $this->trigger_events('user');
        $id = isset($id) ? $id : $this->session->userdata('user_id');

        $this->limit(1);
        $this->where($this->tables['users'] . '.id', $id);
        $this->users();

        return $this;
    }

    public function users($groups = NULL) {
        $this->trigger_events('users');

        if (isset($this->_ion_select) && !empty($this->_ion_select)) {
            foreach ($this->_ion_select as $select) {
                $this->db->select($select);
            }
            $this->_ion_select = array();
        } else {
            $this->db->select(array(
                $this->tables['users'] . '.*',
                $this->tables['users'] . '.id as id',
                $this->tables['users'] . '.id as user_id'
            ));
        }

        if (isset($groups) && !empty($groups)) {
            if (!is_array($groups)) {
                $groups = array($groups);
            }

            $this->db->join($this->tables['users_groups'], $this->tables['users_groups'] . '.user_id = ' . $this->tables['users'] . '.id');
            $this->db->join($this->tables['groups'], $this->tables['groups'] . '.id = ' . $this->tables['users_groups'] . '.group_id');
            $this->db->where_in($this->tables['groups'] . '.id', $groups);
        }

        $this->trigger_events('extra_where');

        if (isset($this->_ion_where) && !empty($this->_ion_where)) {
            foreach ($this->_ion_where as $where) {
                $this->db->where($where);
            }
            $this->_ion_where = array();
        }

        if (isset($this->_ion_like) && !empty($this->_ion_like)) {
            foreach ($this->_ion_like as $like) {
                $this->db->like($like);
            }
            $this->_ion_like = array();
        }

        if (isset($this->_ion_limit) && isset($this->_ion_offset)) {
            $this->db->limit($this->_ion_limit, $this->_ion_offset);
            $this->_ion_limit = NULL;
            $this->_ion_offset = NULL;
        } else if (isset($this->_ion_limit)) {
            $this->db->limit($this->_ion_limit);
            $this->_ion_limit = NULL;
        }

        if (isset($this->_ion_order_by) && isset($this->_ion_order)) {
            $this->db->order_by($this->_ion_order_by, $this->_ion_order);
            $this->_ion_order_by = NULL;
            $this->_ion_order = NULL;
        }

        $this->response = $this->db->get($this->tables['users']);
        return $this;
    }

    public function row() {
        $row = $this->response->row();
        return $row;
    }

    public function result() {
        $result = $this->response->result();
        return $result;
    }

    public function result_array() {
        $result = $this->response->result_array();
        return $result;
    }

    public function update($id, array $data) {
        $this->trigger_events('pre_update_user');

        $user = $this->user($id)->row();

        if (array_key_exists('password', $data)) {
            if (!empty($data['password'])) {
                $data['password'] = $this->hash_password($data['password'], $user->{$this->config->item('identity', 'ion_auth')});
                if ($data['password'] === FALSE) {
                    return FALSE;
                }
            } else {
                unset($data['password']);
            }
        }

        $this->trigger_events('extra_where');
        $this->db->update($this->tables['users'], $data, array('id' => $id));

        $this->trigger_events('post_update_user');
        $this->set_message('update_successful');
        return TRUE;
    }

    public function delete_user($id) {
        $this->trigger_events('pre_delete_user');

        $this->db->trans_begin();
        $this->db->delete($this->tables['users_groups'], array('user_id' => $id));
        $this->db->delete($this->tables['users'], array('id' => $id));

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->set_error('delete_unsuccessful');
            return FALSE;
        }

        $this->db->trans_commit();
        $this->trigger_events('post_delete_user');
        $this->set_message('delete_successful');
        return TRUE;
    }

    public function update_last_login($id) {
        $this->trigger_events('update_last_login');
        $this->load->helper('date');
        $this->db->update($this->tables['users'], array('last_login' => time()), array('id' => $id));
        return $this->db->affected_rows() === 1;
    }

    public function set_session($user) {
        $this->trigger_events('set_session');
        
        $session_data = array(
            'identity'             => $user->{$this->config->item('identity', 'ion_auth')},
            'username'             => $user->{$this->config->item('identity', 'ion_auth')},
            'email'                => $user->email,
            'user_id'              => $user->id,
            'id'                   => $user->id,
            'last_check'           => time()
        );

        $this->session->set_userdata($session_data);
        return TRUE;
    }

    public function remember_user($id) {
        $this->trigger_events('pre_remember_user');
        
        $user = $this->user($id)->row();
        $salt = md5(uniqid(mt_rand(), true));
        
        $this->db->update($this->tables['users'], array('remember_code' => $salt), array('id' => $id));

        if ($this->db->affected_rows() > 0) {
            $expire = $this->config->item('user_expire', 'ion_auth');
            if ($expire === 0 || $expire > self::MAX_COOKIE_LIFETIME) {
                $expire = self::MAX_COOKIE_LIFETIME;
            }

            set_cookie(array(
                'name'   => 'remember_code',
                'value'  => $salt,
                'expire' => $expire
            ));

            $this->trigger_events('post_remember_user_successful');
            return TRUE;
        }

        $this->trigger_events('post_remember_user_unsuccessful');
        return FALSE;
    }

    public function login_remembered_user() {
        $this->trigger_events('login_remembered_user');
        
        $remember_code = get_cookie('remember_code');
        if (empty($remember_code)) {
            return FALSE;
        }

        $this->trigger_events('extra_where');
        $query = $this->db->select($this->config->item('identity', 'ion_auth') . ', email, id, password, active, last_login')
                          ->where('remember_code', $remember_code)
                          ->limit(1)
                          ->get($this->tables['users']);

        if ($query->num_rows() === 1) {
            $user = $query->row();
            
            if ($user->active == 0) {
                $this->trigger_events('post_login_unsuccessful');
                return FALSE;
            }

            $this->set_session($user);
            $this->update_last_login($user->id);
            $this->trigger_events('post_login_successful');
            return TRUE;
        }

        return FALSE;
    }

    public function get_attempts_num($identity, $ip_address = NULL) {
        $this->db->where('login', $identity);
        if ($this->config->item('ip_address_login_attempts', 'ion_auth')) {
            $this->db->where('ip_address', $ip_address);
        }
        return $this->db->count_all_results($this->tables['login_attempts']);
    }

    public function is_max_login_attempts_exceeded($identity, $ip_address = NULL) {
        if ($this->config->item('track_login_attempts', 'ion_auth')) {
            $attempts = $this->get_attempts_num($identity, $ip_address);
            $max_attempts = $this->config->item('max_login_attempts', 'ion_auth');
            
            if ($attempts >= $max_attempts) {
                $this->db->select('time');
                $this->db->where('login', $identity);
                if ($this->config->item('ip_address_login_attempts', 'ion_auth')) {
                    $this->db->where('ip_address', $ip_address);
                }
                $this->db->order_by('id', 'desc');
                $this->db->limit(1);
                $query = $this->db->get($this->tables['login_attempts']);

                if ($query->num_rows() === 1) {
                    $last_attempt = $query->row()->time;
                    $lockout_time = $this->config->item('lockout_time', 'ion_auth');
                    if (time() - $last_attempt < $lockout_time) {
                        return TRUE;
                    }
                    $this->clear_login_attempts($identity);
                    return FALSE;
                }
            }
        }
        return FALSE;
    }

    public function increase_login_attempts($identity) {
        if ($this->config->item('track_login_attempts', 'ion_auth')) {
            $data = array(
                'ip_address' => $this->input->ip_address(),
                'login'      => $identity,
                'time'       => time()
            );
            return $this->db->insert($this->tables['login_attempts'], $data);
        }
        return FALSE;
    }

    public function clear_login_attempts($identity, $expire_period = 86400) {
        if ($this->config->item('track_login_attempts', 'ion_auth')) {
            $this->db->where('login', $identity);
            if ($this->config->item('ip_address_login_attempts', 'ion_auth')) {
                $this->db->where('ip_address', $this->input->ip_address());
            }
            $this->db->delete($this->tables['login_attempts']);
            
            $this->db->where('time <', time() - $expire_period);
            $this->db->delete($this->tables['login_attempts']);
            return TRUE;
        }
        return FALSE;
    }

    public function recheck_session() {
        $recheck = (NULL !== $this->config->item('recheck_timer', 'ion_auth')) ? $this->config->item('recheck_timer', 'ion_auth') : 0;

        if ($recheck !== 0) {
            if ($this->session->userdata('last_check') + $recheck < time()) {
                $query = $this->db->select('id')
                                  ->where(array(
                                      $this->config->item('identity', 'ion_auth') => $this->session->userdata('identity'),
                                      'id' => $this->session->userdata('user_id'),
                                      'active' => 1
                                  ))
                                  ->limit(1)
                                  ->get($this->tables['users']);

                if ($query->num_rows() === 1) {
                    $this->session->set_userdata('last_check', time());
                    return TRUE;
                }

                $this->session->unset_userdata(array('identity', 'user_id', 'email', 'last_check'));
                return FALSE;
            }
            return TRUE;
        }
        return (bool) $this->session->userdata('identity');
    }

    public function get_users_groups($id = FALSE) {
        $this->trigger_events('get_users_groups');
        $id = isset($id) ? $id : $this->session->userdata('user_id');

        return $this->db->select($this->tables['groups'] . '.id as id, ' . $this->tables['groups'] . '.name as name, ' . $this->tables['groups'] . '.description as description')
                        ->join($this->tables['users_groups'], $this->tables['users_groups'] . '.group_id = ' . $this->tables['groups'] . '.id')
                        ->where($this->tables['users_groups'] . '.user_id', $id)
                        ->get($this->tables['groups']);
    }

    public function add_to_group($group_ids, $user_id = false) {
        $this->trigger_events('add_to_group');

        if (empty($user_id)) {
            return false;
        }

        if (!is_array($group_ids)) {
            $group_ids = array($group_ids);
        }

        foreach ($group_ids as $group_id) {
            $data = array(
                'user_id'  => $user_id,
                'group_id' => $group_id
            );
            $this->db->insert($this->tables['users_groups'], $data);
        }

        return true;
    }

    public function remove_from_group($group_ids = false, $user_id = false) {
        $this->trigger_events('remove_from_group');

        if (empty($user_id)) {
            return FALSE;
        }

        if (!empty($group_ids)) {
            if (!is_array($group_ids)) {
                $group_ids = array($group_ids);
            }
            $this->db->where_in('group_id', $group_ids);
        }

        $this->db->delete($this->tables['users_groups'], array('user_id' => $user_id));
        return TRUE;
    }

    public function groups() {
        $this->trigger_events('groups');

        if (isset($this->_ion_select) && !empty($this->_ion_select)) {
            foreach ($this->_ion_select as $select) {
                $this->db->select($select);
            }
            $this->_ion_select = array();
        }

        if (isset($this->_ion_where) && !empty($this->_ion_where)) {
            foreach ($this->_ion_where as $where) {
                $this->db->where($where);
            }
            $this->_ion_where = array();
        }

        if (isset($this->_ion_like) && !empty($this->_ion_like)) {
            foreach ($this->_ion_like as $like) {
                $this->db->like($like);
            }
            $this->_ion_like = array();
        }

        if (isset($this->_ion_limit) && isset($this->_ion_offset)) {
            $this->db->limit($this->_ion_limit, $this->_ion_offset);
            $this->_ion_limit = NULL;
            $this->_ion_offset = NULL;
        } else if (isset($this->_ion_limit)) {
            $this->db->limit($this->_ion_limit);
            $this->_ion_limit = NULL;
        }

        if (isset($this->_ion_order_by) && isset($this->_ion_order)) {
            $this->db->order_by($this->_ion_order_by, $this->_ion_order);
            $this->_ion_order_by = NULL;
            $this->_ion_order = NULL;
        }

        $this->response = $this->db->get($this->tables['groups']);
        return $this;
    }

    public function group($id = NULL) {
        $this->trigger_events('group');
        if (isset($id)) {
            $this->db->where('id', $id);
        }
        $this->limit(1);
        return $this->groups();
    }

    public function create_group($group_name, $group_description = '', $additional_data = array()) {
        if (empty($group_name)) {
            $this->set_error('group_creation_unsuccessful');
            return FALSE;
        }

        $query = $this->db->get_where($this->tables['groups'], array('name' => $group_name), 1);
        if ($query->num_rows() > 0) {
            $this->set_error('group_already_exists');
            return FALSE;
        }

        $data = array('name' => $group_name, 'description' => $group_description);
        if (!empty($additional_data)) {
            $data = array_merge($this->_filter_data($this->tables['groups'], $additional_data), $data);
        }

        $this->db->insert($this->tables['groups'], $data);
        $group_id = $this->db->insert_id();

        if ($group_id) {
            $this->set_message('group_creation_successful');
            return $group_id;
        }

        $this->set_error('group_creation_unsuccessful');
        return FALSE;
    }

    public function update_group($group_id = false, $group_name = false, $additional_data = array()) {
        if (empty($group_id)) {
            return FALSE;
        }

        $data = array();
        if ($group_name !== FALSE) {
            $query = $this->db->get_where($this->tables['groups'], array('name' => $group_name), 1);
            if ($query->num_rows() > 0 && $query->row()->id !== $group_id) {
                $this->set_error('group_already_exists');
                return FALSE;
            }
            $data['name'] = $group_name;
        }

        if (!empty($additional_data)) {
            $data = array_merge($this->_filter_data($this->tables['groups'], $additional_data), $data);
        }

        $this->db->update($this->tables['groups'], $data, array('id' => $group_id));

        $this->set_message('group_update_successful');
        return TRUE;
    }

    public function delete_group($group_id = false) {
        if (empty($group_id)) {
            return FALSE;
        }

        if ($group_id == 1 || $group_id == 2 || $group_id == 3) {
            $this->set_error('group_delete_unsuccessful');
            return FALSE;
        }

        $this->db->trans_begin();
        $this->db->delete($this->tables['users_groups'], array('group_id' => $group_id));
        $this->db->delete($this->tables['groups'], array('id' => $group_id));

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->set_error('group_delete_unsuccessful');
            return FALSE;
        }

        $this->db->trans_commit();
        $this->set_message('group_delete_successful');
        return TRUE;
    }

    public function select($select) {
        $this->_ion_select[] = $select;
        return $this;
    }

    public function where($where, $value = NULL) {
        if (!is_array($where)) {
            $where = array($where => $value);
        }
        $this->_ion_where[] = $where;
        return $this;
    }

    public function like($like, $value = NULL, $position = 'both') {
        $this->_ion_like[] = array('field' => $like, 'match' => $value, 'side' => $position);
        return $this;
    }

    public function limit($limit) {
        $this->_ion_limit = $limit;
        return $this;
    }

    public function offset($offset) {
        $this->_ion_offset = $offset;
        return $this;
    }

    public function order_by($by, $order = 'desc') {
        $this->_ion_order_by = $by;
        $this->_ion_order = $order;
        return $this;
    }

    public function email_check($email = '') {
        $this->trigger_events('email_check');
        if (empty($email)) {
            return FALSE;
        }

        $this->trigger_events('extra_where');
        $query = $this->db->where('email', $email)
                          ->limit(1)
                          ->count_all_results($this->tables['users']);

        return $query > 0;
    }

    public function username_check($username = '') {
        $this->trigger_events('username_check');
        if (empty($username)) {
            return FALSE;
        }

        $this->trigger_events('extra_where');
        $query = $this->db->where('username', $username)
                          ->limit(1)
                          ->count_all_results($this->tables['users']);

        return $query > 0;
    }

    public function activate($id, $code = FALSE) {
        $this->trigger_events('pre_activate');

        if ($code !== FALSE) {
            $query = $this->db->select('id')
                              ->where('id', $id)
                              ->where('activation_code', $code)
                              ->limit(1)
                              ->get($this->tables['users']);

            if ($query->num_rows() !== 1) {
                $this->set_error('activate_unsuccessful');
                return FALSE;
            }
        }

        $data = array(
            'activation_code' => NULL,
            'active'          => 1
        );

        $this->trigger_events('extra_where');
        $this->db->update($this->tables['users'], $data, array('id' => $id));

        if ($this->db->affected_rows() === 1) {
            $this->set_message('activate_successful');
            $this->trigger_events('post_activate');
            return TRUE;
        }

        $this->set_error('activate_unsuccessful');
        return FALSE;
    }

    public function deactivate($id = NULL) {
        $this->trigger_events('deactivate');

        if (!isset($id)) {
            return FALSE;
        }

        $data = array('id' => $id);
        $this->trigger_events('extra_where');
        
        $this->db->update($this->tables['users'], array('active' => 0), $data);

        if ($this->db->affected_rows() === 1) {
            $this->set_message('deactivate_successful');
            return TRUE;
        }

        $this->set_error('deactivate_unsuccessful');
        return FALSE;
    }

    public function forgotten_password($identity) {
        if (empty($identity)) {
            $this->set_error('forgot_password_unsuccessful');
            return FALSE;
        }

        $this->trigger_events('pre_forgotten_password');

        $this->trigger_events('extra_where');
        $query = $this->db->select('id, email, forgotten_password_code')
                          ->where($this->config->item('identity', 'ion_auth'), $identity)
                          ->limit(1)
                          ->get($this->tables['users']);

        if ($query->num_rows() === 1) {
            $user = $query->row();
            $key  = md5(uniqid(mt_rand(), true));

            $update_data = array(
                'forgotten_password_code' => $key,
                'forgotten_password_time' => time()
            );

            $this->db->update($this->tables['users'], $update_data, array('id' => $user->id));

            if ($this->db->affected_rows() === 1) {
                $this->trigger_events('post_forgotten_password_successful');
                return $key;
            }
        }

        $this->trigger_events('post_forgotten_password_unsuccessful');
        $this->set_error('forgot_password_unsuccessful');
        return FALSE;
    }

    public function reset_password($identity, $new_password) {
        $this->trigger_events('pre_change_password');

        if (empty($identity) || empty($new_password)) {
            return FALSE;
        }

        $this->trigger_events('extra_where');
        $query = $this->db->select('id, password, salt')
                          ->where($this->config->item('identity', 'ion_auth'), $identity)
                          ->limit(1)
                          ->get($this->tables['users']);

        if ($query->num_rows() !== 1) {
            $this->trigger_events('post_change_password_unsuccessful');
            return FALSE;
        }

        $user = $query->row();
        $hashed_password = $this->hash_password($new_password, $identity);

        if ($hashed_password === FALSE) {
            return FALSE;
        }

        $data = array(
            'password'                => $hashed_password,
            'forgotten_password_code' => NULL,
            'forgotten_password_time' => NULL
        );

        $this->db->update($this->tables['users'], $data, array('id' => $user->id));

        if ($this->db->affected_rows() === 1) {
            $this->set_message('password_change_successful');
            $this->trigger_events('post_change_password_successful');
            return TRUE;
        }

        $this->set_error('password_change_unsuccessful');
        return FALSE;
    }

    public function clear_forgotten_password_code($code) {
        if (empty($code)) {
            return FALSE;
        }

        $this->db->update($this->tables['users'], array(
            'forgotten_password_code' => NULL,
            'forgotten_password_time' => NULL
        ), array('forgotten_password_code' => $code));

        return TRUE;
    }

    public function clear_remember_code($identity) {
        if (empty($identity)) {
            return FALSE;
        }

        $this->db->update($this->tables['users'], array('remember_code' => NULL), array(
            $this->config->item('identity', 'ion_auth') => $identity
        ));

        return TRUE;
    }

    public function register($identity, $password, $email, $additional_data = array(), $groups = array()) {
        $this->trigger_events('pre_register');

        if (empty($identity) || empty($password)) {
            $this->set_error('account_creation_unsuccessful');
            return FALSE;
        }

        if ($this->identity_check($identity)) {
            $this->set_error('account_creation_duplicate_identity');
            return FALSE;
        }

        if (!empty($email) && $this->config->item('email_check', 'ion_auth') && $this->email_check($email)) {
            $this->set_error('account_creation_duplicate_email');
            return FALSE;
        }

        $hashed_password = $this->hash_password($password, $identity);
        if ($hashed_password === FALSE) {
            $this->set_error('account_creation_unsuccessful');
            return FALSE;
        }

        $data = array(
            $this->config->item('identity', 'ion_auth') => $identity,
            'password' => $hashed_password,
            'email'    => $email,
            'ip_address' => $this->input->ip_address(),
            'created_on' => time(),
            'active'     => ($this->config->item('email_activation', 'ion_auth') ? 0 : 1)
        );

        if ($this->config->item('email_activation', 'ion_auth')) {
            $data['activation_code'] = md5(uniqid(mt_rand(), true));
        }

        $user_data = array_merge($this->_filter_data($this->tables['users'], $additional_data), $data);

        $this->db->insert($this->tables['users'], $user_data);
        $id = $this->db->insert_id();

        if (!empty($groups)) {
            foreach ($groups as $group) {
                $this->add_to_group($group, $id);
            }
        }

        if ($this->config->item('default_group', 'ion_auth') && empty($groups)) {
            $this->add_to_group($this->config->item('default_group', 'ion_auth'), $id);
        }

        $this->trigger_events('post_register');
        return $id ? $id : FALSE;
    }

    public function identity_check($identity = '') {
        $this->trigger_events('identity_check');
        if (empty($identity)) {
            return FALSE;
        }

        $query = $this->db->where($this->config->item('identity', 'ion_auth'), $identity)
                          ->limit(1)
                          ->count_all_results($this->tables['users']);

        return $query > 0;
    }

    public function trigger_events($events) {
        if (is_array($events)) {
            foreach ($events as $event) {
                $this->trigger_events($event);
            }
            return;
        }

        if (!empty($this->_ion_hooks->$events) && is_array($this->_ion_hooks->$events)) {
            foreach ($this->_ion_hooks->$events as $hook) {
                if (call_user_func_array(array($hook->class, $hook->method), $hook->arguments) === FALSE) {
                    return FALSE;
                }
            }
        }
    }

    public function set_message($message) {
        $this->messages[] = $message;
        return $message;
    }

    public function set_error($error) {
        $this->errors[] = $error;
        return $error;
    }

    public function messages() {
        $_output = '';
        foreach ($this->messages as $message) {
            $messageLang = $this->lang->line($message) ? $this->lang->line($message) : $message;
            $_output .= $this->error_start_delimiter . $messageLang . $this->error_end_delimiter;
        }
        return $_output;
    }

    public function errors() {
        $_output = '';
        foreach ($this->errors as $error) {
            $errorLang = $this->lang->line($error) ? $this->lang->line($error) : $error;
            $_output .= $this->error_start_delimiter . $errorLang . $this->error_end_delimiter;
        }
        return $_output;
    }

    protected function _filter_data($table, $data) {
        $filtered_data = array();
        $columns = $this->db->list_fields($table);

        if (is_array($data)) {
            foreach ($data as $key => $val) {
                if (in_array($key, $columns)) {
                    $filtered_data[$key] = $val;
                }
            }
        }
        return $filtered_data;
    }

    protected function _get_hash_algo() {
        $hash_method = $this->config->item('hash_method', 'ion_auth');

        if ($hash_method === 'bcrypt') {
            return PASSWORD_BCRYPT;
        }

        if ($hash_method === 'argon2') {
            return defined('PASSWORD_ARGON2I') ? PASSWORD_ARGON2I : PASSWORD_DEFAULT;
        }

        return PASSWORD_DEFAULT;
    }

    protected function _get_hash_parameters($identity = NULL) {
        $hash_method = $this->config->item('hash_method', 'ion_auth');

        if ($hash_method === 'bcrypt') {
            return array('cost' => $this->config->item('bcrypt_default_cost', 'ion_auth'));
        }

        if ($hash_method === 'argon2') {
            return $this->config->item('argon2_default_params', 'ion_auth');
        }

        return array();
    }

    protected function _password_verify_sha1_legacy($identity, $password, $hashed_password_db) {
        $this->trigger_events('pre_sha1_password_migration');
        
        $query = $this->db->select('salt, id')
                          ->where($this->config->item('identity', 'ion_auth'), $identity)
                          ->limit(1)
                          ->get($this->tables['users']);

        if ($query->num_rows() !== 1) {
            return FALSE;
        }

        $salt_db = $query->row();
        
        $salt_length = $this->config->item('salt_length', 'ion_auth');
        if ($salt_length !== FALSE) {
            $salt = substr($hashed_password_db, 0, $salt_length);
            $hashed_password = $salt . substr(sha1($salt . $password), 0, -$salt_length);
        } else {
            $hashed_password = sha1($password . $salt_db->salt);
        }

        if ($hashed_password === $hashed_password_db) {
            $new_hashed_password = $this->hash_password($password, $identity);
            
            $this->db->update($this->tables['users'], array('password' => $new_hashed_password, 'salt' => NULL), array('id' => $salt_db->id));
            
            $this->trigger_events(array('post_sha1_password_migration', 'post_sha1_password_migration_successful'));
            return TRUE;
        }

        $this->trigger_events(array('post_sha1_password_migration', 'post_sha1_password_migration_unsuccessful'));
        return FALSE;
    }
	public function in_group($check_group, $id = false)
    {
        $this->trigger_events('in_group');

        $id || $id = $this->session->userdata('user_id');

        if (!is_array($check_group))
        {
            $check_group = array($check_group);
        }

        if (isset($this->_cache_user_in_group[$id]))
        {
            foreach ($check_group as $group)
            {
                if (in_array($group, $this->_cache_user_in_group[$id]))
                {
                    return TRUE;
                }
            }
            return FALSE;
        }

        $users_groups = $this->ion_auth_model->get_users_groups($id)->result();
        $groups_array = array();

        foreach ($users_groups as $group)
        {
            $groups_array[$group->id] = $group->name;
        }

        $this->_cache_user_in_group[$id] = $groups_array;

        foreach ($check_group as $group)
        {
            if (in_array($group, $groups_array))
            {
                return TRUE;
            }
        }

        return FALSE;
    }
}