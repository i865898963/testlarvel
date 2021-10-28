<?php
/**
 * Created by PhpStorm.
 * User: liuSir
 * Date: 2020/7/13
 */

namespace App\Services\WxTools;


use App\Facades\Util\Common;
use App\Models\User\AdWxUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;
use Exception;

class WxTools
{

    private $appId;
    private $appSecret;
    private $accessToken;
    private $jsTicket;
    private $authUrl;

    public function __construct()
    {

        $this->appId = 'wxffdc2bd6f98f39ba';
        $this->appSecret = '79b6366d67a630966a4270b2516f0140';
        $this->authUrl = 'https://api.weixin.qq.com/sns/oauth2/access_token';
    }

    /**
     * 获取公共参数
     * User: liuSir
     * Date: 2020/7/13
     * @return array
     */
    protected function getPubParams()
    {
        $result = [
            'appId' => $this->appId,
            'timestamp' => Carbon::now()->timestamp,
            'nonceStr'  => $this->getNonceStr(),
        ];


        return $result;
    }


    /**
     *  生成签名的随机串
     * User: liuSir
     * Date: 2020/7/13
     */
    private function getNonceStr($length = 32)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    /**
     * 生成签名
     * User: liuSir
     * Date: 2020/7/13
     * @param $jsapiTicket
     * @param $nonceStr
     * @param $timestamp
     * @param $url
     * @return string
     */
    private function getSignatue($jsapiTicket,$nonceStr,$timestamp,$url)
    {
        $string = "jsapi_ticket=".$jsapiTicket."&noncestr=".$nonceStr."&timestamp=".$timestamp."&url=".$url;

        return sha1($string);
    }


    /**
     * 获取微信分享参数
     * User: liuSir
     * Date: 2020/7/13
     * @param $url
     * @return array
     * @throws Exception
     */
    public function getShareParams($url)
    {
        $pubParams = $this->getPubParams();
        $ticket = $this->getAccessToken()->getJspTicket();
        $pubParams['signature'] = $this->getSignatue($ticket,$pubParams['nonceStr'],$pubParams['timestamp'],$url);
        $pubParams['jsapi_ticket'] = $ticket;

        return $pubParams;
    }

    /**
     * 获取access_token
     * User: liuSir
     * Date: 2020/7/13
     * @return $this
     * @throws Exception
     */
    public function getAccessToken()
    {
       $accessToken = Redis::get('wx_access_token');

       if (!empty($accessToken)) {
           $this->accessToken = $accessToken;
           return $this;
       }

       $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
       $res = json_decode(Common::curl_request($url),true);
       $accessToken = $res['access_token'] ?? '';

       if (empty($accessToken)) throw new Exception('获取数据失败',1111);
       Redis::setEx("wx_access_token", 7100, $accessToken);
       $this->accessToken = $accessToken;

       return $this;
    }


    /**
     * 使用access_token，请求接口获取到jsapi_ticket
     * User: liuSir
     * Date: 2020/7/13
     * @return $this
     * @throws Exception
     */
    public function getJspTicket()
    {
        $ticket = Redis::get('wx_jsapi_ticket');

        if (!empty($ticket)) {
            $this->jsTicket = $ticket;
            return $this;
        }

        $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$this->accessToken";
        $res = json_decode(Common::curl_request($url),true);
        $ticket = $res['ticket'] ?? '';

        if (empty($ticket)) throw new Exception('获取数据失败',1111);

        $this->jsTicket = $ticket;

        return $ticket;
    }


    /**
     * 校验auth
     * User: liuSir
     * Date: 2020/7/13
     * @param $code
     * @return string
     */
    public function checkAuth($code)
    {
        $params = [
            'appid' => $this->appId,
            'secret' => $this->appSecret,
            'code'   => $code,
            'grant_type' => 'authorization_code'
        ];

        $requestUrl = $this->authUrl.'?'.http_build_query($params);

        $data = json_decode(Common::curl_request($requestUrl),true);

        $openId = $data['openid'] ?? '';

        return $openId;
    }
}