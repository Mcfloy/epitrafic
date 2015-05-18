<?php
session_start();
// Parseur d'informations via l'intranet
include('../connexion/cookie.php');

if (!isset($_SESSION['login']) || !isset($_SESSION['password'])) {
	echo json_encode(array('Erreur' => 'Vous devez vous reconnecter'));
} else {
	$login = $_SESSION['login'];
	$password = my_decrypt($_SESSION['password']);
	$form = "login=" . urlencode($login) . "&password=" . urlencode($password) . "&remind=true";

	$date_today = date("Y-m-d", strtotime("-3 days"));
	$date_next_week = date("Y-m-d", strtotime("+8 days"));

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://intra.epitech.eu/planning/load?format=json&start=".$date_today."&end=".$date_next_week);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $form);
	curl_setopt($ch, CURLOPT_COOKIE, "language=fr");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	if (($content = curl_exec($ch)) == false) {
	    echo "Curl Error: ".curl_error($ch);
	}
	curl_close($ch);
	
	$content = str_replace("// Epitech JSON webservice ...\n", "", $content);
	$activites = json_decode($content);
	$activites_array = array();

	foreach ($activites as $node) {
		if (isset($node->{"id_calendar"}) && $node->{"event_registered"} == "registered") {
			if (date("Y-m-d H:i:s") < $node->{"end"})
				$past = 1;
			else
				$past = 0;
			array_push($activites_array, array(
				"acti_title" => $node->{"title"},
				"titlemodule" => "Association",
				"type_title" => $node->{"calendar_type"},
				"room_name" => $node->{"location"},
				"event_registered" => $node->{"event_registered"},
				"past" => $past,
				"start" => $node->{"start"},
				"end" => $node->{"end"}
				));
		}
		else if ((isset($node->{"past"}) && $node->{"past"} == true && $node->{"event_registered"} == "registered") || $node->{"event_registered"} == "registered") {
			if ($node->{"type_code"} == "rdv") {
				if (isset($node->{"rdv_group_registered"}) || isset($node->{"rdv_indiv_registered"})) {
					if ($node->{"rdv_group_registered"} != NULL) {
						list($start, $end) = explode('|', $node->{"rdv_group_registered"});
					}
					else if ($node->{"rdv_indiv_registered"} != NULL) {
						list($start, $end) = explode('|', $node->{"rdv_indiv_registered"});
						
					}
				}
			}
			else {
				$start = $node->{"start"};
				$end = $node->{"end"};
			}
			if ($node->{"type_title"} == "Soutenance" && date('Y-m-d H:i:s') > $end);
			else {
				array_push($activites_array, array(
					"scolaryear" => $node->{"scolaryear"},
					"codemodule" => $node->{"codemodule"},
					"codeinstance" => $node->{"codeinstance"},
					"codeacti" => $node->{"codeacti"},
					"codeevent" => $node->{"codeevent"},
					"acti_title" => $node->{"acti_title"},
					"titlemodule" => $node->{"titlemodule"},
					"type_title" => $node->{"type_title"},
					"total_students_registered" => $node->{"total_students_registered"},
					"room_name" => $node->{"room"}->{"code"},
					"room_seats" => $node->{"room"}->{"seats"},
					"event_registered" => $node->{"event_registered"},
					"start" => $start,
					"end" => $end
					));
			}
		}
	}

	include('../global/identifiants.php');
	$req = $bdd->prepare('UPDATE membres SET robot_activite = :activite WHERE login = :login');
	$req->execute(array(
			'activite' => serialize($activites_array),
			'login' => $_SESSION['login']));
	$req->closeCursor();

	echo json_encode(array("Message" => "Informations à jour"));
}
?>