<?php

require_once "config.php";
$db = Config::GetIntance();

header("Content-Type:text/plain;chartset=utf-8");
$id = $_GET['id'];
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, "http://www.ybe123.com");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.88 Safari/537.36");
    $curlobj = curl_exec($curl);
    preg_match_all('/href="\/flv.php\?(.*?)(id=.*?)">(.*?)</i', $curlobj, $linkobj);
print_r($linkobj);

?>
