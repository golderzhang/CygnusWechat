<?php

    include 'netHandle.php';

    define("appID", "wx621f179d28795704");
    define("appsecret", "a063c5b1d7765ac5ff8f4b47c099f94b");
    
    /* 
     * Token相关类
     */
    class AccessToken {
        
        /* 
         * 获取token
         */
        public function getAccessToken() {
            
            
            // 获取access_token的URL
            $url_string = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s";
            $url = sprintf($url_string, appID, appsecret);

            date_default_timezone_set("Asia/Shanghai");
            
            $handle = new NetHandle();
            $jsonObj = $handle->getData($url);
            $jsonArr = json_decode($jsonObj, true);
            
            if (isset($jsonArr["access_token"])) {
                $access_token = $jsonArr["access_token"];
                $expires_in = $jsonArr["expires_in"];
                return $access_token;
            } else {
                return "";
            }
        }
        
        /* Token 是否有效
         * start_time: 起始日期
         * expire: 有效时常(秒)
         */
        private function isValid($start_time, $expire) {
            
        }
        
    }
?>