<?php
class Index extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
    }

    public function index()
    {
        $this->load->view('home/index/index.html');
    }

    public function join()
    {
        $this->load->view('home/index/join.html');
    }

    public function question()
    {
        $this->load->view('home/index/question.html');
    }

    public function contact()
    {
        $this->load->view('home/index/contact.html');
    }

    public function register()
    {
        $post = $this->input->post();
        $this->load->model('Register_model');
        if($this->Register_model->create($post)){
            echo 1;
        }else{
            echo 0;
        }
    }
}