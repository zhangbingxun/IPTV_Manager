<ul class="nav nav-drawer">
    <li class="nav-item <?php echo $index ?>"> <a href="index.php"><i class="mdi mdi-home"></i>首页</a> </li>
    <li class="nav-item <?php echo $agentadmin ?>"> <a href="agentadmin.php"><i class="mdi mdi-account-key"></i>代理商</a> </li>
    <li class="nav-item <?php echo $serialadmin ?>"> <a href="serialadmin.php"><i class="mdi mdi-account-key"></i>账号</a> </li>
    <li class="nav-item <?php echo $author ?>"> <a href="author.php"><i class="mdi mdi-account-check"></i>授权</a> </li>
    <li class="nav-item <?php echo $useradmin ?>"> <a href="useradmin.php"><i class="mdi mdi-account"></i>用户</a> </li>
    <li class="nav-item <?php echo $exception ?>"> <a href="exception.php"><i class="mdi mdi-account-alert"></i>异常</a> </li>
    <li class="nav-item <?php echo $mealsadmin ?>"> <a href="mealsadmin.php"><i class="mdi mdi-shopping"></i>套餐</a></li>
    <li class="nav-item <?php echo $ordersadmin ?>"> <a href="ordersadmin.php"><i class="mdi mdi-wallet-giftcard"></i>订单</a></li>
    <li class="nav-item <?php echo $epgadmin ?>"> <a href="epgadmin.php"><i class="mdi mdi-television-guide"></i>EPG</a> </li>
    <li class="nav-item <?php echo $vodsadmin ?>"> <a href="vodsadmin.php"><i class="mdi mdi-television-guide"></i>点播</a> </li>
    <li class="nav-item nav-item-has-subnav channeladmin">
        <a href="javascript:void(0)"><i class="mdi mdi-television-classic"></i>频道列表</a>
        <ul class="nav nav-subnav">
            <li class="<?php echo $web ?>"><a href="channeladmin.php?type=web">网页端频道</a></li>
            <li class="<?php echo $default ?>"><a href="channeladmin.php?type=default">默认频道</a></li>
            <li class="<?php echo $province ?>"><a href="channeladmin.php?type=province">省份频道</a></li>
            <li class="<?php echo $vip ?>"><a href="channeladmin.php?type=vip">会员频道</a></li>
        </ul>
    </li>
    <li class="nav-item nav-item-has-subnav sysadmin">
        <a href="javascript:void(0)"><i class="mdi mdi-settings-box"></i>系统设置</a>
        <ul class="nav nav-subnav">
            <li class="<?php echo $index0 ?>"><a href="sysadmin.php?index=0">系统公告</a></li>
            <li class="<?php echo $index1 ?>"><a href="sysadmin.php?index=1">背景图片</a></li>
            <li class="<?php echo $index2 ?>"><a href="sysadmin.php?index=2">后台记录</a></li>        
            <li class="<?php echo $index3 ?>"><a href="sysadmin.php?index=3">后台设置</a></li>
            <li class="<?php echo $index4 ?>"><a href="sysadmin.php?index=4">客户端设置</a></li>
            <li class="<?php echo $index5 ?>"><a href="sysadmin.php?index=5">网页端设置</a></li>
        </ul>
    </li>
</ul>