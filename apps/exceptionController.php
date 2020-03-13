<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);

require_once "../config.php";
$db = Config::GetIntance();

?>

<?php
if ($_SESSION['ipcheck'] == 0) {
    echo"<script>alert('你无权访问此页面！');history.go(-1);</script>";
    exit();
} 

if (isset($_POST['clearvpn'])) {
    $db->mSet("luo2888_users", "vpn=0");
    exit('<script>javascript:self.location=document.referrer;alert("抓包记录已清空")</script>');
} 

if (isset($_POST['stopuse'])) {
    $name = $_POST['name'];
    $now = time();
    $result = $db->mSet("luo2888_users", "status=0", "where name=$name");
} 

if (isset($_POST['startuse'])) {
    $name = $_POST['name'];
    $result = $db->mSet("luo2888_users", "status=1", "where name=$name and status=0");
} 

if (isset($_POST['submitmodifyipcount'])) {
    $ipcount = $_POST['ipcount'];
    $db->mSet("luo2888_appdata", "ipcount=$ipcount");
} 

if (isset($_POST['submitclearold'])) {
    $oldtime = strtotime(date("Y-m-d"), time());
    $db->mDel("luo2888_loginrec", "where logintime<$oldtime");
} 

if (isset($_POST['submitclearall'])) {
    $db->mDel("luo2888_loginrec");
} 

if (isset($_POST['submitsameip_user'])) {
    $sameip_user = $_POST['sameip_user'];
    $db->mSet("luo2888_config", "value='$sameip_user'", "where name='max_sameip_user'");
    echo('<script>javascript:self.location=document.referrer;alert("保存成功！")</script>');
} 
// 获取每日允许登陆IP数量
if ($row = $db->mGetRow("luo2888_appdata", "ipcount")) {
    $ipcount = $row['ipcount'];
} else {
    $ipcount = 5;
} 

?>