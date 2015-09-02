<?php
class Api_model extends CI_Model
{
    var $appid;
    var $appsecret;
    var $code;
    var $token;
    var $access_token;
    var $openid;

    public function __construct()
    {
        parent::__construct();
    }

    public function set_parameters($appid, $appsecret, $code='')
    {
        $this->appid = $appid;
        $this->appsecret = $appsecret;
        $this->code = $code;
    }

    private function get_data($url)
    {
        $ch = curl_init();//初始化curl
        curl_setopt($ch, CURLOPT_URL, $url);//抓取指定网页
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//关闭https验证
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($result, true);
        return $result;
    }

    public function set_token()
    {
        $grant_type = 'client_credential';
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type='.$grant_type.'&appid='.$this->appid.'&secret='.$this->appsecret;
        $data = $this->get_data($url);
        $this->token = $data['access_token'];
    }

    /**
     * 将标准信息转化为统一关键字
     */
    public function get_keyword($post){
        if($post['Content']){
            /** 如果是文本信息 **/
            $keyword = $post['Content'];
        }elseif($post['MsgType'] == 'event'){
            /** 如果是事件信息 **/
            if($post['Event'] == 'subscribe'){
                //如果是关注事件
                $keyword = '关注';
            }elseif($post['Event'] == 'CLICK'){
                //如果是菜单点击事件
                $keyword = $post['EventKey'];
            }else{
                $keyword = $post['Event'];
            }
        }else{
            /** 如果是其他，则默认 **/
            $keyword = '默认';
        }
        return $keyword;
    }

    /**
     * 获取用户基本信息(UnionID机制)
     * @param string $access_token 微信TOKEN
     * @param string $openid 微信ID
     * @return array 用户信息数组
     */
    public function get_user_by_unionid($access_token, $openid)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
        $user = $this->get_curl($url);
        return $user;
    }

    /**
     * 网页授权获取用户详细信息
     */
    private function set_access_token()
    {
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$this->appid.'&secret='.$this->appsecret.'&code='.$this->code.'&grant_type=authorization_code';
        $result = $this->get_data($url);
        $this->access_token = $result['access_token'];
        $this->openid = $result['openid'];
    }

    public function get_user()
    {
        $this->set_access_token();
        $url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$this->access_token.'&openid='.$this->openid.'&lang=zh_CN';
        $result = $this->get_data($url);
        return $result;
    }

    private function refresh_token()
    {
        $url = 'https://api.weixin.qq.com/sns/oauth2/refresh_token?appid='.$appid.'&grant_type=refresh_token&refresh_token='.$result['refresh_token'];
        $result = $this->get_data($url);
        return $result;
    }

    private function check_token()
    {
        $url = 'https://api.weixin.qq.com/sns/auth?access_token=ACCESS_TOKEN&openid=OPENID';

    }

    public function checkSignature(){
        $signature = $this->input->get("signature");
        $timestamp = $this->input->get("timestamp");
        $nonce = $this->input->get("nonce");
        $this->set_token();
        $tmpArr = array($this->token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }


    /**
     * 获取请求客服所需的内容
     */
    public function get_transfer_customer($post)
    {
        $tpl = $this->get_tpl();
        $tpl .= '</xml>';
        $result = sprintf($tpl, $post->FromUserName, $post->ToUserName, time(), 'transfer_customer_service');
        return $result;
    }


    public function send_message($data)
    {
        $template_id = 'oEHrn6PNdzh5R22wzvEoF9bsZ9-M9FJhNTaX8iVaPDo';
        $link = 'http://weixin.96717120.com/index.php/orders/view/'.$data['order_id'];
        $ch = curl_init();
        $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$this->get_token();
        $data = array(
            "touser" => $data['open_id'],
            "template_id" => $template_id,
            "url" => $link,
            "topcolor" => $data['color'],
            "data" => array(
                "first" => array(
                    "value" => $data['title'],
                    "color" => "#OAOAOA"
                ),
                "keyword1" => array(
                    "value" => $data['patient'],
                    "color" => "#cccccc"
                ),
                "keyword2" => array(
                    "value" => $data['hospital'],
                    "color" => "#cccccc"
                ),
                "keyword3" => array(
                    "value" => $data['doctor'],
                    "color" => "#cccccc"
                ),
                "keyword4" => array(
                    "value" => $data['date'],
                    "color" => "#cccccc"
                ),
                "keyword5" => array(
                    "value" => $data['status'],
                    "color" => "#cccccc"
                ),
                "remark" => array(
                    "value" => $data['remark'],
                    "color" => "#cccccc"
                )
            )
        );
        $data = json_encode($data);
        curl_setopt($ch, CURLOPT_URL, $url);//设置链接
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//关闭https验证
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//设置是否返回信息
        curl_setopt($ch, CURLOPT_POST, 1);//设置为POST方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);//POST数据
        $result = curl_exec($ch);//接收返回信息
        curl_close($ch);
//        print_r($result);exit;
    }

    public function sync_menu($menu)
    {
        $this->set_token();
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$this->token;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POST, 1);//发送一个post请求
        curl_setopt($ch, CURLOPT_POSTFIELDS, $menu);//post提交的数据包
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);//设置限制时间
        curl_setopt($ch, CURLOPT_HEADER, 0);//显示返回的header区域内容
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//以文件流的形式获取
        $result = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($result, true);
        if($result['errcode'] == 0){
            return TRUE;
        }else{
            return FALSE;
        }
    }
}