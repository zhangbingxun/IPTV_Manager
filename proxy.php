<?php
//
// +----------------------------------------------------------------------+
// | PHP version 7                                                        |
// +----------------------------------------------------------------------+
// | 此代码项目归 www.luo2888.cn 所有，盗版、倒卖死全家！                 |
// +----------------------------------------------------------------------+
// | 下列代码内容为原创内容，归本人所有。                                 |
// | 任何人未经本人许可，不得进行抄袭、复制全部或者部分内容。             |
// +----------------------------------------------------------------------+
// | 作者: KwanKaHo <kwankaho@luo2888.cn>                                 |
// +----------------------------------------------------------------------+
//
require_once "config.php";
$db = Config::getIntance();

// 数据变量
$dataurl = 'http://localhost:8118/dl.php';  //代理API地址
$failurl = 'http://tv.luo2888.cn/fmitv.mp4'; //链接失效视频

/**
 * 发送post请求
 *
 * @param string $url 请求地址
 * @param array $post_data post数据
 */
function send_post($url, $post_data) {
    
    $curl = curl_init();  // 初使化init方法
    curl_setopt($curl, CURLOPT_URL, $url);  // 指定URL
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  // 设定请求后返回结果
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);  // 忽略证书
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);  // 忽略证书验证
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/x-www-form-urlencoded;'
    ));  //HTTP头
    curl_setopt($curl, CURLOPT_USERAGENT, 'FMITV/1.0 (Proxy/1.0.0)');  // USERAGENT
    curl_setopt($curl, CURLOPT_POST, 1);  //声明使用POST方式来进行发送
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);  //POST数据
    $output = curl_exec($curl);  //发送请求
    curl_close($curl);  //关闭curl
    return $output;  //返回数据
    
}

if (isset($_GET['now'])) {
    echo (time());
    exit;
}

if (isset($_GET['play']) || isset($_GET['list'])) {

    if (isset($_GET['play'])) {
        $app_sign = $db->mGet("luo2888_config", "value", "where name='app_sign'");
        $key = $db->mGet("luo2888_config", "value", "where name='keyproxy'");
        $token = $_GET['token'];
        $time = $_GET['time'];
        $vkey = $_GET['key'];
        $line = $_GET['line'];
        $vid = $_GET['vid'];
        $tid = $_GET['tid'];
        $id = $_GET['id'];
        $nowtime = time();
        
        if (abs($nowtime - $time) > 600) {
            header('location:' . $failurl);
            exit();
        } else if ($token != md5($key . $time . "303543214" . $app_sign)) {
            header('location:' . $failurl);
            exit();
        }
        
        $data = json_encode(
            array(
                'video' => $vid,
                'tid' => $tid,
                'id' => $id,
                'line' => $line,
                'token' => $vkey
            )
        );
        
        $post_data = 'fmitv_proxy=' . $data;
        $datastr = send_post($dataurl, $post_data);
        $obj = json_decode($datastr);
        $playurl = $obj->playurl;
        
        if (!empty($playurl)){
            header('location:' . $playurl);
        } else {
            header('location:' . $failurl);
        }
        
    }

    else if (isset($_GET['list'])) {
        
        $vid = $_GET['vid'];
        $tid = $_GET['tid'];
        $data = json_encode(
            array(
                'video' => $vid,
                'tid' => $tid
            )
        );
        
        $post_data = 'fmitv_list=' . $data;
        $datastr = send_post($dataurl, $post_data);
        $listobj = json_decode($datastr, true);
        header("Content-Type:text/plain;chartset=utf-8");
        if (!empty($listobj)){
            foreach($listobj as $channellist) {
                if (is_array($channellist)) {
                    foreach($channellist as $channel) {
                        $channel = preg_replace('#http://域名/文件名#', 'fmitv://tv', $channel);
                        $channel = preg_replace('#token=#', 'key=', $channel);
                        echo $channel . "\n";
                    }
                }
            }
        }
    }
    
    exit;
    
} else {

    header('HTTP/1.1 403 Forbidden');
    exit;

}

?>