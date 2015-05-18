<?php
	$req = $bdd->query('SELECT * FROM forums');
?>

<div class="panel panel-default">
	<div class="panel-heading">Gestion du forum</div>
	<div class="panel-body">
		<ul>
			<?php
				while ($fetch = $req->fetch())
				{
					echo "<li id='panel_node_" . $fetch['id'] . "'><h4>" . utf8_encode($fetch['nom']) . "</h4><br><p>" . utf8_encode($fetch['description']) . "</p><a href='#'><span class='glyphicon glyphicon-pencil' aria-hidden='true'></span></a> <a href='#'><span class='glyphicon glyphicon-minus' aria-hidden='true'></span></a></li>";
				}
			?>
		</ul>
	</div>
</div>