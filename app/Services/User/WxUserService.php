<?php
/**
 * Created by PhpStorm.
 * User: liuSir
 * Date: 2020/7/13
 */

namespace App\Services\User;


use App\Models\User\AdWxUser;
use App\Services\WxTools\WxTools;

class WxUserService implements IWxUserService
{
    /**
     * 校验用户
     * User: liuSir
     * Date: 2020/7/13
     * @param $postData
     * @return mixed
     * @throws \Throwable
     */
    public function checkWxUser($postData)
    {
        $code = $postData['code'];
        $ip =   $postData['ip'];

        $wxTools = new WxTools();
        $openId = $wxTools->checkAuth($code);

        if (empty($openId)) throw new \Exception(config('error.common.1003'),1003);
        $wxUser = AdWxUser::where('open_id',$openId)->first();

        // 是否请求百度地图 1 请求百度地图 0 不请求
        $isAuth = 1;
        if (!empty($wxUser) && !empty($wxUser->province)) $isAuth = 0;

        if (empty($wxUser)) $wxUser = new AdWxUser();

        $wxUser->open_id = $openId;
        $wxUser->token = $this->getNewUserToken($openId); // 每次校验相当于登录一次 都重新生成token
        $wxUser->request_ip = $ip;
        $wxUser->saveOrFail();

        $result = [
            'token' => $wxUser->token,
            'isAuth' => $isAuth
        ];
        return $result;
    }

    /**
     * 获取用户token
     * User: liuSir
     * Date: 2020/7/14
     * @param $openId
     * @return string
     */
    public function getNewUserToken($openId)
    {
        return md5($openId . time() . 'CHENG_HUI' . rand(1000, 9999));
    }
}