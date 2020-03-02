<?php
error_reporting(0);
header('Content-Type: text/json;charset=UTF-8');

if (isset($_GET['vid'])) {
	$vid=$_GET['vid'];
	if ($vid=='migu') {
		$id=$_GET['id'];
		if(isset($id) && !empty($id)){
			if(strlen($id)<9){
				header('HTTP/1.1 403 Forbidden');
			    exit("ID Length Less than 9 your id is $id.");
			}
			
			$info=file_get_contents("http://www.miguvideo.com/gateway/playurl/v1/play/playurl?contId=$id&rateType=4");
			preg_match('/"url":"(.*?)"/i',$info,$sn);
			$playurl=$sn[1];
			$url=str_replace(array("\u002F"),'/',$playurl);
		    header('location:'.urldecode($playurl));
			exit;
		}
	}

	if ($vid=='inews') {
		$url="http://news.tvb.com/live/inews?is_hd";
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
	
	if ($vid=='j5') {
		$url="http://news.tvb.com/live/j5_ch85?is_hd";
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
	
} else {
	header('HTTP/1.1 403 Forbidden');
	exit();
}

?>