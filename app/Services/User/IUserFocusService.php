<?php
/**
 * Created by PhpStorm.
 * User: liuSir
 * Date: 2020/7/11
 */

namespace App\Services\User;


interface IUserFocusService
{
    /**
     * 用户监控列表
     * User: liuSir
     * Date: 2020/7/11
     * @param $postData
     * @return mixed
     */
    public function getUserFocusList($postData);
}