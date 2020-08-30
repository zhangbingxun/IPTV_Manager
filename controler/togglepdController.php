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
if (isset($_GET['cname'])) {
    $cname = $_GET['cname'];
    if ($row = $db->mGetRow("luo2888_category", "enable", "where name='$cname'")) {
        if ($row['enable'] == 1) {
            $db->mSet("luo2888_category", "enable=0", "where name='$cname'");
            echo "<script>alert('$cname 已禁用');</script>";
        } else {
            $db->mSet("luo2888_category", "enable=1", "where name='$cname'");
            echo "<script>alert('$cname 已启用');</script>";
        } 
    } else {
        echo "<script>alert('$cname 操作失败！');</script>";
    } 
} else {
    echo "<script>alert('参数错误');</script>";
} 

?>