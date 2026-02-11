<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_model extends CI_Model {
    
    private $table = 'users';
    private $token_table = 'auth_tokens';
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    /**
     * Buscar usuario por email
     */
    public function get_by_email($email) {
        return $this->db->where('email', $email)
                        ->where('is_active', 1)
                        ->get($this->table)
                        ->row();
    }
    
    /**
     * Crear nuevo usuario
     */
    public function create($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }
    
    /**
     * Actualizar usuario
     */
    public function update($user_id, $data) {
        return $this->db->where('id', $user_id)
                        ->update($this->table, $data);
    }
    
    /**
     * Crear token de autenticación
     */
    public function create_auth_token($user_id, $expiry_minutes = 15) {
        // Generar token único
        $token = bin2hex(random_bytes(32));
        
        // Calcular expiración
        $expires_at = date('Y-m-d H:i:s', strtotime("+{$expiry_minutes} minutes"));
        
        $data = array(
            'user_id' => $user_id,
            'token' => $token,
            'expires_at' => $expires_at,
            'ip_address' => $this->input->ip_address(),
            'user_agent' => $this->input->user_agent()
        );
        
        $this->db->insert($this->token_table, $data);
        
        return $token;
    }
    
    /**
     * Validar token de autenticación
     */
    public function validate_token($token) {
        $result = $this->db->select('auth_tokens.*, users.*')
                           ->from($this->token_table)
                           ->join('users', 'users.id = auth_tokens.user_id')
                           ->where('auth_tokens.token', $token)
                           ->where('auth_tokens.is_used', 0)
                           ->where('auth_tokens.expires_at >', date('Y-m-d H:i:s'))
                           ->get()
                           ->row();
        
        if ($result) {
            // Marcar token como usado
            $this->db->where('token', $token)
                     ->update($this->token_table, array('is_used' => 1));
            
            return $result;
        }
        
        return FALSE;
    }
    
    /**
     * Limpiar tokens expirados
     */
    public function clean_expired_tokens() {
        return $this->db->where('expires_at <', date('Y-m-d H:i:s'))
                        ->or_where('is_used', 1)
                        ->delete($this->token_table);
    }
    
    /**
     * Invalidar todos los tokens de un usuario
     */
    public function invalidate_user_tokens($user_id) {
        return $this->db->where('user_id', $user_id)
                        ->update($this->token_table, array('is_used' => 1));
    }
}