<?php
include_once "../config.php";

if ($_SESSION['channeladmin'] == 0) {
    echo"<script>alert('你无权访问此页面！');history.go(-1);</script>";
    exit();
} 

?>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);
// 对分类进行重新排序
$categorytype = $_GET['categorytype'];

function sort_id() {
    global $categorytype;
    if ($categorytype == 'default') {
        $numCount = 1;
    } else {
        $numCount = 50;
    } 
    $result = mysqli_query($GLOBALS['conn'], "SELECT * from luo2888_category where type='$categorytype' order by id");
    while ($row = mysqli_fetch_array($result)) {
        $name = $row['name'];
        mysqli_query($GLOBALS['conn'], "UPDATE luo2888_category set id=$numCount where name='$name'");
        unset($name);
        $numCount++;
    } 
    unset($row);
    mysqli_free_result($result);
} 
sort_id();
// 检测上下移的ID参数是否存在
function chk_sort_id() {
    global $categorytype, $minid, $maxid;
    $result = mysqli_query($GLOBALS['conn'], "SELECT min(id),max(id) from luo2888_category where type='$categorytype'");
    if ($row = mysqli_fetch_array($result)) {
        $minid = $row['min(id)'];
        $maxid = $row['max(id)'];
    } 
} 
chk_sort_id(); 
// 增加频道列表
function add_channel_list($pd, $listurl) {
    $getlist = file_get_contents($listurl);
    if (!empty($getlist)) {
        mysqli_query($GLOBALS['conn'], "delete from luo2888_channels where category='$pd'");
        $rows = explode("\n", $getlist);
        $rows = preg_replace('# #', '', $rows);
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
                        $channelurl = mysqli_query($GLOBALS['conn'], "SELECT url from luo2888_channels order by id");
                        while ($url = mysqli_fetch_array($channelurl)) {
                            if ($src2 == $url['url']) {
                                $src2 = '';
                            } 
                        } 
                        unset($url);
                        mysqli_free_result($channelurl);
                        if ($channelname != '' && $src2 != '') {
                            mysqli_query($GLOBALS['conn'], "INSERT INTO luo2888_channels VALUES (null,'$channelname','$src2','$pd')");
                        } 
                    } 
                } else {
                    $src2 = str_replace("\"", "", $source);
                    $src2 = str_replace("\'", "", $src2);
                    $src2 = str_replace("}", "", $src2);
                    $src2 = str_replace("{", "", $src2);
                    $channelurl = mysqli_query($GLOBALS['conn'], "SELECT url from luo2888_channels order by id");
                    while ($url = mysqli_fetch_array($channelurl)) {
                        if ($src2 == $url['url']) {
                            $src2 = '';
                        } 
                    } 
                    unset($url);
                    mysqli_free_result($channelurl);
                    if ($channelname != '' && $src2 != '') {
                        mysqli_query($GLOBALS['conn'], "INSERT INTO luo2888_channels VALUES (null,'$channelname','$src2','$pd')");
                    } 
                } 
            } 
        } 
        unset($rows, $getlist);
        return 0;
    } 
    return 1;
} 

if (isset($_GET['pd'])) {
    $pd = $_GET['pd'];
} else {
    $result = mysqli_query($GLOBALS['conn'], "SELECT name from luo2888_category order by id");
    if ($row = mysqli_fetch_array($result)) {
        $pd = $row['name'];
        unset($row);
        mysqli_free_result($result);
    } else {
        mysqli_free_result($result);
        $pd = '';
    } 
} 

mysqli_query($GLOBALS['conn'], "set names utf8");

if (isset($_POST['submit']) && isset($_POST['pd']) && isset($_POST['srclist'])) {
    $pd = $_POST['pd'];
    $srclist = $_POST['srclist'];
    $showindex = $_POST['showindex'];

    mysqli_query($GLOBALS['conn'], "delete from luo2888_channels where category='$pd'");
    $rows = explode("\r\n", $srclist);
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
                    $channelurl = mysqli_query($GLOBALS['conn'], "SELECT url from luo2888_channels order by id");
                    while ($url = mysqli_fetch_array($channelurl)) {
                        if ($src2 == $url['url']) {
                            $src2 = '';
                        } 
                    } 
                    if ($channelname != '' && $src2 != '') {
                        mysqli_query($GLOBALS['conn'], "INSERT INTO luo2888_channels VALUES (null,'$channelname','$src2','$pd')");
                    } 
                } 
            } else {
                $src2 = str_replace("\"", "", $source);
                $src2 = str_replace("\'", "", $src2);
                $src2 = str_replace("}", "", $src2);
                $src2 = str_replace("{", "", $src2);
                $channelurl = mysqli_query($GLOBALS['conn'], "SELECT url from luo2888_channels order by id");
                while ($url = mysqli_fetch_array($channelurl)) {
                    if ($src2 == $url['url']) {
                        $src2 = '';
                    } 
                } 
                if ($channelname != '' && $src2 != '') {
                    mysqli_query($GLOBALS['conn'], "INSERT INTO luo2888_channels VALUES (null,'$channelname','$src2','$pd')");
                } 
            } 
        } 
    } 
    unset($rows, $srclist);
    echo"<script>showindex=$showindex;alert('保存成功');</script>。";
} 

if (isset($_POST['submit']) && isset($_POST['category'])) {
    $category = $_POST['category'];
    $cpass = $_POST['cpass'];
    if ($category == "") {
        echo "<script>alert('类别名称不能为空');</script>";
    } else {
        $result = mysqli_query($GLOBALS['conn'], "SELECT max(id) from luo2888_category where type='$categorytype'");
        if ($row = mysqli_fetch_array($result)) {
            if ($row[0] > 0) {
                $numCount = $row[0] + 1;
            } 
        } 
        unset($row);
        mysqli_free_result($result);
        $sql = "SELECT name FROM luo2888_category where name='$category'";
        $result = mysqli_query($GLOBALS['conn'], $sql);
        if (mysqli_fetch_array($result)) {
            mysqli_free_result($result);
            echo "<script>showindex=$showindex;alert('该栏目已经存在');</script>";
        } else {
            mysqli_query($GLOBALS['conn'], "INSERT INTO luo2888_category (id,name,psw,type) VALUES ($numCount,'$category','$cpass','$categorytype')");
            $result = mysqli_query($GLOBALS['conn'], "SELECT * from luo2888_category");
            $showindex = mysqli_num_rows($result)-1;
            echo "<script>showindex=$showindex;alert('增加类别$category 成功');</script>";
            $pd = $category;
            mysqli_free_result($result);
        } 
    } 
} 
// 增加外部列表
if (isset($_POST['addthirdlist'])) {
    $category = $_POST['thirdlistcategory'];
    $listurl = $_POST['thirdlisturl'];
    if ($category == "") {
        echo "<script>alert('类别名称不能为空');</script>";
    } else {
        $result = mysqli_query($GLOBALS['conn'], "SELECT max(id) from luo2888_category where type='$categorytype'");
        if ($row = mysqli_fetch_array($result)) {
            if ($row[0] > 0) {
                $numCount = $row[0] + 1;
            } 
        } 
        unset($row);
        mysqli_free_result($result);
        $sql = "SELECT name FROM luo2888_category where name='$category'";
        $result = mysqli_query($GLOBALS['conn'], $sql);
        if (mysqli_fetch_array($result)) {
            mysqli_free_result($result);
            echo "<script>showindex=$showindex;alert('该栏目已经存在');</script>";
        } else {
            mysqli_query($GLOBALS['conn'], "INSERT INTO luo2888_category (id,name,psw,type,url) VALUES ($numCount,'$category','$cpass','$categorytype','$listurl')");
            $result = mysqli_query($GLOBALS['conn'], "SELECT * from luo2888_category where $categorytype");
            $showindex = mysqli_num_rows($result)-1;
            mysqli_free_result($result);
            if (add_channel_list($category, $listurl) == 0) {
                echo "<script>showindex=$showindex;alert('增加列表$category 成功');</script>";
            } else {
                echo "<script>showindex=$showindex;alert('增加列表$category 失败');</script>";
                mysqli_query($GLOBALS['conn'], "delete from luo2888_category where name='$category'");
            } 
        } 
    } 
} 
// 更新外部列表
if (isset($_POST['updatelist'])) {
    $category = $_POST['thirdlist'];
    if ($category == "") {
        echo "<script>alert('列表名称不能为空');</script>";
    } else {
        $result = mysqli_query($GLOBALS['conn'], "SELECT * from luo2888_category where $categorytype");
        $showindex = mysqli_num_rows($result)-1;
        mysqli_free_result($result);
        $listurl = mysqli_query($GLOBALS['conn'], "SELECT url from luo2888_category where name='$category'");
        if ($row = mysqli_fetch_array($listurl)) {
            $listurl = $row['url'];
        } 
        if (add_channel_list($category, $listurl) == 0) {
            echo "<script>showindex=$showindex;alert('更新列表$category 成功');</script>";
        } else {
            echo "<script>showindex=$showindex;alert('更新列表$category 失败');</script>";
        } 
    } 
} 

if (isset($_POST['submit_deltype']) && isset($_POST['category'])) {
    $category = $_POST['category'];
    $showindex = $_POST['showindex'];
    if ($category == "") {
        echo "<script>alert('类别名称不能为空');</script>";
    } else {
        $result = mysqli_query($GLOBALS['conn'], "SELECT id from luo2888_category where name='$category'");
        if ($row = mysqli_fetch_array($result)) {
            $categoryid = $row[0];
            mysqli_query($GLOBALS['conn'], "UPDATE luo2888_category set id=id-1 where id>$categoryid");
        } 
        $sql = "delete from luo2888_category where name='$category'";
        mysqli_query($GLOBALS['conn'], $sql);
        mysqli_query($GLOBALS['conn'], "delete from luo2888_channels where category='$category'");
        sort_id();
        echo "<script>showindex=$showindex-1;alert('$category 删除成功');</script>";
    } 
} 

if (isset($_POST['submit_modifytype']) && isset($_POST['category'])) {
    $category = $_POST['category'];
    $cpass = $_POST['cpass'];
    $showindex = $_POST['showindex'];
    $category0 = $_POST['typename0'];
    if ($category == "") {
        echo "<script>alert('类别名称不能为空');</script>";
    } else {
        mysqli_query($GLOBALS['conn'], "update luo2888_category set name='$category',psw='$cpass' where name='$category0'");
        mysqli_query($GLOBALS['conn'], "UPDATE luo2888_channels set category='$category' where category='$category0'");
        echo "<script>showindex=$showindex;alert('$category 修改成功');</script>";
        $pd = $category;
    } 
} 

if (isset($_POST['submit_moveup']) && isset($_POST['category'])) {
    $category = $_POST['category'];
    $showindex = $_POST['showindex'];
    $result = mysqli_query($GLOBALS['conn'], "SELECT id from luo2888_category where name='$category'");
    if ($row = mysqli_fetch_array($result)) {
        $id = $row['id'];
        $preid = $id-1;
        if ($preid >= $minid) {
            mysqli_query($GLOBALS['conn'], "update luo2888_category set id=id+1	where id=$preid");
            mysqli_query($GLOBALS['conn'], "update luo2888_category set id=id-1	where name='$category'");
            unset($row);
            mysqli_free_result($result);
            echo "<script>showindex=$showindex-1;</script>";
        } else {
            echo "<script>showindex=$showindex-1;alert('已经上移到最顶了！！')</script>";
        } 
    } 
} 

if (isset($_POST['submit_movedown']) && isset($_POST['category'])) {
    $category = $_POST['category'];
    $showindex = $_POST['showindex'];
    $result = mysqli_query($GLOBALS['conn'], "SELECT id from luo2888_category where name='$category'");
    if ($row = mysqli_fetch_array($result)) {
        $id = $row['id'];
        $nextid = $id + 1;
        if ($nextid <= $maxid) {
            mysqli_query($GLOBALS['conn'], "update luo2888_category set id=id-1	where id=$nextid'");
            mysqli_query($GLOBALS['conn'], "update luo2888_category set id=id+1	where name='$category'");
            unset($row);
            mysqli_free_result($result);
            echo "<script>showindex=$showindex+1;</script>";
        } else {
            unset($row);
            mysqli_free_result($result);
            echo "<script>showindex=$showindex;alert('已经下移到最底了！！')</script>";
        } 
    } 
} 

if (isset($_POST['submit_movetop']) && isset($_POST['category'])) {
    $category = $_POST['category'];
    $result = mysqli_query($GLOBALS['conn'], "SELECT Min(id) from luo2888_category where type='$categorytype'");
    if ($row = mysqli_fetch_array($result)) {
        $id = $row[0]-1;
        mysqli_query($GLOBALS['conn'], "update luo2888_category set id=$id	where name='$category'");
        sort_id();
        echo "<script>showindex=0;</script>";
    } 
    mysqli_free_result($result);
} 

if (isset($_POST['submit']) && isset($_POST['ver'])) {
    $updateinterval = $_POST['updateinterval'];
    if (isset($_POST['autoupdate'])) {
        mysqli_query($GLOBALS['conn'], "update luo2888_appdata set autoupdate=1,updateinterval=$updateinterval");
    } else {
        $ver = $_POST['ver'];
        $sql = "update luo2888_appdata set dataver=$ver,autoupdate=0";
        mysqli_query($GLOBALS['conn'], $sql);
    } 
    echo "<script>alert('保存成功');</script>";
} 

if (isset($_POST['checkpdname'])) {
    mysqli_query($GLOBALS['conn'], "UPDATE luo2888_category set enable=0");
    foreach ($_POST['enable'] as $pdenable) {
        mysqli_query($GLOBALS['conn'], "UPDATE luo2888_category set enable=1 where name='$pdenable'");
    } 
} 

$sql = "SELECT dataver,appver,autoupdate,updateinterval FROM luo2888_appdata";
$result = mysqli_query($GLOBALS['conn'], $sql);
if ($row = mysqli_fetch_array($result)) {
    $ver = $row['dataver'];
    $versionname = $row['appver'];
    $autoupdate = $row['autoupdate'];
    $updateinterval = $row['updateinterval'];
} else {
    $ver = "0";
    $autoupdate = 0;
    $updateinterval = 0;
} 
unset($row);
mysqli_free_result($result);

if ($autoupdate == 1) {
    $checktext = "checked='true'";
} else {
    $checktext = '';
} 

?>