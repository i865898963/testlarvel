<?php
/**
 * Created by PhpStorm.
 * User: liuSir
 * Date: 2020/7/13
 */

namespace App\Http\Controllers\H5\User;


use App\Http\Controllers\BaseController;
use App\Services\User\IWxUserService;
use App\Services\WxTools\WxTools;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class WxUserController extends BaseController
{

    private $wxUserService;

    public function __construct(IWxUserService $wxUserService)
    {
        $this->wxUserService = $wxUserService;
    }


    /**
     * 登录授权
     * User: liuSir
     * Date: 2020/7/13
     * @param Request $request
     * @return string
     */
    public function checkLogin(Request $request)
    {
        $postData = $request->all();

        $validator = Validator::make($postData,[
            'code' => 'required|string'
        ]);


        if ($validator->fails()) return $this->error(1001,config('error.common.1001'));

        $postData['ip'] = $request->getClientIp();
        try {
          $data = $this->wxUserService->checkWxUser($postData);
        } catch (Exception $e) {
            return $this->error($e->getCode(),$e->getMessage());
        }

        return $this->success('',$data);
    }

    /**
     * 分享参数
     * User: liuSir
     * Date: 2020/7/13
     * @param Request $request
     * @return string
     */
    public function shareParams(Request $request)
    {
       $postData = $request->all();

       $validator = Validator::make($postData,[
           'url' => 'required|string'
       ]);


       if ($validator->fails()) return $this->error(1001,config('error.common.1001'));

       try {
           $wxTools = new WxTools();
           $url = urldecode($postData['url']);

           $data = $wxTools->getShareParams($url);
       } catch (Exception $e) {
           return $this->error($e->getCode(),$e->getMessage());
       }

       return $this->success('',$data);
    }

}