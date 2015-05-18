<?php
session_start();

if (isset($_SESSION['login']))
{
	include('../connexion/cookie.php');
	$login = $_SESSION['login'];
	$password = my_decrypt($_SESSION['password']);
	$form = "login=" . urlencode($login) . "&password=" . urlencode($password);

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, "https://intra.epitech.eu/user/notification/message?format=json");
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $form);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_COOKIE, "language=fr");
	curl_setopt($ch, CURLOPT_HEADER, 1);

	if (($code = curl_exec($ch)) == false) {
		echo "Curl Error: ".curl_error($ch);
	}

	curl_close($ch);

	list($header, $content) = explode("\r\n\r\n", $code, 2);
	$content = str_replace("// Epitech JSON webservice ...\n", "", $content);
	$json = json_decode($content);

	if (isset($json->{"message"})) {
		echo "<div class='alert alert-danger' role='alert'><span class='glyphicon glyphicon-exclamation-sign'></span> Erreur : " . $json->{"message"} . "</div>";
	}
	else {
		include('../global/identifiants.php');
		$req = $bdd->prepare('SELECT robot_notifications FROM membres WHERE login = :login');
		$req->execute(array('login' => $_SESSION['login']));
		$fetch = $req->fetch();
		$req->closeCursor();
		$array_notifications = unserialize($fetch['robot_notifications']);
		$new_msg = 0;

		foreach ($json as $k => $value) {
			if ($json[$k]['id'] != $array_notifications[0][$k]['id'] && $array_notifications[1][$k]['id']) {
				$new_msg++;
			} else {
				$array_notifications[0][$k]['lu'] = 1;
			}
		}

		foreach ($array_notifications[0] as $k => $v) {
			$array_notifications[0][$k]['lu'] = 1; 
		}
		foreach ($array_notifications[1] as $k => $v) {
			$array_notifications[1][$k]['lu'] = 1;
		}
		$array_notifications['nb_notif_others'] = 0;
		$array_notifications['nb_notif_importantes'] = 0;
		
		$req = $bdd->prepare('UPDATE membres SET robot_notifications = :robot_notifications WHERE login = :login');
		$req->execute(array(
						'robot_notifications' => serialize($array_notifications),
						'login' => $_SESSION['login']));
		echo "<div class='alert alert-success' role='alert'><span class='glyphicon glyphicon-exclamation-ok'></span> Les messages ont étés marqués comme lus.</div>";
	}
}
else
{
	echo "<div class='alert alert-danger' role='alert'><span class='glyphicon glyphicon-exclamation-sign'></span> Vous devez être connecté !</div>";
}?>