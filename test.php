<?php

header("Content-Type:text/plain;chartset=utf-8");
$id = $_GET['id'];

        $url = "http://stb.topmso.com.tw:8080/csr_mobile_client_web/ottLiveStreamGroupAction.do?method=getLiveStreamGroupForPhone_byChannel&ottCustNo=";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_TIMEOUT, 5);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $curlobj = curl_exec($curl);
        curl_close($curl);
        preg_match('/liveedge2\/(.*?)_999_/i', $curlobj, $linkobj);
print_r($linkobj);
exit;

?>