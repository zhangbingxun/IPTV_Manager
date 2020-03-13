<?php
require_once "../config.php";
$db = new Config();

session_start();
if ($_SESSION['user'] != 'admin')exit();

$rand = rand(1, 9999999);
$key = md5($rand);
$db->mSet("luo2888_appdata", "randkey='$key'");
$db->mClose();
echo '更新成功！';

?>