<?php
class Index extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        redirect('admin/order_item');
        //$this->load->view('admin/index/index.html');
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('admin/login');
    }
}