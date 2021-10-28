<?php
/**
 * Created by PhpStorm.
 * User: lichang
 * Date: 2019/9/19
 * Time: 2:11 PM
 */

namespace App\Http\Controllers;


class BaseController extends Controller
{
    /**
     * 成功返回值
     * @param string $msg
     * @param array $data
     * @param bool $needObject
     * @param string $code
     * @return string
     */
    public function success($msg = '请求成功', $data = [], $needObject = true, $code = '200')
    {
        return json_encode([
            'code' => "$code",
            'msg'  => $msg,
            'data' => !empty($data) ? $data : ($needObject ? (object)array() : [])
        ]);
    }

    /**
     * 失败返回值
     * @param string $code
     * @param string $msg
     * @param array $data
     * @return string
     */
    public function error($code = '', $msg = '', $data = [])
    {
        return json_encode([
           'code' => "$code",
           'msg'  => $msg,
           'data' => count($data) == 0 ? (object)array() : $data
        ]);
    }
}