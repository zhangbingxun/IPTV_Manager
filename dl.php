<?php
error_reporting(E_ERROR);
//
// +----------------------------------------------------------------------+
// | PHP version 7                                                        |
// +----------------------------------------------------------------------+
// | 此代码项目归 www.luo2888.cn 所有，盗版、倒卖死全家，你妈不得好死！   |
// +----------------------------------------------------------------------+
// | 下列代码内容为原创内容，归本人所有。                                 |
// | 任何人未经本人许可，不得进行抄袭、复制全部或者部分内容。             |
// +----------------------------------------------------------------------+
// | 作者: KwanKaHo <kwankaho@luo2888.cn>                                 |
// +----------------------------------------------------------------------+
//

function GetIP() {
    $IP = $_SERVER['REMOTE_ADDR'];
    if (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {
        $IP = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) AND preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
        foreach ($matches[0] AS $XIP) {
            if (!preg_match('#^(10|172\.16|192\.168)\.#', $XIP)) {
                $IP = $XIP;
                break;
            } 
        } 
    } 
    return $IP;
} 

$remote = GetIP();
$ips = array(
    '127.0.0.1',
    '149.129.98.202', // Me
    '140.143.146.222', // http_id=2 2020-04
    '103.133.179.97', // http_id=2 2020-04
    '23.83.239.54', // http_id=2 2020-04
    '149.129.75.149', // http_id=3 2020-04
    '103.145.39.149', // http_id=4 2020-04
    '123.56.149.168', // http_id=5 2020-04
    '114.67.203.190', // http_id=6
    '198.44.188.193', // http_id=7 2020-04
    '111.230.178.212', // http_id=8 2020-06
    '113.87.129.140', // http_id=8 2020-06
    '39.99.174.216', // http_id=9 2020-06
    '47.56.247.8', // http_id=10 2020-06
    '39.101.217.17', // proxy_id=1 2020-04
    '47.114.56.181', // proxy_id=2 2020-04
);

$banips = array(
    '140.143.146.222', // http_id=2
    '103.133.179.97', // http_id=2
    '23.83.239.54', // http_id=2
);

if (isset($_POST['fmitv_proxy']) || isset($_GET['url'])) {
    
    $json = $_POST['fmitv_proxy'];
    $obj = json_decode($json);
    $token = $obj->token;
    $line = $obj->line;
    $vid = $obj->video;
    $tid = $obj->tid;
    $id = $obj->id;
    if (isset($_GET['url'])) {
        $token = $_GET['token'];
        $line = $_GET['line'];
        $vid = $_GET['vid'];
        $tid = $_GET['tid'];
        $id = $_GET['id'];
        goto url;
    }

    if (in_array($remote,$ips) == false) {
        $data = json_encode(
            array(
                'playurl' => 'http://tv.luo2888.cn/fmitv.mp4'
            )
        );
        echo $data;
        exit;
    }

    url:

    if ($vid == 'bili') {
        $obj = file_get_contents("http://hk.luo2888.cn:8118/bilibili.php?id=$id");
        $playurl = 'https://cn-hbxy-cmcc-live-01.live-play.acgvideo.com/live-bvc/live_' . $obj . '.m3u8';
    }

    if ($vid == 'cibn') {
        $url = "http://api.epg2.cibn.cc/v1/loopChannelList?epgId=1000";
        $obj = file_get_contents($url);
        $channeldata = json_decode($obj, true);
        $playurl = $channeldata['data']['channelList'][$id]['m3u8'];
    }

    if ($vid == 'douyu') {
        $json =  file_get_contents("https://web.sinsyth.com/lxapi/douyujx.x?roomid=$id");
        preg_match('/\/live\\\\\/(.*?)_2000/i', $json, $link);
        $playurl = 'http://tx2play1.douyucdn.cn/live/' . $link[1] . '.m3u8';
    }
    
    if ($vid == 'migu') {
        $playurl = file_get_contents("http://www.luo2888.cn/migu.php?id=$id");
        $playurl = preg_replace('#hlsmgsplive.miguvideo.com:8080#', 'mgsp.live.miguvideo.com:8088', $playurl);
    }
    
    if ($vid == 'fmitv') {
        if ($id == 'tvbjade') {
            $playurl = 'http://vip.cietv.com/gtpd/fct.asp';
        }
        else if ($id == 'tvbj2') {
            $playurl = 'http://vip.cietv.com/gtpd/j2.asp';
        }
        else {
            $playurl = file_get_contents("http://hk.luo2888.cn:8118/channels/$id");
        }
    }
    
    if ($vid == 'utvhk') {
        $obj = file_get_contents("http://miguapi.utvhk.com:18083/clt/publish/resource/UTV_NEW/playData.jsp?contentId=$id&nodeId=$id&rate=5&playerType=4&objType=LIVE&nt=4");
        preg_match('/"url": "(.*?)"/i', $obj, $linkobj);
        $playurl = $linkobj[1];
    }
    
    if ($vid == 'nowtv') {
        $obj = file_get_contents("https://news.now.com/mobile/live");
        preg_match('/url: "(.*?)"/i', $obj, $linkobj);
        $json = file_get_contents($linkobj[1] . "?mode=prod&audioCode=&format=HLS&channelno=$id");
        $urlobj = json_decode($json,true);
        $playurl = $urlobj['asset']['hls']['adaptive']['0'];
    }

    if ($vid == 'fhds') {
        $ids = array(
                    "fhzx"=>"/live/pin_720p",
                    "fhzh"=>"/live/pcc_720p",
                    "fhhk"=>"/live/phk_720p",
        );
        $hexString = dechex(time()+1800);
        $substring = $ids[$id];
        $str2 = "obb9Lxyv5C".$substring.$hexString;
        $playurl = 'http://qlive.fengshows.cn'.$ids[$id].'.flv?txSecret='.md5($str2).'&txTime='.$hexString;
    }

    if ($vid == 'tty') {
        $token_access_url = "http://access.ttcatv.tv/account/login?accounttype=2&deviceno=12&isforce=1&pwd=96e79218965eb72c92a549dd5a330112&devicetype=3&account=TF0P0623XL9ACAN5";
        $res = file_get_contents($token_access_url);
        $result = json_decode($res, true);
        $access_token = $result['access_token'];
        $playurl = "http://httpdvb.slave.ttcatv.tv:13164/playurl?playtype=live&protocol=http&accesstoken=" . $access_token . "&programid=4200000" . $id . "&playtoken=ABCDEFGHI";
    }
    
    if ($vid == 'pygd') {
        $token_access_url = "http://access.pygdzhcs.com/account/login?accounttype=2&accesstoken=R5EB59CBDU309CC066K777D2D19I35E9EE78PBM30F3219V10457Z225B8WE73963A96D1&deviceno=A92E1686F0DEECFDDC346BBF7CB8C1BE&isforce=1&pwd=f91879321d16703dc86a12a68ad9d6cd&devicetype=3&account=luo2888";
        $res = file_get_contents($token_access_url);
        $result = json_decode($res, true);
        $access_token = $result['access_token'];
        $playurl = "http://httpdvb.slave.pygdzhcs.com:13164/playurl?playtype=live&protocol=http&accesstoken=" . $access_token . "&programid=4200000" . $id . "&playtoken=ABCDEFGHI";
    }
    
    if ($vid == 'huya') {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://m.huya.com/$id");
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; Android 8.0.0; Pixel 2 XL Build/OPD1.170816.004) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Mobile Safari/537.36');
        $curlobj = curl_exec($curl);
        preg_match('/liveLineUrl = "\/\/(.*?)\&fm/i', $curlobj, $linkobj);
        $playurl = 'http://' . $linkobj[1];
        $playurl = preg_replace('#m3u8#', 'flv', $playurl);
    }

    if ($vid == 'grtn') {
        $url = "http://www.gdtv.cn/m2o/player/channel_xml.php?id=$id";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 2.0.50727;)');
        $curlobj = curl_exec($curl);
        $curlobj = json_decode(json_encode(simplexml_load_string($curlobj)) , true);
        $linkurl = str_replace('stream1', 'nclive', $curlobj['drm'] . '?url=' . $curlobj['video']['@attributes']['baseUrl'] . $curlobj['video']['item'][0]['@attributes']['url'] . 'live.m3u8');
        curl_setopt($curl, CURLOPT_URL, $linkurl);
        $playurl = curl_exec($curl);
    }
    
    if ($vid == 'tvb') {
        $urlobj = file_get_contents("http://news.tvb.com/ajax_call/getVideo.php?token=http%3A%2F%2Ftoken.tvb.com%2Fstream%2Flive%2Fhls%2Fmobile_" . $id . ".smil%3Fapp%3Dnews%26feed");
        $obj = json_decode($urlobj, true);
        $playurl = $obj['url'];
    }
    
    if ($vid == 'nnzb') {
        if (empty($line)) {
            $line = 0;
        }
        $obj = file_get_contents("http://www.nnzhibo.com/e/DownSys/play/?classid=$tid&id=$id&pathid=$line");
        preg_match('/video-url="(.*?)"/i', $obj, $linkobj);
        if (empty($linkobj)){
            preg_match('/url: "(.*?)"/i', $obj, $linkobj);
        }
        $playurl = $linkobj[1];
    }

    if ($vid=='ysp') {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "http://www.itvi.vip/cctv.php?id=" . $id);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_NOBODY, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 1); 
        curl_setopt($curl, CURLOPT_MAXREDIRS, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, 'MITV');
        curl_exec($curl);
        $playurl = curl_getinfo($curl, CURLINFO_EFFECTIVE_URL);
    }

    if ($vid=='mltv') {
        $sig=12315;
        $key=12315;
        $time=time();
        $ids = array(
                    "1"=>"hkyx",
        );
        $sign = md5($key.$time."303543214".$sig);
        $url = 'http://47.56.251.109/iptv/api/' . $ids[$tid] . '.php?id=' . $id . '&t=' . $time . '&sign=' . $sign;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_NOBODY, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 1); 
        curl_setopt($curl, CURLOPT_MAXREDIRS, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, 'MITV');
        curl_exec($curl);
        $playurl = curl_getinfo($curl, CURLINFO_EFFECTIVE_URL);
    }

    if ($vid == 'youtube') {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://www.youtube.com/watch?v=' . $id);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.88 Safari/537.36");
        curl_setopt($curl, CURLOPT_REFERER, "https://www.youtube.com/");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $string = curl_exec($curl);
        
        $hlsManifestUrl = '/,\\\\"hlsManifestUrl\\\\":\\\\"(.*?)\\\\"/m';
        preg_match_all($hlsManifestUrl, $string, $matches, PREG_PATTERN_ORDER, 0);
        
        $var1=$matches[1][0];
        $var1=str_replace("\/", "/", $var1);
        
        curl_setopt($curl, CURLOPT_URL, $var1);
        $man = curl_exec($curl);
        /*
         96 = 1920x1080 
         95 = 1280x720
         94 = 854x480
         93 = 640x360
        */
        preg_match_all('/(https:\/.*\/95\/.*index.m3u8)/U',$man,$matches, PREG_PATTERN_ORDER);
        $playurl=$matches[1][0];
    }

    if ($vid == 'longtv') {
        $curl = curl_init();
        logined:
        curl_setopt($curl, CURLOPT_URL, 'http://sh.woaizhuguo.cn/news/view/id/' . $id);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_COOKIE , 'PHPSESSID=abcdefghijklmnopqrstuvwxyz');
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.138 Mobile Safari/537.36');
        $listobj = curl_exec($curl);
        preg_match('/<video  src="(.*?)"/i', $listobj, $linkobj);
        if (empty($linkobj)){
            curl_setopt($curl, CURLOPT_URL, 'http://sh.woaizhuguo.cn/login/mlogin');
            curl_setopt($curl, CURLOPT_POST, 1);  //声明使用POST方式来进行发送
            curl_setopt($curl, CURLOPT_POSTFIELDS, 'uname=13651761912&upass=112233');  //POST数据
            curl_exec($curl);
            goto logined;
        }
        $playurl = $linkobj[1];
        curl_close($curl);
    }
    
    if ($vid == 'iptv345') {
        if (!empty($line)) {
            $part = '&p=' . $line;
        }
        $url = "http://m.iptv345.com/";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url . "?act=play&tid=$tid&id=$id");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; Android 8.0.0; Pixel 2 XL Build/OPD1.170816.004) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Mobile Safari/537.36');
        $curlobj = curl_exec($curl);
        preg_match('/<option value="(.*?)"/i', $curlobj, $linkobj);
        $linkurl = $linkobj[1] . $part;
        $linkurl = preg_replace('#http://m.iptv789.com/player.m3u8#', 'http://play.ggiptv.com:13164/play.m3u8', $linkurl);
        $linkurl = preg_replace('#http://m.iptv.com/player.m3u8#', 'http://play.ggiptv.com:13164/play.m3u8', $linkurl);
        curl_setopt($curl, CURLOPT_URL, $linkurl);
        curl_setopt($curl, CURLOPT_TIMEOUT,2); 
        curl_setopt($curl, CURLOPT_NOBODY, 1);
        curl_setopt($curl, CURLOPT_MAXREDIRS, 2);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_exec($curl);
        $playurl = curl_getinfo($curl, CURLINFO_EFFECTIVE_URL);
        curl_close($curl);
    }
    
    if ($vid == 'iptv2020') {
        if (!empty($line)) {
            $part = '&p=' . $line;
        }
        $url = "https://player.ggiptv.com/iptv.php";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url . "?tid=$tid");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded'
        ));
        curl_setopt($curl, CURLOPT_USERAGENT, 'MQQBrowser/6.2 TBS/045130 MicroMessengeriptv VideoPlayer god/3.0.0 Html5Plus/1.0 (Immersed/29.411766)');
        $listobj = curl_exec($curl);
        preg_match_all('/<li><a href="(.*?)" data-ajax="false">(.*?)<\/a><\/li>/i', $listobj, $channelobj);
        foreach($channelobj[0] as $channellink) {
            preg_match("/token=(.*?)&tid=$tid&id=$id\"/i", $channellink, $tokenobj);
            if (!empty($tokenobj)){
                $token = $tokenobj[1];
                break;
            }
        }
        curl_setopt($curl, CURLOPT_URL, $url . "?act=play&token=$token&tid=$tid&id=$id");
        $curlobj = curl_exec($curl);
        if (strstr($curlobj, "src1591") != false) {
            preg_match('/var src(.*)="(.*)"/i', $curlobj, $linkobj);
            $linkurl = $linkobj[2] . $part;
        } else {
            preg_match('/<option value="(.*?)"/i', $curlobj, $linkobj);
            $linkurl = $linkobj[1] . $part;
        }
        if ($tid == 'bfiptv') {
            $playurl = $linkurl;
        } else {
            curl_setopt($curl, CURLOPT_URL, $linkurl);
            curl_setopt($curl, CURLOPT_TIMEOUT,2); 
            curl_setopt($curl, CURLOPT_NOBODY, 1);
            if (strstr($tid, "wintv") != false) {
                curl_setopt($curl, CURLOPT_MAXREDIRS, 1);
            } else {
                curl_setopt($curl, CURLOPT_MAXREDIRS, 2);
            }
            curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($curl, CURLOPT_USERAGENT, 'Lavf/57.83.100');
            curl_exec($curl);
            $playurl = curl_getinfo($curl, CURLINFO_EFFECTIVE_URL);
            curl_close($curl);
        }
    }
    
    if (strstr($vid, "lttv") != false) {
        if ($vid == 'lttv'){
            $url = "http://bbdd.228888888.xyz:8081/hls/$id.m3u8";
        }
        else if ($vid == 'lttv2'){
            $url = "http://ddbb.228888888.xyz:8081/live/$id.m3u8";
        }
        else if ($vid == 'lttv3'){
            $url = "http://bddb.228888888.xyz:8081/live/$id.m3u8";
        }
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_NOBODY, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 15); 
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Lavf/57.83.100');
        curl_exec($curl);
        $playurl = curl_getinfo($curl, CURLINFO_EFFECTIVE_URL);
    }
    
	if (strstr($playurl, "lctv") != false || strstr($playurl, "starray") != false) {
	    $playurl = null;
	}
	
    $data = json_encode(
        array(
            'playurl' => $playurl
        )
    );
    echo $data;
    exit;
    
} else if (isset($_POST['fmitv_list'])) {
    
    $json = $_POST['fmitv_list'];
    $obj = json_decode($json);
    $vid = $obj->video;
    $tid = $obj->tid;
    
    if (in_array($remote,$ips) == false) {
        $data = json_encode(
            array(
                'tvlist' => array("查询授权失败,请联系客服kwankaho@luo2888.cn，你的连接IP是：" . $remote)
            )
        );
        echo $data;
        exit;
    }

    require_once('tvlist.php');
    $channellist = preg_replace('# #', '', $channellist);
    
    $data = json_encode(
        array(
            'tvlist' => $channellist
        )
    );
    
    echo $data;
    exit;

} else {

    header('HTTP/1.1 403 Forbidden');
    exit;

}

?>