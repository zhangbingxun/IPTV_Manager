<?php
class Aes {
    protected $method;
    protected $secret_key;
    protected $iv;
    protected $options;

    public function __construct($key, $method = 'AES-128-ECB', $iv = '', $options = 0) {
        $this->secret_key = isset($key) ? $key : 'tvkey_luo2888';
        $this->method = $method;
        $this->iv = $iv;
        $this->options = $options;
    } 

    public function encrypt($data) {
        return openssl_encrypt($data, $this->method, $this->secret_key, $this->options, $this->iv);
    } 

    public function decrypt($data) {
        return openssl_decrypt($data, $this->method, $this->secret_key, $this->options, $this->iv);
    } 
} 

function send_post($useragent, $url, $post_data) {
   //初使化init方法
   $curl = curl_init();
   curl_setopt($curl, CURLOPT_URL, $url);
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
   curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
   //curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded;charset=utf-8'));
   curl_setopt($curl, CURLOPT_USERAGENT, $useragent);
   curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
   curl_setopt($curl, CURLOPT_TIMEOUT, 10);
   curl_setopt($curl, CURLOPT_POST, 1);
   curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
   $output = curl_exec($curl);
   curl_close($curl);
   return $output;
} 

function randomStr($len = 16)
{
    $chars = "1234567890abcdefghijklmnopqrstuvwxyz";
    $shuffle = str_shuffle($chars);
    $result = '';
    for ($i=0;$i<$len;$i++){
        $index = mt_rand(0,strlen($chars));
        $result .= substr($shuffle,$index,1);
    }
    return $result;
}


// 配置
if (isset($_GET['fyds'])) {
    $useragent = 'MSIE';
    $aid = randomStr();
    $loginfile = 'login3.php';
    $datafile = 'data3.php';
    $mac = "11:22:33:44:55:66";
    $appname = '风韵电视'; //软件名
    $key = "0ef64bef55b1b04514d7f58f7cb11e7b";
    $url = 'http://121.89.198.224/aatv'; // 后台地址
}
if (isset($_GET['hk168'])) {
    $useragent = 'MSIE';
    $aid = randomStr();
    $loginfile = 'login3.php';
    $datafile = 'data3.php';
    $mac = "11:22:33:44:55:66";
    $appname = '华凯超视觉'; //软件名
    $key = "3bbe41dd6aeee8d1b7ff190e7226bd4d";
    $url = 'http://tv668.club/hk666'; // 后台地址
}
if (isset($_GET['xszb'])) {
    $useragent = 'MSIE';
    $aid = randomStr();
    $loginfile = 'login3.php';
    $datafile = 'data3.php';
    $mac = "11:22:33:44:55:66";
    $appname = '星闪直播'; //软件名
    $key = "dcaaa109d90d9846ab6a5e42f658743d";
    $url = 'http://xszb.chxjon.cn'; // 后台地址
}
if (isset($_GET['mhds'])) {
    $useragent = 'MSIE';
    $loginfile = 'login3.php';
    $datafile = 'data3.php';
    $aid = "319fdd0b8a87bb06";
    $mac = "11:22:33:44:55:66";
    $appname = '美好电视'; //软件名
    $key = "a3186c0d4c6da6daef9b3b911798dde7";
    $url = 'http://139.224.232.220/mhtv'; // 后台地址
}
if (isset($_GET['yjxb'])) {
    $useragent = 'MSIE';
    $aid = randomStr();
    $loginfile = 'login3.php';
    $datafile = 'data3.php';
    $mac = "11:22:33:44:55:66";
    $appname = '有家新版'; //软件名
    $key = "f4adef60bca2d6722fb319343a6c185b";
    $url = 'http://bio-panasonic.cn/yj_iptv'; // 后台地址
}
if (isset($_GET['qwtds'])) {
    $useragent = 'MSIE';
    $aid = randomStr();
    $loginfile = 'login.php';
    $datafile = 'data.php';
    $mac = "11:22:33:44:55:66";
    $appname = '全网通TV'; //软件名
    $key = "f6a52170b44d98bf3e9dfb2c97606edc";
    $url = 'http://qwttv.xb08.cn'; // 后台地址
}
if (isset($_GET['qwtzb'])) {
    $useragent = 'MSIE';
    $model = "MI 8";
    $aid = "8bfac13a39286a47";
    $mac = "94:87:e0:1b:d2:71";
    $loginfile = 'login3.php';
    $datafile = 'data3.php';
    $appname = '全网通直播'; //软件名
    $key = "69041a89b3e2728a47f2c35e6f44a26f";
    $url = 'https://iptv.ottcom.cn/101/qwtlive'; // 后台地址
}
if (isset($_GET['yqlds'])) {
    $useragent = 'MSIE';
    $aid = randomStr();
    $loginfile = 'login3.php';
    $datafile = 'data3.php';
    $mac = "11:22:33:44:55:66";
    $appname = '云麒麟TV'; //软件名
    $key = "e558e80ce561f42632ae146de7e9e050";
    $url = 'http://66playgame.net/iptv'; // 后台地址
}
if (isset($_GET['sytv'])) {
    $useragent = 'MSIE';
    $aid = randomStr();
    $loginfile = 'login3.php';
    $datafile = 'data3.php';
    $mac = "11:22:33:44:55:66";
    $appname = '速云TV'; //软件名
    $key = "d3fd0083a5f6eeee297e3ea0f070fbf0";
    $url = 'http://guangminggongdian.com/iptv'; // 后台地址
}
// 头部
if (isset($_GET['txt'])) {
    header("Content-type:application/octet-stream");
    header("Content-Disposition: attachment; filename=" . $appname . ".txt");
} else {
    header("Content-Type:text/plain;chartset=utf-8");
}

// 登录
$postdata = '{"region":"","mac":"' . $mac . '","androidid":"'. $aid . '","model":"Android x86","nettype":"","appname":"' . $appname . '"}';
if (isset($_GET['login'])) {
    $loginkey = substr($key, 5, 16);
    $login_post = 'login=' . $postdata;
    $login = new Aes($loginkey);
    $loginstr = send_post($useragent, $url . '/' . $loginfile, $login_post);
    $loginjson = $login->decrypt($loginstr);
    $logindata = json_decode($loginjson, true);
    $randkey = $logindata['randkey'];
    $dataurl = $logindata['dataurl'];
} else {
    $rand = rand(1, 9999999);
    $randkey = md5($rand);
}

// 获取频道
$data_post = 'data=' . '{"region":"","mac":"' . $mac . '","androidid":"'. $aid . '","model":"Android x86","nettype":"","appname":"' . $appname . '","rand":"' . $randkey . '"}';
$datakey = md5($key . $randkey);
$datakey = substr($datakey, 7, 16);
if (isset($_GET['login'])) {
$datastr = send_post($useragent, $dataurl, $data_post);
} else {
$datastr = send_post($useragent, $url . '/' . $datafile, $data_post);
}
$datastr = str_replace("A9SZzkKb5bJKldYrCBa3", "", $datastr);
$encrypted = substr($datastr, 128, strlen($datastr)-128);
$encrypted = str_replace("y", "#", $encrypted);
$encrypted = str_replace("t", "y", $encrypted);
$encrypted = str_replace("#", "t", $encrypted);
$encrypted = str_replace("b", "&", $encrypted);
$encrypted = str_replace("f", "b", $encrypted);
$encrypted = str_replace("&", "f", $encrypted);
$data = new Aes($datakey);
$datajson = $data->decrypt($encrypted);
$datajson = gzuncompress(base64_decode($datajson));
$channeldata = json_decode($datajson, true);

foreach($channeldata as $catelist) {
    print_r("\n" . '--------------------------------------------------------' . $catelist['name'] . '--------------------------------------------------------' . "\n\n");
    foreach($catelist as $channellist) {
        if (is_array($channellist)) {
            foreach($channellist as $channel) {
                if (is_array($channel)) {
                    foreach($channel['source'] as $url) {
                        print_r($channel['name'] . ',' . $url . "\n");
                    } 
                } 
            } 
        } 
    } 
} 

?>