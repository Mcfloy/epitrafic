<?php
	ini_set('display_errors', 1); 
	error_reporting(E_ALL);
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
			if ($_POST['data']) {
				$liste = json_decode($_POST['data']);
				foreach ($liste as $node)
				{
					$req = $bdd->prepare('UPDATE membres SET netsoul = :netsoul WHERE login = :login');
					$req->execute(array('login' => htmlspecialchars($node->{"login"}), 'netsoul' => $node->{"netsoul"}));
					$req->closeCursor();
				}
				echo '{"Success" : "Liste netsoul mise à jour !"}';
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
