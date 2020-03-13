<?php
header("Content-type: text/json; charset=utf-8");
header("Cache-Control:no-cache,must-revalidate");
header("Pragma: no-cache");
require_once "config.php";
$GetIP = new GetIP();
$db = Config::GetIntance();

if ($_GET['ip']=='myip'){$ip=$GetIP->getuserip();}else{$ip=$_GET['ip'];}
$myurl=dirname('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);

$ip_chk = $db->mGet("luo2888_config", "value", "where name='ip_chk'");
if($ip_chk=='1'){
	echo file_get_contents("$myurl/apps/iploc/qqzeng.php?ip=$ip");
}else if($ip_chk=='2'){
	echo file_get_contents("$myurl/apps/iploc/taobao.php?ip=$ip");
}

?>
