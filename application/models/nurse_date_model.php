<?php
class Nurse_date_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function check_date($data)
    {
        $this->db->where('nurse_id', $data['nurse_id']);
        $this->db->where('date', $data['date']);
        $query = $this->db->get('nurse_date');
        return $query->row_array();
    }

    public function check_time($data)
    {
        $this->db->where('nurse_id', $data['nurse_id']);
        $this->db->where('date', $data['date']);
        $this->db->where('time', $data['time']);
        $query = $this->db->get('nurse_date');
        return $query->row_array();
    }

    public function create($data)
    {
        return $this->db->insert('nurse_date', $data);
    }

    public function delete($data)
    {
        $this->db->where('nurse_id', $data['nurse_id']);
        $this->db->where('date', $data['date']);
        $this->db->where('time', $data['time']);
        return $this->db->delete('nurse_date');
    }
}