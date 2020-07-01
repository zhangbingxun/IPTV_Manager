$(function(){
	$(window).scroll(function(){
          height = $(window).scrollTop();
   	  	  if(height > 100){
   	  	  	$('.J-tabset').addClass("navfiex");
   	  	  }else{
   	  	  	$('.J-tabset').removeClass("navfiex");
   	  	  };

});
});