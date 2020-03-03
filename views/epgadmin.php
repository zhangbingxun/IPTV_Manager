<?php include_once "view.section.php";include_once "../apps/epgadminController.php"; ?>

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
						<div class="card-header"><h4>EPG列表</h4></div>
						<div id="listctr" class="card-toolbar clearfix">
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
	                				<div class="table-responsive" >
	                					<?php include "epgedit.php" ?>
										<table class="table table-hover table-vcenter">
										    <tr>
												<td colspan="7">
													<form method="POST">
														<label class="control-label">操作：</label>
														<button class="btn btn-default" type="button" data-toggle="modal" data-target="#addepg">增加EPG</button>
													    <button class="btn btn-default" type="submit" name="bdpd" onclick="return confirm('自动绑定频道列表后,如果不准确请手动修改!!!')">绑定频道</button>
														<button class="btn btn-default" type="submit" name="qkbd" onclick="return confirm('确定要清空绑定的频道列表吗？')">清空绑定</button>
														<button class="btn btn-default" type="button" data-toggle="modal" data-target="#epgset">EPG接口设置</button>
													</form>
												</td>
											</tr>
											<tr align="center">
												<td class="w-5">#</td>
												<td class="w-10">EPG名称</td>
												<td class="w-5">备注</td>
												<td class="w-5">来源</td>
												<td class="w-5">状态</td>
												<td class="w-10">绑定频道</td>
												<td class="w-5">操作</td>
											</tr>
											<tbody style="font-size:12px;font-weight: bold;">
												<?php
												//获取EPG数据显示
												$recStart=$recCounts*($page-1);
												$result=mysqli_query($GLOBALS['conn'],"select * from luo2888_epg $searchparam limit $recStart,$recCounts");
												if (!mysqli_num_rows($result)) {
												    echo"<tr>";
													echo"<td align=\"center\" style=\"font-size:12px;color:red;height:35px;font-weight: bold;\">当前未有EPG数据！";
													echo"</td>";
													echo"</tr>";
													echo"</table></tr></div>";
													mysqli_free_result($result);
													mysqli_close($GLOBALS['conn']);
												}
												while ($r=mysqli_fetch_array($result,MYSQLI_ASSOC)) {
													if ($r["status"]) {
													    $stu="<font color=\"#33a996\">上线</font>";
														$stus="<a href=\"?act=downline&id=".$r["id"]."\"><font color=\"red\">下线</font></a>";
													}else {
													    $stu="<font color=\"red\">下线</font>";
														$stus="<a href=\"?act=online&id=".$r["id"]."\"><font color=\"#33a996\">上线</font></a>";
													}
													$epg = explode("-",$r['name']);
													if($epg[0] == 'cntv'){
															$epgname = 'CCTV官网';
													}else if($epg[0] == 'jisu'){
															$epgname = '极速数据';
													}else if($epg[0] == 'tvmao'){
															$epgname = '电视猫';
													}else if($epg[0] == 'tvsou'){
															$epgname = '搜视网';
													}else if($epg[0] == '51zmt'){
															$epgname = '51zmt';
													}
													 echo"<tr>";
													 echo"<td align=\"center\">".$r["id"]."</td>";
													 echo"<td align=\"center\">".$r["name"]."</td>";
													 echo"<td align=\"center\"><font color=\"red\">".$r["remarks"]."</font></td>";
													 echo"<td align=\"center\"><font color=\"red\">".$epgname."</font></td>";
													 echo"<td align=\"center\">".$stu."</td>";
													 echo"<td align=\"left\">".$r["content"]."</td>";
													 echo"<td align=\"center\">
													 ".$stus."&nbsp;
													 <a href=\"?act=edit&id=".$r["id"]."\">编辑</a>&nbsp;
													 <a href=\"?act=dels&id=".$r["id"]."\"><font color=\"#8E388E\">删除</font></a>
													 </td>";
													 echo"</tr>";
												}
												unset($r);
												mysqli_free_result($result);
												mysqli_close($GLOBALS['conn']);
												?>
											</tbody>
										</table>
										<div class="modal fade" id="addepg" tabindex="-1" role="dialog">
											<div class="modal-dialog" role="document">
												<div class="modal-content">
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
														<h4 class="modal-title">增加EPG</h4>
													</div>
													<form method="post" action="?act=add">
														<div class="modal-body">
															<div class="form-group">
																<label class="control-label">名称：</label>
																<input type="text" class="form-control" name="name" placeholder="请输入名称">
															</div>
															<div class="form-group">
																<label class="control-label">备注：</label>
																<input type="text" class="form-control" name="remarks" placeholder="请输入备注">
															</div>
															<div class="form-group">
																<label class="control-label">EPG来源：</label>
																<select class="form-control btn btn-default dropdown-toggle" id="epg" name="epg">
																	<option value="">请选EPG来源</option>
																	<option value="cntv">CCTV官网</option>
																	<option value="tvmao">电视猫</option>
																	<option value="tvsou">搜视网</option>
																	<option value="51zmt">51zmt</option>
																	<option value="jisu">极速数据</option>
																</select>
															</div>
														</div>
														<div class="modal-footer">
															<button class="btn btn-primary" type="submit" >新增</button>
															<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
														</div>
													</form>
												</div>
											</div>
										</div>
										<div class="modal fade" id="epgset" tabindex="-1" role="dialog">
											<div class="modal-dialog" role="document">
												<div class="modal-content">
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
														<h4 class="modal-title">EPG接口设置</h4>
													</div>
													<form method="post">
														<div class="modal-body">
															<div class="form-group">
																<label class="control-label">API_KEY：</label>
																<input type="text" class="form-control" name="jisuapi_key" value="<?php echo $jisuapi_key?>" placeholder="请输入极速数据API_KEY">
															</div>
															<div class="form-group">
																<label class="control-label">接口验证：</label>
																<label class="lyear-checkbox checkbox-inline checkbox-primary">
																	<input type="checkbox" name="epg_api_chk" onclick="alert('注意：非专用客户端打开后EPG会将不显示！');" <?php echo $epg_api_chk;?>>
																	<span></span>
																</label>
															</div>
														</div>
														<div class="modal-footer">
															<button class="btn btn-default" type="submit" name="submit">保存</button>
															<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
														</div>
													</form>
												</div>
											</div>
										</div>
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