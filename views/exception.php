<?php include_once "view.section.php";include_once "../apps/exceptionController.php"; ?>

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
										<input style="width: 85px;height: 30px;" class="form-control" type="text" name="sameip_user" size="2" value="<?php echo get_config('max_sameip_user');?>">
										<button type="submit" name="submitsameip_user" class="btn btn-sm btn-primary m-r-5">保存</button>
										</div>
									</div>
								</form>
								<div class="form-group">
	                				<div class="table-responsive">
										<table class="table table-hover">
											<thead>
											<tr>
												<th>账号</th>
												<th>登陆信息</th>
												<th>登陆信息</th>
												<th>登陆信息</th>
												<th>登陆信息</th>
												<th>登陆信息</th>
												<th>IP数量</th>
											</tr>
											</thead>
											<tbody>
												<?php
													$pre24time=strtotime(date("Y-m-d"),time());
													$result=mysqli_query($GLOBALS['conn'],"SELECT userid,deviceid,mac,model,ip,region,logintime from luo2888_loginrec where logintime>$pre24time order by userid,deviceid,mac,model");
													$arrLoginInfo = array();
													while($row=mysqli_fetch_array($result)){
														$logintime=date("Y-m-d H:i:s",$row['logintime']);
														$userid=$row['userid'];
														$arrLoginInfo[$userid][]=$row['region']."<br>".$row['ip']."<br>".$logintime;
													}
													foreach ($arrLoginInfo as $key => $value) {
														if(count($value)>=$ipcount){
															echo "<tr>
																<td>".$key."</td>
																<td>".$arrLoginInfo[$key][0]."</td>
																<td>".$arrLoginInfo[$key][1]."</td>
																<td>".$arrLoginInfo[$key][2]."</td>
																<td>".$arrLoginInfo[$key][3]."</td>
																<td>".$arrLoginInfo[$key][4]."</td>
																<td>".count($value)."</td>
															</tr>";
														}
													}
												?>
											</tbody>
										</table>
									</div>
								</div>
								<form align="center" style="padding: 5px;" method="POST" >
									<button type="submit" name="clearvpn" class="btn btn-label btn-danger"><label><i class="mdi mdi-delete-empty"></i></label> 清空记录</button>
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
													$result=mysqli_query($GLOBALS['conn'],"SELECT status,name,model,vpn,marks,exp from luo2888_users where vpn>0");
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