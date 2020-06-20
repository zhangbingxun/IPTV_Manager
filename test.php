<?php

require_once "config.php";
$db = Config::GetIntance();

header("Content-Type:text/plain;chartset=utf-8");
$id = $_GET['id'];
$channelNumber = 1;
$category = '测试频道';
$channelname = $db->mGet("luo2888_channels", "name", "where category='$category'");

        $result = $db->mQuery("SELECT id,name,url FROM luo2888_channels where category='$category' order by id");
        $nameArray = array();
        while ($row = mysqli_fetch_array($result)) {
            if (!in_array($row['name'], $nameArray)) {
                $nameArray[] = $row['name'];
            } 
            if (strstr($row['url'], "http") != false) {
                $sourceArray[$row['name']][] = $myurl . '?tvplay&id=' . $row['id'];
            } else {
                $sourceArray[$row['name']][] = $row['url'];
            }
        } 
        $objCategory = (Object)null;
        $objChannel = (Object)null;
        $channelArray = array();
        for($i = 0;
            $i < count($nameArray);
            $i++) {
            $objChannel = (Object)null;
            $objChannel->num = $channelNumber;
            $objChannel->name = $nameArray[$i];
            $objChannel->source = $sourceArray[$nameArray[$i]];
            $channelArray[] = $objChannel;
            $channelNumber++;
        } 
        $objCategory->name = $alisname;
        $objCategory->psw = $psw;
        $objCategory->data = $channelArray;
        unset($row,$nameArray, $sourceArray, $objChannel);
        mysqli_free_result($result);

print_r($objCategory);

?>
