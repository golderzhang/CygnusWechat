<?php

    class CommonMsg {
        
        /* 统一管理函数
         * msgObj: 微信POST的XML对象
         */
        public function responseCommonMsg($msgObj) {
            $response = ""; // 响应结果
            $msgType = $msgObj->MsgType; // 消息类型
          
            switch ($msgType) {
                case "text": // 文本消息
                    $response = $this->responseTextMsg($msgObj);
                    break;
                case "image": // 图片消息
                    $response = $this->responseImageMsg($msgObj);
                    break;
                case "voice": // 语音消息
                    $response = $this->responseVoiceMsg($msgObj);
                    break;
                case "video": // 视频消息
                    $response = $this->responseVideoMsg($msgObj);
                    break;
                case "shortvideo": // 小视频消息
                    $response = $this->responseShortVideoMsg($msgObj);
                    break;
                case "location": // 地理位置消息
                    $response = $this->responseLocationMsg($msgObj);
                    break;
                case "link": // 链接消息
                    $response = $this->responseLinkMsg($msgObj);
                    break;
                default:
                    $response = "不能识别的消息类型";
                    break;
            }
            return $response;
        }
        
        /* 文本消息
         * msgObj: 微信POST的XML对象
         */
        public function responseTextMsg($msgObj) {
            $fromUsername = $msgObj->FromUserName;
            $toUsername = $msgObj->ToUserName;
            $keyword = trim($msgObj->Content); // 关键字
            $time = time();
            $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";
            if(!empty( $keyword ))
            {
                $msgType = "text";
                $contentStr = "欢迎来到Cygnus";
                $response = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
            }else{
                $response = "";
            }
            return $response;
        }
        
        /* 图片消息
         * msgObj: 微信POST的XML对象
         */
        public function responseImageMsg($msgObj) {
            return "";
        }
        
        /* 语音消息
         * msgObj: 微信POST的XML对象
         */
        public function responseVoiceMsg($msgObj) {
            return "";
        }
        
        /* 视频消息
         * msgObj: 微信POST的XML对象
         */
        public function responseVideoMsg($msgObj) {
            return "";
        }
        
        /* 小视频消息
         * msgObj: 微信POST的XML对象
         */
        public function responseShortVideoMsg($msgObj) {
            return "";
        }
        
        /* 地理位置消息
         * msgObj: 微信POST的XML对象
         */
        public function responseLocationMsg($msgObj) {
            return "";
        }
        
        /* 链接消息
         * msgObj: 微信POST的XML对象
         */
        public function responseLinkMsg($msgObj) {
            return "";
        }
    }
?>