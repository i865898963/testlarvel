<?php
/**
 * Created by PhpStorm.
 * User: liuSir
 * Date: 2020/7/11
 */

namespace App\Services\AdminUser;

use App\Models\AdminUser\AdminUser;
use Illuminate\Support\Facades\Cache;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;

class AdminUserService implements IAdminUserService
{
    /**
     * 后台登录
     * User: liuSir
     * Date: 2020/7/11
     * @param string $account
     * @param string $password
     * @return array|mixed
     * @throws Exception
     */
    public function login($account, $password)
    {
        $user = AdminUser::where('account',$account)->first();

        // 帐号校验
        if (empty($user)) throw new Exception(config('error.user.1101'),1101);

        // 密码校验（此处可用加密对比）
        if ($user->password != $password) throw new Exception(config('error.user.1102'),1102);

        // 限制一个设备使用
        if ($lastToken = Cache::get('admin_user_'.$user->id)) {

            // token 有效则进行退出
            if (JWTAuth::setToken($lastToken)->check()) JWTAuth::setToken($lastToken)->invalidate();

            Cache::forget('admin_user_'.$user->id);
        }

        $token = auth('api')->login($user);
        Cache::forever('admin_user_'.$user->id,$token);

        $result = [
            'userId'    => $user->id,
            'token' => $token,
            'nickname' => $user->nickname
        ];

        return $result;
    }
}