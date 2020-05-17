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
    '132.232.115.61', // Me
    '140.143.146.222', // http_id=2
    '103.145.38.239', // http_id=2
    '103.133.179.97', // http_id=2
    '149.129.75.149', // http_id=3 3m 2020-04
    '103.145.39.149', // http_id=4 3m 2020-04
    '123.56.149.168', // http_id=5
    '114.67.203.190', // http_id=6
    '198.44.188.193', // http_id=7
    '39.101.217.17', // proxy_id=1
    '47.114.56.181', // proxy_id=2
);

$banips = array(
    '140.143.146.222', // http_id=2
    '103.145.38.239', // http_id=2
    '103.133.179.97', // http_id=2
);

if (in_array($remote,$ips) == false) {
    if (isset($_GET['url'])){
        goto url;
    }
    $data = json_encode(
        array(
            'playurl' => 'http://tv.luo2888.cn/fmitv.mp4'
        )
    );
    echo $data;
    exit;
}

url:
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
    }

    if ($vid == 'cibn') {
        $url = "http://api.epg2.cibn.cc/v1/loopChannelList?epgId=1000";
        $obj = file_get_contents($url);
        $channeldata = json_decode($obj, true);
        $playurl = $channeldata['data']['channelList'][$id]['m3u8'];
    }
    
    if ($vid == 'migu') {
        $obj = file_get_contents("http://webapi.miguvideo.com/gateway/playurl/v3/play/playurl?contId=$id&rateType=4");
        preg_match('/"url":"(.*?)"/i', $obj, $linkobj);
        $playurl = $linkobj[1];
    }
    
    if ($vid == 'utvhk') {
        $obj = file_get_contents("http://miguapi.utvhk.com:18083/clt/publish/resource/UTV_NEW/playData.jsp?contentId=$id&nodeId=$id&rate=5&playerType=4&objType=LIVE&nt=4");
        preg_match('/"url": "(.*?)"/i', $obj, $linkobj);
        $playurl = $linkobj[1];
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
        $url = "http://news.tvb.com/live/$id?is_hd";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; Android 8.0.0; Pixel 2 XL Build/OPD1.170816.004) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Mobile Safari/537.36');
        $curlobj = curl_exec($curl);
        preg_match('/vdo_url = "(.*?)"/i', $curlobj, $linkobj);
        $playurl = $linkobj[1];
    }
    
    if ($vid == 'nnzb') {
        if (in_array($remote,$banips) != false) {
            exit;
        }
        if (empty($line)) {
            $line = 0;
        }
        $obj = file_get_contents("http://www.nnzhibo.com/e/DownSys/play/?classid=$tid&id=$id&pathid=$line");
        preg_match('/video-url="(.*?)"/i', $obj, $linkobj);
        if (empty($linkobj)){preg_match('/url: "(.*?)"/i', $obj, $linkobj);}
        $playurl = $linkobj[1];
    }
    
    if ($vid == 'null') {
        if (empty($line)) {
            $linkurl = "http://hd.zhibo123.top/$id/playlist.m3u8";
        } else if ($line == 1) {
            $linkurl = "http://198.16.106.58:8278/$id/playlist.m3u8";
        } 
        $playurl = "playurl=" . $linkurl;
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
        if (strstr($curlobj, "src1589") != false) {
            preg_match('/var src(.*)="(.*)"/i', $curlobj, $linkobj);
            $linkurl = $linkobj[2] . $part;
        } else {
            preg_match('/<option value="(.*?)"/i', $curlobj, $linkobj);
            $linkurl = $linkobj[1] . $part;
        }
        curl_setopt($curl, CURLOPT_URL, $linkurl);
        curl_setopt($curl, CURLOPT_TIMEOUT,2); 
        curl_setopt($curl, CURLOPT_NOBODY, 1);
        if (strstr($tid, "wintv") != false) {
            curl_setopt($curl, CURLOPT_MAXREDIRS, 2);
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
        curl_setopt($curl, CURLOPT_MAXREDIRS, 1);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Lavf/57.83.100');
        curl_exec($curl);
        $playurl = curl_getinfo($curl, CURLINFO_EFFECTIVE_URL);
        curl_close($curl);
    }
    
	if (strstr($playurl, "lctv") != false) {
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