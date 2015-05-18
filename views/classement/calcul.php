<?php
include('views/global/identifiants.php');

if (!isset($_GET['promo'])) {
	$promo = $_SESSION['promo'];
} else {
	if (intval($_GET['promo']) < 2017)
		$promo = 2017;
	else if (intval($_GET['promo']) > 2019)
		$promo = 2019;
	else
		$promo = intval($_GET['promo']);
	if ($_GET['promo'] == 'all')
		$promo = "all";
}

if (!isset($_GET['ville'])) {
	if (isset($_SESSION['ville']))
		$ville = str_replace('FR/', '', $_SESSION['ville']);
	else
		$ville = "all";
} else {
	$liste_villes = array("all", "BDX", "LIL", "LYN", "MAR", "MPL", "NCY", "NAN", "NCE", "PAR", "REN", "STG", "TLS");
	if (!in_array(htmlspecialchars(urldecode($_GET['ville'])), $liste_villes))
		$ville = "all";
	else
		$ville = urldecode(htmlspecialchars($_GET['ville']));
}

if ($promo != "all") {
	if ($ville != "all") {
		$req = $bdd->prepare('SELECT login, ville, informations_epitech FROM membres WHERE annee_promo = :promo AND ville = :ville');
		$req->execute(array('promo' => $promo, 'ville' => $ville));
	} else {
		$req = $bdd->prepare('SELECT login, ville, informations_epitech FROM membres WHERE annee_promo = :promo');
		$req->execute(array('promo' => $promo));
	}
}
else {
	if ($ville != "all") {
		$req = $bdd->prepare('SELECT login, ville, informations_epitech FROM membres WHERE ville = :ville');
		$req->execute(array('ville' => $ville));
	} else {
		$req = $bdd->query('SELECT login, ville, informations_epitech FROM membres');
	}
}

$fetch = $req->fetchAll();
foreach ($fetch as $key => $value) {
	$fetch[$key]["informations_epitech"] = unserialize($fetch[$key]["informations_epitech"]);
}

if (isset($_GET['tri']))
	$tri = htmlspecialchars($_GET['tri']);
else
	$tri = "gpa";

function cmp_gpa($a, $b) {
	if ($a["informations_epitech"]["classement_national_gpa"] == $b["informations_epitech"]["classement_national_gpa"])
		return (0);
	return ($a["informations_epitech"]["classement_national_gpa"] < $b["informations_epitech"]["classement_national_gpa"]) ? -1 : 1;
}

function cmp_log($a, $b) {
	if ($a["informations_epitech"]["classement_national_log"] == $b["informations_epitech"]["classement_national_log"])
		return (0);
	return ($a["informations_epitech"]["classement_national_log"] < $b["informations_epitech"]["classement_national_log"]) ? -1 : 1;
}

function cmp_credits($a, $b) {
	if ($a["informations_epitech"]["classement_national_credits"] == $b["informations_epitech"]["classement_national_credits"])
		return (0);
	return ($a["informations_epitech"]["classement_national_credits"] < $b["informations_epitech"]["classement_national_credits"]) ? -1 : 1;
}

if ($tri == "log")
	uasort($fetch, "cmp_log");
else if ($tri == "credits")
	uasort($fetch, "cmp_credits");
else
	uasort($fetch, "cmp_gpa");
?>