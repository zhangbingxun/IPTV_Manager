<?php require_once "view.section.php";require_once "../apps/exceptionController.php"; ?>

<!--页面主要内容-->
<main class="lyear-layout-content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">
				<div class="card">
					<div class="card-header"><h4>异常列表</h4></div>
					<div class="tab-content">
						<div class="tab-pane active">
							<form class="form-inline" method="POST" style="padding: 0px 10px 10px 0px;" >
								<label>允许同一个IP授权数量：</label>
								<div class="input-group">
									<div class="input-group-btn">
									<input style="width: 85px;height: 30px;" class="form-control" type="text" name="sameip_user" size="2" value="<?php echo $max_sameip_user; ?>">
									<button type="submit" name="submitsameip_user" class="btn btn-sm btn-primary m-r-5">保存</button>
									<button type="submit" name="clearvpn" class="btn btn-sm btn-danger">清空抓包记录</button>
									</div>
								</div>
							</form>
							<form align="center" style="padding: 5px;" method="POST" >
								
							</form>
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
													<td>$name</td>
													<td>$vpn</td>
													<td>$model</td>
													<td>$marks</td>
													<td>$st</td>
													<td colspan='2'>
													<form method='post'>
														<input type='hidden' name='name' value='$name'>
														<button class='btn btn-sm btn-primary' type='submit' name='startuse'>启用</button>
														<button class='btn btn-sm btn-danger' type='submit' name='stopuse'>停用</button>
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