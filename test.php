<!DOCTYPE html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
<title>直播鉴权系统</title>
</head>

<body>
<div style="text-align: center;">
<h2>直播鉴权</h2>
<form method="post">
推流名：<input type="text" name="stream" />
<br>
<br>
验证码：<input type="text" name="vcode" />
<br>
<br>
<button type="submit" name="submit">获取地址</button>
</form>
</div>

<?php
if (isset($_POST['stream']) && $_POST['vcode'] == '12345') {
$AppName = 'playzhan'; // 应用名
$StreamName = $_POST['stream']; //流名
$push_cdn = 'push.paxy365.com'; //推流域名
$pull_cdn = 'pull.paxy365.com'; //播流域名
$pull_key = 'oCudfghYGM'; //播流 主key
$push_key = '0hgggfjAhT';  //推流 主key

/* 有效时间 */
$time = time() + 1800;

/* 推流地址 */
$strpush = "/{$AppName}/{$StreamName}-{$time}-0-0-{$push_key}";
$pushurl = "rtmp://{$push_cdn}/{$AppName}/{$StreamName}?auth_key={$time}-0-0-".md5($strpush);

/* 播流地址 */
$strviewrtmp = "/{$AppName}/{$StreamName}-{$time}-0-0-{$pull_key}";
$strviewflv = "/{$AppName}/{$StreamName}.flv-{$time}-0-0-{$pull_key}";
$strviewm3u8 = "/{$AppName}/{$StreamName}.m3u8-{$time}-0-0-{$pull_key}";

$rtmpurl = "rtmp://{$pull_cdn}/{$AppName}/{$StreamName}?auth_key={$time}-0-0-".md5($strviewrtmp);    //播流地址
$flvurl = "https://{$pull_cdn}/{$AppName}/{$StreamName}.flv?auth_key={$time}-0-0-".md5($strviewflv);     //播流地址
$m3u8url = "https://{$pull_cdn}/{$AppName}/{$StreamName}.m3u8?auth_key={$time}-0-0-".md5($strviewm3u8); //播流地址


/* 鉴权签名后的推流地址 */
echo '<p>推流地址：<br>'.$pushurl.'</p>';

/* 鉴权后的播放地址 */
echo '<p>rtmp拉流地址：<br>'.$rtmpurl.'</p>';
echo '<p>http-flv拉流地址：<br>'.$flvurl.'</p>';
echo '<p>http-hls拉流地址：<br>'.$m3u8url.'</p>';

/* 播流lhd地址 */
$rtmpurl_lhd = "rtmp://{$pull_cdn}/{$AppName}/{$StreamName}_lhd?auth_key={$time}-0-0-".md5($strviewrtmp);    //播流地址
$flvurl_lhd = "https://{$pull_cdn}/{$AppName}/{$StreamName}_lhd.flv?auth_key={$time}-0-0-".md5($strviewflv);     //播流地址
$m3u8url_lhd = "https://{$pull_cdn}/{$AppName}/{$StreamName}_lhd.m3u8?auth_key={$time}-0-0-".md5($strviewm3u8); //播流地址

/* 鉴权后的播放lhd地址 */
echo '<p>rtmp拉流lhd地址：<br>'.$rtmpurl_lhd.'</p>';
echo '<p>http-flv拉流lhd地址：<br>'.$flvurl_lhd.'</p>';
echo '<p>http-hls拉流lhd地址：<br>'.$m3u8url_lhd.'</p>';

}

?>

</body>
</html>