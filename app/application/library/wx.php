<?php

/**
 * @Desc: 微信服务
 * @User: liuhui
 * @Date: 15-6-7 下午10:55
 */
class wx
{
    public $openId = null;
    //weixin conf
    private $_AppToken = 'Daenerys';
    private $_AppId = 'wxd3a921cb1ec69ba8';
    private $_AppSecret = 'd6c9624e769b924e3917af3bd287701b';

    //weixin api url
    private $_UrlToken = 'https://api.weixin.qq.com/cgi-bin/token?%s';//get access token
    private $_UrlCreateMenu = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=%s';
    private $_UrlUserInfo = 'https://api.weixin.qq.com/cgi-bin/user/info?%s';
    private $_urlUserGet = 'https://api.weixin.qq.com/cgi-bin/user/get?%s';

    public function __construct()
    {
        import(APPLICATION_PATH . '/application/library/netFunctions.php');
    }

    /**
     * 接受发送的POST DATA信息
     * */
    public function getInput()
    {
        //$input = $GLOBALS['HTTP_RAW_POST_DATA']; //不高效不安全不推荐
        $input = file_get_contents('php://input', 'r');//推荐
        $params = (array)simplexml_load_string($input);
        return $params;
    }

    /**
     * 获取access token
     * */
    public function getToken()
    {
        $params['grant_type'] = 'client_credential';
        $params['appid'] = $this->_AppId;
        $params['secret'] = $this->_AppSecret;

        $url = sprintf($this->_UrlToken, http_build_query($params));
        $result = json_decode(getByUrl($url), true);
        //todo to storage access_token
        return $result['access_token'];
    }

    /**
     * 创建自定义的用户菜单
     * @apiurl: https://api.weixin.qq.com/cgi-bin/menu/create?access_token=ACCESS_TOKEN
     * */
    public function createMenu()
    {
        $query = array(
            'type' => 'click',
            'name' => '查天气',
            'key' => 'QUERY'
        );
        $aqi = array(
            'type' => 'click',
            'name' => '空气质量',
            'key' => 'AQI'
        );
        $download = array(
            'type' => 'view',
            'name' => '下载墨迹',
            'url' => 'http://wx.mojichina.com/download'
        );
        $result = array(
            'button' => array($query, $aqi, $download)
        );
        echo json_encode($result);
//

    }

//<xml>
//<ToUserName><![CDATA[toUser]]></ToUserName>
//<FromUserName><![CDATA[fromUser]]></FromUserName>
//<CreateTime>12345678</CreateTime>
//<MsgType><![CDATA[news]]></MsgType>
//<ArticleCount>2</ArticleCount>
//<Articles>
//<item>
//<Title><![CDATA[title1]]></Title>
//<Description><![CDATA[description1]]></Description>
//<PicUrl><![CDATA[picurl]]></PicUrl>
//<Url><![CDATA[url]]></Url>
//</item>
//<item>
//<Title><![CDATA[title]]></Title>
//<Description><![CDATA[description]]></Description>
//<PicUrl><![CDATA[picurl]]></PicUrl>
//<Url><![CDATA[url]]></Url>
//</item>
//</Articles>
//</xml>
    public function replyNews($openId, $articles)
    {
        $reply = array();
        $reply['ToUserName'] = $openId;
        $reply['FromUserName'] = 'gh_8082dd9a4785';
        $reply['CreateTime'] = time();
        $reply['MsgType'] = 'news';
        $reply['ArticleCount'] = count($articles);

        foreach ($articles as $v) {
            $item = array();
            $item['Title'] = $v['title'];
            $item['Description'] = $v['description'];
            $item['PicUrl'] = $v['pic_url'];
            $item['Url'] = $v['url'];
            $reply['Articles'][] = array('item' => $item);
        }
        echo $this->wrapXml($reply);
    }

    public function replyText($openId, $text)
    {
        $reply = array();
        $reply['ToUserName'] = $openId;
        $reply['FromUserName'] = 'gh_8082dd9a4785';
        $reply['CreateTime'] = time();
        $reply['MsgType'] = 'text';
        $reply['Content'] = htmlspecialchars($text);

        echo $this->wrapXml($reply);
    }

    private function wrapXml($data)
    {
        $result = '<xml>';
        $result .= $this->doWrapXml($data);
        $result .= '</xml>';
        return $result;
    }

    private function doWrapXml($data)
    {
        $result = '';
        foreach ($data as $k => $v) {
            if (is_array($v)) {
                if (is_int($k))
                    $result .= $this->doWrapXml($v);
                else
                    $result .= sprintf('<%s>%s</%s>', $k, $this->doWrapXml($v), $k);
            } else
                $result .= sprintf('<%s>%s</%s>', $k, $v, $k);
        }
        return $result;
    }
}