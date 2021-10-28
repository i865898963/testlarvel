<?php

namespace App\Providers;

use App\Services\Common\CommonService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $this->app->bind('common', function () {
            return new CommonService();
        });
        App::bind('App\Services\User\IUserService', 'App\Services\User\UserService');
        App::bind('App\Services\Article\IArticleService', 'App\Services\Article\ArticleService');
        App::bind('App\Services\Classify\IClassifyService', 'App\Services\Classify\ClassifyService');
        App::bind('App\Services\AdminUser\IAdminUserService', 'App\Services\AdminUser\AdminUserService');
        App::bind('App\Services\User\IUserFocusService', 'App\Services\User\UserFocusService');
        App::bind('App\Services\User\IWxUserService', 'App\Services\User\WxUserService');
    }
}
