<?php
	header("Content-Type: application/json; charset=UTF-8");

	if (isset($_POST['login']) && isset($_POST['token'])) {
		include('../../views/global/identifiants.php');
		$req = $bdd->prepare('SELECT * FROM api WHERE login = :login AND token = :token');
		$req->execute(array(
			'login' => htmlspecialchars($_POST['login']),
			'token' => intval($_POST['token'])));
		$count = $req->rowCount();
		$req->closeCursor();
		if ($count == 1) {
			$fetch = $req->fetch();
			if (unserialize($fetch['droits'])['get'] == false) {
				echo '{"Error" : "Vous n\'avez pas les droits nécessaires."}';
				exit();
			}
			if (isset($_POST['v']))
				$v = htmlspecialchars($_POST['v']);
			else
				$v = "";
			if (isset($_POST['p']))
				$p = intval($_POST['p']);
			else
				$p = "";
			$response = array();
			if ($v != "" && $p != "") {
				$req = $bdd->prepare('SELECT login, netsoul FROM membres WHERE netsoul > 0 AND annee_promo = :promo AND ville = :ville');
				$req->execute(array('promo' => $p, 'ville' => $v));
			}
			else if ($v != "") {
				$req = $bdd->prepare('SELECT login, netsoul FROM membres WHERE netsoul > 0 AND ville = :ville');
				$req->execute(array('promo' => $p, 'ville' => $v));
			}
			else if ($p != "") {
				$req = $bdd->prepare('SELECT login, netsoul FROM membres WHERE netsoul > 0 AND annee_promo = :promo');
				$req->execute(array('promo' => $p, 'ville' => $v));
			}
			else {
				$req = $bdd->execute('SELECT login, netsoul FROM membres WHERE netsoul > 0');
			}
			$count = $req->rowCount();
			if ($count > 0)
			{
				while (($fetch = $req->fetch()))
					array_push($response, array($fetch['login'], $fetch['netsoul']));
				echo  json_encode($response);
			}
			else
			{
				echo '{"Error" : "Personne dans cette ville."}';					
			}
		} else {
			echo '{"Error" : "Mauvais token !"}';
		}
	}
	else {
		echo '{"Error" : "Vous devez entrer un token !"}';
	}
?>