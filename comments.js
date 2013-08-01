$(document).ready(function() {
	$(".dg_comment_container").children("div").hide();
	$(".post_comment_container").children("div").hide();
	
	$(".comments_header").click(function() {
		$(this).closest(".dg_comment_container").children("div").toggle('fast');
		$(this).css("color", "white");
	}); 
	
	$(".commentbox").click(function() {
		$(this).closest(".post_comment_container").children("div").toggle('fast');
		$(this).css("color", "white");
	});
	
	/* 
		$(".dg_comment_container").click(function() {
		$(this).children("div").toggle('fast');
		});
		
		$(".post_comment_container").click(function() {
		$(this).children("div").toggle('fast');
		if ($(this).css("height") == "150px") {
			$(this).css("height","");
		} else {
			$(this).css("height","150px");
		}
		
	}); */
});
