$(document).ready(function() {
	$(".message").children(".message_content").hide();
	$(".message").children(".message_reply").hide();
	$(".message").children(".message_reply_container").hide();
	
	$(".message_title").click(function() {
		$(this).closest(".message").children(".message_content").toggle('fast');
		$(this).closest(".message").children(".message_reply").toggle('fast');
		if ($(this).closest(".message").children(".message_reply_container").is(":hidden")) {
			// Do nothing
			$(this).closest(".message").children(".message_reply_container").toggle('fast');
		} else {
			$(this).closest(".message").children(".message_reply_container").toggle('fast');
		}
	}); 
	
	$(".message_reply").click(function() {
		if ($(this).closest(".message").children(".message_reply_container").is(":hidden")) {
			$(this).closest(".message").children(".message_reply_container").toggle('fast');
		} else {
			// $(this).closest(".message").children(".message_reply_container").toggle('fast');
		}
	
		
	});
});
