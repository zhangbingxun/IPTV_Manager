<?php

$channellist[] = "肥米TV节目源动态代理
Email：KwanKaHo@luo2888.cn
QQ：625336209
";

if (empty($vid)){
    $channellist[] = "节目表获取链接：http://域名/文件名?list&vid=节目VID

        节目VID列表：
            CIBN：cibn
            荔枝网：grtn
            北京云：bjy
            天途云：tty
            央视频：ysp
            北京移动：bjyd
            福建移动：fjyd
            咪咕视频：migu
            香港无线：tvb
            香港有线：utvhk
            平遥广电：pygd
            昭通广电：ztgd
            凤凰电视：fhds
            时光电视：tstv
            B站直播：bilibili
            YY直播：yylive
            斗鱼直播：douyu
            虎牙直播：huya
            优酷直播：youku
            企鹅电竞：egame
            影视解析(id是网站地址)：6ska
            肥米TV(香港网络)：fmitv
            香港NowTV(限制IP)：nowtv
            YouTube(仅限境外)：youtube
            牛牛直播(支持多线切换)：nnzb
            IPTV345(支持多线切换)：iptv345
            IPTV2020(支持多线切换)：iptv2020
    ";
}

else if ($vid == 'iptv345' || $vid == 'iptv2020') {
    $url = "https://player.ggiptv.com/iptv.php";
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/x-www-form-urlencoded'
    ));
    curl_setopt($curl, CURLOPT_USERAGENT, 'MQQBrowser/6.2 TBS/045130 MicroMessengeriptv VideoPlayer god/3.0.0 Html5Plus/1.0 (Immersed/29.411766)');
    if (empty($tid)) {
        $i = 0;
        curl_setopt($curl, CURLOPT_URL, $url);
        $curlobj = curl_exec($curl);
        preg_match_all('/href="\?tid=(.*?)" data-ajax="false">(.*?)</i', $curlobj, $channel);
        foreach ($channel[2] as &$channelname) {
            $channellist[] = $channelname . '：' . $channel[1][$i];
            $i++;
        }
        if ($vid == 'iptv345') { $channellist = preg_replace('#wintv123#', '不支持', $channellist); }
    } else {
        curl_setopt($curl, CURLOPT_URL, $url . "?tid=$tid");
        $curlobj = curl_exec($curl);
        preg_match_all('/token=(.*?)&tid=(.*?)&id=(.*?)" data-ajax="false">(.*?)</i', $curlobj, $channel);
        $i = 0;
        foreach ($channel[4] as &$channelname) {
            $channelname = preg_replace('#凤凰卫视#', '凤凰', $channelname);
            $channelname = preg_replace('#\(HD\)#', '', $channelname);
            $channelname = preg_replace('# #', '', $channelname);
            $channellist[] = $channelname . ",http://域名/文件名?play&vid=$vid&tid=$tid&id=" . $channel[3][$i];
            $i++;
        }
    }
}

else if ($vid == 'longtv') {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, 'http://sh.woaizhuguo.cn/');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.138 Mobile Safari/537.36');
    $list = curl_exec($curl);
    preg_match_all('/(?s)<a href="\/news\/view\/id\/(.*?)">(.*?)<dt>(.*?)<\/dt>/i', $list, $channel);
    $i = 0;
    foreach ($channel[3] as &$channelname) {
        $channelname = preg_replace('#（(.*?)）#', '', $channelname);
        $channelname = preg_replace('#\((.*?)\)#', '', $channelname);
        $channelname = preg_replace('#\((.*?)）#', '', $channelname);
        $channelname = preg_replace('#\[17A\]#', '', $channelname);
        $channelname = preg_replace('#\[TN\]#', '', $channelname);
        $channelname = preg_replace('#\[#', '', $channelname);
        $channelname = preg_replace('#\]#', '', $channelname);
        $channelname = preg_replace('#\t#', '', $channelname);
        $channelname = preg_replace('# #', '', $channelname);
        $channellist[] =  $channelname . ",http://你的域名/文件名?play&vid=longtv&id=" . $channel[1][$i];
        $i++;
    }
}

else if ($vid == 'bilibili' || $vid == 'douyu' || $vid == 'huya' || $vid == 'youku' || $vid == 'egame' || $vid == 'yylive') {
    $channellist[] = "参考链接：http://域名/文件名?play&vid=$vid&id=主播房间号";
}

else if ($vid == 'fmitv') {
    $i = 0;
    $obj = file_get_contents("channels/fmitv_cust.txt");
    $channellist[] = file_get_contents("channels/fmitv.txt");
    preg_match_all('/(.*?)#(.*?),(.*?)/i', $obj, $channel);
    foreach ($channel[2] as &$channelname) {
            $channellist[] = $channelname . ",http://域名/文件名?play&vid=fmitv&id=" . $channel[1][$i];
        $i++;
    }
}

else {
    $channellist[] = file_get_contents("channels/$vid.txt");
}

?>