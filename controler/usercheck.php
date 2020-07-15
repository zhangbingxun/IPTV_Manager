<?php
session_start();
$db = Config::getIntance();

if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
} else {
    header("location:../admin.php");
    exit;
} 

if ($user == 'admin') {
    $psw = $db->mGet("luo2888_config","value","where name='adminpass'");
} else {
    header("location:../admin.php");
    exit;
} 

if (!isset($_SESSION['psw']) || $_SESSION['psw'] != $psw) {
    header("location:../admin.php");
    exit;
} 

?>
