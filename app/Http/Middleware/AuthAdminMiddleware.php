<?php

namespace App\Http\Middleware;

use Closure;

class AuthAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = auth('api')->user();
        // 用户是否存在
        if (empty($user)) return response()->json(['code' => '4399','msg' => '登陆信息失效,请重新登录']);

        // token是否有效
        if (!auth('api')->check()) {
            auth('api')->logout();
            return response()->json(['code' => '4399','msg' => '登陆信息失效,请重新登录']);
        }

        return $next($request);
    }
}
