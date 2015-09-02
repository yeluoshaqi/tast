<?php
class Host_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

    /**
     * 路由表中简单的添加修改和删除功能
     */
	public function get_host()
	{	
		$query = $this->db->get('host_routes');
		$list = $query->result_array();

		foreach($list as $key=>$value){
			if($value['type'] == '文本回复'){
				$type = 'textupdate/';
			}else{
				$type = 'newsupdate/';
			}
			$list[$key]['url'] = site_url().'/admin/host/'.$type.$value['id'];
		}

		return $list;
	}
	
	public function get_host_by_keyword($keyword, $wx_id)
	{
		$this->db->where('wx_id',$wx_id);
        $this->db->where('keyword', $keyword);
		$query = $this->db->get('host_routes');
		$info = $query->row_array();
		return $info;
	}
	
	private function set_search($search)
	{
		//echo 'model';
		//print_r($search);
		if($search['keyword']){
			$this->db->like('keyword', $search['keyword']);
		}
	}

    public function get_sort($sort)
    {
        $fields = array('motify_date');
        $list = array();
        foreach($fields as $key=>$value){
            $list[$value]['url'] = site_url().'/admin/host?sort='.$value.'&type=desc';
            $list[$value]['icon'] = 'fa fa-angle-up right';
        }
        if($sort['type'] == 'desc'){
            $list[$sort['sort']] = array(
                'url' => site_url().'/admin/host?sort='.$sort['sort'].'&type=asc',
                'icon' => 'fa fa-angle-down right'
            );
        }
        return $list;
    }
		
	public function get_count($search)
	{
		$this->set_search($search);
		$query = $this->db->get('host_routes');
		return $query->num_rows();
	}
	
	public function get_list($offset=0, $length=10, $search, $sort){
		//print_r($search);
		$this->set_search($search);
		$this->db->order_by($sort['sort'], $sort['type']);
		$this->db->limit($length, $offset);
		$query = $this->db->get('host_routes');
		$list = $query->result_array();
		
		foreach($list as $key=>$value){
			if($value['type'] == '文本回复'){
				$type = 'textupdate/';
			}else{
				$type = 'newsupdate/';
			}
			$list[$key]['url'] = site_url().'/admin/host/'.$type.$value['id'];
		}

		return $list;
	}
	public function create_host($data)
	{
		$data['motify_date'] = date('Y-m-d H:i:s');
		return $this->db->insert('host_routes',$data);
	}
	
	public function update_host($data)
	{
		$this->db->where('id',$data['id']);
		return $this->db->update('host_routes',$data);
	}
	
	public function delete_host_by_id($id)
	{
		$this->db->where('id',$id);
		return $this->db->delete('host_routes');
	}
	
	public function textcreate()
	{
		$data['motify_date'] = date('Y-m-d H:i:s');
		return $this->db->insert('text',$data);
	}
}