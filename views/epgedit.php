<?php
require_once "view.section.php";
require_once "../controler/epgadminController.php";

if (isset($_GET["id"])) {
    $id = !empty($_GET["id"]) ? $_GET["id"] : exit("<script>$.alert({title: '错误',content: '参数为空！',type: 'red',buttons: {confirm: {text: '确定',btnClass: 'btn-primary',action: function(){self.location='epgadmin.php';}}}});</script>");
    //检查EPG是否存在
    $result = $db->mQuery("select name,content,remarks from luo2888_epg where id=" . $id);
    if (!mysqli_num_rows($result)) {
        mysqli_free_result($result);
        exit("<script>$.alert({title: '错误',content: 'EPG不存在！',type: 'red',buttons: {confirm: {text: '确定',btnClass: 'btn-primary',action: function(){self.location='epgadmin.php';}}}});</script>");
    }
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $content = $row["content"];
    $remarks = $row["remarks"];
    if (strstr($row["name"], "cntv") != false) {
        $epgname = substr($row["name"], 5);
        $cntv = "selected";
    }
    else if (strstr($row["name"], "tvmao") != false) {
        $epgname = substr($row["name"], 6);
        $tvmao = "selected";
    }
    else if (strstr($row["name"], "tvsou") != false) {
        $epgname = substr($row["name"], 6);
        $tvsou = "selected";
    }
    else if (strstr($row["name"], "51zmt") != false) {
        $epgname = substr($row["name"], 6);
        $zmt = "selected";
    }
    unset($row);
    mysqli_free_result($result);
}
?>

<script type="text/javascript">
    function quanxuan(a) {
        var ck = document.getElementsByName("ids[]");
        for (var i = 0; i < ck.length; i++) {
            var tr = ck[i].parentNode.parentNode;
            if (a.checked) {
                ck[i].checked = true;
            } else {
                ck[i].checked = false;
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
                    <form method="POST">
                        <div class="card-header">
                            <h4>编辑EPG</h4>
                            <button class="btn btn-sm btn-danger pull-right"  type="submit" name="clearbind" onclick="return confirm('确定要清空绑定的频道列表吗？')">清空</button>
                            <button type="submit" name="editchannel" class="btn btn-sm btn-primary pull-right m-r-5">确认</button>
                            <button class="btn btn-sm btn-default pull-right m-r-5"  type="submit" name="bindchannel" onclick="return confirm('自动绑定频道列表后,如果不准确请手动修改!!!')">绑定</button>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane active">
                                <input type="hidden" name="id" style="width: 0px;" value="<?php echo $id;?>">
                                <div class="form-group">
                                    <label>EPG来源：</label>
                                    <select class="form-control btn btn-default dropdown-toggle w-80" id="epg" name="epg" >
                                        <option value="cntv" <?php echo $cntv; ?>>CCTV官网</option>
                                        <option value="tvmao" <?php echo $tvmao; ?>>电视猫</option>
                                        <option value="tvsou" <?php echo $tvsou; ?>>搜视网</option>
                                        <option value="51zmt" <?php echo $zmt; ?>>51zmt</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>EPG名称：</label>
                                    <input class="form-control w-80" style="display: inline;" type="text" name="name" placeholder="请输入EPG名称" value="<?php echo $epgname;?>">
                                </div>
                                <div class="form-group">
                                    <label>EPG备注：</label>
                                    <input class="form-control w-80" style="display: inline;" type="text" name="remarks" placeholder="请输入备注" value="<?php echo $remarks;?>">
                                </div>
                                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                        <label class="lyear-checkbox m-b-10">
                                            <input type="checkbox" onclick="quanxuan(this)">
                                            <span>全选/反选</span>
                                        </label>
<?php
//获取频道内容
$result = $db->mQuery("SELECT name FROM luo2888_category order by id");
if (!mysqli_num_rows($result)) {
    mysqli_free_result($result);
    exit("<script>$.alert({title: '错误',content: '对不起，无法生成频道信息！',type: 'red',buttons: {confirm: {text: '确定',btnClass: 'btn-primary',action: function(){self.location=document.referrer;}}}});</script>");
}
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    echo '<div class="panel panel-primary">';
    echo '<div class="panel-heading" role="tab" id="heading_' . $row["name"] . '" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_' . $row["name"] . '" aria-expanded="true" aria-controls="collapse_' . $row["name"] . '">';
    echo '<h4 class="panel-title">' . $row["name"] . '</h4>';
    echo '</div>';
    echo '<div id="collapse_' . $row["name"] . '" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading_' . $row["name"] . '">';
    echo '<div class="panel-body">';
    $channeldata = $db->mQuery("SELECT distinct name FROM luo2888_channels where category='" . $row["name"] . "'order by id");
    if (!mysqli_num_rows($channeldata)) {
        echo '<p align="center">该分类无频道</p>';
    }
    while ($channel = mysqli_fetch_array($channeldata, MYSQLI_ASSOC)) {
        $channelname = $channel["name"];
        if (in_array($channelname, explode(',', $content))) {
            echo "<label class=\"lyear-checkbox checkbox-inline\" style=\"margin: 5px 7px;\"><input type='checkbox' value='" . $channelname . "' name='ids[]'   checked=\"checked\"><span>$channelname</span></label>";
        } else {
            echo "<label class=\"lyear-checkbox checkbox-inline\" style=\"margin: 5px 7px;\"><input type='checkbox' value='" . $channelname . "' name='ids[]' ><span>$channelname</span></label>";
        }
        unset($channelname);
    }
    echo '</div>';
    echo '</div>';
    echo '</div>';
    unset($channel);
    mysqli_free_result($channeldata);
}
unset($row);
mysqli_free_result($result);
?>
                                </div>
                            </div>
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