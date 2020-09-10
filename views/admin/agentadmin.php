<?php
require_once "../view.section.php";
require_once "../../controler/agentadminController.php";
?>

<!--页面主要内容-->
<main class="lyear-layout-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4>代理商列表</h4>
                        <button type="button" class="btn btn-sm btn-primary pull-right" data-toggle="modal" data-target="#addagent">增加</button>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane active">
                            <div class="form-group">
                                <div class="table-responsive">
                                        <table class="table table-hover table-vcenter" style="white-space:nowrap;word-break:keep-all;">
                                            <tr align="center">
                                                <td class="w-5">
                                                    编号
                                                </td>
                                                <td class="w-5">
                                                    用户名
                                                </td>
                                                <td class="w-5">
                                                    用户数量
                                                </td>
                                                <td class="w-5">
                                                    剩余账号
                                                </td>
                                                <td class="w-5">
                                                    滚动公告
                                                </td>
                                                <td class="w-5">
                                                    预留信息
                                                </td>
                                                <td class="w-5">
                                                    操作
                                                </td>
                                            </tr>
                                            <tbody style="font-size:12px;font-weight: bold;">
<?php
//获取套餐数据显示
$result = $db->mQuery("select * from luo2888_agents order by id");
if (!mysqli_num_rows($result)) {
    echo "<tr>";
    echo "<td colspan=\"7\" align=\"center\" style=\"font-size:14px;color:red;height:35px;font-weight: bold;\">当前未有代理商！";
    echo "</td>";
    echo "</tr>";
    mysqli_free_result($result);
}
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    echo '<form method="post">';
    echo '<tr>';
    echo '<input type="hidden" name="id" value="' . $row["id"] . '"/>';
    echo '<td align="center">' . $row["id"] . '</td>';
    if ($usercount = $db->mGetRow("luo2888_users", "count(*)", "where author='" . $row["id"] . "'")) {
        $usernum = $usercount[0];
    } else {
        $usernum = 0;
    } 
    if ($serialcount = $db->mGetRow("luo2888_serialnum", "count(*)", "where author='" . $row["id"] . "'")) {
        $serialnum = $serialcount[0];
    } else {
        $serialnum = 0;
    } 
    echo '<td align="center">' . $row["name"] . '</td>';
    echo '<td align="center">' . $usernum . '</td>';
    echo '<td align="center">' . $serialnum . '</td>';
    echo '<td align="center">' . $row["adtext"] . '</td>';
    echo '<td align="center">' . $row["adinfo"] . '</td>';
    echo '<td align="center">';
    echo '<button type="button" class="btn btn-sm btn-cyan m-r-5" data-toggle="modal" data-target="#editagentinfo_' . $row["id"] . '">编辑</button>';
    echo '<button type="button" class="btn btn-sm btn-cyan m-r-5" data-toggle="modal" data-target="#editagentpass_' . $row["id"] . '">修改密码</button>';
    echo '<button type="submit" class="btn btn-sm btn-danger" name="delagent" onclick="return confirm(\'确认删除吗？\')">删除</button>';
    echo '</td>';
    echo '</tr>';
    echo '</form>';
    echo '<div class="modal fade" id="editagentinfo_' . $row["id"] . '" tabindex="-1" role="dialog">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title">编辑代理商</h4>
						</div>
						<form method="post">
					        <input type="hidden" name="id" value="' . $row["id"] . '"/>
							<div class="modal-body">
								<div class="form-group">
									<label class="control-label">编号：</label>
									<input type="text" class="form-control" value="' . $row["id"] . '" disabled>
								</div>
								<div class="form-group">
									<label class="control-label">用户名：</label>
									<input type="text" class="form-control" name="name" value="' . $row["name"] . '">
								</div>
								<div class="form-group">
									<label class="control-label">滚动公告：</label>
									<textarea class="form-control" rows="5" name="adtext" placeholder="请输入公告内容" >' . $row["adtext"] . '</textarea>
								</div>
								<div class="form-group">
									<label class="control-label">预留信息：</label>
									<textarea class="form-control" rows="5" name="adinfo" placeholder="请输入预留信息" >' . $row["adinfo"] . '</textarea>
								</div>
							</div>
							<div class="modal-footer">
								<button type="submit" class="btn btn-primary" name="submit">确定</button>
								<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
							</div>
						</form>
					</div>
				</div>
			</div>';
    echo '<div class="modal fade" id="editagentpass_' . $row["id"] . '" tabindex="-1" role="dialog">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title">修改代理商密码</h4>
						</div>
						<form method="post">
					        <input type="hidden" name="id" value="' . $row["id"] . '"/>
							<div class="modal-body">
								<div class="form-group">
									<label class="control-label">新密码：</label>
									<input type="password" class="form-control" name="newpass" placeholder="请输入新密码">
								</div>
								<div class="form-group">
									<label class="control-label">确认新密码：</label>
									<input type="password" class="form-control" name="newpass_confirm" placeholder="请确认新密码">
								</div>
							</div>
							<div class="modal-footer">
								<button type="submit" class="btn btn-primary" name="submit">确定</button>
								<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
							</div>
						</form>
					</div>
				</div>
			</div>';
}
unset($row,$usercount,$serialcount);
mysqli_free_result($result);
?>
                                            </tbody>
                                        </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="addagent" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">
                                &times;
                            </span>
                        </button>
                        <h4 class="modal-title">
                            增加代理商
                        </h4>
                    </div>
                    <form method="post">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="recipient-name" class="control-label">
                                    代理商编号：
                                </label>
                                <div class="input-group m-b-10">
                                    <span class="input-group-addon">A</span>
                                    <input type="text" class="form-control" name="id" placeholder="请输入代理商编号">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" name="addagent">
                                确定
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                关闭
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<!--End 页面主要内容-->
</div>
</div>