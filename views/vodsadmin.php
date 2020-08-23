<?php
require_once "view.section.php";
require_once "../controler/vodsadminController.php";
?>
<script type="text/javascript">
    function submitForm() {
        var form = document.getElementById("recCounts");
        form.submit()
    }
</script>
<!--页面主要内容-->
<main class="lyear-layout-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4>点播API列表</h4>
                        <button type="button" class="btn btn-sm btn-primary pull-right" data-toggle="modal" data-target="#addvod">增加</button>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane active">
                            <div class="form-group">
                                <div class="table-responsive">
                                    <table class="table table-hover table-vcenter">
                                        <tbody>
                                            <tr align="center">
                                                <td class="w-5">
                                                    序号
                                                </td>
                                                <td class="w-10">
                                                    名称
                                                </td>
                                                <td class="w-5">
                                                    状态
                                                </td>
                                                <td class="w-10">
                                                    地址
                                                </td>
                                                <td class="w-10">
                                                    操作
                                                </td>
                                            </tr>
<?php
//获取接口数据显示
$result = $db->mQuery("select * from luo2888_vods");
if (!mysqli_num_rows($result)) {
    echo '<tr><td colspan="5" align="center" style="font-size:14px;color:red;height:35px;font-weight: bold;">当前未有点播接口！</td></tr>';
    mysqli_free_result($result);
}
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    if ($row["status"]) {
        $func = '<button type="submit" class="btn btn-sm btn-success" name="downline">上线</button>';
    } else {
        $func = '<button type="submit" class="btn btn-sm btn-danger" name="upline">下线</button>';
    }
    echo '<form method="post">';
    echo '<tr>';
    echo '<input type="hidden" name="id" value="' . $row["id"] . '"/>';
    echo '<td align="center">' . $row["id"] . '</td>';
    echo '<td align="center">' . $row["name"] . '</td>';
    echo '<td align="center">' . $func . '</td>';
    echo '<td align="left">' . $row["url"] . '</td>';
    echo '<td align="center">';
    echo '<button type="button" class="btn btn-sm btn-cyan m-r-5" data-toggle="modal" data-target="#editvod_' . $row["id"] . '">编辑</button>';
    echo '<button type="submit" class="btn btn-sm btn-danger" name="delete" onclick="return confirm(\'确认删除吗？\')">删除</button>';
    echo '</td>';
    echo '</tr>';
    echo '</form>';
    echo '<div class="modal fade" id="editvod_' . $row["id"] . '" tabindex="-1" role="dialog">
									<div class="modal-dialog" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
												<h4 class="modal-title">编辑接口</h4>
											</div>
											<form method="post">
										    <input type="hidden" name="id" value="' . $row["id"] . '"/>
												<div class="modal-body">
													<div class="form-group">
														<label for="recipient-name" class="control-label">名称：</label>
														<input type="text" class="form-control" name="name" value="' . $row["name"] . '">
													</div>
													<div class="form-group">
														<label for="recipient-name" class="control-label">链接：</label>
														<input type="text" class="form-control" name="url" value="' . $row["url"] . '">
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
    <div class="modal fade" id="addvod" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">增加接口</h4>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="recipient-name" class="control-label">名称：</label>
                            <input type="text" class="form-control" name="name" placeholder="请输入接口名称">
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="control-label">链接：</label>
                            <input class="form-control" name="url" placeholder="请输入接口链接"></input>
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
</main>
<!--End页面主要内容-->
</div>
</div>