<?php

$nowtime = time();
$signtime = $nowtime + 1908;
$sign = $signtime ^ 209002969;
$url = $_GET['url'] . "&t=" . $nowtime . "&sign=" . $sign;

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_NOBODY, 1);
curl_setopt($curl, CURLOPT_TIMEOUT, 2);
curl_setopt($curl, CURLOPT_MAXREDIRS, 1);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($curl, CURLOPT_USERAGENT, 'longtengtv');
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
    'referer: https://tv.lctv.vip/ssl/longtv.html'
));
curl_exec($curl);
$returnurl = curl_getinfo($curl, CURLINFO_EFFECTIVE_URL);

header("Content-Type:text/plain;chartset=utf-8");
print_r($returnurl);
curl_close($curl);
exit;

?>