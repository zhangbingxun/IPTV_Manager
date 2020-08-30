<?php

    header("Content-Type:text/plain;chartset=utf-8");
    $poststr = $_POST['channel'];
    $str = json_decode($poststr, true);
    if (!empty($str)) {
        print_r($str);
    } else {
        exit('failed!');
    }
?>