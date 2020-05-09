<?php
require_once "config.php";
$db = Config::getIntance();
$time = date("Y-m-d H:i:s");

// 增加频道列表
function add_channel_list($cname, $srclist) {
    global $db;
    if (!empty($srclist && $cname)) {
        $db->mDel("luo2888_channels", "where category='$cname'");
        $repetnum = 0;
        $rows = explode("\n", $srclist);
        $rows = preg_replace('# ,#', ',', $rows);
        $rows = preg_replace('#\r#', '', $rows);
        $rows = preg_replace('/高清/', '', $rows);
        $rows = preg_replace('/FHD/', '', $rows);
        $rows = preg_replace('/HD/', '', $rows);
        $rows = preg_replace('/SD/', '', $rows);
        $rows = preg_replace('/\[.*?\]/', '', $rows);
        $rows = preg_replace('/\#genre\#/', '', $rows);
        $rows = preg_replace('/ver\..*?\.m3u8/', '', $rows);
        $rows = preg_replace('/t\.me.*?\.m3u8/', '', $rows);
        $rows = preg_replace("/https(.*)www.bbsok.cf[^>]*/", "", $rows);
        foreach($rows as $row) {
            if (strpos($row, ',') !== false) {
                $ipos = strpos($row, ',');
                $channelname = substr($row, 0, $ipos);
                $source = substr($row, $ipos + 1);
                if (strpos($source, '#') !== false) {
                    $sources = explode("#", $source);
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
                } else {
                    $src2 = str_replace("\"", "", $source);
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
        if (!empty($category)) {
            $listurl = $db->mGetRow("luo2888_category", "url", "where name='$category'");
            $addlist = add_channel_list($category, $srclist);
            if ($addlist !== -1) {
                $db->mInt("luo2888_adminrec","id,name,ip,loc,time,func","null,'自动任务','系统','系统','$time','更新列表$category 成功！'");
            } else {
                $db->mInt("luo2888_adminrec","id,name,ip,loc,time,func","null,'自动任务','系统','系统','$time','更新列表$category 失败！'");
            } 
        } 
    }
}
?>