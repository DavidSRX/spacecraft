<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Spacecraft_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_by_user($user_id, $filters = [])
    {
        $this->db->where('user_id', $user_id);

        // Filtro específico por nombre (separado)
        if (!empty($filters['name'])) {
            $this->db->like('name', $filters['name']);
        }

        // Filtro puntual por estado
        if (!empty($filters['nationality'])) {
            $this->db->like('nationality', $filters['nationality']);
        }

        // Filtro puntual por estado
        if (!empty($filters['model'])) {
            $this->db->like('model', $filters['model']);
        }

        // Filtro puntual por estado
        if (!empty($filters['build'])) {
            $this->db->like('build_year', $filters['build']);
        }


        return $this->db->order_by('created_at', 'DESC')
                        ->get('spacecrafts')
                        ->result();
    }



    public function insert($data)
    {
        return $this->db->insert('spacecrafts', $data);
    }

    public function get_one($id, $user_id)
    {
        return $this->db->where('id', $id)
                        ->where('user_id', $user_id)
                        ->get('spacecrafts')
                        ->row();
    }

    public function update($id, $data)
    {
        return $this->db->where('id', $id)->update('spacecrafts', $data);
    }
}
