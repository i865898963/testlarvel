<?php
/**
 * Created by PhpStorm.
 * User: lichang
 * Date: 2019/10/5
 * Time: 11:34 PM
 */

namespace App\Models\Article;


use App\Models\UserFocus\AdUserFocus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdArticle extends Model
{
    use SoftDeletes;
    protected $table = 'ad_article';


    /**
     * 被用户关注
     * User: liuSir
     * Date: 2020/7/11
     */
    public function userFocus()
    {
        return $this->hasMany(AdUserFocus::class,'article_id');
    }
}