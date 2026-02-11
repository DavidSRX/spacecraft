<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Users_model');
        $this->load->library('email');
        $this->load->helper(array('url', 'form'));
        $this->load->library('session');
        $this->load->library('form_validation');

    }

     public function login() {

        // Configurar reglas de validación
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email', array(
            'required' => 'El email es obligatorio',
            'valid_email' => 'Debes ingresar un email válido'
        ));
        
        $this->form_validation->set_rules('password', 'Contraseña', 'required', array(
            'required' => 'La contraseña es obligatoria'
        ));
        
        // Ejecutar validación
        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $error_message = implode('. ', $errors);
            
            echo json_encode(array(
                'success' => FALSE,
                'message' => $error_message
            ));
            return;
        }
        
        $email = strtolower($this->input->post('email', TRUE));
        $password = $this->input->post('password', TRUE);
        
        // Buscar usuario por email
        $user = $this->Users_model->get_by_email($email);
        
        if (!$user) {
            // Registrar intento fallido
            log_message('info', 'Intento de login fallido - Usuario no encontrado: ' . $email);
            
            echo json_encode(array(
                'success' => FALSE,
                'message' => 'Usuario no registrado'
            ));
            return;
        }
        
        // Verificar si el usuario está activo
        if ($user->is_active != 1) {
            echo json_encode(array(
                'success' => FALSE,
                'message' => 'Tu cuenta está inactiva. Contacta al soporte.'
            ));
            return;
        }
        
        // Verificar contraseña
        if (!password_verify($password, $user->password)) {
            // Registrar intento fallido
            log_message('info', 'Intento de login fallido - Contraseña incorrecta: ' . $email);
            
            echo json_encode(array(
                'success' => FALSE,
                'message' => 'Credenciales incorrectas'
            ));
            return;
        }
        
        // Login exitoso - Crear sesión
        $session_data = array(
            'user_id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
            'logged_in' => TRUE,
            'login_time' => time()
        );
        
        $this->session->set_userdata($session_data);
        

        // Registrar login exitoso
        log_message('info', 'Login exitoso: ' . $email . ' (ID: ' . $user->id . ')');
        
        // Respuesta exitosa
        echo json_encode(array(
            'success' => TRUE,
            'message' => 'Login exitoso',
            'user' => array(
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email
            ),
            'redirect_url' => site_url('dashboard')
        ));
        
    }
    
    public function send_magic_link() {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array(
                'success' => FALSE,
                'message' => validation_errors()
            ));
            return;
        }
        
        $email = $this->input->post('email', TRUE);
        
        // Buscar o crear usuario
        $user = $this->Users_model->get_by_email($email);
        
        if (!$user) {
            // Crear nuevo usuario
            $user_id = $this->Users_model->create(array(
                'email' => $email,
                'name' => explode('@', $email)[0] // Nombre temporal del email
            ));
            $user = $this->Users_model->get_by_email($email);
        }
        
        // Generar token
        $token = $this->Users_model->create_auth_token($user->id, 15); // 15 minutos
        
        // Enviar email
        $magic_link = site_url('users/verify/' . $token);
        
        if ($this->send_login_email($email, $user->name, $magic_link)) {
            echo json_encode(array(
                'success' => TRUE,
                'message' => 'Revisa tu email. Te hemos enviado un enlace mágico para iniciar sesión.'
            ));
        } else {
            echo json_encode(array(
                'success' => FALSE,
                'message' => 'Error al enviar el email. Por favor intenta nuevamente.'
            ));
        }
    }

    public function register() {
        // Configurar reglas de validación
        $this->form_validation->set_rules('name', 'Nombre', 'required|trim|min_length[2]|max_length[100]', array(
            'required' => 'El nombre es obligatorio',
            'min_length' => 'El nombre debe tener al menos 2 caracteres',
            'max_length' => 'El nombre no puede exceder 100 caracteres'
        ));
        
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[users.email]', array(
            'required' => 'El email es obligatorio',
            'valid_email' => 'Debes ingresar un email válido',
            'is_unique' => 'Este email ya está registrado'
        ));
        
        $this->form_validation->set_rules('password', 'Contraseña', 'required|min_length[8]', array(
            'required' => 'La contraseña es obligatoria',
            'min_length' => 'La contraseña debe tener al menos 8 caracteres'
        ));
        
        $this->form_validation->set_rules('confirm_password', 'Confirmar Contraseña', 'required|matches[password]', array(
            'required' => 'Debes confirmar tu contraseña',
            'matches' => 'Las contraseñas no coinciden'
        ));
        
        // Ejecutar validación
        if ($this->form_validation->run() == FALSE) {
            // Si la validación falla, devolver errores
            $errors = $this->form_validation->error_array();
            $error_message = implode('. ', $errors);
            
            echo json_encode(array(
                'success' => FALSE,
                'message' => $error_message,
                'errors' => $errors
            ));
            return;
        }
        
        // Obtener datos del formulario (sanitizados)
        $name = $this->input->post('name', TRUE);
        $email = $this->input->post('email', TRUE);
        $password = $this->input->post('password', TRUE);
        
        // Preparar datos para insertar
        $user_data = array(
            'name' => $name,
            'email' => strtolower($email), // Guardar email en minúsculas
            'password' => password_hash($password, PASSWORD_BCRYPT, array('cost' => 12)),
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s')
        );
        
        // Iniciar transacción
        $this->db->trans_start();
        
        try {
            // Crear usuario
            $user_id = $this->Users_model->create($user_data);
            
            if (!$user_id) {
                throw new Exception('Error al crear el usuario en la base de datos');
            }
            
            // Completar transacción
            $this->db->trans_complete();
            
            // Verificar si la transacción fue exitosa
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Error en la transacción de base de datos');
            }
            
            // Registrar en logs
            log_message('info', 'Nuevo usuario registrado: ' . $email . ' (ID: ' . $user_id . ')');
                        
            // Respuesta exitosa
            echo json_encode(array(
                'success' => TRUE,
                'message' => '🚀 Cuenta creada exitosamente. Ya puedes iniciar sesión.',
                'user_id' => $user_id,
                'redirect' => site_url('auth/login')
            ));
            
        } catch (Exception $e) {
            // Si algo falla, hacer rollback
            $this->db->trans_rollback();
            
            // Registrar error en logs
            log_message('error', 'Error en registro de usuario: ' . $e->getMessage());
            
            // Respuesta de error
            echo json_encode(array(
                'success' => FALSE,
                'message' => 'Error al crear la cuenta. Por favor intenta nuevamente.'
            ));
        }
    }
    
    /**
     * Verificar token y autenticar usuario
     */
    public function verify($token = NULL) {
        if (!$token) {
            $this->session->set_flashdata('error', 'Token inválido');
            redirect('login');
        }
        
        // Validar token
        $user = $this->Users_model->validate_token($token);
        
        if (!$user) {
            $this->session->set_flashdata('error', 'El enlace es inválido o ha expirado');
            redirect('login');
        }
        
        // Crear sesión
        $session_data = array(
            'user_id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
            'logged_in' => TRUE
        );
        
        $this->session->set_userdata($session_data);
        
        $this->session->set_flashdata('success', '¡Bienvenido de vuelta, ' . $user->name . '!');
        redirect('dashboard');
    }
    
    /**
     * Cerrar sesión
     */
    public function logout() {
        // Invalidar tokens del usuario
        if ($this->session->userdata('user_id')) {
            $this->Users_model->invalidate_user_tokens($this->session->userdata('user_id'));
        }
        
        // Destruir sesión
        $this->session->sess_destroy();
        
        $this->session->set_flashdata('success', 'Has cerrado sesión correctamente');
        redirect('login');
    }
    
    /**
     * Enviar email con enlace mágico
     */
    private function send_login_email($to, $name, $magic_link) {
        $this->email->from('solanorojasdavid@gmail.com', 'Tu App');
        $this->email->to($to);
        $this->email->subject('🔐 Tu enlace de acceso');
        
        $message = $this->load->view('magic_link', array(
            'name' => $name,
            'magic_link' => $magic_link,
            'expiry_minutes' => 15
        ), TRUE);
        
        $this->email->message($message);
        
        return $this->email->send();
    }


}