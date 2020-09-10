<?php
require_once "../view.section.php";
require_once "../../controler/exceptionController.php";
?>

<!--页面主要内容-->
<main class="lyear-layout-content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">
				<div class="card">
					<div class="card-header">
						<h4>异常用户列表</h4>
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
											<th>用户状态</th>
										</tr>
										</thead>
										<tbody>
											<?php
												$result=$db->mQuery("SELECT status,name,model,vpn,marks,exp from luo2888_users where author='$user' and vpn>0");
												if (!mysqli_num_rows($result)) {
												    echo '<tr><td colspan="4" align="center" style="font-size:14px;color:red;height:35px;font-weight: bold;">当前无用户抓包记录！</td></tr>';
												    mysqli_free_result($result);
												}
												while ($row=mysqli_fetch_array($result)) {
													$vpn=$row['vpn'];
													$name=$row['name'];
													$model=$row['model'];
													$status=$row['status'];
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
													<td>$st</td>
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