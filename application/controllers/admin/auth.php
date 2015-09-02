<?php
class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('User_model');
        $this->load->helper('url');
    }

    public function login()
    {
        $this->load->library('form_validation');
        $data = array();
        if($this->input->post()){
            $this->form_validation->set_error_delimiters('<small class="error">', '</small>');
            $this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[4]');
            $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|md5');

            if($this->form_validation->run()){
                $name = $this->input->post('name');
                $password = $this->input->post('password');
                $result = $this->User_model->validate($name, $password);
                if($result){
                    $data = array(
                        'is_login' => 1,
                        'user_id'  => $result['id'],
                        'group_id' => $result['group_id'],
                    );
                    $this->session->set_userdata($data);
                    redirect('admin');
                }
                $data['error_msg'] = '用户名或密码错误!';
            }
        }
        $this->load->view('admin/public/login.html', $data);
    }
}