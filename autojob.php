<?php
require_once "config.php";
require_once "api/common/converter.class.php";
$db = Config::getIntance();
$time = date("Y-m-d H:i:s");
ini_set("max_execution_time", 0);

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
                    $sources[] = $source;
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
        $category=$row['name'];
        $listurl=$row['url'];
        $srclist = file_get_contents($listurl);
        if (!empty($srclist)) {
            $addlist = add_channel_list($category, $srclist);
            if ($addlist !== -1) {
                $db->mInt("luo2888_record","id,name,ip,loc,time,func","null,'自动任务','系统','系统','$time','更新列表$category 成功！'");
            } else {
                $db->mInt("luo2888_record","id,name,ip,loc,time,func","null,'自动任务','系统','系统','$time','更新列表$category 失败！'");
            } 
        } 
    }
}

ini_set("max_execution_time", 300);
exit;

?>