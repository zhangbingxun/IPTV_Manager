<? include "apps/secretkeyController.php" ?>
<?php
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");//标记内容最后修改时间
header("Cache-Control: no-store, no-cache, must-revalidate");//强制不缓存
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");//禁止本页被缓存
header("Access-Control-Allow-Origin: *"); // Support CORS

?>
<!DOCTYPE html>
<html lang="zh">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
<title>欢迎登录IPTV管理系统</title>
<link rel="icon" href="views/images/favicon.ico" type="image/ico">
<meta name="keywords" content="小肥米,后台管理系统">
<meta name="description" content="小肥米IPTV后台管理系统">
<meta name="author" content="luo2888">
<link href="views/css/bootstrap.min.css" rel="stylesheet">
<link href="views/css/materialdesignicons.min.css" rel="stylesheet">
<link href="views/css/style.min.css" rel="stylesheet">
<style>
.lyear-wrapper {
    position: relative;
}
.lyear-login {
    min-height: 100vh;
    display: flex !important;
    align-items: flex-start !important;
    justify-content: center !important;
}
@media screen and (max-width: 524px){
	.lyear-login {
	    padding-left: 3.5em;
	    padding-right: 2.5em;
	}
}
.login-center {
    height: auto;
    background: #fff;
    border-radius: 5px;
    margin: 2.85714em 0;
    min-width: 38.25rem;
    padding: 2.14286em 3.57143em;
}
.login-header {
    margin-bottom: 1.5rem !important;
}
.login-center .has-feedback.feedback-left .form-control {
    padding-left: 38px;
    padding-right: 12px;
}
.login-center .has-feedback.feedback-left .form-control-feedback {
    left: 0;
    right: auto;
    width: 38px;
    height: 38px;
    line-height: 38px;
    z-index: 4;
    color: #dcdcdc;
}
.login-center .has-feedback.feedback-left.row .form-control-feedback {
    left: 15px;
}
</style>
</head>
  
<body scroll="no" style="overflow-x:hidden;overflow-y:hidden">
	<div class="row lyear-wrapper">
		<div class="lyear-login">
			<div class="login-center">
				<div class="login-header text-center">
					<a href="index.php"> <img alt="light year admin" src="views/images/logo-sidebar.png"> </a>
				</div>
				<?php if($_SESSION['secret_key_status']=='1'){include "views/userlogin.php";}?>
				<form id="secret_keyForm" method="post">
					<div class="form-group has-feedback feedback-left">
						<input type="password" name="secret_key" class="form-control" placeholder="请输入安全验证码">
						<span class="mdi mdi-check-all form-control-feedback" aria-hidden="true"></span>
					</div>
					<div class="form-group has-feedback feedback-left">
						<label class="lyear-checkbox">
							<input type="checkbox" name="remembersecret_key" value="1">
							<span>记住7天</span>
						</label>
					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-block btn-primary" name="secret_key_enter" id="secret_key_enter">立即登陆</button>
					</div>
				</form>
				<hr>
				<footer class="col-sm-12 text-center">
					<p class="m-b-0">Copyright © 2020 <a href="http://www.luo2888.cn">luo2888.cn</a>. All right reserved</p>
				</footer>
			</div>
		</div>
	</div>
	<script type="text/javascript" src="views/js/jquery.min.js"></script>
	<script type="text/javascript" src="views/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="views/js/perfect-scrollbar.min.js"></script>
	<script type="text/javascript" src="views/js/lightyear.js"></script>
	<script type="text/javascript" src="views/js/main.min.js"></script>
	<script type="text/javascript">
	// 消息提示示例
	$('#secret_key_enter').on('click', function(){
	    lightyear.loading('show');
	});
	$('#login_key_enter').on('click', function(){
	    lightyear.loading('show');
	});
	</script>
	<?php if($_SESSION['secret_key_status']=='1'){
		echo '<script type="text/javascript">$("#secret_keyForm").hide;$("#secret_keyForm").hide(0);</script>';
		}
	?>
</body>
</html>