<?php
/**
 * Created by PhpStorm.
 * User: liuSir
 * Date: 2020/7/11
 */

namespace App\Http\Controllers\H5\Article;


use App\Http\Controllers\BaseController;
use App\Services\Article\IArticleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class ArticleController extends BaseController
{
    private $articleService;

    public function __construct(IArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    /**
     * 获取H5文章详情
     * User: liuSir
     * Date: 2020/7/11
     * @param Request $request
     * @return string
     */
    public function getH5ArticleDetail(Request $request)
    {
        $postData = $request->input('postData');

        $validator = Validator::make($postData,[
           'id' => 'required|numeric'
        ]);

        if ($validator->fails()) return $this->error(1001,config('error.common.1001'));

        try {
            $data = $this->articleService->getH5ArticleDetail($postData['id']);
        } catch (Exception $e) {
            return $this->error($e->getCode(),$e->getMessage());
        }

        return $this->success('',$data);
    }

    /**
     * 用户浏览
     * User: liuSir
     * Date: 2020/7/14
     * @param Request $request
     * @return string
     */
    public function userViewArticle(Request $request)
    {
        $postData = $request->input('postData');


        $validator = Validator::make($postData,[
            'id'        => 'required|numeric',
            'userId'    => 'required|numeric', // 用户id
            'province'  => 'nullable|string', // 省
            'city'      => 'nullable|string', // 市
            'district'  => 'nullable|string', // 区
            'ip'        => 'nullable|string', // 客户端ip
            'sourceUrl' => 'nullable|string', // 请求地址
            'device'    => 'nullable|string', // 设备号

        ]);

        if ($validator->fails()) return $this->error(1001,config('error.common.1001'));

        try {
            $data = $this->articleService->userViewArticle($postData);
        } catch (Exception $e) {
            return $this->error($e->getCode(),$e->getMessage());
        }

        return $this->success('',$data);
    }

    /**
     * 用户浏览时长
     * User: liuSir
     * Date: 2020/7/14
     * @param Request $request
     * @return string
     */
    public function lookThrough(Request $request)
    {
        $postData = $request->input('postData');

        $validator = Validator::make($postData,[
            'id'        => 'required|numeric',
            'userId'    => 'required|numeric', // 用户id
            'look'      => 'nullable|string', //  访问进度
            'wait'      => 'nullable|string', //  停留时长
            'times'     => 'nullable|string', //  二维码点击次数
        ]);

        if ($validator->fails()) return $this->error(1001,config('error.common.1001'));

        try {
            $data = $this->articleService->lookThrough($postData);
        } catch (Exception $e) {
            return $this->error($e->getCode(),$e->getMessage());
        }

        return $this->success('',$data);
    }
}