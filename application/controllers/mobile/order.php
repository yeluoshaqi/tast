<?php
class Order extends MY_Controller
{
    public function __construct()
    {
        parent::__construct('order');
        $this->load->model('Order_model');
        $this->load->model('Order_item_model');
        $this->member_id = $this->session->userdata['member_id'];
        $this->wx = $this->session->userdata['wx'];
    }

    public function index()
    {
        $data['list'] = array();
        if($this->wx == 1){
            $orders = $this->Order_model->get_list(0,100,array('member_id'=>$this->member_id,'is_pay'=>2));
            $list = array();
            if($orders){
                foreach($orders as $key=>$value){
                    $list = array_merge($list, $this->Order_item_model->get_list(0,100,array('order_id'=>$value['id'])));
                }
                $data['list'] = $list;
            }
        }else{
            $data['list'] = $this->Order_item_model->get_list(0,10,array('nurse_id'=>$this->member_id));
        }


        $this->load->view('mobile/order/index.html', $data);
    }


    public function info()
    {
        $id = $this->uri->segment(4);
        if($id){
            $data['info'] = $this->Order_item_model->get_one($id);
            $this->load->view('mobile/order/info.html', $data);
        }
    }

    public function cancel()
    {
        $id = $this->uri->segment(4);
        if($id){
            $this->load->model('Order_item_model');
            if($this->Order_item_model->set_status($id, 7)){
                $this->session->set_flashdata('result_code', 1);
                $this->session->set_flashdata('result_msg', '取消成功');
                redirect('mobile/order?wx=1');
            }else{
                $this->session->set_flashdata('result_code', 1);
                $this->session->set_flashdata('result_msg', '取消失败');
                redirect('mobile/order/info/'.$id.'?wx=1');
            }

        }
    }

    public function comment()
    {
        if($this->input->post()){
            $this->load->model('Comment_model');
            $insert = $this->input->post();
            if($insert['item']){
                $insert['item'] = implode(",", $insert['item']);
            }
            if($this->Comment_model->create($insert)){
                //$this->load->model('Order_item_model');
                //$this->Order_item_model->set_status($insert['item_id'], 6);
                $this->session->set_flashdata('result_code', 1);
                $this->session->set_flashdata('result_msg', 'Success');
            }else{
                $this->session->set_flashdata('result_code', 1);
                $this->session->set_flashdata('result_msg', 'Error');
            }
            redirect('mobile/order/info/'.$insert['item_id'].'?wx='.$this->session->userdata['wx']);
        }else{
            $id = $this->uri->segment(4);
            if($id){
                $data['order_id'] = $id;
                $this->load->view('mobile/order/comment_'.$this->wx.'.html', $data);
            }
        }
    }

    public function entry()
    {
        $this->load->model('Field_value_model');
        if($this->input->post()){
            $insert = $this->input->post();
            $this->Order_item_model->update(array('id'=>$insert['item_id'],'entry_date'=>date('Y-m-d H:i:s'),'status'=>5));
            foreach($insert['log'] as $field_id => $value){
                $result = $this->Field_value_model->update($insert['item_id'], $field_id, $value);
            }
            if($result){
                $this->session->set_flashdata('result_code', 1);
                $this->session->set_flashdata('result_msg', 'Success');
            }else{
                $this->session->set_flashdata('result_code', 1);
                $this->session->set_flashdata('result_msg', 'Error');
            }
            redirect('mobile/order/info/'.$insert['item_id'].'?wx=2');
        }else{
            $id = $this->uri->segment(4);
            if($id){

                $data['list'] = $this->Field_value_model->get_list('service', 1, $id);
                $data['item_id'] = $id;
                $this->load->view('mobile/order/entry.html', $data);
            }
        }
    }

    public function set_status()
    {
        $item_id = $this->uri->segment(4);
        $status = $this->uri->segment(5);
        if($item_id OR $status){
            if($this->Order_item_model->set_status($item_id, $status)){
                $this->session->set_flashdata('result_code', 1);
                $this->session->set_flashdata('result_msg', '操作成功');
                redirect('mobile/order?wx=2');
            }
        }
    }
}