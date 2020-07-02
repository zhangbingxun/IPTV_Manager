<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);

require_once "api/common/cacher.class.php";
require_once "config.php";
$db = Config::GetIntance();
$remote = new GetIP();
$channelNumber = 1;
$myurl = mUrl();
$nowtime = time();

$appsign = $db->mGet("luo2888_config","value","where name='app_sign'");
$appname = $db->mGet("luo2888_config","value","where name='app_appname'");
$packagename = $db->mGet("luo2888_config","value","where name='app_packagename'");
$b64str = 'uksevY3s!@lTmTJ1Pm&X$CT!5mCwCTZ&v^eKlozFP%Ysjni!UyBk5udDQofWs8Y6JkA80xxGp#oJqjWvQo9glQx^7eWoe#C4m71QQn!A1ZxhdjTqwoUp9QNZv#3oV@ZC';
$appkey = md5($appsign . $appname . $packagename . $b64str);
$key = md5($appkey . $appname . $packagename);

class Aes {
    protected $iv;
    protected $options;
    protected $method;
    protected $secret_key;
 
    public function __construct($key, $iv = '', $method = 'AES-128-CBC', $options = 0)
    {
        $this->iv = $iv;
        $this->options = $options;
        $this->method = $method;
        $this->secret_key = isset($key) ? $key : 'tvkey_luo2888';
    }
 
    public function encrypt($data)
    {
        return openssl_encrypt($data, $this->method, $this->secret_key, $this->options, $this->iv);
    }
 
    public function decrypt($data)
    {
        return openssl_decrypt($data, $this->method, $this->secret_key, $this->options, $this->iv);
    }
}

// 获取API地址
function mUrl() {

    $Url = 'http://';
    if($_SERVER['HTTPS'] == 'on') {
        $Url = 'https://';
    }
    $Url .= $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
    return $Url;

}

// 生成随机账号
function genName() {
    global $db;    
    $name = rand(1000, 999999);
    $users = $db->mCheckOne("luo2888_users", "*", "where name=$name");
    $serial = $db->mCheckOne("luo2888_serialnum", "*", "where name=$name");
    if ($users || $serial) {
        genName();
    } else {
        unset($users,$serial);
        return $name;
    }
}

// 输出频道数据
function echoJSON($username, $category, $alisname, $psw) {

    global $db, $remote, $channelNumber, $key, $myurl;
    $userip = $remote -> getuserip();
    $nowtime = time();

    if ($alisname == '我的收藏') {
        $channelname = $alisname;
    } else {
        $channelname = $db->mGet("luo2888_channels", "name", "where category='$category'");
    } 

    if (!empty($channelname)) {
        $result = $db->mQuery("SELECT id,name,url FROM luo2888_channels where category='$category' order by id");
        $nameArray = array();
        while ($row = mysqli_fetch_array($result)) {
            if (!in_array($row['name'], $nameArray)) {
                $nameArray[] = $row['name'];
            } 
            if (strstr($row['url'], "http") != false) {
                $sourceArray[$row['name']][] = mUrl() . '?tvplay&user=' . $username . '&channel=' . $row['id'] . '&time=' . $nowtime . '&token=' . md5($row['id'] . $nowtime . $key);
            } else {
                $sourceArray[$row['name']][] = $row['url'];
            }
        } 
        $objCategory = (Object)null;
        $objChannel = (Object)null;
        $channelArray = array();
        for($i = 0;
            $i < count($nameArray);
            $i++) {
            $objChannel = (Object)null;
            $objChannel->num = $channelNumber;
            $objChannel->name = $nameArray[$i];
            $objChannel->source = $sourceArray[$nameArray[$i]];
            $channelArray[] = $objChannel;
            $channelNumber++;
        } 
        $objCategory->name = $alisname;
        $objCategory->psw = $psw;
        $objCategory->data = $channelArray;
        unset($row,$nameArray, $sourceArray, $objChannel);
        mysqli_free_result($result);
        return $objCategory;
    } 

} 

// EPG错误信息
function failmsg($code, $msg) {
    date_default_timezone_set("Asia/Shanghai");
    header('content-type:application/json;charset=utf-8');
    $arr = [];
    $datas = [];
    $datas["name"] = $msg;
    $datas["starttime"] = date("H:i", time());
    $arr["code"] = $code;
    $arr["msg"] = $msg;
    $arr["name"] = "Access deniend";
    $arr["tvid"] = "1";
    $arr["date"] = date("Y-m-d", time());
    $arr["data"] = [$datas];
    $str = json_encode($arr, JSON_UNESCAPED_UNICODE);
    echo $str;
    exit;
} 

// 缓存数据
function cache($key, $f_name, $ff = []) {
    Cache::$cache_path = "./cache/tvapi/";
    $val = Cache::gets($key);
    if (!$val) {
        $data = call_user_func_array($f_name, $ff);
        Cache::put($key, $data);
        return $data;
    } else {
        return $val;
    } 
} 

// 缓存超时
function cache_time_out() {
    date_default_timezone_set("Asia/Shanghai");
    $timetoken = time() + 1200;
    return $timetoken;
}

function mCurl($url,$method,$refurl,$post_data){
    $UserAgent = 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36';
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_USERAGENT, $UserAgent);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    if ($method == "POST") {
        curl_setopt($curl, CURLOPT_REFERER, $refurl); 
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
    }
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

function lanzouUrl($url) {
    $ruleMatchDetailInList = "~ifr2\"\sname=\"[\s\S]*?\"\ssrc=\"\/(.*?)\"~";
    preg_match_all($ruleMatchDetailInList, mCurl($url,null,null,null),$link);
    $index = 0;
    for($i=0;$i<count($link[1]);$i++){
        if($link[1][$i]!="fn?v2"){
            $index = $i;
            break;
        }
    }

    $refurl = "https://www.lanzous.com/".$link[1][$index];
    $ruleMatchDetailInList = "~var ajaxup = '([^\]]*)';//~";
    preg_match($ruleMatchDetailInList, mCurl($refurl,null,null,null),$segment);
    $post_data = array(
        "action" => "downprocess",
        "sign" => $segment[1],
        "ves" => 1,
        "p" => ""
    );

    $downjson = mCurl("https://www.lanzous.com/ajaxm.php","POST",$refurl,$post_data);
    $linkobj = json_decode($downjson);
    if ($linkobj->dom == "") {
        return false;
    } else {
        return $linkobj->dom . "/file/" . $linkobj->url;
    }
}

if (isset($_GET['bgpic'])) {

    header('Content-Type: text/json;charset=UTF-8');
    $dir = dirname(__FILE__);
    $dir = $dir . '/images';
    $files = glob("images/*.png");

    foreach ($files as $file) {
        $pngs[] = dirname($myurl) . '/' . $file;
    }

    $rkey = array_rand($pngs);
    echo $pngs[$rkey];
    exit;

}

else if (isset($_GET['getver'])) {

    $boxver = $db->mGet("luo2888_config", "value", "where name='appver_sdk14'");
    $boxurl = $db->mGet("luo2888_config", "value", "where name='appurl_sdk14'");
    $appver = $db->mGet("luo2888_config", "value", "where name='appver'");
    $appurl = $db->mGet("luo2888_config", "value", "where name='appurl'");
    $up_size = $db->mGet("luo2888_config", "value", "where name='up_size'");
    $up_sets = $db->mGet("luo2888_config", "value", "where name='up_sets'");
    $up_text = $db->mGet("luo2888_config", "value", "where name='up_text'");

    $timetoken = cache("time_out_chk", "cache_time_out");
    if (time() >= $timetoken) {
        Cache::$cache_path = "./cache/tvapi/"; 
        Cache::dels();
        cache("time_out_chk", "cache_time_out");
    } 

    if (strstr($appurl,"lanzou://")) {
        $appurl = preg_replace('#lanzou\:#', 'https:', $appurl);
        $appurl = cache("appurl" . $appurl, "lanzouUrl", [$appurl]);
    }

    if (strstr($boxurl,"lanzou://")) {
        $boxurl = preg_replace('#lanzou\:#', 'https:', $boxurl);
        $boxurl = cache("boxurl" . $boxurl, "lanzouUrl", [$boxurl]);
    }

    $data = json_encode(
        array(
            'boxver' => $boxver,
            'boxurl' => $boxurl,
            'appver' => $appver,
            'appurl' => $appurl,
            'appsets' => $up_sets,
            'appsize' => $up_size,
            'apptext' => $up_text,
        ),JSON_UNESCAPED_UNICODE
    );

    echo $data;
    exit;

}

else if (isset($_GET['getloc'])) {

    header("Content-type: text/json; charset=utf-8");
    header("Cache-Control:no-cache,must-revalidate");
    header("Pragma: no-cache");
    $userip = $_GET['ip'];

    if (empty($userip)) {
        $userip = $remote -> getuserip();
    }

    $iploc = $remote -> getloc($userip);
    echo $iploc;

    exit;

}

else if (isset($_GET['vpnuser'])) {

    $id = $_GET['id'];
    $db->mSet("luo2888_users", "vpn=vpn+1", "where name=$id");
    exit;

}

else if (isset($_GET['gettime'])) {

    echo $nowtime;
    exit;

}

else if (isset($_GET['getmeal'])) {

    $androidid = $_GET['id'];
    $mealid = $db->mGet("luo2888_users", "meal", "where deviceid='$androidid'");
    $mealname = $db->mGet("luo2888_meals", "name", "where id='$mealid'");
    echo $mealname;
    exit;

}

else if (isset($_GET['tvinfo'])) {

    $channel_name = $_GET['channel'];
    $apidir = dirname($myurl) . '/api/common';
    $epgdata =  file_get_contents("$apidir/tvguide.php?channel=" . $channel_name);
    if (empty(json_decode($epgdata, true))) {
       failmsg(200, "EPG接口错误");
    }
    echo $epgdata;
    exit;

}

else if (isset($_GET['tvplay'])) {

    $channelid = $_GET['channel'];
    $username = $_GET['user'];
    $token = $_GET['token'];
    $time = $_GET['time'];
    $userip = $remote -> getuserip();
    $uptime = $db->mGet("luo2888_config", "value", "where name='updateinterval'");
    $vpntimes = $db->mGet("luo2888_config", "value", "where name='vpntimes'");
    $failureurl = $db->mGet("luo2888_config", "value", "where name='failureurl'");
    $deniedurl = $db->mGet("luo2888_config", "value", "where name='deniedurl'");
    $uservpntimes = $db->mGet("luo2888_users", "vpn", "where name='$username'");
    $playurl = $db->mGet("luo2888_channels", "url", "where id='$channelid'");

    if (abs($nowtime - $time) > $uptime * 60 + 30) {
        header('location:' . $deniedurl);
        exit('您被系统判定为盗链！');
    }
    else if ($token != md5($channelid . $time . $key))
    {
        header('location:' . $deniedurl);
        exit('您被系统判定为盗链！');
    }
    else if ($uservpntimes >= $vpntimes)
    {
        header('location:' . $deniedurl);
        exit('您被系统判定为抓包！');
    }

    if (!empty($playurl)) {
        header('location:' . $playurl);
    } else {
        header('location:' . $failureurl);

    }


    exit;

}

else if (isset($_POST['active'])) {

    $actstr = $_POST['active'];
    $jsonstr = base64_decode($actstr);
    $obj = json_decode($jsonstr);
    $androidid = $obj->androidid;
    $mac = $obj->mac;
    $model = $obj->model;
    $serial = $obj->serial;
    $nowtime = time();

    if(!is_numeric($serial))exit('授权号必须为数字！！');

    if ($db->mCheckOne("luo2888_users", "*", "where deviceid='$androidid'") == false) {
        exit('你的设备无法识别,请与管理员联系！');
    }
    unset($row);

    if ($row = $db->mCheckRow("luo2888_users", "status,name,exp,marks,author,authortime", "where name=$serial and status>0 and mac='' and deviceid=''")) {
        $status = $row['status'];
        $exp = $row['exp'];
        $marks = $row['marks'];
        $author = $row['author'];
        $authortime = $row['authortime'];

        //通过SN重新绑定
        $db->mDel("luo2888_users","where mac='$mac' and deviceid='$androidid' and model='$model' and status=-1");
        $db->mSet("luo2888_users","mac='$mac',deviceid='$androidid',model='$model'","where name=$serial and status>0 and mac='' and deviceid='' and model=''");
        exit("系统已为你重新绑定成功！");	

    } else {

        if($row = $db->mCheckRow("luo2888_serialnum", "name,meal,days,author,marks", "where name=$serial")){
            $nowtime = time();
            $meal = $row['meal'];
            $marks = $row['marks'];
            $author = $row['author'];
            $exp = strtotime(date("Y-m-d"),$nowtime) + $row['days'] * 86400;

            if ($row['days'] == 999) {
                $status = 999;
            } else {
                $status = 1;
            }
           
            if (empty($marks)) {
                $marks = '授权号绑定';
            }

            $db->mSet("luo2888_users","name=$serial,meal=$meal,status=$status,exp=$exp,author='$author',authortime=$nowtime,marks='$marks'","where deviceid='$androidid'");
            $db->mDel("luo2888_serialnum","where name=$serial");
            exit("授权号绑定成功！！");
        } else {
            exit("授权号错误，请联系提供商！！");
			   }
    }
    unset($row);
}

else if (isset($_POST['login'])) {

    $loginstr = $_POST['login'];
    $jsonstr = base64_decode($loginstr);
    $obj = json_decode($jsonstr);
    $region = $obj->region;
    $androidid = $obj->androidid;
    $iv = $obj->iv;
    $mac = $obj->mac;
    $model = $obj->model;
    $nettype = $obj->nettype;
    $appname = $obj->appname;
    $userip = $remote -> getuserip();
    $ipcount = $db->mGet("luo2888_users", "count(*)", "where ip='$userip'");
    $ipadmit = $db->mGet("luo2888_config", "value", "where name='max_sameip_user'");
    
    if (strstr($mac,"44:55:66")  || $androidid == '871544fa3caeb847'){
        exit;
    }

    if ($userip == '' || $userip == '127.0.0.1') {
      $userip = '127.0.0.1';
      $region = 'localhost'; 
    }

    if (empty($region)) {
        $json = $remote -> getloc($userip);
        $obj = json_decode($json);
        $region = $obj->data->region . $obj->data->city . $obj->data->isp;
    } 

    // 没有mac禁止登陆
    if(strstr($mac,":") == false) {
        $nomacuser = 1;
        goto banuser;
    }

    // mac是否匹配
    if ($row = $db->mCheckRow("luo2888_users", "name,status,exp,deviceid,mac,model,meal", "where mac='$mac'")) {

        // 匹配成功
        $days = ceil(($row['exp'] - time()) / 86400);
        $status = intval($row['status']);
        $name = $row['name'];
        $deviceid = $row['deviceid'];
        $mealid = $row['meal'];
        $exp = $row["exp"]; //收视期限，时间戳
        $status2 = $status;

        // 禁止抓包用户登录
        $vpntimes = $db->mGet("luo2888_config", "value", "where name='vpntimes'");
        $uservpntimes = $db->mGet("luo2888_users", "vpn", "where name='$name'");
        if ($uservpntimes >= $vpntimes) {
            $vpnuser = 1;
            goto banuser;
        }

        if ($days > 0 && $status == -1) {
            $status = 1;
        } else if ($status2 == -999) {
            $status = 1;
        } 

        if ($deviceid != $androidid){
        	$db->mSet("luo2888_users", "deviceid='$androidid',idchange=idchange+1", "where mac='$mac'"); 
        }

        // 更新位置，登陆时间
        $db->mSet("luo2888_users", "region='$region',ip='$userip',lasttime=$nowtime", "where mac='$mac'"); 

    } else {

        // 用户验证失败，识别用户信息存入后台

        // 禁止重复用户注册
        if ($ipcount > $ipadmit) {
            $sameuser = 1;
            goto banuser;
        }

        /* if (strpos($region, '电信') !== false || strpos($region, '联通') !== false || strpos($region, '移动') !== false) {
            $banuser = 1;
            goto banuser;
        } */

        $name = genName();
        $days = $db->mGet("luo2888_config", "value", "where name='trialdays'");
        if (empty($days)) {
            $days = 0;
        } 
        if ($days > 0) {

            $status = -1;
            $marks = '试用';
        } else if ($days == "-999") {
            $status = -999;
            $marks = '免费';
            $days = 3;
        } else {
            $status = -1;
            $marks = '未授权';
        } 
        $mealid = 1000;
        $status2 = $status;
        $exp = strtotime(date("Y-m-d"), time()) + 86400 * $days;
        $db->mInt("luo2888_users", "name,mac,deviceid,model,exp,ip,status,region,lasttime,marks", "$name,'$mac','$androidid','$model',$exp,'$userip',$status,'$region',$nowtime,'$marks'");
        if ($days > 0 && $status == -1) {
            $status = 1;
        } else if ($status2 == -999) {
            $status = 1;
        } 
    } 
    unset($row);

    $app_appname = $db->mGet("luo2888_config", "value", "where name='app_appname'");
    $app_sign = $db->mGet("luo2888_config", "value", "where name='app_sign'");
    $dataver = $db->mGet("luo2888_config", "value", "where name='dataver'");
    $appurl = $db->mGet("luo2888_config", "value", "where name='appurl'");
    $appver = $db->mGet("luo2888_config", "value", "where name='appver'");
    $setver = $db->mGet("luo2888_config", "value", "where name='setver'");
    $adinfo = $db->mGet("luo2888_config", "value", "where name='adinfo'");
    $adtext = $db->mGet("luo2888_config", "value", "where name='adtext'");
    $showwea = $db->mGet("luo2888_config", "value", "where name='showwea'");
    $showtime = $db->mGet("luo2888_config", "value", "where name='showtime'");
    $showinterval = $db->mGet("luo2888_config", "value", "where name='showinterval'");
    $decoder = $db->mGet("luo2888_config", "value", "where name='decoder'");
    $buffTimeOut = $db->mGet("luo2888_config", "value", "where name='buffTimeOut'");
    $needauthor = $db->mGet("luo2888_config", "value", "where name='needauthor'");
    $autoupdate = $db->mGet("luo2888_config", "value", "where name='autoupdate'");
    $randkey = $db->mGet("luo2888_config", "value", "where name='randkey'");
    $updateinterval = $db->mGet("luo2888_config", "value", "where name='updateinterval'");
    $keyproxy = $db->mGet("luo2888_config", "value", "where name='keyproxy'");
    $tiploading = $db->mGet("luo2888_config", "value", "where name='tiploading'");
    $tipusernoreg = '您的账号是' . $name . '，' . $db->mGet("luo2888_config", "value", "where name='tipusernoreg'");
    $tipuserexpired = '当前账号' . $name . '，' . $db->mGet("luo2888_config", "value", "where name='tipuserexpired'");
    $tipuserforbidden = '当前账号' . $name . '，' . $db->mGet("luo2888_config", "value", "where name='tipuserforbidden'");
    $datatoken = "token=" . md5($name . $app_sign . $randkey);

    if ($needauthor == 0 || ($status2 == -999)) {
        $status = 999;
    } 

    $mealname = $db->mGet("luo2888_meals", "name", "where id='$mealid'");
    $week = array('日', '一', '二', '三', '四', '五', '六');
    $adtext = '尊敬的用户，欢迎使用' . $app_appname . '。' . date('今天n月d号') . "，" . '星期' . $week[date('w')] . '，当前套餐：' . $mealname . '。' . $adtext;

    if ($showwea == 1) {
        $weaapi_id = $db->mGet("luo2888_config", "value", "where name='weaapi_id'");
        $weaapi_key = $db->mGet("luo2888_config", "value", "where name='weaapi_key'");
        $url = "https://www.tianqiapi.com/api?version=v6&appid=$weaapi_id&appsecret=$weaapi_key&ip=$userip";
        $weajson = file_get_contents($url);
        $obj = json_decode($weajson);
        if (!empty($obj->city)) {
            $weather =  "今天" . $obj->wea . '，' . $obj->tem2 . '℃' . '～' . $obj->tem1 . '℃' . '，' . $obj->win . $obj->win_speed . '，' . '湿度' . $obj->humidity . '，' . '空气' . $obj->air_level . '。';
            $adinfo = $weather .  "\n" . $adinfo;
        } 
    } 

    if ($status < 1) {
        $datatoken = '';
        $keyproxy = '';
        $randkey = '';
    } 

    $result = $db->mQuery("SELECT name from luo2888_category where enable=1 and type='province' order by id");
    while ($row = mysqli_fetch_array($result)) {
        $arrprov[] = $row[0];
    } 
    unset($row);
    mysqli_free_result($result);

    $result = $db->mQuery("SELECT name,url from luo2888_vods where enable=1 order by id");
    while ($row = mysqli_fetch_array($result)) {
        $vodmodels[] = array(
            "name" => $row['name'],
            "api" => $row['url'],
        );
    } 
    $vodapi = (Object)null;
    $vodapi->name = "肥米TV视频采集接口";
    $vodapi->model = $vodmodels;
    $voddatas = json_encode($vodapi, JSON_UNESCAPED_UNICODE);
    unset($row);
    mysqli_free_result($result);

    $arrcanseek[] = '';

    banuser:
    if ($vpnuser == 1) {
        $status = 0;
        $tipuserforbidden= '您使用了VPN等程序' . $uservpntimes . '次，系统判定抓包已禁止登录！';
    }
    
    if ($nomacuser == 1) {
        $status = 0;
        $tipuserforbidden= '检测不到Mac地址，请打开WiFi重新登录！';
    }


    if ($banuser == 1) {
        $status = 0;
        $tipuserforbidden= '对不起，该应用禁止国内IP访问！';
    }
    
    if ($sameuser == 1) {
        $status = 0;
        $tipuserforbidden= '对不起，同一个IP只允许注册' . $ipadmit . '台设备！';
    }

    $objres = array('id' => $name, 'status' => $status, 'mealname' => $mealname, 'datatoken' => $datatoken, 'appurl' => $appurl, 'dataver' => $dataver, 'appver' => $appver, 'setver' => $setver, 'adtext' => $adtext, 'showinterval' => $showinterval, 'exp' => $days, 'userip' => $userip, 'showtime' => $showtime , 'provlist' => $arrprov, 'canseeklist' => $arrcanseek, 'decoder' => $decoder, 'buffTimeOut' => $buffTimeOut, 'tipusernoreg' => $tipusernoreg, 'tiploading' => $tiploading, 'tipuserforbidden' => $tipuserforbidden, 'tipuserexpired' => $tipuserexpired, 'adinfo' => $adinfo, 'keyproxy' => $keyproxy, 'location' => $region, 'nettype' => $nettype, 'autoupdate' => $autoupdate, 'updateinterval' => $updateinterval, 'randkey' => $randkey, 'exps' => $exp, 'movieengine' => $voddatas);

    $objres = str_replace("\\/", "/", json_encode($objres, JSON_UNESCAPED_UNICODE)); 
    $key = substr($key, 5, 16);
    $aes = new Aes($key,$iv);
    $encrypted = $aes -> encrypt($objres);
    echo $encrypted;
    exit;

}

else if (isset($_POST['tvdata']) && isset($_GET['token'])) {

    $token = $_GET['token'];
    $datastr  = $_POST['tvdata'];
    $data = base64_decode($datastr);
    $obj = json_decode($data);
    $androidid = $obj->androidid;
    $mac = $obj->mac;
    $model = $obj->model;
    $region = $obj->region;
    $nettype = $obj->nettype;
    $randkey = $obj->rand;
    $iv = $obj->iv;
    $app_sign = $db->mGet("luo2888_config", "value", "where name='app_sign'");
    $username = $db->mGet("luo2888_users", "name", "where mac='$mac'");

    if ($token != md5($username . $app_sign . $randkey)) {
        header('HTTP/1.1 403 Forbidden');

        exit;
    }

    if (strpos($nettype, '电信') !== false) {
        $nettype = "chinanet";
    } else if (strpos($nettype, '联通') !== false) {
        $nettype = "unicom";
    } else if (strpos($nettype, '移动') !== false) {
        $nettype = "cmcc";
    } else {
        $nettype = "";
    } 

    // 查找当前用户对应的套餐

    $result = $db->mQuery("SELECT meal from luo2888_users where mac='$mac'");
    if (mysqli_num_rows($result)) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

        if (empty($row["meal"])) {
            $mid = 1000;
            mysqli_free_result($result);
        } else {
            $mid = $row["meal"];
            mysqli_free_result($result);
        } 
    }

    // 检测套餐是否存在，收视内容是否为空
    $result = $db->mQuery("select content from luo2888_meals where status=1 and id=$mid");
    if (mysqli_num_rows($result)) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        if (empty($row["content"])) {
            $m_text = false;
            mysqli_free_result($result);
        } else {
            $m_text = $row["content"];
            mysqli_free_result($result);
        } 
    } 

    // 增加我的收藏
    $contents[] = echoJSON($username, '', "我的收藏", ''); 

    // 默认套餐不输出运营商和各省的频道
    if ($mid != 1000) {

        // 添加运营商频道数据
        if (!empty($nettype)) {
            $result = $db->mQuery("SELECT name,id,psw FROM luo2888_category where enable=1 and type='$nettype' order by id");
            while ($row = mysqli_fetch_array($result)) {
                $pdname = $row['name'];
                $psw = $row['psw'];
                $contents[] = echoJSON($username, $pdname, $pdname, $psw);
            } 
            unset($row);
            mysqli_free_result($result);
        } 


        // 添加省内频道数据
        if (isset($region) && $region != '') {
            $result = $db->mQuery("SELECT name,id,psw FROM luo2888_category where enable=1 and type='province' and name like '$region%' order by id");
            while ($row = mysqli_fetch_array($result)) {
                $pdname = $row['name'];
                $psw = $row['psw'];
                $contents[] = echoJSON($username, $pdname, '省内', $psw);
            } 
            unset($row);
            mysqli_free_result($result);
        } 

    } 

    // 授权套餐频道数据
    if ($m_text) {
        $m_str = explode("_", $m_text);
        foreach ($m_str as $id => $meal_content) {
            $result = $db->mQuery("SELECT name,id,psw FROM luo2888_category where enable=1 and name='$meal_content' ORDER BY id asc");
            if (!mysqli_num_rows($result)) {
                mysqli_free_result($result);
            } else {
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                $pdname = $row['name'];
                $psw = $row['psw'];
                $contents[] = echoJSON($username, $pdname, $pdname, $psw);
                unset($row);
                mysqli_free_result($result);
            } 
        } 
        unset($m_str, $m_text);
    } 

    $str = json_encode($contents, JSON_UNESCAPED_UNICODE);
    $str = preg_replace('#null,#', '', $str);
    $str = stripslashes($str);
    $str = base64_encode(gzcompress($str));
    $key = md5($key . $randkey);
    $key = substr($key, 7, 16);
    $aes = new Aes($key,$iv);
    $encrypted = $aes->encrypt($str);
    $encrypted = str_replace("f", "&", $encrypted);
    $encrypted = str_replace("b", "f", $encrypted);
    $encrypted = str_replace("&", "b", $encrypted);
    $encrypted = str_replace("t", "#", $encrypted);
    $encrypted = str_replace("y", "t", $encrypted);
    $encrypted = str_replace("#", "y", $encrypted);
    $coded = substr($encrypted, 44, 128);
    $coded = strrev($coded);
    $datastr = $coded . $encrypted;
    echo $datastr;
    exit;

} else {

    header('HTTP/1.1 403 Forbidden');
    exit;

} 

?>
