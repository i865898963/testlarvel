<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateAdUserFocusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_user_focus', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->comment('用户id');
            $table->integer('article_id')->comment('文章id');
            $table->string('province',128)->comment('省');
            $table->string('city',128)->comment('市');
            $table->string('district',128)->comment('区');
            $table->string('request_ip',50)->comment('请求ip');
            $table->integer('wait_time')->default(1)->comment('停留时长/访问时长（单位：秒） 做累加');
            $table->string('look_progress',128)->default(0)->comment('浏览进度 （单位：%）');
            $table->string('source_url')->comment('来源地址');
            $table->string('device')->comment('设备号');
            $table->integer('click_num')->default(1)->comment('用户访问次数');
            $table->integer('qrcode_times')->default(0)->comment('二维码点击次数');
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement("ALTER TABLE `ad_user_focus` comment '用户关注表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ad_user_focus');
    }
}
