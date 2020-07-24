<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);

if ($user != 'admin') {
    exit("<script>$.alert({title: '警告',content: '你无权访问此页面。',type: 'orange',buttons: {confirm: {text: '确定',btnClass: 'btn-primary',action: function(){history.go(-1);}}}});</script>");
} 

?>

<?php

if (isset($_POST['clearuser'])) {
    $name = $_POST['name'];
    $db->mSet("luo2888_users", "vpn=0", "where name=$name");
    echo"<script>lightyear.notify('用户$name 记录已清空！', 'success', 3000);</script>";
} 

if (isset($_POST['clearvpn'])) {
    $db->mSet("luo2888_users", "vpn=0");
    echo"<script>lightyear.notify('抓包记录已清空！', 'success', 3000);</script>";
} 

if (isset($_POST['stopuse'])) {
    $name = $_POST['name'];
    $now = time();
    $result = $db->mSet("luo2888_users", "status=0", "where name=$name");
} 

?>