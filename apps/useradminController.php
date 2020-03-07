<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);

include_once "../config.php";

if ($_SESSION['useradmin'] == 0) {
    echo"<script>alert('你无权访问此页面！');history.go(-1);</script>";
    exit();
} 

if (isset($_POST['submitdelall'])) {
    $nowtime = time();
    $sql = "delete from luo2888_users where status=1 and exp<$nowtime";
    mysqli_query($GLOBALS['conn'], $sql);
    exit('<script>javascript:self.location=document.referrer;alert("已清空所有过期用户！")</script>');
} 

if (isset($_POST['submitdel'])) {
    if (empty($_POST['id'])) {
        exit('<script>javascript:self.location=document.referrer;alert("请选择要删除的用户账号信息！")</script>');
    } 
    foreach ($_POST['id'] as $id) {
        mysqli_query($GLOBALS['conn'], "delete from luo2888_users where name=$id");
        mysqli_query($GLOBALS['conn'], "delete from luo2888_loginrec where userid=$id");
    } 
    exit('<script>javascript:self.location=document.referrer;alert("选中用户及其登陆信息已删除！")</script>');
} 
if (isset($_POST['submitsetvip'])) {
    if (empty($_POST['id'])) {
        exit('<script>javascript:self.location=document.referrer;alert("请选择要设置为VIP的用户账号")</script>');
    } 
    foreach ($_POST['id'] as $id) {
        mysqli_query($GLOBALS['conn'], "update luo2888_users set isvip=1 where name=$id");
    } 
    exit('<script>javascript:self.location=document.referrer;alert("选中用户已设置为VIP！")</script>');
} 
if (isset($_POST['submitclearvip'])) {
    if (empty($_POST['id'])) {
        exit('<script>javascript:self.location=document.referrer;alert("请选择要取消VIP的用户账号")</script>');
    } 
    foreach ($_POST['id'] as $id) {
        mysqli_query($GLOBALS['conn'], "update luo2888_users set isvip=0 where name=$id");
    } 
    exit('<script>javascript:self.location=document.referrer;alert("选中用户已取消VIP！")</script>');
} 
if (isset($_POST['submitmodify'])) {
    $expimportmac = $_POST['exp'];
    $exp = strtotime(date("Y-m-d"), time()) + 86400 * $_POST['exp'];
    foreach ($_POST['id'] as $id) {
        mysqli_query($GLOBALS['conn'], "update luo2888_users set exp=$exp where name=$id and status=1");
        mysqli_query($GLOBALS['conn'], "update luo2888_users set exp=$expimportmac where name=$id and status=2");
    } 
    exit('<script>javascript:self.location=document.referrer;alert("选中用户授权天数已修改！")</script>');
} 

if (isset($_POST['submitadddays'])) {
    if (empty($_POST['id'])) {
        exit('<script>javascript:self.location=document.referrer;alert("请选择要增加授权天数的用户账号信息！")</script>');
    } 
    $expimportmac = $_POST['exp'];
    $exp = 86400 * $_POST['exp'];
    foreach ($_POST['id'] as $id) {
        mysqli_query($GLOBALS['conn'], "update luo2888_users set exp=exp+$exp where name=$id and status=1");
        mysqli_query($GLOBALS['conn'], "update luo2888_users set exp=exp+$expimportmac where name=$id and status=2");
    } 
    exit('<script>javascript:self.location=document.referrer;alert("选中用户授权天数已增加！")</script>');
} 

if (isset($_POST['submitmodifymarks'])) {
    if (empty($_POST['id'])) {
        exit('<script>javascript:self.location=document.referrer;alert("请选择要修改备注的用户账号信息！")</script>');
    } 
    $marks = $_POST['marks'];
    foreach ($_POST['id'] as $id) {
        mysqli_query($GLOBALS['conn'], "update luo2888_users set marks='$marks' where name=$id");
    } 
    exit('<script>javascript:self.location=document.referrer;alert("选中用户备注已修改！")</script>');
} 

if (isset($_POST['submitforbidden'])) {
    if (empty($_POST['id'])) {
        exit('<script>javascript:self.location=document.referrer;alert("请选择要取消授权的用户账号信息！")</script>');
    } 
    foreach ($_POST['id'] as $id) {
        mysqli_query($GLOBALS['conn'], "update luo2888_users set status=0,author='',authortime=0 where name=$id and (status=1 or status=999)");
    } 
    exit('<script>javascript:window.location.href="author.php";alert("选中用户已取消授权！")</script>');
} 

if (isset($_POST['submitNotExpired'])) {
    if (empty($_POST['id'])) {
        exit('<script>javascript:self.location=document.referrer;alert("请选择要设置永不到期的用户账号信息！")</script>');
    } 
    foreach ($_POST['id'] as $id) {
        mysqli_query($GLOBALS['conn'], "update luo2888_users set status=999 where name=$id and status=1");
    } 
    exit('<script>javascript:self.location=document.referrer;alert("选中用户已设置为永不到期！")</script>');
} 

if (isset($_POST['submitCancelNotExpired'])) {
    if (empty($_POST['id'])) {
        exit('<script>javascript:self.location=document.referrer;alert("请选择要取消永不到期的用户账号信息！")</script>');
    } 
    foreach ($_POST['id'] as $id) {
        mysqli_query($GLOBALS['conn'], "update luo2888_users set status=1 where name=$id and status=999");
    } 
    exit('<script>javascript:self.location=document.referrer;alert("选中用户已取消永不到期权限！")</script>');
} 

if (isset($_POST['submitmodifyipcount'])) {
    $ipcount = $_POST['ipcount'];
    mysqli_query($GLOBALS['conn'], "update luo2888_appdata set ipcount=$ipcount");
} 

if (isset($_POST['recCounts'])) {
    $recCounts = $_POST['recCounts'];
    mysqli_query($GLOBALS['conn'], "update luo2888_admin set showcounts=$recCounts where name='$user'");
} 

$searchparam = '';
if (isset($_GET['keywords'])) {
    $keywords = trim($_GET['keywords']);
    $searchparam = "and (name like '%$keywords%' or deviceid like '%$keywords%' or mac like '%$keywords%' or name like '%$keywords%' or model like '%$keywords%' or ip like '%$keywords%' or region like '%$keywords%' or author like '%$keywords%' or marks like '%$keywords%')";
} 

if (isset($_GET['submitsearch'])) {
    $keywords = trim($_GET['keywords']);
    $searchparam = "and (name like '%$keywords%' or deviceid like '%$keywords%' or mac like '%$keywords%' or name like '%$keywords%' or model like '%$keywords%' or ip like '%$keywords%' or region like '%$keywords%' or author like '%$keywords%' or marks like '%$keywords%')";
} 
// 获取每页显示数量
$result = mysqli_query($GLOBALS['conn'], "select showcounts from luo2888_admin where name='$user'");
if ($row = mysqli_fetch_array($result)) {
    $recCounts = $row['showcounts'];
} else {
    $recCounts = 100;
} 
// 获取每日允许登陆IP数量
$result = mysqli_query($GLOBALS['conn'], "select ipcount from luo2888_appdata");
if ($row = mysqli_fetch_array($result)) {
    $ipcount = $row['ipcount'];
} else {
    $ipcount = 5;
} 
unset($row);
mysqli_free_result($result);
// 获取当前页
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
} 
// 获取排序依据
if (isset($_GET['order'])) {
    $order = $_GET['order'];
} else {
    $order = 'lasttime desc';
} 
// 获取用户总数并根据每页显示数量计算页数
$result = mysqli_query($GLOBALS['conn'], "select count(*) from luo2888_users where status>-1");
if ($row = mysqli_fetch_array($result)) {
    $userCount = $row[0];
    $pageCount = ceil($row[0] / $recCounts);
} else {
    $userCount = 0;
    $pageCount = 1;
} 
unset($row);
mysqli_free_result($result);
// 处理跳转逻辑
if (isset($_POST['jumpto'])) {
    $p = $_POST['jumpto'];
    if (($p <= $pageCount) && ($p > 0)) {
        header("location:?page=$p&order=$order");
    } 
} 
// todayTime为24小时前时间
$todayTime = strtotime(date("Y-m-d"), time());
$result = mysqli_query($GLOBALS['conn'], "select count(*) from luo2888_users where status>-1 and lasttime>$todayTime");
if ($row = mysqli_fetch_array($result)) {
    $todayuserCount = $row[0];
} else {
    $todayuserCount = 0;
} 
unset($row);
mysqli_free_result($result);

$result = mysqli_query($GLOBALS['conn'], "select count(*) from luo2888_users where status>-1 and authortime>$todayTime");
if ($row = mysqli_fetch_array($result)) {
    $todayauthoruserCount = $row[0];
} else {
    $todayauthoruserCount = 0;
} 
unset($row);
mysqli_free_result($result);

$nowTime = time();
$result = mysqli_query($GLOBALS['conn'], "select count(*) from luo2888_users where status=1 and exp<$nowTime");
if ($row = mysqli_fetch_array($result)) {
    $expuserCount = $row[0];
} else {
    $expuserCount = 0;
} 
unset($row);
mysqli_free_result($result);

?>