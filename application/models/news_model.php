<?php
class News_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

    /**
     * 路由表中简单的添加修改和删除功能
     */
	public function newscreate($data)
	{
		//print_r($data);exit;
		$data['motify_date'] = date('Y-m-d H:i:s');
		$routes['id'] = $data['id'];
		$routes['type'] = $data['type'];
		$routes['keyword'] = $data['keyword'];
		$routes['wx_id'] = $data['wx_id'];
		$routes['motify_date'] = $data['motify_date'];
		
		
		$news['id'] = $data['id'];
		$news['keyword'] = $data['keyword'];
		$news['wx_id'] = $data['wx_id'];
		$news['content'] = $data['content'];
		$news['front_id'] = $data['front_id'];
		$news['title'] = $data['title'];
		$news['desc'] = $data['desc'];
		$news['content'] = $data['content'];
		$news['link'] = $data['link'];
		$news['userfile'] = $data['userfile'];
		
		if($this->db->insert('host_routes',$routes)){
			$newsid = $this->db->insert_id();
			$news['id'] = $newsid;			
			return $this->db->insert('news',$news);
		}else{
			return false;
		}
	}


	public function get_news_by_id($id)
	{
		$this->db->where('id',$id);
		$query = $this->db->get('news');
		$info = $query->row_array();
		return $info;
	}

	public function newsupdate($data)
	{
		
		$this->db->where('id',$data['id']);//先显示之前的信息
		$query = $this->db->get('news');
		$news = $query->row_array();
		
		$data['motify_date'] = date('Y-m-d H:i:s');
		$routes['id'] = $data['id'];
		$routes['type'] = $data['type'];
		$routes['keyword'] = $data['keyword'];
		$routes['wx_id'] = $data['wx_id'];
		$routes['motify_date'] = $data['motify_date'];
		
		
		$news['id'] = $data['id'];
		$news['keyword'] = $data['keyword'];
		$news['wx_id'] = $data['wx_id'];
		$news['content'] = $data['content'];
		$news['front_id'] = $data['front_id'];
		$news['title'] = $data['title'];
		$news['desc'] = $data['desc'];
		$news['content'] = $data['content'];
		$news['link'] = $data['link'];
		

		$news['userfile'] = $data['userfile'];
		
		$this->db->where('id', $routes['id']);
		$this->db->update('host_routes',$routes);
		
		$this->db->where('id', $data['id']);
		if($this->db->update('news',$news)){
			return true;
		}else{
			return false;
		}
		
		
		//if($data['userfile']!= null){
//				//unlink('./uploads/'.$news['userfile']);
//
//
//			}
		
/*		$this->db->where('id', $news['id']);	
		if($this->db->update('news',$news)){
			if($data['userfile']!= null){
				unlink('./uploads/'.$news['userfile']);
				$news['userfile'] = $data['userfile'];

			}
			$this->db->where('id', $routes['id']);	
			if($this->db->update('host_routes',$routes)){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
*/		
		//return $this->db->update('text',$data);
	}
	
		public function newsdelete($id)
	{
		$this->db->where('id',$id);		
		if($this->db->delete('host_routes')){
			$this->db->where('id',$id);
			if($this->db->delete('news')){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
}