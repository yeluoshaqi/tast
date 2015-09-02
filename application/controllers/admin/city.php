<?php
class City extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('City_model');
    }

    public function index()
    {
        $list = array(
            array('城市管理')
        );
        $data['breadcrumbs'] = create_breadcrumbs($list);

        $this->load->library('pagination');
        $config['base_url'] = site_url().'/admin/city';
        $config['per_page'] = 10;
        $page = $this->uri->segment(3,1);
        $offset = ($page-1) * $config['per_page'];
        $config['total_rows'] = $this->City_model->get_count();
        $this->pagination->initialize($config);

        $data['list'] = $this->City_model->get_list($offset, $config['per_page']);
        $this->load->view('admin/city/index.html', $data);
    }

    public function create()
    {
        $this->load->library('form_validation');
        if($this->input->post()){
            $this->City_model->set_form_validation();
            if($this->form_validation->run()){
                $insert = $this->input->post();
                if($this->City_model->create($insert)){
                    $this->session->set_flashdata('result_code', 1);
                    $this->session->set_flashdata('result_msg', 'Create success');
                    redirect('admin/city');
                }else{
                    $data['result_code'] = 0;
                    $data['result_msg'] = 'create error';
                }
            }
        }
        $list = array(
            array('城市管理', 'admin/city'),
            array('添加城市')
        );
        $data['breadcrumbs'] = create_breadcrumbs($list);
        $this->load->view('admin/city/create.html', $data);
    }

    public function update()
    {
        $this->load->library('form_validation');
        if($this->input->post()){
            $update = $this->input->post();
            $id = $update['id'];
            $this->City_model->set_form_validation();
            if($this->form_validation->run()){
                if($this->City_model->update($update)){
                    $data['result_code'] = 1;
                    $data['result_msg'] = 'update success';
                }else{
                    $data['result_code'] = 0;
                    $data['result_msg'] = 'update error';
                }
            }
        }else{
            $id = $this->uri->segment(4);
        }
        $list = array(
            array('城市管理', 'admin/city'),
            array('编辑城市')
        );
        $data['breadcrumbs'] = create_breadcrumbs($list);
        $data['info'] = $this->City_model->get_one($id);
        $this->load->view('admin/city/update.html', $data);
    }
}