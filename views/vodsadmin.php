<?php require_once "view.section.php";require_once "../controler/vodsadminController.php"; ?>

<script type="text/javascript">
function submitForm(){
    var form = document.getElementById("recCounts");
    form.submit();
}
</script>

<!--页面主要内容-->
<main class="lyear-layout-content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">
				<div class="card">
					<div class="card-header"><h4>点播接口列表</h4></div>
					<div class="tab-content">
						<div class="tab-pane active">
							<div class="form-group">
								<div class="table-responsive" >
									<?php require_once "mealsedit.php" ?>
									<table class="table table-hover table-vcenter">
										<tr align="center">
											<td colspan="5">
												<label class="control-label">接口管理：</label>
												<button class="btn btn-default" type="button" data-toggle="modal" data-target="#addvod">增加接口</button>
											</td>
										</tr>
										<tr align="center">
											<td class="w-5">接口编号</td>
											<td class="w-5">接口名称</td>
											<td class="w-5">接口状态</td>
											<td class="w-15">接口链接</td>
											<td class="w-5">操作</td>
										</tr>
										<tbody style="font-size:12px;font-weight: bold;">
											<?php
											//获取接口数据显示
											$result=$db->mQuery("select * from luo2888_vods");
											if (!mysqli_num_rows($result)) {
												echo"<tr>";
												echo"<td colspan=\"5\" align=\"center\" style=\"font-size:14px;color:red;height:35px;font-weight: bold;\">当前未有点播接口！";
												echo"</td>";
												echo"</tr>";
												mysqli_free_result($result);
											}
											while ($row=mysqli_fetch_array($result,MYSQLI_ASSOC)) {
												if ($row["enable"]) {
													$status="<font color=\"#33a996\">上线</font>";
													$func="<button type=\"button\" class=\"btn btn-sm btn-default\"><a href=\"?act=downline&id=".$row["id"]."\"><font color=\"red\">下线</font></a></button>";
												}else {
													$status="<font color=\"red\">下线</font>";
													$func="<button type=\"button\" class=\"btn btn-sm btn-default\"><a href=\"?act=online&id=".$row["id"]."\"><font color=\"#33a996\">上线</font></a></button>";
												}
												echo"<tr>";
												echo"<td align=\"center\" style=\"font-size:12px;height:35px;font-weight: bold;\">".$row["id"]."</td>";
												echo"<td align=\"center\" style=\"font-size:12px;font-weight: bold;\">".$row["name"]."</td>";
												echo"<td align=\"center\" style=\"font-size:12px;font-weight: bold;\">".$status."</td>";
												echo"<td align=\"left\" style=\"font-size:12px;font-weight: bold;\">".$row["url"]."</td>";
												echo"<td align=\"center\" style=\"font-size:12px;font-weight: bold;\">
												".$func."&nbsp;
												<button type=\"button\" class=\"btn btn-sm btn-default\" data-toggle=\"modal\" data-target=\"#editvod_".$row["id"]."\">编辑</button>&nbsp;
												<button type=\"button\" class=\"btn btn-sm btn-default\"><a href=\"?act=dels&id=".$row["id"]."\"><font color=\"#8E388E\">删除</font></a></button>
												</td>";
												echo"</tr>";
											?>
            <?php
            echo '<div class="modal fade" id="editvod_'.$row["id"].'" tabindex="-1" role="dialog">
									<div class="modal-dialog" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
												<h4 class="modal-title">编辑接口</h4>
											</div>
											<form method="post">
										    <input type="hidden" name="id" value="'.$row["id"].'"/>
												<div class="modal-body">
													<div class="form-group">
														<label for="recipient-name" class="control-label">名称：</label>
														<input type="text" class="form-control" name="name" value="'.$row["name"].'">
													</div>
													<div class="form-group">
														<label for="recipient-name" class="control-label">链接：</label>
														<input type="text" class="form-control" name="url" value="'.$row["url"].'">
													</div>
												</div>
												<div class="modal-footer">
													<button type="submit" class="btn btn-primary" name="submitvodedit">确定</button>
													<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
												</div>
											</form>
										</div>
									</div>
								</div>';
								}
								unset($row);
								mysqli_free_result($result);
            ?>
								<div class="modal fade" id="addvod" tabindex="-1" role="dialog">
									<div class="modal-dialog" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
												<h4 class="modal-title">新增接口</h4>
											</div>
											<form method="post">
												<div class="modal-body">
													<div class="form-group" id="snNumber">
														<label for="recipient-name" class="control-label">名称：</label>
														<input type="text" class="form-control" name="name" placeholder="请输入接口名称">
													</div>
													<div class="form-group">
														<label for="recipient-name" class="control-label">链接：</label>
														<input type="text" class="form-control" name="url" placeholder="请输入接口链接">
													</div>
												</div>
												<div class="modal-footer">
													<button type="submit" class="btn btn-primary" name="submitvod">确定</button>
													<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
												</div>
											</form>
										</div>
									</div>
								</div>
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