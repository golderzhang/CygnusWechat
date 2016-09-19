<?php

    include 'accessToken.php';
    
    $service = new CustomService();
//     $kf_account = "service1@gh_7b7b2cf2773f";
    $kf_account = "service1@cygnus1314";
    $nickname = "客服1";
    $password = "123456";
    $result = $service->addAccount($kf_account, $nickname, $password);
    if ($result) {
        echo "success";
    } else {
        echo "failure";
    }
    
    /*
     * 客服接口
     */
    class CustomService {
        
        /*
         * 添加客服账号
         */
        public function addAccount($kf_account, $nickname, $password) {
            
            $net = new NetHandle();
            $token = new AccessToken();
            $access_token = $token->getAccessToken();
            
            $data = array("kf_account"=>$kf_account, "nickname"=>$nickname, "password"=>$password);
            $url = "https://api.weixin.qq.com/customservice/kfaccount/add?access_token=".$access_token;
            $response = $net->postData($url, $data);
            $resObj = json_decode($response, true);
            var_dump($resObj);
            echo "\n";
            if ($resObj['errcode'] == 0) {
                return true;
            } else {
                return false;
            }
        }
        
        /*
         * 修改客服账号
         */
        public function updateAccount($kf_account, $nickname, $password) {
            
            $net = new NetHandle();
            $token = new AccessToken();
            $access_token = $token->getAccessToken();
            
            $data = array("kf_account"=>$kf_account, "nickname"=>$nickname, "password"=>$password);
            $url = "https://api.weixin.qq.com/customservice/kfaccount/update?access_token=".$access_token;
            $response = $net->postData($url, $data);
            $resObj = json_decode($response, true);
            if ($resObj['errcode'] == 0) {
                return true;
            } else {
                return false;
            }
        }
        
        /*
         * 删除客服账号
         */
        public function deleteAccount($kf_account, $nickname, $password) {
            $net = new NetHandle();
            $token = new AccessToken();
            $access_token = $token->getAccessToken();
            
            $data = array("kf_account"=>$kf_account, "nickname"=>$nickname, "password"=>$password);
            $url = "https://api.weixin.qq.com/customservice/kfaccount/del?access_token=".$access_token;
            $response = $net->postData($url, $data);
            $resObj = json_decode($response, true);
            if ($resObj['errcode'] == 0) {
                return true;
            } else {
                return false;
            }
        }
        
        /*
         * 设置客服账号头像
         */
        public function uploadHeadImg($kf_account, $img_url) {
            
            $net = new NetHandle();
            $token = new AccessToken();
            $access_token = $token->getAccessToken();
            $url = "http://api.weixin.qq.com/customservice/kfaccount/uploadheadimg?access_token=".$access_token."&kf_account=".$kf_account;
            $response = $net->postData($url, array("media"=>"logo.jpg"));
            $result = json_decode($response, true);
            if ($result['errcode'] == 0) {
                return true;
            } else {
                return false;
            }
        }
    }
?>