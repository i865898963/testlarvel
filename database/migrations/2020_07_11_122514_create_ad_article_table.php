<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateAdArticleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_article', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('kind_id')->default(0)->comment('分类id');
            $table->string('name',255)->default('')->comment('标题');
            $table->string('cover_url',255)->comment('封面');
            $table->string('desc',255)->default('')->comment('描述');
            $table->tinyInteger('listen_img_time')->default(2)->comment('监听图片时间');
            $table->longText('content')->comment('详情富文本');
            $table->tinyInteger('status')->default(0)->comment('状态：0：下架 1：上架');
            $table->integer('operator_id')->default(0)->comment('操作人id');
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement("ALTER TABLE `ad_article` comment '文章广告表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ad_article');
    }
}
