<?php
class Field_value_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_list($type, $id, $log_id, $is_number=false)
    {
        $this->load->model('Field_model');
        $list = $this->Field_model->get_list($type, $id, $is_number);
        foreach($list as $key=>$value){
            $value = $this->get_one($log_id, $value['id']);
            if($value){
                $list[$key]['value'] = $value['value'];
            }else{
                $list[$key]['value'] = '';
            }
        }
        return $list;
    }

    public function get_one($log_id, $field_id)
    {
        $this->db->where('log_id', $log_id);
        $this->db->where('field_id', $field_id);
        $query = $this->db->get('field_value');
        return $query->row_array();
    }

    public function create($insert)
    {
        return $this->db->insert('field_value', $insert);
    }

    public function update($log_id, $field_id, $value)
    {
        $result = $this->get_one($log_id, $field_id);
        if($result){
            $this->db->where('log_id', $log_id);
            $this->db->where('field_id', $field_id);
            return $this->db->update('field_value', array('value'=>$value));
        }else{
            return $this->create(array('log_id'=>$log_id,'field_id'=>$field_id,'value'=>$value));
        }
    }
}