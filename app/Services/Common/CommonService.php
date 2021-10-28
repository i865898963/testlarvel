<?php
/**
 * Created by PhpStorm.
 * User: lichang
 * Date: 2019/9/19
 * Time: 6:06 PM
 */

namespace App\Services\Common;


use App\Traits\Collect;
use Illuminate\Support\Facades\DB;

class CommonService
{
    use Collect;
    /**
     * curl
     * @param $url
     * @param int $post
     * @param null $data
     * @return mixed
     */
    public function curl_request($url, $post = 0, $data = null)
    {
        //初始化curl
        $ch = curl_init();
        //参数设置
        $res = curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, $post);

        if ($post) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);

        curl_close($ch);
        return $result;
    }

    /**
     * 通过ip获取地址（聚合数据 免费会员： 100次/天 聚合黑钻：无限次）
     * User: liuSir
     * Date: 2020/7/15
     * @param $ip
     * @return array
     */
    public function getPositionByIp($ip) {
        $appKey = '57e49f27b465f457a7e7dda439201d57';
        $requestUrl = 'http://apis.juhe.cn/ip/ipNew?ip='.$ip.'&key='.$appKey;
        $data = $this->curl_request($requestUrl);
        $data = json_decode($data,true);

        $result = [
            'country' => '',
            'province' => '',
            'city' => '',
            'isp' => '',
        ];

        if ($data['resultcode'] != 200) return $result;

        $result['country'] = $data['result']['Country'];
        $result['province'] = $data['result']['Province'];
        $result['city'] = $data['result']['City'];
        $result['isp'] = $data['result']['Isp'];


        return $result;
    }

    /**
     * 获取采集数据
     * @return array
     */
    public function getCollectData($url = '',$selector = '')
    {
//        $selector =  '.v-content>div';
//        for ($i = 5500; $i <= 5850; $i++) {
//            $url = 'http://m.13lizhi.com/lizhimingyan/'.$i.'.html';
//            $data = $this->getTextData($url,$selector);
//            if (!$data) continue;
//            DB::table('yd_article')->insert($data);
//        }
        $url = 'https://8zt.cc/';
        $selector =  '#sentence';
        $newData = [];
        for ($i =1; $i <= 10000; $i++) {
            $data = $this->getTextData($url,$selector);
            $newData = array_merge($newData,$data);
            if (count($newData) == 100) {
                DB::table('yd_article')->insert($newData);
                $newData = [];
            }
        }


        return $newData;
    }
}