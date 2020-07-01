<?php
// 头部
header("Content-Type:text/plain;chartset=utf-8");

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://jx.126c.cn/?url=https://www.iqiyi.com/v_19rrjcdxnc.html');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; Android 8.0.0; Pixel 2 XL Build/OPD1.170816.004) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Mobile Safari/537.36');
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        $data = curl_exec($curl);
        $data=mb_convert_encoding($data, 'UTF-8', 'UTF-8,GBK,GB2312,BIG5');
print_r($data);
?>