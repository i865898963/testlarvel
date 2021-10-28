<?php
/**
 * Created by PhpStorm.
 * User: liuSir
 * Date: 2020/7/11
 */

namespace App\Services\AdminUser;


interface IAdminUserService
{
    /**
     * 登录
     * User: liuSir
     * Date: 2020/7/11
     * @param $account string 帐号
     * @param $password string 密码
     * @return mixed
     */
    public function login($account,$password);
}