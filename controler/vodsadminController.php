<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);

if ($user != 'admin') {
    exit("<script>$.alert({title: '警告',content: '你无权访问此页面。',type: 'orange',buttons: {confirm: {text: '确定',btnClass: 'btn-primary',action: function(){history.go(-1);}}}});</script>");
}

?>

<?php 
// 上线操作
if (isset($_POST['upline'])) {
    $id = !empty($_POST["id"])?$_POST["id"]:exit("<script>$.alert({title: '错误',content: '参数为空！',type: 'red',buttons: {confirm: {text: '确定',btnClass: 'btn-primary',action: function(){self.location=document.referrer;}}}});</script>");
    $db->mSet("luo2888_vods", "status=1", "where id=$id");
    exit("<script>$.alert({title: '成功',content: '接口编号 " . $id . " 已上线！',type: 'green',buttons: {confirm: {text: '确定',btnClass: 'btn-primary',action: function(){self.location=document.referrer;}}}});</script>");
} 

// 下线操作
if (isset($_POST['downline'])) {
    $id = !empty($_POST["id"])?$_POST["id"]:exit("<script>$.alert({title: '错误',content: '参数为空！',type: 'red',buttons: {confirm: {text: '确定',btnClass: 'btn-primary',action: function(){self.location=document.referrer;}}}});</script>");
    $db->mSet("luo2888_vods", "status=0", "where id=$id");
    exit("<script>$.alert({title: '成功',content: '接口编号 " . $id . " 已下线！',type: 'green',buttons: {confirm: {text: '确定',btnClass: 'btn-primary',action: function(){self.location=document.referrer;}}}});</script>");
} 

// 删除操作
if (isset($_POST['delete'])) {
    $id = !empty($_POST["id"])?$_POST["id"]:exit("<script>$.alert({title: '错误',content: '参数为空！',type: 'red',buttons: {confirm: {text: '确定',btnClass: 'btn-primary',action: function(){self.location=document.referrer;}}}});</script>");
    $db->mDel("luo2888_vods", "where id=$id");
    exit("<script>$.alert({title: '成功',content: '接口编号 " . $id . " 已删除！',type: 'green',buttons: {confirm: {text: '确定',btnClass: 'btn-primary',action: function(){self.location=document.referrer;}}}});</script>");
} 

// 新增接口数据
if (isset($_POST['submitvod'])) {
    $vod_name = !empty($_POST["name"])?$_POST["name"]:exit("<script>$.alert({title: '错误',content: '请填写接口名称！',type: 'red',buttons: {confirm: {text: '确定',btnClass: 'btn-primary',action: function(){self.location=document.referrer;}}}});</script>");
    $vod_url = !empty($_POST["url"])?$_POST["url"]:exit("<script>$.alert({title: '错误',content: '请填写接口名称！',type: 'red',buttons: {confirm: {text: '确定',btnClass: 'btn-primary',action: function(){self.location=document.referrer;}}}});</script>");
    $result = $db->mQuery("select * from luo2888_vods where name=" . "'" . $vod_name . "'"); 
    // 检测名称是否已经同名或存在
    if (mysqli_num_rows($result)) {
		     mysqli_free_result($result);
        exit("<script>$.alert({title: '错误',content: '接口名为 " . $vod_name . " 已存在，请不要重复新增！',type: 'red',buttons: {confirm: {text: '确定',btnClass: 'btn-primary',action: function(){self.location=document.referrer;}}}});</script>");
    } 
    $result = $db->mQuery("select * from luo2888_vods where url=" . "'" . $vod_url . "'"); 
    // 检测链接是否已经同名或存在
    if (mysqli_num_rows($result)) {
		     mysqli_free_result($result);
        exit("<script>$.alert({title: '错误',content: '接口链接： " . $vod_url . " 已存在，请不要重复新增！',type: 'red',buttons: {confirm: {text: '确定',btnClass: 'btn-primary',action: function(){self.location=document.referrer;}}}});</script>");
    } 
    // 新加接口数据
    $db->mInt("luo2888_vods", "name,url,status", "'$vod_name', '$vod_url', 1");
    exit("<script>$.alert({title: '成功',content: '接口 " . $vod_name . " 已增加！',type: 'green',buttons: {confirm: {text: '确定',btnClass: 'btn-primary',action: function(){self.location=document.referrer;}}}});</script>");
} 

// 修改接口数据
if (isset($_POST['submitvodedit'])) {
    $id = !empty($_POST["id"])?$_POST["id"]:exit("<script>$.alert({title: '错误',content: '参数为空！',type: 'red',buttons: {confirm: {text: '确定',btnClass: 'btn-primary',action: function(){self.location=document.referrer;}}}});</script>");
    $vod_name = !empty($_POST["name"])?$_POST["name"]:exit("<script>$.alert({title: '错误',content: '请填写接口名称！',type: 'red',buttons: {confirm: {text: '确定',btnClass: 'btn-primary',action: function(){self.location=document.referrer;}}}});</script>");
    $vod_url = !empty($_POST["url"])?$_POST["url"]:exit("<script>$.alert({title: '错误',content: '请填写接口名称！',type: 'red',buttons: {confirm: {text: '确定',btnClass: 'btn-primary',action: function(){self.location=document.referrer;}}}});</script>");
    $result = $db->mQuery("select * from luo2888_vods where name=" . "'" . $vod_name . "'"); 
    // 修改套餐数据
    $db->mSet("luo2888_vods", "name='$vod_name',url='$vod_url'", "where id=$id");
    exit("<script>$.alert({title: '成功',content: '接口 " . $vod_name . " 已修改！',type: 'green',buttons: {confirm: {text: '确定',btnClass: 'btn-primary',action: function(){self.location=document.referrer;}}}});</script>");
} 

?>