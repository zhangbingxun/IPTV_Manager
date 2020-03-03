<? include_once "./apps/userloginController.php" ?>
<form method="post">
	<div class="form-group has-feedback feedback-left">
		<input type="text" placeholder="请输入您的用户名" class="form-control" name="username" id="username" />
		<span class="mdi mdi-account form-control-feedback" aria-hidden="true"></span>
	</div>
	<div class="form-group has-feedback feedback-left">
		<input type="password" placeholder="请输入密码" class="form-control" id="password" name="password" />
		<span class="mdi mdi-lock form-control-feedback" aria-hidden="true"></span>
	</div>
	<div class="form-group has-feedback feedback-left">
		<label class="lyear-checkbox checkbox-primary">
		<input type="checkbox" name="rememberpass" value="1">
			<span>记住7天</span>
		</label>
	</div>
	<div class="form-group">
		<button type="submit" class="btn btn-block btn-primary" id="login_key_enter" >进入后台</button>
	</div>
</form>