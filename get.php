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

function send_post($url, $post_data) {
   //初使化init方法
   $curl = curl_init();
   curl_setopt($curl, CURLOPT_URL, $url);
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
   curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
   //curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded;charset=utf-8'));
   curl_setopt($curl, CURLOPT_USERAGENT, 'MSIE');
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
if (isset($_GET['cietv'])) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, 'http://vip.cietv.com/mlive.asp?id=2&see=1');
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_COOKIE , 'HX%5FUSER=User%5FName=gysguan&userhidden=2&uid=28089&User%5FPwd=fa95ba7e62717d39a015b7d562717d39a015b7d5;');
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.138 Mobile Safari/537.36');
    $listobj = curl_exec($curl);
    $listobj=mb_convert_encoding($listobj, 'UTF-8', 'UTF-8,GBK,GB2312,BIG5');

    preg_match_all('/id="(.*?)" title="(.*?)"/i', $listobj, $channel);
    $i = 0;
    foreach ($channel[2] as &$channelname) {
        $playurl = $channel[1][$i];
        $channelname = preg_replace('# #', '', $channelname);
        if (strstr($playurl,"http") == false) {
            $playurl = 'http://vip.cietv.com' . $playurl;
        }
        echo $channelname . ',' . $playurl . "\n";
        $i++;
    }
    exit;
}
if (isset($_GET['fyds'])) {
    $jnikey = 'AD80F93B542B';
    $aid = randomStr();
    $sig = 20202; //签名密码
    $appname = '风韵电视'; //软件名
    $packagename = 'com.vvv.test'; //软件包名
    $url = 'http://121.89.198.224/aatv'; // 后台地址
}
if (isset($_GET['hk168'])) {
    $jnikey = 'AD80F93B542B';
    $aid = randomStr();
    $sig = 16123; //签名密码
    $appname = '华凯超视觉TV'; //软件名
    $packagename = 'com.lt.hk168'; //软件包名
    $url = 'http://tv668.club/hk666'; // 后台地址
}
if (isset($_GET['pszb'])) {
    $jnikey = 'AD80F93B542B';
    $aid = randomStr();
    $sig = 13838; //签名密码
    $appname = '普視直播'; //软件名
    $packagename = 'com.global.live'; //软件包名
    $url = 'http://live.tvbox.asia/live'; // 后台地址
}
if (isset($_GET['xszb'])) {
    $jnikey = 'AD80F93B542B';
    $aid = randomStr();
    $sig = 13628; //签名密码
    $appname = '星闪直播'; //软件名
    $packagename = 'com.xszb.tv'; //软件包名
    $url = 'http://xszb.chxjon.cn'; // 后台地址
}
if (isset($_GET['hxzb'])) {
    $jnikey = 'AD80F93B542B';
    $aid = randomStr();
    $sig = 9119; //签名密码
    $appname = '海峡直播'; //软件名
    $packagename = 'cn.hxtv.player'; //软件包名
    $url = 'http://hxtv.942wifi.com:8421'; // 后台地址
}
if (isset($_GET['mhds'])) {
    $jnikey = 'AD80F93B542B';
    $aid = "319fdd0b8a87bb06";
    $sig = 19869; //签名密码
    $appname = '美好电视'; //软件名
    $packagename = 'com.meilixuexi.tv'; //软件包名
    $url = 'http://139.224.232.220/mhtv'; // 后台地址
}
if (isset($_GET['yjxb'])) {
    $jnikey = 'LC2RC27QTTQM';
    $aid = randomStr();
    $sig = 523123313; //签名密码
    $appname = '有家新版'; //软件名
    $packagename = 'com.youjia.qwnew'; //软件包名
    $url = 'http://bio-panasonic.cn/yj_iptv'; // 后台地址
}

// 头部
if (isset($_GET['txt'])) {
    header("Content-type:application/octet-stream");
    header("Content-Disposition: attachment; filename=" . $appname . ".txt");
} else {
    header("Content-Type:text/plain;chartset=utf-8");
}
$mac = "11:22:33:44:55:66";
$key = md5($sig . $appname . $packagename . $jnikey);
$key = md5($key . $appname . $packagename);
$postdata = '"region":"","mac":"' . $mac . '","androidid":"'. $aid . '","model":"Android x86","nettype":"","appname":"' . $appname . '"';

// 登录
if (isset($_GET['login'])) {
    $loginkey = substr($key, 5, 16);
    $login_post = 'login={' . $postdata . '}';
    $login = new Aes($loginkey);
    $loginstr = send_post($url . '/login3.php', $login_post);
    $loginjson = $login->decrypt($loginstr);
    $logindata = json_decode($loginjson, true);
    $randkey = $logindata['randkey'];
    $dataurl = $logindata['dataurl'];
} else {
    $rand = rand(1, 9999999);
    $randkey = md5($rand);
}

// 获取频道
$data_post = 'data={' . $postdata . ',' . '"rand":"' . $randkey . '"' . '}';
$datakey = md5($key . $randkey);
$datakey = substr($datakey, 7, 16);
if (isset($_GET['login'])) {
$datastr = send_post($dataurl, $data_post);
$datastr = str_replace("A9SZzkKb5bJKldYrCBa3", "", $datastr);
} else {
$datastr = send_post($url . '/data3.php', $data_post);
}
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
                if (is_array($channel) && strstr($channel['source'][0],"#sop://") == false) {
                    print_r($channel['name'] . ',' . $channel['source'][0] . "\n");
                    if (!empty($channel['source'][1])) {
                        print_r($channel['name'] . ',' . $channel['source'][1] . "\n");
                    } 
                    if (!empty($channel['source'][2])) {
                        print_r($channel['name'] . ',' . $channel['source'][2] . "\n");
                    } 
                    if (!empty($channel['source'][3])) {
                        print_r($channel['name'] . ',' . $channel['source'][3] . "\n");
                    } 
                    if (!empty($channel['source'][4])) {
                        print_r($channel['name'] . ',' . $channel['source'][4] . "\n");
                    } 
                    if (!empty($channel['source'][5])) {
                        print_r($channel['name'] . ',' . $channel['source'][5] . "\n");
                    } 
                } 
            } 
        } 
    } 
} 

?>