<?php require_once "views/view.main.php";require_once "apps/alipayController.php"; ?>
<body scroll="no" style="overflow-x:hidden;overflow-y:hidden">
	<div id="container" class="row lyear-wrapper">
		<div class="lyear-login">
			<div id="bg">
				<div id="anitOut"></div>
			</div>
			<div class="login-center form__content">
				<div class="login-header text-center">
					<a href="payment.php"> <img src="views/images/logo-sidebar.png"> </a>
				</div>
				<table class="table table-bordered form-inline">
					<?php 						
					if (isset($_POST['userid_enter'])) {
						$userid=$_POST['userid'];
						if ($row = $db->mCheckRow("luo2888_users", "name,mac,ip,region", "where name='$userid'")) {
						    $userid= $row['name'];
						    $usermac= $row['mac'];
						    $userip= $row['ip'];
						    $userloc= $row['region'];
						} else {
							echo ("<script>$.alert({title: '警告',content: '找不到该用户，请确认用户ID是否正确！',type: 'orange',buttons: {confirm: {text: '确定',btnClass: 'btn-primary',action: function(){window.location.href='payment.php';}}}});</script>");
						}
						echo "
							<tr align='left'><td>用户ID：$userid</td></tr>
							<tr align='left'><td>用户IP：$userip</td></tr>
							<tr align='left'><td>用户位置：$userloc</td></tr>
							<tr align='left'><td>设备MAC地址：$usermac</td></tr>
						";
					} ?>
				</table>
				<form class="form-inline" id="userid_form" method="post">
					<div class="form-group has-feedback feedback-left">
					<input type="text" name="userid" class="form-control" value="<?php echo $userid ?>" placeholder="请输入用户ID">
					<div class="form-group">
						<button type="submit" class="btn btn-block btn-primary" name="userid_enter" id="userid_enter">查询</button>
					</div>
						<?php
						$result=$db->mQuery("select id,name from luo2888_meals where status=1 ORDER BY id ASC");
							echo  "<select class=\"btn btn-default dropdown-toggle\" name=\"meal_s[]\"> ";
							echo "<option value=\"\">请选择授权套餐</option>";
							while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
								echo "<option value=\"" . $row["id"] . "\">" . $row["name"] . "</option>";
							} 
						echo "</select>";
						mysqli_free_result($result);
						?>
					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-block btn-primary" name="secret_key_enter" id="secret_key_enter">提交订单</button>
					</div>
				</form>
				<hr>
				<footer class="col-sm-12 text-center">
					<p class="m-b-0">Copyright © 2020 <a href="http://www.luo2888.cn">luo2888.cn</a>. All right reserved</p>
				</footer>
			</div>
		</div>
	</div>
	<script type="text/javascript">
	$('#userid_enter').on('click', function(){
	    lightyear.loading('show');
	});
	</script>
</body>