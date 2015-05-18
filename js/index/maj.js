function maj() {
	$('.nav-tabs a:last').tab('show');
	$("#button_maj").prop("disabled", true);
	$("#button_maj").css('background-color', '#f0ad4e');
	$("#button_maj").css('border-color', '#eea236');
	$("#button_maj").text('Mise à jour en cours (1/3)');
	$.ajax({
		dataType: "json",
		url: "views/index/maj_informations.php"
	}).done(function ( data ) {
		if (typeof data['Erreur'] != 'undefined') {
			$("#button_maj").css('background-color', '#C9302C');
			$("#button_maj").css('border-color', '#AC2925');
			$("#button_maj").text('Erreur : ' + data['Erreur']);
		} else {
			$("#button_maj").text('Mise à jour en cours (2/3)');
			$.ajax({
				dataType: "json",
				url: "views/index/maj_projets.php"
			}).done(function ( data ) {
				if (typeof data['Erreur'] != 'undefined') {
					$("#button_maj").css('background-color', '#C9302C');
					$("#button_maj").css('border-color', '#AC2925');
					$("#button_maj").text('Erreur : ' + data['Erreur']);
				} else {
					$("#button_maj").text('Mise à jour en cours (3/3)');
					$.ajax({
						dataType: "json",
						url: "views/index/maj_notifications.php"
					}).done(function ( data ) {
						if (typeof data['Erreur'] != 'undefined') {
							$("#button_maj").css('background-color', '#C9302C');
							$("#button_maj").css('border-color', '#AC2925');
							$("#button_maj").text('Erreur : ' + data['Erreur']);
						} else {
							$("#button_maj").css('background-color', '#5CB85C');
							$("#button_maj").css('border-color', '#4CAE4C');
							$("#button_maj").text('Mise à jour terminée.');
							setTimeout(function() {
								location.reload();
							}, 1000);
						}
					});
				}
			});
		}
	});
}