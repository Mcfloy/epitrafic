function clear_notifications() {
	$.ajax({
		url: "views/index/clear_notifications.php"
	}).done(function ( data ) {
		$("#answer").html(data);
		$("#answer").fadeIn("slow", function() {
			setTimeout(function() {
				$("#answer").fadeOut("slow", function() {
					$("#answer").html("");
				});
			}, 3000);
		});
	});
}