<?php

    include 'accessToken.php';
    include 'dbManager.php';
    
//     $user = new UserInfo();
//     $list = $user->getUserList();
//     $data = $list["data"];
    
//     $openid = $data["openid"];
//     echo $openid[0]."\n";
    
//     $userInfo = $user->getUserInfo($openid[0]);
//     var_dump($userInfo);
    
//     $user->insertUserInfo($userInfo);
    
    class UserInfo {
        
        /*
         * communicate with Wechat Server
         */
        
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
         * 获取用户列表
         * next_openid: 第一个拉取的OPENID，不填默认从头开始拉取
         */
        public function getUserList($next_openid=null) {
            
            $handle = new NetHandle();
            $access_token = new AccessToken();
            $token = $access_token->getAccessToken();
            
            $url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=".$token;
            if (!empty($next_openid)) {
                $url = $url."&next_openid=".$next_openid;
            }
            $response = $handle->getData($url);
            $list = json_decode($response, true);
            return $list;
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
         *  communicate with MySQL Database
         */
        
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
        
        /*
         * 用户是否已存储
         */
        public function isExistInDatabase($openid) {
            if (empty($openid)) {
                return false;
            }
            
            $condition = "openid = '{$openid}'";
            $result = $this->selectUserInfos($condition);
            if (count($result) > 0) {
                return true;
            } else {
                return false;
            }
        }
        
        /*
         * 删除用户
         */
        public function deleteUserInfos($condition) {
            if (empty($condition)) {
                return false;
            }
            
            $connect = mysqli_connect(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);
            if (mysqli_connect_errno($connect)) {
                echo "链接MySQL失败".mysqli_connect_error();
                return false;
            }
            
            $sql = "delete from user where ".$condition.";";
            $result = mysqli_query($connect, $sql);
            
            mysqli_close($connect);
            
            if (empty($result)) {
                return false;
            }
            return true;
        }
        
        /*
         * 更新用户信息
         */
        public function updateUserInfos($condition, $newUser) {
            if (empty($condition)) {
                return false;
            }
            
            $connect = mysqli_connect(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);
            if (mysqli_connect_errno($connect)) {
                echo "链接MySQL失败".mysqli_connect_error();
                return false;
            }
            $sql = "update user set subscribe='%s',openid='%s',nickname='%s',sex='%s',language='%s',city='%s',province='%s',country='%s',headimgurl='%s',subscribe_time='%s',unionid='%s',remark='%s',groupid='%s' where ".$condition.";";
            $sql = sprintf($sql, $newUser['subscribe'],$newUser['openid'],$newUser['nickname'],$newUser['sex'],$newUser['language'],$newUser['city'],$newUser['province'],$newUser['country'],$newUser['headimgurl'],$newUser['subscribe_time'],$newUser['unionid'],$newUser['remark'],$newUser['groupid']);
            $result = mysqli_query($connect, $sql);
            
            mysqli_close($connect);
            
            if (empty($result)) {
                return false;
            }
            
            return true;
        }
    }
?>