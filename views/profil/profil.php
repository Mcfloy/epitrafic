<?php
	if (isset($_GET['login'])) {
		$login = htmlspecialchars($_GET['login']);
	}
	else {
		$login = $_SESSION['login'];
	}
	$req = $bdd->prepare('SELECT * FROM membres WHERE login = :login');
	$req->execute(array( 'login' => $login ));
	if ($req->rowCount() == 0) {
		echo "Erreur, pas de profil correspondant.";
	} else {
		$fetch = $req->fetch();
		$robot_activite = unserialize($fetch['robot_activite']);
		$robot_projets = unserialize($fetch['robot_projets']);
		?>
		<div class="hidden-xs">
			<div class="col-lg-2">
				<img src="https://cdn.local.epitech.eu/userprofil/profilview/<?php echo $login; ?>.jpg" alt="Avatar de <?php echo $login; ?>" onerror="if (this.src != 'https://intra.epitech.eu/static7373/img/nopicture-profilview.png') this.src = 'https://intra.epitech.eu/static7373/img/nopicture-profilview.png'">
			</div>
			<div class="col-lg-10">
				<p>Login IRL : <?php echo $fetch['nomprenom']; ?></p>
				<p>Ville : <?php echo $fetch['ville']; ?></p>
				<p>Promotion : <?php echo $fetch['annee_promo']; ?></p>
				<?php
					if ($robot_projets['nb_projets'] == 0) {
						echo "<p>Ne travaille sur aucun projet.</p>";
					} elseif ($robot_projets['nb_projets'] == 1) {
						echo "<p>Travaille sur 1 projet.</p>";
					} else {
						echo "<p>Travaille sur " . $robot_projets['nb_projets'] . " projets</p>";
					}

					if ($fetch['netsoul'] == 2) {
						echo "<p>Connecté à l'école.</p>";
					}
					else if ($fetch['netsoul'] == 1) {
						echo "<p>Connecté à l'extérieur.</p>";
					} else {
						echo "<p>Non connecté.</p>";
					}
				?>
			</div>
		</div>
		<?php
	}
?>