<?php
class Aes {
    /**
     * var string $method 加解密方法，可通过openssl_get_cipher_methods()获得
     */
    protected $method;

    /**
     * var string $secret_key 加解密的密钥
     */
    protected $secret_key;

    /**
     * var string $iv 加解密的向量，有些方法需要设置比如CBC
     */
    protected $iv;

    /**
     * var string $options （不知道怎么解释，目前设置为0没什么问题）
     */
    protected $options;

    /**
     * 构造函数
     * 
     * @param string $key 密钥
     * @param string $method 加密方式
     * @param string $iv iv向量
     * @param mixed $options 还不是很清楚
     */
    public function __construct($key, $method = 'AES-128-ECB', $iv = '', $options = 0) {
        // key是必须要设置的
        $this->secret_key = isset($key) ? $key : 'tvkey_luo2888';

        $this->method = $method;

        $this->iv = $iv;

        $this->options = $options;
    } 

    /**
     * 加密方法，对数据进行加密，返回加密后的数据
     * 
     * @param string $data 要加密的数据
     * @return string 
     */
    public function encrypt($data) {
        return openssl_encrypt($data, $this->method, $this->secret_key, $this->options, $this->iv);
    } 

    /**
     * 解密方法，对数据进行解密，返回解密后的数据
     * 
     * @param string $data 要解密的数据
     * @return string 
     */
    public function decrypt($data) {
        return openssl_decrypt($data, $this->method, $this->secret_key, $this->options, $this->iv);
    } 
} 

/**
 * 发送post请求
 * 
 * @param string $url 请求地址
 * @param array $post_data post数据
 */
function send_post($url, $post_data) {
   //初使化init方法
   $curl = curl_init();
   //指定URL
   curl_setopt($curl, CURLOPT_URL, $url);
   //设定请求后返回结果
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
   //忽略证书
   curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
   curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
   //HTTP头
   curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded;charset=utf-8'));
   //UA
   curl_setopt($curl, CURLOPT_USERAGENT, 'MSIE');
   //声明使用POST方式来进行发送
   curl_setopt($curl, CURLOPT_POST, 1);
   //POST数据
   curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
   //发送请求
   $output = curl_exec($curl);
   //关闭curl
   curl_close($curl);
   //返回数据
   return $output;
} 

/**
 * 随机字符串
 * @param int $len
 * @return string
 */
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
if (isset($_GET['dzzb'])) {
    $sig = 14463; //签名密码
    $appname = '大众直播'; //软件名
    $packagename = 'com.iptv.dzzb'; //软件包名
    $url = 'http://zhibo123.top/'; // 后台地址
}
if (isset($_GET['zszb'])) {
    $sig = 12315; //签名密码
    $appname = '郑氏直播'; //软件名
    $packagename = 'www.zszb.top'; //软件包名
    $url = 'http://zhu2.crtv.zstv.top/zszb'; // 后台地址
}
if (isset($_GET['qqds'])) {
    $sig = 12315; //签名密码
    $appname = '全球电视'; //软件名
    $packagename = 'com.quanqiu'; //软件包名
    $url = 'http://47.56.251.109/iptv'; // 后台地址
}
$key = md5($sig . $appname . $packagename . "AD80F93B542B");
$key = md5($key . $appname . $packagename);
$postdata = '"region":"","mac":"11:22:33:44:55:66","androidid":"'. randomStr() . '","model":"Android x86","nettype":"","appname":"' . $appname . '"';

// 头部
header("Content-Type:text/plain;chartset=utf-8");

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
                if (is_array($channel) && strstr($channel['source'][0],"sop://") == false) {
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