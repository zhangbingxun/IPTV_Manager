<?php
ini_set("error_reporting", "E_ALL & ~E_NOTICE");

session_start();
require_once "config.php";
$db = Config::GetIntance();
$remote = new GetIP();
$time = date("Y-m-d H:i:s");
$userip = $remote->getuserip();
$json = $remote -> getloc($userip);
$obj = json_decode($json);
$region = $obj->data->region . $obj->data->city . $obj->data->isp;
$getkey =  $db->mGet("luo2888_config","value","where name='secret_key'");
        
if (isset($_GET['key']))
{
    $secret_key = md5($_GET['key']);
    $secret_key = $db->mEscape_string($secret_key);
    setcookie("secret_key", $secret_key, time() + 3600 * 24 * 7, "/");
} 
else if (isset($_COOKIE['secret_key']))
{
    $secret_key = $_COOKIE['secret_key'];
} 

if (!empty($getkey) && $secret_key != $getkey) {
    header('HTTP/1.1 403 Forbidden');
    exit;
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
            $db->mInt("luo2888_adminrec","id,name,ip,loc,time,func","null,'$user','$ip','$region','$time','用户登入'");
            header("location:views/index.php");
        } else {
            echo "<script>alert('密码错误！');</script>";
            $db->mInt("luo2888_adminrec","id,name,ip,loc,time,func","null,'$user','$ip','$region','$time','输入错误密码'");
        } 
    } else {
        echo "<script>alert('用户不存在！');</script>";
        $db->mInt("luo2888_adminrec","id,name,ip,loc,time,func","null,'$user','$ip','$region','$time','尝试登陆'");
    }
	unset($row);
} 

?>