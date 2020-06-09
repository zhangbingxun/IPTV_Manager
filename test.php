<?php    

header("Content-Type:text/plain;chartset=utf-8");
$id = $_GET['id'];
        $obj = file_get_contents("channels/ublive.txt");
        preg_match('/http:\/\/(.*?)\/live\/' . $id . '\/(.*?)\/index\.m3u8/i', $obj, $linkobj);
print_r($linkobj);
?>
