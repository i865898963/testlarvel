<?php
/**
 * Created by PhpStorm.
 * User: lichang
 * Date: 2019/10/6
 * Time: 12:19 AM
 */

namespace App\Services\Article;


use App\Models\Article\AdArticle;
use App\Models\Log\AdUserOperationLog;
use App\Models\User\AdWxUser;
use App\Models\UserFocus\AdUserFocus;
use Carbon\Carbon;
use Exception;

class ArticleService implements IArticleService
{
    /**
     * 获取文章内容
     * @param $postData
     * @return mixed
     */
    public function getArticleList($postData)
    {
        $name  = $postData['name'] ?? ''; // 文章名称
        $startTime = $postData['startTime'] ?? ''; // 开始时间
        $endTime = $postData['endTime'] ?? ''; // 结束时间
        $pagePerNum  = $postData['pagePerNum'] ?? 15; // 一页显示多少条
        $currentPage = $postData['currentPage'] ?? 1; // 当前页数

        $list = AdArticle::when($name, function ($query) use ($name) {
                    $query->where('name','like',$name.'%');
                })
                ->when($startTime, function ($query) use ($startTime) {
                    $query->where('created_at','>=',$startTime.' 00:00:00');
                })
                ->when($endTime, function ($query) use ($endTime) {
                    $query->where('created_at','<=',$endTime.' 23:59:59');
                })
                ->withCount('userFocus')
                ->paginate($pagePerNum, ['*'],'currentPage', $currentPage);

        $data = collect($list->items())->transform(function ($item) {
            $arr = [
                'id' => $item->id,
                'name' => $item->name,
                'desc' => $item->desc,
                'status' => $item->status,
                'clickNum' => $item->user_focus_count ?? 0,
//                'content' => $item->content,
                'url'     => env('H5_DOMAIN').'/?id='.$item->id,
                'updateAt' => Carbon::parse($item->updated_at)->toDateTimeString()
            ];

            return $arr;
        });

        $result = [
            'data' => $data,
            'totalNum' => $list->total()
        ];

        return $result;
    }

    /**
     * 获取文章详情
     * @param $postData
     * @return mixed
     * @throws Exception
     */
    public function getArticleDetail($id)
    {
        $article = AdArticle::find($id);
        if (empty($article)) throw new Exception(config('error.article.1201'),1201);

        $result = [
            'id' => $article->id,
            'name' => $article->name,
            'cover_url' => $article->cover_url,
            'desc' => $article->desc,
            'listenTime' => $article->listen_img_time,
            'content' => $article->content
        ];
        return $result;

    }


    /**
     * 创建文章
     * User: liuSir
     * Date: 2020/7/11
     * @param $postData
     * @return array|mixed
     * @throws \Throwable
     */
    public function articleCreate($postData)
    {
        $name = $postData['name'];
        $desc = $postData['desc'] ?? '';
        $cover = $postData['cover'];
        $listenTime = $postData['listenTime'];
        $content = $postData['content'];

        $article = new AdArticle();
        $article->name = $name;
        $article->cover_url = $cover;
        $article->listen_img_time = $listenTime;
        $article->content = $content;
        $article->status = 1;
        $article->desc = $desc;
        $article->saveOrFail();
    }

    /**
     * 更新文章
     * User: liuSir
     * Date: 2020/7/11
     * @param $postData
     * @return array|mixed
     * @throws \Throwable
     */
    public function articleUpdate($postData)
    {
        $id = $postData['id'];
        $name = $postData['name'];
        $cover = $postData['cover'];
        $desc = $postData['desc'] ?? '';
        $listenTime = $postData['listenTime'];
        $content = $postData['content'];

        $article = AdArticle::find($id);

        if (empty($article)) throw new Exception(config('error.article.1201'),1201);
        $article->name = $name;
        $article->cover_url = $cover;
        $article->listen_img_time = $listenTime;
        $article->content = $content;
        $article->status = 1;
        $article->desc = $desc;
        $article->saveOrFail();
    }

    /**
     * 删除文章
     * User: liuSir
     * Date: 2020/7/11
     * @param $id
     * @return mixed|void
     * @throws Exception
     */
    public function articleDelete($id)
    {
        $article = AdArticle::find($id);

        if (empty($article)) throw new Exception(config('error.article.1201'),1201);

        $article->delete();
    }

    /**
     * 文章上下架
     * User: liuSir
     * Date: 2020/7/11
     * @param $id
     * @param $status
     * @return mixed|void
     * @throws Exception
     */
    public function articleDownOrUp($id,$status)
    {
        $article = AdArticle::find($id);

        if (empty($article)) throw new Exception(config('error.article.1201'),1201);

        if ($article->status != $status) {
            $article->status = $status;
            $article->saveOrFail();
        }
    }

    /**
     * 获取h5文章详情
     * User: liuSir
     * Date: 2020/7/11
     * @param $id
     * @return mixed
     * @throws Exception
     */
    public function getH5ArticleDetail($id)
    {
        // todo 如果获取不到用户信息 在获取详情后就拿到ip生成用户
        $article = AdArticle::withCount('userFocus')->find($id);

        if (empty($article)) throw new Exception(config('error.article.1201'),1201);

        $result = [
            'content' => $article->content,
            'cover'   => $article->cover_url,
            'title'   => $article->name,
            'desc'    => $article->desc,
            'readNum' => $article->user_focus_count ?? 0
        ];

        return $result;
    }

    /**
     * 用户浏览
     * User: liuSir
     * Date: 2020/7/14
     * @param $postData
     * @throws \Throwable
     */
    public function userViewArticle($postData)
    {
        $userId = $postData['userId'];
        $articleId = $postData['id'];
        $province = $postData['province'] ?? '';
        $city = $postData['city'] ?? '';
        $district = $postData['district'] ?? '';
        $ip = $postData['ip'] ?? '';
        $sourceUrl = $postData['sourceUrl'] ?? '';
        $device = $postData['device'] ?? '';

        $article = AdArticle::find($articleId);
        // 记录操作日志
        //$this->userOperationLog($postData);
        if (empty($article)) return;

        $userFocus = AdUserFocus::where('user_id',$userId)->where('article_id',$articleId)->first();


        // 用观看信息 可以记录操作
        if (!empty($userFocus)) {
            $userFocus->click_num += 1;
            $userFocus->saveOrFail();
            return;
        }

        $adWxUser = AdWxUser::find($userId);
        if (empty($adWxUser->province)) {
            $adWxUser->province = $province;
            $adWxUser->city = $city;
            $adWxUser->district = $district;
            $adWxUser->saveOrFail();
        }
        
        $userFocus = new AdUserFocus();
        $userFocus->user_id = $userId;
        $userFocus->article_id = $articleId;
        $userFocus->province = $province;
        $userFocus->city = $city;
        $userFocus->district = $district;
        $userFocus->request_ip = $ip;
        $userFocus->source_url = $sourceUrl;
        $userFocus->device = $device;
        $userFocus->saveOrFail();
    }

    /**
     * 记录用户操作日志
     * User: liuSir
     * Date: 2020/7/14
     * @param $postData
     * @throws \Throwable
     */
    public function userOperationLog($postData)
    {
        $userId = $postData['userId'];
        $articleId = $postData['id'];
        $province = $postData['province'] ?? '';
        $city = $postData['city'] ?? '';
        $district = $postData['district'] ?? '';
        $ip = $postData['ip'] ?? '';
        $sourceUrl = $postData['sourceUrl'] ?? '';
        $device = $postData['device'] ?? '';

        $log = new AdUserOperationLog();
        $log->user_id = $userId;
        $log->article_id = $articleId;
        $log->province = $province;
        $log->city = $city;
        $log->district = $district;
        $log->request_ip = $ip;
        $log->source_url = $sourceUrl;
        $log->device = $device;
        $log->saveOrFail();
    }

    /**
     * 用户浏览时长
     * User: liuSir
     * Date: 2020/7/14
     * @param $postData
     * @return mixed|void
     * @throws Exception
     */
    public function lookThrough($postData)
    {
        $articleId = $postData['id'];
        $userId = $postData['userId'];
        $look = $postData['look'] ?? 0;
        $wait = $postData['wait'] ?? 1;
        $times = $postData['times'] ?? 0;

        $userFocus = AdUserFocus::where('user_id',$userId)->where('article_id',$articleId)->first();
        if (empty($userFocus)) return;

        if($userFocus->wait_time < $wait) $userFocus->wait_time = $wait;
        if ($userFocus->look_progress < $look)  $userFocus->look_progress = $look;
        $userFocus->qrcode_times += $times;
        $userFocus->saveOrFail();

    }
}