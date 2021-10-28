<?php
/**
 * Created by PhpStorm.
 * User: lichang
 * Date: 2019/10/6
 * Time: 3:48 PM
 */

namespace App\Services\Classify;


use App\Models\Classify\Classify;

class ClassifyService implements IClassifyService
{
    /**
     *  获取栏目列表
     * @param $postData
     * @return mixed
     */
    public function getClassify($postData)
    {
        $pagePreNum  = $postData['pagePerNum'] ?? 15;
        $currentPage = $postData['currentPage'] ?? 1;
        $offset      = ($currentPage - 1) * $pagePreNum;

        $classify = Classify::orderBy('sort','desc')
            ->offset($offset)
            ->limit($pagePreNum)
            ->get();

        return $classify;

    }
}