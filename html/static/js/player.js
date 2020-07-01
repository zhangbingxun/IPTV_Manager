var iPlayerElement = '<video id="player" width="100%" height="100%" preload="auto" controls><source src="http://tv.ncist.edu.cn:281/live/cctv10hd.flv"/></video>';

$('#J_player').prepend(iPlayerElement);

var playerNode = $('#J_player'),
iframeElement = $("#player").get(0).contentWindow,
bqState = 'yes';

if (iframeElement.attachEvent) {
    iframeElement.attachEvent("onload",
    function() {
        iframeElement.checkckplay(bqState);
    })
} else {
    iframeElement.onload = function() {
        iframeElement.checkckplay(bqState);
    }
}
$(".playlist").change(function() {
    var data = $(this).val();
    iframeNode = '';
    playerNode.html(iframeNode);

    var iframeElement = $("#player").get(0).contentWindow;

    if (iframeElement.attachEvent) {
        iframeElement.attachEvent("onload",
        function() {
            iframeElement.checkckplay(bqState);
        })
    } else {
        iframeElement.onload = function() {
            iframeElement.checkckplay(bqState);
        }
    }

});

$('[data-player]').on('click',
function(event) {
    var $this = $(this),
    iframeNode,
    data = $this.attr('data-player');

    if ($this.hasClass('btn-syc-select')) {
        return false
    } else {

        iframeNode = '';
        playerNode.html(iframeNode);

        var iframeElement = $("#player").get(0).contentWindow;

        if (iframeElement.attachEvent) {
            iframeElement.attachEvent("onload",
            function() {
                iframeElement.checkckplay(bqState);
            })
        } else {
            iframeElement.onload = function() {
                iframeElement.checkckplay(bqState);
            }
        }

        $this.siblings().removeClass('btn-syc-select');
        $this.addClass('btn-syc-select');
    }
})