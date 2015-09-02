<?php
class Service_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_list($status=0)
    {
        if($status){
            $this->db->where('status', $status);
        }
        $query = $this->db->get('service');
        $list = $query->result_array();
        foreach($list as $key=>$value){
            $list[$key]['status_name'] = ($value['status'] == 2) ? '使用' : '不使用';
        }
        return $list;
    }

    public function get_one($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('service');
        $info = $query->row_array();
        $info['status_name'] = ($info['status'] == 2) ? '使用' : '不使用';
        return $info;
    }

    public function create($data)
    {
        $data['modity_date'] = date('Y-m-d H:i:s');
        return $this->db->insert('service', $data);
    }

    public function update($data)
    {
        $this->db->where('id', $data['id']);
        return $this->db->update('service', $data);
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('service');
    }
}