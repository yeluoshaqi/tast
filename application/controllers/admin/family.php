<?php
class Family extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Parent_model');
    }

    public function index()
    {
        $list = array(
            array('家人列表')
        );
        $data['breadcrumbs'] = create_breadcrumbs($list);

        $this->load->library('pagination');
        $config['base_url'] = site_url().'/admin/family';
        $config['per_page'] = 10;
        $page = $this->uri->segment(3,1);
        $offset = ($page-1)*$config['per_page'];
        $config['total_rows'] = $this->Parent_model->get_count();
        $this->pagination->initialize($config);

        $data['list'] = $this->Parent_model->get_list($offset, $config['per_page']);
        $this->load->view('admin/family/index.html', $data);
    }

    public function view()
    {
        $list = array(
            array('家人列表', 'admin/family'),
            array('健康信息')
        );
        $data['breadcrumbs'] = create_breadcrumbs($list);

        $id = $this->uri->segment(4);
        $data['info'] = $this->Parent_model->get_one($id);
        $this->load->view('admin/family/view.html', $data);
    }
}