<table class="table">
	<tbody>
	<?php	
		function my_strcmp($a, $b) {
			if ($a['start'] == $b['start'])
				return (0);
			return ($a['start'] > $b['start']) ? (1) : (-1);
		}

		setlocale(LC_TIME, 'fr_FR.utf8','fra');
		usort($robot_activites_array, "my_strcmp");

		$day = "";

		function display_date($d, $s) {
			if ($d != strftime("%Y-%m-%d", strtotime($s)))
			{
				$d = strftime("%Y-%m-%d", strtotime($s));
				if (date_parse($d)['day'] == date('d'))
					$date = "aujourd'hui";
				else
					$date = strftime("%A %e %B", strtotime($d));
				echo "<tr class='info'><td colspan='6'><strong>" . ucwords($date) . "</strong></td></tr>";
			}
			return ($d);
		}
		
		if (count($robot_activites_array) == 0)
			echo "<tr><td colspan='6'>Pas d'activités pour la semaine.</td></tr>";

		foreach ($robot_activites_array as $node)
		{
			if (end(explode('/', $node["room_name"])) == "tout-le-batiment")
			{
				$room = "Tout le batiment";
			}
			else
			{
				$room = str_replace('-', ' et ', end(explode('/', $node["room_name"])));
			}

			if ($node['titlemodule'] == "Association" || $node['type_title'] == 'Soutenance')
			{
				if (date('Y-m-d H:i:s') >= $node['start'] && date('Y-m-d H:i:s') <= $node['end']) {
					$day = display_date($day, $node['start']);
					echo "<tr class='active'><td>" . strftime("%Hh%M", strtotime($node['start'])) . " - " . strftime("%Hh%M", strtotime($node['end'])) . "</td><td>" . $node['acti_title'] . " / <button type='button' class='btn btn-link btn-sm'>" . $node['titlemodule'] . "</button></td><td>" . $room . "</td><td></td></tr>";
				}
				else if (date('Y-m-d H:i:s') < $node['end']) {
					$day = display_date($day, $node['start']);
					echo "<tr><td>" . strftime("%Hh%M", strtotime($node['start'])) . " - " . strftime("%Hh%M", strtotime($node['end'])) . "</td><td>" . $node['acti_title'] . " / <button type='button' class='btn btn-link btn-sm'>" . $node['titlemodule'] . "</button></td><td>" . $room . "</td><td></td></tr>";
				}
			}
			else
			{
				if (date('Y-m-d H:i:s') >= $node['end'] && $node['event_registered'] == "registered")
				{
					$day = display_date($day, $node['start']);
					echo "<tr><td>" . strftime("%Hh%M", strtotime($node['start'])) . " - " . strftime("%Hh%M", strtotime($node['end'])) . "</td><td>" . $node['acti_title'] . " / <button type='button' class='btn btn-link btn-sm'>" . $node['titlemodule'] . "</button></td><td>" . $room . "</td><td><button type='button' class='btn btn-danger btn-xs' data-toggle='modal' data-target='#tokenModal' data-event='" . $node['codeevent'] . "'>Valider sa présence</button></td></tr>";
				}
				else if (date('Y-m-d H:i:s') > $node['start'] && date('Y-m-d H:i:s') < $node['end'])
				{
					$day = display_date($day, $node['start']);
					if ($node['event_registered'] == "present")
						$presence = "<span class='label label-sucess'>Validée</span>";
					else
						$presence = "<button class='btn btn-warning btn-xs'>Valider sa présence</button>";
					echo "<tr><td>" . strftime("%Hh%M", strtotime($node['start'])) . " - " . strftime("%Hh%M", strtotime($node['end'])) . "</td><td>" . $node['acti_title'] . " / <button type='button' class='btn btn-link btn-sm'>" . $node['titlemodule'] . "</button></td><td>" . $room . "</td><td>" . $presence . "</td></tr>";
				}
				else if (date('Y-m-d H:i:s') < $node['start'])
				{
					$day = display_date($day, $node['start']);
					echo "<tr><td>". strftime("%Hh%M", strtotime($node['start'])) ." - " . strftime("%Hh%M", strtotime($node['end'])) . "</td><td>" . $node['acti_title'] . " / <button type='button' class='btn btn-link btn-xs'>" . $node['titlemodule'] . "</button></td><td>" . $room . "</td><td><button class='btn btn-default' data-toggle='tooltip' data-placement='top' title='(" . $node['total_students_registered'] . " / " . $node['room_seats'] . ")'>Inscrit</button></td></tr>";
				}
			}
		}
	?>
	</tbody>
</table>
<script type="text/javascript">
	$(function () {
  		$('[data-toggle="tooltip"]').tooltip()
	})
</script>
<div class="modal fade" id="tokenModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="titleModal">Valider sa présence</h4>
			</div>
			<div class="modal-body">
				<form>
					<input type="hidden" id="event">
					<div class="form-group">
						<label for="token" class="control-label">Token:</label>
						<input type="text" class="form-control" id="token" />
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
				<button type="button" class="btn btn-primary">Valider</button>
			</div>
		</div>
	</div>
</div>