<?php

        header("Content-Type:text/plain;chartset=utf-8");
$str = 'http://tx.hls.huya.com/src/94525224-2655537474-11405446604132450304-2704233350-10057-A-0-1-imgplus_2000.m3u8?wsSecret=79ced731978aad03f09a5b4442fbd717&wsTime=5f2d4eb5&fm=RFdxOEJjSjNoNkRKdDZUWV8kMF8kMV8kMl8kMw%3D%3D&ctype=tars_mobile&fs=bgct&&sphdcdn=al_7-tx_3-js_3-ws_7-bd_2-hw_2&sphdDC=huya&sphd=264_*&t=103';

$server = 'http://tx.hls.huya.com/src/';
preg_match('/src\/(.*?)_2000/i',$str,$stream);
preg_match('/wsTime=(.*?)&/i',$str,$wsTime);
preg_match('/fm=(.*?)&/i',$str,$fm);
preg_match('/(.*?)_/i', base64_decode(urldecode($fm[1])),$defm);
$time = '15967225474152150';
$wsSecret = md5($defm[1] . '_0_' . $stream[1] . '_' . $time . '_' . $wsTime[1]);

echo $server . $stream[1] . '.m3u8?wsSecret=' . $wsSecret . '&wsTime=' . $wsTime[1] . '&u=0' . '&seqid=' . $time . '&ctype=huya_tars&fs=bgct&sphdcdn=al_7-tx_3-js_3-ws_7-bd_2-hw_2&sphdDC=huya&sphd=264_*&t=103';

?>