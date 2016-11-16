window.onload = function() {
	$("#js").remove();
}

window.onscroll = function() {
	 var scrolled = window.pageYOffset || document.documentElement.scrollTop;
	 var scrol_check = $(document).height() / 6 * 4;
	 if (scrolled > scrol_check && scroll_id != 1) {
	 	scroll_id = 1;
	 	$.ajax({
	        type: "POST",
	        url:'../modules/feeds/new_feed.php',
	        data:{},
	        success:function(data){
	        	$(".wall").append(data);
	        	scroll_id = 0;
	        }
	    });
	 }
}

$(function() {
    $('#user_avatar').click(function(){
       $('html, body').animate({scrollTop:0}, 'slow');
   });
});