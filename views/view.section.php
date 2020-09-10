<?php
require_once "../../config.php";
require_once "../../controler/usercheck.php";

$current_uri = $_SERVER['REQUEST_URI'];

if (strpos($current_uri,'index.php') !== false){
    $index='active';
} else if (strpos($current_uri,'agentadmin') !== false){
    $agentadmin='active';
} else if (strpos($current_uri,'serialadmin') !== false){
    $serialadmin='active';
} else if (strpos($current_uri,'author') !== false){
    $author='active';
} else if (strpos($current_uri,'useradmin') !== false){
    $useradmin='active';
} else if (strpos($current_uri,'exception') !== false){
    $exception='active';
} else if (strpos($current_uri,'mealsadmin') !== false){
    $mealsadmin='active';
} else if (strpos($current_uri,'epg') !== false){
    $epgadmin='active';
} else if (strpos($current_uri,'vodsadmin') !== false){
    $vodsadmin='active';
} else if (strpos($current_uri,'ordersadmin') !== false){
    $ordersadmin='active';
} else if (strpos($current_uri,'sysadmin') !== false){
    $sysadmin='active';
} else if (strpos($current_uri,'type=web') !== false){
    $web='active';
} else if (strpos($current_uri,'type=default') !== false){
    $default='active';
} else if (strpos($current_uri,'type=province') !== false){
    $province='active';
} else if (strpos($current_uri,'type=vip') !== false){
    $vip='active';
} else if (strpos($current_uri,'index=0') !== false){
    $index0='active';
} else if (strpos($current_uri,'index=1') !== false){
    $index1='active';
} else if (strpos($current_uri,'index=2') !== false){
    $index2='active';
} else if (strpos($current_uri,'index=3') !== false){
    $index3='active';
} else if (strpos($current_uri,'index=4') !== false){
    $index4='active';
} else if (strpos($current_uri,'index=5') !== false){
    $index5='active';
}
?>

<!DOCTYPE html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=0.92, maximum-scale=1, user-scalable=no" />
<meta name="keywords" content="IPTV,后台管理系统" />
<meta name="description" content="IPTV后台管理系统" />
<meta name="author" content="luo2888" />
<meta name="renderer" content="webkit" />
<title>小肥米TV管理系统</title>
<link rel="icon" href="/views/images/favicon.ico" type="image/ico">
<link href="/views/css/bootstrap.min.css" rel="stylesheet">
<link href="/views/css/materialdesignicons.min.css" rel="stylesheet">
<link href="/views/css/style.min.css" rel="stylesheet">
<link href="/views/css/animate.css" rel="stylesheet">
<link rel="stylesheet" href="/views/js/jconfirm/jquery-confirm.min.css">
</head>

<script type="text/javascript" src="/views/js/jquery.min.js"></script>
<script type="text/javascript" src="/views/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/views/js/perfect-scrollbar.min.js"></script>
<script type="text/javascript" src="/views/js/main.min.js"></script>
<script type="text/javascript" src="/views/js/lightyear.js"></script>
<script src="/views/js/jconfirm/jquery-confirm.min.js"></script>
<script src="/views/js/bootstrap-notify.min.js"></script>

<body>
<div class="lyear-layout-web">
    <div class="lyear-layout-container">
        <!--左侧导航-->
        <aside class="lyear-layout-sidebar">
            <!-- logo -->
            <div id="logo" class="sidebar-header">
                <a href="index.php"><img src="/views/images/logo-sidebar.png"/></a>
            </div>
            <div class="lyear-layout-sidebar-scroll"> 
                <nav class="sidebar-main">
                    <?php
                        if ($user == $admin) {
                            require_once("../view.adminnav.php");
                        } else {
                            require_once("../view.agentnav.php");
                        }
                    ?>
                </nav>
                
                <div class="sidebar-footer">
                    <p align="center"><?php echo date("Y-m-d",time()); ?></p>
                    <p align="center">客户讨论QQ群：<a target="_blank" href="https://jq.qq.com/?_wv=1027&k=55jH1sG">807073464</a></p>
                    <p class="copyright">Copyright &copy; 2020. <a target="_blank" href="http://www.luo2888.cn">luo2888</a> All rights reserved. </p>
                </div>
            </div>
        </aside>
        <!--End 左侧导航-->
        
        <!--头部信息-->
        <header class="lyear-layout-header">
            <nav class="navbar navbar-default">
                <div class="topbar">
                    <div class="topbar-left">
                        <div class="lyear-aside-toggler">
                            <span class="lyear-toggler-bar"></span>
                            <span class="lyear-toggler-bar"></span>
                            <span class="lyear-toggler-bar"></span>
                        </div>
                        <span class="navbar-page-title">
                        <?php
                        if (strpos($current_uri,'index.php') !== false){ echo '&nbsp;首页&nbsp;'; }
                        else if (strpos($current_uri,'author') !== false){ echo '&nbsp;授权&nbsp;'; }
                        else if (strpos($current_uri,'agentadmin') !== false){ echo '&nbsp;代理商&nbsp;'; }
                        else if (strpos($current_uri,'serialadmin') !== false){ echo '&nbsp;账号&nbsp;'; }
                        else if (strpos($current_uri,'useradmin') !== false){ echo '&nbsp;用户&nbsp;'; }
                        else if (strpos($current_uri,'exception') !== false){ echo '&nbsp;异常&nbsp;'; }
                        else if (strpos($current_uri,'mealsadmin') !== false){ echo '&nbsp;套餐&nbsp;'; }
                        else if (strpos($current_uri,'ordersadmin') !== false){ echo '&nbsp;订单&nbsp;'; }
                        else if (strpos($current_uri,'epg') !== false){ echo '&nbsp;EPG&nbsp;'; }
                        else if (strpos($current_uri,'vodsadmin') !== false){ echo '&nbsp;点播采集&nbsp;'; }
                        else if (strpos($current_uri,'channeladmin') !== false){ echo '&nbsp;频道列表&nbsp;'; }
                        else if (strpos($current_uri,'sysadmin') !== false){ echo '&nbsp;系统设置&nbsp;'; }
                        ?>
                        </span>
                    </div>
                    <ul class="topbar-right">
                        <li class="dropdown dropdown-profile">
                            <a href="javascript:void(0)" data-toggle="dropdown">
                                <span style="font-size: 15px;padding-right: 10px;"><?php echo $user ?></span>
                                <img class="img-avatar img-avatar-32 m-r-5" src="/views/images/users/avatar.png" alt="user" />
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li> <a href="../../controler/logout.php"><i class="mdi mdi-logout-variant"></i> 退出登录</a> </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!--End 头部信息-->