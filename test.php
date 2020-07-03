<?php
// 头部
header("Content-Type:text/plain;chartset=utf-8");

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "http://m.66zhibo.net/e/extend/tv.php?id=14734&gid=2&v=cctv1");
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; Android 8.0.0; Pixel 2 XL Build/OPD1.170816.004) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Mobile Safari/537.36');
        $curlobj = curl_exec($curl);
    $curlobj=mb_convert_encoding($curlobj, 'UTF-8', 'UTF-8,GBK,GB2312,BIG5');
echo $curlobj;
?>