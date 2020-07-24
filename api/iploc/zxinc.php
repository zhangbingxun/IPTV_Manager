<?php

function loc($ip) {
    retry:
        $url = "http://ip.zxinc.org/api.php?type=json&ip=$ip";
        $jsonobj = file_get_contents($url);
        if (empty($jsonobj)) {
            $jsonobj = (Object)null;
            goto retry;
        }
        $jsonobj = trim($jsonobj, chr(239) . chr(187) . chr(191));
        $ipobj = json_decode($jsonobj);
        $region = $ipobj->data->country;
        $local = $ipobj->data->local;
        $region = str_replace("中国", "", $region);
        $local = str_replace("公众宽带", "", $local);
        $local = str_replace("(全省通用)", "", $local);
        $local = str_replace("CMNET网络", "", $local);
        $local = str_replace("中国电信", "电信", $local);
        $local = str_replace("中国联通", "联通", $local);
        $local = str_replace("中国移动", "移动", $local);
        $isp = str_replace("数据上网公共出口", "", $local);
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