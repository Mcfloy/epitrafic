<?php
	header("Content-Type: application/json; charset=UTF-8");

	if (isset($_POST['login']) && isset($_POST['token'])) {
		include('../../views/global/identifiants.php');
		$req = $bdd->prepare('SELECT * FROM api WHERE login = :login AND token = :token');
		$req->execute(array(
			'login' => htmlspecialchars($_PSOT['login']),
			'token' => intval($_POST['token'])));
		$count = $req->rowCount();
		$req->closeCursor();
		if ($count == 1) {
			if ($_POST['data']) {
				$liste = json_decode($_POST['data']);
				foreach ($liste as $node)
				{
					$array = (array)$node->{"informations_epitech"}[0];
					$array["log"] = round($array["log"], 1);
					$array["log_old"] = round($array["log_old"], 1);
					$array["gpa"] = round($array["gpa"], 2);
					$array["gpa_old"] = round($array["gpa_old"], 2);
					$array["credits"] = round($array["credits"], 1);
					$array["credits_old"] = round($array["credits_old"], 1);
					$req = $bdd->prepare('UPDATE membres SET informations_epitech = :informations WHERE login = :login');
					$req->execute(array('login' => htmlspecialchars($node->{"login"}), 'informations' => serialize($array)));
					$req->closeCursor();
				}
				echo '{"Success" : "Classement mis à jour !"}';
			}
			else {
				echo '{"Error" : "Tableau du classement requis !"}';
			}
		} else {
			echo '{"Error" : "Mauvais token !"}';
		}
	}
	else {
		echo '{"Error" : "Vous devez entrer un token !"}';
	}
?>