<?php
/**
 * 发送模版消息
 * User: Marcus
 * Date: 18/11/1
 * Time: 13:49
 */

namespace Wx\Sdk;

$msg = new TempMsg($config);
/*
 * data=>array(
     'first'=>array('value'=>urlencode("您好,您已购买成功"),'color'=>"#743A3A"),
     'name1'=>array('value'=>urlencode("商品信息:微时代电影票"),'color'=>'#EEEEEE'),
     'name2'=>array('value'=>urlencode("商品信息:微时代电影票"),'color'=>'#EEEEEE'),
     'name3'=>array('value'=>urlencode("商品信息:微时代电影票"),'color'=>'#EEEEEE'),
     'remark'=>array('value'=>urlencode('永久有效!密码为:1231313'),'color'=>'#FFFFFF'),
 )
*/
echo $msg->doSend($touser, $template_id, $url, $data, $topcolor = '#7B68EE');
exit;

class TempMsg
{
    public $appid;
    public $secrect;
    public $accessToken;

    public function __construct($params)
    {
        $this->appid = $params->appID;
        $this->secrect = $params->appSecret;
        $this->accessToken = $this->getToken($this->appid, $this->secrect);
    }
    /**
     * 发送post请求
     * @param string $url
     * @param string $param
     * @return bool|mixed
     */
    public function request_post($url = '', $param = '')
    {
        if (empty($url) || empty($param)) {
            return false;
        }
        $ch = curl_init(); //初始化curl
        curl_setopt($ch, CURLOPT_URL, $url); //抓取指定网页
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        $data = curl_exec($ch); //运行curl
        curl_close($ch);
        return $data;
    }
    /**
     * 发送get请求
     * @param string $url
     * @return bool|mixed
     */
    public function request_get($url = '')
    {
        if (empty($url)) {
            return false;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
    /**
     * @param $appid
     * @param $appsecret
     * @return mixed
     * 获取token
     */
    public function getToken($appid, $secrect)
    {

        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $appid . "&secret=" . $secrect;
        $token = $this->request_get($url);
        $result = json_decode($token, true);
        return $result['access_token'];

    }
    /**
     * 发送自定义的模板消息
     * @param $touser
     * @param $template_id
     * @param $url
     * @param $data
     * @param string $topcolor
     * @return bool
     */
    public function doSend($touser, $template_id, $url, $data, $topcolor = '#000000')
    {
        /*
         * data=>array(
             'first'=>array('value'=>urlencode("您好,您已购买成功"),'color'=>"#743A3A"),
             'name'=>array('value'=>urlencode("商品信息:微时代电影票"),'color'=>'#EEEEEE'),
             'remark'=>array('value'=>urlencode('永久有效!密码为:1231313'),'color'=>'#FFFFFF'),
         )
        */
        $template = array(
            'touser' => $touser,
            'template_id' => $template_id,
            'url' => $url,
            'topcolor' => $topcolor,
            'data' => $data
        );
        $json_template = json_encode($template);
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $this->accessToken;

        $dataRes = $this->request_post($url, urldecode($json_template));
        $res = json_decode($dataRes, true);
        if ($res['errcode'] == 0) {
            return true;
        } else {
            return false;
        }
    }
}
