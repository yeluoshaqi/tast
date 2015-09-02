<?php
class Api extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
    }

    public function wx()
    {
        $this->load->model(array('Api_model','Wx_model', 'Host_model'));
        $this->load->helper('url');
        include_once("./WxPayPubHelper/WxPayPubHelper.php");
        $wx_id = $this->input->get('wx');

        // $valid = $this->input->get('echostr');
        // echo $valid;exit;
        // $wx = $this->Wx_model->get_wx_by_id($wx_id);

        // $this->Api_model->set_parameters($wx['appid'], $wx['appsecret']);

        // if($valid){
        //     if($this->Api_model->checkSignature()){
        //         exit;
        //     }
        // }
        $wx = new Common_util_pub();
        $post_str = $GLOBALS['HTTP_RAW_POST_DATA'];
        $post = $wx->xmlToArray($post_str);
        $keyword = $this->Api_model->get_keyword($post);

        $result = array();
        if($keyword == '在线客服'){
            $result = array(
                'ToUserName' => $post['FromUserName'],
                'FromUserName' => $post['ToUserName'],
                'CreateTime' => time(),
                'MsgType' => 'transfer_customer_service'
            );
        }elseif($keyword == '关注' && $wx_id == 1){
            $result = array(
                'ToUserName' => $post['FromUserName'],
                'FromUserName' => $post['ToUserName'],
                'CreateTime' => time(),
                'MsgType' => 'news',
                'ArticleCount' => 1,
                'Articles' => array(
                    'item' => array(
                        'Title' => '用儿女般的爱，一起陪爸妈',
                        'Description' => '用儿女般的爱，一起陪爸妈',
                        'PicUrl' => 'http://www.ipeibama.com/foundation/images/momo.jpg',
                        'Url' => 'http://dwz.cn/ZVvxH',
                    ),
                )
            );

        }else{
            $route = $this->Host_model->get_host_by_keyword($keyword, $wx_id);
            if(!$route){
                $result = array(
                    'ToUserName' => $post['FromUserName'],
                    'FromUserName' => $post['ToUserName'],
                    'CreateTime' => time(),
                    'MsgType' => 'text',
                    'Content' => '你好'
                );
            }else{
                if($route['type'] == '文本回复'){
                    $this->load->model('Text_model');
                    $data = $this->Text_model->get_text_by_id($route['id']);
                    $result = array(
                        'ToUserName' => $post['FromUserName'],
                        'FromUserName' => $post['ToUserName'],
                        'CreateTime' => time(),
                        'MsgType' => 'text',
                        'Content' => $data['content']
                    );
                // }elseif($route['type'] == '图文回复'){
                //     $data = $this->News_model->get_one();
                //     $result = array(

                //     );
                }
            }
        }
        $result = $wx->arrayToXml($result);
        echo $result;
    }


    public function member()
    {
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model(array('Wx_model','Api_model', 'Member_model'));
        $code = $this->input->get('code');
        $return_uri = $this->input->get('state');
        if($code){
            $wx_id = $this->session->userdata('wx');
            $wx = $this->Wx_model->get_wx_by_id($wx_id);
            $this->Api_model->set_parameters($wx['appid'], $wx['appsecret'], $code);
            $user = $this->Api_model->get_user();

            $user['wx_id'] = $wx_id;
            $member = $this->Member_model->get_member_by_openid($user);

            $session_data = array(
                'wx_id' => $member['wx_id'],
                'openid' => $member['openid'],
                'member_id' => $member['id'],
            );
            $this->session->set_userdata($session_data);
            redirect($return_uri.'?wx='.$wx_id);
        }else{
            echo '未同意授权';exit;
        }
    }


    /***********************************************************************/

    public function check_date()
    {
        $this->load->model('Nurse_date_model');
        $post = $this->input->post();
        $result = $this->Nurse_date_model->check_date($post);
        if($result){
            echo 1;
        }else{
            echo 0;
        }
    }

    public function update_date_time()
    {
        $this->load->model('Nurse_date_model');
        $post = $this->input->post();
        if($post['action'] == 'create'){
            unset($post['action']);
            $result = $this->Nurse_date_model->create($post);
        }elseif($post['action'] == 'delete'){
            $result = $this->Nurse_date_model->delete($post);
        }elseif($post['action'] == 'check'){
            $result = $this->Nurse_date_model->check_time($post);
        }
        if($result){
            echo 1;
        }else{
            echo 0;
        }
    }

    public function get_city()
    {
        header("Access-Control-Allow-Origin:*");
        $id = $this->uri->segment(3,1);
        $this->load->model('City_model');
        $list = $this->City_model->get_json($id);
        echo $list;
    }

    public function get_city_name()
    {
        header("Access-Control-Allow-Origin:*");
        $id = $this->uri->segment(3,0);
        $this->load->model('City_model');
        $street = $this->City_model->get_one($id);
        if($street){
            $zone = $this->City_model->get_one($street['parent_id']);
            $city = $this->City_model->get_one($zone['parent_id']);
            echo $city['name'].','.$zone['name'].','.$street['name'];
        }else{
            echo '';
        }
    }

    public function get_data()
    {
        $id = $this->uri->segment(3);
        $this->load->model('Order_item_model');
        if($id){
            echo $this->Order_item_model->get_data($id);
        }
    }

    public function get_comment_data_by_family()
    {
        $id = $this->uri->segment(3);
        $this->load->model('Comment_model');
        if($id){
            echo $this->Comment_model->get_data($id);
        }
    }

    /****************************************************************/

    public function pay_notify()
    {
        include_once("./pay/log_.php");
        include_once("./WxPayPubHelper/WxPayPubHelper.php");

        $notify = new Notify_pub();

        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        $notify->saveData($xml);

        if($notify->checkSign() == FALSE){
            $notify->setReturnParameter("return_code","FAIL");//返回状态码
            $notify->setReturnParameter("return_msg","签名失败");//返回信息
        }else{
            $notify->setReturnParameter("return_code","SUCCESS");//设置返回码
        }
        $returnXml = $notify->returnXml();
        echo $returnXml;

        $log_ = new Log_();
        $log_name="./log/pay/".date('Ym').".log";//log文件路径
        $log_->log_result($log_name,"【接收到的notify通知】:\n".$xml."\n");

        if($notify->checkSign() == TRUE)
        {
            if ($notify->data["return_code"] == "FAIL") {
                $log_->log_result($log_name,"【通信出错】:\n".$xml."\n");
            }
            elseif($notify->data["result_code"] == "FAIL"){
                $log_->log_result($log_name,"【业务出错】:\n".$xml."\n");
            }
            else{
                $this->load->model('Order_model');
                $out_trade_no = substr($notify->data['out_trade_no'], 10);
                $this->Order_model->set_pay_status($out_trade_no, $notify->data['transaction_id']);
                $log_->log_result($log_name,"【支付成功】:\n".$xml."\n");
            }
        }
    }


    public function pay()
    {
        //@header("Content-Type: text/html; charset=utf-8");
        $id = $this->uri->segment(3);

        include_once("./WxPayPubHelper/WxPayPubHelper.php");
        $jsApi = new JsApi_pub();

        if (!isset($_GET['code']))
        {
            $url = $jsApi->createOauthUrlForCode('http://jjyl.edaixi.com/index.php/api/pay/'.$id);
            Header("Location: $url");
        }else{
            $code = $_GET['code'];
            $jsApi->setCode($code);
            $openid = $jsApi->getOpenId();
        }

        $this->load->model('Order_model');
        $order = $this->Order_model->get_one($id);


        $unifiedOrder = new UnifiedOrder_pub();
        $unifiedOrder->setParameter("openid",$openid);
        $unifiedOrder->setParameter("body","陪爸妈");
        $unifiedOrder->setParameter("out_trade_no",time().$order['id']);
        $unifiedOrder->setParameter("total_fee",$order['fee']*100);
        $unifiedOrder->setParameter("notify_url",'http://jjyl.edaixi.com/index.php/api/pay_notify');
        $unifiedOrder->setParameter("trade_type","JSAPI");
        $prepay_id = $unifiedOrder->getPrepayId();

        $jsApi->setPrepayId($prepay_id);
        $data['jsApiParameters'] = $jsApi->getParameters();
        $data['order'] = $order;
        //print_r($data);exit;
        $this->load->view('pay.html', $data);
    }

}
