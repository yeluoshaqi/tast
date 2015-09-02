<?php
class Service_log extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Service_log_model');
    }

    public function index()
    {
        $list = array(
            array('服务记录')
        );
        $data['breadcrumbs'] = create_breadcrumbs($list);

        $this->load->library('pagination');
        $config['base_url'] = site_url().'/admin/service_log';
        $config['per_page'] = 10;
        $page = $this->uri->segment(3,1);
        $offset = ($page-1)*$config['per_page'];
        $config['total_rows'] = $this->Service_log_model->get_count();
        $this->pagination->initialize($config);

        $data['list'] = $this->Service_log_model->get_list($offset, $config['per_page']);
        $this->load->view('admin/service_log/index.html', $data);
    }

    public function view()
    {
        $this->load->model('Field_value_model');
        $list = array(
            array('服务记录', 'admin/service_log'),
            array('服务记录详情')
        );
        $data['breadcrumbs'] = create_breadcrumbs($list);

        $id = $this->uri->segment(4);
        $data['info'] = $this->Service_log_model->get_one($id);
        $data['fields'] = $this->Field_value_model->get_list($id);
        $this->load->view('admin/service_log/view.html', $data);
    }
}