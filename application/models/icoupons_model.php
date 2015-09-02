<?php
class Icoupons_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
   
   public function sets($data){
     return $this->db->insert_batch($data);
   }
}