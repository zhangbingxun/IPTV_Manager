<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);

require_once "config.php";
$db = Config::GetIntance();
$remote = new GetIP();
$userip = $remote -> getuserip();
$category = $_GET['cate'];
$channel = $_GET['channel'];
$epgjson = file_get_contents(mUrl() . "/api/common/tvguide.php?channel=" . $channel);
$epgdata =  json_decode($epgjson, true);
$epgpos = $epgdata['pos'];

if (strstr($_SERVER['HTTP_USER_AGENT'], "Windows") && strstr($_SERVER['HTTP_USER_AGENT'], "Chrome")) {
    $ischrome = '<p align="center">您的浏览器正使用Chrome内核，要正常观看节目内容需要在属性页增加参数</p><p align="center">--allow-running-insecure-content --disable-web-security --user-data-dir=C:\Browser</p>';
}

?>
<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="applicable-device" content="mobile">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <title>
        <?php echo $channel; ?> - 肥米TV - 在线直播
    </title>
    <meta name="keywords" content="肥米TV" />
    <meta name="description" content="肥米TV，是一款優秀的OTT移動電視直播平台，除電視直播外，還有精彩的電影電視劇輪播、點播，給你最佳的娛樂體驗。功能全面增強，操作簡單快捷，隨時隨地觀看電視的同時，還有福利內容不時提供。" />
    <meta name="author" content="luo2888" />
    <meta name="renderer" content="webkit" />
    <link rel="icon" href="/views/images/favicon.ico" type="image/ico">
    <link rel="stylesheet" href="/views/css/zhibo.css?t=<?php echo time(); ?>">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
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
                    <a href="/zblist.php?cate=<?php echo $category; ?>">
                        <?php echo $category; ?>
                    </a>
                    <i class="icon">
                    </i>
                    <a href="?cate=<?php echo $category; ?>&channel=<?php echo $channel; ?>">
                        <?php echo $channel; ?>
                    </a>
                    <i class="icon">
                    </i>
                </li>
            </ul>
        </div>
    </header>
    <div class="bg">
    </div>
    <section>
        <!--play-bx-->
        <div class="play-bx">
            <div class="play-bd">
                <div class="player" id="J_player">
                <?php
                    $cid = $db->mGet("luo2888_channels", "id", "where name='$channel' and category='$category'");
                    echo "
                    <script>var sourid='$cid',cname='$channel',token='" . md5($channel . $userip) . "';</script> 
                    ";
                ?>
                </div>
            </div>
        </div>
        <!--/play-bx-->
        <div class="tab-syb">
            <span>
                切换线路:
            </span>
            <div class="buttons custom-select">
                <select class="playlist">
                <?php
                    $i = 1;
                    $func = "SELECT id FROM luo2888_channels where name='$channel' and category='$category' order by id";
                    $result = $db->mQuery($func);
                    while($row = mysqli_fetch_array($result)) {
                        echo "
                    <option value='" . $row['id'] . "'>
                        线路" . $i . "
                    </option>
                        ";
                        $i++;
                    }
                    unset($row);
                ?>
                </select>
            </div>
        </div>
        <script src="/views/js/player.js?t=<?php echo time(); ?>"></script>
        <h3 align="center">
            网页端仅供体验测试使用，更多频道请下载客户端观看↓
        </h3>
        <div class="zbtool">
            <div class="bintro">
                <a>
                    <?php echo $channel; ?>
                </a>
            </div>
            <div class="report">
                <a href="/index.php/#android" target="_blank">
                    下载APP
                </a>
            </div>
            <div class="clear">
            </div>
        </div>
        <?php echo $ischrome; ?>
        <div class="intro_desc">
            <h3>
                <b>
                    节目预告
                </b>
            </h3>
            <div id="endtext">
                <table style="width: 100%;">
                <?php
                    if (!empty($epgdata['data'])) {
                        echo '<h3 style="text-align: center;overflow:scroll;margin-bottom: 15px;">正在播放：' . $epgdata['data'][$epgpos]['name'] . '</h3>';
                        foreach ($epgdata['data'] as &$program) {
                            echo '<tr><td><font size=4px>' . $program['starttime'] . '</font>&nbsp;&nbsp;&nbsp;&nbsp;' . $program['name'] . '</td></tr>';
                        }
                    } else {
                         echo '<tr><td align="center">暂无数据</td></tr>';
                    }
                ?>
                </table>
            </div>
        </div>
        <div class="clear">
        </div>
    </section>
    <div class="clear">
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