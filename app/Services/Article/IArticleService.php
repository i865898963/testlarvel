<?php
/**
 * Created by PhpStorm.
 * User: lichang
 * Date: 2019/9/19
 * Time: 2:27 PM
 */

namespace App\Services\Article;


interface IArticleService
{
    /**
     * 文章列表
     * User: liuSir
     * Date: 2020/7/11
     * @param $postData
     * @return mixed
     */
   public function getArticleList($postData);

    /**
     * 文章详情
     * User: liuSir
     * Date: 2020/7/11
     * @param $id
     * @return mixed
     */
   public function getArticleDetail($id);

    /**
     * 创建文章
     * User: liuSir
     * Date: 2020/7/11
     * @param $postData
     * @return mixed
     */
   public function articleCreate($postData);

    /**
     * 更新文章
     * User: liuSir
     * Date: 2020/7/11
     * @param $postData
     * @return mixed
     */
   public function articleUpdate($postData);

    /**
     * 删除文章
     * User: liuSir
     * Date: 2020/7/11
     * @param $id
     * @return mixed
     */
   public function articleDelete($id);

    /**
     * 文章上下架
     * User: liuSir
     * Date: 2020/7/11
     * @param $id
     * @param $status
     * @return mixed
     */
   public function articleDownOrUp($id,$status);

    /**
     * 获取H5文章详情
     * User: liuSir
     * Date: 2020/7/11
     * @param $id
     * @return mixed
     */
   public function getH5ArticleDetail($id);

    /**
     * 用户浏览
     * User: liuSir
     * Date: 2020/7/14
     * @param $postData
     * @return mixed
     */
   public function userViewArticle($postData);

    /**
     * 用户浏览时长
     * User: liuSir
     * Date: 2020/7/14
     * @param $postData
     * @return mixed
     */
   public function lookThrough($postData);
}