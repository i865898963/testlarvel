<?php
/**
 * Created by PhpStorm.
 * User: liuSir
 * Date: 2020/7/10
 */

namespace App\Http\Controllers\admin\AdminUser;


use App\Http\Controllers\BaseController;
use App\Services\AdminUser\IAdminUserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class AdminUserController extends BaseController
{
    private $adminUserService;

    public function __construct(IAdminUserService $adminUserService)
    {
        $this->adminUserService = $adminUserService;
    }

    /**
     * 登录
     * User: liuSir
     * Date: 2020/7/11
     * @param Request $request
     * @return string
     */
    public function login(Request $request)
    {
        $postData = $request->input();

        $validator = Validator::make($postData,[
            'account' => 'required|string',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) return $this->error(1001,config('error.common.1001'));


        try {
            $data = $this->adminUserService->login($postData['account'],$postData['password']);
        } catch (Exception $e) {
            return $this->error($e->getCode(),$e->getMessage());
        }

        return $this->success('',$data);
    }

    /**
     * 退出登录
     * User: liuSir
     * Date: 2020/7/11
     * @return string
     */
    public function logout()
    {
        auth('api')->logout();
        return $this->success('退出登录成功');
    }
}