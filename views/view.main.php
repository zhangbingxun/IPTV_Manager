<?php
require_once "config.php";

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, 'https://api.xygeng.cn/one');
curl_setopt($curl, CURLOPT_TIMEOUT, 2);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_USERAGENT, 'FMITV');
$myapi = curl_exec($curl);
$mingyan = json_decode($myapi,true);
if (!empty($mingyan['data'])) {
    $arrlength = count($mingyan['data']);
}
if ($arrlength > 1) {
    $mingyan_contents = $mingyan['data']['content'];
    $mingyan_author = $mingyan['data']['origin'];
} else {
    $mingyan_contents = '欢迎';
    $mingyan_author = '小肥米电子科技工作室';
} 
$db = Config::GetIntance();
$appname = $db->mGet("luo2888_config", "value", "where name='app_appname'");
$web_title = $db->mGet("luo2888_config", "value", "where name='web_title'");
$web_copyright = $db->mGet("luo2888_config", "value", "where name='web_copyright'");
?>
<!DOCTYPE html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
<title><?php echo $appname; ?> - <?php echo $web_title; ?></title>
<link rel="icon" href="views/images/favicon.ico" type="image/ico">
<meta name="keywords" content="小肥米,后台管理系统" />
<meta name="description" content="小肥米IPTV后台管理系统" />
<meta name="author" content="luo2888" />
<meta name="renderer" content="webkit" />
<link href="/views/css/bootstrap.min.css" rel="stylesheet">
<link href="/views/css/materialdesignicons.min.css" rel="stylesheet">
<link href="/views/css/style.min.css" rel="stylesheet">
<link href="/views/css/login.css?t=<?php echo time(); ?>" rel="stylesheet" >
<link href="/views/js/jconfirm/jquery-confirm.min.css" rel="stylesheet">
<script type="text/javascript" src="/views/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/views/js/perfect-scrollbar.min.js"></script>
<script type="text/javascript" src="/views/js/lightyear.js"></script>
<script type="text/javascript" src="/views/js/main.min.js"></script>
<script type="text/javascript" src="/views/js/jquery.min.js"></script>
<script src="/views/js/jconfirm/jquery-confirm.min.js"></script>
<script src="/views/js/bootstrap-notify.min.js"></script>
<script src="/views/js/login.js"></script>
</head>
