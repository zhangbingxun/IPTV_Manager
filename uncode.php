<?php
class Aes
{
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
     *
     */
    public function __construct($key, $method = 'AES-128-ECB', $iv = '', $options = 0)
    {
        // key是必须要设置的
        $this->secret_key = isset($key) ? $key : 'morefun';

        $this->method = $method;
 
        $this->iv = $iv;
 
        $this->options = $options;
    }
 
    /**
     * 加密方法，对数据进行加密，返回加密后的数据
     *
     * @param string $data 要加密的数据
     *
     * @return string
     *
     */
    public function encrypt($data)
    {
        return openssl_encrypt($data, $this->method, $this->secret_key, $this->options, $this->iv);
    }
 
    /**
     * 解密方法，对数据进行解密，返回解密后的数据
     *
     * @param string $data 要解密的数据
     *
     * @return string
     *
     */
    public function decrypt($data)
    {
        return openssl_decrypt($data, $this->method, $this->secret_key, $this->options, $this->iv);
    }
}

//解密字符
$str="data3.php抓包数据放在这里";
$sig=12315;
$appname='写APP名字';
$packagename='写包名';
$key=md5($sig.$appname.$packagename."AD80F93B542B");
$key=md5($key.$appname.$packagename);
$randkey="13ae41c997b396765bd57e3f8786dbf7";
$key=md5($key.$randkey);
$key=substr($key,7,16);
$encrypted=substr($str,128,strlen($str)-128);
$encrypted=str_replace( "y","#", $encrypted);
$encrypted=str_replace( "t","y", $encrypted);
$encrypted=str_replace( "#","t", $encrypted);
$encrypted=str_replace( "b","&", $encrypted);
$encrypted=str_replace("f","b",  $encrypted);
$encrypted=str_replace( "&", "f",$encrypted);
$aes = new Aes($key);
$encrypted =$aes->decrypt($encrypted);
$str1=gzuncompress(base64_decode($encrypted));

echo $str1;

?>
