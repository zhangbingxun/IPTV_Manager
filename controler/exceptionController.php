<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);

if ($_SESSION['ipcheck'] == 0) {
    echo"<script>alert('你无权访问此页面！');history.go(-1);</script>";
    exit();
} 

?>

<?php
if (isset($_POST['clearvpn'])) {
    $db->mSet("luo2888_users", "vpn=0");
    echo"<script>lightyear.notify('抓包记录已清空！', 'success', 3000);</script>";
} 

if (isset($_POST['clearidchange'])) {
    $db->mSet("luo2888_users", "idchange=0");
    echo"<script>lightyear.notify('设备ID更换记录已清空！', 'success', 3000);</script>";
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

if (isset($_POST['submitvpntimes'])) {
    $vpntimes = $_POST['vpntimes'];
    $db->mSet("luo2888_config", "value='$vpntimes'", "where name='vpntimes'");
    echo"<script>lightyear.notify('保存成功！', 'success', 3000);</script>";
} 

if (isset($_POST['submitsameip_user'])) {
    $sameip_user = $_POST['sameip_user'];
    $db->mSet("luo2888_config", "value='$sameip_user'", "where name='max_sameip_user'");
    echo"<script>lightyear.notify('保存成功！', 'success', 3000);</script>";
} 

// 获取允许抓包最大次数和允许登陆IP最大数量
$max_sameip_user = $db->mGet("luo2888_config", "value", "where name='max_sameip_user'");
$vpntimes = $db->mGet("luo2888_config", "value", "where name='vpntimes'");

?>