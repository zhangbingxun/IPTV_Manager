<?php
header("Content-Type:text/plain;chartset=utf-8");

    $i = 1;
    $url = "http://www.nnzhibo.com/geshengpindao/";
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_USERAGENT, 'MQQBrowser/6.2 TBS/045130 MicroMessengeriptv VideoPlayer god/3.0.0 Html5Plus/1.0 (Immersed/29.411766)');
    $listobj = curl_exec($curl);
    $listobj = iconv("gb2312","utf-8//IGNORE",$listobj);
    preg_match_all('/<p><a href="\/zhibo\/(.*?).html" title="(.*?)">(.*?)<\/a><\/p>/i', $listobj, $channellistobj);
    foreach($channellistobj[0] as $channellink) {
        preg_match('/<p><a href="\/zhibo\/(.*?).html" title="(.*?)">(.*?)<\/a><\/p>/i', $channellink, $channelobj);
        $channel[] = $channelobj[2] . ",http://你的域名/文件名?act=play&vid=nnzb&tid=4&id=" . $channelobj[1];
    }
    while ($i != 40) {
        $nurl = $url . "index_$i.html";
        curl_setopt($curl, CURLOPT_URL, $nurl);
        $listobj = curl_exec($curl);
        $listobj = iconv("gb2312","utf-8//IGNORE",$listobj);
        preg_match_all('/<p><a href="\/zhibo\/(.*?).html" title="(.*?)">(.*?)<\/a><\/p>/i', $listobj, $channellistobj);
        foreach($channellistobj[0] as $channellink) {
            preg_match('/<p><a href="\/zhibo\/(.*?).html" title="(.*?)">(.*?)<\/a><\/p>/i', $channellink, $channelobj);
            $channel[] = $channelobj[2] . ",http://你的域名/文件名?act=play&vid=nnzb&tid=4&id=" . $channelobj[1];
        }
        $i++;
    }
    foreach($channel as $channellink) {
        echo $channellink . "\n";
    }
    
?>