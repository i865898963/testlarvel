<?php
/**
 * Created by PhpStorm.
 * User: lichang
 * Date: 2019/10/6
 * Time: 3:45 PM
 */

namespace App\Http\Controllers;


use App\Services\Classify\IClassifyService;
use Illuminate\Http\Request;
use Exception;

class ClassifyController extends BaseController
{
    private $classifyService;

    public function __construct(IClassifyService $classifyService)
    {
        $this->classifyService = $classifyService;
    }

    /**
     * 获取栏目列表
     * @param Request $request
     * @return string
     */
    public function getClassify(Request $request)
    {
        $postData = $request->input();

        try {
            $data = $this->classifyService->getClassify($postData);
        } catch (Exception $e) {
            return $this->error($e->getCode(),$e->getMessage());
        }
        return $this->success('',$data);

    }
}