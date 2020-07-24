<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);

if ($user != 'admin') {
    exit("<script>$.alert({title: '警告',content: '你无权访问此页面。',type: 'orange',buttons: {confirm: {text: '确定',btnClass: 'btn-primary',action: function(){history.go(-1);}}}});</script>");
} 

?>

<?php 
// 修改密码操作
if (isset($_POST['submit']) && isset($_POST['newpassword'])) {
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
if (isset($_POST['submit']) && isset($_POST['newskey'])) {
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
if (isset($_POST['submit']) && isset($_POST['appver'])) {
    $versionname = $_POST['appver'];
    $appurl = $_POST['appurl'];
    $up_size = $_POST["up_size"];
    $up_text = $_POST["up_text"];
    if (isset($_POST['up_sets'])) {
        $up_sets = 1;
    } else {
        $up_sets = 0;
    } 
	$db->mSet("luo2888_config", "value='$versionname'", "where name='appver'");
	$db->mSet("luo2888_config", "value='$appurl'", "where name='appurl'");
	$db->mSet("luo2888_config", "value='$up_size'", "where name='up_size'");
	$db->mSet("luo2888_config", "value='$up_sets'", "where name='up_sets'");
	$db->mSet("luo2888_config", "value='$up_text'", "where name='up_text'");
    echo"<script>showindex=4;lightyear.notify('通用版APP升级设置成功！', 'success', 3000);</script>";
} 

// 设置APP升级信息
if (isset($_POST['submit']) && isset($_POST['appver_sdk14'])) {
    $versionname = $_POST['appver_sdk14'];
    $appurl = $_POST['appurl_sdk14'];
	$db->mSet("luo2888_config", "value='$versionname'", "where name='appver_sdk14'");
	$db->mSet("luo2888_config", "value='$appurl'", "where name='appurl_sdk14'");
    echo"<script>showindex=4;lightyear.notify('盒子版APP升级设置成功！', 'success', 3000);</script>";
} 

// APP设置
if (isset($_POST['decodersel']) && isset($_POST['buffTimeOut'])) {
    $decoder = $_POST['decodersel'];
    $buffTimeOut = $_POST['buffTimeOut'];
    $trialdays = $_POST['trialdays'];
    $vpntimes = $_POST['vpntimes'];
    $sameip_user = $_POST['sameip_user'];
    if ($trialdays == 0) {
        $db->mSet("luo2888_users", "exp=0", "where status=-1");
    } 
    $db->mSet("luo2888_config", "value='$sameip_user'", "where name='max_sameip_user'");
    $db->mSet("luo2888_config", "value='$vpntimes'", "where name='vpntimes'");
    $db->mSet("luo2888_config", "value='$decoder'", "where name='decoder'");
    $db->mSet("luo2888_config", "value='$trialdays'", "where name='trialdays'");
    $db->mSet("luo2888_config", "value='$buffTimeOut'", "where name='buffTimeOut'");
    echo"<script>showindex=4;lightyear.notify('设置成功！', 'success', 3000);</script>";
} 

if (isset($_POST['submitsetver'])) {
	$db->mSet("luo2888_config", "value=value+1", "where name='setver'");
    echo"<script>showindex=4;lightyear.notify('推送成功，用户下次启动将恢复默认设置！', 'success', 3000);</script>";
} 

// APP提示信息
if (isset($_POST['submittipset'])) {
    $failureurl = $_POST['failureurl'];
    $deniedurl = $_POST['deniedurl'];
    $tiploading = $_POST['tiploading'];
    $tipusernoreg = $_POST['tipusernoreg'];
    $tipuserexpired = $_POST['tipuserexpired'];
    $tipuserforbidden = $_POST['tipuserforbidden'];
    $db->mSet("luo2888_config", "value='$failureurl'", "where name='failureurl'");
    $db->mSet("luo2888_config", "value='$deniedurl'", "where name='deniedurl'");
    $db->mSet("luo2888_config", "value='$tiploading'", "where name='tiploading'");
    $db->mSet("luo2888_config", "value='$tipusernoreg'", "where name='tipusernoreg'");
    $db->mSet("luo2888_config", "value='$tipuserexpired'", "where name='tipuserexpired'");
    $db->mSet("luo2888_config", "value='$tipuserforbidden'", "where name='tipuserforbidden'");
    echo"<script>showindex=4;lightyear.notify('提示信息已修改！', 'success', 3000);</script>";
} 

// 天气设置
if (isset($_POST['weaapi_id']) && isset($_POST['weaapi_key'])) {
    $weaapi_id = $_POST['weaapi_id'];
    $weaapi_key = $_POST['weaapi_key'];
    if (empty($weaapi_id)) {
        echo("<script>showindex=0;lightyear.notify('请填写天气APP_ID！', 'danger', 3000);</script>");
    } else if (empty($weaapi_key)) {
        echo("<script>showindex=0;lightyear.notify('请填写天气APP_KEY！', 'danger', 3000);</script>");
    } else {
        if (isset($_POST['showwea'])) {
            $showwea = 1;
        } else {
            $showwea = 0;
        } 
        $db->mSet("luo2888_config", "value='$showwea'", "where name='showwea'");
        $db->mSet("luo2888_config", "value='$weaapi_id'", "where name='weaapi_id'");
        $db->mSet("luo2888_config", "value='$weaapi_key'", "where name='weaapi_key'");
        if ($showwea == 0) {
            echo"<script>showindex=0;lightyear.notify('天气显示已关闭！', 'success', 3000);</script>";
        } else {
            echo"<script>showindex=0;lightyear.notify('天气显示已开启！', 'success', 3000);</script>";
        } 
    } 
} 

// 公告设置
if (isset($_POST['submit']) && isset($_POST['adtext'])) {
    $adtext = $_POST['adtext'];
    $adtext_free = $_POST['adtext_free'];
    $showtime = $_POST['showtime'];
    $showinterval = $_POST['showinterval'];
    $adinfo = $_POST['adinfo'];
	$db->mSet("luo2888_config", "value='$adinfo'", "where name='adinfo'");
	$db->mSet("luo2888_config", "value='$adtext'", "where name='adtext'");
	$db->mSet("luo2888_config", "value='$adtext_free'", "where name='adtext_free'");
	$db->mSet("luo2888_config", "value='$showtime'", "where name='showtime'");
	$db->mSet("luo2888_config", "value='$showinterval'", "where name='showinterval'");
    echo"<script>showindex=0;lightyear.notify('公告修改成功！', 'success', 3000);</script>";
} 

// APP信息设置
if (isset($_POST['submitappinfo'])) {
    $keyproxy = $_POST['keyproxy'];
    $app_sign = $_POST['app_sign'];
    $app_b64key = $_POST['app_b64key'];
    $app_appname = $_POST['app_appname'];
    $app_packagename = $_POST['app_packagename'];
    $db->mSet("luo2888_config", "value='$keyproxy'", "where name='keyproxy'");
    $db->mSet("luo2888_config", "value='$app_b64key'", "where name='app_b64key'");
    $db->mSet("luo2888_config", "value='$app_sign'", "where name='app_sign'");
    $db->mSet("luo2888_config", "value='$app_appname'", "where name='app_appname'");
    $db->mSet("luo2888_config", "value='$app_packagename'", "where name='app_packagename'");
    echo"<script>showindex=4;lightyear.notify('保存成功！', 'success', 3000);</script>";
} 

// 支付宝API设置
if (isset($_POST['alipay_set'])) {
    $alipay_appid = $_POST['alipay_appid'];
    $alipay_publickey = $_POST['alipay_publickey'];
    $alipay_privatekey = $_POST['alipay_privatekey'];
	$db->mSet("luo2888_config", "value='$alipay_appid'", "where name='alipay_appid'");
	$db->mSet("luo2888_config", "value='$alipay_publickey'", "where name='alipay_publickey'");
	$db->mSet("luo2888_config", "value='$alipay_privatekey'", "where name='alipay_privatekey'");
    echo"<script>showindex=0;lightyear.notify('提交修改成功！', 'success', 3000);</script>";
} 

// 上传APP背景图片
if (isset($_POST['submit']) && isset($_FILES["splash"])) {
    if ($_FILES["splash"]["type"] == "image/png") {
        if ($_FILES["splash"]["error"] > 0) {
            echo "Error: " . $_FILES["splash"]["error"];
        } else {
            $savefile = "../images/" . $_FILES["splash"]["name"];
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
    unlink('../images/' . $file);
    echo"<script>showindex=1;lightyear.notify('删除成功！', 'success', 3000);</script>";
} 

// 用户授权设置
if (isset($_POST['submitauthor'])) {
    $needauthor = $_POST['needauthor'];
    if ($needauthor == 1) {
        $needauthor = 0;
        echo"<script>showindex=4;lightyear.notify('用户授权已关闭！', 'success', 3000);</script>";
    } else {
        $needauthor = 1;
        echo"<script>showindex=4;lightyear.notify('用户授权已开启！', 'success', 3000);</script>";
    } 
    $db->mSet("luo2888_config", "value='$needauthor'", "where name='needauthor'");
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
$files = glob("../images/*.png");

// 初始化变量
$adinfo = $db->mGet("luo2888_config", "value", "where name='adinfo'");
$adtext = $db->mGet("luo2888_config", "value", "where name='adtext'");
$adtext_free = $db->mGet("luo2888_config", "value", "where name='adtext_free'");
$dataver = $db->mGet("luo2888_config", "value", "where name='dataver'");
$appver = $db->mGet("luo2888_config", "value", "where name='appver'");
$appver_sdk14 = $db->mGet("luo2888_config", "value", "where name='appver_sdk14'");
$setver = $db->mGet("luo2888_config", "value", "where name='setver'");
$dataurl = $db->mGet("luo2888_config", "value", "where name='dataurl'");
$appurl = $db->mGet("luo2888_config", "value", "where name='appurl'");
$appurl_sdk14 = $db->mGet("luo2888_config", "value", "where name='appurl_sdk14'");
$showtime = $db->mGet("luo2888_config", "value", "where name='showtime'");
$showinterval = $db->mGet("luo2888_config", "value", "where name='showinterval'");
$splash = $db->mGet("luo2888_config", "value", "where name='splash'");
$needauthor = $db->mGet("luo2888_config", "value", "where name='needauthor'");
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

?>
