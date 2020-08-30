<?php
require_once "view.section.php";
require_once "../controler/epgadminController.php";
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
                    <div class="card-header">
                        <h4>编辑EPG</h4>
                        <button type="submit" class="btn btn-sm btn-primary pull-right" name="submit">确认</button>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane active">
                            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                    <label class="lyear-checkbox m-b-10">
                                        <input type="checkbox" onclick="quanxuan(this)">
                                        <span>全选/反选</span>
                                    </label>
<?php
//获取频道内容
$result=$db->mQuery("SELECT distinct category FROM luo2888_channels order by id");
if (!mysqli_num_rows($result)) {
	mysqli_free_result($result);
	exit("<script>$.alert({title: '错误',content: '对不起，无法生成频道信息！',type: 'red',buttons: {confirm: {text: '确定',btnClass: 'btn-primary',action: function(){self.location=document.referrer;}}}});</script>");
}
while ($row=mysqli_fetch_array($result,MYSQLI_ASSOC)) {
    echo '<div class="panel panel-primary">';
  	echo '<div class="panel-heading" role="tab" id="heading_'.$row["category"].'">';
  	echo '<h4 class="panel-title">';
  	echo '<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_'.$row["category"].'" aria-expanded="true" aria-controls="collapse_'.$row["category"].'">'.$row["category"].'</a>';
  	echo '</h4>';
  	echo '</div>';
    echo '<div id="collapse_'.$row["category"].'" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading_'.$row["category"].'">';
    echo '<div class="panel-body">';
    $channeldata=$db->mQuery("SELECT distinct id,name FROM luo2888_channels where category='" . $row["category"] . "'order by id");
    while ($channel=mysqli_fetch_array($channeldata,MYSQLI_ASSOC)) {
    	$channelname=$channel["name"];
    	if(in_array($channelname,explode(',', $content))){
        echo "<label class=\"lyear-checkbox checkbox-inline\" style=\"margin: 5px 7px;\"><input type='checkbox' value='" . $channelname . "' name='ids[]'   checked=\"checked\"><span>$channelname</span></label>";
    	}else {
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
                </div>
            </div>
        </div>
    </div>
</main>
<!--End 页面主要内容-->
</div>
</div>