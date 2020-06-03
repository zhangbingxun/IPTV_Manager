<?php

        header("Content-Type:text/plain;chartset=utf-8");
        $id = $_GET['id'];
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://news.now.com/mobile/live');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; Android 8.0.0; Pixel 2 XL Build/OPD1.170816.004) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Mobile Safari/537.36');
        $curlobj = curl_exec($curl);
        preg_match('/url: "(.*?)"/i', $curlobj, $linkobj);
        curl_setopt($curl, CURLOPT_URL, $linkobj[1] . "?mode=prod&audioCode=&format=HLS&channelno=$id");
        $json = curl_exec($curl);
        $urlobj = json_decode($json,true);
        $playurl = $urlobj['asset']['hls']['adaptive']['0'];
        header('location:' . $playurl);

?>