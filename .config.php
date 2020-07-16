<?php
header("Content-type: text/html; charset=utf-8");

define("PANEL_MD5_KEY", "tvkey_"); //面板密码MD5加密秘钥

class Config {
    // 私有属性,防止在类外引入存放对象
    private static $conn = false;
    private $host;
    private $port;
    private $user;
    private $pass;
    private $database;
    private $charset;
    private $link; 
    // 私有属性,防止在类外使用new关键字实例化对象
    private function __construct() {
        $this->host = 'localhost'; //数据库服务器
        $this->port = '3306'; //数据库端口
        $this->user = ''; //数据库帐号
        $this->pass = ''; //数据库密码
        $this->database = 'tvpanel'; //数据库名称
        $this->charset = 'utf8'; //数据库字符集
        $this->db_connect(); //连接数据库
        $this->db_charset(); //设置字符集
    } 
    // 防止在类外通过克隆成另一个对象
    private function __clone() {
        die('此类不允许克隆');
    } 
    // 连接数据库
    private function db_connect() {
        $this->link = mysqli_connect($this->host . ':' . $this->port, $this->user, $this->pass, $this->database);
        if (!$this->link) {
            exit("数据库连接失败，请检查错误！");
        } 
    } 
    // 设置字符集
    private function db_charset() {
        mysqli_query($this->link, "set names {$this->charset}");
    } 
    // 实例化对象
    public static function GetIntance() {
        if (self::$conn == false) {
            self::$conn = new self;
        } 
        return self::$conn;
    } 
    // 执行SQL语句
    public function mQuery($func) {
        if ($result = mysqli_query($this->link, $func)) {
            return $result;
        } else {
            return false;
        } 
    } 
    // 过滤字符串
    public function mEscape_string($value) {
        if ($result = mysqli_real_escape_string($this->link, $value)) {
            return $result;
        } else {
            return false;
        } 
    } 
    // 获取全部数组
    public function mGetRow($table, $value, $func = "where 1") {
        if ($result = $this->mQuery("SELECT $value FROM $table $func")) {
            $row = mysqli_fetch_array($result);
            return $row;
        } else {
            return false;
            exit("<script>$.alert('mGetRow数据库有错误！$value,$table,$func');</script>");
        } 
    } 
    // 获取单条数据
    public function mGet($table, $value, $func = "where 1") {
        if ($result = $this->mQuery("SELECT $value FROM $table $func")) {
            $row = mysqli_fetch_array($result);
            return $row[0];
        } else {
            return false;
            exit("<script>$.alert('mGet数据库有错误！$value,$table,$func');</script>");
        } 
    } 
    // 检查全部数组
    public function mCheckRow($table, $value, $func = "where 1") {
        if ($result = $this->mQuery("SELECT $value FROM $table $func")) {
            $row = mysqli_fetch_array($result);
            return $row;
        } else {
            return false;
        } 
    } 
    // 检查单条数据
    public function mCheckOne($table, $value, $func = "where 1") {
        if ($result = $this->mQuery("SELECT $value FROM $table $func")) {
            $row = mysqli_fetch_array($result);
            return $row[0];
        } else {
            return false;
        } 
    } 
    // 更新数据
    public function mSet($table, $value, $func = "where 1") {
        $this->mQuery("UPDATE $table SET $value $func");
    } 
    // 删除数据
    public function mDel($table, $func = "where 1") {
        $this->mQuery("DELETE FROM $table $func");
    } 
    // 插入数据
    public function mInt($table, $name, $value) {
        $this->mQuery("INSERT INTO $table ($name) VALUES($value)");
    } 
    // 关闭数据库连接
    public function mClose() {
        mysqli_close($this->link);
    } 
    // 销毁对象并断开数据库连接
    public function __destruct() {
        $this->mClose();
    } 
} 

function mCurl($url,$method,$refurl,$post_data){
    $UserAgent = 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36';
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_TIMEOUT, 2);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_USERAGENT, $UserAgent);
    if ($method == "POST") {
        curl_setopt($curl, CURLOPT_REFERER, $refurl); 
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
    }
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

function lanzouUrl($url) {
    $ruleMatchDetailInList = "~ifr2\"\sname=\"[\s\S]*?\"\ssrc=\"\/(.*?)\"~";
    preg_match_all($ruleMatchDetailInList, mCurl($url,null,null,null),$link);
    $index = 0;
    for($i=0;$i<count($link[1]);$i++){
        if($link[1][$i]!="fn?v2"){
            $index = $i;
            break;
        }
    }

    $refurl = "https://www.lanzous.com/".$link[1][$index];
    $ruleMatchDetailInList = "~var ajaxup = '([^\]]*)';//~";
    preg_match($ruleMatchDetailInList, mCurl($refurl,null,null,null),$segment);
    $post_data = array(
        "action" => "downprocess",
        "sign" => $segment[1],
        "ves" => 1,
        "p" => ""
    );

    $downjson = mCurl("https://www.lanzous.com/ajaxm.php","POST",$refurl,$post_data);
    $linkobj = json_decode($downjson);
    if ($linkobj->dom == "") {
        return false;
    } else {
        return $linkobj->dom . "/file/" . $linkobj->url;
    }
}

class GetIP {

    // 获取API地址
    function mUrl() {
        $Url = 'http://';
        if($_SERVER['HTTPS'] == 'on') {
            $Url = 'https://';
        }
        $Url .= $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
        return $Url;
    }

    function getuserip() {
        $IP = $_SERVER['REMOTE_ADDR'];
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) AND preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
            foreach ($matches[0] AS $xip) {
                if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {
                    $IP = $xip;
                    break;
                } 
            } 
        }
        if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            $IP = $_SERVER['HTTP_CF_CONNECTING_IP'];
        }
        return $IP;
    } 

    // 获取IP归属地
    function getloc($db,$userip) {
        $ipchk = $db->mGet("luo2888_config", "value", "where name='ipchk'");
        $locapi = dirname($this -> mUrl()) . '/api/iploc';

        switch ($ipchk) {
            case 1:
                $iploc =  file_get_contents("$locapi/qqzeng.php?ip=$userip");
                break;
            case 2:
                $iploc =  file_get_contents("$locapi/ipcn.php?ip=$userip");
                break;
            case 3:
                $iploc =  file_get_contents("$locapi/taobao.php?ip=$userip");
                break;
            case 4:
                $iploc =  file_get_contents("$locapi/pconline.php?ip=$userip");
                break;
            case 5:
                $iploc = file_get_contents("$locapi/zxinc.php?ip=$userip");
                break;
        }
        return $iploc;
    }

} 

?>