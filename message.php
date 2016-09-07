<?php
    define("WECHAT_ID", "cygnus1314");

    $item1 = array("title"=>"新年快乐","description"=>"des","picurl"=>"www.baidu.com/pic1.jpg","url"=>"www");
    $itemArray = array($item1, $item1);
    
    $msgObj = new Message();
    $msg = $msgObj->createNewsMsg("zhangjinshi", $itemArray);
    echo $msg;
    
    class Message {
        
        /*
         * 开发者微信号
         */
        private function wechat_id() {
            return WECHAT_ID;
        }
        
        /*
         * 生成文本消息
         * content: 回复的消息内容
         * toUserName: 接收方帐号（收到的OpenID）
         */
        public function createTextMsg($content, $toUserName) {
            
            if (empty($content) || empty($toUserName)) {
                return "";
            }
            
            $msg = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[%s]]></Content>
</xml>";
            $msg = sprintf($msg, $toUserName, $this->wechat_id(), time(), $content);
            return $msg;
        }
        
        /*
         * 生成图片消息
         * media_id: 通过素材管理接口上传多媒体文件，得到的id
         * toUserName: 接收方帐号（收到的OpenID）
         */
        public function createImageMsg($media_id, $toUserName) {
            
            if (empty($media_id) || empty($toUserName)) {
                return "";
            }
            
            $msg = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[image]]></MsgType>
<Image>
<MediaId><![CDATA[%s]]></MediaId>
</Image>
</xml>";
            $msg = sprintf($msg, $toUserName, $this->wechat_id(), time(), $media_id);
            return $msg;
        }
        
        /*
         * 生成语音消息
         * media_id: 通过素材管理接口上传多媒体文件，得到的id
         * toUserName: 接收方帐号（收到的OpenID）
         */
        public function createVoiceMsg($media_id, $toUserName) {
            if (empty($media_id) || empty($toUserName)) {
                return "";
            }
            
            $msg = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[voice]]></MsgType>
<Voice>
<MediaId><![CDATA[%s]]></MediaId>
</Voice>
</xml>";
            $msg = sprintf($msg, $toUserName, $this->wechat_id(), time(), $media_id);
            return $msg;
        }
        
        /*
         * 生成视频消息
         * media_id: 通过素材管理接口上传多媒体文件，得到的id
         * toUserName: 接收方帐号（收到的OpenID）
         * title: 视频消息的标题
         * description: 视频消息的描述
         */
        public function createVideoMsg($media_id, $toUserName, $title, $description) {
            if (empty($media_id) || empty($toUserName)) {
                return "";
            }
            
            $tit = $title;
            $des = $description;
            if (empty($title)) {
                $tit = "";
            }
            if (empty($description)) {
                $des = "";
            }
            
            $msg = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[video]]></MsgType>
<Video>
<MediaId><![CDATA[%s]]></MediaId>
<Title><![CDATA[%s]]></Title>
<Description><![CDATA[%s]]></Description>
</Video> 
</xml>";
            $msg = sprintf($msg, $toUserName, $this->wechat_id(), time(), $media_id, $tit, $des);
            return $msg;
        }
        
        /*
         * 生成音乐消息
         * toUserName: 接收方帐号（收到的OpenID）
         * title: 音乐消息的标题
         * description: 音乐消息的描述
         * music_url: 音乐链接
         * hq_music_url: 高质量音乐链接，WIFI环境优先使用该链接播放音乐
         * thumb_media_id: 缩略图的媒体id，通过素材管理接口上传多媒体文件，得到的id
         */
        public function createMusicMsg($toUserName, $title, $description, $music_url, $hq_music_url, $thumb_media_id) {
            if (empty($thumb_media_id) || empty($toUserName) || $music_url || $hq_music_url) {
                return "";
            }
            
            $tit = $title;
            $des = $description;
            if (empty($title)) {
                $tit = "";
            }
            if (empty($description)) {
                $des = "";
            }
            
            $msg = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[music]]></MsgType>
<Music>
<Title><![CDATA[%s]]></Title>
<Description><![CDATA[%s]]></Description>
<MusicUrl><![CDATA[%s]]></MusicUrl>
<HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
<ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
</Music>
</xml>";
            $msg = sprintf($msg, $toUserName, $this->wechat_id(), time(), $tit, $des, $music_url, $hq_music_url, $thumb_media_id);
            return $msg;
        }
        
        /*
         * 生成图文消息，消息最多8个
         * toUserName: 接收方帐号（收到的OpenID）
         * itemArray: 图文消息数组，数组内每项为关联数组，需要包含title(图文消息标题)，description(图文消息描述)，picurl(图片链接), url(点击图文消息跳转链接)
         */
        public function createNewsMsg($toUserName, $itemArray) {
            if (empty($toUserName) || empty($itemArray)) {
                return "";
            }
            
            $msg = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[news]]></MsgType>
<ArticleCount>%s</ArticleCount>
<Articles>";
            $msg = sprintf($msg, $toUserName, $this->wechat_id(), time(), count($itemArray));
            foreach ($itemArray as $item) {
                $msg = $msg."<item>";
                $msg = $msg."<Title><![CDATA[%s]]></Title>
<Description><![CDATA[%s]]></Description>
<PicUrl><![CDATA[%s]]></PicUrl>
<Url><![CDATA[%s]]></Url>";
                $msg = sprintf($msg, $item['title'], $item['description'], $item['picurl'], $item['url']);
                $msg = $msg."</item>";
            }
            $msg = $msg."</Articles></xml>";
            return $msg;
        }
    }
?>