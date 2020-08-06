<?php require_once "view.section.php";require_once "../controler/serialadminController.php"; ?>

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
	function genserial(){
				var genseries = document.getElementById("genseries");
				var snNumber =document.getElementById("snNumber");
				var snCount =document.getElementById("snCount");
				if(genseries.checked){
						snNumber.style.display = "none"
						snCount.style.display = "block"
				}else{
						snNumber.style.display = "block"
						snCount.style.display = "none"
				} 
	}
	function copytoclip() {
		var ck=document.getElementsByName("id[]");
		var clipBoardContent="";
		for (var i = 0; i < ck.length; i++) {
			if(ck[i].checked){
				clipBoardContent+=ck[i].value+"\r\n";
			}

		}
		if (clipBoardContent === undefined || clipBoardContent.length == 0) {
			alert("请选择要复制的帐号");
			return false;
		}
		var oInput = document.createElement('textarea');
		oInput.value = clipBoardContent;
		document.body.appendChild(oInput);
    oInput.select();
    document.execCommand("Copy");
    oInput.className = 'oInput';
    document.body.removeChild(oInput);
    alert("选中的账号已复制到剪切板。");
	}
</script>
    
    <!--页面主要内容-->
	<main class="lyear-layout-content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12">
					<div class="card">
						<div class="card-header"><h4>账号</h4></div>
						<div class="card-toolbar clearfix">
							<div class="btn-block" >
								<label>账号总数：<?php echo $serialCount; ?></label>
								<label>今日授权：<?php echo $todayauthoruserCount; ?></label>
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
											<th class="w-5"><a href="?order=name">账号</a></th>
											<th class="w-10"><a href="?order=meal">套餐</a></th>
											<th class="w-5"><a href="?order=days">期限</a></th>
											<th class="w-15"><a href="?order=gentime">生成时间</a></th>
											<th class="w-5"><a href="?order=author">授权人</a></th>
											<th class="w-10"><a href="?order=marks">备注</a></th>
										</tr>
										</thead>
										<tbody>
											<form method="POST">
												<?php
													$recStart=$recCounts*($page-1);
													if($user=='admin'){
														$func="select * from luo2888_serialnum where 1 $searchparam order by $order limit $recStart,$recCounts";
													}else{
														$func="select * from luo2888_serialnum where author='$user' $searchparam order by $order limit $recStart,$recCounts";
													}
													$meals=$db->mQuery("select id,name from luo2888_meals where id<>1000");
													if (mysqli_num_rows($meals)) {
														$meals_arr = [];
														while ($row = mysqli_fetch_array($meals, MYSQLI_ASSOC)) {
															$meals_arr[$row["id"]] = $row["name"];
														} 
														unset($row);
														mysqli_free_result($meals);
													} 
													$result=$db->mQuery($func);
													if (mysqli_num_rows($result)) {
														while($row=mysqli_fetch_array($result)){
															$gentime=date("Y-m-d H:i:s",$row['gentime']);
															$days=$row['days'];
															$name=$row['name'];
															$marks=$row['marks'];
															$author=$row['author'];
															if (empty($meals_arr[$row["meal"]])) {
																$meal = $row["meals"];
															} else {
																$meal = $meals_arr[$row["meal"]];
															} 
															$days = $days . '天';
															if($days == 999){
																$days='永不到期';
															}
															echo "<tr class=\"h-5\">
																<td><label class=\"lyear-checkbox checkbox-primary\">
																<input type=\"checkbox\" name=\"id[]\" value=\"$name\"><span></span>
																</label></td>
																<td>$name</td>
																<td>$meal</td>
																<td>$days</td>
																<td>$gentime</td>
																<td>$author</td>
																<td>$marks</td>
																</tr>";
														}
														unset($row);
													}else {
													    echo "<tr><td align='center' colspan='12'><font color='red'>对不起，当前未有已生成的账号数据！</font></td></tr>";
													}
													mysqli_free_result($result);
																?>
												<div class="form-inline pull-left">
												<tr>
													<td colspan="12">
														<div class="input-group">
															<div class="input-group-btn">
																<select class="btn btn-sm btn-default dropdown-toggle" style="width: 115px;height: 30px;" name="meal_s">
																	<option value="">请选择套餐</option>
																	<?php 
																		foreach($meals_arr as $mealid => $mealname) {
																			echo "<option value=\"$mealid\">$mealname";
																		} 
																	?>
																</select>
																<button class="btn btn-sm btn-primary m-r-10" type="submit" name="e_meals">修改套餐</button>
																<input class="btn btn-default " style="width: 115px;height: 30px;" type="text" name="marks" size="3" placeholder="请输入备注">
																<button class="btn btn-sm btn-primary m-r-10" type="submit" name="submitmodifymarks">修改备注</button>
																<input class="btn btn-default " style="width: 85px;height: 30px;" type="text" name="days" size="3" placeholder="授权天数">
																<button class="btn btn-sm btn-primary m-r-10" type="submit" name="submitmodify">修改天数</button>
																<button class="btn btn-sm btn-primary m-r-10" type="button" data-toggle="modal" data-target="#addserial">增加账号</button>
																<button class="btn btn-sm btn-primary m-r-10" type="button" onclick="copytoclip()">复制账号</button>
																<button class="btn btn-sm btn-primary m-r-10" type="submit" name="submitNotExpired">设为永不到期</button>
																<button class="btn btn-sm btn-primary m-r-10" type="submit" name="submitdel" onclick="return confirm('确定删除选中用户吗？')">删除</button>
															</div>
														</div>
													</td>
												</tr>
												</div>
											</form>
										</tbody>
									</table>
								</div>
								<div class="modal fade" id="addserial" tabindex="-1" role="dialog">
									<div class="modal-dialog" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
												<h4 class="modal-title">生成账号</h4>
											</div>
											<form method="post">
												<div class="modal-body">
													<div class="form-group" id="snNumber">
														<label for="recipient-name" class="control-label">账号：</label>
														<input type="number" class="form-control" name="snNumber" placeholder="请输入要生成的账号">
													</div>
													<div class="form-group" id="snCount" style="display: none;">
														<label for="recipient-name" class="control-label">账号数量：</label>
														<input type="number" class="form-control" name="snCount" placeholder="请输入要生成的账号数量">
													</div>
													<div class="form-group">
														<label for="message-text" class="control-label">授权套餐：</label>
														<select class="form-control btn btn-default dropdown-toggle" name="meal_s">
															<option value="">请选择套餐</option>
															<?php 
																foreach($meals_arr as $mealid => $mealname) {
																	echo "<option value=\"$mealid\">$mealname";
																	} 
																unset($meals_arr);
															?>
														</select>
													</div>
													<div class="form-group">
														<label for="message-text" class="control-label">授权天数：</label>
														<input type="number" class="form-control" name="days" placeholder="请输入要授权的天数"></input>
													</div>
													<div class="form-group">
														<label for="message-text" class="control-label">备注：</label>
														<input class="form-control" name="marks" placeholder="请输入备注信息"></input>
													</div>
													<div class="form-group">
														<label class="control-label">批量生成：</label>
														<label class="lyear-checkbox checkbox-inline checkbox-primary">
															<input type="checkbox" id="genseries" onClick="genserial()">
															<span></span>
														</label>
													</div>
												</div>
												<div class="modal-footer">
													<button type="submit" class="btn btn-primary" name="submitserial">确定</button>
													<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
												</div>
											</form>
										</div>
									</div>
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