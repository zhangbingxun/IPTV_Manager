<?php
require_once "../view.section.php";
require_once "../../controler/channeladminController.php"
?>

<script>
	function categorycheck(cname){
		$.get("../../controler/togglepdController.php?cname="+cname,function(data){$("#tip").html(data)});
	}
	function showlist(index){
		$("#srclist").val("正在加载中...");
		$("#srclist").load("../../controler/listController.php?category="+cname[index],function(data){
			$("#srclist").val(data);
		});
		$("#typename").val(cname[index]);
		$("#typename0").val(cname[index]);
		$("#typepass").val(cpass[index]);
		$("#category").val(cname[index]);
		$("#showindex").val(index);
		$("#showindextype").val(index);
		showindex=index;
		$.session.set("<?php echo '$showindex';?>",showindex);
	}
	if(showindex==-1) showindex=$.session.get("<?php echo '$showindex';?>");
</script>

<style type="text/css">
	#categorylist{padding-left: 0px;padding-top: 5px;}
	ul li{list-style: none}
</style>

<!--页面主要内容-->
<main class="lyear-layout-content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">
				<div class="card">
					<div class="card-header"><h4>频道设置</h4></div>
					<div class="tab-content">
						<div class="tab-pane active">
	                		<div class="table-responsive" >
								<table class="table table-bordered table-vcenter">
<tbody>
									<tr align="center">
										<td colspan="3">
											<form class="form-inline" method="post" id='autoupdate_form'>
											<label>列表设置：</label>
												<span>更新间隔</span>
												<input type="hidden" name="ver" value="<?php echo ($ver+1); ?>">
												<input type="text" class="form-control" name='updateinterval' style="display: inline;width: 15%;height: 20px;" value="<?php echo $updateinterval ?>" size="5"><span>&nbsp;秒</span>
												<button class="btn btn-xs btn-default" type="button" data-toggle="modal" data-target="#addlist">导入列表</button>
												<button class="btn btn-xs btn-default" type="submit" name="submit"/>保存设定</button>
											</form>
										</td>
									</tr>
    <tr align="center">
        <td class="w-5">
            分类
        </td>
        <td class="w-5">
            操作
        </td>
    </tr>
<?php
//获取接口数据显示
$result = $db->mQuery("SELECT * from luo2888_category where type='$categorytype' and url is not null");
if (!mysqli_num_rows($result)) {
    echo '<tr><td colspan="3" align="center" style="font-size:14px;color:red;height:35px;font-weight: bold;">当前未有外部列表！</td></tr>';
    mysqli_free_result($result);
}
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    echo '<form method="post">';
    echo '<tr>';
    echo '<input type="hidden" name="id" value="' . $row["id"] . '"/>';
    echo '<td align="center">' . $row["name"] . '</td>';
    echo '<td align="center">';
    echo '<button type="button" class="btn btn-sm btn-cyan m-r-5" data-toggle="modal" data-target="#editlist_' . $row["id"] . '">编辑</button>';
    echo '<input type="hidden" name="category" value="' . $row["name"] . '"/>';
    echo '<button type="submit" class="btn btn-sm btn-success" id="updatelist" name="updatelist">更新</button>';
    echo '</td>';
    echo '</tr>';
    echo '</form>';
    echo '<div class="modal fade" id="editlist_' . $row["id"] . '" tabindex="-1" role="dialog">
									<div class="modal-dialog" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
												<h4 class="modal-title">编辑外部列表</h4>
											</div>
											<form method="post">
												<div class="modal-body">
													<div class="form-group">
														<label for="recipient-name" class="control-label">分类名称：</label>
														<input type="text" class="form-control" name="thirdlistcategory" value="' . $row["name"] . '">
													</div>
													<div class="form-group">
														<label for="recipient-name" class="control-label">列表链接：</label>
														<input type="text" class="form-control" name="thirdlisturl" value="' . $row["url"] . '">
													</div>
												</div>
												<div class="modal-footer">
													<button type="submit" class="btn btn-primary" id="editlist" name="editlist">确定</button>
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
</tbody>
								<div class="modal fade" id="addlist" tabindex="-1" role="dialog">
									<div class="modal-dialog" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
												<h4 class="modal-title">增加外部列表</h4>
											</div>
											<form method="post">
												<div class="modal-body">
													<div class="form-group">
														<label for="recipient-name" class="control-label">分类名称：</label>
														<input type="text" class="form-control" name="thirdlistcategory" placeholder="分类名称">
													</div>
													<div class="form-group">
														<label for="message-text" class="control-label">列表链接：</label>
														<input class="form-control" name="thirdlisturl" placeholder="请输入列表链接"></input>
													</div>
												</div>
												<div class="modal-footer">
													<button type="submit" class="btn btn-primary" id="addthirdlist" name="addthirdlist">确定</button>
													<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
												</div>
											</form>
										</div>
									</div>
								</div>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="card">
					<div class="card-header">
						<h4>
							<?php
							if (strpos($_SERVER['REQUEST_URI'],'web') !== false){ echo '网页端频道'; }
							else if (strpos($_SERVER['REQUEST_URI'],'default') !== false){ echo '默认频道'; }
							else if (strpos($_SERVER['REQUEST_URI'],'province') !== false){ echo '省份频道'; }
							else if (strpos($_SERVER['REQUEST_URI'],'chinanet') !== false){ echo '电信频道'; }
							else if (strpos($_SERVER['REQUEST_URI'],'unicom') !== false){ echo '联通频道'; }
							else if (strpos($_SERVER['REQUEST_URI'],'cmcc') !== false){ echo '移动频道'; }
							else if (strpos($_SERVER['REQUEST_URI'],'vip') !== false){ echo '会员频道'; }
							?>
						</h4>
					</div>
            			<div class="card-body">
	                		<div class="table-responsive" >
								<table class="table table-bordered table-vcenter" style="min-width:750px;">
										    <tr>
											    <td colspan="5">
							<form class="form-inline" method="post" style="padding: 0 15px 0 15px;">
								<label class="control-label">分类管理：</label>
								<div class="input-group">
									<div class="input-group-btn">
										<input type="hidden" id="showindextype" name="showindex" value=""/>
										<input type="hidden" id="typename0" name="typename0" value=""/>
										<input class="form-control" style="width: 108px;height: 30px;" id="typename" type="text" size="10" name="category" value="<?PHP echo $category?>" placeholder="分类名称"/>
										<input class="form-control" style="width: 115px;height: 30px;" id="typepass" type="text" size="10" name="cpass" value="<?PHP echo $cpass?>" placeholder="分类密码"/>
										<button class="btn btn-sm btn-default" type="submit" name="submit">增加分类</button>
										<button class="btn btn-sm btn-default" type="submit" name="submit_deltype" onclick="return confirm('确认删除频道分类吗？')">删除分类</button>
										<button class="btn btn-sm btn-default" type="submit" name="submit_modifytype" onclick="return confirm('确认修改频道分类吗？')">修改分类</button>
										<button class="btn btn-sm btn-default" type="submit" name="submit_moveup">上移分类</button>
										<button class="btn btn-sm btn-default" type="submit" name="submit_movedown">下移分类</button>
										<button class="btn btn-sm btn-default" type="submit" name="submit_movetop">移至最上</button>
									</div>
								</div>
							</form>
							</td>
							</tr>
							<tr>
							<td align="center" valign="top" style="float: left;padding: 40px 0 0 0;border-width: 0px;height: 800px;overflow:auto;">
								<div id="tip"></div>
								<script type="text/javascript">
									var cname=[];
									var cpass=[];
								</script>
								<div class="btn-group-vertical" style="padding-left: 15px;">
									<label class="btn-block">分类列表</label>
									<?php
										$func = "SELECT name,psw,enable FROM luo2888_category where type='$categorytype' order by id";
										$result = $db->mQuery($func);
										$index=0;
										while($row = mysqli_fetch_array($result)) {
											$cname=$row['name'];
											$enable=$row['enable'];
											$cpass=$row['psw'];
											if($enable==1){
												$check='checked=checked';
											}else{
												$check='';
											}
											if($cpass==''){
												$lockimg='';
											}else{
												$lockimg='*';
											}
											echo "<script>cname[$index]='$cname';cpass[$index]='$cpass';</script>";
											echo "
												<button id=\"categorylist\" class=\"btn btn-default\" onclick=\"showlist($index)\">
													<div class='categorylist' style='text-align:left;padding: 5px;'>
														<label class=\"lyear-checkbox checkbox-inline checkbox-cyan\">
															<input type=\"checkbox\" $check onclick='categorycheck(\"$cname\")'>
															<span></span>
														</label>				
														$cname $lockimg 
													</div>
												</button>";
											$index++;
										}
										unset($row);
										mysqli_free_result($result);
									?>
								</div>
							</td>
							<td align="center" valign="top" style="padding-top: 5px;width: 100%;">
								<form method="post">
									<div class="input-group">
										<div class="input-group-btn">
											<div class="col-xs-12" style="padding: 15px;">
												<button class="btn btn-sm btn-default" id="updatesrc" style="width:100%;" type="submit" name="submit">保存</button>
											</div>
											<input type="hidden" id="category" name="category" value=""/>
											<input type="hidden" id="showindex" name="showindex" value=""/>
											<div class="col-xs-12">
												<textarea class="form-control" id="srclist" name="srclist" rows="35" placeholder="节目列表"></textarea>
											</div>
										</div>
									</div>
								</form>
							</td>
							</tr>
						</table>
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

<script type="text/javascript">
	showlist(showindex);
	$('#editlist').on('click', function(){
	    lightyear.loading('show');
	});
	$('#updatelist').on('click', function(){
	    lightyear.loading('show');
	});
	$('#updatesrc').on('click', function(){
	    lightyear.loading('show');
	});
	$('#addthirdlist').on('click', function(){
	    lightyear.loading('show');
	});
	$('.channeladmin').toggleClass( 'open' );
</script>