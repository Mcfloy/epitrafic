<?php
	$req = $bdd->query('SELECT * FROM api');
?>

<div class="panel panel-default">
	<div class="panel-heading">Gestion de l'API</div>
	<div class="panel-body">
		<ul>
			<?php
				while ($fetch = $req->fetch())
				{
					echo "<li id='panel_node_" . $fetch['id'] . "'><p>" . $fetch['login'] . "</p> <a href='#'><span class='glyphicon glyphicon-pencil' aria-hidden='true'></span></a> <a href='#'><span class='glyphicon glyphicon-minus' aria-hidden='true'></span></a></li>";
				}
			?>
		</ul>
	</div>
</div>