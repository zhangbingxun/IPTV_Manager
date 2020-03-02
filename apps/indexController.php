<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);

include_once "../config.php";

$result = mysqli_query($GLOBALS['conn'], "select count(*) from luo2888_users");
if ($row = mysqli_fetch_array($result)) {
    $userCount = $row[0];
    $pageCount = ceil($row[0] / $recCounts);
} else {
    $userCount = 0;
    $pageCount = 1;
} 
unset($row);
mysqli_free_result($result);

$todayTime = strtotime(date("Y-m-d"), time());
$result = mysqli_query($GLOBALS['conn'], "select count(*) from luo2888_users where lasttime>$todayTime");
if ($row = mysqli_fetch_array($result)) {
    $todayuserCount = $row[0];
} else {
    $todayuserCount = 0;
}
unset($row);
mysqli_free_result($result);

$result = mysqli_query($GLOBALS['conn'], "select count(*) from luo2888_users where status>0 and authortime>$todayTime");
if ($row = mysqli_fetch_array($result)) {
    $todayauthoruserCount = $row[0];
} else {
    $todayauthoruserCount = 0;
} 
unset($row);
mysqli_free_result($result);

$result = mysqli_query($GLOBALS['conn'], "select count(*) from luo2888_users where vpn>0");
if ($row = mysqli_fetch_array($result)) {
    $exceptionuserCount = $row[0];
} else {
    $exceptionuserCount = 0;
} 
unset($row);
mysqli_free_result($result);

$result = mysqli_query($GLOBALS['conn'], "select count(*) from luo2888_category");
if ($row = mysqli_fetch_array($result)) {
    $categoryCount = $row[0];
} else {
    $categoryCount = 0;
} 
unset($row);
mysqli_free_result($result);

$result = mysqli_query($GLOBALS['conn'], "select count(*) from luo2888_channels");
if ($row = mysqli_fetch_array($result)) {
    $channelCount = $row[0];
} else {
    $channelCount = 0;
} 
unset($row);
mysqli_free_result($result);


$result = mysqli_query($GLOBALS['conn'], "select count(*) from luo2888_epg");
if ($row = mysqli_fetch_array($result)) {
    $epgCount = $row[0];
} else {
    $epgCount = 0;
} 
unset($row);
mysqli_free_result($result);

$result = mysqli_query($GLOBALS['conn'], "select count(*) from luo2888_users where isvip=1");
if ($row = mysqli_fetch_array($result)) {
    $vipCount = $row[0];
} else {
    $vipCount = 0;
} 
unset($row);
mysqli_free_result($result);

?>