<?php
class Wx_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * 微信表中简单的添加修改和删除功能
     */
	public function get_wx()
	{
		$query = $this->db->get('wx');
		$list = $query->result_array();
		return $list;
	}

	public function get_wx_by_id($id)
	{
		$this->db->where('id',$id);
		$query = $this->db->get('wx');
		$info = $query->row_array();
		return $info;
	}

	public function create_wx($data)
	{
		$data['motify_date'] = date('Y-m-d H:i:s');
		return $this->db->insert('wx',$data);
	}

	public function update_wx($data)
	{
		$this->db->where('id',$data['id']);
		return $this->db->update('wx',$data);
	}

	public function delete_wx_by_id($id)
	{
		$this->db->where('id',$id);
		return $this->db->delete('wx');
	}
}