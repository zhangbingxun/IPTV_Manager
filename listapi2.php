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
if (isset($_GET['zbdy'])) {
    $useragent = 'MSIE';
    $aid = "8bfac13a39286a47";
    $mac = "94:87:e0:1b:d2:71";
    $model = "MI 8";
    $appname = '直播电影'; //软件名
    $key = "3856b05ab459be531787592fbe6396f3";
    $url = 'http://x20195.cn/zbdy2020'; // 后台地址
    $ts = trim(file_get_contents($url . '/ts'));
    $token = md5($ts . $url);
}
if (isset($_GET['lttv'])) {
    $useragent = 'longtengtv';
    $aid = "8bfac13a39286a47";
    $mac = "94:87:e0:1b:d2:71";
    $model = "MI 8";
    $appname = '龙腾TV'; //软件名
    $key = "17127e5ce9fa2a78566d887c28b50745";
    $url = 'http://long.188918.xyz:10086/longtv'; // 后台地址
    $ts = trim(file_get_contents($url . '/ts'));
    $token = md5($ts . $url);
}
if (isset($_GET['kkds'])) {
    $useragent = 'MSIE';
    $aid = "8bfac13a39286a47";
    $mac = "94:87:e0:1b:d2:71";
    $model = "MI 8";
    $appname = '看看电视'; //软件名
    $key = "f0733b7171d68cf90f2488ec3ef2ceda";
    $url = 'http://kk.suntv.xyz/kktv'; // 后台地址
    $ts = trim(file_get_contents($url . '/ts'));
    $token = md5($ts . $url);
}

// 头部
header("Content-Type:text/plain;chartset=utf-8");

// 登录
$postdata = base64_encode(gzcompress('{"r":"未知","m":"' . $mac . '","aid":"'. $aid . '","mo":"'. $model . '","n":"","a":"' . $appname . '","ts":' . $ts . ',"token":"' . $token . '"}'));

$loginkey = substr($key, 10, 26);
$login_post = 'login=' . $postdata;
$login = new Aes($loginkey);
$loginstr = send_post($useragent, $url . '/login3.php', $login_post);
$loginjson = $login->decrypt($loginstr);
$logindata = json_decode($loginjson, true);
$randkey = $logindata['randkey'];
$dataurl = $logindata['dataurl'];

// 获取频道
$data_post = 'data=' . base64_encode(gzcompress('{"r":"未知","m":"' . $mac . '","aid":"'. $aid . '","mo":"Android x86","n":"","na":"' . $appname . '","ra":"' . $randkey . '","ts":' . $ts . ',"token":"' . $token . '"}'));

$datakey = md5($key . $randkey);
$datakey = substr($datakey, 12, 21);

$datastr = send_post($useragent, $dataurl, $data_post);
$encrypted = substr($datastr, 256, strlen($datastr)-256);
$encrypted = str_replace("y", "#", $encrypted);
$encrypted = str_replace("x", "y", $encrypted);
$encrypted = str_replace("#", "x", $encrypted);
$encrypted = str_replace("b", "&", $encrypted);
$encrypted = str_replace("a", "b", $encrypted);
$encrypted = str_replace("&", "a", $encrypted);
$data = new Aes($datakey);
$datajson = $data->decrypt($encrypted);
$jsondata = gzuncompress(base64_decode($datajson));

$jsondata = preg_replace('#\.php\?#', '#', $jsondata);
$jsondata = preg_replace('#ab:\/\/#', 'vid=lttv_ab#tid=', $jsondata);
$jsondata = preg_replace('#long:\/\/#', 'vid=lttv_long#tid=', $jsondata);
$jsondata = preg_replace('#bd:\/\/#', 'vid=lttv_bd#tid=', $jsondata);
$jsondata = preg_replace('#hd:\/\/#', 'vid=lttv_hd#tid=', $jsondata);
$jsondata = preg_replace('#hw:\/\/#', 'vid=lttv_hw#tid=', $jsondata);
$jsondata = preg_replace('#ws:\/\/#', 'vid=lttv_ws#tid=', $jsondata);
$jsondata = preg_replace('#as:\/\/#', 'vid=lttv_as#tid=', $jsondata);
$jsondata = preg_replace('#mm:\/\/#', 'vid=lttv_mm#tid=', $jsondata);

$channeldata = json_decode($jsondata, true);

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