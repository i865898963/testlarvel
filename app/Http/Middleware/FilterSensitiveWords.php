<?php

namespace App\Http\Middleware;

use Closure;
use DfaFilter\SensitiveHelper;
use Illuminate\Support\Facades\Redis;

class FilterSensitiveWords
{
    /**
     * Handle an incoming request.
     *
     * @param $request
     * @param Closure $next
     * @param array ...$filterInputs
     * @return \Illuminate\Http\JsonResponse|mixed
     * @throws \Exception
     */
    public function handle($request, Closure $next,...$filterInputs)
    {
        $wordFilePath = storage_path('badDic/badwords-lv7.txt');

        $map = [];
        $hasBadWords = false;
        $data = $request->get('postData');
        $deviceNum = $request->header('device-num');    //设备号
        $ip = $request->getClientIp();

        if (empty($deviceNum)) {
//            Log::setGroup('ExceptionRequest')->error('用户缺少设备号', [$data]);
        }

        $cacheKey = sprintf('api:device_num:%s:limit',$deviceNum);
        $hasPubNum = Redis::get($cacheKey);
        //如果当前设备号发送带有敏感词汇的内容超过一定次数，禁止再次发送
        if ($hasPubNum !== false && $hasPubNum >= config('public.MAX_PUBLISH_NUM')) {
            return response()->json([
                'code' => '900',
                'msg' => config('error.sys.900')
            ]);
        }

        foreach ($filterInputs as $key => $input) {
            if (!empty($data[$input])) {
                $map[$input] = $data[$input];
            }
        }

        if (! empty($map)) {
            // 获取感词库文件路径
            //$wordFilePath = storage_path('badDic/badwords.txt');
            $wordFilePath = storage_path('badDic/badwords-lv7.txt');

            $handle = SensitiveHelper::init()->setTreeByFile($wordFilePath);

            foreach ($map as $key => $val) {
                if ($handle->islegal($val)) {
                    if (!$hasBadWords) {
                        $hasBadWords = true;
                    }

                    $filterContent = $handle->replace($val, '***');
                    $data[$key] = $filterContent;
                }
            }

            $request->offsetSet('postData',$data);
        }

//        //如果有敏感词汇则记录一次
//        if ($hasBadWords && !empty($deviceNum)) {
//            Redis::incr($cacheKey);
//            $expireTime = Tool::getLeftSecondsForToday();
//            Redis::expire($cacheKey,$expireTime);
//
//            //增加一条记录到用户敏感记录表
//            $sensRecord = new SensitiveRecord();
//            $sensRecord->addSensRecord($data['userId'],$map,$deviceNum,$ip);
//        }

        return $next($request);
    }
}