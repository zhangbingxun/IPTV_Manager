<?php
session_start();
$db = Config::getIntance();

if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
} else {
    header("location:../../admin.php");
    exit;
} 

$admin = $db->mGet("luo2888_config","value","where name='adminname'");
if ($user == $admin) {
    $pass = $db->mGet("luo2888_config","value","where name='adminpass'");
} else if ($row = $db->mGetRow("luo2888_agents","pass","where id='$user'")) {
    $pass = $row['pass'];
} else {
    header("location:../../admin.php");
    exit;
} 

if (!isset($_SESSION['pass']) || $_SESSION['pass'] != $pass) {
    header("location:../../admin.php");
    exit;
} 

?>
