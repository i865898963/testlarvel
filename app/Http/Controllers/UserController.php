<?php
/**
 * Created by PhpStorm.
 * User: lichang
 * Date: 2019/9/19
 * Time: 1:55 PM
 */

namespace App\Http\Controllers;


use App\Models\Article\Article;
use App\Services\Common\CommonService;
use App\Services\User\IUserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class UserController extends BaseController
{
    protected $userService;

    public function __construct(IUserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * 登陆校验
     * @param Request $request
     * @return string
     */
    public function getUserToken(Request $request) {
        $postData = $request->input();

        $validator = Validator::make($postData,[
            'code' => 'required|string'
        ]);

        if($validator->fails()) return $this->error('1001',config('error.common.1001'));

        try {
            $data = $this->userService->getCode2Session($postData['code']);
        } catch (Exception $e) {
            return $this->error($e->getCode(),$e->getMessage());
        }


        return $this->success('',$data);

    }

    public function getCollectData(Request $request) {
        $content = '　　45．一个人如果不被恶习所染，幸福近矣。';
        $pattern = '/\s*[0-9]*\．?\.?/';
        preg_match_all($pattern,$content,$matches);
//        return $matches[0];
//        preg_match('/[^\x{4e00}-\x{9fa5}a-zA-Z0-9]/u','博客园★博客园。博客园.博客园',$matches);
       return empty($matches[0]) ? $content : str_replace($matches[0],'',$content);
        return $matches[0];

        return $this->error('1001',config('error.common.1001'));
        $commService = new CommonService();
        $data = $commService->getCollectData();

        return $this->success($commService->getCollectData());
    }
}