<?php include_once "view.section.php";include_once "../apps/useradminController.php"; ?>

<script type="text/javascript">
	function submitForm(){
		var form = document.getElementById("recCounts");
		form.submit();
	}
	function submitjump(){
		var form = document.getElementById("jumpto");
		form.submit();
	}
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
						<div class="card-header"><h4>已授权用户列表</h4></div>
						<div class="card-toolbar clearfix">
							<div class="btn-block" >
								<label>待授权用户：<?php echo $userCount; ?></label>
								<label>今日上线：<?php echo $todayuserCount; ?></label>
								<label>今日授权：<?php echo $todayauthoruserCount; ?></label>
							</div>
							<form class="pull-right search-bar" method="get" role="form">
								<div class="input-group">
									<div class="input-group-btn">
										<input class="form-control" style="width: 225px;" type="text" name="keywords" value="<?php echo $keywords;?>" placeholder="请输入名称">
										<button class="btn btn-default" type="submit" name="submitsearch" >搜索</button>
									</div>
								</div>
							</form>

	                		<div class="toolbar-btn-action">
								<form class="pull-left" method="POST" id="recCounts">
										<label>每页</label>
										<select class="btn btn-sm btn-default dropdown-toggle" id="sel" name="recCounts" onchange="submitForm();">			
											<?php
											switch ($recCounts) {
												case '20':
													echo "<option value=\"20\" selected=\"selected\">20</option>";
													echo "<option value=\"50\">50</option>";
													echo "<option value=\"100\">100</option>";
													break;
												case '50':
													echo "<option value=\"20\">20</option>";
													echo "<option value=\"50\" selected=\"selected\">50</option>";
													echo "<option value=\"100\">100</option>";
													break;
												case '100':
													echo "<option value=\"20\">20</option>";
													echo "<option value=\"50\">50</option>";
													echo "<option value=\"100\" selected=\"selected\">100</option>";
													break;
												
												default:
													echo "<option value=\"20\" selected=\"selected\">20</option>";
													echo "<option value=\"50\">50</option>";
													echo "<option value=\"100\">100</option>";
													break;
											}
											?>			
										</select><label>&nbsp;条</label>
								</form>
								<form class="pull-left" method="post" id="jumpto">
									<input type="text" name="jumpto" style="border-width: 0px;text-align: right;" size=2 value="<?php echo $page?>">/<?php echo $pageCount?>
									<button class="btn btn-xs btn-default" onclick="submitjump()">跳转</button>
								</form>
							</div>
						</div>
						<div class="tab-content">
							<div class="tab-pane active">
								<div class="form-group">
                				<div class="table-responsive">
									<table class="table table-hover">
										<thead>
										<tr>
											<th>
												<label class="lyear-checkbox checkbox-primary">
													<input type="checkbox" onclick="quanxuan(this)">
													<span></span>
												</label>
											</th>
											<th><a href="?order=name">账号</a></th>
											<th><a href="?order=mac">MAC地址</a></th>
											<th><a href="?order=deviceid">设备ID</a></th>
											<th><a href="?order=model">型号</a></th>
											<th><a href="?order=ip">IP</a></th>
											<th><a href="?order=region">地区</a></th>
											<th><a href="?order=lasttime">最后登陆</a></th>
											<th><a href="?order=exp">状态</a></th>
											<th><a href="?order=isvip">VIP</a></th>
											<th><a href="?order=author">授权人</a></th>
											<th><a href="?order=marks">备注</a></th>
										</tr>
										</thead>
										<tbody>
											<form method="POST">
												<?php
													$recStart=$recCounts*($page-1);
													if($user=='admin'){
													$sql="select status,name,mac,deviceid,model,ip,region,lasttime,exp,author,marks,vpn,isvip from luo2888_users where status>0 $searchparam order by $order  limit $recStart,$recCounts";
													}else{
														$sql="select status,name,mac,deviceid,model,ip,region,lasttime,exp,author,marks,vpn,isvip from luo2888_users where status>0 and author='$user' $searchparam order by $order limit $recStart,$recCounts";
													}
													$result=mysqli_query($GLOBALS['conn'],$sql);
													if (mysqli_num_rows($result)) {
														while($row=mysqli_fetch_array($result)){
															$status=$row['status'];
															$lasttime=$status==2?'MAC导入未激活':date("Y-m-d H:i:s",$row['lasttime']);
															$days=ceil(($row['exp']-time())/86400);
															$expdate="到期时间：".date("Y-m-d H:i:s",$row['exp']);
															$name=$row['name'];
															$deviceid=$row['deviceid'];
															$mac=$row['mac'];
															$model=$row['model'];
															$ip=$row['ip'];
															$region=$row['region'];
															$author=$row['author'];
															$marks=$row['marks'];
															$vpn=$row['vpn'];
															if($row['isvip']==0){$isvip='否';$fontcolor='black';}else{$isvip='是';$fontcolor='red';}
															if($row['exp']<time()){
																$days='过期';
															}
															if($status==0){
																$days='停用';
																if($vpn>0){
																   $days="禁用[".$vpn."]";
																}
															}
															if($status==2){
																$days=$row['exp'];
																$expdate='MAC导入未激活';
															}
															if($status==999){
																$days='永不到期';
																$expdate=$days;
															}
															echo "<tr>
																<td><label class=\"lyear-checkbox checkbox-primary\">
																<input type=\"checkbox\" name=\"id[]\" value=\"$name\"><span></span>
																</label></td>
																<td><font color='$fontcolor'>$name </font></td>
																<td>$mac</td>
																<td>".$deviceid."</td>
																<td>$model</td>
																<td>".$ip."</td>
																<td>".$region."</td>
																<td>".$lasttime ."</td>
																<td title='$expdate'>".$days."</td>
																<td>".$isvip."</td>
																<td>".$author."</td>
																<td>$marks</td>
																</tr>";
														}
														unset($row);
													}else {
													    echo "<tr><td align='center' colspan='12'><font color='red'>对不起，当前未有已授权的用户数据！</font></td></tr>";
													}
													mysqli_free_result($result);
													mysqli_close($GLOBALS['conn']);
																?>
												<tr>
													<td colspan="12">
															<div class="example-box">
																<button class="btn btn-sm btn-primary" type="submit" name="submitsetvip">设为VIP用户</button>
																<button class="btn btn-sm btn-primary" type="submit" name="submitclearvip">取消VIP用户</button>
																<button class="btn btn-sm btn-primary" type="submit" name="submitNotExpired">设为永不到期</button>
																<button class="btn btn-sm btn-primary" type="submit" name="submitCancelNotExpired">取消永不到期</button>
																<button class="btn btn-sm btn-primary" type="submit" name="submitforbidden">取消授权</button>
																<button class="btn btn-sm btn-primary" type="submit" name="submitdel" onclick="return confirm('确定删除选中用户吗？')">删除</button>
																<button class="btn btn-sm btn-primary" type="submit" name="submitdelall" onclick="return confirm('确认删除所有已过期授权信息？')">清空过期用户</button>
															</div>
														</div>
													</td>
												</tr>
												<tr>
													<td colspan="12">
														<div class="input-group">
															<div class="input-group-btn">
																<input class="form-control" style="width: 85px;height: 30px;" type="text" name="marks" size="3" placeholder="备注">
																<button class="btn btn-sm btn-primary" type="submit" name="submitmodifymarks">修改备注</button>
															</div>
															<div class="input-group-btn">
																<input class="form-control" style="width: 85px;height: 30px;" type="text" name="exp" size="3" placeholder="天数">
																<button class="btn btn-sm btn-primary" type="submit" name="submitmodify">修改天数</button>
																<button class="btn btn-sm btn-primary" type="submit" name="submitadddays">增加天数</button>
															</div>
														</div>
													</td>
												</tr>
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