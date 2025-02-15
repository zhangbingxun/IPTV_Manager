<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);

require_once "api/common/cacher.class.php";
require_once "config.php";
$db = Config::GetIntance();
$remote = new GetIP();
$channelNumber = 1;
$myurl = mUrl() . $_SERVER['PHP_SELF'];
$nowtime = time();
$datetime = date("Y-m-d H:i:s");
$today = strtotime(date("Y-m-d") , time());

$appsign = $db->mGet("luo2888_config","value","where name='app_sign'");
$b64str = $db->mGet("luo2888_config","value","where name='app_b64key'");
$appname = $db->mGet("luo2888_config","value","where name='app_appname'");
$packagename = $db->mGet("luo2888_config","value","where name='app_packagename'");
$appkey = md5("#$appname#$appsign#$packagename#$b64str#");
$key = md5("#$appname#$appkey#$packagename#");

class Aes {
    protected $iv;
    protected $options;
    protected $method;
    protected $secret_key;
 
    public function __construct($key, $iv = '', $method = 'AES-128-CBC', $options = 0) {
        $this->iv = $iv;
        $this->options = $options;
        $this->method = $method;
        $this->secret_key = isset($key) ? $key : 'tvkey_luo2888';
    }
 
    public function encrypt($data) {
        return openssl_encrypt($data, $this->method, $this->secret_key, $this->options, $this->iv);
    }
 
    public function decrypt($data) {
        return openssl_decrypt($data, $this->method, $this->secret_key, $this->options, $this->iv);
    }
}

// 生成随机账号
function genName() {
    global $db;    
    $name = rand(100000, 99999999);
    $users = $db->mCheckOne("luo2888_users", "*", "where name=$name");
    $serial = $db->mCheckOne("luo2888_serialnum", "*", "where name=$name");
    if ($users || $serial) {
        genName();
    } else {
        unset($users,$serial);
        return $name;
    }
}

// 随机字符串
function randomStr($len) {
    $chars = array(
        "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
        "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
        "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
        "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
        "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
        "3", "4", "5", "6", "7", "8", "9"
    );

    $charsLen = count($chars) - 1;
    shuffle($chars);
    $str = '';
    for ($i=0; $i<$len; $i++) {
        $str .= $chars[mt_rand(0, $charsLen)];
    }
    return $str;
}

// 输出频道数据
function echoJSON($user, $category, $alisname, $psw) {

    global $db, $channelNumber, $key, $myurl;
    $ckey = $db->mGet("luo2888_config", "value", "where name='keyproxy'");
    $today = strtotime(date("Y-m-d") , time());

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
            if (strstr($row['url'], "fmitv") != false) {
                $url = str_replace('fmitv://', mUrl() . '/', $row['url']);
                $sourceArray[$row['name']][] = str_replace('play', 'play' . '&user=' . $user . '&tsum=' . md5('fmitv_' . $user . $ckey . $today), $url);
            } else if (strstr($row['url'], "http") != false && strstr($row['url'], "migu") == false) {
                $sourceArray[$row['name']][] = $myurl . '?tvplay&cid=' . $row['id'] . '&user=' . $user . '&tsum=' . md5($row['id'] . $user . $ckey . $today);
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

if (isset($_GET['bgpic'])) {

    header('Content-Type: text/json;charset=UTF-8');
    $files = glob("images/*.png");

    foreach ($files as $file) {
        $pngs[] = dirname($myurl) . '/' . $file;
    }

    $rkey = array_rand($pngs);
    echo $pngs[$rkey];
    exit;

}

else if (isset($_GET['getver'])) {

    $appver = $db->mGet("luo2888_config", "value", "where name='appver'");
    $appurl = $db->mGet("luo2888_config", "value", "where name='appurl'");
    $boxver = $db->mGet("luo2888_config", "value", "where name='boxver'");
    $boxurl = $db->mGet("luo2888_config", "value", "where name='boxurl'");
    $up_size = $db->mGet("luo2888_config", "value", "where name='up_size'");
    $up_sets = $db->mGet("luo2888_config", "value", "where name='up_sets'");
    $up_text = $db->mGet("luo2888_config", "value", "where name='up_text'");

    $data = json_encode(
        array(
            'appver' => $appver,
            'appurl' => $appurl,
            'boxver' => $boxver,
            'boxurl' => $boxurl,
            'appsets' => $up_sets,
            'appsize' => $up_size,
            'apptext' => $up_text,
        ),JSON_UNESCAPED_UNICODE
    );

    header("Content-type: text/json; charset=utf-8");
    header("Cache-Control:no-cache,must-revalidate");
    header("Pragma: no-cache");
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

    $iploc = $remote -> getloc($db,$userip);
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

else if (isset($_GET['tvinfo'])) {

    $channel_name = $_GET['channel'];
    $apidir = dirname($myurl) . '/api/common';
    if (!empty($_GET["simple"])) {
        $epgdata =  file_get_contents("$apidir/tvguide.php?simple=1&channel=" . $channel_name);
    } else {
        $epgdata =  file_get_contents("$apidir/tvguide.php?channel=" . $channel_name);
    }

    $epgarray = json_decode($epgdata, true);
    if (empty($epgarray['data'])) {
        exit('{"code":500,"msg":"EPG接口发生错误！","data":{"name":"暂无节目信息","starttime":"00:00"}}');
    } else {
        exit($epgdata);
    }

}

else if (isset($_GET['tvplay'])) {

    $user = $_GET['user'];
    $token = $_GET['tsum'];
    $channelid = $_GET['cid'];
    $uptime = $db->mGet("luo2888_config", "value", "where name='updateinterval'");
    $vpntimes = $db->mGet("luo2888_config", "value", "where name='vpntimes'");
    $failureurl = $db->mGet("luo2888_config", "value", "where name='failureurl'");
    $deniedurl = $db->mGet("luo2888_config", "value", "where name='deniedurl'");
    $status = $db->mGet("luo2888_users", "status", "where name='$user'");
    $lasttime = $db->mGet("luo2888_users", "lasttime", "where name='$user'");
    $uservpntimes = $db->mGet("luo2888_users", "vpn", "where name='$user'");
    $ckey = $db->mGet("luo2888_config", "value", "where name='keyproxy'");
    preg_match('/\((.*?)\)/i', $_SERVER['HTTP_USER_AGENT'], $tokenstr);

    $usertoken = explode(".",  $tokenstr[1]);
    $authkey = hexdec($usertoken[1]) ^ trim($user);
    if ($user != $usertoken[0])
    {
        header('location:' . $deniedurl);
        exit('您被系统判定为盗链！');
    }
    else if ($authkey != $today)
    {
        header('location:' . $deniedurl);
        exit('您被系统判定为盗链！');
    }
    else if ($token != md5($channelid . $user . $ckey . $today))
    {
        header('location:' . $deniedurl);
        exit('您被系统判定为盗链！');
    }
    else if (abs($nowtime - $lasttime) > $uptime * 5)
    {
        header('location:' . $failureurl);
        exit('未能检测到用户状态！');
    }
    else if ($uservpntimes >= $vpntimes)
    {
        header('location:' . $deniedurl);
        exit('您被系统判定为抓包！');
    }
    else if ($status == 0)
    {
        header('location:' . $deniedurl);
        exit('您已被系统禁止访问！');
    }

    $playurl = $db->mGet("luo2888_channels", "url", "where id='$channelid'");

    if (!empty($playurl)) {
        header('location:' . $playurl);
    } else {
        header('location:' . $failureurl);
    }

    exit;

}

else if (isset($_POST['active'])) {

    $actstr = $_POST['active'];
    $actstr = preg_replace("# #", "+", $actstr);
    $gzstr = base64_decode($actstr);
    $jsonstr = gzuncompress($gzstr);
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

    if ($row = $db->mCheckRow("luo2888_users", "status,name,exp,marks,author,authortime", "where name=$serial and status>0 and mac='' and deviceid=''")) {
        $status = $row['status'];
        $exp = $row['exp'];
        $marks = $row['marks'];
        $author = $row['author'];
        $authortime = $row['authortime'];

        //通过SN重新绑定
        $db->mDel("luo2888_users","where mac='$mac' and deviceid='$androidid' and model='$model' and status=-1");
        $db->mSet("luo2888_users","mac='$mac',deviceid='$androidid',model='$model'","where name=$serial and status>0 and mac='' and deviceid='' and model=''");
        exit("已为你重新绑定账号，请返回重新登录。");	

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
            exit("授权号绑定成功，正在登录，请稍后...");
        } else {
            exit("授权号错误或已激活，请检查账号！！");
			   }
    }
    unset($row);
}

else if (isset($_POST['login'])) {

    $loginstr = $_POST['login'];
    $loginstr = preg_replace("# #", "+", $loginstr);
    $gzstr = base64_decode($loginstr);
    $jsonstr = gzuncompress($gzstr);
    $obj = json_decode($jsonstr);
    $androidid = $obj->androidid;
    $iv = $obj->iv;
    $mac = $obj->mac;
    $model = $obj->model;
    $nettype = $obj->nettype;
    $appname = $obj->appname;
    $userip = $remote -> getuserip();
    $ipcount = $db->mGet("luo2888_users", "count(*)", "where ip='$userip'");
    $ipadmit = $db->mGet("luo2888_config", "value", "where name='max_sameip_user'");

    if ($userip == '' || $userip == '127.0.0.1') {
      $userip = '127.0.0.1';
      $region = 'localhost'; 
    }

    if (empty($region)) {
        $json = $remote -> getloc($db,$userip);
        $obj = json_decode($json);
        $region = $obj->data->region . $obj->data->city . $obj->data->isp;
    } 

    /* if (strpos($region, '电信') !== false || strpos($region, '联通') !== false || strpos($region, '移动') !== false) {
        $banuser = 1;
        goto banuser;
    } */

    // 没有mac禁止登陆
    if(strstr($mac,":") == false) {
        $nomacuser = 1;
        goto banuser;
    }

    // 设备ID与型号相同时mac变动自动更改
    if ($row = $db->mCheckRow("luo2888_users", "mac", "where deviceid='$androidid' and model='$model'")) {
        $devicemac = $row['mac'];
        if ($mac != $devicemac){
            $db->mSet("luo2888_users", "mac='$mac'", "where deviceid='$androidid' and model='$model'"); 
        }
    }

    // mac是否匹配
    if ($row = $db->mCheckRow("luo2888_users", "name,status,exp,deviceid,mac,model,meal", "where mac='$mac' and model='$model'")) {

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
        	$db->mSet("luo2888_users", "deviceid='$androidid'", "where mac='$mac'"); 
        }

        // 更新位置，登陆时间
        $db->mSet("luo2888_users", "region='$region',ip='$userip',logintime=$nowtime", "where mac='$mac'"); 

    } else {

        // 禁止重复用户注册
        if ($ipcount > $ipadmit) {
            $sameuser = 1;
            goto banuser;
        }

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
        $db->mInt("luo2888_users", "name,mac,deviceid,model,exp,ip,status,region,logintime,marks", "$name,'$mac','$androidid','$model',$exp,'$userip',$status,'$region',$nowtime,'$marks'");
        if ($days > 0 && $status == -1) {
            $status = 1;
        } else if ($status2 == -999) {
            $status = 1;
        } 
    } 
    unset($row);

    $app_useragent = $db->mGet("luo2888_config", "value", "where name='app_useragent'");
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
    $randkey = $db->mGet("luo2888_config", "value", "where name='randkey'");
    $updateinterval = $db->mGet("luo2888_config", "value", "where name='updateinterval'");
    $keyproxy = $db->mGet("luo2888_config", "value", "where name='keyproxy'");
    $tiploading = $db->mGet("luo2888_config", "value", "where name='tiploading'");
    $tipusernoreg = '账号:' . $name . '，' . $db->mGet("luo2888_config", "value", "where name='tipusernoreg'");
    $tipuserexpired = '账号:' . $name . '，' . $db->mGet("luo2888_config", "value", "where name='tipuserexpired'");
    $tipuserforbidden = '账号:' . $name . '，' . $db->mGet("luo2888_config", "value", "where name='tipuserforbidden'");
    $datatoken = "token=" . md5($name . $b64str . $randkey);

    if ($status2 < 1) {
         $adtext = $db->mGet("luo2888_config", "value", "where name='adtext_free'");
    } 

    $author = $db->mGet("luo2888_users", "author", "where name='$name'");
    if (strstr($author, "A") != false) {
        $adtext = $db->mGet("luo2888_agents", "adtext", "where id='$author'");
        $adinfo = $db->mGet("luo2888_agents", "adinfo", "where id='$author'");
    } 

    if ($status2 == -999) {
        $status = 999;
    } 

    $mealname = $db->mGet("luo2888_meals", "name", "where id='$mealid'");
    $week = array('日', '一', '二', '三', '四', '五', '六');
    $adtext =  '尊敬的『' . $name . '』，今天' . date('n月d号') . "，" . '星期' . $week[date('w')] . '。' . $adtext;

    $result = $db->mQuery("SELECT name from luo2888_category where enable=1 and type='province' order by id");
    while ($row = mysqli_fetch_array($result)) {
        $arrprov[] = $row[0];
    } 
    unset($row);
    mysqli_free_result($result);

    $result = $db->mQuery("SELECT name,url from luo2888_vods where status=1 order by id");
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

    if ($status < 1) {
        $datatoken = '';
        $keyproxy = '';
        $randkey = '';
        $voddatas = '';
    } 

    banuser:
    if ($vpnuser == 1) {
        $status = 0;
        $tipuserforbidden= '使用了VPN等程序' . $uservpntimes . '次，系统判定抓包已禁止登录！';
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

    $accesstoken = strtoupper(dechex($today ^ $name));

    $objres = array('id' => $name, 'status' => $status, 'mealname' => $mealname, 'datatoken' => $datatoken, 'appurl' => $appurl, 'dataver' => $dataver, 'appver' => $appver, 'setver' => $setver, 'adtext' => $adtext, 'showinterval' => $showinterval, 'exp' => $days, 'userip' => $userip, 'showtime' => $showtime , 'provlist' => $arrprov, 'canseeklist' => $arrcanseek, 'decoder' => $decoder, 'buffTimeOut' => $buffTimeOut, 'tipusernoreg' => $tipusernoreg, 'tiploading' => $tiploading, 'tipuserforbidden' => $tipuserforbidden, 'tipuserexpired' => $tipuserexpired, 'adinfo' => $adinfo, 'keyproxy' => $keyproxy, 'location' => $region, 'nettype' => $nettype, 'autoupdate' => 1, 'updateinterval' => $updateinterval, 'randkey' => $randkey, 'exps' => $exp, 'movieengine' => $voddatas, 'useragent' => $app_useragent, 'accesstoken' => $accesstoken);

    $objres = str_replace("\\/", "/", json_encode($objres, JSON_UNESCAPED_UNICODE)); 
    $key = substr($key, 9, 20);
    $ivkey = substr(md5($iv), 7, 23);
    $aes = new Aes($key,$ivkey);
    $encrypted = $aes -> encrypt($objres);
    echo $encrypted;
    exit;

}

else if (isset($_POST['tvdata']) && isset($_GET['token'])) {

    $token = $_GET['token'];
    $datastr  = $_POST['tvdata'];
    $datastr = preg_replace("# #", "+", $datastr);
    $gzstr = base64_decode($datastr);
    $jsonstr = gzuncompress($gzstr);
    $obj = json_decode($jsonstr);
    $androidid = $obj->androidid;
    $mac = $obj->mac;
    $model = $obj->model;
    $region = $obj->region;
    $nettype = $obj->nettype;
    $randkey = $obj->rand;
    $iv = $obj->iv;
    $pdkey = md5($iv);
    $userip = $remote -> getuserip();
    $username = $db->mGet("luo2888_users", "name", "where mac='$mac'");
    $logintime = $db->mGet("luo2888_users", "logintime", "where mac='$mac'");

    if (empty($region)) {
        $json = $remote -> getloc($db,$userip);
        $obj = json_decode($json);
        $region = $obj->data->region . $obj->data->city . $obj->data->isp;
    }

    if (abs($nowtime - $logintime) > 86400) {
        header('HTTP/1.1 403 Forbidden');
        exit;
    }

    if ($token != md5($username . $b64str . $randkey)) {
         $db->mInt("luo2888_record","id,name,ip,loc,time,func","null,'主动拦截','$userip','$region','$datetime','虚假设备登录已拦截！'");
        header('HTTP/1.1 403 Forbidden');
        exit;
    }

    // 更新在线时间
    $db->mSet("luo2888_users", "lasttime=$nowtime", "where mac='$mac'");

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
    $contents[] = echoJSON($username, '', "我的收藏", '', $pdkey); 

    // 默认套餐不输出各省的频道
    if ($mid != 1000) {

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
    $str = str_replace('null,', '', $str);
    $str = stripslashes($str);
    $str = base64_encode(gzcompress($str));
    $key = md5($key . $randkey);
    $key = substr($key, 13, 22);
    $ivkey = substr(md5($iv), 9, 25);
    $aes = new Aes($key,$ivkey);
    $encrypted = $aes->encrypt($str);
    $encrypted = str_replace("h", "&", $encrypted);
    $encrypted = str_replace("k", "h", $encrypted);
    $encrypted = str_replace("&", "k", $encrypted);
    $encrypted = str_replace("z", "#", $encrypted);
    $encrypted = str_replace("i", "z", $encrypted);
    $encrypted = str_replace("#", "i", $encrypted);
    $coded = substr($encrypted, 44, 192);
    $coded = strrev($coded);
    $datastr = $coded . $encrypted;
    echo $datastr;
    exit;

} else {

    header('HTTP/1.1 403 Forbidden');
    exit;

} 

?>
