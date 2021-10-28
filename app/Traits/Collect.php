<?php
/**
 * Created by PhpStorm.
 * User: lichang
 * Date: 2019/10/5
 * Time: 10:11 PM
 */

namespace App\Traits;
use QL\QueryList;

trait Collect
{
    /**
     * 获取文本数据
     * @param $url 采集地址
     * @param $selector 采集选择器
     * @return array
     */
    public function getTextData($url,$selector)
    {
        try {
            $data = QueryList::getInstance()->get($url)->rules([
                'content' => [$selector,'text']
            ])->query()->getData()->reverse()->map(function ($item) {
                $item['content'] = strstr($item['content'],'、') ? mb_substr(strstr($item['content'],'、'),1) : (strstr($item['content'],'.') ? mb_substr(strstr($item['content'],'.'),1) : $item['content']);
                $item['classify_id'] = 2;
                return $item;
            })->all();
            return $data;
        } catch (\Exception $e) {
            return false;
        }
    }
}