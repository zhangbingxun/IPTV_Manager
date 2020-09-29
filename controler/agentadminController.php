<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);
?>

<?php 

// 随机字符串
function randomStr($len) {
    $chars = array(
        "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
        "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
        "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
        "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
        "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
        "3", "4", "5", "6", "7", "8", "9"
    );

    $charsLen = count($chars) - 1;
    shuffle($chars);
    $str = '';
    for ($i=0; $i<$len; $i++) {
        $str .= $chars[mt_rand(0, $charsLen)];
    }
    return $str;
}

if (isset($_POST['addagent']) || isset($_POST['delagent'])) {

    if ($user != $admin) {
        exit("<script>$.alert({title: '警告',content: '你无权进行此操作。',type: 'orange',buttons: {confirm: {text: '确定',btnClass: 'btn-primary',action: function(){history.go(-1);}}}});</script>");
    }
    
    // 新增代理商
    if (isset($_POST['addagent'])) {
        $id = $_POST['id'];
        $name = randomStr(8);
        if (empty($id)) {
            echo "<script>lightyear.notify('代理商编号不能为空！', 'danger', 3000);</script>";
        } else {
            // 代理商是否已经同名或存在
            $result = $db->mQuery("select * from luo2888_agents where id='A$id'");
            if (mysqli_num_rows($result)) {
        		mysqli_free_result($result);
                echo "<script>lightyear.notify('代理商编号 A" . $id . " 已存在，请不要重复新增！', 'danger', 3000);</script>";
            } else {
                $db->mInt("luo2888_agents", "id,name,pass", "'A$id','$name','980784867833e9c6e8cf5816874bb08c'");
                echo "<script>lightyear.notify('代理商 A" . $id . " 已增加，默认用户名：" . $name . "，默认密码: 123456！', 'success', 3000);</script>";
            }
        }
    } 
    
    // 删除代理商
    if (isset($_POST['delagent'])) {
        $id = $_POST['id'];
        $db->mDel("luo2888_agents", "where id='$id'");
        echo "<script>lightyear.notify('代理商编号 " . $id . " 已删除！', 'success', 3000);</script>";
    } 

}

if (isset($_POST['adtext']) || isset($_POST['newpass'])) {
    
    $id = $_POST['id'];
    
    if ($user != $admin) {
        if ($id != $user) {
            exit("<script>$.alert({title: '警告',content: '你无权进行此操作。$id',type: 'orange',buttons: {confirm: {text: '确定',btnClass: 'btn-primary',action: function(){history.go(-1);}}}});</script>");
        }
    }
    
    // 信息修改
    if (isset($_POST['adinfo']) || isset($_POST['adtext'])) {
        $name=$_POST["name"];
        $adinfo=$_POST["adinfo"];
        $adtext=$_POST["adtext"];
        $db->mSet("luo2888_agents", "name='$name',adtext='$adtext',adinfo='$adinfo'", "where id='$id'");
        echo "<script>lightyear.notify('公告信息修改成功！！', 'success', 3000);</script>";
    }
    
    // 修改密码操作
    if (isset($_POST['newpass'])) {
        if (empty($_POST['newpass'])) {
            echo "<script>lightyear.notify('密码不能为空！', 'danger', 3000);</script>";
        } else {
            $postnewpass = $_POST['newpass'];
            $postnewpass_confirm = $_POST['newpass_confirm'];
            if ($postnewpass == $postnewpass_confirm) {
                $newpass = md5(PANEL_MD5_KEY . $postnewpass);
                $db->mSet("luo2888_agents", "pass='$newpass'", "where id='$id'");
                echo "<script>lightyear.notify('密码修改成功！', 'success', 3000);</script>";
            } else {
                echo "<script>lightyear.notify('两次输入不匹配！', 'danger', 3000);</script>";
            } 
        } 
    }

} 

?>