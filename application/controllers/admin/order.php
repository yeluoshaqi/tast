<?php
class Order extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Order_model');
    }

    public function check_new()
    {
        $this->load->helper('file');
        $file = './message.log';
        $data = read_file($file);
        write_file($file, '');
        if($data){
            echo 1;
        }else{
            echo 0;
        }
    }

    public function index()
    {
        $list = array(
            array('订单列表')
        );
        $data['breadcrumbs'] = create_breadcrumbs($list);


        /** search **/
        if($this->input->post()){
            $search = $this->input->post();
            $this->session->set_userdata('order_search', $search);
        }else{
            if(isset($this->session->userdata['order_search'])){
                $search = $this->session->userdata['order_search'];
            }else{
                $search = array('id'=>'','order_num'=>'','name'=>'','mobile'=>'','status'=>0,'type'=>0,'is_pay'=>2,'is_comment'=>0);
            }
        }

        /** sort **/
        if($this->input->get()){
            $sort = $this->input->get();
            $this->session->set_userdata('order_sort', $sort);
        }else{
            if(isset($this->session->userdata['order_sort'])){
                $sort = $this->session->userdata['order_sort'];
            }else{
                $sort = array('sort'=>'create_date', 'type'=>'desc');
            }
        }

        $data['sort'] = $this->Order_model->get_sort($sort);
        $data['search'] = $search;
        $data['status_list'] = $this->Order_model->get_status();

        $this->load->library('pagination');
        $config['base_url'] = site_url().'/admin/order';
        $config['per_page'] = 10;
        $page = $this->uri->segment(3, 1);
        $offset = ($page-1) * $config['per_page'];
        $config['total_rows'] = $this->Order_model->get_count($search);
        $this->pagination->initialize($config);


        $data['list'] = $this->Order_model->get_list($offset, $config['per_page'],$search, $sort);
        $this->load->view('admin/order/index.html', $data);
    }

    public function create()
    {
        $this->load->library('form_validation');
        $this->load->model('Service_model');
        if($this->input->post()){
            $this->set_form_validation();
            if($this->form_validation->run()){
                $post = $this->input->post();
                $service = $this->Service_model->get_one($post['service_id']);
                $order_insert = array(
                    'service_id' => $post['service_id'],
                    'type' => 2,
                    'fee' => $service['price'],
                    'status' => 1,
                    'order_num' => time().rand(1000,9999),
                    'is_pay' => $post['is_pay'],
                );

                $order_id = $this->Order_model->create($order_insert);
                if($order_id){


                    $item_insert = array(
                        'order_id' => $order_id,
                        'family_id' => 0,
                        'name' => $post['name'],
                        'mobile' => $post['mobile'],
                        'address' => $post['address'],
                        'date' => $post['date'],
                        'time' => $post['time'],
                        'note' => $post['note'],
                        'motify_date' => date('Y-m-d H:i:s'),
                        'is_comment' => 0,
                        'status' => 1,
                    );

                    $date = $post['date'];
                    $first_date = mktime(0,0,0,substr($date,5,2), substr($date,8,2), substr($date,0,4));
                    $this->load->model(array('Order_item_model', 'Field_model', 'Field_value_model'));

                    //$field_list = $this->Field_model->get_list('service', $post['service_id']);
                    for($i=0; $i<4; $i++){
                        $item_insert['num'] = date("Ymd").rand(1000,9999).'m';
                        $item_id = $this->Order_item_model->create($item_insert);
                        // if($item_id){
                        //     foreach($field_list as $key=>$value){
                        //         $log = array(
                        //             'log_id' => $item_id,
                        //             'field_id' => $value['id'],
                        //             'value' => ''
                        //         );
                        //         $this->Field_value_model->create($log);
                        //     }
                        // }
                        if($i==0){
                            $item_insert['date'] = date('Y-m-d', strtotime("+1 week",$first_date));
                        }else{
                            $item_insert['date'] = date('Y-m-d', strtotime("+".($i+1)." weeks",$first_date));
                        }

                    }

                    $this->session->set_flashdata('result_code', 1);
                    $this->session->set_flashdata('result_msg', '添加成功');
                    redirect('admin/order');
                 }
            }
        }
        $list = array(
            array('订单列表', 'admin/order'),
            array('添加订单')
        );
        $data['breadcrumbs'] = create_breadcrumbs($list);

        $data['service_list'] = $this->Service_model->get_list(2);
        $this->load->view('admin/order/create.html', $data);
    }

    public function view()
    {
        $this->load->model('Service_log_model');
        $list = array(
            array('订单列表', 'admin/order'),
            array('订单详情')
        );
        $data['breadcrumbs'] = create_breadcrumbs($list);

        $id = $this->uri->segment(4);
        $data['info'] = $this->Order_model->get_one($id);
        $search = array('order_id'=>$id);
        $data['log_list'] = $this->Service_log_model->get_list(0,10,$search);
        $this->load->view('admin/order/view.html', $data);
    }

    private function set_form_validation()
    {
        $this->form_validation->set_error_delimiters('<small class="error">', '</small>');
        $this->form_validation->set_rules('name', 'Name', 'trim');
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|exact_length[11]');
        $this->form_validation->set_rules('nurse_id', 'Nurse Num', 'trim|is_natural_no_zero');
        $this->form_validation->set_rules('status', 'Status', 'trim|is_natural_no_zero');
    }

    public function update()
    {
        $this->load->library('form_validation');
        if($this->input->post()){
            $this->set_form_validation();
            $update = $this->input->post();
            $id = $update['id'];
            if($this->form_validation->run()){
                if($this->Order_model->update($update)){
                    $this->session->set_flashdata('result_code', 1);
                    $this->session->set_flashdata('result_msg', '编辑成功');
                    redirect('admin/order/view/'.$id);
                }
            }
        }else{
            $id = $this->uri->segment(4);
        }
        $data['status_list'] = $this->Order_model->get_status();
        $list = array(
            array('订单列表', 'admin/order'),
            array('订单详情', 'admin/order/view/'.$id),
            array('编辑订单')
        );
        $data['breadcrumbs'] = create_breadcrumbs($list);
        $data['info'] = $this->Order_model->get_one($id);
        $this->load->view('admin/order/update.html', $data);
    }
}