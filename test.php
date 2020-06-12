<?php

error_reporting(0);
$id = $_GET['id']; // st1 st2 st3
$bstrURL = 'https://sttv2-api.cutv.com/api/getlivelistST.php?v=2&type=hdtv';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $bstrURL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$data = curl_exec($ch);
curl_close($ch);
$obj = json_decode($data);
$nId = intval(str_replace('st', '', $id)) - 1; //st1 = 0
$bstrLiveUrl = $obj->data->tv[$nId]->liveurl;
$bstrLiveUrl = str_replace('\/\/', '\/500\/', $bstrLiveUrl); // 避免比特率数据丢失造成的播放失败
$bstrURL = 'https://sttv2-api.cutv.com/api/getIP.php';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $bstrURL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$data = curl_exec($ch);
curl_close($ch);
$obj = json_decode($data);
$aes_data = $obj->data[0];
$aes = new Aes('reter4446fdfgdfg', 'AES-128-CBC', '0102030405060708');
$de_aes = $aes->decrypt($aes_data);
$timestamp = dechex(time() + 7200);
$subPath = substr($bstrLiveUrl, stripos($bstrLiveUrl, 'hls.cutv.com') + 12);
$sign = md5($de_aes . 'j5dt4yng0nux7s8bew1r1gip' . $subPath . $timestamp);
header('location:' . $bstrLiveUrl . '?sign=' . $sign . '&t=' . $timestamp);
class Aes {
    protected $method;
    protected $secret_key;
    protected $iv;
    protected $options;
    public function __construct($key, $method = 'AES-128-CBC', $iv = '', $options = 0) {
        $this->secret_key = isset($key) ? $key : 'morefun';
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

?>
