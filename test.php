<?php
// 头部
header("Content-Type:text/plain;chartset=utf-8");

        $url = 'http://94.191.97.114:10080/gzgd/AuthIndexStandard?nns_cp_id=&nns_video_type=1&nns_version=1.0.0.0.0.SC-GZGD-APAD.0.0_Release&nns_func=check_auth_and_get_media_by_media&nns_output_type=&nns_user_id=01010116091201000313&nns_user_agent=&nns_webtoken=&nns_video_id=851355&nns_tag=26';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $content = curl_exec($curl);
        preg_match('|<media id="(.*?)" url="(.*?)"|',$content, $data);
        $playurl = $data[2];
echo $content;
?>