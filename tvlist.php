<?php

// 加密URL
function encode($str){
  $str = base64_encode(urlencode($str));
  return strtr($str, '+/=', '-!_');
}

$header[] = "肥米TV节目源动态代理
QQ：625336209
Email：KwanKaHo@luo2888.cn
" . "\n";

if (empty($vid)){
    $header[] = "节目VID列表：";
    $header[] = "
        # 大陆 #
        CIBN：cibn
        荔枝网：grtn
        北京云：bjy
        天途云：tty
        亦非云：yfy
        央视频：ysp
        芒果TV：mgtv
        重庆有线：cqyx
        珠江宽频：zjkp
        汕头电视：cutv
        咪咕视频：migu
        北京移动：bjyd
        福建移动：fjyd
        辽宁移动：lnyd
        浙江移动：zjyd
        宁夏广电：nxgd
        平遥广电：pygd
        昭通广电：ztgd
        天脉聚源：tvming

        # 港台 #
        肥米TV：fmitv
        普视PV：pvbox
        凤凰电视：fhds
        龙腾TV(需要专用壳)：lttv
        香港无线(节目源来自境外)：tvb
        四季线上(节目源来自境外)：4gtv
        台湾哈TV(节目源来自境外)：hatv
        香港UTV(节目源来自境外)：utvhk
        香港NOW(节目源来自境外)：nowtv
        YouTube(节目源来自境外)：youtube
        安博UBLive(节目源来自境外)：ublive

        # 轮播#
        B站直播：bilibili
        YY直播：yylive
        斗鱼直播：douyu
        虎牙直播：huya
        优酷直播：youku
        电影轮播：movie
        企鹅电竞：egame
        视频网站解析：6ska

        # 自定义 #
        外部列表：third

        # 多线 #
        91看电视(支持多线切换)：91kds
        IPTV345(支持多线切换)：iptv345
        IPTV2020(支持多线切换)：iptv2020
    ";
    $header = preg_replace('#  #', ' ', $header);
}

else if ($vid == '91kds') {
    $url = "http://m.91kds.cn";
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_USERAGENT, 'MQQBrowser/6.2 TBS/045130 MicroMessengeriptv VideoPlayer god/3.0.0 Html5Plus/1.0 (Immersed/29.411766)');
    if (empty($tid)) {
        $header[] = "节目TID列表：";
        $i = 0;
        curl_setopt($curl, CURLOPT_URL, $url);
        $curlobj = curl_exec($curl);
        preg_match_all('/href="(.*?)\.html" data-ajax="false">(.*?)</i', $curlobj, $arrays);
        foreach ($arrays[2] as &$channelname) {
            $header[] = $channelname . '：' . $arrays[1][$i];
            $i++;
        }
    } else {
        curl_setopt($curl, CURLOPT_URL, $url . "/$tid.html");
        $curlobj = curl_exec($curl);
        preg_match_all('/href="jiemu_(.*?)\.html" data-ajax="false">(.*?)</i', $curlobj, $arrays);
        $i = 0;
        foreach ($arrays[2] as &$channelname) {
            $channellist[] = $channelname . ",vid=91kds#id=" . $arrays[1][$i] . "\n";
            $i++;
        }
    }
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
        $header[] = "节目TID列表：" . "\n";
        $i = 0;
        curl_setopt($curl, CURLOPT_URL, $url);
        $curlobj = curl_exec($curl);
        if ($vid == 'iptv345') {
            $curlobj = preg_replace('#>秒开港澳台频道<#', '', $curlobj);
        }
        preg_match_all('/href="\?tid=(.*?)" data-ajax="false">(.*?)</i', $curlobj, $arrays);
        foreach ($arrays[2] as &$channelname) {
            $header[] = $channelname . '：' . $arrays[1][$i];
            $i++;
        }
    } else {
        curl_setopt($curl, CURLOPT_URL, $url . "?tid=$tid");
        $curlobj = curl_exec($curl);
        preg_match_all('/token=(.*?)&tid=(.*?)&id=(.*?)" data-ajax="false">(.*?)</i', $curlobj, $arrays);
        $i = 0;
        foreach ($arrays[4] as &$channelname) {
            $channelname = preg_replace('#凤凰卫视#', '凤凰', $channelname);
            $channelname = preg_replace('#\(HD\)#', '', $channelname);
            $channelname = preg_replace('# #', '', $channelname);
            $channellist[] = $channelname . ",vid=$vid#tid=$tid#id=" . $arrays[3][$i] . "\n";
            $i++;
        }
    }
}

else if ($vid == 'bilibili' || $vid == 'douyu' || $vid == 'huya' || $vid == 'youku' || $vid == 'egame' || $vid == 'yylive' || $vid == '6ska') {
    if (empty($id)) {
        $header[] = "使用方法：链接：http://你的域名/文件名?列表安全码&list&vid=$vid&id=视频地址、房间号";
    } else {
        $channellist[] = "播放地址,vid=$vid#id=" . $id . "\n";
    }
}

else if ($vid == 'fmitv') {
    $i = 0;
    $i2 = 0;
    $clist = file_get_contents("channels/fmitv.txt");
    preg_match_all('/(.*?),(.*?)\n/i', $clist, $arrays);
    $clist2 = file_get_contents("channels/fmitv_cust.txt");
    preg_match_all('/(.*?)#(.*?),(.*?)/i', $clist2, $arrays2);
    foreach ($arrays[1] as &$channelname) {
        $channellist[] = $channelname . ',' . $arrays[2][$i] . "\n";
        $i++;
    }
    foreach ($arrays2[2] as &$channelname2) {
        $channellist[] = $channelname2 . ",vid=fmitv#id=" . $arrays2[1][$i2] . "\n";
        $i2++;
    }
}

else if ($vid == 'tstv') {
    $i = 0;
    $obj = file_get_contents("channels/tstv.txt");
    preg_match_all('/(.*?),(.*?)\/live\/(.*?)\/(.*?)\//i', $obj, $arrays);
    foreach ($arrays[1] as &$channelname) {
            $channellist[] = $channelname . ",vid=tstv#id=" . $arrays[4][$i] . "\n";
        $i++;
    }
}

else if ($vid == 'pvbox') {
    $i = 0;
    $obj = file_get_contents("http://tv.luo2888.cn/get.php?pszb");
    preg_match_all('/(.*?),mitv(.*?)ts/i', $obj, $arrays);
    foreach ($arrays[0] as &$channelname) {
        $header[] = $channelname;
        if ($id  == 'p2p') {
            $header = preg_replace('#mitv#', 'p2p', $header);
            $header = preg_replace('#\.ts#', '&userid=$user=$mac=b21047001cd9$key=614fe9255aee3b71be9e7c6267f90c34', $header);
        }
        $i++;
    }
    $header = preg_replace('# #', '', $header);
    $header = preg_replace('#①#', '', $header);
    $header = preg_replace('#②#', '', $header);
    $header = preg_replace('#1-#', '', $header);
    $header = preg_replace('#2-#', '', $header);
    $header = preg_replace('#臺#', '台', $header);
    $header = preg_replace('#线路#', '', $header);
    $header = preg_replace('#\+#', 'Plus', $header);
    $header = preg_replace('#1J2#', 'J2', $header);
    $header = preg_replace('#2J2#', 'J2', $header);
    $header = preg_replace('#VIU#', 'Viu', $header);
    $header = preg_replace('#HKC#', '有線', $header);
    $header = preg_replace('#1翡翠#', '翡翠', $header);
    $header = preg_replace('#2翡翠#', '翡翠', $header);
    $header = preg_replace('#1无线#', '無線', $header);
    $header = preg_replace('#2无线#', '無線', $header);
    $header = preg_replace('#Viu,#', 'ViuTV,', $header);
    $header = preg_replace('#J5#', '無線財經', $header);
    $header = preg_replace('#CCTV#', 'CCTV-', $header);
    $header = preg_replace('#RTHK#', '港台電視', $header);
}

else if ($vid == 'third') {
    $i = 0;
    if (empty($id)) {
        $header[] = "
使用方法：链接：http://你的域名/文件名?列表安全码&list&vid=$vid&id=TXT文件地址

TXT文件格式：
频道,vid=节目VID#id=节目ID
如：
加勒比海盗2,vid=6ska#id=https://www.iqiyi.com/v_19rrjcdxnc.html
LPL赛事直播,vid=huya#id=lpl
";
    } else {
        $channels = file_get_contents($id);
        preg_match_all('/(.*?),(.*?)\n/i', $channels, $arrays);
        foreach ($arrays[1] as &$channelname) {
            $channellist[] = $channelname . ',' . $arrays[2][$i] . "\n";
            $i++;
        }
    }
}

else {
    $i = 0;
    $channels = file_get_contents("channels/$vid.txt");
    preg_match_all('/(.*?),(.*?)\n/i', $channels, $arrays);
    foreach ($arrays[1] as &$channelname) {
        $channellist[] = $channelname . ',' . $arrays[2][$i] . "\n";
        $i++;
    }
}

if (!empty($channellist)){
    foreach($channellist as $channels) {
        preg_match_all('/(.*?),(.*?)\n/i', $channels, $arrays);
        foreach ($arrays[1] as $channelname) {
            $channel[] = $channelname . ',http://域名/文件名?play&vkeys=' . encode(preg_replace('#\r#', '', $arrays[2][0]));
        }
    }
    $result = array_merge($header, $channel);
} else if (!isset($vid) || strstr($vid, "iptv") || strstr($vid, "pvbox")) {
    $result = $header;
} else {
    $header[] = "该源不存在或已下架" . "\n";
    $result = $header;
}

?>