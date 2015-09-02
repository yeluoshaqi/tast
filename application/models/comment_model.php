<?php
class Comment_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_item($type=0,$item_string=array())
    {
        $list = array(
            1 => array(
                1 => '没有准时拜访',
                2 => '体检时没带好手套',
                3 => '进门没带好鞋套',
                4 => '没主动出示随身物品',
            ),
            2 => array(
                1 => '老人不守时',
                2 => '沟通不顺畅',
                3 => '老人不配合',
                4 => '老人行为怪异'
            )
        );

        if(is_array($item_string)){
            if($type){
                return $list[$type];
            }else{
                return $list;
            }
        }else{
            $string = '';
            $item_array = explode(",",$item_string);
            foreach($item_array as $key=>$value){
                $string .= $list[$type][$value].',';
            }
            return $string;
        }

    }
    public function get_count()
    {
        $query = $this->db->get('comment');
        return $query->num_rows();
    }

    public function get_list($offset=0, $length=10)
    {
        $this->load->model('Order_model');

        $this->db->order_by('create_date desc');
        $this->db->limit($length, $offset);
        $query = $this->db->get('comment');
        $list = $query->result_array();
        foreach($list as $key=>$value){
            $order = $this->Order_model->get_one($value['order_id']);
            $list[$key]['order_num'] = $order['order_num'];
            $list[$key]['item_string'] = $this->get_item($value['item']);
            $list[$key]['type_name'] = ($value['type'] == 1) ? '客户对众包' : '众包对客户';
            $list[$key]['score_icon'] = $this->get_score_icon($value['score']);
        }
        return $list;
    }

    private function get_score_icon($score)
    {
        $icon = '<i class="fa fa-star"></i>';
        $string = '';
        for($i=0;$i<$score;$i++){
            $string .= $icon;
        }
        return $string;
    }

    public function get_one($item_id, $type)
    {
        $this->db->where('item_id', $item_id);
        $this->db->where('type', $type);
        $query = $this->db->get('comment');
        $info = $query->row_array();
        return $info;
    }

    // public function get_one($id)
    // {
    //     $this->load->model('Order_model');
    //     $this->db->where('id', $id);
    //     $query = $this->db->get('comment');
    //     $info = $query->row_array();
    //     $order = $this->Order_model->get_one($info['order_id']);
    //     $info['order_num'] = $order['order_num'];
    //     $info['item_string'] = $this->get_item($info['item']);
    //     $info['type_name'] = ($info['type'] == 1) ? '客户对众包' : '众包对客户';
    //     $info['score_icon'] = $this->get_score_icon($info['score']);
    //     return $info;
    // }

    public function create($insert)
    {
        return $this->db->insert('comment', $insert);
    }

    public function update($data)
    {
        $info = $this->get_one($data['item_id'], $data['type']);
        if($info){
            $this->db->where('id', $info['id']);
            return $this->db->update('comment',$data);
        }else{
            return $this->create($data);
        }
    }

    public function get_data($family_id)
    {
        $this->load->model(array('Order_model','Field_model','Field_value_model'));
        $order_ids = $this->Order_model->get_ids_by_family($family_id);

        $this->db->where_in('order_id', $order_ids);
        $query = $this->db->get('comment');
        $comment = $query->result_array();

        $list = $this->get_item();
        //$list = array_slice($list, 4, 4);
        foreach($list as $key=>$value){
            if(in_array($key, array(5,6,7,8))){
                $new_list[$key] = $value;
            }
        }
        $item = array();
        foreach($new_list as $key=>$value){
            $item[$key] = 0;
            foreach($comment as $k2=>$v2){
                if(in_array($key, explode(',',$v2['item']))){
                    $item[$key]++;
                }
            }
        }
        $datasets = array(
            array(
                'label' => 'Comment',
                'fillColor' => 'rgba(220,220,220,0.5)',
                'strokeColor' => 'rgba(220,220,220,0.5)',
                'highlightFill' => 'rgba(220,220,220,0.5)',
                'highlightStroke' => 'rgba(220,220,220,0.5)',
                'data' => $item
            )
        );
        $data = array(
            'labels' => array_slice($list, 4,4),
            'datasets' => $datasets
        );
        return json_encode($data);
    }
}