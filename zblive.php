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
    $ischrome = '<p align="center" style="margin: 5px;">您的浏览器正使用Chrome内核，要正常观看节目内容需要在属性页增加参数</p><p align="center">--allow-running-insecure-content --disable-web-security --user-data-dir=C:\Browser</p>';
}

// 初始化
$appname = $db->mGet("luo2888_config", "value", "where name='app_appname'");
$web_title = $db->mGet("luo2888_config", "value", "where name='web_title'");
$web_copyright = $db->mGet("luo2888_config", "value", "where name='web_copyright'");
$web_description = $db->mGet("luo2888_config", "value", "where name='web_description'");
?>
<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="applicable-device" content="mobile">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <title>
        <?php echo $channel; ?> - <?php echo $appname; ?> - <?php echo $web_title; ?>
    </title>
    <meta name="keywords" content="<?php echo $appname; ?>,<?php echo $channel; ?>直播" />
    <meta name="description" content="<?php echo $web_description; ?>" />
    <meta name="author" content="luo2888" />
    <meta name="renderer" content="webkit" />
    <link rel="icon" href="/views/images/favicon.ico" type="image/ico">
    <link rel="stylesheet" href="/views/css/zhibo.css?t=<?php echo time(); ?>">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.0/dist/jquery.min.js"></script>
</head>
<body>
    <header class="header-map">
        <a href="/" class="logo">
            <i class="icon">
                <?php echo $appname; ?>
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
    <div class="bg"></div>
    <section>
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
        <?php echo $ischrome; ?>
        <hr>
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
                <a href="/#android" target="_blank">
                    下载APP
                </a>
            </div>
            <div class="clear">
            </div>
        </div>
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
                        echo '<h3 style="text-align: center;overflow:hidden;margin-bottom: 15px;width: 100%;">正在播放：' . $epgdata['data'][$epgpos]['name'] . '</h3>';
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
                    <?php echo $web_copyright; ?>
                </span>
            </div>
        </div>
    </footer>
</body>

</html>