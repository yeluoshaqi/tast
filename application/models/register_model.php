<?php
class Register_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_count()
    {
        $query = $this->db->get('register');
        return $query->num_rows();
    }

    public function get_list($offset=0, $length=10)
    {
        $this->db->order_by('create_date desc');
        $this->db->limit($length, $offset);
        $query = $this->db->get('register');
        $list = $query->result_array();
        foreach($list as $key=>$value){
        }
        return $list;
    }

    public function get_one($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('register');
        $info = $query->row_array();
        return $info;
    }

    public function create($insert)
    {
        return $this->db->insert('register', $insert);
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('register');
    }
}