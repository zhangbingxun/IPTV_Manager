<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);

if ($user != $admin) {
    exit("<script>$.alert({title: '警告',content: '你无权访问此页面。',type: 'orange',buttons: {confirm: {text: '确定',btnClass: 'btn-primary',action: function(){history.go(-1);}}}});</script>");
} 

?>

<?php
// 修改用户名
if (isset($_POST['olduser']) && isset($_POST['newuser'])) {
    if (empty($_POST['olduser']) || empty($_POST['newuser'])) {
        echo"<script>showindex=3;lightyear.notify('用户名不能为空！', 'danger', 3000);</script>";
    } else {
        $olduser = $_POST['olduser'];
        $newuser = $_POST['newuser'];
        $newuser_confirm = $_POST['newuser_confirm'];
        if ($newuser == $newuser_confirm) {
            if (!empty($db->mGet("luo2888_config", "name", "where value='$olduser'"))) {
                $db->mSet("luo2888_config", "value='$newuser'", "where name='adminname'");
                echo"<script>showindex=3;lightyear.notify('用户名修改成功！', 'success', 3000);</script>";
            } else {
                echo"<script>showindex=3;lightyear.notify('用户名不匹配！', 'danger', 3000);</script>";
            } 
        } else {
            echo"<script>showindex=3;lightyear.notify('两次输入不匹配！', 'danger', 3000);</script>";
        } 
    } 
} 

// 修改密码操作
if (isset($_POST['oldpassword']) && isset($_POST['newpassword'])) {
    if (empty($_POST['oldpassword']) || empty($_POST['newpassword'])) {
        echo"<script>showindex=3;lightyear.notify('密码不能为空！', 'danger', 3000);</script>";
    } else {
        $postnewpass = $_POST['newpassword'];
        $postnewpass_confirm = $_POST['newpassword_confirm'];
        if ($postnewpass == $postnewpass_confirm) {
            $oldpassword = md5(PANEL_MD5_KEY . $_POST['oldpassword']);
            $newpassword = md5(PANEL_MD5_KEY . $_POST['newpassword']);
            if (!empty($db->mGet("luo2888_config", "name", "where value='$oldpassword'"))) {
                $db->mSet("luo2888_config", "value='$newpassword'", "where name='adminpass'");
                echo"<script>showindex=3;lightyear.notify('密码修改成功！', 'success', 3000);</script>";
            } else {
                echo"<script>showindex=3;lightyear.notify('原始密码不匹配！', 'danger', 3000);</script>";
            } 
        } else {
            echo"<script>showindex=3;lightyear.notify('两次输入不匹配！', 'danger', 3000);</script>";
        } 
    } 
} 

// 设置安全入口
if (isset($_POST['newskey']) && isset($_POST['newskey_confirm'])) {
    if (empty($_POST['newskey']) || empty($_POST['newskey_confirm'])) {
        echo"<script>showindex=3;lightyear.notify('安全码不能为空！', 'danger', 3000);</script>";
    } else {
        $postnewskey = $_POST['newskey'];
        $postnewskey_confirm = $_POST['newskey_confirm'];
        if ($postnewskey == $postnewskey_confirm) {
            $newsecret_key = md5($_POST['newskey']);
            $db->mSet("luo2888_config", "value='$newsecret_key'", "where name='secret_key'");
            echo"<script>showindex=3;lightyear.notify('安全码修改成功！', 'success', 3000);</script>";
        } else {
            echo"<script>showindex=3;lightyear.notify('两次输入不匹配！', 'danger', 3000);</script>";
        } 
    } 
} 

// 关闭安全入口
if (isset($_POST['closesecret_key'])) {
    $db->mSet("luo2888_config", "value=NULL", "where name='secret_key'");
    echo"<script>showindex=3;lightyear.notify('安全码验证已关闭！', 'success', 3000);</script>";
} 

// 更新随机密钥
if (isset($_POST['update_rankey'])) {
    $rand = rand(1, 9999999);
    $key = md5($rand);
    $db->mSet("luo2888_config", "value='$key'", "where name='randkey'");
    echo"<script>showindex=3;lightyear.notify('随机密钥已更新！', 'success', 3000);</script>";
} 

// 设置APP升级信息
if (isset($_POST['appver']) && isset($_POST['appurl'])) {
    $appver = $_POST['appver'];
    $appurl = $_POST['appurl'];
    $up_size = $_POST["up_size"];
    $up_text = $_POST["up_text"];
    if (isset($_POST['up_sets'])) {
        $up_sets = 1;
    } else {
        $up_sets = 0;
    } 
	$db->mSet("luo2888_config", "value='$appver'", "where name='appver'");
	$db->mSet("luo2888_config", "value='$appurl'", "where name='appurl'");
	$db->mSet("luo2888_config", "value='$up_size'", "where name='up_size'");
	$db->mSet("luo2888_config", "value='$up_sets'", "where name='up_sets'");
	$db->mSet("luo2888_config", "value='$up_text'", "where name='up_text'");
    echo"<script>showindex=4;lightyear.notify('通用版APP升级设置成功！', 'success', 3000);</script>";
} 

// 设置APP升级信息
if (isset($_POST['boxver']) && isset($_POST['boxurl'])) {
    $boxver = $_POST['boxver'];
    $boxurl = $_POST['boxurl'];
	$db->mSet("luo2888_config", "value='$boxver'", "where name='boxver'");
	$db->mSet("luo2888_config", "value='$boxurl'", "where name='boxurl'");
    echo"<script>showindex=4;lightyear.notify('盒子版APP升级设置成功！', 'success', 3000);</script>";
} 

// APP设置
if (isset($_POST['decodersel']) && isset($_POST['buffTimeOut'])) {
    $vpntimes = $_POST['vpntimes'];
    $decoder = $_POST['decodersel'];
    $trialdays = $_POST['trialdays'];
    $buffTimeOut = $_POST['buffTimeOut'];
    $sameip_user = $_POST['sameip_user'];
    if ($trialdays == 0) {
        $db->mSet("luo2888_users", "exp=0", "where status=-1");
    } 
    $db->mSet("luo2888_config", "value='$decoder'", "where name='decoder'");
    $db->mSet("luo2888_config", "value='$vpntimes'", "where name='vpntimes'");
    $db->mSet("luo2888_config", "value='$trialdays'", "where name='trialdays'");
    $db->mSet("luo2888_config", "value='$buffTimeOut'", "where name='buffTimeOut'");
    $db->mSet("luo2888_config", "value='$sameip_user'", "where name='max_sameip_user'");
    echo"<script>showindex=4;lightyear.notify('设置成功！', 'success', 3000);</script>";
} 

if (isset($_POST['submitsetver'])) {
	$db->mSet("luo2888_config", "value=value+1", "where name='setver'");
    echo"<script>showindex=4;lightyear.notify('推送成功，用户下次启动将恢复默认设置！', 'success', 3000);</script>";
} 

// APP提示信息
if (isset($_POST['failureurl']) && isset($_POST['deniedurl'])) {
    $deniedurl = $_POST['deniedurl'];
    $failureurl = $_POST['failureurl'];
    $tiploading = $_POST['tiploading'];
    $tipusernoreg = $_POST['tipusernoreg'];
    $tipuserexpired = $_POST['tipuserexpired'];
    $tipuserforbidden = $_POST['tipuserforbidden'];
    $db->mSet("luo2888_config", "value='$deniedurl'", "where name='deniedurl'");
    $db->mSet("luo2888_config", "value='$failureurl'", "where name='failureurl'");
    $db->mSet("luo2888_config", "value='$tiploading'", "where name='tiploading'");
    $db->mSet("luo2888_config", "value='$tipusernoreg'", "where name='tipusernoreg'");
    $db->mSet("luo2888_config", "value='$tipuserexpired'", "where name='tipuserexpired'");
    $db->mSet("luo2888_config", "value='$tipuserforbidden'", "where name='tipuserforbidden'");
    echo"<script>showindex=4;lightyear.notify('提示信息已修改！', 'success', 3000);</script>";
} 

// 公告设置
if (isset($_POST['adtext']) && isset($_POST['adinfo'])) {
    $adtext = $_POST['adtext'];
    $adinfo = $_POST['adinfo'];
    $showtime = $_POST['showtime'];
    $adtext_free = $_POST['adtext_free'];
    $showinterval = $_POST['showinterval'];
	$db->mSet("luo2888_config", "value='$adinfo'", "where name='adinfo'");
	$db->mSet("luo2888_config", "value='$adtext'", "where name='adtext'");
	$db->mSet("luo2888_config", "value='$showtime'", "where name='showtime'");
	$db->mSet("luo2888_config", "value='$adtext_free'", "where name='adtext_free'");
	$db->mSet("luo2888_config", "value='$showinterval'", "where name='showinterval'");
    echo"<script>showindex=0;lightyear.notify('公告修改成功！', 'success', 3000);</script>";
} 

// APP信息设置
if (isset($_POST['app_appname']) && isset($_POST['app_packagename'])) {
    $keyproxy = $_POST['keyproxy'];
    $app_sign = $_POST['app_sign'];
    $app_b64key = $_POST['app_b64key'];
    $app_appname = $_POST['app_appname'];
    $app_useragent = $_POST['app_useragent'];
    $app_packagename = $_POST['app_packagename'];
    $db->mSet("luo2888_config", "value='$keyproxy'", "where name='keyproxy'");
    $db->mSet("luo2888_config", "value='$app_sign'", "where name='app_sign'");
    $db->mSet("luo2888_config", "value='$app_b64key'", "where name='app_b64key'");
    $db->mSet("luo2888_config", "value='$app_appname'", "where name='app_appname'");
    $db->mSet("luo2888_config", "value='$app_useragent'", "where name='app_useragent'");
    $db->mSet("luo2888_config", "value='$app_packagename'", "where name='app_packagename'");
    echo"<script>showindex=4;lightyear.notify('保存成功！', 'success', 3000);</script>";
} 

// 支付宝API设置
if (isset($_POST['alipay_publickey']) && isset($_POST['alipay_privatekey'])) {
    $alipay_appid = $_POST['alipay_appid'];
    $alipay_publickey = $_POST['alipay_publickey'];
    $alipay_privatekey = $_POST['alipay_privatekey'];
	$db->mSet("luo2888_config", "value='$alipay_appid'", "where name='alipay_appid'");
	$db->mSet("luo2888_config", "value='$alipay_publickey'", "where name='alipay_publickey'");
	$db->mSet("luo2888_config", "value='$alipay_privatekey'", "where name='alipay_privatekey'");
    echo"<script>showindex=0;lightyear.notify('提交修改成功！', 'success', 3000);</script>";
} 

// 网页端设置
if (isset($_POST['web_appinfo']) && isset($_POST['web_description'])) {
    $panurl = $_POST['panurl'];
    $web_about = $_POST['web_about'];
    $web_title = $_POST['web_title'];
    $web_appinfo = $_POST['web_appinfo'];
    $web_copyright = $_POST['web_copyright'];
    $web_description = $_POST['web_description'];
	$db->mSet("luo2888_config", "value='$panurl'", "where name='panurl'");
	$db->mSet("luo2888_config", "value='$web_about'", "where name='web_about'");
	$db->mSet("luo2888_config", "value='$web_title'", "where name='web_title'");
	$db->mSet("luo2888_config", "value='$web_appinfo'", "where name='web_appinfo'");
	$db->mSet("luo2888_config", "value='$web_copyright'", "where name='web_copyright'");
	$db->mSet("luo2888_config", "value='$web_description'", "where name='web_description'");
    echo"<script>showindex=0;lightyear.notify('提交修改成功！', 'success', 3000);</script>";
} 

// 上传APP背景图片
if (isset($_POST['submitsplash'])) {
    if ($_FILES["splash"]["type"] == "image/png") {
        if ($_FILES["splash"]["error"] > 0) {
            echo "Error: " . $_FILES["splash"]["error"];
        } else {
            $savefile = "../../images/" . $_FILES["splash"]["name"];
            move_uploaded_file($_FILES["splash"]["tmp_name"], $savefile);
            echo "<script>showindex=1;lightyear.notify('上传成功！', 'success', 3000);</script>";
        } 
    } else {
        echo "<script>showindex=1;lightyear.notify('图片仅支持PNG格式，大小不能超过800KB！', 'danger', 3000);</script>";
    } 
} 

// 删除背景图片
if (isset($_POST['submitdelbg'])) {
    $file = $_POST['file'];
    unlink('../../images/' . $file);
    echo"<script>showindex=1;lightyear.notify('删除成功！', 'success', 3000);</script>";
} 

// 后台记录
if (isset($_POST['clearlog'])) {
    $db->mDel("luo2888_record");
    echo"<script>showindex=2;lightyear.notify('后台记录已清空！', 'success', 3000);</script>";
} 

// IP归属地接口
if (isset($_POST['ipchk'])) {
    $ipchk = $_POST['ipchk'];
    $db->mSet("luo2888_config", "value='$ipchk'", "where name='ipchk'");
    echo"<script>showindex=2;lightyear.notify('IP数据库已更换！', 'success', 3000);</script>";
} 

// 创建目录
$imgdir = "../images";
if (! is_dir ($imgdir)) {
    @mkdir ($imgdir, 0755, true) or die ('创建文件夹失败');
} 
$files = glob("../../images/*.png");

// 初始化变量
$adinfo = $db->mGet("luo2888_config", "value", "where name='adinfo'");
$adtext = $db->mGet("luo2888_config", "value", "where name='adtext'");
$adtext_free = $db->mGet("luo2888_config", "value", "where name='adtext_free'");
$dataver = $db->mGet("luo2888_config", "value", "where name='dataver'");
$appver = $db->mGet("luo2888_config", "value", "where name='appver'");
$boxver = $db->mGet("luo2888_config", "value", "where name='boxver'");
$setver = $db->mGet("luo2888_config", "value", "where name='setver'");
$dataurl = $db->mGet("luo2888_config", "value", "where name='dataurl'");
$appurl = $db->mGet("luo2888_config", "value", "where name='appurl'");
$boxurl = $db->mGet("luo2888_config", "value", "where name='boxurl'");
$showtime = $db->mGet("luo2888_config", "value", "where name='showtime'");
$showinterval = $db->mGet("luo2888_config", "value", "where name='showinterval'");
$splash = $db->mGet("luo2888_config", "value", "where name='splash'");
$decoder = $db->mGet("luo2888_config", "value", "where name='decoder'");
$buffTimeOut = $db->mGet("luo2888_config", "value", "where name='buffTimeOut'");
$tiploading = $db->mGet("luo2888_config", "value", "where name='tiploading'");
$tipusernoreg = $db->mGet("luo2888_config", "value", "where name='tipusernoreg'");
$tipuserexpired = $db->mGet("luo2888_config", "value", "where name='tipuserexpired'");
$tipuserforbidden = $db->mGet("luo2888_config", "value", "where name='tipuserforbidden'");
$trialdays = $db->mGet("luo2888_config", "value", "where name='trialdays'");
$up_size = $db->mGet("luo2888_config", "value", "where name='up_size'");
$up_sets = $db->mGet("luo2888_config", "value", "where name='up_sets'");
$up_text = $db->mGet("luo2888_config", "value", "where name='up_text'");
$secret_key = $db->mGet("luo2888_config", "value", "where name='secret_key'");
$weaapi_id = $db->mGet("luo2888_config", "value", "where name='weaapi_id'");
$weaapi_key = $db->mGet("luo2888_config", "value", "where name='weaapi_key'");
$app_sign = $db->mGet("luo2888_config", "value", "where name='app_sign'");
$app_appname = $db->mGet("luo2888_config", "value", "where name='app_appname'");
$app_useragent = $db->mGet("luo2888_config", "value", "where name='app_useragent'");
$app_packagename = $db->mGet("luo2888_config", "value", "where name='app_packagename'");
$alipay_appid = $db->mGet("luo2888_config", "value", "where name='alipay_appid'");
$app_b64key = $db->mGet("luo2888_config", "value", "where name='app_b64key'");
$alipay_publickey = $db->mGet("luo2888_config", "value", "where name='alipay_publickey'");
$alipay_privatekey = $db->mGet("luo2888_config", "value", "where name='alipay_privatekey'");
$ipchk = $db->mGet("luo2888_config", "value", "where name='ipchk'");
$showwea = $db->mGet("luo2888_config", "value", "where name='showwea'");
$keyproxy = $db->mGet("luo2888_config", "value", "where name='keyproxy'");
$failureurl = $db->mGet("luo2888_config", "value", "where name='failureurl'");
$deniedurl = $db->mGet("luo2888_config", "value", "where name='deniedurl'");
$max_sameip_user = $db->mGet("luo2888_config", "value", "where name='max_sameip_user'");
$vpntimes = $db->mGet("luo2888_config", "value", "where name='vpntimes'");

$panurl = $db->mGet("luo2888_config", "value", "where name='panurl'");
$web_about = $db->mGet("luo2888_config", "value", "where name='web_about'");
$web_title = $db->mGet("luo2888_config", "value", "where name='web_title'");
$web_appinfo = $db->mGet("luo2888_config", "value", "where name='web_appinfo'");
$web_copyright = $db->mGet("luo2888_config", "value", "where name='web_copyright'");
$web_description = $db->mGet("luo2888_config", "value", "where name='web_description'");

?>
