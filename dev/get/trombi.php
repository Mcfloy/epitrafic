<?php
	header("Content-Type: application/json; charset=UTF-8");

	if (isset($_POST['token'])) {
		include('../../views/global/identifiants.php');
		$req = $bdd->prepare('SELECT * FROM api WHERE token = :token');
		$req->execute(array('token' => intval($_POST['token'])));
		$count = $req->rowCount();
		$fetch = $req->fetch();
		$req->closeCursor();
		if ($count == 1) {
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
			if (isset($_POST['infos']))
				$infos = $_POST['infos'];
			else
				$infos = false;

			$response = array();
			if ($infos != true) {
				if ($v != "" && $p != "") {
					$req = $bdd->prepare('SELECT login FROM membres WHERE ville = :ville AND annee_promo = :promo');
					$req->execute(array('ville' => $v, 'promo' => $p));
				}
				else if ($v != "") {
					$req = $bdd->prepare('SELECT login FROM membres WHERE ville = :ville');
					$req->execute(array('ville' => $v));
				}
				else if ($p != "") {
					$req = $bdd->prepare('SELECT login FROM membres WHERE annee_promo = :promo');
					$req->execute(array('promo' => $p));
				}
				else {
					$req = $bdd->execute('SELECT login FROM membres');
				}
			}
			else {
				if ($v != "" && $p != "") {
					$req = $bdd->prepare('SELECT login, informations_epitech FROM membres WHERE ville = :ville AND annee_promo = :promo');
					$req->execute(array('ville' => $v, 'promo' => $p));
				}
				else if ($v != "") {
					$req = $bdd->prepare('SELECT login, informations_epitech FROM membres WHERE ville = :ville');
					$req->execute(array('ville' => $v));
				}
				else if ($p != "") {
					$req = $bdd->prepare('SELECT login, informations_epitech FROM membres WHERE annee_promo = :promo');
					$req->execute(array('promo' => $p));
				}
				else {
					$req = $bdd->execute('SELECT login, informations_epitech FROM membres');
				}
			}
			$count = $req->rowCount();
			if ($count > 0)
			{
				while (($fetch = $req->fetch()))
					if ($infos == true)
						array_push($response, array("login" => $fetch['login'], "infos" => $fetch['informations_epitech']));
					else
						array_push($response, array("login" => $fetch['login']));
				echo '{"effectif" : ' . $count . ' , "liste" : ' . json_encode($response) . '}';
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