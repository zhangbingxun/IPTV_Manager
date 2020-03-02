<?php
//修改EPG数据
if ($_GET["act"]=="edit") { 
	$id=!empty($_GET["id"])?$_GET["id"]:exit("<script>javascript:alert('参数不能为空!');history.go(-1);</script>");
	//检查EPG是否存在
	$result=mysqli_query($GLOBALS['conn'],"select name,content,remarks from luo2888_epg where id=".$id);
	if (!mysqli_num_rows($result)) {
		mysqli_free_result($result);
		exit("<script>javascript:alert('EPG不存在!');self.location='epgadmin.php';</script>");
	}
	$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
	$content=$row["content"];
	$remarks=$row["remarks"];
	if(strstr($row["name"],"cntv") != false){
		$epgname = substr($row["name"], 5);
		$epgopt = '<option value="cntv" selected>CCTV官网</option><option value="jisu">极速数据</option><option value="tvmao">电视猫</option><option value="tvsou">搜视网</option><option value="51zmt">51zmt</option>';
	}else if(strstr($row["name"],"jisu") != false){
		$epgname = substr($row["name"], 5);
		$epgopt = '<option value="cntv">CCTV官网</option><option value="jisu" selected>极速数据</option><option value="tvmao">电视猫</option><option value="tvsou">搜视网</option><option value="51zmt">51zmt</option>';
	}else if(strstr($row["name"],"tvmao") != false){
		$epgname = substr($row["name"], 6);
		$epgopt = '<option value="cntv">CCTV官网</option><option value="jisu">极速数据</option><option value="tvmao"  selected>电视猫</option><option value="tvsou">搜视网</option><option value="51zmt">51zmt</option>';
	}else if(strstr($row["name"],"tvsou") != false){
		$epgname = substr($row["name"], 6);
		$epgopt = '<option value="cntv">CCTV官网</option><option value="jisu">极速数据</option><option value="tvmao">电视猫</option><option value="tvsou"  selected>搜视网</option><option value="51zmt">51zmt</option>';
	}else if(strstr($row["name"],"51zmt") != false){
		$epgname = substr($row["name"], 6);
		$epgopt = '<option value="cntv">CCTV官网</option><option value="jisu">极速数据</option><option value="tvmao">电视猫</option><option value="tvsou">搜视网</option><option value="51zmt"  selected>51zmt</option>';
	}
	unset($row);
	mysqli_free_result($result);
	//获取频道内容
	$sql="SELECT distinct name FROM luo2888_channels order by category,id";
	$result=mysqli_query($GLOBALS['conn'],$sql);
	if (!mysqli_num_rows($result)) {
		mysqli_free_result($result);
		exit("<script>javascript:alert('对不起，暂时没有节目信息，无法生成!');self.location=document.referrer;</script>");
	}
?>

<script type="text/javascript">
function quanxuan(a){
	var ck=document.getElementsByName("ids[]");
	for (var i = 0; i < ck.length; i++) {
		var tr=ck[i].parentNode.parentNode;
		if(a.checked){
			ck[i].checked=true;
		}else{
			ck[i].checked=false;
		}
	}
}
$("#listctr").hide();
</script>

	<table class="table table-bordered table-striped table-vcenter" align="center">
		<form method="post" action="?act=edits">
		<tr>
			<td>
				<div class="input-group">
					<div class="input-group-btn">
						<select class="form-control btn btn-default dropdown-toggle" style="width: 125px;" id="epg" name="epg" >
							<option value="">请选EPG来源</option>
							<?=$epgopt?>
						</select>&nbsp;&nbsp;
						<input class="form-control" type="text" name="name" style="width: 125px;" placeholder="EPG名称" value="<?php echo $epgname;?>">
						<input class="form-control" type="text" name="remarks" style="width: 125px;" placeholder="备注" value="<?php echo $remarks;?>">
						<input type="hidden" name="id" style="width: 0px;" value="<?php echo $id;?>">
						<button class="btn btn-default"  type="submit" name="bdpd" onclick="return confirm('自动绑定频道列表后,如果不准确请手动修改!!!')">搜索绑定频道</button>
						<button class="btn btn-default"  type="submit" name="qkbd" onclick="return confirm('确定要清空绑定的频道列表吗？')">清空绑定</button>
					</div>
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<p align="left"><input type="checkbox" onclick="quanxuan(this)">全选/反选</p>
				<?php
				while ($row=mysqli_fetch_array($result,MYSQLI_ASSOC)) {
					$channelname=$row["name"];
					$channelcategory=$row["category"];
					if(in_array($channelname,explode(',', $content))){
						echo "<div style=' float: left;background: #7fff00; margin-right: 3px; margin-bottom: 3px; padding: 2px 5px;'><input type='checkbox' value='".$channelname."' name='ids[]'  checked=\"checked\">".$channelname."</div>";
					}else {
						echo "&nbsp;&nbsp;<div style=' float: left;background: #E7E7E7; margin-right: 3px; margin-bottom: 3px; padding: 2px 5px;'><input type='checkbox' value='".$row["name"]."' name='ids[]' >".$row["name"]."</div>";
					}
					unset($channelname);
				}
				unset($row);
				mysqli_free_result($result);
				mysqli_close($GLOBALS['conn']);
				?>
			</td>
		</tr>
		<tr align="center">
			<td>
				<input type="submit" value="确认修改">
			</td>
		</tr>
		</form>
	</table>
<?php exit;}?>