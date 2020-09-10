<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);

require_once "config.php";
$db = Config::GetIntance();

$appver = $db->mGet("luo2888_config", "value", "where name='appver'");
$boxver = $db->mGet("luo2888_config", "value", "where name='boxver'");
$appurl = $db->mGet("luo2888_config", "value", "where name='appurl'");
$boxurl = $db->mGet("luo2888_config", "value", "where name='boxurl'");
$panurl = $db->mGet("luo2888_config", "value", "where name='panurl'");

// 初始化
$appname = $db->mGet("luo2888_config", "value", "where name='app_appname'");
$web_about = $db->mGet("luo2888_config", "value", "where name='web_about'");
$web_title = $db->mGet("luo2888_config", "value", "where name='web_title'");
$web_appinfo = $db->mGet("luo2888_config", "value", "where name='web_appinfo'");
$web_copyright = $db->mGet("luo2888_config", "value", "where name='web_copyright'");
$web_description = $db->mGet("luo2888_config", "value", "where name='web_description'");
?>

<!DOCTYPE HTML>
<html>
    <head>
        <title><?php echo $appname; ?> - <?php echo $web_title; ?></title>
        <meta charset="utf-8" />
        <meta name="keywords" content="<?php echo $appname; ?>" />
        <meta name="description" content="<?php echo $web_description; ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
        <meta name="author" content="luo2888" />
        <meta name="renderer" content="webkit" />
        <link rel="icon" href="/views/images/favicon.ico" type="image/ico">
        <link rel="stylesheet" href="/views/css/index.css" />
        <noscript><link rel="stylesheet" href="/views/css/noscript.css" /></noscript>
    </head>
    <body class="is-preload">
        <section id="Loading"></section>
        <div id="wrapper">
            <header id="header">
                <div class="logo">
                    <img class="icon" style="width: 92%;height: 92%;" src="/views/images/logo.png">
                </div>
                <div class="content">
                    <div class="inner">
                        <h1><?php echo $appname; ?> - <?php echo $web_title; ?></h1>
                        <p><?php echo $web_description; ?></p>
                    </div>
                </div>
                <nav>
                    <ul>
                        <li><a href="/zblist.php">在线观看</a></li>
                        <li><a href="#android">软件下载</a></li>
                        <li><a href="#meals">套餐购买</a></li>
                        <li><a href="#channels">频道列表</a></li>
                        <li><a href="#about">关于我们</a></li>
                    </ul>
                </nav>
            </header>

            <div id="main">
                <article id="android">
                    <h2 class="major"><?php echo $appname; ?> Android版本</h2>
                    <?php echo $web_appinfo; ?>
                    <p>下載：</p>
                    <p>
                        <ul class="actions">
                            <li><a class="button primary icon solid fa-download"  href="<?php echo $panurl; ?>">网盘下载地址</a></li>
                        </ul>
                    </p>
                    <p>
                        <ul class="actions">
                            <li><a class="button primary icon solid fa-download" href="<?php echo $appurl; ?>">手机版（点播+直播） <?php echo 'V' . $appver; ?> 下载</a></li>
                        </ul>
                    </p>
                    <p>
                        <ul class="actions">
                            <li><a class="button primary icon solid fa-download"  href="<?php echo $boxurl; ?>">电视/盒子版（直播） <?php echo 'V' . $boxver; ?> 下载</a></li>
                        </ul>
                    </p>
                </article>

                <article id="about">
                    <h2 class="major">关于我们</h2>
                    <?php echo $web_about; ?>
                </article>

                <article id="meals">
                    <h2 class="major">套餐购买</h2>
<?php 
$result = $db->mQuery("select * from luo2888_meals where sale=1");
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
echo '<h3 class="major">' . $row["name"] . '&nbsp&nbsp' . $row["amount"] . '元' . '&nbsp&nbsp收视' . $row["days"] . '天</h3>';
echo '<p>' . $row["content"] . '</p>';
}
?>
                    <p>
                        <ul class="actions">
                        <li><a class="button primary icon solid fa-download"  href="/payment.php">在线购买</a></li>
                        </ul>
                    </p>
                </article>

                <article id="channels">
                    <h2 class="major">频道列表</h2>
<?php 
$result = $db->mQuery("SELECT id,name FROM luo2888_category where type <> 'web' order by id");
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
echo '<span class="button" style="margin: 1% 2.5%;"><a href="#' . $row["id"] . '">' . $row["name"] . '</a></span>';
}
?>
                </article>

<?php 
$result = $db->mQuery("SELECT id,name FROM luo2888_category order by id");
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
$channeldata = $db->mQuery("SELECT distinct name FROM luo2888_channels where category='" . $row["name"] . "'order by id");
echo '<article id="' . $row["id"] . '">';
echo '<h2 class="major">' . $row["name"] . '</h2>';
$i = 1;
echo '<p>';
while ($channel = mysqli_fetch_array($channeldata, MYSQLI_ASSOC)) {
$channelname = $channel["name"];
echo $i . '、' . $channelname . '<br>';
$i++;
}
echo '</p>';
echo '</article>';
}
?>

                <article id="develop">
                    <h2 class="major">正在建设</h2>
                    <p>该项目正在建设中，请等待上线 ...</p>
                </article>
            </div>

            <footer id="footer">
                <p class="copyright"><?php echo $web_copyright; ?></p>
            </footer>
        </div>

        <div id="bg"></div>

        <script src="/views/js/jquery.min.js"></script>
        <script src="/views/js/browser.min.js"></script>
        <script src="/views/js/breakpoints.min.js"></script>
        <script src="/views/js/util.js"></script>
        <script src="/views/js/index.js"></script>
        <script type="text/javascript">
            document.onreadystatechange=function(){
                if(document.readyState=='complete'){
                    $("#Loading").fadeOut();
                }
        }
        </script>
    </body>
</html>
