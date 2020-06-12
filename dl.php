<?php
//
// +----------------------------------------------------------------------+
// | PHP version 7                                                        |
// +----------------------------------------------------------------------+
// | 此代码项目归 www.luo2888.cn 所有，盗版、倒卖死全家！   |
// +----------------------------------------------------------------------+
// | 下列代码内容为原创内容，归本人所有。                                 |
// | 任何人未经本人许可，不得进行抄袭、复制全部或者部分内容。             |
// +----------------------------------------------------------------------+
// | 作者: KwanKaHo <kwankaho@luo2888.cn>                                 |
// +----------------------------------------------------------------------+
//
error_reporting(E_ERROR);
include_once "api/common/cacher.class.php";

class Aes {
    protected $method;
    protected $secret_key;
    protected $iv;
    protected $options;
    public function __construct($key, $method = 'AES-128-CBC', $iv = '', $options = 0) {
        $this->secret_key = isset($key) ? $key : 'fmitv_key';
        $this->method = $method;
        $this->iv = $iv;
        $this->options = $options;
    }
    public function encrypt($data) {
        return openssl_encrypt($data, $this->method, $this->secret_key, $this->options, $this->iv);
    }
    public function decrypt($data) {
        return openssl_decrypt($data, $this->method, $this->secret_key, $this->options, $this->iv);
    }
}

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

// 缓存节目数据
function cache($key, $data = []) {
    Cache::$cache_path = "./cache/urls/";
    $val = Cache::gets($key);
    if (!$val) {
        Cache::put($key, $data);
        return $data;
    } else {
        return $val;
    } 
} 

function cache_time_out() {
    date_default_timezone_set("Asia/Shanghai");
    $timetoken = time() + 10800;
    return $timetoken;
} 

$remote = GetIP();
$ips = array(
    '127.0.0.1',
    '149.129.98.202', // Me
    '39.101.217.17', // id=1 2020-04
    '140.143.146.222', // id=2 2020-04
    '103.133.179.97', // id=2 2020-04
    '23.83.239.54', // id=2 2020-04
    '149.129.75.149', // id=3 2020-04
    '103.145.39.149', // id=4 2020-04
    '123.57.48.155', // id=5 2020-04
    '114.67.203.190', // id=6
    '198.44.188.193', // id=7 2020-04
    '111.230.178.212', // id=8 2020-06
    '113.87.129.140', // id=8 2020-06
    '39.99.174.216', // id=9 2020-06
    '47.56.247.8', // id=10 2020-06
    '122.114.123.45', // id=11 2020-06
    '191.101.44.238', // id=12 2020-06
    '14.116.198.35', // id=13 2020-06
    '47.114.56.181', // id=14 2020-04
    '111.229.143.72', // id=15 2020-06
);

$banips = array(
);

if (strstr($_SERVER['HTTP_USER_AGENT'], "FMITV/1.0") != false) 
{
    header('HTTP/1.1 403 Forbidden');
    exit;
}

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
    
    if ($vid == 'tvming') {
        $playurl = "https://live-wxxcx.mtq.tvmmedia.cn/weixin/live_$id.m3u8";
    }
    
    if ($vid == 'ysp') {
        $playurl = "http://120.241.133.167/outlivecloud-cdn.ysp.cctv.cn/001/$id.m3u8";
    }
    
    if ($vid == 'cqyx') {
        $playurl = "http://ott.cqccn.com/PLTV/88888888/224/$id/index.m3u8";
    }
    
    if ($vid == 'bjyd') {
        $playurl = "http://otttv.bj.chinamobile.com/PLTV/88888888/224/$id/1.m3u8";
    }
    
    if ($vid == 'fjyd') {
        $playurl = "http://ott.fj.chinamobile.com/PLTV/88888888/224/$id/1.m3u8";
    }

    if ($vid == 'pyyx') {
        $access_token = 'R5EE1E555U30959108K776530E4IECB4000EPBM30063B9V1020DZ6B731W16176E9CCB1715EF';
        $playurl = "http://httpdvb.slave.pyitv.com:13164/playurl?playtype=live&protocol=http&accesstoken=" . $access_token . "&programid=4200851" . $id . "&playtoken=ABCDEFGHI";
    }

    if ($vid == 'bilibili') {
        $obj = file_get_contents("http://hk.luo2888.cn:8118/bilibili.php?id=$id");
        $playurl = 'https://cn-hbxy-cmcc-live-01.live-play.acgvideo.com/live-bvc/live_' . $obj . '.m3u8';
    }

    if ($vid == 'cibn') {
        $url = "http://api.epg2.cibn.cc/v1/loopChannelList?epgId=1000";
        $obj = file_get_contents($url);
        $channeldata = json_decode($obj, true);
        $playurl = $channeldata['data']['channelList'][$id]['m3u8'];
    }

    if ($vid == '6ska') {
        $json =  file_get_contents("https://6ska.cn/api-2/vipjx/index.php?url=$id");
        preg_match('/"playurl":"(.*?)"/i', $json, $link);
        $playurl = $link[1];
    }

    if ($vid == 'douyu') {
        $json =  file_get_contents("https://web.sinsyth.com/lxapi/douyujx.x?roomid=$id");
        preg_match('/\/live\\\\\/(.*?)\?/i', $json, $link);
        $playurl = 'http://tx2play1.douyucdn.cn/live/' . $link[1];
        $playurl = preg_replace('#_2000p#', '', $playurl);
    }
    
    if ($vid == 'migu') {
        $playurl = file_get_contents("http://www.luo2888.cn/migu.php?id=$id");
        $playurl = preg_replace('#hlsmgsplive.miguvideo.com:8080#', 'mgsp.live.miguvideo.com:8088', $playurl);
    }

    if ($vid=='zjkp') {
        $ips = array(
            "1"=>"116.199.5.51:8114",
            "2"=>"116.199.5.52:8114",
            "3"=>"116.199.5.58:8114",
        );
        $playurl = 'http://' . $ips[$tid] . '/00000000/index.m3u8?Fsv_ctype=LIVES&Fsv_rate_id=1&Fsv_otype=1&FvSeid=5abd1660af1babb4&Pcontent_id=7f88be5fb6fd426494f6aa240f1dc7a9&Provider_id=00000000&Fsv_chan_hls_se_idx=' . $id;
    }
    
    if ($vid == 'fmitv') {
        $obj = file_get_contents("channels/fmitv_cust.txt");
        preg_match('/' . $id . '#(.*?),(.*?)\r/i', $obj, $linkobj);
        $playurl = $linkobj[2];
        if (empty($playurl)) {
            $playurl = file_get_contents("http://hk.luo2888.cn:8118/channels/$id");
        }
    }
   
    if ($vid == 'ublive') {
        $obj = file_get_contents("channels/ublive.txt");
        preg_match('/http:\/\/(.*?)\/live\/' . $id . '\/(.*?)\/index\.m3u8/i', $obj, $linkobj);
        $playurl = $linkobj[0];
    }
    
    if ($vid == 'tstv') {
        $obj = file_get_contents("channels/tstv.txt");
        preg_match('/http:\/\/(.*?)\/lbtv\/live\/(.*?)\/' . $id . '\/index\.m3u8/i', $obj, $linkobj);
        $playurl = $linkobj[0];
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
            "fhzx"=>"/live/8222pin72",
            "fhzh"=>"/live/8222pcc72",
            "fhhk"=>"/live/8222phk72",
        );
        $hexString = dechex(time()+1800);
        $substring = $ids[$id];
        $str2 = "obb9Lxyv5C".$substring.$hexString;
        $playurl = 'http://qlive.fengshows.cn'.$ids[$id].'.flv?txSecret='.md5($str2).'&txTime='.$hexString;
    }

    if ($vid == 'tvb') {
        $urlobj = file_get_contents("http://news.tvb.com/ajax_call/getVideo.php?token=http%3A%2F%2Ftoken.tvb.com%2Fstream%2Flive%2Fhls%2Fmobilehd_" . $id . ".smil%3Fapp%3Dnews%26feed");
        $obj = json_decode($urlobj, true);
        $playurl = $obj['url'];
    }
    
    if ($vid == 'nnzb') {
        if (empty($line)) {
            $line = 0;
        }
        $url = "http://www.nnzhibo.com/e/DownSys/play/?classid=$tid&id=$id&pathid=$line";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; Android 8.0.0; Pixel 2 XL Build/OPD1.170816.004) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Mobile Safari/537.36');
        $curlobj = curl_exec($curl);
        preg_match('/video-url="(.*?)"/i', $curlobj, $linkobj);
        if (empty($linkobj)){
            preg_match('/url: "(.*?)"/i', $curlobj, $linkobj);
        }
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
        $token_access_url = "http://access.pygdzhcs.com/account/login?accounttype=2&deviceno=A92E1686F0DEECFDDC346BBF7CB8C1BE&isforce=1&pwd=f91879321d16703dc86a12a68ad9d6cd&devicetype=3&account=luo2888";
        $res = file_get_contents($token_access_url);
        $result = json_decode($res, true);
        $access_token = $result['access_token'];
        $playurl = "http://httpdvb.slave.pygdzhcs.com:13164/playurl?playtype=live&protocol=http&accesstoken=" . $access_token . "&programid=4200000" . $id . "&playtoken=ABCDEFGHI";
    }

    if ($vid == 'ztgd') {
        $url = "http://access.ztgdwl.tv:12690/account/login?accounttype=2&deviceno=A92E1686F0DEECFDDC346BBF7CB8C1BE4&isforce=1&pwd=96e79218965eb72c92a549dd5a330112&devicetype=3&account=2ZBVALFP0XP5V99F";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 2.0.50727;)');
        $curlobj = curl_exec($curl);
        $result = json_decode($curlobj, true);
        $access_token = $result['access_token'];
        $playurl = "http://httpdvb.slave.ztgdwl.tv:13164/playurl?playtype=live&protocol=http&accesstoken=" . $access_token . "&programid=42000000" . $id . "&playtoken=ABCDEFGHI";
        curl_close($curl);
    }

    if ($vid == 'nxgd') {
        $url = "http://access.nx96200.cn:12690/account/login?accounttype=2&deviceno=A92E1686F0DEECFDDC346BBF7CB8C1BE4&isforce=1&pwd=f91879321d16703dc86a12a68ad9d6cd&devicetype=3&account=13066258954";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 2.0.50727;)');
        $curlobj = curl_exec($curl);
        $result = json_decode($curlobj, true);
        $access_token = $result['access_token'];
        $playurl = "http://httpdvb.slave.nx96200.cn:13164/playurl?playtype=live&protocol=http&accesstoken=" . $access_token . "&programid=4200900" . $id . "&playtoken=ABCDEFGHI";
        curl_close($curl);
    }
   
    if ($vid == 'hatv') {
        $cachefile = "./cache/hatv.id";
        $url = "http://stb.topmso.com.tw:8080/csr_mobile_client_web/ottLiveStreamGroupAction.do?method=getLiveStreamGroupForPhone_byChannel&ottCustNo=";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 2.0.50727;)');
        $filemtime = filemtime($cachefile);
        if (time() - $filemtime >= 14400 || !file_exists($cachefile))
        {
            unlink($cachefile);
            $curlobj = curl_exec($curl);
            preg_match('/liveedge2\/(.*?)_999_/i', $curlobj, $linkobj);
            $playid = $linkobj[1];
            file_put_contents($cachefile, $playid) ;
        } else {
            $playid = file_get_contents($cachefile);
        }
        $playurl = 'http://58.99.33.2:1935/liveedge2/' . $playid . '_' . $id . '_1/chunklist.m3u8?checkCode=37050688asdfsdfsadf&aa=9000234&as=2015&dr=&mmmm=';
        curl_close($curl);
    }
    
    if ($vid == 'bjy') {
        $tt = time()*1000+1234;
        $params = 'app_version=1.0.0&assetID='.$id.'&clientid=1&device_id=1B%3ADD%3A71%3AAC%3A08%3A60&ip=192.168.0.1&modules=programplay%3A1&playType=2&resourceCode='.$id.'&siteid=10001&system_name=android&type=android';
        $sign = md5(md5($params).'7ad794e167910229dc2dcec45749b9da'.$tt);   
        $bstrURL = 'http://m.api.bjy-app.beijingcloud.com.cn/v2/audiovisual?app_version=1.0.0&sign='.$sign.'&device_id=1B:DD:71:AC:08:60&time='.$tt.'&assetID='.$id.'&system_name=android&ip=192.168.0.1&siteid=10001&clientid=1&resourceCode='.$id.'&modules=programplay:1&type=android&playType=2';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $bstrURL);	 	 
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); 
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE); 
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.88 Safari/537.36');
        $data = curl_exec($curl);
        $obj = json_decode($data);
        $playurl = $obj->data->programplay->bitPlayUrlList[0]->url;
        curl_close($curl);
    }
    
    if ($vid == 'egame') {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://share.egame.qq.com/cgi-bin/pgg_async_fcgi");
        $postdata = 'param={"0":{"module":"pgg_live_read_svr","method":"get_live_and_profile_info","param":{"anchor_id":'.$id.',"layout_Id":"hot","index":1,"other_uid":0}}}';
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded'
        ));
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.88 Safari/537.36");
        $curlobj = curl_exec($curl);
        $obj = json_decode($curlobj);
        $bstrURL = $obj->data->{0}->retBody->data->video_info->stream_infos[0]->play_url;
        preg_match('/.*?&uid=/',$bstrURL,$result);
        $playurl = $result[0];
        curl_close($curl);
    }

    if ($vid == 'youku') {
        $bstrURL = 'https://acs.youku.com/h5/mtop.youku.live.com.livefullinfo/1.0/?appKey=24679788';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $bstrURL);	
        curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); 
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE); 
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.88 Safari/537.36");
        $data = curl_exec($curl);
        $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $header = substr($data, 0, $headerSize);
        preg_match_all('/_m_h5_tk=(.*?)_/',$header,$result);
        $h5_token = ($result[1][0]);
        preg_match('/_m_h5_tk=.*?;/',$header,$tk1);
        preg_match('/_m_h5_tk_enc=.*?;/',$header,$tk2);
        $cookies = $tk1[0].$tk2[0];
        $data = array("liveId"=>intval($id),"app"=>"Pc");
        $data = json_encode($data);
        $tt = time() ;
        $sign =md5( $h5_token.'&'.$tt.'&24679788&'.$data);
        $bstrURL = $bstrURL.'&t='.$tt.'&sign='.$sign.'&data='.urlencode($data);
        curl_setopt($curl, CURLOPT_URL, $bstrURL);	
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_HTTPHEADER,array('Cookie:'.$cookies));
        $data = curl_exec($curl);
        $obj = json_decode($data);
        $sStreamName = $obj->data->data->stream[0]->streamName;
        $playurl = 'http://lvo-live.youku.com/vod2live/'.$sStreamName.'_mp4hd2v3.m3u8?&expire=21600&psid=1&ups_ts='.time().'&vkey=';
        curl_close($curl);
    }

    if ($vid == 'yylive') {
        $bstrURL = 'http://interface.yy.com/hls/new/get/'.$id.'/'.$id.'/1200?source=wapyy&callback=jsonp3';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $bstrURL);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.88 Safari/537.36" );
        curl_setopt($curl,CURLOPT_REFERER,'http://wap.yy.com/mobileweb/'.$id);
        $data = curl_exec($curl);
        preg_match('/hls":"(.*?)"/',$data,$result);
        $playurl = $result[1];
        curl_close($curl);
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
        preg_match('/liveLineUrl = "\/\/(.*?)"/i', $curlobj, $linkobj);
        if (!empty($linkobj[1])){
            $playurl = 'http://' . $linkobj[1];
        }
        curl_close($curl);
    }

    if ($vid == 'grtn') {
        $url = "http://www.gdtv.cn/m2o/player/channel_xml.php?id=$id";
        $stime = time();
        $PlayerVersion = '4.12.180327_RC';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 2.0.50727;)');
        $curlobj = curl_exec($curl);
        $curlobj = json_decode(json_encode(simplexml_load_string($curlobj)) , true);
        $linkurl = str_replace('stream1', 'nclive', $curlobj['drm'] . '?playerVersion=' . $PlayerVersion . '&time=' . $stime . '&url=' . $curlobj['video']['@attributes']['baseUrl'] . $curlobj['video']['item'][0]['@attributes']['url'] . 'live.m3u8');
        curl_setopt($curl, CURLOPT_URL, $linkurl);
        $playurl = curl_exec($curl);
        curl_close($curl);
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
        curl_close($curl);
    }

    if ($vid=='cutv') {
        $bstrURL = 'https://sttv2-api.cutv.com/api/getlivelistST.php?v=2&type=hdtv';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $bstrURL);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; Android 8.0.0; Pixel 2 XL Build/OPD1.170816.004) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Mobile Safari/537.36');
        $data = curl_exec($curl);
        $obj = json_decode($data);
        $nId = intval(str_replace('st', '', $id)) - 1; //st1 = 0
        $bstrLiveUrl = $obj->data->tv[$nId]->liveurl;
        $bstrLiveUrl = str_replace('\/\/', '\/500\/', $bstrLiveUrl);
        $bstrURL = 'https://sttv2-api.cutv.com/api/getIP.php';
        curl_setopt($curl, CURLOPT_URL, $bstrURL);
        $data = curl_exec($curl);
        $obj = json_decode($data);
        $aes_data = $obj->data[0];
        $aes = new Aes('reter4446fdfgdfg', 'AES-128-CBC', '0102030405060708');
        $de_aes = $aes->decrypt($aes_data);
        $timestamp = dechex(time() + 7200);
        $subPath = substr($bstrLiveUrl, stripos($bstrLiveUrl, 'hls.cutv.com') + 12);
        $sign = md5($de_aes . 'j5dt4yng0nux7s8bew1r1gip' . $subPath . $timestamp);
        $playurl = $bstrLiveUrl . '?sign=' . $sign . '&t=' . $timestamp;
        curl_close($curl);
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
        curl_close($curl);
    }

    if ($vid == '91kds') {
        if (empty($line)) {
            $line = 0;
        }
        $url = "http://m.91kds.org/jiemu_$id.html";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; Android 8.0.0; Pixel 2 XL Build/OPD1.170816.004) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Mobile Safari/537.36');
        $curlobj = curl_exec($curl);
        preg_match_all('/<option value="(http)(.*?)"/i', $curlobj, $linkobj);
        $playurl = $linkobj[1][$line] . $linkobj[2][$line];
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
    
    if (empty($playurl) || strstr($playurl, "starray") != false) {
        $playurl = null;
    }
	
    $data = json_encode(
        array(
            'playurl' => $playurl
        )
    );

    $timetoken = cache("time_out_chk", cache_time_out());
    if (time() >= $timetoken) {
        Cache::$cache_path = "./cache/urls/"; 
        Cache::dels();  // 删除当前目录缓存文件
        cache("time_out_chk", cache_time_out());  // 重新写入缓存
    } 
    $cached = cache($vid . '#' . $tid . '#' . $id, $data);
    echo $cached;
    exit;
    
} else if (isset($_POST['fmitv_list'])) {
    
    $json = $_POST['fmitv_list'];
    $obj = json_decode($json);
    $vid = $obj->video;
    $tid = $obj->tid;
    $id = $obj->id;
    
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

    $data = json_encode(
        array(
            'tvlist' => $result
        )
    );
    
    echo $data;
    exit;

} else {

    header('HTTP/1.1 403 Forbidden');
    exit;

}

?>