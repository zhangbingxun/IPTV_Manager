<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);

include_once "../config.php";

if ($_SESSION['epgadmin'] == 0) {
    echo"<script>alert('你无权访问此页面！');history.go(-1);</script>";
    exit();
} 

if (isset($_GET['keywords'])) {
    $keywords = $_GET['keywords'];
    $searchparam = "where name like '%$keywords%' or remarks like '%$keywords%' or content like '%$keywords%'";
} 

if (isset($_POST['recCounts'])) {
    $recCounts = $_POST['recCounts'];
    mysqli_query($GLOBALS['conn'], "update luo2888_admin set showcounts=$recCounts where name='$user'");
} 

if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
} 

$result = mysqli_query($GLOBALS['conn'], "select showcounts from luo2888_admin where name='$user'");
if ($row = mysqli_fetch_array($result)) {
    $recCounts = $row['showcounts'];
} else {
    $recCounts = 100;
} 

$result = mysqli_query($GLOBALS['conn'], "select count(*) from luo2888_epg");
if ($row = mysqli_fetch_array($result)) {
    $userCount = $row[0];
    $pageCount = ceil($row[0] / $recCounts);
} else {
    $userCount = 0;
    $pageCount = 1;
} 

if (isset($_POST['jumpto'])) {
    $p = $_POST['jumpto'];
    if (($p <= $pageCount) && ($p > 0)) {
        header("location:?page=$p");
    } 
} 

if (isset($_POST['qkbd'])) {
    $sql = "update luo2888_epg set content=null";
    if (isset($_POST['id'])) {
        $sql .= " where id=" . $_POST['id'];
    } 
    mysqli_query($GLOBALS['conn'], $sql);
    mysqli_close($GLOBALS['conn']);
    exit("<script>javascript:alert('清除绑定频道成功!');self.location=document.referrer;</script>");
} 

if (isset($_POST['bdpd'])) {
    $sql = "SELECT distinct name FROM luo2888_channels";
    $result = mysqli_query($GLOBALS['conn'], $sql);
    if (!mysqli_num_rows($result)) {
        mysqli_free_result($result);
        exit("<script>javascript:alert('对不起，暂时没有节目信息，无法匹配!');self.location=document.referrer;</script>");
    } while ($r = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $clist[] = $r;
    } 
    unset($r);
    mysqli_free_result($result);

    if (isset($_POST['id'])) {
        if (empty($_POST["remarks"])) {
            exit("<script>javascript:alert('对不起，备注信息不完，无法匹配!');self.location=document.referrer;</script>");
        } 
        foreach ($clist as $k => $v) {
            if (strstr($v['name'], $_POST['remarks']) !== false) {
                $list[$k] = $v['name'];
            } 
        } 
        $a = implode(",", array_unique($list));
        if (empty($a)) {
            exit("<script>javascript:alert('对不起，没有索引到频道列表!');self.location=document.referrer;</script>");
        } 
        mysqli_query($GLOBALS['conn'], "update luo2888_epg set content='$a' where id=" . $_POST['id']);
        mysqli_close($GLOBALS['conn']);
        exit("<script>javascript:alert('EPG信息匹配完成!');self.location=document.referrer;</script>");
    } 

    $sql = "select id,remarks,content from luo2888_epg where remarks != ''";
    $result = mysqli_query($GLOBALS['conn'], $sql);
    if (!mysqli_num_rows($result)) {
        mysqli_free_result($result);
        exit("<script>javascript:alert('对不起，暂时没有EPG信息，无法匹配!');self.location=document.referrer;</script>");
    } while ($r = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        foreach ($clist as $k => $v) {
            if (strstr($v['name'], $r['remarks']) !== false) {
                $list[$k] = $v['name'];
                $a = implode(",", array_unique($list));
                mysqli_query($GLOBALS['conn'], "update luo2888_epg set content='$a' where id=" . $r['id']);
            } 
        } 
        unset($list);
    } 
    unset($r);
    mysqli_free_result($result);
    mysqli_close($GLOBALS['conn']);
    exit("<script>javascript:alert('EPG信息匹配完成!');self.location=document.referrer;</script>");
} 
// 上线操作
if ($_GET["act"] == "online") {
    $id = !empty($_GET["id"])?$_GET["id"]:exit("<script>javascript:alert('参数不能为空!');history.go(-1);</script>");
    mysqli_query($GLOBALS['conn'], "update luo2888_epg set status=1 where id=" . $id);
    exit("<script>javascript:alert('EPG编号 " . $id . " 上线操作成功!');self.location=document.referrer;</script>");
} 
// 下线操作
if ($_GET["act"] == "downline") {
    $id = !empty($_GET["id"])?$_GET["id"]:exit("<script>javascript:alert('参数不能为空!');history.go(-1);</script>");
    mysqli_query($GLOBALS['conn'], "update luo2888_epg set status=0 where id=" . $id);
    exit("<script>javascript:alert('EPG编号 " . $id . " 下线操作成功!');self.location=document.referrer;</script>");
} 
// 删除操作
if ($_GET["act"] == "dels") {
    $id = !empty($_GET["id"])?$_GET["id"]:exit("<script>javascript:alert('参数不能为空!');history.go(-1);</script>");
    mysqli_query($GLOBALS['conn'], "delete from luo2888_epg  where id=" . $id);
    exit("<script>javascript:alert('EPG编号 " . $id . " 删除操作成功!');self.location=document.referrer;</script>");
} 
// 新增EPG数据
if ($_GET["act"] == "add") {
    $epg = !empty($_POST["epg"])?$_POST["epg"]:exit("<script>javascript:alert('请选择EPG来源!');history.go(-1);</script>");
    $name = !empty($_POST["name"])?$_POST["name"]:exit("<script>javascript:alert('请填写EPG名称!');history.go(-1);</script>");
    $remarks = $_POST["remarks"];
    $epg_name = $epg . '-' . $name;
    $result = mysqli_query($GLOBALS['conn'], "select * from luo2888_epg where name='" . $epg_name . "'"); 
    // EPG是否已经同名或存在
    if (mysqli_num_rows($result)) {
        mysqli_free_result($result);
        exit("<script>javascript:alert('EPG名为 " . $epg_name . " 已存在，请不要重复新增!');self.location=document.referrer;</script>");
    } 
    // 新加EPG数据
    mysqli_query($GLOBALS['conn'], "insert into luo2888_epg (name,remarks) values ('" . $epg_name . "','" . $remarks . "')");
    exit("<script>javascript:alert('新增加的EPG为 " . $epg_name . " 新增加成功!');self.location=document.referrer;</script>");
} 
// 修改EPG数据
if ($_GET["act"] == "edits") {
    $id = !empty($_POST["id"])?$_POST["id"]:exit("<script>javascript:alert('参数不能为空!');history.go(-1);</script>");
    $epg = !empty($_POST["epg"])?$_POST["epg"]:exit("<script>javascript:alert('请选择EPG来源!');history.go(-1);</script>");
    $name = !empty($_POST["name"])?$_POST["name"]:exit("<script>javascript:alert('请填写EPG名称!');history.go(-1);</script>");
    $epg_name = $epg . '-' . $name;
    $ids = implode(",", array_unique($_POST['ids']));
    $remarks = $_POST["remarks"];
    mysqli_query($GLOBALS['conn'], "update luo2888_epg set name='" . $epg_name . "',content='" . $ids . "',remarks='" . $remarks . "' where id=" . $id);
    exit("<script>javascript:alert('EPG名为 " . $epg_name . " 修改成功!');self.location=document.referrer;</script>");
} 
mysqli_free_result($result);
// 极速数据API
if (isset($_POST['submit']) && isset($_POST['jisuapi_key'])) {
    if (isset($_POST['epg_api_chk'])) {
        $epg_api_chk = 1;
    } else {
        $epg_api_chk = 0;
    } 
    $sql = "update luo2888_config set value='$epg_api_chk' where name='epg_api_chk'";
    mysqli_query($GLOBALS['conn'], $sql);
    $jisuapi_key = $_POST['jisuapi_key'];
    set_config('jisuapi_key', "$jisuapi_key");
    exit("<script>javascript:alert('设置已保存!');self.location=document.referrer;</script>");
} 
// 初始化
if (get_config('epg_api_chk') == 1) {
    $epg_api_chk = 'checked="checked"';
} else {
    $epg_api_chk = "";
} 
$jisuapi_key=get_config('jisuapi_key');
?>