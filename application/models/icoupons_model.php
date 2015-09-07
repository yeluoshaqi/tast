<?php
class Icoupons_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
   
   public function sets($data){
     return $this->db->insert_batch('icoupon_sncode',$data);
   }

   public function get($search){
        if(empty($search) || !is_array($search)){
            return false;
        }
        foreach ($search as $key => $value) {
            $this->db->where($key,$value);
        }
        $query = $this->db->get('icoupon_sncode');
        return $query->row_array();
    }

     public function get_count($search){
         foreach ($search as $key => $value) {
            $this->db->where($key,$value);
        }
        $query = $this->db->get('icoupon_sncode'); 
        return $query->num_rows();
    }

    public function gets($search,$offset=0, $length=10){
        $this->db->limit($length, $offset);
        foreach ($search as $key => $value) {
            $this->db->where($key,$value);
        }
        $query = $this->db->get('icoupon_sncode'); 
        return $query->result_array();
    }
}