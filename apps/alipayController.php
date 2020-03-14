<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);

header('Content-type:text/html; Charset=utf-8');
require_once "config.php";
$db = Config::GetIntance();

/*** 配置信息 ***/
$signType = 'RSA2';  //签名算法类型，支持RSA2和RSA
$myurl=dirname('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
$notifyUrl = $myurl . '/apps/alipayController.php?notify';  //异步回调地址
$appid = $db->mGet("luo2888_config", "value", "where name='alipay_appid'");  //应用APPID
$appname = $db->mGet("luo2888_config", "value", "where name='app_appname'");  //应用名称
$rsaPrivateKey=$db->mGet("luo2888_config", "value", "where name='alipay_privatekey'");  //商户私钥
$alipayPublicKey=$db->mGet("luo2888_config", "value", "where name='alipay_publickey'");  //支付宝公钥

//应用的APPID、支付宝公钥、商户私钥生成参考
//https://open.alipay.com 账户中心->密钥管理->开放平台密钥
//生成密钥参考：
//https://docs.open.alipay.com/291/105971
//https://docs.open.alipay.com/200/105310
/*** 配置结束 ***/

if (isset($_GET['notify'])) {
	$notify = new NotifyService($alipayPublicKey);
	$notifyres = $notify->rsaCheck($_POST,$_POST['sign_type']);  //验证签名
	if($notifyres===true){
	    //处理你的逻辑，例如获取订单号$_POST['out_trade_no']，订单金额$_POST['total_amount']
		echo 'success';exit();
	}
	echo 'error';exit();
}

if (isset($_POST['dopay'])) {
	$orderName = $appname . '套餐';  //订单标题
	$payAmount = 0.02;  //付款金额
	$outTradeNo = uniqid(mt_rand(), true);  //生成订单号
	$aliPay = new AlipayService();
	$aliPay->setAppid($appid);
	$aliPay->setNotifyUrl($notifyUrl);
	$aliPay->setRsaPrivateKey($rsaPrivateKey);
	$aliPay->setTotalFee($payAmount);
	$aliPay->setOutTradeNo($outTradeNo);
	$aliPay->setOrderName($orderName);
	
	$result = $aliPay->doPay();
	$result = $result['alipay_trade_precreate_response'];
	if($result['code'] && $result['code']=='10000'){
	    //生成二维码
	    $url = 'http://qr.topscan.com/api.php?text=' . $result['qr_code'] . '&bg=ffffff&fg=000000&pt=1c73bd&m=10&w=400&el=1&inpt=1eabfc&logo=https://t.alipayobjects.com/tfscom/T1Z5XfXdxmXXXXXXXX.png';
	    echo "<img src='{$url}' style='width:300px;'><br>";
	    echo '二维码内容：'.$result['qr_code'];
	}else{
	    echo $result['msg'].' : '.$result['sub_msg'];
	}
}
?>