<?php
error_reporting(0);
header('Content-Type: text/json;charset=UTF-8');
require_once "config.php";
$db = Config::getIntance();

if (isset($_GET['vid'])&&isset($_GET['time'])&&isset($_GET['token'])) {
	$nowtime=time();
	$vid=$_GET['vid'];
	$time=$_GET['time'];
	$token=$_GET['token'];
	$key=$db->mGet("luo2888_config", "value", "where name='keyproxy'");
	$app_sign=$db->mGet("luo2888_config", "value", "where name='app_sign'");
	if(abs($nowtime-$time)>600){
		header('HTTP/1.1 403 Forbidden');
		exit();
	} else if ($token!=md5($key.$time."303543214".$app_sign)) {
		header('HTTP/1.1 403 Forbidden');
		exit();
	}

	if ($vid=='tvb') {
		$id=$_GET['id'];
		$url="http://news.tvb.com/live/$id?is_hd";
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_TIMEOUT, 5);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; Android 8.0.0; Pixel 2 XL Build/OPD1.170816.004) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Mobile Safari/537.36');
		$curlobj = curl_exec($curl);
		preg_match('/<source src="(.*?)"/i',$curlobj,$sn);
		$playurl=$sn[1];
	    header('location:'.urldecode($playurl));
		exit;
	}

	if ($vid=='migu') {
		$id=$_GET['id'];
		$info=file_get_contents("http://webapi.miguvideo.com/gateway/playurl/v3/play/playurl?contId=$id&rateType=4");
		preg_match('/"url":"(.*?)"/i',$info,$sn);
		$playurl=$sn[1];
		$url=str_replace(array("\u002F"),'/',$playurl);
		header('location:'.urldecode($playurl));
		exit;
	}

	if ($vid=='utvhk') {
		$id=$_GET['id'];
		$info=file_get_contents("http://miguapi.utvhk.com:18083/clt/publish/resource/UTV_NEW/playData.jsp?contentId=$id&nodeId=$id&rate=5&playerType=4&objType=LIVE");
		preg_match('/"url": "(.*?)"/i',$info,$sn);
		$playurl=$sn[1];
		$url=str_replace(array("\u002F"),'/',$playurl);
		header('location:'.urldecode($playurl));
		exit;
	}
	
	if ($vid=='iptv234') {
		$id=$_GET['id'];
		$tid=$_GET['tid'];
		if (!empty($_GET['p'])){$part='&p=' . $_GET['p'];}
		$url="http://app.iptv234.com/iptv.php?act=play&tid=$tid&id=$id";
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_TIMEOUT, 5);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; Android 10; ELE-AL00 Build/HUAWEIELE-AL00; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/66.0.3359.126 MQQBrowser/6.2 TBS/045130 Mobile Safari/537.36 MicroMessengeriptv VideoPlayer god/3.0.0 Html5Plus/1.0 (Immersed/29.411766)');
		$curlobj = curl_exec($curl);
		curl_close($curl);
		if (strstr($curlobj, "src1") != false){
		    preg_match('/var src1 = "(.*?)"/i',$curlobj,$sn);
		} else {
		    preg_match('/<option value="(.*?)"/i',$curlobj,$sn);
		}
		$linkurl=$sn[1] . $part;
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $linkurl);
		curl_setopt($curl, CURLOPT_NOBODY, 1);
		curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_USERAGENT, 'Lavf/57.83.100');
		curl_exec($curl);
		$playurl = curl_getinfo($curl,CURLINFO_EFFECTIVE_URL);
		header('location:'.$playurl);
		exit;
	}

	if ($vid=='sop2') {
		$sig=12315;
		$key=12315;
		$time=time();
		$sign = md5($key.$time."303543214".$sig);
		header('location:'.'http://zhu2.crtv.zstv.top/zszb/api/atv_119.php?id=44ca88e2d2844038cdb169e25ac5fbbf' . '&t=' . $time . '&sign=' . $sign);
		exit;
	}
	
} else {
	header('HTTP/1.1 403 Forbidden');
	exit();
}

?>
