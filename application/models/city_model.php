<?php
class City_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function set_form_validation()
    {
        $this->form_validation->set_error_delimiters('<small class="error">','</small>');
        $this->form_validation->set_rules('parent_id','Parent_id','trim|required');
        $this->form_validation->set_rules('name','Name','trim|required');
        $this->form_validation->set_rules('sort','Sort','trim|required|max_length[4]');
    }

    private function set_search($search)
    {
        if(isset($search['parent_id']) && $search['parent_id']){
            $this->db->where('parent_id', $search['parent_id']);
        }
    }

    public function get_count()
    {
        $query = $this->db->get('city');
        return $query->num_rows();
    }

    public function get_list($offset=0,$length=10,$search=array())
    {
        $this->set_search($search);
        $this->db->limit($length, $offset);
        $query = $this->db->get('city');
        $list = $query->result_array();
        return $list;
    }

    public function get_json($id)
    {
        $this->db->where('parent_id', $id);
        $query = $this->db->get('city');
        $list = $query->result_array();
        return json_encode($list);
    }

    public function get_array($id)
    {
        if(!$id){
            return array();
        }else{
            $street = $this->get_one($id);
            $zone = $this->get_one($street['parent_id']);
            $city = $this->get_one($zone['parent_id']);
            return array_merge(array($city), array($zone), array($street));
        }
    }

    public function get_one($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('city');
        return $query->row_array();
    }

    public function create($data)
    {
        return $this->db->insert('city', $data);
    }

    public function update($data)
    {
        $this->db->where('id', $data['id']);
        return $this->db->update('city', $data);
    }

}