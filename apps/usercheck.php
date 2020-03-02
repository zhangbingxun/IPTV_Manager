<?php
session_start();

if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
} else {
    header("location:../index.php");
    exit();
} 

$result = mysqli_query($GLOBALS['conn'], "select * from luo2888_admin where name='$user'");
if ($row = mysqli_fetch_array($result)) {
    $psw = $row['psw'];
} else {
    $psw = '';
} 

if (!isset($_SESSION['psw']) || $_SESSION['psw'] != $psw) {
    header("location:../index.php");
    exit();
} 

?>
