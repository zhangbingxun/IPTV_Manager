$(function(){
    $(window).scroll(function(){
        height = $(window).scrollTop();
        if(height > 100){
            $('.J-tabset').addClass("navfiex");
        }else{
            $('.J-tabset').removeClass("navfiex");
        };
    });
    $(".top-search").on("click", function(e) {
        $('#seindex').toggle();
    });
});
