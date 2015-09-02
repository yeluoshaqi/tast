<?php
class Register extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Register_model');
    }

    public function index()
    {
        $list = array(
            array('注册管理')
        );
        $data['breadcrumbs'] = create_breadcrumbs($list);

        $this->load->library('pagination');
        $config['base_url'] = site_url().'/admin/register';
        $config['per_page'] = 10;
        $page = $this->uri->segment(3,1);
        $offset = ($page-1) * $config['per_page'];
        $config['total_rows'] = $this->Register_model->get_count();
        $this->pagination->initialize($config);

        $data['list'] = $this->Register_model->get_list($offset, $config['per_page']);
        $this->load->view('admin/register/index.html', $data);
    }

    public function view()
    {
        $list = array(
            array('注册管理', 'admin/register'),
            array('注册详情')
        );
        $data['breadcrumbs'] = create_breadcrumbs($list);
        $id = $this->uri->segment(4);
        $data['info'] = $this->Register_model->get_one($id);
        $this->load->view('admin/register/view.html', $data);
    }

    public function delete()
    {
        $id = $this->uri->segment(4);
        if($id){
            if($this->Register_model->delete($id)){
                $this->session->set_flashdata('result_code', 1);
                $this->session->set_flashdata('result_msg', '删除成功');
                redirect('admin/register');
            }else{
                $this->session->set_flashdata('result_msg', '删除失败');
                redirect('admin/register/view/'.$id);
            }
        }
        redirect('admin/register');
     }
}