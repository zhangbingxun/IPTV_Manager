<?php

if (empty($vid)){
    $channellist = array("
        肥米TV节目源动态代理,KwanKaHo@luo2888.cn
        QQ:625336209
        
        VID列表：
            CIBN：cibn
            荔枝网：grtn
            天途云：tty
            央视频：ysp
            咪咕视频：migu
            香港无线：tvb
            香港有线：utvhk
            B站直播：bili
            斗鱼直播：douyu
            虎牙直播：huya
            龙腾TV：lttv
            平遥广电：pygd
            凤凰电视：fhds
            肥米TV(香港网络)：fmitv
            香港NowTV(限制IP)：nowtv
            YouTube(仅限境外)：youtube
            牛牛直播(支持多线切换)：nnzb
            IPTV345(支持多线切换)：iptv345
            IPTV2020(支持多线切换)：iptv2020
    ");
}

else if ($vid == 'iptv345' || $vid == 'iptv2020') {
    if (empty($tid)) {
        $channellist = array('
            肥米TV节目源动态代理,KwanKaHo@luo2888.cn
            
            TID列表：
                综合：itv
                体育：ty
                央视：ys
                卫视：ws
                北邮：bupt
                港澳台：gt
                港澳台新源：wintv123
                其他（国外）：other
                电影轮播：movie
                咪咕视频：migu
                北方云：bfiptv
                广西联通：ltiptv
                福建移动：fjitv
                湖北广电：hrtn
                黑龙江移动：hlitv
                天途云：tt
                珠江宽频：zjkp');
        if ($vid == 'iptv345') { $channellist = preg_replace('#wintv123#', '不支持', $channellist); }
    } else {
        $url = "https://player.ggiptv.com/iptv.php?tid=$tid";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded'
        ));
        curl_setopt($curl, CURLOPT_USERAGENT, 'MQQBrowser/6.2 TBS/045130 MicroMessengeriptv VideoPlayer god/3.0.0 Html5Plus/1.0 (Immersed/29.411766)');
        $curlobj = curl_exec($curl);
        preg_match_all('/token=(.*?)&tid=(.*?)&id=(.*?)" data-ajax="false">(.*?)</i', $curlobj, $channel);
        $i = 0;
        foreach ($channel[4] as &$channelname) {
            $channelname = preg_replace('#凤凰卫视#', '凤凰', $channelname);
            $channelname = preg_replace('#\(HD\)#', '', $channelname);
            $channelname = preg_replace('# #', '', $channelname);
            $channellist[] = $channelname . ",http://你的域名/文件名?play&vid=$vid&tid=$tid&id=" . $channel[3][$i];
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

else if ($vid == 'bili' || $vid == 'douyu' || $vid == 'huya') {
    $channellist = array("
        参考链接：http://你的域名/文件名?play&vid=$vid&id=主播房间号
    ");
}

else if ($vid == 'tvb') {
    $channellist = array('
        无线新闻,http://你的域名/文件名?play&vid=tvb&id=news
        无线财经资讯,http://你的域名/文件名?play&vid=tvb&id=finance
    ');
}

else if ($vid == 'fhds') {
    $channellist = array('
        凤凰资讯,http://你的域名/文件名?play&vid=fhds&id=fhzx
        凤凰中文,http://你的域名/文件名?play&vid=fhds&id=fhzh
        凤凰香港,http://你的域名/文件名?play&vid=fhds&id=fhhk
    ');
}

else if ($vid == 'utvhk') {
    $channellist = array('
        有线新闻,http://你的域名/文件名?play&vid=utvhk&id=657254667
        有线综合娱乐,http://你的域名/文件名?play&vid=utvhk&id=662547571
        有线财经资讯,http://你的域名/文件名?play&vid=utvhk&id=657254837
    ');
}

else if ($vid == 'nowtv') {
    $channellist = array('
        NOW直播,http://你的域名/文件名?play&vid=nowtv&id=331
        NOW新闻,http://你的域名/文件名?play&vid=nowtv&id=332
        NOW体育,http://你的域名/文件名?play&vid=nowtv&id=630
    ');
}

else if ($vid == 'pygd') {
    $channellist = array('
        晋中一台,http://你的域名/文件名?play&vid=pygd&id=006
        晋中二台,http://你的域名/文件名?play&vid=pygd&id=178
    ');
}

else {
    $channellist = array(file_get_contents("channels/$vid.txt"));
}

?>