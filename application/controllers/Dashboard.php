<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Spacecraft_model');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->helper(array('url', 'form'));
        $this->load->helper('flag');


        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
    }

    public function index()
    {
        $this->output->set_content_type('text/html', 'UTF-8');
        $data['spacecrafts'] = $this->Spacecraft_model
            ->get_by_user($this->session->userdata('user_id'));

        $this->load->view('dashboard', $data);
    }

    public function search()
    {
        $filters = [
            'model' => $this->input->post('model'),   // modelo/estado
            'name'   => $this->input->post('name'),     // nombre explícito
            'build' => $this->input->post('build'),
            'nationality' => $this->input->post('nationality'),
        ];


        $ships = $this->Spacecraft_model
            ->get_by_user($this->session->userdata('user_id'), $filters);

        echo json_encode($ships);
    }
    public function create()
    {
        $this->_form_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('spacecraft_form_create');
        } else {
            $image = $this->_upload_image();

            $this->Spacecraft_model->insert([
                'user_id' => $this->session->userdata('user_id'),
                'name'    => $this->input->post('name'),
                'model'   => $this->input->post('model'),
                'status'  => $this->input->post('status'),
                'nationality'  => $this->input->post('nationality'),
                'build_year'  => $this->input->post('build_year'),
                'price'  => $this->input->post('price'),
                'image'   => $image
            ]);

            redirect('dashboard');
        }
    }

    public function edit_dashboard($id)
    {
        $data['ship'] = $this->Spacecraft_model->get_one($id, $this->session->userdata('user_id'));
        $this->load->view('edit_dashborad', $data);
    }


    public function edit()
    {
       if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $this->input->post('id');
        $data = [
            'name'        => $this->input->post('name'),
            'model'       => $this->input->post('model'),
            'nationality' => $this->input->post('nationality'),
            'build_year'  => $this->input->post('build_year'),
            'price'       => $this->input->post('price'),
            'status'      => $this->input->post('status')
        ];

        // Upload image if exists
        if (!empty($_FILES['image']['name'])) {
            $config['upload_path'] = './uploads/';
            $config['allowed_types'] = 'jpg|png|jpeg';
            $config['file_name'] = time();

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('image')) {
                $data['image'] = $this->upload->data('file_name');
            }
        }

        $this->Spacecraft_model->update($id, $data);

        echo json_encode(["status" => "success"]);
        return;
    }
    }

    private function _form_rules()
    {
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('model', 'Model', 'required');
        $this->form_validation->set_rules('status', 'Status', 'required');
    }

    private function _upload_image()
    {
        $config['upload_path']   = FCPATH . 'uploads/';
        $config['allowed_types'] = 'jpg|png|jpeg';
        $config['encrypt_name']  = TRUE;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('image')) {
            return 'default_ship.png';
        }

        $uploadData = $this->upload->data();
        $fullPath   = $uploadData['full_path'];   // ruta absoluta
        $fileName   = $uploadData['file_name'];   // nombre original cifrado

        return $this->remove_background($fullPath, $fileName);
    }


   private function remove_background($fullPath, $fileName)
{
    $apiKey = 'XFHqu1qnQHe3DZdRX8n2Wgwm';

    $postFields = [
        'image_file' => new CURLFile($fullPath),
        'size'       => 'auto'
    ];

    $ch = curl_init('https://api.remove.bg/v1.0/removebg');

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $postFields,
        CURLOPT_HTTPHEADER     => [
            'X-Api-Key: ' . $apiKey
        ],
        CURLOPT_HEADER         => true, 
        CURLOPT_TIMEOUT        => 60,
        CURLOPT_VERBOSE        => true   
    ]);

    // Capturar verbose output
    $verbose = fopen('php://temp', 'w+');
    curl_setopt($ch, CURLOPT_STDERR, $verbose);

    $response = curl_exec($ch);
    $info     = curl_getinfo($ch);
    $curlErr  = curl_error($ch);
    curl_close($ch);

    rewind($verbose);
    $verboseLog = stream_get_contents($verbose);

    // Separar headers y body
    $headerSize = $info['header_size'];
    $headers    = substr($response, 0, $headerSize);
    $body       = substr($response, $headerSize);

    // Guardar respuesta cruda para inspección
    file_put_contents(FCPATH.'uploads/debug_response.txt', $body);

    if ($info['http_code'] !== 200) {
        return $fileName;
    }

    $newName = pathinfo($fileName, PATHINFO_FILENAME) . '.png';
    file_put_contents(FCPATH.'uploads/'.$newName, $body);

    unlink($fullPath);

    return $newName;
}



}
