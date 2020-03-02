<?php
header("Content-type: text/html; charset=utf-8");

define("PANEL_MD5_KEY","tvkey_");  //面板密码MD5加密秘钥

$db_host="localhost";  //数据库服务器
$db_user="";  //数据库帐号
$db_pwd="";   //数据库密码
$db_database="";  //数据库名称
$conn=mysqli_connect($db_host,$db_user,$db_pwd,$db_database) OR die ('无法登录MYSQL服务器！');  //连接数据库对象

global $conn;
mysqli_query($GLOBALS['conn'],"SET NAMES 'UTF8'");

function get_config($name){
	$result=mysqli_query($GLOBALS['conn'],"SELECT value from luo2888_config where name='$name'");
	if($row=mysqli_fetch_array($result)){$return=$row['value'];}
	unset($row);
	mysqli_free_result($result);
	return $return;
}

function set_config($name,$value){
	mysqli_query($GLOBALS['conn'],"UPDATE luo2888_config set value='$value' where name='$name'");
}
?>