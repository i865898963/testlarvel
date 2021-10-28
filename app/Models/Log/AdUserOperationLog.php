<?php
/**
 * Created by PhpStorm.
 * User: liuSir
 * Date: 2020/7/14
 */

namespace App\Models\Log;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdUserOperationLog extends Model
{
    use SoftDeletes;

    protected $table = 'ad_user_operation_log';

}