<?php
/**
 * Created by PhpStorm.
 * User: lichang
 * Date: 2019/9/19
 * Time: 2:27 PM
 */

namespace App\Services\User;


interface IUserService
{
    /**
     * 登陆凭证校验
     * @param $code
     * @return mixed
     */
    public function getCode2Session($code);
}