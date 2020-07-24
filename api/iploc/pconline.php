<?php

function loc($ip) {
    retry:
    $url = "http://whois.pconline.com.cn/ip.jsp?ip=$ip";
    $ipobj = iconv("gbk", "utf-8", file_get_contents($url));
    if (empty($ipobj)) {
        $ipobj = (Object)null;
        goto retry;
    }
    $str = preg_replace('#\r#', '', $ipobj);
    $str = preg_replace('#\n#', '', $str);
    $str = explode(" ", $str);
    $region = $str[0];
    $isp = $str[1];
    $obj = (Object)null;
    $obj->region = $region;
    if (!empty($region)) {
        $obj->city = '，';
    }
    if (!empty($isp)) {
        $obj->isp = $isp;
    } else {
        $obj->city = '';
        $obj->isp = '';
    }
    $json['data'] = $obj;
    return json_encode($json, JSON_UNESCAPED_UNICODE);
}

?>