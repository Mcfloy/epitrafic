<?php
	session_start();
	// On check si on a le login et mot de passe
	if (isset($_POST['login']) && isset($_POST['password'])) {
		// On sécurise le tout
		$login = htmlspecialchars($_POST['login']);
		$password = htmlspecialchars($_POST['password']);

		// On va créer les paramètres en prenant en compte les caractères pénibles
		$form = "login=" . urlencode($login) . "&password=" . urlencode($password);

		// Curl va faire ce qu'un utilisateur peut faire avec son navigateur : envoyer des requêtes
		$ch = curl_init();

		/*
		** Pour en savoir plus sur les paramètres que j'initialise :
		** http://php.net/manual/fr/function.curl-setopt.php
		*/

		curl_setopt($ch, CURLOPT_URL, "https://intra.epitech.eu?format=json");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $form);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 1);

		// On vérifie la valeur de retour en cas d'erreur
		if (($code = curl_exec($ch)) == false) {
			echo "Curl Error: ".curl_error($ch);
		}

		curl_close($ch);

		/*
		** J'utilise la même méthode que celle de l'API Epitech : https://github.com/Raphy/epitech-api
		** Je vous encourage si possible à utiliser cette dernière (dans ce cas-ci je n'utilise pas de framework PHP)
		*/

		list($header, $content) = explode("\r\n\r\n", $code, 2);

		// On enlève le commentaire empêchant le décodage du JSON (*sigh* Why a comment ? WutFace)
		$content = str_replace("// Epitech JSON webservice ...\n", "", $content);

		// Cette ligne permet de décoder ce que l'intra nous a renvoyé
		$json = json_decode($content);

		if (isset($json->{"message"})) {
			echo "<div class='alert alert-danger' role='alert'><span class='glyphicon glyphicon-exclamation-sign'></span> Identifiants incorrects.</div>";
		} else {
			/*
			** On initialise les variables de session, vous trouverez une documentation de l'intra ici :
			** http://www.epitrafic.com/documentation-epitech
			*/

			$_SESSION['login'] = urlencode($_POST['login']);
			$_SESSION['password'] = urlencode($_POST['password']);
			$_SESSION['title'] = $json->{"infos"}->{"title"};
			$_SESSION['ville'] = $json->{"infos"}->{"location"};
			$_SESSION['promo'] = $json->{"infos"}->{"promo"};

			/*
			** On signale à la BDD qu'on vient de se connecter
			*/

			// Des cookies, parce qu'on a faim. (Ils permettent en réalité de tenir plus longtemps sur une page)
			setcookie('connexionAuto', $_SESSION['login'] . '-' . $_SESSION['password'], (time() + 365*24*3600));

			echo "<div class='alert alert-success' role='alert'><span class='glyphicon glyphicon-ok-sign'></span> Bonjour ". $_SESSION['login'] .", heureux de vous voir sur EpiTrafic ! Nous allons vous rediriger automatiquement dans 3 secondes.</div>";
		}
	} else {
		echo "Erreur";
	}
?>