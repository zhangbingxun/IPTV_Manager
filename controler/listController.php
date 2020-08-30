<?php
require_once "../config.php";
$db = Config::GetIntance();

session_start();
if (isset($_SESSION['user']))$user = $_SESSION['user'];
$admin = $db->mGet("luo2888_config","value","where name='adminname'");
if ($user == $admin) {
    $pass = $db->mGet("luo2888_config","value","where name='adminpass'");
} else {
    exit("<script>alert('你无权访问该页面，请先登录！');window.location.href='../admin.php';</script>");
} 
if (!isset($_SESSION['pass']) || $_SESSION['pass'] != $pass) {
    exit("<script>alert('数据错误，请检查网站日志！');window.location.href='../admin.php';</script>");
} 

?>

<?php
header("Content-type:text/json;charset=utf-8");

function echoSource($category) {
    global $db;
    $db->mQuery("SET NAMES UTF8");
    $result = $db->mQuery("SELECT distinct id,name,url FROM luo2888_channels where category='$category' order by id");
    while ($row = mysqli_fetch_array($result)) {
        echo $row['name'] . "," . $row['url'] . "\n";
    } 
    unset($row);
    mysqli_free_result($result);
} 

if (isset($_GET['category'])) {
    $category = $_GET['category'];
} else {
    $category = '未知';
} 

echoSource($category);

?>