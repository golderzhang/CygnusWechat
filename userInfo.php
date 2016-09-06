<?php

    include 'accessToken.php';
    include 'dbManager.php';
    
    class UserInfo {
        
        /*
         * 获取用户基本信息(从微信服务器)
         */
        public function getUserInfo($openID) {
            
            $handle = new NetHandle();
            $access_token = new AccessToken();
            $token = $access_token->getAccessToken();
            
            $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token="."{$token}"."&openid="."{$openID}"."&lang=zh_CN";
            $response = $handle->getData($url);
            $userInfo = json_decode($response, true);
            return $userInfo;
        }
        
        /*
         * 设置用户备注名
         * openid: 用户标识
         * remark: 新的用户名，长度必须小于30
         * return: true or false
         */
        public function updateUserRemark($openid, $remark) {
            if (empty($openid) || empty($remark) || (strlen($remark) >= 30)) {
                return false;
            }
             
            $data = array("openid"=>$openid, "remark"=>$remark);
            $handle = new NetHandle();
            $access_token = new AccessToken();
            $token = $access_token->getAccessToken();
            $url = "https://api.weixin.qq.com/cgi-bin/user/info/updateremark?access_token=".$token;
        
            $response = $handle->postData($url, $data);
            $result = json_decode($response, true);
            if ($result["errcode"] == 0) {
                return true;
            } else {
                return false;
            }
        }
        
        /*
         * 将用户数据插入到数据库
         * return: true or false
         */
        public function insertUserInfo($userInfo) {
            	
            if (empty($userInfo)) {
                return false;
            }
            	
            $connect = mysqli_connect(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);
            if (mysqli_connect_errno($connect)) {
                echo "链接MySQL失败".mysqli_connect_error();
                return false;
            }
            	
            $sql = "insert into user(subscribe,openid,nickname,sex,language,city,province,country,headimgurl,subscribe_time,unionid,remark,groupid) values('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')";
            $sql = sprintf($sql, $userInfo['subscribe'],$userInfo['openid'],$userInfo['nickname'],$userInfo['sex'],$userInfo['language'],$userInfo['city'],$userInfo['province'],$userInfo['country'],$userInfo['headimgurl'],$userInfo['subscribe_time'],$userInfo['unionid'],$userInfo['remark'],$userInfo['groupid']);
            $result = mysqli_query($connect, $sql);
            	
            mysqli_close($connect);
            	
            if (empty($result)) {
                return false;
            }
            	
            return true;
        }
        
        /*
         * 从数据库中选择用户
         * condition: 筛选条件
         * return: 用户关联数组
         */
        public function selectUserInfos($condition=null) {
            	
            $connect = mysqli_connect(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);
            if (mysqli_connect_errno($connect)) {
                echo "链接MySQL失败".mysqli_connect_error();
                return null;
            }
            	
            $sql = "select * from user";
            if (!empty($condition)) {
                $sql = $sql." where ".$condition;
            }
            $sql = $sql.";";
            	
            $userArray = array();
            $result = mysqli_query($connect, $sql);
            while (is_array($user = mysqli_fetch_array($result))) {
                $userArray[] = $user;
            }
            mysqli_free_result($result);
            	
            return $userArray;
        }
        
        
    }
?>