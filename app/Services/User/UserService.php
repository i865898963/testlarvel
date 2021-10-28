<?php
/**
 * Created by PhpStorm.
 * User: lichang
 * Date: 2019/9/19
 * Time: 2:27 PM
 */

namespace App\Services\User;


use App\Facades\Util\Common;
use App\Models\User\User;
use App\Services\Common\CommonService;
use Carbon\Carbon;
use Exception;

class UserService implements IUserService
{
    /**
     * 登陆凭证校验
     * @param $code
     * @return mixed
     * @throws \Throwable
     */
    public function getCode2Session($code)
    {
        $data = [
            'appid'      => config('wxAuto.AppID'),
            'secret'     => config('wxAuto.AppSecret'),
            'js_code'    => $code,
            'grant_type' => 'authorization_code'
        ];
        $url = config('wxAuto.code2Session');

        $wxData = $this->curlRequest($url,'GET',[],$data,true);

        if (empty($wxData)) throw new Exception(config('error.wxAuto.40000'),40000);

        if (isset($wxData['errcode']) && $wxData['errcode'] != 0) throw new Exception(config('error.wxAuto.40000'), 40000);

        try {
            $user = new User();
            $userInfo = $user->where('app_token',$wxData['openid'])->first();
            if (empty($userInfo)) {
                $user->app_token       = $wxData['openid'];
                $user->last_login_time = Carbon::now()->toDateString();
                $user->sex = 1;
                $user->saveOrFail();
            }
        } catch (Exception $e) {
            throw new Exception(config('error.user.1101'),1101);
        }

        $list = [
            'openid' => $wxData['openid']
        ];
        return $list;

    }

    /**
     * curl请求
     * @param $url
     * @param string $type
     * @param null $data
     * @param array $header
     * @param $isSSL
     * @return mixed
     */
    public function curlRequest($url,$type,$header = [],$data = null)
    {
        $type = strtoupper($type);

        if ($type == 'GET' && !empty($data)) {
            $url = $url.'?'.http_build_query($data);
        }
        //初始化curl
        $ch = curl_init();
        //参数设置
        $res = curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_HEADER, 0);
        // 查看请求头
        //curl_setopt($ch, CURLINFO_HEADER_OUT, true);

        switch ($type) {
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
                break;
            case 'PUT':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
                break;
            case 'GET':
                break;
            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
                break;
            default: return false;
        }

        if (!empty($header)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        // 查看请求头
        //       return curl_getinfo($ch, CURLINFO_HEADER_OUT);
        curl_close($ch);
        return json_decode($result,true);
    }



}