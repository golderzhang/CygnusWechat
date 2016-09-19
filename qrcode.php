<?php
    include 'accessToken.php';

    $code = new QRCode();
    $ticket = $code->createLimitTicket(123);
    
    $code->showQRCodeImg($ticket, 123);
    
    
    class QRCode {
        
        /*
         * 临时二维码
         */
        public function createTempTicket($scene_id) {
            $token = getToken();
            $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token={$token}";
            $jsonStr = '{"expire_seconds": 604800, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": '.$scene_id.'}}}';
            
            $response = request($url, true, $jsonStr);
            $result = json_decode($response, true);
            $ticket = $result['ticket'];
            return $ticket;
        }
        
        /*
         * 永久二维码
         */
        public function createLimitTicket($scene_id) {
            $token = getToken();
            $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token={$token}";
            $jsonStr = '{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id": '.$scene_id.'}}}';
            
            $response = request($url, true, $jsonStr);
            $result = json_decode($response, true);
            $ticket = $result['ticket'];
            return $ticket;
        }
        
        /*
         * 展示或下载二维码
         */
        public function showQRCodeImg($ticket, $scene_id) {
            if (isset($ticket) && (strlen($ticket) > 0)) {
                $token = getToken();
                $enTicket = urlencode($ticket);
                $url = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket={$enTicket}";
                
                $filename = "qr_".$scene_id.".jpeg";
                $response = download($url, $filename);
                
                return $response;
            }
        }
    }
?>