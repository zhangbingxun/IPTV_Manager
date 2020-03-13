<?php
session_start();
require_once "config.php";
$db = Config::GetIntance();

$getkey =  $db->mGet("luo2888_config","value","where name='secret_key'");
if (empty($getkey)) {
        $_SESSION['secret_key_status'] = '1';}
        
if (isset($_POST['secret_key_enter'])) {
    $secret_key = $_POST['secret_key'];
    $secret_key = $db->mEscape_string($_POST['secret_key']);
    $secret_key = md5($secret_key);
    if ($secret_key == $getkey) {
        if (isset($_POST['remembersecret_key'])) {
            setcookie("secret_key", $getkey, time() + 3600 * 24 * 7);
            setcookie("remembersecret_key", "1", time() + 3600 * 24 * 7);
        } else {
            setcookie("remembersecret_key", "1", time()-3600);
        } 
        $_SESSION['secret_key_status'] = '1';
        echo '<script language=JavaScript>location.replace(location.href);</script>';
    } else {
        echo "<script>alert('安全码错误！');</script>";
    }
} 

if (isset($_COOKIE['remembersecret_key'])) {
    $secret_key = $db->mEscape_string($_COOKIE['secret_key']);
    if ($secret_key == $getkey) {
        $_SESSION['secret_key_status'] = '1';
    } 
} 

?>