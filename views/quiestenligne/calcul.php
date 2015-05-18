<?php
include('views/global/identifiants.php');

if (!isset($_GET['promo']))
	$promo = $_SESSION['promo'];
else {
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
		$req = $bdd->prepare('SELECT login, netsoul FROM membres WHERE annee_promo = :promo AND ville = :ville ORDER BY login');
		$req->execute(array('promo' => $promo, 'ville' => $ville));
	} else {
		$req = $bdd->prepare('SELECT login, netsoul FROM membres WHERE annee_promo = :promo ORDER BY login');
		$req->execute(array('promo' => $promo));
	}
}
else {
	if ($ville != "all") {
		$req = $bdd->prepare('SELECT login, netsoul FROM membres WHERE ville = :ville ORDER BY login');
		$req->execute(array('ville' => $ville));
	} else {
		$req = $bdd->query('SELECT login, netsoul FROM membres ORDER BY login');
	}
}
?>