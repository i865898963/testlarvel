<?php
/**
 * Created by PhpStorm.
 * User: lichang
 * Date: 2019/10/6
 * Time: 3:46 PM
 */
namespace App\Services\Classify;

interface IClassifyService
{
    // 获取分类列表
    public function getClassify($postData);

}