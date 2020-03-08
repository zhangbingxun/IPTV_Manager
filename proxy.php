<?php
error_reporting(0);
header('Content-Type: text/json;charset=UTF-8');
if (isset($_GET['vid'])) {
	$vid=$_GET['vid'];

	if ($vid=='tvb') {
		$id=$_GET['id'];
		$url="http://news.tvb.com/live/$id?is_hd";
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_TIMEOUT, 5);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
		$curlobj = curl_exec($curl);
		
		preg_match('/<source src="(.*?)"/i',$curlobj,$sn);
		$playurl=$sn[1];
	    header('location:'.urldecode($playurl));
		exit;
	}

	if ($vid=='migu') {
		$id=$_GET['id'];
		$info=file_get_contents("http://www.miguvideo.com/gateway/playurl/v1/play/playurl?contId=$id&rateType=4");
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

} else {
	header('HTTP/1.1 403 Forbidden');
	exit();
}

?>