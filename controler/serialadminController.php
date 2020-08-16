<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);

if ($user != 'admin') {
    exit("<script>$.alert({title: '警告',content: '你无权访问此页面。',type: 'orange',buttons: {confirm: {text: '确定',btnClass: 'btn-primary',action: function(){history.go(-1);}}}});</script>");
}

?>

<?php

// 生成随机账号
function genName() {
    global $db;    
    $name = rand(1000, 999999);
    $users = $db->mCheckOne("luo2888_users", "*", "where name=$name");
    $serial = $db->mCheckOne("luo2888_serialnum", "*", "where name=$name");
    if ($users || $serial) {
        genName();
    } else {
        unset($users,$serial);
        return $name;
    }
}

//单个生成SN帐号
if(isset($_POST['submitserial']) && !empty($_POST['snNumber']))
{
    $name = $_POST['snNumber'];
    if (empty($name) || empty($_POST["meal_s"])) {
        echo("<script>lightyear.notify('要生成的账号或套餐不能为空！', 'danger', 3000);</script>");
    } else {
        $nowtime = time();
        $days = $_POST['days'];
        $marks = $_POST['marks'];
        $meal = $_POST["meal_s"];
        $author = $_POST['author'];
        if($db->mCheckOne("luo2888_serialnum", "*", "where name='$name'")){
	    	echo("<script>lightyear.notify('该账号已经存在', 'danger', 3000);</script>");
        } else {
            $db->mInt("luo2888_serialnum", "name,meal,days,gentime,author,marks", "$name,'$meal',$days,$nowtime,'$author','$marks'");
			       echo("<script>lightyear.notify('恭喜，账号已生成！', 'success', 3000);</script>");
        }
    }
}

//批量生成SN帐号
if(isset($_POST['submitserial']) && !empty($_POST['snCount']))
{
    $snCount=$_POST['snCount'];
    if ($snCount<=0 || empty($_POST["meal_s"])) {
        echo("<script>lightyear.notify('要生成的账号或套餐不能为空！', 'danger', 3000);</script>");
    } else {
        $days = $_POST['days'];
        $marks = $_POST['marks'];
        $meal = $_POST["meal_s"];
        $author = $_POST['author'];
        for ($i=0; $i <$snCount ; $i++) { 
            $name = genName();
            $nowtime = time();
            $db->mInt("luo2888_serialnum", "name,meal,days,gentime,author,marks", "$name,'$meal',$days,$nowtime,'$author','$marks'");
         }
         echo("<script>lightyear.notify('恭喜，账号已生成完成！', 'success', 3000);</script>");
    }
}

if (isset($_POST['submitdel'])) {
    if (empty($_POST['id'])) {
        echo("<script>lightyear.notify('请选择要删除的用户账号！', 'danger', 3000);</script>");
    } else {
        foreach ($_POST['id'] as $id) {
            $db->mDel("luo2888_serialnum", "where name=$id");
        } 
        echo("<script>lightyear.notify('选中账号已删除！', 'success', 3000);</script>");
    } 
} 

if (isset($_POST['submitmodify'])) {
    if (empty($_POST['id'])) {
        echo("<script>lightyear.notify('请选择要修改授权天数的账号！', 'danger', 3000);</script>");
    } else {
        $days = $_POST['days'];
        foreach ($_POST['id'] as $id) {
            $db->mSet("luo2888_serialnum", "days=$days", "where name=$id");
            echo("<script>lightyear.notify('账号$id 授权天数已修改！', 'success', 3000);</script>");
        } 
    } 
} 

if (isset($_POST['submitmodifymarks'])) {
    if (empty($_POST['id'])) {
        echo("<script>lightyear.notify('请选择要修改备注的账号！', 'danger', 3000);</script>");
    } else {
        $marks = $_POST['marks'];
        foreach ($_POST['id'] as $id) {
            $db->mSet("luo2888_serialnum", "marks='$marks'", "where name=$id");
            echo("<script>lightyear.notify('账号$id 备注已修改！', 'success', 3000);</script>");
        } 
    } 
} 

if (isset($_POST['submitmodifyauthor'])) {
    if (empty($_POST['id'])) {
        echo("<script>lightyear.notify('请选择要修改备注的账号！', 'danger', 3000);</script>");
    } else {
        $author = $_POST['author'];
        foreach ($_POST['id'] as $id) {
            $db->mSet("luo2888_serialnum", "author='$author'", "where name=$id");
            echo("<script>lightyear.notify('账号$id 代理商id已修改！', 'success', 3000);</script>");
        } 
    } 
} 

if (isset($_POST['submitNotExpired'])) {
    if (empty($_POST['id'])) {
        echo("<script>lightyear.notify('请选择要设置永不到期的账号！', 'danger', 3000);</script>");
    } else {
        foreach ($_POST['id'] as $id) {
            $db->mSet("luo2888_serialnum", "days=999", "where name=$id");
            echo("<script>lightyear.notify('账号$id 已设置为永不到期！', 'success', 3000);</script>");
        } 
    } 
} 

if (isset($_POST["meal_s"]) && isset($_POST["e_meals"])) {
    if (empty($_POST["meal_s"])) {
        echo("<script>lightyear.notify('请选择要修改的套餐！', 'danger', 3000);</script>");
    } elseif (empty($_POST['id'])) {
        echo("<script>lightyear.notify('请选择要修改套餐的账号！', 'danger', 3000);</script>");
    } else {
        foreach($_POST["id"]as $mealid => $userid) {
            $db->mSet("luo2888_serialnum", "meal=" . $_POST["meal_s"], "where name='$userid'");
            echo("<script>lightyear.notify('账号$userid 已修改套餐！', 'success', 3000);</script>");
        } 
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
    $searchparam = "and (name like '%$keywords%' or author like '%$keywords%' or marks like '%$keywords%' or days like '%$keywords%')";
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
    $order = 'gentime desc';
} 

// 获取账号总数并根据每页显示数量计算页数
if ($row = $db->mGetRow("luo2888_serialnum", "count(*)")) {
    $serialCount = $row[0];
    $pageCount = ceil($row[0] / $recCounts);
} else {
    $serialCount = 0;
    $pageCount = 1;
} 
unset($row);

// 处理跳转逻辑
if (isset($_POST['jumpto'])) {
    $p = $_POST['jumpto'];
    if (($p <= $pageCount) && ($p > 0)) {
        echo "<script language=JavaScript>location.href='serialadmin.php' + '?page=$p&order=$order';</script>";
    } 
} 

// 获取当天授权用户总数
$todayTime = strtotime(date("Y-m-d"), time());
if ($row = $db->mGetRow("luo2888_users", "count(*)", "where status>-1 and authortime>$todayTime")) {
    $todayauthoruserCount = $row[0];
} else {
    $todayauthoruserCount = 0;
} 
unset($row);

?>