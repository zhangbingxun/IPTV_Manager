<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);

if ($user != $admin) {
    exit("<script>$.alert({title: '警告',content: '你无权访问此页面。',type: 'orange',buttons: {confirm: {text: '确定',btnClass: 'btn-primary',action: function(){history.go(-1);}}}});</script>");
}

?>

<?php

// 删除所有订单
if (isset($_POST['submitdelall'])) {
    $db->mDel("luo2888_payment");
    echo("<script>lightyear.notify('已删除所有订单！', 'success', 3000);</script>");
} 

// 删除所有未支付订单
if (isset($_POST['submitdelunpaid'])) {
    $db->mDel("luo2888_payment", "where status=0");
    echo("<script>lightyear.notify('已删除所有未支付订单！', 'success', 3000);</script>");
} 

// 删除订单
if (isset($_POST['submitdel'])) {
    if (empty($_POST['id'])) {
        echo("<script>lightyear.notify('请选择要删除的订单！', 'danger', 3000);</script>");
    } else {
        foreach ($_POST['id'] as $id) {
            $db->mDel("luo2888_payment", "where order_id='$id'");
        } 
        echo("<script>lightyear.notify('选中订单已删除！', 'success', 3000);</script>");
    } 
} 

// 设置每页显示数量
if (isset($_POST['recCounts'])) {
    $recCounts = $_POST['recCounts'];
    $db->mSet("luo2888_config", "value=$recCounts", "where name='admin_showcounts'");
} 

// 获取每页显示数量
$recCounts = $db->mGet("luo2888_config", "value", "where name='admin_showcounts'");

// 搜索关键字
if (isset($_GET['keywords'])) {
    $keywords = trim($_GET['keywords']);
    $searchparam = "and (userid like '%$keywords%' or order_id like '%$keywords%')";
} 
$keywords = trim($_GET['keywords']);

// 获取当前页
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
} 

// 获取排序依据
if (isset($_GET['order'])) {
    $order = $_GET['order'];
} else {
    $order = 'ordertime desc';
} 

// 获取订单总数并根据每页显示数量计算页数
if ($row = $db->mGetRow("luo2888_payment", "count(*)")) {
    $ordersCount = $row[0];
    $pageCount = ceil($row[0] / $recCounts);
} else {
    $ordersCount = 0;
    $pageCount = 1;
} 
unset($row);

// 处理跳转逻辑
if (isset($_POST['jumpto'])) {
    $p = $_POST['jumpto'];
    if (($p <= $pageCount) && ($p > 0)) {
        echo "<script language=JavaScript>location.href='ordersadmin.php' + '?page=$p&order=$order';</script>";
    } 
} 

// 获取当天订单总数
$todayTime = strtotime(date("Y-m-d"), time());
if ($row = $db->mGetRow("luo2888_payment", "count(*)", "where ordertime>$todayTime")) {
    $todayordersCount = $row[0];
} else {
    $todayordersCount = 0;
} 
unset($row);

?>