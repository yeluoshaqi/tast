<?php
class Member_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_status($wx, $status=0)
    {
        $list = array(
            1 => array(
                1 => '正常',
                2 => '高级',
                3 => '黑名单'
            ),
            2 => array(
                1 => '未激活',
                2 => '可接单',
                3 => '接单中',
                5 => '黑名单',
            )
        );
        if($status){
            return $list[$wx][$status];
        }else{
            return $list[$wx];
        }
    }

    private function set_search($search)
    {
        if($search['name']){
            $this->db->like('name', $search['name']);
        }
        if($search['mobile']){
            $this->db->like('mobile', $search['mobile']);
        }
        if(isset($search['wx_id']) && $search['wx_id']){
            $this->db->where('wx_id', $search['wx_id']);
        }
        if(isset($search['city']) && $search['city']){
            $this->db->where('city', $search['city']);
        }
    }

    public function get_sort($sort)
    {
        $fields = array('reg_date', 'mobile', 'name');
        $list = array();
        foreach($fields as $key=>$value){
            $list[$value]['url'] = site_url().'/admin/member?sort='.$value.'&type=desc';
            $list[$value]['icon'] = 'fa fa-angle-up right';
        }
        if($sort['type'] == 'desc'){
            $list[$sort['sort']] = array(
                'url' => site_url().'/admin/member?sort='.$sort['sort'].'&type=asc',
                'icon' => 'fa fa-angle-down right'
            );
        }
        return $list;
    }

    public function get_count($search)
    {
        $this->set_search($search);
        $query = $this->db->get('member');
        return $query->num_rows();
    }

    public function get_list($offset=0, $length=10, $search, $sort)
    {
        $this->set_search($search);
        $this->db->order_by($sort['sort'], $sort['type']);
        $this->db->limit($length, $offset);
        $query = $this->db->get('member');
        $list = $query->result_array();
        foreach($list as $key=>$value){
            $list[$key]['status_string'] = $this->get_status($value['wx_id'], $value['status']);
        }
        return $list;
    }

    public function get_one($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('member');
        $info = $query->row_array();
        if($info){
            $info['status_string'] = $this->get_status($info['wx_id'], $info['status']);
            $this->load->model('City_model');
            $info['city_array'] = $this->City_model->get_array($info['city']);
        }
        return $info;
    }

    public function get_member_by_openid($data)
    {
        $this->db->where('openid', $data['openid']);
        $query = $this->db->get('member');
        $info = $query->row_array();
        if($info){
            return $info;
        }else{
            $insert = array(
                'wx_id' => $data['wx_id'],
                'name' => $data['nickname'],
                'nickname' => $data['nickname'],
                'openid' => $data['openid'],
                'last_date' => date('Y-m-d H:i:s'),
                'address' => $data['city'],
                'avatar' => $data['headimgurl']
            );
            $this->create($insert);
            $id = $this->db->insert_id();
            $insert['id'] = $id;
            return $insert;
        }
    }

    public function create($data)
    {
        return $this->db->insert('member', $data);
    }

    public function update($data, $member_id)
    {
        $this->db->where('id', $member_id);
        return $this->db->update('member', $data);
    }

    public function delete($ids)
    {
        if(is_array($ids)){
            $this->db->where_in('id', $ids);
        }else{
            $this->db->where('id', $ids);
        }
        return $this->db->delete('member');
    }
}
