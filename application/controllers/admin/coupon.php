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
        $this->load->library('pagination');
        $config['per_page'] = 5;
        $config['base_url'] = site_url().'/admin/coupon/index';
        $page = empty($this->uri->segment(4)) ? 1: $this->uri->segment(4);

        $offset = ($page-1) * $config['per_page'];
        $config['total_rows'] = $this->Icouponl_model->get_count();
        $this->pagination->initialize($config);

        $data['list'] = $this->Icouponl_model->gets($offset,$config['per_page']);
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

    public function bind_coupons(){
        $post_date = $this->input->post();
        if(empty($post_date)){
            $this->load->view('admin/coupon/bind_coupon.html',$data);
        }else{
            $this->load->helper('string');
            $cid = $post_date['coupon_cid'];
            $fan_id = $post_date['fan_id'];
            $coupon = $this->Icouponl_model->get(array('id'=> $cid));
            $sncode = random_string('numeric',15);
            $arr[] = array(
                'cid' => $cid,
                'sncode' => $sncode,
                'title'   => $coupon['title'],
                'fan_id'=> $fan_id,
                'order_sn' => '0',
                'coupon_price' => $coupon['coupon_price'],
                'used' => 0,
                'starttime'=> $coupon['starttime'],
                'endtime' => $coupon['endtime'],
                'lingqu_time' => time(),
                'create_time'=> time(),
                'updated_at'=> '0-0-0 0:0:0'
                ); 
            $res = $this->Icoupons_model->sets($arr); 
            redirect('admin/coupon');
        }
    }

    public function create_coupon(){
        $post_date = $this->input->post();
        if(empty($post_date)){
            $this->load->library('form_validation');
            $data['cid'] = $this->uri->segment(4);
            $this->load->view('admin/coupon/create_coupon.html',$data);
        }else{
            $this->load->helper('string');
            $nub = $post_date['nub'];
            $cid = $post_date['cid'];
            $coupon = $this->Icouponl_model->get(array('id'=> $cid));
            $i = 1;
            while ( $i<= $nub) {
                $i++;
                $sncode = random_string('numeric',15);
                $arr[] = array(
                    'cid' => $cid,
                    'sncode' => $sncode,
                    'title'   => $coupon['title'],
                    'fan_id'=> 0,
                    'order_sn' => $order_sn,
                    'coupon_price' => $coupon['coupon_price'],
                    'used' => 0,
                    'starttime'=> $coupon['starttime'],
                    'endtime' => $coupon['endtime'],
                    'lingqu_time' => 0,
                    'create_time'=> time(),
                    'updated_at'=> '0-0-0 0:0:0'
                    ); 
            }
            $res = $this->Icoupons_model->sets($arr);
        }
    }

    public function show_coupon(){
        $cid = $this->uri->segment(4);
        $this->load->library('pagination');
        $config['per_page'] = 5;
        $config['base_url'] = site_url().'/admin/coupon/show_coupon/'.$cid;
        $page = $this->uri->segment(5,1);
        $offset = ($page-1) * $config['per_page'];
        $config['total_rows'] = $this->Icoupons_model->get_count(array('cid'=>$cid));
        $this->pagination->initialize($config);
        $data['coupons'] = $this->Icoupons_model->gets(array('cid' => $cid),$offset,$config['per_page']);
        $this->load->view('admin/coupon/show_coupon.html',$data);
    }
}