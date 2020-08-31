<?php
//
// +----------------------------------------------------------------------+
// | PHP version 7                                                        |
// +----------------------------------------------------------------------+
// | 此代码项目归 www.luo2888.cn 所有，盗版、倒卖死全家！   |
// +----------------------------------------------------------------------+
// | 下列代码内容为原创内容，归本人所有。                                 |
// | 任何人未经本人许可，不得进行抄袭、复制全部或者部分内容。             |
// +----------------------------------------------------------------------+
// | 作者: KwanKaHo <kwankaho@luo2888.cn>                                 |
// +----------------------------------------------------------------------+
//

class curl {
    var $ch; //CRUL对象
    var $url;
    private static $_instance; //静态本类对象

    //构造函数
    function __construct() {
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT, 60);
    }

    //静态方法入口
    static function c() {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    //get方法提交
    function get($url) {
        $this->url = $url;
        curl_setopt($this->ch, CURLOPT_URL, $url);
        return $this->exec_data();
    }

    //post方法提交
    function post($url, $str = "") {
        $this->url = $url;
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_POST, 1);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $str);
        return $this->exec_data();
    }

    //CURL 发送后返回的数据
    private function exec_data() {
        return curl_exec($this->ch);
    }

    //自定义header
    function set_header($arr = []) {
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $arr);
        return $this;
    }

    //启用GZIP解码
    function set_gzip() {
        curl_setopt($this->ch, CURLOPT_ENCODING, 'gzip');
        return $this;
    }

    //设置显示HEADER信息
    function set_show_header() {
        curl_setopt($this->ch, CURLOPT_HEADER, true);
        return $this;
    }

    //设置UA标识
    function set_ua($selected = "pc") {
        $ua_arr = [
"PC" => "Mozilla/5.0 (Windows NT 5.1; zh-CN) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/30.0.1599.101 Safari/537.36",
"QQ" => "Mozilla/5.0 (Linux; Android 5.0; SM-N9100 Build/LRX21V) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/37.0.0.0 Mobile Safari/537.36 V1_AND_SQ_5.3.1_196_YYB_D QQ/5.3.1.2335 NetType/WIFI",
"WeiXin" => "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.116 Safari/537.36 QBCore/3.53.1159.400 QQBrowser/9.0.2524.400 Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36 MicroMessenger/6.5.2.501 NetType/WIFI WindowsWechat",
"IOS" => "Mozilla/5.0 (iPhone; CPU iPhone OS 7_1_2 like Mac OS X) AppleWebKit/537.51.2 (KHTML, like Gecko) Mobile/11D257 MicroMessenger/6.0.1 NetType/WIFI", ];
        if (empty($ua_arr[$selected])) {
            $ua = $selected;
        } else {
            $ua = $ua_arr[$selected];
        }
        curl_setopt($this->ch, CURLOPT_USERAGENT, $ua);
        return $this;
    }

    //设置referer
    function set_ref($ref = false) {
        if ($ref) {
            curl_setopt($this->ch, CURLOPT_REFERER, $ref);
            return $this;
        } else {
            curl_setopt($this->ch, CURLOPT_REFERER, $this->url);
            return $this;
        }
    }

    //设置启用SSL协议
    function set_ssl() {
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, false);
        return $this;
    }

    //释放资源
    function close() {
        curl_close($this->ch);
        $this->ch = null;
        $this->url = null;
        self::$_instance = null;
    }

    //析构函数
    function __destruct() {
        $this->close();
    }
}

?>