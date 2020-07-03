var iPlayerElement = '<iframe id="playerframe" width="100%" height="100%" allowtransparency="true" border="0" marginwidth="0" marginheight="0" frameborder="0" scrolling="no" src="/webplay.php?cid=' + sourid + '&cname=' + cname + '&token=' + token + '" allowfullscreen="allowfullscreen" mozallowfullscreen="mozallowfullscreen" msallowfullscreen="msallowfullscreen" oallowfullscreen="oallowfullscreen" webkitallowfullscreen="webkitallowfullscreen"></iframe>';

$('#J_player').prepend(iPlayerElement);

var playerNode = $('#J_player'),
iframeElement = $("#playerframe").get(0).contentWindow,
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
    iframeNode = '<iframe id="playerframe" width="100%" height="100%" allowtransparency="true" border="0" marginwidth="0" marginheight="0" frameborder="0" scrolling="no" src="/webplay.php?cid=' + data + '&cname=' + cname + '&token=' + token + '" allowfullscreen="allowfullscreen" mozallowfullscreen="mozallowfullscreen" msallowfullscreen="msallowfullscreen" oallowfullscreen="oallowfullscreen" webkitallowfullscreen="webkitallowfullscreen"></iframe>';
    playerNode.html(iframeNode);

    var iframeElement = $("#playerframe").get(0).contentWindow;

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