<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateAdAdminUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_admin_user', function (Blueprint $table) {
            $table->increments('id')->comment('自增ID');
            $table->string('nickname',48)->comment('昵称');
            $table->string('account',255)->comment('帐号');
            $table->string('password',128)->comment('密码');
            $table->string('mobile',15)->comment('手机号码');
            $table->string('email',128)->comment('邮箱');
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement("ALTER TABLE `ad_admin_user` comment '后台管理员表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ad_admin_user');
    }
}
