<?php

$id = $_GET['id'];
$nowtime = time();
$time = $_GET['time'];
$token = $_GET['token'];

if (abs($nowtime - $time) > 5) {
    exit('盗链狗，你想干什么！');
} else if ($token != md5('fmitv_' . $id . $time)) {
    exit('盗链狗，你想干什么！');
}

$filename = "fmitv_4gtv-channel_" . $id . ".m3u8";
$url = "http://hdtv.ub1818.com/ublive/index_$id.m3u8";
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.88 Safari/537.36");
$m3u8 = curl_exec($curl);
header("Content-type:application/octet-stream");
header("Content-Disposition: attachment; filename=" . $filename);
print_r($m3u8);

?>
