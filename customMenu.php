<?php
    $menu = new CustomMenu();
    $menu->createMenu();

    class CustomMenu {
        
        /*
         *  创建菜单
         */
        public function createMenu() {
            
            include 'accessToken.php';
//             include 'netHandle.php';
            
            // 获取access_token并生成url
            $token = new AccessToken();
            $access_token = $token->getAccessToken();
            $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=%s";
            $url = sprintf($url, $access_token);
            
            $data = <<<p_d

            {
                "button": [
                    {
                        "name":"优惠活动",
                        "type":"click",
                        "key":"activity_1"
                    },
                    {
                        "name":"Cygnus",
                        "sub_button": [
                            {
                                "name":"主题婚纱",
                                "type":"click",
                                "key":"cygnus_1"
                            },
                            {
                                "name":"龙凤呈祥",
                                "type":"click",
                                "key":"cygnus_2"
                            },
                            {
                                "name":"气质礼服",
                                "type":"click",
                                "key":"cygnus_3"
                            },
                            {
                                "name":"精美配饰",
                                "type":"click",
                                "key":"cygnus_4"
                            }
                        ]
                    },
                    {
                        "name":"联系我们",
                        "sub_button": [
                            {
                                "name":"预约试纱",
                                "type":"click",
                                "key":"contact_1"
                            },
                            {
                                "name":"合作伙伴",
                                "type":"click",
                                "key":"contact_2"
                            }
                        ]
                    }
                ]    
            }
            
p_d;
            $net = new NetHandle();
            $response = $net->postData($url, $data);
            echo "<br/>";
            echo $response;
        }
    }
?>