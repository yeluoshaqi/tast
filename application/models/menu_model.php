<?php
class Menu_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}


    public function get_wx_menu($wx_id, $top_id=0)
    {
        $this->db->where('wx_id', $wx_id);
        $this->db->where('top_id', $top_id);
        $this->db->order_by('sort');
        $query = $this->db->get('menu');
        $menu = $query->result_array();
        $list = array();
        foreach($menu as $key=>$value){
            $list[$key]['id'] = $value['id'];
            $list[$key]['name'] = urlencode($value['name']);
            $list[$key]['type'] = $value['type'];
            if($value['type'] == 'view'){
                $list[$key]['url'] = $value['value'];
            }else{
                $list[$key]['key'] = urlencode($value['value']);
            }
        }
        return $list;
    }
    
	/*菜单的三个基本功能*/
	public function get_menu()
	{
		$this->db->order_by('wx_id');
		$query = $this->db->get('menu');
		$list = $query->result_array();
		return $list;
	}
	
	public function get_menu_by_id($id)
	{
		$this->db->where('id',$id);
		$query = $this->db->get('menu');
		$info = $query->row_array();
		return $info;
	}
	
	public function get_parent_menu()
	{
		$this->db->from('menu');
		$this->db->where('top_id',0);
		$query = $this->db->get();
		return $query->result_array();
	}
	
	public function create_menu($data)
	{
		return $this->db->insert('menu',$data);
	}
	
	public function update_menu($data)
	{
		$this->db->where('id',$data['id']);
		return $this->db->update('menu',$data);
	}
	
	public function delete_menu_by_id($id)
	{
		$this->db->where('id',$id);
		return $this->db->delete('menu');
		
	}
}