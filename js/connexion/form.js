function send_form() {
	var login = $("#login").val(),
		password = $("#password").val();

	clear_alert();
	if (login != "" && password != "") {
		if (password.length == 4) {
			$(".col-centered").prepend("<div class='alert alert-danger' role='alert'><span class='glyphicon glyphicon-exclamation-sign'></span> Nous vous demandons le mot de passe UNIX.</div>");
		} else {
			$("#loader-wrapper").fadeIn("slow");
			$("form button").attr("value", "Envoi en cours..");
			$("form button").attr("disabled", true);
			$.ajax({
				url: "/views/connexion/ajax.php",
				method: "POST",
				dataType: 'html',
				data: "login=" + login + "&password=" + encodeURIComponent(password)
				}).done(function (data) {
					$(".col-centered").prepend(data);
					if ($(".alert-success").length > 0) {
						$("form").remove();
						window.location.replace("/");
					} else {
						$("form button").attr("attr", "Envoi");
						$("form button").attr("disabled", false);
						$("#loader-wrapper").fadeOut("slow");
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