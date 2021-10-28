<?php

namespace App\Http\Middleware;

use App\Models\User\AdWxUser;
use Closure;

class CheckUserMiddleware
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

        $token = $request->header('token');

        $wxUser = AdWxUser::where('token',$token)->first();

        if (empty($wxUser)) return response()->json(['code' => 1001, 'msg' => '您无权访问']);

        $agent       = strtolower($_SERVER['HTTP_USER_AGENT']);
        $device_type = 'unknown';

        $device_type = (strpos($agent, 'windows nt')) ? 'pc' : $device_type;

        $device_type = (strpos($agent, 'iphone')) ? 'iphone' : $device_type;

        $device_type = (strpos($agent, 'ipad')) ? 'ipad' : $device_type;

        $device_type = (strpos($agent, 'android')) ? 'android' : $device_type;

        $postData = $request->all();
        $postData['userId'] = $wxUser->id;
        $postData['ip'] = $request->getClientIp();
        $postData['sourceUrl'] = $request->url();
        $postData['device'] = $device_type;
        $request->offsetSet('postData', $postData);
        return $next($request);
    }
}
