<?php
class Parent_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_count()
    {
        $query = $this->db->get('parent');
        return $query->num_rows();
    }

    public function get_list($offset=0, $length=10)
    {
        $this->db->limit($length, $offset);
        $query = $this->db->get('parent');
        $list = $query->result_array();
        foreach($list as $key=>$value){
            $list[$key]['sex_name'] = ($value['sex'] == 1) ? 'Man' : 'Women';
        }
        return $list;
    }

    public function get_list_by_member($member_id)
    {
        $this->db->where('member_id', $member_id);
        $query = $this->db->get('parent');
        $list = $query->result_array();
        return $list;
    }

    public function get_list_by_nurse($nurse_id)
    {
        $this->load->model('Order_item_model');
        $item_list = $this->Order_item_model->get_list(0,10,array('nurse_id'=>$nurse_id));
        $ids = array();
        if($item_list){
            foreach($item_list as $key=>$value){
                if(!in_array($value['family_id'], $ids)){
                    $ids[] = $value['family_id'];
                }
            }
        }
        if($ids){
            $this->db->where_in('id', $ids);
            $query = $this->db->get('parent');
            return $query->result_array();
        }else{
            return array();
        }
    }

    public function get_list_by_ids($ids)
    {
        $this->db->where_in('id', $ids);
        $query = $this->db->get('parent');
        $list = $query->result_array();
        return $list;
    }

    public function get_one($id, $member_id=0)
    {
        $this->db->where('id', $id);
        if($member_id){
            $this->db->where('member_id', $member_id);
        }
        $query = $this->db->get('parent');
        $info = $query->row_array();
        $info['sex_name'] = ($info['sex'] == 1) ? 'Man' : 'Woman';
        $this->load->model('City_model');
        $info['city_array'] = $this->City_model->get_array($info['city']);
        return $info;
    }

    public function create($insert)
    {
        $insert['modify_date'] = date('Y-m-d H:i:s');
        return $this->db->insert('parent', $insert);
    }

    public function update($update, $member_id)
    {
        $info = $this->get_one($update['id'], $member_id);
        if($info){
            $update['modify_date'] = date('Y-m-d H:i:s');
            $this->db->where('id', $update['id']);
            return $this->db->update('parent', $update);
        }else{
            return false;
        }
    }

    public function delete($id, $member_id=0)
    {
        $this->db->where('id', $id);
        if($member_id){
            $this->db->where('member_id', $member_id);
        }
        return $this->db->delete('parent');
    }
}