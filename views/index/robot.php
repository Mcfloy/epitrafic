<?php

	$req = $bdd->prepare('SELECT date_last_robot, robot_notifications, robot_activite, robot_projets FROM membres WHERE login = :login');
	$req->execute(array('login' => $_SESSION['login']));

	$fetch = $req->fetch();
	$req->closeCursor();

	$robot_notifications_array = unserialize($fetch['robot_notifications']);
	if ((intval($robot_notifications_array['nb_notif_importantes']) + intval($robot_notifications_array['nb_notif_others'])) <= 1)
		$notifications = (intval($robot_notifications_array['nb_notif_importantes']) + intval($robot_notifications_array['nb_notif_others'])) . " nouvelle notification"; 
	else
		$notifications = (intval($robot_notifications_array['nb_notif_importantes']) + intval($robot_notifications_array['nb_notif_others'])) . " nouvelles notifications";

	$robot_projets_array = unserialize($fetch['robot_projets']);
	if ($robot_projets_array['nb_projets'] <= 1)
		$projets = $robot_projets_array['nb_projets'] . " projet en cours"; 
	else
		$projets = $robot_projets_array['nb_projets'] . " projets en cours";

	$robot_activites_array = unserialize($fetch['robot_activite']);

	date_default_timezone_get("Europe/Paris");
	$date_now = new DateTime();
	$date_maj = date_create($fetch['date_last_robot']);
	date_add($date_maj, date_interval_create_from_date_string('15 minutes'));
	$intervalle = $date_maj->diff($date_now);
?>
<div class="hidden-xs">
	<div role="tabpanel">
		<ul class="nav nav-tabs">
			<li role="presentation"><a href="#information" aria-controls="information" role="tab" data-toggle="tab">Informations</a></li>
			<li role="presentation"><a href="#planning" aria-controls="planning" role="tab" data-toggle="tab">Planning</a></li>
			<li role="presentation"><a href="#projet" aria-controls="projet" role="tab" data-toggle="tab"><?php echo $projets; ?></a></li>
			<li role="presentation"><a href="#notification" aria-controls="notification" role="tab" data-toggle="tab"><?php echo $notifications; ?></a></li>
			<li role="presentation"><a href="#maj" aria-controls="maj" role="tab" data-toggle="tab" id="onglet_maj">Mettre à jour (Mis à jour automatique dans <?php echo $intervalle->format('%i minutes %s secondes'); ?>)</a></li>
		</ul>

		<div class="tab-content">
			<div role="tabpanel" class="tab-pane fade in active" id="information">
				<?php
					include('robot_information.php');
				?>
			</div>
			<div role="tabpanel" class="tab-pane fade" id="planning">
				<?php
					include('robot_planning.php');
				?>
			</div>
			<div role="tabpanel" class="tab-pane fade" id="projet">
				<?php
					include('robot_projet.php');
				?>
			</div>
			<div role="tabpanel" class="tab-pane fade" id="notification">
				<?php
					include('robot_notification.php');
				?>
			</div>
			<div role="tabpanel" class="tab-pane fade" id="maj">
				<?php
					include ('robot_maj.php');
				?>
				<script>
				var maj_en_cours = false;
				window.setInterval(function() {
					var date = new Date(),
						date_maj = new Date("<?php echo date_format($date_maj, 'M d H:i:s Y'); ?>"),
						total_secondes = Math.floor((date_maj - date) / 1000);
						minutes = Math.floor(total_secondes / 60);
						secondes = Math.floor(total_secondes - (minutes * 60));

					if (total_secondes < 0) {
						$("#onglet_maj").text("Mis à jour en cours");
						if (maj_en_cours == false) {
							maj_en_cours = true;
							maj();
						}
					} else {
						$("#onglet_maj").text("Mettre à jour (Mis à jour automatique dans " + minutes + " minutes " + secondes + " secondes)");
					}
				}, 1000);
				</script>
			</div>
		</div>
	</div>
</div>