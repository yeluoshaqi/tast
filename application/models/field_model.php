<?php
class Field_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_source_type_name($source_type)
    {
        $list = array(
            'service' => '套餐',
        );
        return $list[$source_type];
    }

    public function get_list($source_type, $source_id, $is_number=false)
    {
        if($is_number){
            $this->db->where('is_number', 1);
        }
        $this->db->where('source_type', $source_type);
        $this->db->where('source_id', $source_id);
        $this->db->order_by('sort');
        $query = $this->db->get('field');
        $list = $query->result_array();
        return $list;
    }

    public function get_one($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('field');
        $info = $query->row_array();
        return $info;
    }

    public function create($data)
    {
        return $this->db->insert('field', $data);
    }

    public function update($data)
    {
        $this->db->where('id', $data['id']);
        return $this->db->update('field', $data);
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('field');
    }
}