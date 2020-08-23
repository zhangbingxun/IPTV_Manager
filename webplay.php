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

if (strstr($playurl, "flv") != false) {
    $player = "FlvJsPlayer";
} else {
    $player = "HlsJsPlayer";
}

if ($token != md5($channel . $userip)) {
    exit('根据系统判定，您属于盗链行为！');
}
?>
<!DOCTYPE html>
<html>
    
    <head>
        <meta charset="utf-8" />
        <style>
            *{ margin:0;padding:0; }
            body { background:#fff; }
            #Loading{ background:url("/views/images/loading_red.gif")50% no-repeat #fff; width:100%; height:100%; overflow:hidden; position:fixed; left:0; top:0; z-index:100; }
            #liveplayer{ width: 100%;height: 220px; }
        </style>
    </head>
    
    <body>
        <section id="Loading"></section>
        <div id="liveplayer"></div>
    </body>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/xgplayer@2.9.14/browser/index.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/xgplayer-flv.js@2.1.2/browser/index.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/xgplayer-hls.js@2.2.3/browser/index.js"></script>
    <script>
        let player = new <?php echo $player; ?>({
            id: 'liveplayer',
            lang: 'zh-cn',
            isLive: true,
            airplay: true,
            autoplay: true,
            playsinline: true,
            keyShortcut: 'on',
            closeVideoTouch: true,
            width: window.innerWidth,
            height: window.innerHeight,
            url: '<?php echo $playurl; ?>'
        });
        player.on('requestFullscreen',
        function() {
            if (/Android|webOS|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                screen.orientation.lock('landscape');
                document.getElementById('liveplayer').style.height = "100%";
            }
        });
        player.on('exitFullscreen',
        function() {
            if (/Android|webOS|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                screen.orientation.lock('landscape');
                document.getElementById('liveplayer').style.height = "220px";
            }
        });
    </script>
    <script type="text/javascript">
        document.onreadystatechange = function() {
            if (document.readyState == 'complete') {
                $("#Loading").fadeOut();
            }
        }
    </script>
</html>