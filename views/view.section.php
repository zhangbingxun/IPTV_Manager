<?php include_once "../config.php";include_once "../apps/usercheck.php"; ?>
<!DOCTYPE html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
<meta name="keywords" content="IPTV,后台管理系统">
<meta name="description" content="IPTV后台管理系统">
<meta name="author" content="luo2888">
<title>小肥米TV管理系统</title>
<link rel="icon" href="images/favicon.ico" type="image/ico">
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/materialdesignicons.min.css" rel="stylesheet">
<link href="css/style.min.css" rel="stylesheet">
</head>

<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/perfect-scrollbar.min.js"></script>
<script type="text/javascript" src="js/main.min.js"></script>
<script type="text/javascript" src="js/lightyear.js"></script>

<body>
<div class="lyear-layout-web">
	<div class="lyear-layout-container">
		<!--左侧导航-->
		<aside class="lyear-layout-sidebar">
			<!-- logo -->
			<div id="logo" class="sidebar-header">
				<a href="index.php"><img src="images/logo-sidebar.png" title="LightYear" alt="LightYear" /></a>
			</div>
			<div class="lyear-layout-sidebar-scroll"> 
				<nav class="sidebar-main">
					<ul class="nav nav-drawer">
						<li class="nav-item active"> <a href="index.php"><i class="mdi mdi-home"></i>首页</a> </li>
						<li class="nav-item active"> <a href="author.php"><i class="mdi mdi-account-check"></i>授权</a> </li>
						<li class="nav-item active"> <a href="useradmin.php"><i class="mdi mdi-account"></i>用户</a> </li>
						<li class="nav-item active"> <a href="exception.php"><i class="mdi mdi-account-alert"></i>异常</a> </li>
						<li class="nav-item active"> <a href="epgadmin.php"><i class="mdi mdi-television-guide"></i>EPG列表</a> </li>
						<li class="nav-item active"> <a href="channeladmin.php?categorytype=default"><i class="mdi mdi-television-classic"></i>频道列表</a> </li>
						<li class="nav-item active"> <a href="channeladmin.php?categorytype=vip"><i class="mdi mdi-crown"></i>会员专区</a> </li>
						<?php 
							if ($_SERVER['REQUEST_URI'] !='/views/sysadmin.php'){ 
								echo '<li class="nav-item active"> <a href="sysadmin.php"><i class="mdi mdi-settings-box"></i>系统设置</a> </li>';
							}else{
								echo '
						<li class="nav-item nav-item-has-subnav">
							<a href="javascript:void(0)"><i class="mdi mdi-menu"></i>系统设置</a>
							<ul class="nav nav-subnav">
								<li><a href="#" onclick="showli(0)">系统公告</a></li>
								<li><a href="#" onclick="showli(1)">系统备份</a></li>
								<li><a href="#" onclick="showli(2)">APP设置</a></li>
								<li><a href="#" onclick="showli(3)">背景图片</a></li>
								<li><a href="#" onclick="showli(4)">后台记录</a></li>		
								<li><a href="#" onclick="showli(5)">修改密码</a></li>
								<li><a href="#" onclick="showli(6)">管理员设置</a></li>
							</ul>
						</li>';
							}
						?>
					</ul>
				</nav>
				
				<div class="sidebar-footer">
					<p align="center"><?php echo date("Y-m-d H:i",time()); ?></p>
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
						if ($_SERVER['REQUEST_URI'] =='/views/index.php'){ echo '&nbsp;首页&nbsp;'; }
						else if ($_SERVER['REQUEST_URI'] =='/views/author.php'){ echo '&nbsp;授权&nbsp;'; }
						else if ($_SERVER['REQUEST_URI'] =='/views/useradmin.php'){ echo '&nbsp;用户&nbsp;'; }
						else if ($_SERVER['REQUEST_URI'] =='/views/exception.php'){ echo '&nbsp;异常&nbsp;'; }
						else if ($_SERVER['REQUEST_URI'] =='/views/epgadmin.php'){ echo '&nbsp;EPG列表&nbsp;'; }
						else if ($_SERVER['REQUEST_URI'] =='/views/channeladmin.php?categorytype=default'){ echo '&nbsp;频道列表&nbsp;'; }
						else if ($_SERVER['REQUEST_URI'] =='/views/channeladmin.php?categorytype=vip'){ echo '&nbsp;会员专区&nbsp;'; }
						else if ($_SERVER['REQUEST_URI'] =='/views/sysadmin.php'){ echo '&nbsp;系统设置&nbsp;'; }
						?>
						</span>
					</div>
					<ul class="topbar-right">
						<li class="dropdown dropdown-profile">
							<a href="javascript:void(0)" data-toggle="dropdown">
								<img class="img-avatar img-avatar-48 m-r-10" src="images/users/avatar.png" alt="user" />
								<span><?php echo $user ?><span class="caret"></span></span>
							</a>
							<ul class="dropdown-menu dropdown-menu-right">
								<li> <a href="../apps/logout.php"><i class="mdi mdi-logout-variant"></i> 退出登录</a> </li>
							</ul>
						</li>
						<!--切换主题配色-->
						<li class="dropdown dropdown-skin">
							<span data-toggle="dropdown" class="icon-palette"><i class="mdi mdi-palette"></i></span>
							<ul class="dropdown-menu dropdown-menu-right" data-stopPropagation="true">
								<li class="drop-title"><p>主题</p></li>
								<li class="drop-skin-li clearfix">
									<span class="inverse">
										<input type="radio" name="site_theme" value="default" id="site_theme_1" checked>
										<label for="site_theme_1"></label>
									</span>
									<span>
										<input type="radio" name="site_theme" value="dark" id="site_theme_2">
										<label for="site_theme_2"></label>
									</span>
									<span>
										<input type="radio" name="site_theme" value="translucent" id="site_theme_3">
										<label for="site_theme_3"></label>
									</span>
								</li>
								<li class="drop-title"><p>LOGO</p></li>
								<li class="drop-skin-li clearfix">
									<span class="inverse">
										<input type="radio" name="logo_bg" value="default" id="logo_bg_1" checked>
										<label for="logo_bg_1"></label>
									</span>
									<span>
										<input type="radio" name="logo_bg" value="color_2" id="logo_bg_2">
										<label for="logo_bg_2"></label>
									</span>
									<span>
										<input type="radio" name="logo_bg" value="color_3" id="logo_bg_3">
										<label for="logo_bg_3"></label>
									</span>
									<span>
										<input type="radio" name="logo_bg" value="color_4" id="logo_bg_4">
										<label for="logo_bg_4"></label>
									</span>
									<span>
										<input type="radio" name="logo_bg" value="color_5" id="logo_bg_5">
										<label for="logo_bg_5"></label>
									</span>
									<span>
										<input type="radio" name="logo_bg" value="color_6" id="logo_bg_6">
										<label for="logo_bg_6"></label>
									</span>
									<span>
										<input type="radio" name="logo_bg" value="color_7" id="logo_bg_7">
										<label for="logo_bg_7"></label>
									</span>
									<span>
										<input type="radio" name="logo_bg" value="color_8" id="logo_bg_8">
										<label for="logo_bg_8"></label>
									</span>
								</li>
								<li class="drop-title"><p>头部</p></li>
								<li class="drop-skin-li clearfix">
									<span class="inverse">
										<input type="radio" name="header_bg" value="default" id="header_bg_1" checked>
										<label for="header_bg_1"></label>											
									</span>																										
									<span>																										 
										<input type="radio" name="header_bg" value="color_2" id="header_bg_2">
										<label for="header_bg_2"></label>											
									</span>																										
									<span>																										 
										<input type="radio" name="header_bg" value="color_3" id="header_bg_3">
										<label for="header_bg_3"></label>
									</span>
									<span>
										<input type="radio" name="header_bg" value="color_4" id="header_bg_4">
										<label for="header_bg_4"></label>											
									</span>																										
									<span>																										 
										<input type="radio" name="header_bg" value="color_5" id="header_bg_5">
										<label for="header_bg_5"></label>											
									</span>																										
									<span>																										 
										<input type="radio" name="header_bg" value="color_6" id="header_bg_6">
										<label for="header_bg_6"></label>											
									</span>																										
									<span>																										 
										<input type="radio" name="header_bg" value="color_7" id="header_bg_7">
										<label for="header_bg_7"></label>
									</span>
									<span>
										<input type="radio" name="header_bg" value="color_8" id="header_bg_8">
										<label for="header_bg_8"></label>
									</span>
								</li>
								<li class="drop-title"><p>侧边栏</p></li>
								<li class="drop-skin-li clearfix">
									<span class="inverse">
										<input type="radio" name="sidebar_bg" value="default" id="sidebar_bg_1" checked>
										<label for="sidebar_bg_1"></label>
									</span>
									<span>
										<input type="radio" name="sidebar_bg" value="color_2" id="sidebar_bg_2">
										<label for="sidebar_bg_2"></label>
									</span>
									<span>
										<input type="radio" name="sidebar_bg" value="color_3" id="sidebar_bg_3">
										<label for="sidebar_bg_3"></label>
									</span>
									<span>
										<input type="radio" name="sidebar_bg" value="color_4" id="sidebar_bg_4">
										<label for="sidebar_bg_4"></label>
									</span>
									<span>
										<input type="radio" name="sidebar_bg" value="color_5" id="sidebar_bg_5">
										<label for="sidebar_bg_5"></label>
									</span>
									<span>
										<input type="radio" name="sidebar_bg" value="color_6" id="sidebar_bg_6">
										<label for="sidebar_bg_6"></label>
									</span>
									<span>
										<input type="radio" name="sidebar_bg" value="color_7" id="sidebar_bg_7">
										<label for="sidebar_bg_7"></label>
									</span>
									<span>
										<input type="radio" name="sidebar_bg" value="color_8" id="sidebar_bg_8">
										<label for="sidebar_bg_8"></label>
									</span>
								</li>
							</ul>
						</li>
					</ul>
				</div>
			</nav>
		</header>
		<!--End 头部信息-->