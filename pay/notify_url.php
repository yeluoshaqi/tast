<?php
/**
 * 通用通知接口demo
 * ====================================================
 * 支付完成后，微信会把相关支付和用户信息发送到商户设定的通知URL，
 * 商户接收回调信息后，根据需要设定相应的处理流程。
 * 
 * 这里举例使用log文件形式记录回调信息。
*/
	include_once("./log_.php");
	include_once("../WxPayPubHelper/WxPayPubHelper.php");

    //使用通用通知接口
	$notify = new Notify_pub();

	//存储微信的回调
	$xml = $GLOBALS['HTTP_RAW_POST_DATA'];	
	$notify->saveData($xml);
	
	//验证签名，并回应微信。
	//对后台通知交互时，如果微信收到商户的应答不是成功或超时，微信认为通知失败，
	//微信会通过一定的策略（如30分钟共8次）定期重新发起通知，
	//尽可能提高通知的成功率，但微信不保证通知最终能成功。
	if($notify->checkSign() == FALSE){
		$notify->setReturnParameter("return_code","FAIL");//返回状态码
		$notify->setReturnParameter("return_msg","签名失败");//返回信息
	}else{
		$notify->setReturnParameter("return_code","SUCCESS");//设置返回码
	}
	$returnXml = $notify->returnXml();
	echo $returnXml;
	
	//==商户根据实际情况设置相应的处理流程，此处仅作举例=======
	
	//以log文件形式记录回调信息
	$log_ = new Log_();
	$log_name="./notify_url.log";//log文件路径
	$log_->log_result($log_name,"【接收到的notify通知】:\n".$xml."\n");

	if($notify->checkSign() == TRUE)
	{
		if ($notify->data["return_code"] == "FAIL") {
			//此处应该更新一下订单状态，商户自行增删操作
			$log_->log_result($log_name,"【通信出错】:\n".$xml."\n");
		}
		elseif($notify->data["result_code"] == "FAIL"){
			//此处应该更新一下订单状态，商户自行增删操作
			$log_->log_result($log_name,"【业务出错】:\n".$xml."\n");
		}
		else{
			//此处应该更新一下订单状态，商户自行增删操作
            //更新订单状态
            $con = mysql_connect("localhost","dazhong","dazhong");
            if(!$con){
                die('Could not connect: ' . mysql_error());
            }
            mysql_select_db("dazhong", $con);
            mysql_query("UPDATE gh_orders SET pay_type = 1, status = 3 WHERE id = ".$notify->data['out_trade_no']);
            mysql_close($con);

            //获取access_token
            $grant_type = 'client_credential';
            $appid = '';
            $appsecret = '';
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type='.$grant_type.'&appid='.$appid.'&secret='.$appsecret;
            $ch = curl_init();//初始化curl
            curl_setopt($ch, CURLOPT_URL, $url);//抓取指定网页
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//关闭https验证
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($ch);
            curl_close($ch);
            //将获取到的内容json解码为类
            $data = json_decode($result, true);

            //向用户发送通知
            $ch = curl_init();
            $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$data['access_token'];
            $post = '{"touser":"OPENID","template_id":"ngqIpbwh8bUfcSsECmogfXcV14J0tQlEpBO27izEYtY","url":"http://weixin.qq.com/download","topcolor":"#FF0000","data":{
                   "first": {
                       "value":"您好，您已成功消费。",
                       "color":"#0A0A0A"
                   },
                   "keynote1":{
                       "value":"预约挂号",
                       "color":"#CCCCCC"
                   },
                   "keynote2": {
                       "value":"'.$notify->data['total_fee'].'元",
                       "color":"#CCCCCC"
                   },
                   "keynote3":{
                       "value":"'.date('Y年m月d日', $notify->data['time_end']).'",
                       "color":"#CCCCCC"
                   },
                   "remark":{
                       "value":"欢迎再次购买。",
                       "color":"#173177"
                   }}}';
            curl_setopt($ch, CURLOPT_URL, $url);//设置链接
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//关闭https验证
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//设置是否返回信息
            curl_setopt($ch, CURLOPT_POST, 1);//设置为POST方式
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);//POST数据
            $response = curl_exec($ch);//接收返回信息
            curl_close($ch);
            
			$log_->log_result($log_name,"【支付成功】:\n".$xml."\n");
		}
		
		//商户自行增加处理流程,
		//例如：更新订单状态
		//例如：数据库操作
		//例如：推送支付完成信息
	}
?>