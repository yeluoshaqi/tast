<?php
class Message_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_type($type=0)
    {
        $list = array(
            1 => '意见',
            2 => '纠错',
            3 => '感谢',
            4 => '投诉'
        );
        if($type){
            return $list[$type];
        }else{
            return $list;
        }
    }

    public function get_count()
    {
        $query = $this->db->get('message');
        return $query->num_rows();
    }

    public function get_list($offset=0, $length=10)
    {
        $this->load->model('Member_model');
        $this->db->order_by('create_date desc');
        $this->db->limit($length, $offset);
        $query = $this->db->get('message');
        $list = $query->result_array();
        foreach($list as $key=>$value){
            $member = $this->Member_model->get_one($value['member_id']);
            $list[$key]['member_name'] = $member['name'];
            $list[$key]['type_string'] = $this->get_type($value['type']);
        }
        return $list;
    }

    public function get_one($id)
    {
        $this->load->model('Member_model');
        $this->db->where('id', $id);
        $query = $this->db->get('message');
        $info = $query->row_array();
        $member = $this->Member_model->get_one($info['member_id']);
        $info['member_name'] = $member['name'];
        $info['type_string'] = $this->get_type($info['type']);
        return $info;
    }

    public function create($insert)
    {
        return $this->db->insert('message', $insert);
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('message');
    }
}
