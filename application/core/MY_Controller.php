<?php
class MY_Controller extends CI_Controller
{
    public function __construct($group='admin')
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        if($group == 'admin'){
            if(!$this->session->userdata('is_login')){
                redirect('admin/login');
            }
        }else{
            $wx_id = $this->input->get('wx');
            if(!$wx_id){
                $wx_id = 1;
            }
            if($wx_id == '1'){
                $this->session->set_userdata('wx', 1);
                $this->session->set_userdata('member_id', 1);
            }
            if(!$this->session->userdata('member_id') OR !$this->session->userdata('wx') OR ($wx_id != $this->session->userdata['wx'])){
                $this->session->set_userdata('wx', $wx_id);

                $this->load->model('Wx_model');
                $wx = $this->Wx_model->get_wx_by_id($wx_id);
                $return_uri = urlencode(site_url().'/api/member?wx='.$wx_id);
                $scope = 'snsapi_userinfo';
                $state = urlencode($this->uri->uri_string());

                redirect('https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$wx['appid'].'&redirect_uri='.$return_uri.'&response_type=code&scope='.$scope.'&state='.$state.'#wechat_redirect');
            }
        }
    }



}