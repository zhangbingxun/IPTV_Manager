<?php
ini_set("error_reporting", "E_ALL & ~E_NOTICE");

session_start();
if ($_SESSION['secret_key_status'] != "1") {
    header('location:../index.php');
    exit();
} 

include_once "../config.php";

if (!empty($_SERVER['HTTP_X_REAL_IP'])) {
    $ip = $_SERVER['HTTP_X_REAL_IP'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
} 
$myurl = 'http://' . $_SERVER['HTTP_HOST'];
$json = file_get_contents("$myurl/getIpInfo.php?ip=$ip");
$obj = json_decode($json);
$region = $obj->data->region . $obj->data->city . $obj->data->isp;
$time = date("Y-m-d H:i:s");

if (!empty($_POST['username']) && !empty($_POST['password'])) {
    $user = mysqli_real_escape_string($GLOBALS['conn'], $_POST['username']);
    $psw = mysqli_real_escape_string($GLOBALS['conn'], $_POST['password']);
    $psw = md5(PANEL_MD5_KEY . $psw);
    $result = mysqli_query($GLOBALS['conn'], "select * from luo2888_admin where name='$user'");
    if ($row = mysqli_fetch_array($result)) {
        if ($psw == $row['psw']) {
            $user = $row['name'];
            $_SESSION['user'] = $user;
            $_SESSION['psw'] = $row['psw'];
            $_SESSION['author'] = $row['author'];
            $_SESSION['useradmin'] = $row['useradmin'];
            $_SESSION['ipcheck'] = $row['ipcheck'];
            $_SESSION['epgadmin'] = $row['epgadmin'];
            $_SESSION['channeladmin'] = $row['channeladmin'];
            if (isset($_POST['rememberpass'])) {
                setcookie("username", $user, time() + 3600 * 24 * 7);
                setcookie("psw", $row['psw'], time() + 3600 * 24 * 7);
                setcookie("rememberpass", "1", time() + 3600 * 24 * 7);
            } else {
                setcookie("rememberpass", "1", time()-3600);
            } 
            mysqli_query($GLOBALS['conn'], "INSERT into luo2888_adminrec (id,name,ip,loc,time,func) values(null,'$user','$ip','$region','$time','用户登入')");
            unset($row);
            mysqli_free_result($result);
            mysqli_close($GLOBALS['conn']);
            echo '<script language=JavaScript>lightyear.loading("hide");window.location.replace("views/index.php");</script>';
            header("location:views/index.php");
        } else {
            echo "<script>alert('密码错误！');</script>";
            mysqli_query($GLOBALS['conn'], "INSERT into luo2888_adminrec (id,name,ip,loc,time,func) values(null,'$user','$ip','$region','$time','输入错误密码')");
        } 
    } else {
        echo "<script>alert('用户不存在！');</script>";
        mysqli_query($GLOBALS['conn'], "INSERT into luo2888_adminrec (id,name,ip,loc,time,func) values(null,'$user','$ip','$region','$time','尝试登陆')");
    } 
    unset($row);
    mysqli_free_result($result);
    mysqli_close($GLOBALS['conn']);
} 

if (isset($_COOKIE['rememberpass'])) {
    $user = mysqli_real_escape_string($GLOBALS['conn'], $_COOKIE['username']);
    $psw = mysqli_real_escape_string($GLOBALS['conn'], $_COOKIE['psw']);
    $result = mysqli_query($GLOBALS['conn'], "select * from luo2888_admin where name='$user'");
    if ($row = mysqli_fetch_array($result)) {
        if ($psw == $row['psw']) {
            $_SESSION['user'] = $user;
            $_SESSION['psw'] = $psw;
            $_SESSION['author'] = $row['author'];
            $_SESSION['useradmin'] = $row['useradmin'];
            $_SESSION['ipcheck'] = $row['ipcheck'];
            $_SESSION['epgadmin'] = $row['epgadmin'];
            $_SESSION['channeladmin'] = $row['channeladmin'];
            mysqli_query($GLOBALS['conn'], "INSERT into luo2888_adminrec (id,name,ip,loc,time,func) values(null,'$user','$ip','$region','$time','用户登入')");
            unset($row);
            mysqli_free_result($result);
            mysqli_close($GLOBALS['conn']);
            echo '<script language=JavaScript>lightyear.loading("hide");</script>';
            header("location:views/index.php");
        } 
    } 
    unset($row);
    mysqli_free_result($result);
    mysqli_close($GLOBALS['conn']);
} 

?>