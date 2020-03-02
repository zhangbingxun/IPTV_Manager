<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);

include_once "../config.php";

if ($_SESSION['author'] == 0) {
    echo"<script>alert('你无权访问此页面！');history.go(-1);</script>";
    exit();
} 

if (isset($_POST['submitdel'])) {
    foreach ($_POST['id'] as $id) {
        mysqli_query($GLOBALS['conn'], "delete from luo2888_users where name=$id");
        $sql = "delete from luo2888_users where name='$id'";
        mysqli_query($GLOBALS['conn'], $sql);
    } 
    echo "<script>javascript:self.location=document.referrer;alert('选中用户信息已删除！');</script>";
} 

if (isset($_POST['submitauthor'])) {
    foreach ($_POST['id'] as $id) {
    	if (isset($_POST['submitauthorvip'])) {$isvip=1;} else {$isvip=0;}
        $administrator = $_SESSION['user'];
        $nowtime = time();
        $exp = strtotime(date("Y-m-d"), time()) + 86400 * $_POST['exp'];
        $sql = "update luo2888_users set status=1,exp=$exp,author='$administrator',authortime=$nowtime,isvip=$isvip,marks='已授权' where name='$id'";
        mysqli_query($GLOBALS['conn'], $sql);
    } 
    echo '<script>javascript:window.location.href="useradmin.php";alert("选中用户授权成功！");</script>';
} 

if (isset($_POST['submitauthorforever'])) {
    foreach ($_POST['id'] as $id) {
    	if (isset($_POST['submitauthorvip'])) {$isvip=1;} else {$isvip=0;}
        $exp = strtotime(date("Y-m-d"), time()) + 86400 * 999;
        $administrator = $_SESSION['user'];
        $nowtime = time();
        $sql = "update luo2888_users set status=999,exp=$exp,author='$administrator',authortime=$nowtime,isvip=$isvip,marks='已授权' where name='$id'";
        mysqli_query($GLOBALS['conn'], $sql);
    } 
    echo'<script>javascript:window.location.href="useradmin.php";alert("选中用户已授权为永不到期！")</script>';
} 

if (isset($_POST['submitforbidden'])) {
    if (empty($_POST['id'])) {
        echo'<script>javascript:self.location=document.referrer;alert("请选择要禁用的账号!")</script>';
    } else {
        foreach ($_POST['id'] as $id) {
            $exp = strtotime(date("Y-m-d"), time());
            $administrator = $_SESSION['user'];
            $sql = "update luo2888_users set status=0 where name='$id'";
            mysqli_query($GLOBALS['conn'], $sql);
        } 
        echo'<script>javascript:self.location=document.referrer;alert("选中用户已被禁止试用！")</script>';
    } 
} 

if (isset($_POST['submitdelonedaybefor'])) {
    $onedaybefore = strtotime(date("Y-m-d"), time());
    $sql = "delete from luo2888_users where status=-1 and lasttime<$onedaybefore";
    mysqli_query($GLOBALS['conn'], $sql);
    echo'<script>javascript:self.location=document.referrer;alert("已删除一天前未授权用户！")</script>';
} 

if (isset($_POST['submitdelall'])) {
    $sql = "delete from luo2888_users where status=-1";
    mysqli_query($GLOBALS['conn'], $sql);
    echo'<script>javascript:self.location=document.referrer;alert("已删除所有未授权用户！")</script>';
} 

if (isset($_POST['recCounts'])) {
    $recCounts = $_POST['recCounts'];
    mysqli_query($GLOBALS['conn'], "update luo2888_admin set showcounts=$recCounts where name='$user'");
} 

if (isset($_GET['keywords'])) {
    $keywords = $_GET['keywords'];
    $searchparam = "and (name like '%$keywords%' or mac like '%$keywords%' or deviceid like '%$keywords%' or model like '%$keywords%' or ip like '%$keywords%' or region like '%$keywords%' or lasttime like '%$keywords%')";
} 

$result = mysqli_query($GLOBALS['conn'], "select showcounts from luo2888_admin where name='$user'");
if ($row = mysqli_fetch_array($result)) {
    $recCounts = $row['showcounts'];
} else {
    $recCounts = 100;
} 
unset($row);
mysqli_free_result($result);

$result = mysqli_query($GLOBALS['conn'], "select needauthor,trialdays from luo2888_appdata");
if ($row = mysqli_fetch_array($result)) {
    $needauthor = $row['needauthor'];
    $isfreeuser = $row["trialdays"];
} 
unset($row);
mysqli_free_result($result);

if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
} 

if (isset($_GET['order'])) {
    $order = $_GET['order'];
} else {
    $order = 'lasttime';
} 

$result = mysqli_query($GLOBALS['conn'], "select count(*) from luo2888_users where status=-1 or status=-999 or status=0");
if ($row = mysqli_fetch_array($result)) {
    $userCount = $row[0];
    $pageCount = ceil($row[0] / $recCounts);
} else {
    $userCount = 0;
    $pageCount = 1;
} 
unset($row);
mysqli_free_result($result);

if (isset($_POST['jumpto'])) {
    $p = $_POST['jumpto'];
    if (($p <= $pageCount) && ($p > 0)) {
        header("location:?page=$p&order=$order");
    } 
} 

$todayTime = strtotime(date("Y-m-d"), time());
$result = mysqli_query($GLOBALS['conn'], "select count(*) from luo2888_users where status=-1 or status=-999 and lasttime>$todayTime");
if ($row = mysqli_fetch_array($result)) {
    $todayuserCount = $row[0];
} else {
    $todayuserCount = 0;
}
unset($row);
mysqli_free_result($result);

$result = mysqli_query($GLOBALS['conn'], "select count(*) from luo2888_users where status>0 and authortime>$todayTime");
if ($row = mysqli_fetch_array($result)) {
    $todayauthoruserCount = $row[0];
} else {
    $todayauthoruserCount = 0;
} 
unset($row);
mysqli_free_result($result);

?>