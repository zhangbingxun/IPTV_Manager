<?php include_once "view.section.php";include_once "../apps/authorController.php"; ?>

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
						<div class="card-header"><h4>待授权列表</h4></div>
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
												<th><a href="?order=exp">状态</a></th>
												<th><a href="?order=lasttime">最后登陆</a></th>
											</tr>
											</thead>
											<tbody>
												<form method="POST">
													<?php
													$recStart=$recCounts*($page-1);
													$sql="select name,mac,deviceid,model,ip,region,lasttime,exp,status from luo2888_users where status=-1 or status=-999 or status=0 $searchparam order by $order desc limit $recStart,$recCounts";
													$result=mysqli_query($GLOBALS['conn'],$sql);
													while($row=mysqli_fetch_array($result)){
														$lasttime=date("Y-m-d H:i:s",$row['lasttime']);
														$name=$row['name'];
														$deviceid=$row['deviceid'];
														$mac=$row['mac'];
														$model=$row['model'];
														$ip=$row['ip'];
														$region=$row['region'];
														$status=$row['status'];
														$days=ceil(($row['exp']-time())/86400);
														if($days>0){$days='剩'."$days".'天';}else{$days="已到期";}
														if($status==0){$days="已禁用";}else if($status==-999){$days="永不到期";}
														if($needauthor==0){$days="关闭授权";}
														echo "<tr>
														<td><label class=\"lyear-checkbox checkbox-primary\">
														<input type=\"checkbox\" name=\"id[]\" value=\"$name\"><span></span>
														</label></td>
														<td>".$name."</td>
														<td>".$mac."</td>
														<td>".$deviceid."</td>
														<td>".$model."</td>
														<td>" .$ip ."</td>	
														<td>" .$region ."</td>
														<td>" .$days ."</td>
														<td>" . $lasttime ."</td>
														</tr>";
													}
													unset($row);
													mysqli_free_result($result);
													mysqli_close($GLOBALS['conn']);
													?>
													<div class="form-inline pull-left" >
														<tr>
															<td colspan="4">
																<div class="input-group">
																	<div class="input-group-btn">
																	<input class="form-control" style="width: 85px;height: 30px;" type="text" name="exp" size="3" placeholder="授权天数">
																	<button class="btn btn-sm btn-primary" type="submit" name="submitauthor">授权</button>
																	<button class="btn btn-sm btn-primary" type="submit" name="submitauthorforever">永久授权</button>
																	<button class="btn btn-sm btn-primary" type="submit" name="submitforbidden">禁止试用</button>
																	</div>
																</div>
															</td>
															<td colspan="5">
																<div class="form-group">
																	<label class="lyear-checkbox">
																		<input type="checkbox" name="submitauthorvip">
																		<span>同时设置为VIP</span>
																	</label>
																</div>
															</td>
														</tr>
														<tr>
															<td colspan="9">
																<button class="btn btn-sm btn-primary" type="submit" name="submitdel" onclick="return confirm('确定删除选中用户吗？')">删除记录</button>
																<button class="btn btn-sm btn-primary" type="submit" name="submitdelonedaybefor" onclick="return confirm('确认清空一天前待授权信息？')">清空一天前记录</button>
																<button class="btn btn-sm btn-primary" type="submit" name="submitdelall" onclick="return confirm('确认删除所有待授权信息？')">清空所有记录</button>
															</td>
														</tr>
													</div>
												</form>
											</tbody>
										</table>
									</div>
								</div>
								<nav>
									<ul class="pager">
										<li><a href="<?php if($page>1){$p=$page-1;}else{$p=1;} echo '?keywords='.$keywords.'&page='.$p.'&order='.$order?>">上一页</a></li>
										<li class="previous"><a href="<?php echo '?keywords='.$keywords.'&page=1&order='.$order?>"><span aria-hidden="true">&larr;</span> 首页</a></li>
										<li class="next"><a href="<?php echo '?keywords='.$keywords.'&page='.$pageCount.'&order='.$order?>">尾页 <span aria-hidden="true">&rarr;</span></a></li>
										<li><a href="<?php if($page<$pageCount){$p=$page+1;} else {$p=$page;} echo '?keywords='.$keywords.'&page='.$p.'&order='.$order?>">下一页</a></li>
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