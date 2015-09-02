<?php
class Service_log_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_count()
    {
        $query = $this->db->get('service_log');
        return $query->num_rows();
    }

    private function set_search($search)
    {
        if(isset($search['order_id']) && $search['order_id']){
            $this->db->where('order_id', $search['order_id']);
        }
    }

    public function get_list($offset=0, $length=10, $search=array())
    {
        $this->set_search($search);
        $this->load->model(array('Order_model', 'Member_model'));
        $this->db->limit($length, $offset);
        $this->db->order_by('date desc');
        $query = $this->db->get('service_log');
        $list = $query->result_array();
        foreach($list as $key=>$value){
            $order = $this->Order_model->get_one($value['order_id']);
            $nurse = $this->Member_model->get_one($order['nurse_id']);
            $list[$key] += array(
                'order_num' => $order['order_num'],
                'name' => $order['name'],
                'mobile' => $order['mobile'],
                'nurse_name' => $nurse['name']
            );
        }
        return $list;
    }

    public function get_data($family_id)
    {
        $this->load->model(array('Order_model','Field_model','Field_value_model'));
        $order_ids = $this->Order_model->get_ids_by_family($family_id);

        $this->db->where_in('order_id', $order_ids);
        $this->db->limit(12);
        $query = $this->db->get('service_log');
        $logs = $query->result_array();

        $field_list = $this->Field_model->get_list('service', 1);
        foreach($field_list as $key=>$value){

            foreach($logs as $k2=>$v2){
                $times[$k2] = substr($v2['date'], 0, 10);
                $field = $this->Field_value_model->get_one($v2['id'], $value['id']);
                $field_value[$k2] = $field['value'];
            }
            $color = base_convert(substr($value['color'],1,2),16,10).','.
                   base_convert(substr($value['color'],3,2), 16,10).','.
                   base_convert(substr($value['color'],5,2),16,10);
            $datasets[$key] = array(
                'label' => $value['name'],
                'fillColor' => '',
                'fillColor' => "rgba(".$color.",0.2)",
                'strokeColor' => "rgba(".$color.",1)",
                'pointColor' => "rgba(".$color.",1)",
                'pointStrokeColor'=> "#fff",
                'pointHighlightFill' => "#fff",
                'pointHighlightStroke' => "rgba(".$color.",1)",
                'data' => $field_value
            );
        }

        $data = array(
            'labels' => $times,
            'datasets' => $datasets
        );
        return json_encode($data);
    }

    public function get_one($id)
    {
        $this->load->model(array('Order_model', 'Member_model'));
        $this->db->where('id', $id);
        $query = $this->db->get('service_log');
        $info = $query->row_array();

        $order = $this->Order_model->get_one($info['order_id']);
        $nurse = $this->Member_model->get_one($order['nurse_id']);

        $info += array(
            'order_num' => $order['order_num'],
            'name' => $order['name'],
            'mobile' => $order['mobile'],
            'nurse_name' => $nurse['name']
        );
        return $info;
    }

    public function create($data)
    {
        $insert = array(
            'order_id' => $data['order_id']
        );
        $result = $this->db->insert('service_log', $insert);
        if($result){
            $this->load->model('Field_value_model');
            $log_id = $this->db->insert_id();
            foreach($data['field'] as $key=>$value){
                $item = array(
                    'log_id' => $log_id,
                    'field_id' => $key,
                    'value' => $value
                );
                $this->Field_value_model->create($item);
            }
            return TRUE;
        }else{
            return FALSE;
        }
    }
}