<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);

require_once "../config.php";
require_once "../api/common/notify.class.php";
$db = Config::GetIntance();

$alipayPublicKey = $db->mGet("luo2888_config", "value", "where name='alipay_publickey'");  //支付宝公钥
$notify = new NotifyService($alipayPublicKey);
$notifyres = $notify->rsaCheck($_POST,$_POST['sign_type']);  //验证签名

if ($notifyres === true) {
    $nowtime = time();
    $postid = $_POST['out_trade_no'];
    if ($row = $db->mGetRow("luo2888_payment", "userid,meal,days", "where order_id='$postid'")) {
        $days = $row['days'];
        $meal = $row['meal'];
        $userid = $row['userid'];
        $exp = strtotime(date("Y-m-d"), time()) + 86400 * $days;
        $userexp = $db->mGet("luo2888_users", "exp", "where name='$userid'");
        $ustatus = $db->mGet("luo2888_users", "status", "where name='$userid'");
        if ($days == 999) {
	          $db->mSet("luo2888_users", "status=999,exp=$exp,author='支付宝',authortime=$nowtime,marks='已支付',meal='$meal'", "where name='$userid'");
        } else if ($ustatus == 1 && $userexp > time()) {
            $exp = $userexp + 86400 * $days;
            $db->mSet("luo2888_users", "status=1,exp=$exp,author='支付宝',authortime=$nowtime,marks='已续费',meal='$meal'", "where name='$userid'");
	      } else {
            $db->mSet("luo2888_users", "status=1,exp=$exp,author='支付宝',authortime=$nowtime,marks='已支付',meal='$meal'", "where name='$userid'");
	      }
        $db->mSet("luo2888_payment", "paidtime=$nowtime,expiredtime=$exp,status=1", "where order_id='$postid'");
        exit('success') ;
    } else {
        exit('error') ;
    } 
}

exit('error') ;

?>