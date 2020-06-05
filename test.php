<?php

if (isset($_GET['list'])) {

    header("Content-Type:text/plain;chartset=utf-8");
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, 'http://vip.cietv.com/mlive.asp?id=2&see=1');
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_COOKIE , 'HX%5FUSER=User%5FName=gysguan&userhidden=2&uid=28089&User%5FPwd=fa95ba7e62717d39a015b7d562717d39a015b7d5;');
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.138 Mobile Safari/537.36');
    $listobj = curl_exec($curl);
    $listobj=mb_convert_encoding($listobj, 'UTF-8', 'UTF-8,GBK,GB2312,BIG5');

    preg_match_all('/id="(.*?)" title="(.*?)"/i', $listobj, $channel);
    $i = 0;
    foreach ($channel[2] as &$channelname) {
        $playurl = $channel[1][$i];
        $channelname = preg_replace('# #', '', $channelname);
        if (strstr($playurl,"http") == false) {
            $playurl = 'http://vip.cietv.com' . $playurl;
        }
        echo $channelname . ',' . $playurl . "\n";
        $i++;
    }
    
    exit;
    
} else {

    header('HTTP/1.1 403 Forbidden');
    exit;

}

?>