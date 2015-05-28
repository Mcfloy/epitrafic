<?php
	ini_set('display_errors', 1); 
	error_reporting(E_ALL); 
	session_start();
	include('../global/identifiants.php');
	if (isset($_SESSION['login']) && $_SESSION['grade'] == 5)
	{
		if (isset($_POST['action']))
		{
			$action = htmlspecialchars($_POST['action']);
			switch ($action) {
				case "add":
					if (isset($_POST['message'])) {
						$message = htmlspecialchars(urldecode($_POST['message']));
						$req = $bdd->prepare('INSERT INTO panel (message, date_creation) VALUES (:message, NOW())');
						$req->execute(array(
							'message' => htmlentities($message)
							));
						echo "<div class='alert alert-success' role='alert'><span class='glyphicon glyphicon-ok-sign'></span> Message ajouté.</div>";
					} else {
						echo "<div class='alert alert-danger' role='alert'><span class='glyphicon glyphicon-exclamation-sign'></span> Il manque le message à ajouter.</div>";
					}
					break;

				case "edit":
					if (isset($_POST['message']) && isset($_POST['id'])) {
						$message = htmlspecialchars(urldecode($_POST['message']));
						$id = intval($_POST['id']);
						$req = $bdd->prepare('UPDATE panel SET message = :message WHERE id = :id');
						$req->execute(array(
							'message' => htmlentities($message),
							'id' => $id
							));
						echo "<div class='alert alert-success' role='alert'><span class='glyphicon glyphicon-ok-sign'></span> Message modifié.</div>";
					} else {
						echo "<div class='alert alert-danger' role='alert'><span class='glyphicon glyphicon-exclamation-sign'></span> Il manque soit le message à modifier, soit l'id du message à modifier (ou bien les deux).</div>";
					}
					break;

				case "delete":
					if (isset($_POST['id'])) {
						$id = intval($_POST['id']);
						$req = $bdd->prepare('DELETE FROM panel WHERE id = :id');
						$req->execute(array(
							'id' => $id
							));
						echo "<div class='alert alert-success' role='alert'><span class='glyphicon glyphicon-ok-sign'></span> Message <s>censuré</s> supprimé.</div>";
					} else {
						echo "<div class='alert alert-danger' role='alert'><span class='glyphicon glyphicon-exclamation-sign'></span> Vous devez préciser l'id du message à supprimer.</div>";
					}
					break;
				
				default:
					echo "<div class='alert alert-success' role='alert'><span class='glyphicon glyphicon-ok-sign'></span> L'action n'existe pas.</div>";
					break;
			}
		} else {
			echo "<div class='alert alert-danger' role='alert'><span class='glyphicon glyphicon-exclamation-sign'></span> Pas d'action définie.</div>";
		}
	}
?>