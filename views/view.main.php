<?php
$myapi = file_get_contents('http://v1.alapi.cn/api/mingyan');
$mingyan = json_decode($myapi,true);
$mingyan_contents = $mingyan['data']['content'];
$mingyan_author = $mingyan['data']['author']; ?>
<!DOCTYPE html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
<title>欢迎访问IPTV管理系统</title>
<link rel="icon" href="views/images/favicon.ico" type="image/ico">
<meta name="keywords" content="小肥米,后台管理系统" />
<meta name="description" content="小肥米IPTV后台管理系统" />
<meta name="author" content="luo2888" />
<meta name="renderer" content="webkit" />
<link href="/views/css/bootstrap.min.css" rel="stylesheet">
<link href="/views/css/materialdesignicons.min.css" rel="stylesheet">
<link href="/views/css/style.min.css" rel="stylesheet">
<link href="/views/css/login.css" rel="stylesheet" >
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
