<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);

require_once "api/common/cacher.class.php";
require_once "config.php";
$db = Config::GetIntance();
$nowtime = time();

$appver = $db->mGet("luo2888_config", "value", "where name='appver'");
$boxver = $db->mGet("luo2888_config", "value", "where name='appver_sdk14'");

$appurl = $db->mGet("luo2888_config", "value", "where name='appurl'");
$boxurl = $db->mGet("luo2888_config", "value", "where name='appurl_sdk14'");

// 缓存数据
function cache($key, $f_name, $ff = []) {
    Cache::$cache_path = "./cache/tvapi/";
    $val = Cache::gets($key);
    if (!$val) {
        $data = call_user_func_array($f_name, $ff);
        Cache::put($key, $data);
        return $data;
    } else {
        return $val;
    } 
} 

// 缓存超时
function cache_time_out() {
    date_default_timezone_set("Asia/Shanghai");
    return time();
}

// 蓝奏云解析
function downurl($url) {

    if (strstr($url,"lanzou://")) {

        $lzurl = str_replace('lanzou:', 'https:', $url);
        $directlink = lanzouUrl($lzurl);
        if (!$directlink) {
            return $lzurl;
        } else {
            return $directlink;
        }

    } else {

        return $url;

    }

}

$timetoken = cache("time_out_chk", "cache_time_out");
if (abs($nowtime - $timetoken) > 120) {
    Cache::$cache_path = "./cache/tvapi/"; 
    Cache::dels();
    cache("time_out_chk", "cache_time_out");
} 

$appurl = cache("appdown" . $appurl, "downurl", [$appurl]);
$boxurl = cache("boxdown" . $boxurl, "downurl", [$boxurl]);
?>

<!DOCTYPE HTML>
<html>
	<head>
		<title>肥米TV - 電視直播</title>
		<meta charset="utf-8" />
		<meta name="keywords" content="肥米TV" />
		<meta name="description" content="肥米TV，是一款優秀的OTT移動電視直播平台，除電視直播外，還有精彩的電影電視劇輪播、點播，給你最佳的娛樂體驗。功能全面增強，操作簡單快捷，隨時隨地觀看電視的同時，還有福利內容不時提供。" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<meta name="author" content="luo2888" />
		<meta name="renderer" content="webkit" />
		<link rel="icon" href="/views/images/favicon.ico" type="image/ico">
		<link rel="stylesheet" href="/views/css/index.css" />
		<noscript><link rel="stylesheet" href="/views/css/noscript.css" /></noscript>
	</head>
	<body class="is-preload">
		<section id="Loading"></section>
			<div id="wrapper">
					<header id="header">
						<div class="logo">
							<img class="icon" style="width: 92%;height: 92%;" src="/views/images/logo.png">
						</div>
						<div class="content">
							<div class="inner">
								<h1>肥米TV - 電視直播</h1>
								<p>優秀的OTT移動電視直播平台，除電視直播外，還有精彩的電影電視劇輪播、點播，給你最佳的娛樂體驗。功能全面增強，操作簡單快捷，隨時隨地觀看電視的同時，還有福利內容不時提供。</p>
							</div>
						</div>
						<nav>
							<ul>
								<li><a href="/zblist.php">線上觀看</a></li>
								<li><a href="#android">軟體下載</a></li>
								<li><a href="#about">關於及免責聲明</a></li>
							</ul>
						</nav>
					</header>

					<div id="main">
							<article id="android">
								<h2 class="major">肥米TV Android版本</h2>
								<p>本站建立運營的電視直播軟件，支持Android 4.4 – Android 10.0的移動電話、平板電腦、AndroidTV電視機安裝使用。</p>
								<span class="image main"><img src="http://blog.luo2888.cn/wp-content/uploads/2020/02/Screenshot_20200213_111339_cn.luo2888.tv_-1024x473.jpg" alt="肥米TV" /></span>
								<p>因中國網絡原因，部分頻道可能無法在國內地區收看。</p>
								<p>因Android系統種類繁多，APP無法百分百保證最佳的兼容性，請測試后再購買。</p>
								<p>加密频道密碼：12345</p>
								<p>下載：</p>
								<p>
									<ul class="actions">
										<li><a class="button primary icon solid fa-download" href="<?php echo $appurl; ?>">手机版（點播+直播） <?php echo 'V' . $appver; ?> 下載</a></li>
									<li><a class="button primary icon solid fa-download"  href="<?php echo $boxurl; ?>">电视/盒子版（直播） <?php echo 'V' . $boxver; ?> 下載</a></li>
									</ul>
								</p>
							</article>

							<article id="about">
								<h2 class="major">關於我們</h2>
								<h3 class="major">微信公眾號</h2>
								<span class="image main"><img src="/views/images/official.jpg" alt="" /></span>
								<h3 class="major">聯繫客服</h2>
								<span class="image main"><img src="/views/images/wechat.jpg" alt="" /></span>
								<h3 class="major">免責聲明</h2>
								<p>軟體僅用於流媒體方案傳輸測試，相關項目開發合作請與我們聯繫，平台所有節目採集於網絡，如有侵犯到您的版權請來信我們將予以取消。</p>
							</article>

							<article id="develop">
								<h2 class="major">正在建設</h2>
								<p>該項目正在建設中，請等待上線 ...</p>
							</article>
					</div>

					<footer id="footer">
						<p class="copyright">&copy; 2017-2020 <a href="https://www.luo2888.cn">luo2888.cn</a>. | All Rights Reserved.</p>
        </footer>

			</div>

			<div id="bg"></div>

			<script src="/views/js/jquery.min.js"></script>
			<script src="/views/js/browser.min.js"></script>
			<script src="/views/js/breakpoints.min.js"></script>
			<script src="/views/js/util.js"></script>
			<script src="/views/js/index.js"></script>
			<script type="text/javascript">
				document.onreadystatechange=function(){
					if(document.readyState=='complete'){
        				$("#Loading").fadeOut();
					}
    		}
			</script>
	</body>
</html>
