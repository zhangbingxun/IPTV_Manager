<?php
require_once "../view.section.php";
require_once "../../controler/mealsadminController.php";
?>

<!--页面主要内容-->
<main class="lyear-layout-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4>套餐列表</h4>
                        <button type="button" class="btn btn-sm btn-primary pull-right" data-toggle="modal" data-target="#addmeal">增加</button>
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
                                                    名称
                                                </td>
                                                <td class="w-5">
                                                    状态
                                                </td>
                                                <td class="w-5">
                                                    公开销售
                                                </td>
                                                <td class="w-5">
                                                    金额
                                                </td>
                                                <td class="w-5">
                                                    期限
                                                </td>
                                                <td class="w-15">
                                                    收视内容
                                                </td>
                                                <td class="w-5">
                                                    操作
                                                </td>
                                            </tr>
                                            <tbody style="font-size:12px;font-weight: bold;">
<?php
//获取套餐数据显示
$result = $db->mQuery("select * from luo2888_meals");
if (!mysqli_num_rows($result)) {
    echo "<tr>";
    echo "<td colspan=\"5\" align=\"center\" style=\"font-size:14px;color:red;height:35px;font-weight: bold;\">当前未有套餐数据！";
    echo "</td>";
    echo "</tr>";
    mysqli_free_result($result);
}
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    if ($row["status"]) {
        $func = '<button type="submit" class="btn btn-xs btn-success" name="downline">上线</button>';
    } else {
        $func = '<button type="submit" class="btn btn-xs btn-danger" name="upline">下线</button>';
    }
    if ($row["days"] == 999) {
        $days = "永久授权";
    } else {
        $days = $row["days"] . "天";
    }
    if ($row["sale"] == 0) {
        $sale = "否";
    } else {
        $sale = "是";
    }
    echo '<form method="post">';
    echo '<tr>';
    echo '<input type="hidden" name="id" value="' . $row["id"] . '"/>';
    echo '<td align="center">' . $row["id"] . '</td>';
    echo '<td align="center">' . $row["name"] . '</td>';
    echo '<td align="center">' . $func . '</td>';
    echo '<td align="center">' . $sale . '</td>';
    echo '<td align="center">' . $row["amount"] . '元</td>';
    echo '<td align="center">' . $days . '</td>';
    echo '<td align="left">' . $row["content"] . '</td>';
    echo '<td align="center">';
    echo '<button type="button" class="btn btn-sm btn-cyan m-r-5" data-toggle="modal" data-target="#editmeal_' . $row["id"] . '">编辑</button>';
    echo '<button type="submit" class="btn btn-sm btn-danger" name="delmeal" onclick="return confirm(\'确认删除吗？\')">删除</button>';
    echo '</td>';
    echo '</tr>';
    echo '</form>';
    //获取套餐所有的收视内容
    $category=$db->mQuery("SELECT id,name from luo2888_category where type<>'web' and enable=1 ORDER BY id ASC");
    if (!mysqli_num_rows($category)) {
        mysqli_free_result($category);
        exit("<script>$.alert({title: '错误',content: '对不起，没有频道分类信息，无法生成套餐！',type: 'red',buttons: {confirm: {text: '确定',btnClass: 'btn-primary',action: function(){self.location='mealsadmin.php'}}}});</script>");
    }
    switch ($row["sale"]) {
        case '0':
            $salechecked = '';
            break;
        case '1':
            $salechecked = 'checked="checked"';
            break;
    }
    echo '<div class="modal fade" id="editmeal_' . $row["id"] . '" tabindex="-1" role="dialog">
									<div class="modal-dialog" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
												<h4 class="modal-title">编辑套餐</h4>
											</div>
											<form method="post">
										    <input type="hidden" name="id" value="' . $row["id"] . '"/>
												<div class="modal-body">
													<div class="form-group">
														<label for="recipient-name" class="control-label">名称：</label>
														<input type="text" class="form-control" name="name" value="' . $row["name"] . '">
													</div>
													<div class="form-group">
														<label for="recipient-name" class="control-label">金额：</label>
														<input type="text" class="form-control" name="amount" value="' . $row["amount"] . '">
													</div>
													<div class="form-group">
														<label class="control-label">期限：</label>
														<input type="text" class="form-control" name="days" value="' . $row["days"] . '">
													</div>
													<div class="form-group">
														<label class="control-label">公开销售：</label>
														<label class="lyear-checkbox checkbox-inline  checkbox-primary">
															<input type="checkbox" name="sale" ' . $salechecked . '>
															<span></span>
														</label>
													</div>
													<div class="form-group">
														<label class="lyear-checkbox m-b-10">
															<input type="checkbox" onclick="quanxuan(this)">
															<span>全选/反选</span>
														</label>
													</div>
													<div class="form-group">
														<label class="btn-block control-label">收视内容：</label>';
while ($arrays = mysqli_fetch_array($category, MYSQLI_ASSOC)) {
    $categoryname = $arrays["name"];
    if (strpos($row["content"], $categoryname) !== false) {
        echo "<label class=\"lyear-checkbox checkbox-inline\" style=\"margin: 5px 7px;\"><input type='checkbox' value='" . $categoryname . "' name='ids[]'  checked=\"checked\"><span>$categoryname</span></label>";
    } else {
        echo "<label class=\"lyear-checkbox checkbox-inline\" style=\"margin: 5px 7px;\"><input type='checkbox' value='" . $categoryname . "' name='ids[]' ><span>$categoryname</span></label>";
    }
}	
    echo '
													</div>
												</div>
												<div class="modal-footer">
													<button type="submit" class="btn btn-primary" name="editmeal">确定</button>
													<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
												</div>
											</form>
										</div>
									</div>
								</div>';
}
unset($row,$arrays,$categoryname);
mysqli_free_result($result,$category);
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
        <div class="modal fade" id="addmeal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">
                                &times;
                            </span>
                        </button>
                        <h4 class="modal-title">
                            增加套餐
                        </h4>
                    </div>
                    <form method="post">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="recipient-name" class="control-label">
                                    名称：
                                </label>
                                <input type="text" class="form-control" name="name" placeholder="请输入套餐名称">
                            </div>
                            <div class="form-group">
                                <p align="center" style="font-size:14px;font-weight:bold;color:red;">注：套餐数量建议不要超过20个！</p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" name="addmeal">
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