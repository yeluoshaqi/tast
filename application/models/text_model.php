<?php
class Text_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

    /**
     * 路由表中简单的添加修改和删除功能
     */
/*	public function get_text()
	{
		$query = $this->db->get('text');
		$list = $query->result_array();
		return $list;
	}
	
	public function get_text_by_id($id)
	{
		$this->db->where('id',$id);
		$query = $this->db->get('text');
		$info = $query->row_array();
		return $info;
	}
	
	public function create_text($data)
	{
		$data['motify_date'] = date('Y-m-d H:i:s');
		return $this->db->insert('text',$data);
	}
	
	public function update_text($data)
	{
		$this->db->where('id',$data['id']);
		return $this->db->update('text',$data);
	}
	
	public function delete_text_by_id($id)
	{
		$this->db->where('id',$id);
		return $this->db->delete('text');
	}*/
	
		public function textcreate($data)
	{
		//print_r($data);exit;
		$data['motify_date'] = date('Y-m-d H:i:s');
		$routes['type'] = $data['type'];
		$routes['keyword'] = $data['keyword'];
		$routes['wx_id'] = $data['wx_id'];
		$routes['motify_date'] = $data['motify_date'];
		
		$text['keyword'] = $data['keyword'];
		$text['wx_id'] = $data['wx_id'];
		$text['content'] = $data['content'];
		
		if($this->db->insert('host_routes',$routes)){
			//$textid = $this->db->where('id',$id);
			$textid = $this->db->insert_id();
			$text['id'] = $textid;
			//echo '$text[id]';
			//print_r($text['id']);exit;			
			return $this->db->insert('text',$text);
		}else{
			return false;
		}
		//return $this->db->insert('text',$data);
	}

	public function get_text_by_id($id)
	{
		$this->db->where('id',$id);
		$query = $this->db->get('text');
		$info = $query->row_array();
		return $info;
	}


	public function textupdate($data)
	{
		//print_r($data);exit;
		$this->db->where('id',$data['id']);//先显示之前的信息
		//print_r($data);exit;
		$data['motify_date'] = date('Y-m-d H:i:s');
		$routes['id'] = $data['id'];
		$routes['type'] = $data['type'];
		$routes['keyword'] = $data['keyword'];
		$routes['wx_id'] = $data['wx_id'];
		$routes['motify_date'] = $data['motify_date'];
		
		
		$text['id'] = $data['id'];
		$text['keyword'] = $data['keyword'];
		$text['wx_id'] = $data['wx_id'];
		$text['content'] = $data['content'];
		
		$this->db->where('id', $text['id']);		
		if($this->db->update('text',$text)){
			//$textid = $this->db->insert_id();
			//$routes['id'] = $textid;
			//echo '$text[id]';
			//print_r($text['id']);exit;
			//print_r($routes);exit;
			$this->db->where('id', $routes['id']);	
			if($this->db->update('host_routes',$routes)){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
		
		//return $this->db->update('text',$data);
	}
	
		public function textdelete($id)
	{
		$this->db->where('id',$id);		
		if($this->db->delete('host_routes')){
			$this->db->where('id',$id);
			if($this->db->delete('text')){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
}