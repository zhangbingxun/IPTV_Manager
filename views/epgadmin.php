<?php
require_once "view.section.php";
require_once "../controler/epgadminController.php";
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
                        <h4>EPG列表</h4>
                        <button class="btn btn-sm btn-info pull-right" type="button" data-toggle="modal" data-target="#btncontrol">操作</button>
                        <button class="btn btn-sm btn-primary pull-right m-r-5" type="button" data-toggle="modal" data-target="#addchannel">增加</button>
                    </div>
                    <div id="listctr" class="card-toolbar clearfix">
                        <form class="pull-right search-bar" method="get" role="form">
                            <div class="input-group">
                                <div class="input-group-btn">
                                    <input class="form-control" style="width: 225px;" type="text" name="keywords" value="<?php echo $keywords;?>" placeholder="请输入名称">
                                    <button class="btn btn-default" type="submit" name="submitsearch">搜索</button>
                                </div>
                            </div>
                        </form>
                        <div class="toolbar-btn-action">
                            <form class="pull-left" method="POST" id="recCounts">
                                <label>每页</label>
                                <select class="btn btn-sm btn-default dropdown-toggle" id="sel" name="recCounts"
                                onchange="submitForm();">
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
                                </select>
                                <label>&nbsp;条</label>
                            </form>
                            <form class="pull-left" method="post">
                                <input type="text" name="jumpto" style="border-width: 0px;text-align: right;" size=2 value="<?php echo $page?>">/<?php echo $pageCount?>
                                <button class="btn btn-xs btn-default" type="submit">跳转</button>
                            </form>
                        </div>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane active">
                            <div class="form-group">
                                <div class="table-responsive">
                                        <table class="table table-hover table-vcenter" style="white-space:nowrap;word-break:keep-all;">
                                            <tr align="center">
                                                <td class="w-5">
                                                    #
                                                </td>
                                                <td class="w-10">
                                                    EPG名称
                                                </td>
                                                <td class="w-5">
                                                    备注
                                                </td>
                                                <td class="w-5">
                                                    来源
                                                </td>
                                                <td class="w-5">
                                                    状态
                                                </td>
                                                <td class="w-10">
                                                    绑定频道
                                                </td>
                                                <td class="w-5">
                                                    操作
                                                </td>
                                            </tr>
                                            <tbody style="font-size:12px;font-weight: bold;">
<?php
//获取EPG数据显示
$recStart=$recCounts*($page-1);
$result=$db->mQuery("select * from luo2888_epg $searchparam limit $recStart,$recCounts");
if (!mysqli_num_rows($result)) {
    echo"<tr>";
    echo"<td  colspan=\"7\" align=\"center\" style=\"font-size:14px;color:red;height:35px;font-weight: bold;\">当前未有EPG数据！";
    echo"</td>";
    echo"</tr>";
    mysqli_free_result($result);
}
while ($row=mysqli_fetch_array($result,MYSQLI_ASSOC)) {
    if ($row["status"]) {
        $func = '<button type="submit" class="btn btn-xs btn-success" name="downline">上线</button>';
    } else {
        $func = '<button type="submit" class="btn btn-xs btn-danger" name="upline">下线</button>';
    }
    $epg = explode("-",$row['name']);
    if($epg[0] == 'cntv'){
            $epgname = 'CCTV官网';
    }else if($epg[0] == 'tvmao'){
            $epgname = '电视猫';
    }else if($epg[0] == 'tvsou'){
            $epgname = '搜视网';
    }else if($epg[0] == '51zmt'){
            $epgname = '51zmt';
    }
    echo '<form method="post">';
    echo '<tr>';
    echo '<input type="hidden" name="id" value="' . $row["id"] . '"/>';
    echo '<td align="center">' . $row["id"] . '</td>';
    echo '<td align="center">' . $row["name"] . '</td>';
    echo '<td align="center"><font color="red">' . $row["remarks"] . '</font></td>';
    echo '<td align="center"><font color="red">' . $epgname . '</font></td>';
    echo '<td align="center">' . $func . '</td>';
    echo '<td align="left">' . $row["content"] . '</td>';
    echo '<td align="center">';
    echo '<a href="epgedit.php?id=' . $row["id"] . '"><button type="button" class="btn btn-sm btn-primary m-r-5">编辑</button></a>';
    echo '<button type="submit" class="btn btn-sm btn-danger" name="delchannel" onclick="return confirm(\'确认删除吗？\')">删除</button>';
    echo '</td>';
    echo"</tr>";
    echo '</form>';
}
unset($row);
mysqli_free_result($result);
?>
                                            </tbody>
                                        </table>
                                </div>
                            </div>
                            <nav>
                                <ul class="pager">
                                    <li>
                                        <a href="<?php if($page>1){$p=$page-1;}else{$p=1;} echo '?keywords='.$keywords.'&page='.$p?>">
                                            上一页
                                        </a>
                                    </li>
                                    <li class="previous">
                                        <a href="<?php echo '?keywords='.$keywords.'&page=1'?>">
                                            <span aria-hidden="true">
                                                &larr;
                                            </span>
                                            首页
                                        </a>
                                    </li>
                                    <li class="next">
                                        <a href="<?php echo '?keywords='.$keywords.'&page='.$pageCount?>">
                                            尾页
                                            <span aria-hidden="true">
                                                &rarr;
                                            </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php if($page<$pageCount){$p=$page+1;} else {$p=$page;} echo '?keywords='.$keywords.'&page='.$p?>">
                                            下一页
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addchannel" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">
                            &times;
                        </span>
                    </button>
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
                            <select class="form-control btn btn-default dropdown-toggle" id="epg"
                            name="epg">
                                <option value="">请选EPG来源</option>
                                <option value="cntv">CCTV官网</option>
                                <option value="tvmao">电视猫</option>
                                <option value="tvsou">搜视网</option>
                                <option value="51zmt">51zmt</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">新增</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="btncontrol" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">
                            &times;
                        </span>
                    </button>
                    <h4 class="modal-title">EPG操作</h4>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <div class="form-group">
                            <label class="btn-block control-label">操作：</label>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-cyan btn-block btn-lg" type="submit" name="bindchannel" onclick="return confirm('自动绑定频道列表后,如果不准确请手动修改!!!')">绑定频道</button>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-danger btn-block btn-lg" type="submit" name="clearbind" onclick="return confirm('确定要清空绑定的频道列表吗？')">清空绑定</button>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-danger btn-block btn-lg" type="submit" name="clearcache" onclick="return confirm('确定要清空EPG缓存吗？')">清空EPG缓存</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                </div>
            </div>
        </div>
    </div>
</main>
<!--End页面主要内容-->
</div>
</div>