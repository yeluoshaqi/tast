<?php
class Order_item_model extends CI_Model
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
            2 => '已分配',
            3 => '已出发',
            4 => '服务中',
            5 => '待评价',
            6 => '已完成',
            7 => '已取消',
        );
        return ($status) ? $list[$status] : $list;
    }

    public function set_form_validation()
    {
        $this->form_validation->set_error_delimiters('<small class="error">', '</small>');
        $this->form_validation->set_rules('name', 'Name', 'trim');
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|exact_length[11]');
        $this->form_validation->set_rules('nurse_id', 'Nurse Num', 'trim|is_natural_no_zero');
        $this->form_validation->set_rules('status', 'Status', 'trim|is_natural_no_zero');
    }

    public function set_search($search)
    {


        /** order pay **/
        $this->db->select('id,is_pay,type');
        $query = $this->db->get('order');
        $orders = $query->result_array();
        $ids = array();
        foreach($orders as $key=>$value){
            if($value['type'] == 1){
                if($value['is_pay'] == 2){
                    if(!in_array($value['id'], $ids)){
                        $ids[] = $value['id'];
                    }
                }
            }else{
                if(!in_array($value['id'], $ids)){
                    $ids[] = $value['id'];
                }
            }
        }
        $this->db->where_in('order_id', $ids);
        /** end **/

        if(isset($search['order_id']) && $search['order_id']){
            $this->db->where('order_id', $search['order_id']);
        }

        if(isset($search['family_id']) && $search['family_id']){
            $this->db->where('family_id', $search['family_id']);
        }
        if(isset($search['nurse_id']) && $search['nurse_id']){
            $this->db->where('nurse_id', $search['nurse_id']);
        }
        if(isset($search['num']) && $search['num']){
            $this->db->like('num', $search['num']);
        }
        if(isset($search['name']) && $search['name']){
            $this->db->like('name', $search['name']);
        }
        if(isset($search['mobile']) && $search['mobile']){
            $this->db->like('mobile', $search['mobile']);
        }
        if(isset($search['city']) && $search['city']){
            $this->db->where('city', $search['city']);
        }
        if(isset($search['date']) && $search['date']){
            $this->db->where('date', $search['date']);
        }
        if(isset($search['status']) && $search['status']){
            $this->db->where('status', $search['status']);
        }
    }

    public function get_count($search)
    {
        $this->set_search($search);
        $query = $this->db->get('order_item');
        return $query->num_rows();
    }

    public function get_list($offset=0, $length=10, $search=array())
    {
        $this->load->model('Member_model');
        $this->set_search($search);
        
        $this->db->limit($length, $offset);
        $this->db->order_by('create_date desc');
        $query = $this->db->get('order_item');
        $list = $query->result_array();
        foreach($list as $key=>$value){
            $list[$key]['status_string'] = $this->get_status($value['status']);
            if($value['nurse_id']){
                $nurse = $this->Member_model->get_one($value['nurse_id']);
                if(!$nurse){
                    $nurse = array('name'=>'未知','mobile'=>'','address'=>'');
                }
            }else{
                $nurse = array('name'=>'未知','mobile'=>'','address'=>'');
            }
            $list[$key]['nurse'] = $nurse;
        }
        return $list;
    }

    public function get_one($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('order_item');
        $info = $query->row_array();
        $info['status_string'] = $this->get_status($info['status']);
        $this->load->model('Member_model');
        if($info['nurse_id']){
            $nurse = $this->Member_model->get_one($info['nurse_id']);
            if(!$nurse){
                $nurse = array('name'=>'None','mobile'=>'','address'=>'');
            }
        }else{
            $nurse = array('name'=>'None','mobile'=>'','address'=>'');
        }
        $info['nurse'] = $nurse;
        $this->load->model('Parent_model');
        $family = $this->Parent_model->get_one($info['family_id']);
        if($family){
            $info['family_title'] = $family['title'];
        }else{
            $info['family_title'] = '';
        }


        $this->load->model('City_model');
        $info['city_array'] = $this->City_model->get_array($info['city']);
        return $info;
    }

    public function create($insert)
    {
        $result = $this->db->insert('order_item', $insert);
        if($result){
            return $this->db->insert_id();
        }else{
            return 0;
        }
    }

    public function update($update)
    {
        $this->db->where('id', $update['id']);
        $update['motify_date'] = date('Y-m-d H:i:s');
        return $this->db->update('order_item', $update);
    }

    public function delete_by_order($id)
    {
        $this->db->where('order_id', $id);
        return $this->db->delete('order_item');
    }

    public function set_status($id, $status)
    {
        $this->db->where('id', $id);
        return $this->db->update('order_item', array('status'=>$status));
    }

    public function get_data($family_id)
    {
        $this->load->model(array('Order_item_model','Field_model','Field_value_model'));

        /** order pay **/
        $this->db->select('id,is_pay,type');
        $query = $this->db->get('order');
        $orders = $query->result_array();
        $ids = array();
        foreach($orders as $key=>$value){
            if($value['type'] == 1){
                if($value['is_pay'] == 2){
                    if(!in_array($value['id'], $ids)){
                        $ids[] = $value['id'];
                    }
                }
            }else{
                if(!in_array($value['id'], $ids)){
                    $ids[] = $value['id'];
                }
            }
        }
        $this->db->where_in('order_id', $ids);
        /** end **/

        $this->db->where('family_id', $family_id);
        $this->db->limit(12);
        $query = $this->db->get('order_item');
        $list = $query->result_array();
        if($list){
            foreach($list as $key=>$value){
                $times[] = $value['date'];
                $field_list = $this->Field_value_model->get_list('service', 1, $value['id'], 1);
                foreach($field_list as $k2=>$v2){
                    /** make 3 value to 1 **/
                    if(strchr($v2['value'], ",")){
                        $value_list = explode(",",$v2['value']);
                        $value_all = 0;
                        for($i=0;$i<count($value_list);$i++){
                            $value_all += $value_list[$i];
                        }
                        $v2['value'] = round($value_all/(count($value_list)), 2);
                    }
                    /** end **/

                    $item[$k2]['name'] = $v2['name'];
                    $item[$k2]['color'] = base_convert(substr($v2['color'],1,2),16,10).','.
                                        base_convert(substr($v2['color'],3,2), 16,10).','.
                                        base_convert(substr($v2['color'],5,2),16,10);
                    $item[$k2]['array'][$key] = $v2['value'];
                }
            }
        }
        foreach($item as $key=>$value){
            $datasets[$key] = array(
                'label' => $value['name'],
                'fillColor' => '',
                'fillColor' => "rgba(".$value['color'].",0.2)",
                'strokeColor' => "rgba(".$value['color'].",1)",
                'pointColor' => "rgba(".$value['color'].",1)",
                'pointStrokeColor'=> "#fff",
                'pointHighlightFill' => "#fff",
                'pointHighlightStroke' => "rgba(".$value['color'].",1)",
                'data' => $value['array']
            );
        }

        $data = array(
            'labels' => $times,
            'datasets' => $datasets
        );
        return json_encode($data);
    }
}
