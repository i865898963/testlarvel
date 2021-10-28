<?php
/**
 * Created by PhpStorm.
 * User: liuSir
 * Date: 2020/7/11
 */

namespace App\Http\Controllers\admin\UserFocus;


use App\Http\Controllers\BaseController;
use App\Services\User\IUserFocusService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class UserFocusController extends BaseController
{
    private $userFocusService;

    public function __construct(IUserFocusService $userFocusService)
    {
        $this->userFocusService = $userFocusService;
    }

    /**
     * 用户监控列表
     * User: liuSir
     * Date: 2020/7/11
     * @param Request $request
     * @return string
     */
    public function getUserFocusList(Request $request)
    {
        $postData = $request->all();

        $validator = Validator::make($postData,[
            'currentPage' => 'required|numeric',
            'pagePerNum'  => 'required|numeric',
            'name'        => 'nullable|string',
            'waitTime'    => 'nullable|numeric',
            'clickNum'    => 'nullable|numeric',
            'articleId'   => 'nullable|numeric',
            'startTime'   => 'nullable|string',
            'endTime'     => 'nullable|string',
        ]);

        if ($validator->fails()) return $this->error(1001,config('error.common.1001'));
        $isExport = strstr($request->path(), 'userFocus/getUserFocusListExport');
        $postData['isExport'] = $isExport;
        try {
            $data = $this->userFocusService->getUserFocusList($postData);
        } catch (Exception $e) {
            return $this->error($e->getCode(),$e->getMessage());
        }

        return $this->success('',$data);
    }
}