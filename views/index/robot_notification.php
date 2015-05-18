<div class="col-lg-12">
	<br>
	<script type="text/javascript" src="js/index/clear_notifications.js"></script>
	<div id="answer"></div>

	<button class="btn btn-primary" onclick="clear_notifications()">Marquer les messages comme lus</button>
	
</div>
<div class="col-lg-6">
	<table class="table">
		<thead>
			<tr><th colspan="2"><?php echo ($robot_notifications_array["nb_notif_importantes"] <= 1) ? ($robot_notifications_array["nb_notif_importantes"] . " nouvelle information importante") : ($robot_notifications_array["nb_notif_importantes"] . " nouvelles informations importantes"); ?></th></tr>
		</thead>
		<tbody>
				<?php
					foreach ($robot_notifications_array[1] as $key) {
						if ($key['lu'] == 0)
							echo "<tr><td><strong>". str_replace('href="', 'target="_blank" href="https://intra.epitech.eu', htmlspecialchars_decode($key['title'])) ."</strong></td></tr>";
						else
							echo "<tr><td>". str_replace('href="', 'target="_blank" href="https://intra.epitech.eu', htmlspecialchars_decode($key['title'])) ."</td></tr>";
					}
				?>
		</tbody>
	</table>
</div>
<div class="col-lg-6">
	<table class="table">
		<thead>
			<tr><th colspan="2"><?php echo ($robot_notifications_array["nb_notif_others"] <= 1) ? ($robot_notifications_array["nb_notif_others"] . " nouvelle information moins importante") : ($robot_notifications_array["nb_notif_others"] . " nouvelles informations moins importantes"); ?></th></tr>
		</thead>
		<tbody>
			<?php
				foreach ($robot_notifications_array[0] as $key) {
					if ($key['lu'] == 0)
						echo "<tr><td><strong>". str_replace('href="', 'target="_blank" href="https://intra.epitech.eu', htmlspecialchars_decode($key['title'])) ."</strong></td></tr>";
					else
						echo "<tr><td>". str_replace('href="', 'target="_blank" href="https://intra.epitech.eu', htmlspecialchars_decode($key['title'])) ."</td></tr>";
				}
			?>
		</tbody>
	</table>
</div>