<?php
/**
 * Created by PhpStorm.
 * User: liuSir
 * Date: 2020/7/11
 */

namespace App\Http\Controllers\admin\Article;


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
     * 文章列表
     * User: liuSir
     * Date: 2020/7/11
     * @param Request $request
     * @return string
     */
    public function getArticleList(Request $request)
    {
        $postData = $request->input();

        $validate = Validator::make($postData,[
            'name' => 'nullable|string',
            'startTime' => 'nullable|string',
            'endTime' => 'nullable|string',
            'currentPage' => 'required|numeric',
            'pagePerNum' => 'required|numeric',
        ]);

        if ($validate->fails()) return $this->error(1001,config('error.common.1001'));

        try {
            $data = $this->articleService->getArticleList($postData);
        } catch (Exception $e) {
            return $this->error($e->getCode(),$e->getMessage());
        }

        return $this->success('',$data);
    }

    /**
     * 获取文章详情
     * User: liuSir
     * Date: 2020/7/11
     * @param Request $request
     * @return string
     */
    public function getArticleDetail(Request $request)
    {
        $postData = $request->input();

        $validate = Validator::make($postData,[
            'id' => 'required|numeric',
        ]);

        if ($validate->fails()) return $this->error(1001,config('error.common.1001'));

        try {
            $data = $this->articleService->getArticleDetail($postData['id']);
        } catch (Exception $e) {
            return $this->error($e->getCode(),$e->getMessage());
        }

        return $this->success('',$data);
    }

    /**
     * 创建文章
     * User: liuSir
     * Date: 2020/7/11
     * @param Request $request
     * @return string
     */
    public function articleCreate(Request $request)
    {
        $postData = $request->input();

        $validate = Validator::make($postData,[
            'name' => 'required|string',
            'cover' => 'required|string',
            'listenTime' => 'required|numeric',
            'content' => 'required|string',
            'desc'  => 'required|string'
        ]);

        if ($validate->fails()) return $this->error(1001,config('error.common.1001'));

        try {
            $this->articleService->articleCreate($postData);
        } catch (Exception $e) {
            return $this->error($e->getCode(),$e->getMessage());
        }

        return $this->success();
    }

    /**
     * 更新文章
     * User: liuSir
     * Date: 2020/7/11
     * @param Request $request
     * @return string
     */
    public function articleUpdate(Request $request)
    {
        $postData = $request->input();

        $validate = Validator::make($postData,[
            'id' => 'required|numeric',
            'name' => 'required|string',
            'cover' => 'required|string',
            'listenTime' => 'required|numeric',
            'content' => 'required|string',
            'desc'  => 'required|string'
        ]);

        if ($validate->fails()) return $this->error(1001,config('error.common.1001'));

        try {
            $this->articleService->articleUpdate($postData);
        } catch (Exception $e) {
            return $this->error($e->getCode(),$e->getMessage());
        }

        return $this->success();
    }

    /**
     * 删除文章
     * User: liuSir
     * Date: 2020/7/11
     * @param Request $request
     * @return string
     */
    public function articleDelete(Request $request)
    {
        $postData = $request->input();

        $validate = Validator::make($postData,[
            'id' => 'required|numeric',
        ]);

        if ($validate->fails()) return $this->error(1001,config('error.common.1001'));

        try {
            $this->articleService->articleDelete($postData['id']);
        } catch (Exception $e) {
            return $this->error($e->getCode(),$e->getMessage());
        }

        return $this->success();
    }

    /**
     * 文章上下架
     * User: liuSir
     * Date: 2020/7/11
     * @param Request $request
     * @return string
     */
    public function articleDownOrUp(Request $request)
    {
        $postData = $request->input();

        $validate = Validator::make($postData,[
            'id' => 'required|numeric',
            'status' => 'required|numeric',
        ]);

        if ($validate->fails()) return $this->error(1001,config('error.common.1001'));

        try {
            $this->articleService->articleDownOrUp($postData['id'],$postData['status']);
        } catch (Exception $e) {
            return $this->error($e->getCode(),$e->getMessage());
        }

        return $this->success();
    }


}