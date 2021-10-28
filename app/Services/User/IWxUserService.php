<?php
/**
 * Created by PhpStorm.
 * User: liuSir
 * Date: 2020/7/13
 */

namespace App\Services\User;


interface IWxUserService
{
    public function checkWxUser($postData);
}