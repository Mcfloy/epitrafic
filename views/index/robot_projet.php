<table class="table">
	<thead>
		<tr><th>Nom du projet</th><th>Date de rendu</th><th>Ressources pédagogiques</th><th>Collègues connectés à l'école</th></tr>
	</thead>
	<tbody>
		<?php			
			function sort_by_date($a, $b) {
				if (isset($a['deadline']) && isset($b['deadline'])) {
					if ($a['deadline'] == $b['deadline'])
						return (0);
					else
						return ($a['deadline'] < $b['deadline']) ? (-1) : (1);
				} else if ($a['deadline'] != NULL) {
					if ($a['deadline'] == $b['end'])
						return (0);
					else
						return ($a['deadline'] < $b['end']) ? (-1) : (1);
				} else if ($b['deadline'] != NULL) {
					if ($a['end'] == $b['deadline'])
						return (0);
					else
						return ($a['end'] < $b['deadline']) ? (-1) : (1);
				} else {
					if ($a['end'] == $b['end'])
						return (0);
					else
						return ($a['end'] < $b['end']) ? (-1) : (1);
				}
			}
			setlocale(LC_TIME, 'fr_FR.utf8','fra');
			usort($robot_projets_array[0], "sort_by_date");
			foreach ($robot_projets_array[0] as $key) {
				echo "<tr><td>". $key['title'] ."</td><td>";
					if (isset($key['deadline']))
						echo strftime("%e %B %Y, %Hh%M", strtotime($key['deadline']));
					else
						echo strftime("%e %B %Y, %Hh%M", strtotime($key['end']));
				echo "</td><td>";
				// On liste les ressources du projet
				if (count($key['resources']) > 0) {
					echo "<ul>";
					foreach ($key['resources'] as $resource) {
						echo "<li><a target='_blank' href='https://intra.epitech.eu". $resource['fullpath'] ."'>". $resource['title'] ."</a></li>";
					}
					echo "</ul>";
				}
				echo "</td><td><ul class='list-unstyled'>";
				// Ici la liste de ceux qui font aussi le jeu
				$req_membres = $bdd->query('SELECT login, netsoul FROM membres WHERE login IN ("'. implode('","', $key['members']) .'")');
				while ($fetch_membres = $req_membres->fetch()) {
					if ($fetch_membres['netsoul'] > 1)
						echo "<li><span class='glyphicon glyphicon-ok' aria-hidden='true'></span> ". $fetch_membres['login'] ."</li>";
					else
						echo "<li><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> ". $fetch_membres['login'] ."</li>";
				}
				echo "</ul></td></tr>";
			}
		?>
	</tbody>
</table>