<?php

    function request($url, $post = false, $postdata = null) {
        $net = new NetHandle();
        
        if ($post) {
            $result = $net->postData($url, $postdata);
            return $result;
        } else {
            $result = $net->getData($url);
            return $result;
        }
    }
    
    function download($url, $filename) {
        if (empty($url)) {
            return "";
        }
    
        $ssl = substr($url, 0, 8) == "https://" ? true : false;
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_NOBODY, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    
        if ($ssl) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }
    
        $response = curl_exec($ch);
        curl_close($ch);
        
        if (!file_exists("./download")) {
            mkdir("./download");
        }
        file_put_contents("./download/".$filename, $response);
    
        return $response;
    }

    class NetHandle {
        
        /* HTTP GET方式请求数据
         * url: 请求的服务器地址
         */
        public function getData($url) {
            
            if (empty($url)) {
                return "";
            }
            
            $ssl = substr($url, 0, 8) == "https://" ? true : false;
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            
            if ($ssl) {
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            }
            
            $response = curl_exec($ch);
            curl_close($ch);
            
            return $response;
        }
        
        /* HTTP POST方式请求数据
         * url: 请求的服务器地址
         * data: 需要传送的数据，eg 关联数组
         */
        public function postData($url, $data) {
            
            if (empty($url)) {
                return "";
            }
            
            $ssl = substr($url, 0, 8) == "https://" ? true : false;
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            
            if ($ssl) {
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            }
            
            $response = curl_exec($ch);
            curl_close($ch);
            
            return $response;
        }
    }
?>