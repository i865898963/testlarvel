<?php
/**
 * Created by PhpStorm.
 * User: liuSir
 * Date: 2020/7/11
 */

namespace App\Models\UserFocus;


use App\Models\Article\AdArticle;
use App\Models\User\AdWxUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdUserFocus extends Model
{
    use SoftDeletes;

    protected $table = 'ad_user_focus';

    /**
     * 用户信息
     * User: liuSir
     * Date: 2020/7/11
     */
    public function user()
    {
        return $this->belongsTo(AdWxUser::class,'user_id');
    }

    /**
     * 文章
     * User: liuSir
     * Date: 2020/7/11
     */
    public function article()
    {
        return $this->belongsTo(AdArticle::class,'article_id');
    }
}