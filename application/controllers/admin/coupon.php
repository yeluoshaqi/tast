<?php
class Coupon extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Icouponl_model');
        $this->load->model('Icoupons_model');
        $this->load->model('Member_model');
    }

    public function index()
    {
        $offset = 0;$len = 10;
        $data['list'] = $this->Icouponl_model->gets($offset,$len);
        $this->load->view('admin/coupon/index.html',$data);
    }

    public function create_icoupons()
    {
         $this->load->library('form_validation');
        $post_date = $this->input->post();
       if(empty($post_date['title'])){
            $this->load->view('admin/coupon/create_icoupons.html');
        }else{
            $post_date['create_time'] = time();
            $res = $this->Icouponl_model->set($post_date);
            if($res){
                echo "<script>alert('添加成功');</script>";
                redirect('admin/coupon');
            }
        }
    }
    public function update_icoupons(){
        $post_date = $this->input->post();
        if(empty($post_date)){             //show
            $this->load->library('form_validation');
            $cid = $this->uri->segment(4);
            $data = $this->Icouponl_model->get(array('id'=>$cid));
            $this->load->view('admin/coupon/create_icoupons.html',$data);
        }else{
            $post_date['updated_at'] =  date("Y-m-d H:i:s",time());
            $res = $this->Icouponl_model->update(array('id'=>$post_date['id']),$post_date);
            if($res){
                redirect('admin/coupon');
            }
        }
    }

    public function create_coupon(){
        $post_date = $this->input->post();
        if(empty($post_date)){
            $this->load->library('form_validation');
            $data['cid'] = $this->uri->segment(4);
            $this->load->view('admin/coupon/create_coupon.html',$data);
        }else{
            $nub = $post_date['nub'];
            $cid = $post_date['cid'];
            $coupon = $this->Icouponl_model->get(array('id'=> $cid));
            var_dump($coupon);
            var_dump($nub,$cid);exit;
            $i = 0;
            while ( $i<= $nub) {
                $i++;

            }
            var_dump($nub);
        }
    }
}