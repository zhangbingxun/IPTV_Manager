<?php
header("Content-type: text/json; charset=utf-8");
header("Cache-Control:no-cache,must-revalidate");
header("Pragma: no-cache");
include_once "config.php";

if ($_GET['ip']=='myip'){$ip=real_ip();}else{$ip=$_GET['ip'];}
$myurl=dirname('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
$ip_chk=get_config('ip_chk');

if($ip_chk=='1'){
	echo file_get_contents("$myurl/apps/iploc/qqzeng.php?ip=$ip");
}else if($ip_chk=='2'){
	echo file_get_contents("$myurl/apps/iploc/taobao.php?ip=$ip");
}

function real_ip(){
	$real_ip = $_SERVER['REMOTE_ADDR'];
	if (isset($_SERVER['HTTP_X_REAL_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_X_REAL_IP'])) {
		$real_ip = $_SERVER['HTTP_X_REAL_IP'];
	} elseif (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {
		$real_ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR']) AND preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
		foreach ($matches[0] AS $xip) {
			if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {
				$real_ip = $xip;
				break;
			}
		}
	}
	return $real_ip;
}
?>
