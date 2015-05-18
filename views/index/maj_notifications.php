<?php
session_start();
// Parseur de notifications via l'intranet
include('../connexion/cookie.php');

if (!isset($_SESSION['login']) || !isset($_SESSION['password'])) {
	echo json_encode(array('Erreur' => 'Vous devez vous reconnecter'));
} else {
	$login = $_SESSION['login'];
	$password = my_decrypt($_SESSION['password']);
	$form = "login=" . urlencode($login) . "&password=" . urlencode($password) . "&remind=true";

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, "https://intra.epitech.eu/?format=json");
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $form);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_COOKIE, 'language=fr;');
	curl_setopt($ch, CURLOPT_COOKIESESSION, true);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	if (($content = curl_exec($ch)) == false) {
		echo "Curl Error: ".curl_error($ch);
	}
	curl_close($ch);

	$content = str_replace("// Epitech JSON webservice ...\n", "", $content);
	$json = json_decode($content);

	$last_view = json_decode($json->{"infos"}->{"userinfo"})->{"_private"}->{"LastNoticeView"};
	$history = $json->{"history"};
	$notif_importantes = 0;
	$notif_others = 0;
	$array_notifications_importantes = array();
	$array_notifications_others = array();

	foreach ($history as $key => $value) {
		if ($history[$key]->{"date"} > $last_view) {
			if ($history[$key]->{"class"} != "register")
				$notif_importantes++;
			else
				$notif_others++;
			$lu = 0;
		}
		else
			$lu = 1;
		if ($history[$key]->{"class"} != "register") {
			array_push($array_notifications_importantes, array(
				'id' => $history[$key]->{"id"},
				'title' => htmlentities($history[$key]->{"title"}),
				'content' => htmlentities($history[$key]->{"content"}),
				'date' => $history[$key]->{"date"},
				'lu' => $lu));
		} else {
			array_push($array_notifications_others, array(
				'id' => $history[$key]->{"id"},
				'title' => htmlentities($history[$key]->{"title"}),
				'date' => $history[$key]->{"date"},
				'lu' => $lu));
		}
	}

	$array_final_notifications = array("nb_notif_others" => $notif_others, "nb_notif_importantes" => $notif_importantes, $array_notifications_others, $array_notifications_importantes);
	include('../global/identifiants.php');
	$req = $bdd->prepare('UPDATE membres SET robot_notifications = :notif WHERE login = :login');
	$req->execute(array(
				'notif' => serialize($array_final_notifications),
				'login' => $_SESSION['login']
		));
	$req->closeCursor();

	$get = $bdd->prepare('SELECT robot_projets FROM  membres WHERE login = :login');
	$get->execute(array(
		'login' => $_SESSION['login']));
	$fetch = $get->fetch();
	$array_projets = unserialize($fetch['robot_projets']);
	$projects = $json->{"board"}->{"projets"};

	foreach ($projects as $k => $v) {
		foreach ($array_projets[0] as $key => $value) {
			if (utf8_decode($projects[$k]->{"title"}) == utf8_decode(html_entity_decode($array_projets[0][$key]["acti_title"]))) {
				if ($projects[$k]->{"soutenance_name"} != false) {
					$array_projets[0][$key]['soutenance'] = array("soutenance_name" => $projects[$k]->{"soutenance_name"}, "soutenance_link" => $projects[$k]->{"soutenance_link"}, "soutenance_date" => $projects[$k]->{"soutenance_date"}, "soutenance_salle" => $projects[$k]->{"soutenance_salle"});
				}
			}
		}
	}

	$update = $bdd->prepare('UPDATE membres SET robot_projets = :robot, date_last_robot = NOW()  WHERE login = :login');
	$update->execute(array(
		'robot' => serialize($array_projets),
		'login' => $_SESSION['login']));
	echo json_encode(array("Message" => "Notifications à jour"));
}
?>