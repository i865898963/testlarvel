<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
//Route::group(['prefix' => 'user' ], function() {
//    Route::get('/getUserToken','UserController@getUserToken');
//    Route::any('/getCollect','UserController@getCollectData');
//});
//Route::group(['prefix' => 'article'], function () {
//   Route::get('/getArticle','ArticleController@getArticle'); // 文章列表
//   Route::get('/getCurrentArticle','ArticleController@getCurrentArticle'); // 当前文章
//});
//Route::group(['prefix' => 'classify'], function () {
//   Route::get('/getClassify','ClassifyController@getClassify'); // 栏目列表
//});

// 无效校验
Route::group(['prefix' => 'user'], function () {
    Route::post('/login','admin\AdminUser\AdminUserController@login'); // 登录
});

// 用户信息校验
Route::group(['middleware' => ['authAdmin']], function () {
    Route::group(['prefix' => 'user'], function () {
        Route::post('/logout','admin\AdminUser\AdminUserController@logout'); // 退出登录
    });


    // 文章模块
    Route::group(['prefix' => 'article'], function () {
        Route::post('/getArticleList','admin\Article\ArticleController@getArticleList'); // 获取文章列表
        Route::post('/getArticleDetail','admin\Article\ArticleController@getArticleDetail'); // 获取文章详情
        Route::post('/articleCreate','admin\Article\ArticleController@articleCreate'); // 创建文章
        Route::post('/articleUpdate','admin\Article\ArticleController@articleUpdate'); // 更新文章
        Route::post('/articleDelete','admin\Article\ArticleController@articleDelete'); // 删除文章
        Route::post('/articleDownOrUp','admin\Article\ArticleController@articleDownOrUp'); // 文章上下架
    });

    // 用户监控模块
    Route::group(['prefix' => 'userFocus'], function () {
        Route::post('/getUserFocusList','admin\UserFocus\UserFocusController@getUserFocusList'); // 用户监控列表
    });

    // 上传
    Route::group(['prefix' => 'upload'], function () {
        Route::post('/resource','admin\resource\ResourceController@recourseCreate'); // 资源上传
    });

    Route::group(['prefix' => 'resource'], function () {
        Route::post('/getQiNiuConf','admin\resource\ResourceController@getQiNiuConf'); // 获取七牛conf
    });
});

Route::get('userFocus/getUserFocusListExport','admin\UserFocus\UserFocusController@getUserFocusList'); // 用户监控列表导出


Route::group(['prefix' => 'H5'], function () {
    Route::post('/user/checkLogin','H5\User\WxUserController@checkLogin'); // 校验用户登录
    Route::post('/user/shareParams','H5\User\WxUserController@shareParams'); // 分享参数

    Route::group(['middleware' => 'checkUser'], function () {
        Route::post('/article/getArticleDetail','H5\Article\ArticleController@getH5ArticleDetail'); // 获取h5文章详情
        Route::post('/user/view','H5\Article\ArticleController@userViewArticle'); // 用户观看
        Route::post('/user/lookThrough','H5\Article\ArticleController@lookThrough'); // 用户浏览进度/停留时长
    });
});

