<?php
require_once "views/view.main.php";
require_once "controler/alipayController.php";
?>
<script type="text/javascript" src="/views/js/wxcheck.js"></script>
<body>
	<div id="container">
		<div class="lyear-login-box">
       <div class="lyear-login-left">
            <ul class="w3lsg-bubbles">
               <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
            </ul>
            <div class="lyear-overlay"></div>
            <div class="lyear-featured">
                <h4>
                    <?php echo $mingyan_contents; ?>
                    <small>
                        - <?php echo $mingyan_author; ?>
                    </small>
                </h4>
            </div>
      </div>
      <section id="Loading"></section>
      <div class="lyear-login-right form__content">
           <div class="lyear-logo text-center">
                <a href="payment.php">
                    <img src="views/images/logo-sidebar.png">
                </a>
            </div>
				<table class="table form-inline">
					<?php
						if (isset($_POST['dopay'])) {
							if ($result['code'] && $result['code']=='10000') {
							    //生成二维码
							    $url = $result['qr_code'];
							    $qr_code = 'http://qr.topscan.com/api.php?text=' . $url . '&bg=ffffff&fg=000000&pt=1c73bd&m=10&w=400&el=1&inpt=1eabfc&logo=https://t.alipayobjects.com/tfscom/T1Z5XfXdxmXXXXXXXX.png';
							    exit ("
										<tr>
											<td>
												<img class='w-75 m-b-10' src='{$qr_code}' />
												<form method='GET'>
													<div class='form-group'>
														<button type='button' class='btn btn-block btn-primary' onclick='jumptoalipay();'>前往支付宝</button>
													</div>
													<div class='form-group'>
														<button type='submit' class='btn btn-block btn-primary' name='checkpay' value='$userid'>我已支付</button>
													</div>
												</form>
											</td>
										</tr>
										<script>
											function jumptoalipay() {
												var gotoUrl = '$url';
												_AP.pay(gotoUrl);
											}
										</script>
									");
							} else {
							    exit ("
										<tr>
											<td>
												订单生成失败，请联系管理员。
											<td>
										<tr>
								");
							}
						}
						
						if (isset($_GET['checkpay'])) {
							$checkpay = $_GET['checkpay'];
						    if($db->mGet("luo2888_meals", "status", "where userid=$checkpay") == 1){
								exit ("<script>$.alert({title: '支付成功',content: '订单已支付成功，请重新启动APP！',type: 'green',buttons: {confirm: {text: '确定',btnClass: 'btn-primary',action: function(){window.location.href='payment.php';}}}});</script>");
						    } else {
								exit ("<script>$.alert({title: '支付失败',content: '支付状态为未支付，如果您已支付，请联系管理员！',type: 'red',buttons: {confirm: {text: '确定',btnClass: 'btn-primary',action: function(){window.location.href='payment.php';}}}});</script>");
						    }
						}
					?>
					<?php 						
					if (isset($_GET['id']) && !empty($_GET['id'])) {
         $userid = !empty($_POST["userid"])?$_POST["userid"]:$userid=$_GET['id'];
						if ($row = $db->mCheckRow("luo2888_users", "name,mac,region", "where name='$userid'")) {
						    $userid= $row['name'];
						    $usermac= $row['mac'];
						    $userloc= $row['region'];
						} else {
							exit ("<script>$.alert({title: '警告',content: '找不到该用户，请确认用户ID是否正确！',type: 'orange',buttons: {confirm: {text: '确定',btnClass: 'btn-primary',action: function(){window.location.href='payment.php';}}}});</script>");
						}
						echo "
							<label>请确认订单信息</label>
							<tr align='left'><td>用户ID：$userid</td></tr>
							<tr align='left'><td>用户位置：$userloc</td></tr>
							<tr align='left'><td>设备MAC地址：$usermac</td></tr>
						";
							$result=$db->mQuery("select name,content from luo2888_meals where status=1 and sale=1 and id<>1000 ORDER BY id ASC");
							while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
							    echo '<tr align="left"><td>' . $row["name"] . ' : ' . $row["content"] . '</td></tr>';
							} 
							mysqli_free_result($result);
						echo '
						<form class="form-inline" id="order_form" method="post">
							<tr align="center" class="m-t-15">
								<td>
									<div class="input-group">
										<input type="hidden" name="userid" value="'. $userid .'"/>
										<select class="btn btn-sm btn-default dropdown-toggle" style="height: 30px;" name="mealid">
											<option value="">请选择套餐</option>';
						       	$result=$db->mQuery("select id,name,amount,days from luo2888_meals where status=1 and sale=1 and id<>1000 ORDER BY id ASC");
											while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
											 if ($row["days"] == 999) {
									    			$days = "永久授权";
								    		} else {
										   			$days = $row["days"] . "天";
							  				}
												echo '<option value="' . $row["id"] . '">' . $row["name"] . ' | ' . $days . ' | ' . $row["amount"] . '元' . '</option>';
											} 
											mysqli_free_result($result);
						echo			'</select>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="form-group w-100">
										<button type="submit" class="btn btn-block btn-primary" name="dopay" id="dopay" onclick="return confirm(\'警告，订单支付成功代表您同意不退款原则，请确认同意后再付款！\')">提交订单</button>
									</div>
								</td>
							</tr>
						</form>
						';
					} ?>
				</table>
				<form class="form-inline" id="userid_form" method="GET">
					<div class="form-group">
						<input type="text" name="id" class="form-control" value="<?php echo $user ?>" placeholder="请输入用户账号">
					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-block btn-primary" id="userid_enter">查询</button>
					</div>
				</form>
				<hr>
				<footer class="col-sm-12 text-center">
					<p class="m-b-0">Copyright © 2017-2020 <a href="http://www.luo2888.cn">luo2888.cn</a>. All right reserved</p>
				</footer>
		</div>
	</div>
	<script type="text/javascript">
	$('#userid_enter').on('click', function(){
	    lightyear.loading('show');
	});
	$('#dopay').on('click', function(){
	    lightyear.loading('show');
	});
	</script>
  <script type="text/javascript">
      document.onreadystatechange = function() {
          if (document.readyState == 'complete') {
              $("#Loading").fadeOut();
          }
      }
  </script>
<?php
if (isset($_GET['id'])) {
    echo '<script type="text/javascript">$("#userid_form").hide;$("#userid_form").hide(0);</script>';
}
?>
</body>