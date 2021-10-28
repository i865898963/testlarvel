<?php
/**
 * Created by PhpStorm.
 * User: lichang
 * Date: 2019/9/19
 * Time: 6:12 PM
 */

namespace App\Facades\Util;


use Illuminate\Support\Facades\Facade;

class Common extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'common';
    }
}