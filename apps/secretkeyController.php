<?php
session_start();
include_once "config.php";
if (isset($_POST['secret_key_enter'])) {
    $secret_key = $_POST['secret_key'];
    $secret_key = mysqli_real_escape_string($GLOBALS['conn'], $_POST['secret_key']);
    $secret_key = md5($secret_key);
    $get_key = get_config('secret_key');
    if (empty($get_key)) {
        $_SESSION['secret_key_status'] = '1';
        echo '<script language=JavaScript>location.replace(location.href);</script>';
    } else {
        if ($secret_key == $get_key) {
            $_SESSION['secret_key_status'] = '1';
            if (isset($_POST['remembersecret_key'])) {
                setcookie("secret_key", $get_key, time() + 3600 * 24 * 7);
                setcookie("remembersecret_key", "1", time() + 3600 * 24 * 7);
            } else {
                setcookie("remembersecret_key", "1", time()-3600);
            } 
            echo '<script language=JavaScript>location.replace(location.href);</script>';
        } else {
            echo "<script>alert('安全码错误！');</script>";
        } 
    } 
} 

if (isset($_COOKIE['remembersecret_key'])) {
    $secret_key = mysqli_real_escape_string($GLOBALS['conn'], $_COOKIE['secret_key']);
    $get_key = get_config('secret_key');
    if ($secret_key == $get_key) {
        $_SESSION['secret_key_status'] = '1';
            echo '<script language=JavaScript>location.replace(location.href);</script>';
    } 
} 

?>