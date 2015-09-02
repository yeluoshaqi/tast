<?php
class Order_item extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Order_item_model');
    }

    public function index()
    {
        $list = array(
            array('服务列表')
        );
        $data['breadcrumbs'] = create_breadcrumbs($list);

        /** search **/
        if($this->input->post()){
            $search = $this->input->post();
            $this->session->set_userdata('order_item_search', $search);
        }else{
            if(isset($this->session->userdata['order_item_search'])){
                $search = $this->session->userdata['order_item_search'];
            }else{
                $search = array('num'=>'','name'=>'','mobile'=>'','status'=>0,'date'=>'','city'=>0);
            }
        }

        $data['search'] = $search;

        $this->load->library('pagination');
        $config['base_url'] = site_url().'/admin/order_item';
        $config['per_page'] = 10;
        $page = $this->uri->segment(3, 1);
        $offset = ($page-1) * $config['per_page'];

        $config['total_rows'] = $this->Order_item_model->get_count($search);
        
        $this->pagination->initialize($config);

        $data['list'] = $this->Order_item_model->get_list($offset, $config['per_page'],$search);
        $data['status_list'] = $this->Order_item_model->get_status();
        $this->load->view('admin/order_item/index.html', $data);

    }


    public function update()
    {
        $this->load->library('form_validation');
        $this->load->model(array('Field_value_model','Comment_model'));
        if($this->input->post()){
            $this->Order_item_model->set_form_validation();
            $update = $this->input->post();


            if($update['form'] == 'order_item'){
                /** if is order info update **/
                if(!$update['city']){
                    unset($update['city']);
                }
                unset($update['form']);
                $id = $update['id'];
                if($this->form_validation->run()){
                    $result = $this->Order_item_model->update($update);
                }

            }elseif($update['form'] == 'log'){
                /** if is the log update **/
                $id = $update['log_id'];
                $this->Order_item_model->update(array('id'=>$id,'entry_date'=>date('Y-m-d H:i:s')));
                foreach($update['log'] as $field_id => $value){
                    $result = $this->Field_value_model->update($update['log_id'], $field_id, $value);
                }
            }elseif($update['form'] == 'comment_1'){
                unset($update['form']);
                $id = $update['item_id'];
                $update['item'] = ($update['item']) ? implode(",", $update['item']) : '';
                $result = $this->Comment_model->update($update);
            }elseif($update['form'] == 'comment_2'){
                unset($update['form']);
                $id = $update['item_id'];
                $update['item'] = ($update['item']) ? implode(",", $update['item']) : '';
                $result = $this->Comment_model->update($update);
            }


            if($result){
                $this->session->set_flashdata('result_code', 1);
                $this->session->set_flashdata('result_msg', '更新成功');
            }else{
                $this->session->set_flashdata('result_code', 1);
                $this->session->set_flashdata('result_msg', '更新失败');
            }
            redirect('admin/order_item/');

        }else{
            $id = $this->uri->segment(4);
        }
        $list = array(
            array('服务列表', 'admin/order_item'),
            array('服务信息')
        );

        $this->load->model('Field_value_model');

        $data['breadcrumbs'] = create_breadcrumbs($list);
        $data['info'] = $this->Order_item_model->get_one($id);
        $data['fields'] = $this->Field_value_model->get_list('service',1,$id);
        $data['status_list'] = $this->Order_item_model->get_status();
        $data['comment_list'] = $this->Comment_model->get_item();
        $data['comment_1'] = $this->Comment_model->get_one($id, 1);
        $data['comment_2'] = $this->Comment_model->get_one($id, 2);
        $this->load->view('admin/order_item/update.html', $data);
    }

}
