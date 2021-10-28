<?php
/**
 * Created by PhpStorm.
 * User: liuSir
 * Date: 2020/7/11
 */

namespace App\Http\Controllers\admin\resource;


use App\Http\Controllers\BaseController;
use App\Models\Article\AdArticle;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ResourceController extends BaseController
{
    /**
     * 创建资源
     * User: liuSir
     * Date: 2020/7/11
     * @param Request $request
     * @return array|string
     */
    public function recourseCreate(Request $request)
    {
        $postData = $request->all();

        $validator = Validator::make($postData,[
           'type' => 'nullable|numeric' // 0：富文本图片 1封面图
        ]);

        if ($validator->fails()) return $this->error(1001,config('error.common.1001'));

        $file = $request->file('file');

        if (empty($file)) return $this->error(1001,config('error.common.1001'));

        try {
//            $fileName = time().rand(0,10000).$file->getClientOriginalName();
//            $dic = '/public/resource';
//            $savePath = $dic.$fileName;
//            Storage::disk('public')->put($savePath,File::get($file));
            $dic = '/public/resource';
            $url = $file->store($dic);
            $url = Storage::url($url);

            $imageUrl = env('INNER_URL').$url;

            $result = ['imageUrl' => $imageUrl];
        } catch (Exception $e) {
            return $this->error($e->getCode(),$e->getMessage());
        }


        return $this->success('',$result);
    }

    /**
     * 获取七牛配置
     * User: liuSir
     * Date: 2020/8/4
     * @return \Illuminate\Config\Repository|mixed
     */
    public function getQiNiuConf()
    {
        return $this->success('',config('qiniu'));
    }
}