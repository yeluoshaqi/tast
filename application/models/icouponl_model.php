<?php
class Icouponl_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    public function get_count(){
        $query = $this->db->get('icoupon_list'); 
        return $query->num_rows();
    }
    public function get($search){
        if(empty($search) || !is_array($search)){
            return false;
        }
        foreach ($search as $key => $value) {
            $this->db->where($key,$value);
        }
        $query = $this->db->get('icoupon_list');
        return $query->row_array();
    }
    public function gets($offset=0, $length=10){
        $this->db->limit($length, $offset);
        $query = $this->db->get('icoupon_list'); 
        return $query->result_array();
    }
    public function set($data){
        $data['updated_at'] = '2015-10-15 10:23:32';
        return $this->db->insert('icoupon_list', $data);
    }
    public function update($search,$data){
        if(empty($search) || empty($data) || !is_array($search) || !is_array($data)){
            return false;
        }
        foreach ($search as $key => $value) {
            $this->db->where($key,$value);
        }
         return $this->db->update('icoupon_list', $data);
    }
}