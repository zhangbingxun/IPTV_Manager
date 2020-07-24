<?php

header("Content-Type:text/plain;chartset=utf-8");

$m3u8 = file_get_contents('http://live2.cdn.iptv8k.org/live/id115/s.m3u8');
echo str_replace(",\n",",\nhttp://live2.cdn.iptv8k.org/live/id115/",$m3u8);
?>