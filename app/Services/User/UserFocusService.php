<?php
/**
 * Created by PhpStorm.
 * User: liuSir
 * Date: 2020/7/11
 */

namespace App\Services\User;


use App\Models\UserFocus\AdUserFocus;
use App\Services\exports\UserFocusExport;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Facades\Excel;

class UserFocusService implements IUserFocusService
{
    /**
     * 用户监控列表
     * User: liuSir
     * Date: 2020/7/11
     * @param $postData
     * @return array
     */
    public function getUserFocusList($postData)
    {
       $startTime = $postData['startTime'] ?? '';
       $endTime   = $postData['endTime'] ?? '';
       $name      = $postData['name'] ?? '';
       $waitTime      = $postData['waitTime'] ?? '';
       $clickNum      = $postData['clickNum'] ?? 0;
       $articleId     = $postData['articleId'] ?? 0;
       $currentPage   = $postData['currentPage'] ?? 0;
       $pagePerNum   = $postData['pagePerNum'] ?? 15;
       $isExport   = $postData['isExport'];

       $query = AdUserFocus::with(['user','article'])
           ->when($startTime, function ($query) use ($startTime) {
               $query->where('created_at','>=',$startTime.' 00:00:00');
           })
           ->whereHas('article')
           ->when($waitTime, function ($query) use ($waitTime) {
               $query->where('wait_time','>=',$waitTime);
           })
           ->when($endTime, function ($query) use ($endTime) {
               $query->where('created_at','<=',$endTime.' 23:59:59');
           })
           ->when($clickNum, function ($query) use ($clickNum) {
               $query->where('qrcode_times','>=',$clickNum);
           })
           ->when($articleId, function ($query) use ($articleId) {
               $query->where('article_id',$articleId);
           })
           ->when($name, function ($query) use ($name) {
               $query->whereHas('article', function ($query) use ($name) {
                   $query->where('name','like',$name.'%');
               });
           });

       if ($isExport) {
           $list = $query->get();

           if ($list->isEmpty()) return;
           $array = $this->getUserFocusExportData($list);

           $cellData = ['ID','用户名','文章名称','地域','来源','浏览进度','访问时长','二维码点击次数','访问时间'];
           array_unshift($array,$cellData);

           $fileName = Carbon::now()->timestamp . 'flow_monitor';
           if (ob_get_length() > 0)  ob_end_clean();
           header('Content-Type:application/vnd.ms-excel');
           header('Cache-Control: max-age=0');
           Excel::create(iconv('UTF-8', 'GBK', $fileName),function ($excel) use ($array){
               $excel->sheet('data',function ($sheet) use ($array){
                   $sheet->rows($array);
               });
               if (ob_get_length() > 0)  ob_end_clean();
           })->export('xlsx');
           if (ob_get_length() > 0)  ob_end_clean();
           exit();
           return;
       }
       $list = $query->paginate($pagePerNum, ['*'],'currentPage', $currentPage);

       $data = collect($list->items())->transform(function ($item) {
            $arr = [
                'id' => $item->id,
                'nickname' => '用户'.$item->user->id,
                'articleName'  => $item->article->name,
                'waitTime' => $item->wait_time, // 页面访问时长
                'lookProgress' => $item->look_progress, // 浏览进度
                'province' => $item->province ?? $item->user->province, // 省
                'city' => $item->city ?? $item->user->city, // 市
                'district' => $item->district ?? $item->user->district, // 区
                'sourceUrl' => $item->source_url, // 来源
                'clickNum'  => $item->qrcode_times, // 二维码点击次数
                'device'  => $item->device, // 设备号
                'lookCreateAt' => Carbon::parse($item->created_at)->toDateTimeString() // 访问时间
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
     * 获取导出数据
     * User: liuSir
     * Date: 2020/7/16
     * @param $userFocusList
     * @return array
     */
    public function getUserFocusExportData($userFocusList)
    {
      $result = collect($userFocusList)->transform(function ($item) {
            $arr = [
                $item->id,
                '用户'.$item->user->id,
                $item->article->name,
                empty($item->province) ? $item->user->province : $item->province.'/'.empty($item->city) ? $item->user->city : $item->city.'/'.empty($item->district) ? $item->user->district : $item->district,
                $item->source_url,
                $item->look_progress,
                $item->wait_time,
                $item->qrcode_times,
                Carbon::parse($item->created_at)->toDateTimeString()
            ];

            return $arr;
        });

      return $result->toArray();
    }
}