<?php
require_once "../view.section.php";
require_once "../../controler/useradminController.php"; ?>

<script type="text/javascript">
	function quanxuan(a){
		var ck=document.getElementsByName("id[]");
		for (var i = 0; i < ck.length; i++) {
			if(a.checked){
				ck[i].checked=true;
			}else{
				ck[i].checked=false;
			}
		}
	}
</script>
    
    <!--页面主要内容-->
	<main class="lyear-layout-content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12">
					<div class="card">
						<div class="card-header">
						    <h4>已授权用户列表</h4>
                            <button type="button" class="btn btn-sm btn-primary pull-right" onclick="document.userform.submitclearbind.click()">解绑</button>
						</div>
						<div class="card-toolbar clearfix">
							<form class="search-bar" method="GET">
								<div class="input-group">
									<div class="input-group-btn">
										<input class="form-control" style="width: 225px;" type="text" name="keywords" value="<?php echo $keywords;?>" placeholder="请输入名称">
										<button class="btn btn-default" type="submit">搜索</button>
									</div>
								</div>
							</form>
						</div>
						<div class="tab-content">
							<div class="tab-pane active">
								<div class="form-group">
                				<div class="table-responsive">
									<table class="table table-hover" style="white-space:nowrap;word-break:keep-all;">
										<thead>
										<tr>
											<th class="w-1">
												<label class="lyear-checkbox checkbox-primary">
													<input type="checkbox" onclick="quanxuan(this)">
													<span></span>
												</label>
											</th>
											<th class="w-5"><a href="?order=name">账号</a></th>
											<th class="w-10"><a href="?order=meal">套餐</a></th>
											<th class="w-15"><a href="?order=mac">MAC地址</a></th>
											<th class="w-15"><a href="?order=deviceid">设备ID</a></th>
											<th class="w-10"><a href="?order=model">型号</a></th>
											<th class="w-10"><a href="?order=ip">IP</a></th>
											<th class="w-15"><a href="?order=region">地区</a></th>
											<th class="w-5"><a href="?order=lasttime">在线时长</a></th>
											<th class="w-15"><a href="?order=loginime">最后登陆</a></th>
											<th class="w-5"><a href="?order=exp">状态</a></th>
										</tr>
										</thead>
										<tbody>
											<form method="POST" name="userform">
												<?php
													$recStart=$recCounts*($page-1);
													$meals=$db->mQuery("select id,name from luo2888_meals");
													if (mysqli_num_rows($meals)) {
														$meals_arr = [];
														while ($row = mysqli_fetch_array($meals, MYSQLI_ASSOC)) {
															$meals_arr[$row["id"]] = $row["name"];
														} 
														unset($row);
														mysqli_free_result($meals);
													} 
													$result=$db->mQuery("select * from luo2888_users where author='$user' and status>0 $searchparam order by $order limit $recStart,$recCounts");
													if (mysqli_num_rows($result)) {
														while($row=mysqli_fetch_array($result)){
															$status=$row['status'];
															$lasttime=$row['lasttime'];
															$logintime=$row['logintime'];
															$logindate=date("Y-m-d H:i:s",$row['logintime']);
															$onlinetime=abs(round(($lasttime - $logintime) / 60));
															$days=ceil(($row['exp']-time())/86400);
															$name=$row['name'];
															$deviceid=$row['deviceid'];
															$mac=$row['mac'];
															$model=$row['model'];
															$ip=$row['ip'];
															$region=$row['region'];
															if (empty($meals_arr[$row["meal"]])) {
																$meal = $row["meals"];
															} else {
																$meal = $meals_arr[$row["meal"]];
															} 
															if($row['exp']>time()){
															    $days='剩'."$days".'天';
															}
															if($row['exp']<time()){
																$days='过期';
															}
															if($status==999){
																$days='永不到期';
																$expdate=$days;
															}
															if(abs(time() - $lasttime) > $onlinetime * 60) {
																$onlinestatus='不在线';
															} else {
																$onlinestatus=$onlinetime . '分';
															}
															echo "<tr class=\"h-5\">
																<td><label class=\"lyear-checkbox checkbox-primary\">
																<input type=\"checkbox\" name=\"id[]\" value=\"$name\"><span></span>
																</label></td>
																<td>$name</td>
																<td>$meal</td>
																<td>$mac</td>
																<td>".$deviceid."</td>
																<td>$model</td>
																<td>".$ip."</td>
																<td>".$region."</td>
																<td>".$onlinestatus ."</td>
																<td>".$logindate ."</td>
																<td>".$days."</td>
																</tr>";
														}
														unset($row);
													}else {
													    echo "<tr><td align='center' colspan='11'><font color='red'>对不起，没有用户数据！</font></td></tr>";
													}
													mysqli_free_result($result);
												?>
												<input style="display: none;" type="submit" name="submitclearbind"  onclick="return confirm('确认解除设备绑定？')"/>
											</form>
										</tbody>
									</table>
								</div>
								</div>
								<nav>
									<ul class="pager">
										<li><a href="<?php if($page>1){$p=$page-1;}else{$p=1;} echo '?page='.$p.'&order='.$order.'&keywords='.$keywords?>">上一页</a></li>
										<li class="previous"><a href="<?php echo '?page=1&order='.$order.'&keywords='.$keywords?>">首页</a></li>
										<li class="next"><a href="<?php echo '?page='.$pageCount.'&order='.$order.'&keywords='.$keywords?>">尾页</a></li>
										<li><a href="<?php if($page<$pageCount){$p=$page+1;} else {$p=$page;} echo '?page='.$p.'&order='.$order.'&keywords='.$keywords?>">下一页</a></li>
									</ul>
								</nav>
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