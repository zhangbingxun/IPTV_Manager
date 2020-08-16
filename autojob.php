<?php
require_once "config.php";
require_once "api/common/converter.class.php";
$db = Config::getIntance();
$time = date("Y-m-d H:i:s");
ini_set("max_execution_time", 0);

function getlist($url) {
   $curl = curl_init();
   curl_setopt($curl, CURLOPT_URL, $url);
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
   curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
   curl_setopt($curl, CURLOPT_USERAGENT, 'FMITV_AutoJobs');
   curl_setopt($curl, CURLOPT_TIMEOUT, 60);
   curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
   $output = curl_exec($curl);
   curl_close($curl);
   return $output;
}

// 增加频道列表
function add_channel_list($cname, $srclist) {
    global $db;
    $converter = new ZhConvert();
    if (!empty($srclist && $cname)) {
        $db->mDel("luo2888_channels", "where category='$cname'");
        $repetnum = 0;
        $rows = explode("\n", $srclist);
        $rows = preg_replace('# ,#', ',', $rows);
        $rows = preg_replace('#\r#', '', $rows);
        $rows = preg_replace('/\#genre\#/', '', $rows);
        foreach($rows as $row) {
            if (strpos($row, ',') !== false) {
                $ipos = strpos($row, ',');
                $channelname = substr($row, 0, $ipos);
                $channelname = $converter -> zh_hant_to_zh_hans($channelname);
                $source = substr($row, $ipos + 1);
                if (strpos($source, '#') !== false) {
                    $sources = explode("#", $source);
                } else {
                    $sources[0] = $source;
                }
                foreach ($sources as $src) {
                    $src2 = str_replace("\"", "", $src);
                    $src2 = str_replace("\'", "", $src2);
                    $src2 = str_replace("}", "", $src2);
                    $src2 = str_replace("{", "", $src2);
                    $channelurl = $db->mQuery("SELECT url from luo2888_channels");
                    while ($url = mysqli_fetch_array($channelurl)) {
                        if ($src2 == $url[0]) {
                            $src2 = '';
                            $repetnum++;
                        } 
                    } 
                    unset($url);
                    mysqli_free_result($channelurl);
                    if ($channelname != '' && $src2 != '') {
                        $db->mInt("luo2888_channels", "id,name,url,category", "NULL,'$channelname','$src2','$cname'");
                    } 
                } 
            } 
        } 
        unset($rows, $srclist);
        return $repetnum;
    } 
    return -1;
} 

// 更新外部列表
$result = $db->mQuery("SELECT name,url FROM luo2888_category where url is not null");
if ($result){
    while($row = mysqli_fetch_array($result)) {
        $starttime = explode(' ',microtime());
        $category=$row['name'];
        $listurl=$row['url'];
        $srclist = getlist($listurl);
        if (!empty($srclist)) {
            $addlist = add_channel_list($category, $srclist);
            $endtime = explode(' ',microtime());
            $thistime = $endtime[0]+$endtime[1]-($starttime[0]+$starttime[1]);
            $thistime = round($thistime,3);
            if ($addlist !== -1) {
                $db->mInt("luo2888_record","id,name,ip,loc,time,func","null,'自动任务','系统','系统','$time','更新列表$category 成功！耗时$thistime 秒'");
            } else {
                $db->mInt("luo2888_record","id,name,ip,loc,time,func","null,'自动任务','系统','系统','$time','更新列表$category 失败！耗时$thistime 秒'");
            } 
        } 
    }
}

ini_set("max_execution_time", 300);
exit;

?>