<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);

require_once "config.php";
$db = Config::GetIntance();
$remote = new GetIP();
$channelNumber = 1;
$myurl = mUrl();
$nowtime = time();

$appsign = $db->mGet("luo2888_config","value","where name='app_sign'");
$appname = $db->mGet("luo2888_config","value","where name='app_appname'");
$packagename = $db->mGet("luo2888_config","value","where name='app_packagename'");
$appkey = md5($appsign . $appname . $packagename . '?^?0F(??!(??');
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
    $result = $db->mGet("luo2888_users", "*", "where name=$name");
    if (!$result) {
        unset($result);
        return $name;
    } else {
        genName();
    }
}

// 输出频道数据
function echoJSON($category, $alisname, $psw) {

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
                $sourceArray[$row['name']][] = mUrl() . '?tvplay&id=' . $row['id'] . '&time=' . $nowtime . '&token=' . md5($row['id'] . $userip . $nowtime . $key);
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

else if (isset($_GET['tvplay'])) {

    $channelid = $_GET['id'];
    $token = $_GET['token'];
    $time = $_GET['time'];
    $userip = $remote -> getuserip();
    $uptime = $db->mGet("luo2888_config", "value", "where name='updateinterval'");

    if (strstr($_SERVER['HTTP_USER_AGENT'], "FMITV") == false) 
    {
        header('HTTP/1.1 403 Forbidden');
        exit;
    }

    if (abs($nowtime - $time) > $uptime * 60 + 30) {
        header('HTTP/1.1 403 Forbidden');
        exit();
    }
    else if ($token != md5($channelid . $userip . $time . $key))
    {
        header('HTTP/1.1 403 Forbidden');
        exit();
    }

    $playurl = $db->mGet("luo2888_channels", "url", "where id='$channelid'");
    header('location:' . $playurl);
    exit;

}

else if (isset($_POST['login'])) {

    $userip = $remote -> getuserip();
    $ipcount = $db->mGet("luo2888_users", "count(*)", "where ip='$ip'");
    $ipadmit = $db->mGet("luo2888_config", "value", "where name='max_sameip_user'");

    if ($ipcount >= $ipadmit) {
        header('HTTP/1.1 403 Forbidden');
        exit();
    }

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
        header('HTTP/1.1 403 Forbidden');
        exit();
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

        /* if (strpos($region, '电信') !== false || strpos($region, '联通') !== false || strpos($region, '移动') !== false) {
            $newuser= true;
            goto cond;
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
    cond:
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
    $adtext = '尊敬的用户，欢迎使用' . $app_appname . '，当前套餐：' . $mealname . '。' . $adtext;

    if ($showwea == 1) {
        $weaapi_id = $db->mGet("luo2888_config", "value", "where name='weaapi_id'");
        $weaapi_key = $db->mGet("luo2888_config", "value", "where name='weaapi_key'");
        $url = "https://www.tianqiapi.com/api?version=v6&appid=$weaapi_id&appsecret=$weaapi_key&ip=$userip";
        $weajson = file_get_contents($url);
        $obj = json_decode($weajson);
        if (!empty($obj->city)) {
            $weather = date('今天n月d号') . $obj->week . '，' . $obj->city . '，' . $obj->tem . '℃' . $obj->wea . '，' . '气温:' . $obj->tem2 . '℃' . '～' . $obj->tem1 . '℃' . '，' . $obj->win . $obj->win_speed . '，' . '相对湿度:' . $obj->humidity . '，' . '空气质量:' . $obj->air_level . '。';

            $adtext = $adtext . $weather;
        } 
    } 

    if ($status < 1) {
        $datatoken = '';
        $appurl = '';
    } 
    
    if ($newuser == true){
        $status = -1;
        $tipusernoreg= '禁止国内IP访问';
    }
    
    $result = $db->mQuery("SELECT name from luo2888_category where enable=1 and type='province' order by id");
    while ($row = mysqli_fetch_array($result)) {
        $arrprov[] = $row[0];
    } 
    $arrcanseek[] = '';

    $objres = array('status' => $status, 'mealname' => $mealname, 'datatoken' => $datatoken, 'appurl' => $appurl, 'dataver' => $dataver, 'appver' => $appver, 'setver' => $setver, 'adtext' => $adtext, 'showinterval' => $showinterval, 'exp' => $days, 'ip' => $userip, 'showtime' => $showtime , 'provlist' => $arrprov, 'canseeklist' => $arrcanseek, 'id' => $name, 'decoder' => $decoder, 'buffTimeOut' => $buffTimeOut, 'tipusernoreg' => $tipusernoreg, 'tiploading' => $tiploading, 'tipuserforbidden' => $tipuserforbidden, 'tipuserexpired' => $tipuserexpired, 'adinfo' => $adinfo, 'keyproxy' => $keyproxy, 'location' => $region, 'nettype' => $nettype, 'autoupdate' => $autoupdate, 'updateinterval' => $updateinterval, 'randkey' => $randkey, 'exps' => $exp);

    $objres = str_replace("\\/", "/", json_encode($objres, JSON_UNESCAPED_UNICODE)); 
    $key = substr($key, 5, 16);
    $aes = new Aes($key,$iv);
    $encrypted = $aes -> encrypt($objres);
    mysqli_free_result($result);
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
    $contents[] = echoJSON('', "我的收藏", ''); 

    // 默认套餐不输出运营商和各省的频道
    if ($mid != 1000) {

        // 添加运营商频道数据
        if (!empty($nettype)) {
            $result = $db->mQuery("SELECT name,id,psw FROM luo2888_category where enable=1 and type='$nettype' order by id");
            while ($row = mysqli_fetch_array($result)) {
                $pdname = $row['name'];
                $psw = $row['psw'];
                $contents[] = echoJSON($pdname, $pdname, $psw);
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
                $contents[] = echoJSON($pdname, '省内', $psw);
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
                $contents[] = echoJSON($pdname, $pdname, $psw);
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
