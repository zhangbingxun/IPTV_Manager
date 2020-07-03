<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);

require_once "api/common/cacher.class.php";
require_once "config.php";
$db = Config::GetIntance();

if (isset($_GET['keywords'])) {
    $nowcate = !empty($_GET['cate']) ? $_GET['cate'] : $nowcate = $db->mGet("luo2888_category", "name", "where type='web' order by id");
    $keywords = trim($_GET['keywords']);
    $where = "category='$nowcate' and (name like '%$keywords%')";
} else if (empty($_GET['cate'])) {
    $nowcate = $db->mGet("luo2888_category", "name", "where type='web' order by id");
    $where = "category='$nowcate'";
} else {
    $nowcate = $_GET['cate'];
    $where = "category='$nowcate'";
}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="applicable-device" content="mobile" />
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />
        <title>
            肥米TV - 電視直播
        </title>
        <meta name="keywords" content="肥米TV" />
        <meta name="description" content="肥米TV，是一款優秀的OTT移動電視直播平台，除電視直播外，還有精彩的電影電視劇輪播、點播，給你最佳的娛樂體驗。功能全面增強，操作簡單快捷，隨時隨地觀看電視的同時，還有福利內容不時提供。" />
        <meta name="author" content="luo2888" />
        <meta name="renderer" content="webkit" />
        <link rel="icon" href="/views/images/favicon.ico" type="image/ico">
        <link rel="stylesheet" type="text/css" href="/views/css/zhibo.css" />
        <script src="/views/js/zepto.js" type="text/javascript">
        </script>
        <script src="/views/js/zblist.js" type="text/javascript">
        </script>
    </head>
    
    <body>
        <header class="header-map">
            <a href="/" class="logo">
                <i class="icon">
                    肥米TV
                </i>
            </a>
            <div class="top-map-noslide">
                <ul>
                    <li>
                        <a href="/">
                            肥米TV
                        </a>
                        <i class="icon">
                        </i>
                        <a href="/zblist.php">
                            电视直播
                        </a>
                        <i class="icon">
                        </i>
                    </li>
                </ul>
            </div>
            <a rel="nofollow" href="javascript:;" class="top-search">
                <i class="icon">
                    搜索
                </i>
            </a>
        </header>
        <div class="clearfix" id="seindex">
            <form method="GET" style="display: block;">
                <input type="hidden" name="cate" value="<?php echo $nowcate; ?>" />
                <input type="text" name="keywords" class="searchText" placeholder="搜索关键词" />
                <input type="submit" class="searchBtn" value="" />
            </form>
        </div>
        <div class="bg">
        </div>
        <div class="wrap">
            <div class="nav-box">
                <ul class="J-tabset">
                <?php
                    $func = "SELECT name FROM luo2888_category where type='web' order by id";
                    $result = $db->mQuery($func);
                    while($row = mysqli_fetch_array($result)) {
                        echo "
                            <li>
                                <a href='?cate=" . $row['name'] . "'>" . $row['name'] . "</a>
                            </li>
                        ";
                    }
                    unset($row);
                ?>
                </ul>
            </div>
            <div class="list-box J-medal">
                <style>
                    h3.area {line-height: 40px;height: 40px;border-bottom: 1px solid #ddd;text-indent:
                    0.6em;font-size: 18px;color: #fff;background: #3a9;}
                </style>
                <h3 class="area">
                    <?php 
                         if (isset($_GET['keywords'])) {
                             $nowcate = "搜索结果";
                         }
                         echo $nowcate;
                     ?>
                </h3>
                <ul class="xhbox zblist">
                    <?php
					        						$func = "SELECT distinct name FROM luo2888_channels where $where order by id";
				          						$result = $db->mQuery($func);
										         while($row = mysqli_fetch_array($result)) {
                            echo "
                            <li>
                                <a href='zblive.php?cate=" . $nowcate . "&channel=" . $row['name'] . "' title='" . $row['name'] . "在线直播'>" . $row['name'] . "</a>
                            </li>
                            ";
                        }
                        unset($row);
                    ?>
                    <div class="clear">
                    </div>
                </ul>
            </div>
            <div class="clear">
            </div>
        </div>
        <footer class="foot">
            <div class="foot-border">
                <div class="footer-link">
                    <div class="footer">
                        友情链接：
                        <a href="https://www.luo2888.cn" class="sred">
                            luo2888的工作室
                        </a>
                        <a href="https://seller.luo2888.cn">
                            VNet云
                        </a>
                    </div>
                    <span>
                        Copyright &copy; 2020 肥米TV luo2888.cn
                    </span>
                    <span style="display:none">
                    </span>
                </div>
            </div>
        </footer>
    </body>
</html>