<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);

include_once "../config.php";

if ($_SESSION['ipcheck'] == 0) {
    echo"<script>alert('你无权访问此页面！');history.go(-1);</script>";
    exit();
} 

if (isset($_POST['submitunbind'])) {
    $userid = $_POST['userid'];
    $result = mysqli_query($GLOBALS['conn'], "select * from luo2888_users where name=$userid");
    if (mysqli_fetch_array($result)) {
        mysqli_query($GLOBALS['conn'], "update luo2888_users set mac='',deviceid='',model='' where name=$userid");
        exit('<script>javascript:self.location=document.referrer;alert("账号$userid 解绑成功！")</script>');
    } else {
        exit('<script>javascript:self.location=document.referrer;alert("账号不存在！")</script>');
    } 
} 

if (isset($_POST['clearvpn'])) {
    $result = mysqli_query($GLOBALS['conn'], "UPDATE luo2888_users set vpn=0");
    exit('<script>javascript:self.location=document.referrer;alert("抓包记录已清空")</script>');
} 

if (isset($_POST['stopuse'])) {
    $name = $_POST['name'];
    $now = time();
    $result = mysqli_query($GLOBALS['conn'], "UPDATE luo2888_users set status=0 where name=$name");
} 

if (isset($_POST['startuse'])) {
    $name = $_POST['name'];
    $result = mysqli_query($GLOBALS['conn'], "UPDATE luo2888_users set status=1 where name=$name and status=0");
} 

if (isset($_POST['submitmodifyipcount'])) {
    $ipcount = $_POST['ipcount'];
    mysqli_query($GLOBALS['conn'], "update luo2888_appdata set ipcount=$ipcount");
} 

if (isset($_POST['submitclearold'])) {
    $oldtime = strtotime(date("Y-m-d"), time());
    mysqli_query($GLOBALS['conn'], "delete from luo2888_loginrec where logintime<$oldtime");
} 

if (isset($_POST['submitclearall'])) {
    mysqli_query($GLOBALS['conn'], "delete from luo2888_loginrec");
} 

if (isset($_POST['submitsameip_user'])) {
    $sameip_user = $_POST['sameip_user'];
    set_config('max_sameip_user', "$sameip_user");
    echo('<script>javascript:self.location=document.referrer;alert("保存成功！")</script>');
} 
// 获取每日允许登陆IP数量
$result = mysqli_query($GLOBALS['conn'], "select ipcount from luo2888_appdata");
if ($row = mysqli_fetch_array($result)) {
    $ipcount = $row['ipcount'];
} else {
    $ipcount = 5;
} 

?>