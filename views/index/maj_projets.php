<?php
session_start();
// Parseur de projets via l'intranet
include('../connexion/cookie.php');

if (!isset($_SESSION['login']) || !isset($_SESSION['password'])) {
	echo json_encode(array('Erreur' => 'Vous devez vous reconnecter'));
} else {
	$login = $_SESSION['login'];
	$password = my_decrypt($_SESSION['password']);
	$form = "login=" . urlencode($login) . "&password=" . urlencode($password) . "&remind=true";

	$date_today = date("Y-m-d");
	$date_next_week = date("Y-m-d", strtotime("+8 days"));

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, "https://intra.epitech.eu/module/board/?format=json&start=".$date_today."&end=".$date_next_week);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $form);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_COOKIE, 'language=fr');
	curl_setopt($ch, CURLOPT_HEADER, 1);
	if (($code = curl_exec($ch)) == false) {
		echo "Curl Error: ".curl_error($ch);
	}
	curl_close($ch);

	list($header, $content) = explode("\r\n\r\n", $code, 2);
	$session = str_replace("\r\n", "", $header);
	$session = preg_replace("/.*PHPSESSID=([^;]*);.*/","\\1", $session);

	$content = str_replace("// Epitech JSON webservice ...\n", "", $content);
	$json = json_decode($content);
	$array_projet_liste = array();
	$nb_projets = 0;

	foreach ($json as $key => $value) {
		if ($value->{"registered"} == 1 && $value->{"type_acti_code"} == "proj") {
			$nb_projets++;

			$ch2 = curl_init();
			curl_setopt($ch2, CURLOPT_URL, "https://intra.epitech.eu/module/".$value->{"scolaryear"}."/".$value->{"codemodule"}."/".$value->{"codeinstance"}."/".$value->{"codeacti"}."/project/?format=json");
			curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, 0); 
			curl_setopt($ch2, CURLOPT_COOKIE, 'language=fr;PHPSESSID=' . $session);
			curl_setopt($ch2, CURLOPT_COOKIESESSION, true);
			curl_setopt($ch2, CURLOPT_HEADER, 1);
			if (($code2 = curl_exec($ch2)) == false) {
				echo "Curl Error: ".curl_error($ch2);
			}

			curl_close($ch2);

			list($header2, $content2) = explode("\r\n\r\n", $code2, 2);
			$content2 = str_replace("// Epitech JSON webservice ...\n", "", $content2);
			$json2 = json_decode($content2);
			$array_members = array();
			foreach ($json2->{"registered"} as $group) {
				if ($group->{"code"} == $json2->{"user_project_code"}) {
					array_push($array_members, $group->{"master"}->{"login"});
					foreach ($group->{"members"} as $members) {
						array_push($array_members, $members->{"login"});
					}
				}
			}

			$ch3 = curl_init();
			curl_setopt($ch3, CURLOPT_URL, "https://intra.epitech.eu/module/".$value->{"scolaryear"}."/".$value->{"codemodule"}."/".$value->{"codeinstance"}."/".$value->{"codeacti"}."/project/file/?format=json");
			curl_setopt($ch3, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch3, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch3, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch3, CURLOPT_COOKIE, 'language=fr;PHPSESSID=' . $session);
			curl_setopt($ch3, CURLOPT_COOKIESESSION, true);
			curl_setopt($ch3, CURLOPT_HEADER, 1);
			if (($code3 = curl_exec($ch3)) == false) {
			    echo "Curl Error: ".curl_error($ch3);
			}
			$code = curl_getinfo($ch3, CURLINFO_HTTP_CODE);
			curl_close($ch3);
        
			list($header3, $content3) = explode("\r\n\r\n", $code3, 2);
			$content3 = str_replace("// Epitech JSON webservice ...\n", "", $content3);
			$json3 = json_decode($content3);
			$array_resources = array();

			if (!isset($json3->{"error"}) && $code == 200) {
				foreach($json3 as $resource) {
					array_push($array_resources, array("title" => $resource->{"title"}, "fullpath" => $resource->{"fullpath"}));
				}
			}

	        array_push($array_projet_liste, array(
				'scolaryear' => $json2->{"scolaryear"},
				'codemodule' => $json2->{"codemodule"},
				'codeinstance' => $json2->{"codeinstance"},
				'codeacti' => $json2->{"codeacti"},
				'end' => $json2->{"end"},
				'end_register' => $json2->{"end_register"},
				'deadline' => $json2->{"deadline"},
				'nb_min' => $json2->{"nb_min"},
				'nb_max' => $json2->{"nb_max"},
				'closed' => $json2->{"closed"},
				'acti_title' => $json2->{"project_title"},
				'title' => $json2->{"title"},
				'module_title' => $json2->{"module_title"},
				'members' => $array_members,
				'resources' => $array_resources
			));
	    }
	}

	$array_projet = array('nb_projets' => $nb_projets, $array_projet_liste);

	include('../global/identifiants.php');
	$req = $bdd->prepare('UPDATE membres SET robot_projets = :projets WHERE login = :login');
	$req->execute(array(
		'projets' => serialize($array_projet),
		'login' => $_SESSION['login']));
	echo json_encode(array("Message" => "Projets à jour"));
}
?>