<?php

// 头部
header("Content-Type:text/plain;chartset=utf-8");

$jsondata = file_get_contents("channels.txt");
$jsondata = preg_replace('#\.php\?#', '#', $jsondata);
$jsondata = preg_replace('#ab:\/\/#', 'vid=lttv_ab#tid=', $jsondata);
$jsondata = preg_replace('#ten:\/\/#', 'vid=lttv_ten#tid=', $jsondata);
$jsondata = preg_replace('#bd:\/\/#', 'vid=lttv_bd#tid=', $jsondata);
$jsondata = preg_replace('#hd:\/\/#', 'vid=lttv_hd#tid=', $jsondata);
$channeldata = json_decode($jsondata, true);

foreach($channeldata as $catelist) {
    print_r("\n" . '# ' . $catelist['name'] . ' #' . "\n\n");
    foreach($catelist as $channellist) {
        if (is_array($channellist)) {
            foreach($channellist as $channel) {
                if (is_array($channel) && strstr($channel['source'][0],"vid") != false) {
                    print_r($channel['name'] . ',' . $channel['source'][0] . "\n");
                    if (!empty($channel['source'][1])) {
                        print_r($channel['name'] . ',' . $channel['source'][1] . "\n");
                    } 
                    if (!empty($channel['source'][2])) {
                        print_r($channel['name'] . ',' . $channel['source'][2] . "\n");
                    } 
                    if (!empty($channel['source'][3])) {
                        print_r($channel['name'] . ',' . $channel['source'][3] . "\n");
                    } 
                    if (!empty($channel['source'][4])) {
                        print_r($channel['name'] . ',' . $channel['source'][4] . "\n");
                    } 
                    if (!empty($channel['source'][5])) {
                        print_r($channel['name'] . ',' . $channel['source'][5] . "\n");
                    } 
                } 
            } 
        } 
    } 
} 

?>