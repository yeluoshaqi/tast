<?php
class Order_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_status($status=0)
    {
        $list = array(
            1 => '新订单',
            2 => '服务中',
            3 => '已完成',
            4 => '已取消'
        );
        return ($status) ? $list[$status] : $list;
    }

    private function set_search($search)
    {
        if(isset($search['id']) && $search['id']){
            $this->db->where('id', $search['id']);
        }
        if(isset($search['member_id']) && $search['member_id']){
            $this->db->where('member_id', $search['member_id']);
        }
        if(isset($search['type']) && $search['type']){
            $this->db->where('type', $search['type']);
        }
        if(isset($search['order_num']) && $search['order_num']){
            $this->db->where('order_num', $search['order_num']);
        }
        if(isset($search['is_pay']) && $search['is_pay']){
            $this->db->where('is_pay', $search['is_pay']);
        }
        if(isset($search['status']) && $search['status']){
            $this->db->where('status', $search['status']);
        }
    }


    public function get_sort($sort)
    {
        $fields = array('create_date', 'order_num', 'status');
        $list = array();
        foreach($fields as $key=>$value){
            $list[$value]['url'] = site_url().'/admin/order?sort='.$value.'&type=desc';
            $list[$value]['icon'] = 'fa fa-angle-up right';
        }
        if($sort['type'] == 'desc'){
            $list[$sort['sort']] = array(
                'url' => site_url().'/admin/order?sort='.$sort['sort'].'&type=asc',
                'icon' => 'fa fa-angle-down right'
            );
        }
        return $list;
    }

    public function get_count($search)
    {
        $this->set_search($search);
        $query = $this->db->get('order');
        return $query->num_rows();
    }


    public function get_list($offset=0, $length=10, $search=array(), $sort=array('sort'=>'create_date','type'=>'desc'))
    {
        $this->set_search($search);
        $this->db->limit($length, $offset);
        $this->db->order_by($sort['sort'], $sort['type']);
        $query = $this->db->get('order');
        $list = $query->result_array();

        $this->load->model(array('Service_model', 'Member_model'));
        foreach($list as $key=>$value){
            //$service = $this->Service_model->get_one($value['service_id']);
            //$list[$key]['service_name'] = $service['name'];
            $member = $this->Member_model->get_one($value['member_id']);
            $list[$key]['member_name'] = $member['name'];

            $list[$key]['type_name'] = ($value['type'] == 1) ? '<i class="fa fa-weixin"></i>' : '<i class="fa fa-phone"></i>';
            $list[$key]['is_pay_string'] = ($value['is_pay'] == 1) ? '<i class="fa fa-circle-o"></i>' : '<i class="fa fa-check"></i>';

            $list[$key]['status_name'] = $this->get_status($value['status']);
        }
        return $list;
    }

    public function get_one($id, $member_id=0)
    {
        $this->db->where('id', $id);
        if($member_id){
            $this->db->where('member_id', $member_id);
        }
        $query = $this->db->get('order');
        $info = $query->row_array();

        $this->load->model(array('Service_model', 'Member_model'));
        $service = $this->Service_model->get_one($info['service_id']);
        $info['service_name'] = $service['name'];

        $info['type_name'] = ($info['type'] == 1) ? '微信订单' : '电话订单';
        $info['status_name'] = $this->get_status($info['status']);
        $info['is_pay_string'] = ($info['is_pay'] == 1) ? '未支付' : '已支付';
        return $info;
    }

    public function get_family_by_nurse($nurse_id)
    {
        $this->db->select('id,nurse_id,family_id');
        $this->db->where('nurse_id', $nurse_id);
        $query = $this->db->get('order');
        $list = $query->result_array();
        $ids = array();
        $i = 0;
        if($list){
            foreach($list as $key=>$value){
                if(!in_array($value['family_id'], $ids)){
                    $ids[$i] = $value['family_id'];
                    $i++;
                }
            }
        }
        return $ids;
    }

    public function get_ids_by_family($family_id)
    {
        $this->db->select('id, family_id');
        $this->db->where('family_id', $family_id);


        $query = $this->db->get('order');
        $list = $query->result_array();
        $ids = array();
        foreach($list as $key=>$value){
            $ids[$key] = $value['id'];
        }
        return $ids;
    }

    public function create($data)
    {
        $result = $this->db->insert('order', $data);
        if($result){
            $this->load->helper('file');
            write_file("./message.log",date('Y-m-d H:i:s'));
            return $this->db->insert_id();
        }else{
            return 0;
        }
    }

    public function update($data)
    {
        $this->db->where('id', $data['id']);
        return $this->db->update('order', $data);
    }

    public function cancel($id, $member_id)
    {
        $this->db->where('id', $id);
        $this->db->where('member_id', $member_id);
        return $this->db->update('order', array('status'=>4));
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('order');
    }

    public function set_comment($id)
    {
        $this->db->where('id', $id);
        return $this->db->update('order', array('is_comment'=>2));
    }

    public function set_status($id, $status)
    {
        $this->db->where('id', $id);
        //$this->db->where('member_id', $member_id);
        return $this->db->update('order', array('status'=>$status));
    }

    public function set_pay_status($order_num, $trade_no)
    {
        $update = array(
            'pay_num' => $trade_no,
            'is_pay' => 2,
        );
        $this->db->where('id', $order_num);
        $result = $this->db->update('order', $update);
        return $result;
    }
}