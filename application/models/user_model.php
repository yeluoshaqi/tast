<?php
class User_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function validate($name, $password)
    {
        $this->db->where('name', $name);
        $this->db->where('password', $password);
        $query = $this->db->get('user');
        return $query->row_array();
    }
}