<?php
// 头部
header("Content-Type:text/plain;chartset=utf-8");

$jsondata = file_get_contents("channels.txt");
$channeldata = json_decode($jsondata, true);

foreach($channeldata['return_live'] as $catelist) {
    print_r("\n" . '# ' . $catelist['name'] . ' #' . "\n\n");
    foreach($catelist['channel'] as $channellist) {
        if (is_array($channellist)) {
            print_r($channellist['title'] . ',vid=ublive#id=' . $channellist['id'] . "\n");
        } 
    } 
} 

?>