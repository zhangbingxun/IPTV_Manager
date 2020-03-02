<?php
include_once "../config.php";

session_start();
if ($_SESSION['user'] != 'admin')exit();

$r = rand(1, 9999999);
$k = md5($r);
mysqli_query($GLOBALS['conn'], "UPDATE luo2888_appdata set randkey='$k'");

mysqli_close($GLOBALS['conn']);
echo '更新成功！';

?>