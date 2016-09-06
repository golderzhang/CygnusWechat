<?php
/**
  * wechat php test
  */

// Home Page
// http://cygnus1314.duapp.com/index.php

//define my token
define("TOKEN", "cygnus_test");

$wechatObj = new wechatCallbackapi();

if (isset($_GET["echostr"])) { // 如果有随机字符串,证明在验证URL
    $wechatObj->valid();
} else {                       // 否则，响应微信的消息
    $wechatObj->responseMsg();
}

class wechatCallbackapi
{
    // 验证服务器URL
	public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
        	echo $echoStr;
        	exit;
        }
    }
    
    // 校验签名
    private function checkSignature()
    {
        // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }
    
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
    
        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
    
        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

    // 响应消息
    public function responseMsg()
    {
		//get post data, May be due to the different environments
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

		// 将接收数据写入日志
		$this->createLog("C \n".$postStr);
		
      	//extract post data
		if (!empty($postStr)){
                /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
                   the best way is to check the validity of xml by yourself */
                libxml_disable_entity_loader(true);
              	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $msgType = $postObj->MsgType;

                $response = "";
                if ($msgType == "event") {
                    // 事件消息
                    $response = $this->responseEventMsg($postObj);
                } else {
                    // 普通消息
                    $response = $this->responseCommonMsg($postObj);
                }
                
                // 将返回数据写入日志
                $this->createLog("S \n".$response);
                
                echo  $response;
        }else {
        	echo "";
        	exit;
        }
    }
    
    // 响应普通消息
    private function responseCommonMsg($msgObject) {
        
        include 'commonMessage.php';
        
        $commonMsg = new CommonMsg();
        $response = $commonMsg->responseCommonMsg($msgObject);
        return $response;
    }
    
    // 响应事件推送
    private function responseEventMsg($msgObject) {
        
        include 'eventMessage.php';
        
        $eventMsg = new EventMsg();
        $response = $eventMsg->responseEventMsg($msgObject);
        return $response;
    }
		
    // 创建日志
	public function createLog($logContent) {
	    // 日志大小 10M
	    $max_size = 10000;
	    $log_filename = "wechatLog.xml";
	 
	    if (file_exists($log_filename) && abs(filesize($log_filename)) > $max_size) {
	        unlink($log_filename);
	    }
	    
	    // 写入日志
	    
	    date_default_timezone_set("PRC");
	    file_put_contents($log_filename, date("y:M:d H:i:s")." ".$logContent."\n\n", FILE_APPEND);
	}
}

?>