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
    $options = array('http' => array('method' => 'POST',
            'header' => 'Content-type:application/x-www-form-urlencoded',
            'content' => $postdata,
            'timeout' => 15 * 60 // 超时时间（单位:s）
            )
        );
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    return $result;
} 

// 配置
$sig = 12315; //签名密码
$appname = '潮汕人直播'; //软件名
$packagename = 'com.vv.iptv'; //软件包名
$url = 'http://itv.hsincs.site/yd'; // 后台地址
$key = md5($sig . $appname . $packagename . "AD80F93B542B");
$key = md5($key . $appname . $packagename);
$postdata = '"region":"境外","mac":"11:22:33:44:55:66","androidid":"1234567890abcdef","model":"Android x86","nettype":"保留地址","appname":"' . $appname . '"';

// 头部
header("Content-Type:application/octet-stream;chartset=uft-8");
header("Content-Disposition: attachment; filename=$appname.txt");

// 登录
$loginkey = substr($key, 5, 16);
$login_post = 'login={' . $postdata . '}';
$loginstr = send_post($url . '/login3.php', $login_post);
$login = new Aes($loginkey);
$loginjson = $login->decrypt($loginstr);
$logindata = json_decode($loginjson, true);
$randkey = $logindata['randkey'];
$dataurl = $logindata['dataurl'];

// 获取频道
$datakey = md5($key . $randkey);
$datakey = substr($datakey, 7, 16);
$data_post = 'data={' . $postdata . ',' . '"rand":"' . $randkey . '"' . '}';
$datastr = send_post($dataurl, $data_post);
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