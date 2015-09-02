<?php
class Service extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Service_model');
    }

    public function index()
    {
        $list = array(
            array('套餐管理')
        );
        $data['breadcrumbs'] = create_breadcrumbs($list);
        $data['list'] = $this->Service_model->get_list();
        $this->load->view('admin/service/index.html', $data);
    }

    private function set_form_validation()
    {
        $this->form_validation->set_error_delimiters('<small class="error">', '</small>');
        $this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[2]');
        $this->form_validation->set_rules('price', 'Price', 'trim|required');
    }

    public function create()
    {
        $this->load->library('form_validation');
        $data = array();
        if($this->input->post()){
            $this->set_form_validation();
            if($this->form_validation->run()){
                $insert = $this->input->post();
                if($this->Service_model->create($insert)){
                    $this->session->set_flashdata('result_code', 1);
                    $this->session->set_flashdata('result_msg', '添加成功');
                    redirect('admin/service');
                }
            }
        }
        $list = array(
            array('套餐管理', 'admin/service'),
            array('添加套餐')
        );
        $data['breadcrumbs'] = create_breadcrumbs($list);
        $this->load->view('admin/service/create.html', $data);
    }

    public function update()
    {
        $this->load->library('form_validation');
        if($this->input->post()){
            $this->set_form_validation();
            $update = $this->input->post();
            $id = $update['id'];
            if($this->form_validation->run()){
                if($this->Service_model->update($update)){
                    $data['result_code'] = 1;
                    $data['result_msg'] = '更新成功';
                }else{
                    $data['result_code'] = 0;
                    $data['result_msg'] = '更新失败';
                }
            }
        }else{
            $id = $this->uri->segment(4);
        }
        $list = array(
            array('套餐管理', 'admin/service'),
            array('编辑套餐')
        );
        $data['breadcrumbs'] = create_breadcrumbs($list);
        $data['info'] = $this->Service_model->get_one($id);
        $this->load->view('admin/service/update.html', $data);
    }

    public function delete()
    {
        $id = $this->uri->segment(4);
        if($id){
            if($this->Service_model->delete($id)){
                $this->session->set_flashdata('result_code', 1);
                $this->session->set_flashdata('result_msg', '删除成功');
                redirect('admin/service');
            }else{
                $this->session->set_flashdata('result_msg', '删除失败');
                redirect('admin/service/update/'.$id);
            }
        }
        redirect('admin/service');
    }
}