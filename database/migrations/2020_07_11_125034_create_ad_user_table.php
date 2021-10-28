<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateAdUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_wx_user', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nickname','255')->default('')->comment('昵称');
            $table->string('avatar','255')->default('')->comment('头像');
            $table->string('mobile','11')->default('')->comment('手机号');
            $table->tinyInteger('gender')->default(0)->comment('性别: 1男 2女');
            $table->string('open_id','255')->comment('授权openid');
            $table->string('request_ip','11')->comment('请求ip');
            $table->string('token','255')->comment('用户token');
            $table->tinyInteger('type')->default(0)->comment('类型：默认微信');
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement("ALTER TABLE `ad_wx_user` comment '微信用户表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ad_wx_user');
    }
}
