<?php
class Index extends MY_Controller
{
    public function __construct()
    {
        parent::__construct('mobile');
        $this->load->model(array('Service_model', 'Parent_model'));
        $this->member_id = $this->session->userdata['member_id'];
    }

    public function index()
    {
        $this->load->view('mobile/index/index.html');
    }

    public function service()
    {   $this->load->model('Icoupons_model');
        $data['service'] = $this->Service_model->get_list(2);
        $data['family'] = $this->Parent_model->get_list_by_member($this->member_id);
        $data['coupons'] = $this->Icoupons_model->gets(array('fan_id'=> $this->member_id));
        $this->load->view('mobile/index/service.html', $data);
    }

    public function pay()
    {
        $this->load->model(array('Service_model','Parent_model','Order_item_model','Icoupons_model'));
        if($this->input->post()){
            $post = $this->input->post();
            $service = $this->Service_model->get_one($post['service_id']);
            $family = $this->Parent_model->get_one($post['family_id']);
            $coupon = $this->Icoupons_model->get(array('fan_id'=> $this->member_id,'id'=>$post['coupon_id']));
            $order_insert = array(
                'member_id' => $this->member_id,
                'service_id' => $post['service_id'],
                'type' => 1,
                'fee' => $service['price']-$coupon,
                'status' => 1,
                'coupon_id' => $post['coupon_id'],
                'coupon_price' => $coupon['coupon_price'],
                'order_num' => date("Ymd").rand(1000,9999).'w',
            );

            $this->load->model(array('Order_model','Order_item_model'));
            $order_id = $this->Order_model->create($order_insert);
            if($order_id){
                $item_insert = array(
                    'order_id' => $order_id,
                    'family_id' => $post['family_id'],
                    'name' => $family['name'],
                    'mobile' => $family['mobile'],
                    'city' => $family['city'],
                    'address' => $family['address'],
                    'date' => $post['date'],
                    'time' => $post['time'],
                    'note' => $post['note'],
                    'motify_date' => date('Y-m-d H:i:s'),
                    'is_comment' => 0,
                    'status' => 1,
                );

                $date = $post['date'];
                $first_date = mktime(0,0,0,substr($date,5,2), substr($date,8,2), substr($date,0,4));

                for($i=0; $i<4; $i++){
                    $item_insert['num'] = date("Ymd").rand(1000,9999).($i+1);
                    $item_id = $this->Order_item_model->create($item_insert);
                    if($i==0){
                        $item_insert['date'] = date('Y-m-d', strtotime("+1 week",$first_date));
                    }else{
                        $item_insert['date'] = date('Y-m-d', strtotime("+".($i+1)." weeks",$first_date));
                    }

                }
                //redirect('mobile/success');
                redirect('api/pay/'.$order_id);

            }else{
                redirect('mobile/service');
            }
        }else{
            redirect('mobile/service');
        }
    }

    public function delete()
    {
        $id = $this->uri->segment(3);
        $this->load->model(array('Order_model', 'Order_item_model'));
        $this->Order_model->delete($id);
        $this->Order_item_model->delete_by_order($id);
        redirect('mobile/service');
    }

    public function success()
    {
        $this->load->view('mobile/index/success.html');
    }

    private function set_form_validation()
    {
        $this->form_validation->set_error_delimiters('<small class="error">', '</small>');
        $this->form_validation->set_rules('type', 'Type', 'trim|required');
    }

    public function message()
    {
        $this->load->library('form_validation');
        $this->load->model('Message_model');
        if($this->input->post()){
            $this->set_form_validation();
            if($this->form_validation->run()){
                $insert = $this->input->post();
                $insert['member_id'] = $this->member_id;
                if($this->Message_model->create($insert)){
                    //redirect('mobile/message/message_success');
                    redirect('mobile/member?wx='.$this->session->userdata['wx']);
                }
            }
        }
        $data['list'] = $this->Message_model->get_type();
        $this->load->view('mobile/index/message.html', $data);
    }

    public function message_success()
    {
        $this->load->view('mobile/index/message_success.html');
    }

    public function page()
    {
        $view = $this->uri->segment(3);
        $this->load->view('mobile/page/'.$view.'.html');
    }
}