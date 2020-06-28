<?php

if (!isset($_GET['qq625336209'])) {
    exit('盗链狗，你想干什么！');
}

$curl = curl_init();
$url = "http://sytv.applinzi.com/fct.php";
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.88 Safari/537.36");
$m3u8 = curl_exec($curl);
header("Content-type:application/octet-stream");
header("Content-Disposition: attachment; filename=fmitv_tvbjade.m3u8");
print_r($m3u8);

?>
