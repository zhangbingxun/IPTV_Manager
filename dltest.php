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

header('Access-Control-Allow-Origin: *');

// 数据变量
$dataurl = 'https://ott.luo2888.cn/dl.php';  // 代理API地址
$failurl = 'https://ott.luo2888.cn/videos/fmitv_failure.mp4';
$playcode = "dl717_qq625336209"; // 播放验证码
$listcode = "fmi717"; // 列表安全码

// 获取代理地址
function GetUrl() {

    $Url = 'http://';
    if($_SERVER['HTTPS'] == 'on') {
        $Url = 'https://';
    }
    $Url .= $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
    return $Url;

}

// 解密URL
function decode($str){
    $str = strtr($str, '-!_', '+/=');
    $str = urldecode(base64_decode($str));
    $url_array = explode('#', $str);
    if (is_array($url_array)) {
        foreach ($url_array as $var) {
            $var_array = explode("=", $var);
            $vars[$var_array[0]] = $var_array[1];
        }
    }
    return $vars;
}

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
    curl_setopt($curl, CURLOPT_USERAGENT, 'FMITV/1.2 (HTTP/' . $_SERVER['SERVER_NAME'] . ')');
    curl_setopt($curl, CURLOPT_POST, 1);  //声明使用POST方式来进行发送
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);  //POST数据
    $output = curl_exec($curl);  //发送请求
    curl_close($curl);  //关闭curl
    return $output;  //返回数据
    
}

if (isset($_GET['play']) || isset($_GET['list'])) {

    if (isset($_GET['play'])) {
        
        $vkeys = $_GET['vkeys'];
        $vkey = decode($vkeys);
        $line = $_GET['line'];
        $vid = $vkey['vid'];
        $tid = $vkey['tid'];
        $id = $vkey['id'];

        $data = json_encode(
            array(
                'video' => $vid,
                'tid' => $tid,
                'id' => $id,
                'line' => $line
            )
        );
        
        $post_data = 'fmitv_proxy=' . $data;
        $datastr = send_post($dataurl, $post_data);
        $obj = json_decode($datastr);
        $playurl = $obj->playurl;
        
        if (!empty($playurl)  && isset($_GET[$playcode])){
            header('location:' . $playurl);
        } else {
            header('location:' . $failurl);
        }
    }

    else if (isset($_GET['list']) && isset($_GET[$listcode])) {
        
        $vid = $_GET['vid'];
        $tid = $_GET['tid'];
        $id = $_GET['id'];
        $data = json_encode(
            array(
                'video' => $vid,
                'tid' => $tid,
                'id' => $id
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
                        $channel = preg_replace('#http://域名/文件名\?#', GetUrl() . '?' . $playcode . '&', $channel);
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