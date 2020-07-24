<?php require_once "view.section.php";require_once "../controler/ordersController.php"; ?>

<script type="text/javascript">
	function submitForm(){
		var form = document.getElementById("recCounts");
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
						<div class="card-header"><h4>支付订单列表</h4></div>
						<div class="card-toolbar clearfix">
							<div class="btn-block" >
								<label>订单总数：<?php echo $ordersCount; ?></label>
								<label>今日订单：<?php echo $todayordersCount; ?></label>
							</div>
							<form class="pull-right search-bar" method="GET">
								<div class="input-group">
									<div class="input-group-btn">
										<input class="form-control" style="width: 225px;" type="text" name="keywords" value="<?php echo $keywords;?>" placeholder="请输入名称">
										<button class="btn btn-default" type="submit">搜索</button>
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
									<button class="btn btn-xs btn-default" type="submit">跳转</button>
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
											<th class="w-1">
												<label class="lyear-checkbox checkbox-primary">
													<input type="checkbox" onclick="quanxuan(this)">
													<span></span>
												</label>
											</th>
											<th class="w-10"><a href="?order=userid">账号</a></th>
											<th class="w-15"><a href="?order=order_id">订单编号</a></th>
											<th class="w-10"><a href="?order=meal">购买套餐</a></th>
											<th class="w-10"><a href="?order=days">套餐期限</a></th>
											<th class="w-15"><a href="?order=ordertime">下单时间</a></th>
											<th class="w-15"><a href="?order=paidtime">支付时间</a></th>
											<th class="w-15"><a href="?order=expiredtime">到期时间</a></th>
											<th class="w-10"><a href="?order=status">支付状态</a></th>
										</tr>
										</thead>
										<tbody>
											<form method="POST">
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
													$func="select * from luo2888_payment where 1 $searchparam order by $order limit $recStart,$recCounts";
													$result=$db->mQuery($func);
													if (mysqli_num_rows($result)) {
														while($row=mysqli_fetch_array($result)){
															$userid=$row['userid'];
															$order_id=$row['order_id'];
															$days=$row['days'];
															$status=$row['status'];
															if (!empty($meals_arr[$row["meal"]])) {
																$meal = $meals_arr[$row["meal"]];
															}
															$ordertime = date("Y-m-d H:i:s",$row['ordertime']);
															$paidtime = date("Y-m-d H:i:s",$row['paidtime']);
															$expiredday = date("Y-m-d",$row['expiredtime']);
															if($days == 999){
																$days = "永久授权";
															} else {
																$days = $days . "天";
															}
															if($status == 1){
																$payst="已支付";
															} elseif ($status == 0) {
																$payst='未支付';
															}
															echo "<tr class=\"h-5\">
																<td><label class=\"lyear-checkbox checkbox-primary\">
																<input type=\"checkbox\" name=\"id[]\" value=\"$order_id\"><span></span>
																</label></td>
																<td><a href='useradmin.php?keywords=$userid'>$userid</a></td>
																<td>$order_id</td>
																<td>$meal</td>
																<td>$days</td>
																<td>$ordertime</td>
																<td>$paidtime</td>
																<td>$expiredday</td>
																<td>$payst</td>
																</tr>";
														}
														unset($row);
													}else {
													    echo "<tr><td align='center' colspan='12'><font color='red'>对不起，当前未有订单数据！</font></td></tr>";
													}
													mysqli_free_result($result);
																?>
												<div class="form-inline pull-left">
												<tr>
													<td colspan="12">
														<div class="input-group">
															<div class="input-group-btn">
																<button class="btn btn-sm btn-danger m-r-10" type="submit" name="submitdel" onclick="return confirm('确定删除选中订单吗？')">删除订单</button>
																<button class="btn btn-sm btn-danger m-r-10" type="submit" name="submitdelall" onclick="return confirm('确认删除所有订单？')">删除所有订单</button>
																<button class="btn btn-sm btn-danger m-r-10" type="submit" name="submitdelunpaid" onclick="return confirm('确认删除所有未支付订单？')">删除所有未支付订单</button>
															</div>
														</div>
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