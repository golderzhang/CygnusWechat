<?php

    include 'userInfo.php';

    class EventMsg {
        
        /* 统一管理函数
         * msgObj: 微信POST的XML对象
         */
        public function responseEventMsg($msgObj) {
            $response = ""; // 返回结果
            $event = $msgObj->Event;
            if (!empty($event)) {
                switch ($event) {
                    case "subscribe": // 订阅，eg 普通关注，扫描带参数二维码关注
                        $response = $this->responseSubscribeMsg($msgObj);
                        break;
                    case "unsubscribe": // 取消订阅
                        $response = $this->responseUnsubscribeMsg($msgObj);
                        break;
                    case "SCAN":  // 已关注条件下，扫描带参数二维码
                        $response = $this->responseSCANMsg($msgObj);
                        break;
                    case "LOCATION": // 上报地理位置
                        $response = $this->responseLOCATIONMsg($msgObj);
                        break;
                    case "CLICK": // 点击自定义菜单拉取消息
                        $response = $this->responseCLICKMsg($msgObj);
                        break;
                    case "VIEW": // 点击菜单跳转链接
                        $response = $this->responseVIEWMsg($msgObj);
                        break;
                    default :
                        $response = "不能识别的事件类型";
                        break;
                }
            }
            return $response;
        }
        
        /* 订阅，eg 普通关注，扫描带参数二维码关注
         * msgObj: 微信POST的XML对象
         */
        public function responseSubscribeMsg($msgObj) {
            $toUserName = $msgObj->ToUserName;
            $fromUserName = $msgObj->FromUserName; // 发送方账号,openID
            $createTime = $msgObj->CreateTime;
            $eventKey = $msgObj->EventKey; // 事件KEY值，qrscene_为前缀，后面为二维码的参数值
            if (isset($eventKey)) {
                // 扫描带参数二维码关注
                
                $ticket = $msgObj->Ticket; // 二维码的ticket，可用来换取二维码图片
            } else {
                // 普通关注
            }
            
            // 将用户数据存入数据库
            $userInfo = new UserInfo();
            $user = $userInfo->getUserInfo($fromUserName);
            if ($userInfo->isExistInDatabase($fromUserName)) { // 用户已存在（关注又取消，再关注的用户）
                // 更新数据库
                $userInfo->updateUserInfos("openid='{$fromUserName}'", $user);
            } else {
                // 存入数据库
                $userInfo->insertUserInfo($user);
            }
            
            $time = time();
            $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";
            return "订阅";
        }
        
        /* 取消订阅
         * msgObj: 微信POST的XML对象
         */
        public function responseUnsubscribeMsg($msgObj) {
            return "";
        }
        
        /* 已关注条件下，扫描带参数二维码
         * msgObj: 微信POST的XML对象
         */
        public function responseSCANMsg($msgObj) {
            return "";
        }
        
        /* 上报地理位置
         * msgObj: 微信POST的XML对象
         */
        public function responseLOCATIONMsg($msgObj) {
            return "";
        }
        
        /* 点击自定义菜单拉取消息
         * msgObj: 微信POST的XML对象
         */
        public function responseCLICKMsg($msgObj) {
            return "";
        }
        
        /* 点击菜单跳转链接
         * msgObj: 微信POST的XML对象
         */
        public function responseVIEWMsg($msgObj) {
            return "";
        }
    }
?>