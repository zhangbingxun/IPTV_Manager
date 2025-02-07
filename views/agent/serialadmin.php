<?php
require_once "../view.section.php";
require_once "../../controler/serialadminController.php";
?>

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
						<div class="card-header">
						    <h4>账号</h4>
						    <button class="btn btn-sm btn-primary pull-right" type="button" onclick="copytoclip()">复制账号</button>
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
											<th class="w-5"><a href="?order=days">期限</a></th>
											<th class="w-15"><a href="?order=gentime">生成时间</a></th>
										</tr>
										</thead>
										<tbody>
											<form method="POST">
												<?php
													$recStart=$recCounts*($page-1);
													$meals=$db->mQuery("select id,name from luo2888_meals where id<>1000");
													if (mysqli_num_rows($meals)) {
														$meals_arr = [];
														while ($row = mysqli_fetch_array($meals, MYSQLI_ASSOC)) {
															$meals_arr[$row["id"]] = $row["name"];
														} 
														unset($row);
														mysqli_free_result($meals);
													} 
													$result=$db->mQuery("select * from luo2888_serialnum where author='$user' $searchparam order by $order limit $recStart,$recCounts");
													if (mysqli_num_rows($result)) {
														while($row=mysqli_fetch_array($result)){
															$gentime=date("Y-m-d H:i:s",$row['gentime']);
															$days=$row['days'];
															$name=$row['name'];
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
																</tr>";
														}
														unset($row);
													}else {
													    echo "<tr><td align='center' colspan='5'><font color='red'>对不起，没有账号数据！</font></td></tr>";
													}
													mysqli_free_result($result);
												?>
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