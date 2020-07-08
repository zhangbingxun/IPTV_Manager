<?php

header("Content-Type:text/plain;chartset=utf-8");
class Aes {
    protected $method;
    protected $secret_key;
    protected $iv;
    protected $options;
    public function __construct($key, $method = 'AES-128-CBC', $iv = '', $options = 0) {
        $this->secret_key = isset($key) ? $key : 'fmitv_key';
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

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "http://www.twtvcdn.com/apply/getchannel.php");
        curl_setopt($curl, CURLOPT_TIMEOUT, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, "okhttp/3.12.0");
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'device_info: {"iv":"km4Lx4XLkE418icx","sign":"UiOPCaOLCMYiwJXZZYA/IdU5AJhul9LXHoWUixVDA1VodM2n0WUfeI9oHsQEx/RFfS7+Zg/5YpWduuqoPvFa0vUf5Ngv6wCoFUzADK7ybmNA+BoLTPpgQ3AwoeLJX/u5kiktHmAzcaGNwYKcJXmQH9P5vKzDH8EKAZFzegNKyar7+6bMmBdF8kZnhpJSu229CzAsQJ2pnsgqlZYuddF3oJlt1HoLn47uP2+mQZb5FCT4kwoX0RibzLVHQ4UGf9u8"}'
        ));
        $curlobj = curl_exec($curl);
        $en_aes = json_decode($curlobj, true);
        $aes = new Aes('UBLIVE2020041023', 'AES-128-CBC', $en_aes['iv']);
        $de_aes = $aes->decrypt($en_aes['sign']);
        $json = json_decode($de_aes, true);

print_r($json);

?>