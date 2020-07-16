<?php

header("Content-Type: video/mp2t");
$tsurl = $_GET['ts']

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $tsurl);
curl_setopt($curl, CURLOPT_HEADER, 1);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
    'playtoken: 6cd3c4d0f834c26c32182fe053c6b360'
));
$contents = curl_exec($curl);
curl_close($curl);

print_r($contents);

?>