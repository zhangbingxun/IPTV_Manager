<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);

require_once "config.php";
$db = Config::GetIntance();
$remote = new GetIP();
$userip = $remote -> getuserip();
$token = $_GET['token'];
$channelid = $_GET['cid'];
$channel = $_GET['cname'];
$playurl = $db->mGet("luo2888_channels", "url", "where id='$channelid'");

if ($token != md5($channel . $userip)) {
    exit('根据系统判定，您属于盗链行为！');
}
?>
<!DOCTYPE html>
<html>
    
    <head>
        <meta charset="utf-8" />
        <style>
            *{margin:0;padding:0;} body {background:#fff;} #Loading{ background:url("/views/images/loading_red.gif")50% no-repeat #fff; width:100%; height:100%; overflow:hidden; position:fixed; left:0; top:0; z-index:100; }
        </style>
        <script src="https://libs.baidu.com/jquery/1.9.1/jquery.min.js">
        </script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/dplayer@latest/dist/DPlayer.min.css">
        <script src="https://cdn.jsdelivr.net/npm/flv.js@latest/dist/flv.min.js">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/hls.js@latest/dist/hls.min.js">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/dplayer@latest/dist/DPlayer.min.js">
        </script>
        <script>
            var dplayer = new DPlayer({
                container: document.getElementById('liveplayer'),
                live: true,
                hotkey: true,
                lang: 'zh-cn',
                video: {
                    url: '<?php echo $playurl; ?>',
                },
            });
            dplayer.on('fullscreen',
            function() {
                if (/Android|webOS|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                    screen.orientation.lock('landscape');
                }
            });
        </script>
    </head>
    
    <body>
        <section id="Loading">
        </section>
        <div id="liveplayer" style="width: 100%;height: 220px;">
        </div>
    </body>
    <script type="text/javascript">
        document.onreadystatechange = function() {
            if (document.readyState == 'complete') {
                $("#Loading").fadeOut();
            }
        }
    </script>

</html>