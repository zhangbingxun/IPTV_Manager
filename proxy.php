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

	if ($vid=='iptv805') {
		$id=$_GET['id'];
		$tid=$_GET['tid'];
		if (!empty($_GET['p'])){$part='&p=' . $_GET['p'];}
		$url="http://m.iptv805.com/iptv.php?act=play&tid=$tid&id=$id";
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_TIMEOUT, 5);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; Android 8.0.0; Pixel 2 XL Build/OPD1.170816.004) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.132 Mobile Safari/537.36');
		$curlobj = curl_exec($curl);
		curl_close($curl);
		preg_match('/<option value="(.*?)"/i',$curlobj,$sn);
		$linkurl=$sn[1] . $part;
		$linkurl = preg_replace('#http://m.iptv.com/player.m3u8#', 'http://play.ggiptv.com:13164/play.m3u8', $linkurl);
		if (strstr($linkurl, "ggiptv") != false)){
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $linkurl);
			curl_setopt($curl, CURLOPT_NOBODY, 1);
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
			curl_exec($curl);
			$playurl = curl_getinfo($curl,CURLINFO_EFFECTIVE_URL);
			if (isset($_GET['flv'])) {
		    	$playurl = preg_replace('#/playlist.m3u8#', '.flv', $playurl);
		    	$playurl = preg_replace('#.m3u8#', '.flv', $playurl);
			}
			header('location:'.$playurl);
		} else {
			header('location:'.$linkurl);
		}
		exit;
	}

} else {
	header('HTTP/1.1 403 Forbidden');
	exit();
}

?>
