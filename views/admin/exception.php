<?php
require_once "../view.section.php";

if ($user != $admin) {
    exit("<script>$.alert({title: '警告',content: '你无权访问此页面。',type: 'orange',buttons: {confirm: {text: '确定',btnClass: 'btn-primary',action: function(){history.go(-1);}}}});</script>");
} 

require_once "../../controler/exceptionController.php";
?>

<!--页面主要内容-->
<main class="lyear-layout-content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">
				<div class="card">
					<div class="card-header">
						<h4>异常列表</h4>
							<form method="POST">
								<button type="submit" name="clearvpn" class="btn btn-sm btn-danger pull-right">清空记录</button>
							</form>
					</div>
					<div class="tab-content">
						<div class="tab-pane active">
							<div class="form-group">
								<div class="table-responsive">
									<table class="table table-hover">
										<thead>
										<tr>
											<th>账号</th>
											<th>抓包次数</th>
											<th>型号</th>
											<th>账号备注</th>
											<th>用户状态</th>
											<th>操作</th>
										</tr>
										</thead>
										<tbody>
											<?php
												$result=$db->mQuery("SELECT status,name,model,vpn,marks,exp from luo2888_users where vpn>0");
												if (!mysqli_num_rows($result)) {
												    echo '<tr><td colspan="6" align="center" style="font-size:14px;color:red;height:35px;font-weight: bold;">当前无用户抓包记录！</td></tr>';
												    mysqli_free_result($result);
												}
												while ($row=mysqli_fetch_array($result)) {
													$vpn=$row['vpn'];
													$name=$row['name'];
													$marks=$row['marks'];
													$status=$row['status'];
													$model=$row['model'];
													$exp=ceil(($row['exp']-time())/86400);
													if($status==-1){
														$st="试用天数[$exp]";
													}elseif ($status==0) {
														$st='已停用';
													}elseif($status==1){
														$st='正常';
													}else{
														$st='永不到期';
													}
												echo "<tr>
													<td><a href='useradmin.php?keywords=$name'>$name</a></td>
													<td>$vpn</td>
													<td>$model</td>
													<td>$marks</td>
													<td>$st</td>
													<td colspan='2'>
													<form method='post'>
														<input type='hidden' name='name' value='$name'>
														<button class='btn btn-sm btn-danger' type='submit' name='stopuse'>停用</button>
														<button class='btn btn-sm btn-danger' type='submit' name='clearuser'>清空</button>
														&nbsp;&nbsp;&nbsp;&nbsp;
													</form>
													</td>
												</tr>";
												}
											?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
<!--End 页面主要内容-->
</div>
</div>