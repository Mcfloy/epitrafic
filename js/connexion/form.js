function send_form() {
	var login = $("#login").val(),
		password = $("#password").val();

	clear_alert();
	if (login != "" && password != "") {
		if (password.length == 4) {
			$(".col-centered").prepend("<div class='alert alert-danger' role='alert'><span class='glyphicon glyphicon-exclamation-sign'></span> Nous vous demandons le mot de passe UNIX.</div>");
		} else {
			$.ajax({
				url: "/v3/views/connexion/ajax.php",
				method: "POST",
				dataType: 'html',
				data: "login=" + login + "&password=" + password
				}).done(function (data) {
					$(".col-centered").prepend(data);
					if ($(".alert-success").length > 0) {
						$("form").remove();
						setTimeout(function () {
							window.location.replace("/v3/");
						}, 3000);
					}
				});
		}
	} else {
		$(".col-centered").prepend("<div class='alert alert-danger' role='alert'><span class='glyphicon glyphicon-exclamation-sign'></span> Vous devez entrer votre login ET votre mot de passe UNIX.</div>");
	}
}

function clear_alert() {
	if ($(".alert-danger").length > 0) {
		$(".alert-danger").remove();
	}
}