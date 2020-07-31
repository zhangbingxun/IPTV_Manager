<?php

header("Content-Type:text/plain;chartset=utf-8");

        $url = 'http://85.208.109.224/channels/tvbfc';
        echo file_get_contents($url);
//echo 'su' . time() . md5('live_' . time());
?>