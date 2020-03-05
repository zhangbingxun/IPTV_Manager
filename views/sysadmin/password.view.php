<?php $secret_key=get_config('secret_key'); ?>
<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header"><h4>修改密码</h4></div>
			<div class="tab-content">
				<div class="tab-pane active">
					<div class="form-group">
						<form method="post">
							<div class="form-group">
							<label class="btn-block">新安全码</label>
								<input class="form-control" type="password" name="newsecret_key" value=""><br>
							<label class="btn-block">确认新安全码</label>
								<input class="form-control" type="password" name="newsecret_key_confirm" value="">
							</div>
							<div class="form-group">
								<button class="btn btn-label btn-primary" type="submit" name="submit" value=""><label><i class="mdi mdi-checkbox-marked-circle-outline"></i></label>修改安全码</button>
								<button class="btn btn-danger" type="submit" name="closesecret_key" value="" <?php if(empty($secret_key)){echo 'disabled';} ?>><?php if (empty($secret_key)){echo '安全码验证已关闭';} else {echo '关闭安全码验证';} ?></button>
							</div>
						</form>
						<hr>
						<form method="post">
							<div class="form-group">
							<label class="btn-block">用户名</label>
								<input class="form-control" type="text" name="username" value="admin" size="80"><br>
							<label class="btn-block">旧密码</label>
								<input class="form-control" type="password" name="oldpassword" value="" size="80"><br>
							<label class="btn-block">新密码</label>
								<input class="form-control" type="password" name="newpassword" value="" size="80"><br>
							</div>
							<button class="btn btn-label btn-primary" type="submit" name="submit" value=""><label><i class="mdi mdi-checkbox-marked-circle-outline"></i></label>修改密码</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>