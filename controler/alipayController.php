<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);

require_once "config.php"; 
require_once "api/common/alipay.class.php";

// 获取地址
$myurl = 'http://';
if($_SERVER['HTTPS'] == 'on') {
    $myurl = 'https://';
}
$myurl .= $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
$user = $_GET['id'];
$db = Config::GetIntance();
$signType = 'RSA2';  //签名算法类型，支持RSA2和RSA
$notifyUrl = dirname($myurl) . '/controler/alipayNotifier.php';  //异步回调地址
$appid = $db->mGet("luo2888_config", "value", "where name='alipay_appid'");  //应用APPID
$appname = $db->mGet("luo2888_config", "value", "where name='app_appname'");  //应用名称
$rsaPrivateKey = $db->mGet("luo2888_config", "value", "where name='alipay_privatekey'");  //商户私钥

if (isset($_POST['dopay'])) {
    $userid=$_POST['userid'];
    $mealid = $_POST['mealid'];
    $mealname = $db->mGet("luo2888_meals", "name", "where id=$mealid");
    $amount = $db->mGet("luo2888_meals", "amount", "where id=$mealid");
	$days = $db->mGet("luo2888_meals", "days", "where id=$mealid");
	$orderName = $appname . $mealname;  //订单标题
	$payAmount = $amount;  //付款金额
	$outTradeNo = uniqid(rand(100000000000000, 999999999999999));  //生成订单号
	$orderTime = time();
	$aliPay = new AlipayService();
	$aliPay->setAppid($appid);
	$aliPay->setNotifyUrl($notifyUrl);
	$aliPay->setRsaPrivateKey($rsaPrivateKey);
	$aliPay->setTotalFee($payAmount);
	$aliPay->setOutTradeNo($outTradeNo);
	$aliPay->setOrderName($orderName);
    if (empty($days) || empty($mealid)) {
        exit("<script>$.alert({title: '警告',content: '请选择要购买的套餐和天数！',type: 'orange',buttons: {confirm: {text: '确定',btnClass: 'btn-primary',action: function(){window.location.href='payment.php';}}}});</script>");
    } else {
    		$db->mInt("luo2888_payment", "userid,order_id,meal,days,ordertime,paidtime,status", "$userid,'$outTradeNo',$mealid,$days,$orderTime,0,0");
    }
	$result = $aliPay->doPay();
	$result = $result['alipay_trade_precreate_response'];
}

if (isset($_GET['checkpay'])) {
	$checkpay = $_GET['checkpay'];
	$result = $db->mGet("luo2888_payment", "status", "where userid=$checkpay");
    if($result != 1){
		exit ("<script>$.alert({title: '支付失败',content: '支付状态为未支付，如果您已支付，请联系管理员！',type: 'red',buttons: {confirm: {text: '返回',btnClass: 'btn-default',action: function(){history.go(-1);}}}});</script>");
    } else {
		exit ("<script>$.alert({title: '支付成功',content: '订单已支付成功，请重新启动APP！',type: 'green',buttons: {confirm: {text: '确定',btnClass: 'btn-primary',action: function(){window.location.href='payment.php';}}}});</script>");
    }
}
?>