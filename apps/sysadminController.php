<?php
include_once "../config.php";

if ($user != 'admin') {
    echo"<script>alert('你无权访问此页面！');history.go(-1);</script>";
    exit();
} 
// 修改密码操作
if (isset($_POST['submit']) && isset($_POST['newpassword'])) {
    if (empty($_POST['oldpassword']) || empty($_POST['newpassword'])) {
        echo"<script>showindex=5;alert('密码不能为空！');</script>";
    } else {
        $username = $_POST['username'];
        $oldpassword = md5(PANEL_MD5_KEY . $_POST['oldpassword']);
        $newpassword = md5(PANEL_MD5_KEY . $_POST['newpassword']);
        $result = mysqli_query($GLOBALS['conn'], "select * from luo2888_admin where name='$username' and psw='$oldpassword'");
        if (mysqli_fetch_array($result)) {
            $sql = "update luo2888_admin set psw='$newpassword' where name='$username'";
            mysqli_query($GLOBALS['conn'], $sql);
            echo"<script>showindex=5;alert('密码修改成功！');</script>";
            mysqli_free_result($result);
        } else {
            echo"<script>showindex=5;alert('原始密码不匹配！');</script>";
            mysqli_free_result($result);
        } 
    } 
} 
// 修改安全码操作
if (isset($_POST['submit']) && isset($_POST['newsecret_key'])) {
    if (empty($_POST['newsecret_key']) || empty($_POST['newsecret_key_confirm'])) {
        echo"<script>showindex=5;alert('安全码不能为空！');</script>";
    } else {
        $newsecret_key_input = $_POST['newsecret_key'];
        $newsecret_key_confirm = $_POST['newsecret_key_confirm'];
        if ($newsecret_key_input == $newsecret_key_confirm) {
            $newsecret_key = md5($_POST['newsecret_key']);
            $sql = "update luo2888_config set value='$newsecret_key' where name='secret_key'";
            mysqli_query($GLOBALS['conn'], $sql);
            echo"<script>showindex=5;alert('安全码修改成功！');</script>";
        } else {
            echo"<script>showindex=5;alert('两次输入不匹配！');</script>";
        } 
    } 
} 

if (isset($_POST['closesecret_key'])) {
    $needsecret_key = $_POST['closesecret_key'];
    $sql = "update luo2888_config set value=NULL where name='secret_key'";
    mysqli_query($GLOBALS['conn'], $sql);
    echo"<script>showindex=5;alert('安全码验证已关闭！');</script>";
} 
// 添加管理员操作
if (isset($_POST['adminadd'])) {
    if (empty($_POST['addadminname']) || empty($_POST['addadminpsw'])) {
        echo"<script>showindex=6;alert('管理员的账号或是密码不能为空！');</script>";
    } else {
        $adminname = $_POST['addadminname'];
        $adminpsw = md5(PANEL_MD5_KEY . $_POST['addadminpsw']);
        $result = mysqli_query($GLOBALS['conn'], "SELECT count(*) from luo2888_admin");
        if ($row = mysqli_fetch_array($result)) {
            if ($row[0] > 5) {
                unset($row);
                mysqli_free_result($result);
                echo"<script>showindex=6;alert('管理员数量已达上限！');</script>";
            } else {
                $result = mysqli_query($GLOBALS['conn'], "select * from luo2888_admin where name='$adminname'");
                if (mysqli_fetch_array($result)) {
                    unset($row);
                    mysqli_free_result($result);
                    echo"<script>showindex=6;alert('用户名已存在！');</script>";
                } else {
                    mysqli_query($GLOBALS['conn'], "INSERT into luo2888_admin (name,psw) values ('$adminname','$adminpsw')");
                    echo"<script>showindex=6;alert('管理员添加成功！');</script>";
                } 
            } 
        } 
    } 
} 
// 删除账号操作
if (isset($_POST['deleteadmin'])) {
    if (empty($_POST['adminname'])) {
        echo"<script>showindex=6;alert('请选择要删除的帐号！');</script>";
    } else {
        foreach ($_POST['adminname'] as $name) {
            if ($name <> 'admin') {
                mysqli_query($GLOBALS['conn'], "delete from luo2888_admin where name='$name'");
                echo"<script>showindex=6;alert('管理员[$name]已删除！');</script>";
            } else {
                if ($name == "admin") {
                    echo"<script>showindex=6;alert('超级管理员[$name]不允许删除！');</script>";
                } else {
                    echo"<script>showindex=6;alert('删除失败！');</script>";
                } 
            } 
        } 
    } 
} 
// 设置管理员权限
if (isset($_POST['saveauthorinfo'])) {
    if (!empty($_POST['adminname'])) {
        mysqli_query($GLOBALS['conn'], "UPDATE luo2888_admin set author=0,useradmin=0,ipcheck=0,epgadmin=0,channeladmin=0 where name<>'admin'");
        if (!empty($_POST['author'])) {
            foreach ($_POST['author'] as $adminname) {
                mysqli_query($GLOBALS['conn'], "UPDATE luo2888_admin set author=1 where name='$adminname'");
            } 
        } 
        if (!empty($_POST['useradmin'])) {
            foreach ($_POST['useradmin'] as $adminname) {
                mysqli_query($GLOBALS['conn'], "UPDATE luo2888_admin set useradmin=1 where name='$adminname'");
            } 
        } 
        if (!empty($_POST['ipcheck'])) {
            foreach ($_POST['ipcheck'] as $adminname) {
                mysqli_query($GLOBALS['conn'], "UPDATE luo2888_admin set ipcheck=1 where name='$adminname'");
            } 
        } 
        if (!empty($_POST['epgadmin'])) {
            foreach ($_POST['epgadmin'] as $adminname) {
                mysqli_query($GLOBALS['conn'], "UPDATE luo2888_admin set epgadmin=1 where name='$adminname'");
            } 
        } 
        if (!empty($_POST['channeladmin'])) {
            foreach ($_POST['channeladmin'] as $adminname) {
                mysqli_query($GLOBALS['conn'], "UPDATE luo2888_admin set channeladmin=1 where name='$adminname'");
            } 
        } 
        echo"<script>showindex=6;alert('管理员权限设定已保存！');</script>";
    } else {
        echo"<script>showindex=6;alert('请选择管理员！');</script>";
    } 
} 
// 设置APP升级信息
if (isset($_POST['submit']) && isset($_POST['appver'])) {
    $versionname = $_POST['appver'];
    $appurl = $_POST['appurl'];
    $up_size = $_POST["up_size"];
    $up_sets = $_POST["up_sets"];
    $up_text = $_POST["up_text"];
    $sql = "update luo2888_appdata set appver='$versionname',appurl='$appurl',up_size='$up_size',up_sets=$up_sets,up_text='$up_text' ";
    mysqli_query($GLOBALS['conn'], $sql);
    echo"<script>showindex=2;alert('APP升级设置成功！');</script>";
} 

if (isset($_POST['decodersel']) && isset($_POST['buffTimeOut'])) {
    $decoder = $_POST['decodersel'];
    $buffTimeOut = $_POST['buffTimeOut'];
    $trialdays = $_POST['trialdays'];
    $sql = "update luo2888_appdata set decoder=$decoder,buffTimeOut=$buffTimeOut,trialdays=$trialdays";
    mysqli_query($GLOBALS['conn'], $sql);
    if ($trialdays == 0) {
        $sql = "update luo2888_users set exp=0 where status=-1";
        mysqli_query($GLOBALS['conn'], $sql);
    } 
    echo"<script>showindex=2;alert('设置成功！');</script>";
} 

if (isset($_POST['submitsetver'])) {
    $sql = "update luo2888_appdata set setver=setver+1";
    mysqli_query($GLOBALS['conn'], $sql);
    echo"<script>showindex=2;alert('推送成功，用户下次启动将恢复出厂设置！');</script>";
} 

if (isset($_POST['submittipset'])) {
    $tiploading = $_POST['tiploading'];
    $tipusernoreg = $_POST['tipusernoreg'];
    $tipuserexpired = $_POST['tipuserexpired'];
    $tipuserforbidden = $_POST['tipuserforbidden'];
    mysqli_query($GLOBALS['conn'], "update luo2888_appdata set tiploading='$tiploading',tipusernoreg='$tipusernoreg',tipuserexpired='$tipuserexpired',tipuserforbidden='$tipuserforbidden'");
    echo"<script>showindex=2;alert('提示信息已修改！');</script>";
} 

if (isset($_POST['weaapi_id']) && isset($_POST['weaapi_key'])) {
    $weaapi_id = $_POST['weaapi_id'];
    $weaapi_key = $_POST['weaapi_key'];
    if (empty($weaapi_id)) {
        echo("<script>showindex=0;alert('请填写天气APP_ID！');</script>");
    } else if (empty($weaapi_key)) {
        echo("<script>showindex=0;alert('请填写天气APP_KEY！');</script>");
    } else {
	    if (isset($_POST['showwea'])) {
	        $showwea = 1;
	    } else {
	        $showwea = 0;
	    } 
	    set_config('showwea', "$showwea");
	    set_config('weaapi_id', "$weaapi_id");
	    set_config('weaapi_key', "$weaapi_key");
	    if ($showwea == 0) {
	        echo"<script>showindex=0;alert('天气显示已关闭！');</script>";
	    } else {
	        echo"<script>showindex=0;alert('天气显示已开启!');</script>";
	    } 
    }
} 

if (isset($_POST['submit']) && isset($_POST['adtext'])) {
    $adtext = $_POST['adtext'];
    $showtime = $_POST['showtime'];
    $showinterval = $_POST['showinterval'];
    $qqinfo = $_POST['qqinfo'];
    if (isset($_POST['showwea'])) {
        $showwea = 1;
    } else {
        $showwea = 0;
    } 
    $sql = "update luo2888_appdata set adtext='$adtext',showtime=$showtime,showinterval=$showinterval,qqinfo='$qqinfo',showwea=$showwea";
    mysqli_query($GLOBALS['conn'], $sql);
    echo"<script>showindex=0;alert('公告修改成功！');</script>";
} 

if (isset($_POST['submitappinfo'])) {
    $app_sign = $_POST['app_sign'];
    $app_appname = $_POST['app_appname'];
    $app_packagename = $_POST['app_packagename'];
    set_config('app_sign', "$app_sign");
    set_config('app_appname', "$app_appname");
    set_config('app_packagename', "$app_packagename");
    echo"<script>showindex=2;alert('保存成功！');</script>";
} 

$userdata = "";
if (isset($_POST['submitexport'])) {
    $result = mysqli_query($GLOBALS['conn'], "select name,deviceid,mac,model,author,exp,marks,status from luo2888_users where status>-1");
    while ($row = mysqli_fetch_array($result)) {
        $userdata = $userdata . $row[0] . "," . $row[1] . "," . $row[2] . "," . $row[3] . "," . $row[4] . "," . $row[5] . "," . $row[6] . "," . $row[7] . "\r\n";
    } 
    unset($row);
    mysqli_free_result($result);
    echo"<script>showindex=1;alert('数据已导出。请全选，复制后保存！');</script>";
} 

if (isset($_POST['submitimport'])) {
    $userdata = $_POST['userdata'];
    $lines = explode("\r\n", $userdata);
    $sucessCount = 0;
    $failedCount = 0;
    foreach($lines as $line) {
        if (strpos($line, ',') !== false) {
            $arr = explode(",", $line);
            $nowtime = time();
            $name = $arr[0];
            $deviceid = $arr[1];
            $mac = $arr[2];
            $model = $arr[3];
            $author = $arr[4];
            $exp = $arr[5];
            $marks = $arr[6];
            $status = $arr[7];
            $result = mysqli_query($GLOBALS['conn'], "SELECT * from luo2888_users where name=$name");
            if (mysqli_fetch_array($result)) {
                $failedCount++;
                echo "<p align='center'>$line 因ID已存在导入失败</p>";
            } else {
                if (mysqli_query($GLOBALS['conn'], "INSERT into luo2888_users (name,mac,deviceid,model,author,exp,status,marks) values($name,'$mac','$deviceid','$model','$author',$exp,$status,'$marks')")) {
                    $sucessCount++;
                } else {
                    $failedCount++;
                } 
            } 
            unset($arr);
            mysqli_free_result($result);
        } else {
            echo "<p align='center'>$line 因格式错误导入失败</p>";
            $failedCount++;
        } 
    } 
    unset($userdata, $lines);
    echo "<script>alert('导入成功 $sucessCount 条,失败 $failedCount 条。')</script>";
    echo"<script>showindex=1;</script>";
} 

function genName() {
    $name = rand(10000000, 99999999);
    $result = mysqli_query($GLOBALS['conn'], "SELECT * from luo2888_users where name=$name");
    if ($row = mysqli_fetch_array($result)) {
        unset($row);
        mysqli_free_result($result);
        genName();
    } else {
        return $name;
    } 
} 
// 上传APP背景图片
if (isset($_POST['submitsplash'])) {
    if ($_FILES["splash"]["type"] == "image/png") {
        if ($_FILES["splash"]["error"] > 0) {
            echo "Error: " . $_FILES["splash"]["error"];
        } else {
            $savefile = "../images/" . $_FILES["splash"]["name"];
            move_uploaded_file($_FILES["splash"]["tmp_name"], $savefile);
            $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER["REQUEST_URI"];
            $splashurl = dirname($url) . '/' . $savefile;
            $sql = "update luo2888_appdata set splash='$splashurl'";
            mysqli_query($GLOBALS['conn'], $sql);
            echo "<script>alert('上传成功！')</script>";
        } 
    } else {
        echo "<script>alert('图片仅支持PNG格式，大小不能超过800KB。')</script>";
    } 
    echo"<script>showindex=3;</script>";
} 
// 删除背景图片
if (isset($_POST['submitdelbg'])) {
    $file = $_POST['file'];
    unlink('../images/' . $file);
    echo"<script>showindex=3;alert('删除成功！');</script>";
} 

if (isset($_POST['submitcloseauthor'])) {
    $needauthor = $_POST['needauthor'];
    if ($needauthor == 1) {
        $needauthor = 0;
        echo"<script>showindex=2;alert('用户授权已关闭！');</script>";
    } else {
        $needauthor = 1;
        echo"<script>showindex=2;alert('用户授权已开启!');</script>";
    } 
    mysqli_query($GLOBALS['conn'], "UPDATE luo2888_appdata set needauthor=$needauthor");
} 

if (isset($_POST['clearlog'])) {
    $result = mysqli_query($GLOBALS['conn'], "delete from luo2888_adminrec");
    echo"<script>showindex=4;alert('后台记录已清空!');</script>";
} 
// 初始化
$result = mysqli_query($GLOBALS['conn'], "select dataver,appver,setver,dataurl,appurl,adtext,showtime,showinterval,splash,needauthor,decoder,buffTimeOut,tiploading,tipuserforbidden,tipuserexpired,tipusernoreg,trialdays,qqinfo,up_size,up_sets,up_text from luo2888_appdata");
if ($row = mysqli_fetch_array($result)) {
    $adtext = $row['adtext'];
    $dataver = $row['dataver'];
    $appver = $row['appver'];
    $setver = $row['setver'];
    $dataurl = $row['dataurl'];
    $appurl = $row['appurl'];
    $showtime = $row['showtime'];
    $showinterval = $row['showinterval'];
    $splash = $row['splash'];
    $needauthor = $row['needauthor'];
    $decoder = $row['decoder'];
    $buffTimeOut = $row['buffTimeOut'];
    $tiploading = $row['tiploading'];
    $tipusernoreg = $row['tipusernoreg'];
    $tipuserexpired = $row['tipuserexpired'];
    $tipuserforbidden = $row['tipuserforbidden'];
    $trialdays = $row['trialdays'];
    $qqinfo = $row['qqinfo'];
    $up_size = $row["up_size"];
    $up_sets = $row["up_sets"];
    $up_text = $row["up_text"];
} 
unset($row);
mysqli_free_result($result);

if ($needauthor == 1) {
    $closeauthor = "关闭授权";
} else {
    $closeauthor = "开启授权";
} 

if (get_config('showwea') == 1) {
    $showwea = 'checked="checked"';
} else {
    $showwea = "";
} 
// 创建目录
$imgdir = "../images";
if (! is_dir ($imgdir)) {
    @mkdir ($imgdir, 0755, true) or die ('创建文件夹失败');
} 
$files = glob("../images/*.png");

?>
