<?php
class Comment extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Comment_model');
    }

    public function index()
    {
        $list = array(
            array('Comment List')
        );
        $data['breadcrumbs'] = create_breadcrumbs($list);

        $this->load->library('pagination');
        $config['base_url'] = site_url().'/admin/comment';
        $config['per_page'] = 10;
        $page = $this->uri->segment(3,1);
        $offset = ($page-1) * $config['per_page'];
        $config['total_rows'] = $this->Comment_model->get_count();
        $this->pagination->initialize($config);

        $data['list'] = $this->Comment_model->get_list($offset, $config['per_page']);
        $this->load->view('admin/comment/index.html', $data);
    }

    public function view()
    {
        $list = array(
            array('Comment List', 'admin/comment'),
            array('view comment')
        );
        $data['breadcrumbs'] = create_breadcrumbs($list);
        $id = $this->uri->segment(4);
        $data['info'] = $this->Comment_model->get_one($id);
        $this->load->view('admin/comment/view.html', $data);
    }
}