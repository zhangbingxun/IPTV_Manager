<?php
ini_set("error_reporting", "E_ALL & ~E_NOTICE");

session_start();
require_once "config.php";
$remote = new GetIP();
$db = Config::GetIntance();
$time = date("Y-m-d H:i:s");
$userip = $remote->getuserip();
$json = $remote -> getloc($db,$myurl,$userip);
$iploc = json_decode($json);
$region = $iploc->data->region . $iploc->data->city . $iploc->data->isp;
$skey =  $db->mGet("luo2888_config","value","where name='secret_key'");
        
if (isset($_COOKIE['secret_key'])) {
    $secret_key = $_COOKIE['secret_key'];
} else {
    $secret_key = md5($_SERVER['QUERY_STRING']);
} 

if (!empty($skey) && $secret_key != $skey) {

    header("Content-Type:text/html;chartset=utf-8");
    header('HTTP/1.1 403 Forbidden');
    exit('
        <head>
            <meta name="viewport" content="width=device-width, initial-scale=0.92, maximum-scale=1, user-scalable=no" />
            <link rel="icon" href="/views/images/favicon.ico" type="image/ico">
            <title>安全入口验证错误</title>
        </head>
        <body>
            <h1 style="margin-top: 3.5%;">
                <strong>请使用正确的入口登录面板</strong>
            </h1>
            <p><strong>错误原因</strong>：当前面板已经开启了安全入口登录，请使用正确的安全码进入面板。</p>
            <p><strong>解决方法</strong>：如您没记录或不记得了，请到数据库config表把secret_key参数清空。</p>
            <font color=red>注意：【关闭安全入口】将使您的面板登录地址被直接暴露在互联网上，非常危险，请谨慎操作</font>
            <hr style="margin-top: 2%;">
            <span>Copyright © 2020 小肥米IPTV管理平台</span>
        </body>
    ');
} else {
    setcookie("secret_key", $secret_key, time() + 3600 * 24, "/");
}

if (isset($_COOKIE['rememberpass'])) {
    $user = $db->mEscape_string($_COOKIE['username']);
    $psw = $db->mEscape_string($_COOKIE['password']);
    if ($row = $db->mGetRow("luo2888_admin","*","where name='$user'")) {
        if ($psw == $row['psw']) {
            $_SESSION['user'] = $user;
            $_SESSION['psw'] = $psw;
            $_SESSION['author'] = $row['author'];
            $_SESSION['useradmin'] = $row['useradmin'];
            $_SESSION['ipcheck'] = $row['ipcheck'];
            $_SESSION['epgadmin'] = $row['epgadmin'];
            $_SESSION['mealsadmin'] = $row['mealsadmin'];
            $_SESSION['channeladmin'] = $row['channeladmin'];
            $db->mInt("luo2888_adminrec","id,name,ip,loc,time,func","null,'$user','$userip','$region','$time','用户登入'");
            header("location:views/index.php");
        } 
    }
	unset($row);
} 

if (!empty($_POST['username']) && !empty($_POST['password'])) {
    $user = $db->mEscape_string($_POST['username']);
    $psw = $db->mEscape_string($_POST['password']);
    $psw = md5(PANEL_MD5_KEY . $psw);
    if ($row = $db->mGetRow("luo2888_admin","*","where name='$user'")) {
        if ($psw == $row['psw']) {
            $user = $row['name'];
            $_SESSION['user'] = $user;
            $_SESSION['psw'] = $row['psw'];
            $_SESSION['author'] = $row['author'];
            $_SESSION['useradmin'] = $row['useradmin'];
            $_SESSION['ipcheck'] = $row['ipcheck'];
            $_SESSION['epgadmin'] = $row['epgadmin'];
            $_SESSION['mealsadmin'] = $row['mealsadmin'];
            $_SESSION['channeladmin'] = $row['channeladmin'];
            if (isset($_POST['rememberpass'])) {
                setcookie("username", $user, time() + 3600 * 24 * 7, "/");
                setcookie("password", $row['psw'], time() + 3600 * 24 * 7, "/");
                setcookie("rememberpass", "1", time() + 3600 * 24 * 7, "/");
            } else {
                setcookie("rememberpass", "1", time()-3600, "/");
            } 
            $db->mInt("luo2888_adminrec","id,name,ip,loc,time,func","null,'$user','$userip','$region','$time','用户登入'");
            header("location:views/index.php");
        } else {
            echo "<script>alert('密码错误！');</script>";
            $db->mInt("luo2888_adminrec","id,name,ip,loc,time,func","null,'$user','$userip','$region','$time','输入错误密码'");
        } 
    } else {
        echo "<script>alert('用户不存在！');</script>";
        $db->mInt("luo2888_adminrec","id,name,ip,loc,time,func","null,'$user','$userip','$region','$time','尝试登陆'");
    }
	unset($row);
} 

?>