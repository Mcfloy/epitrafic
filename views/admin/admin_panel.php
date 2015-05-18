<?php
	$req = $bdd->query('SELECT * FROM panel');
?>

<div class="panel panel-default">
	<div class="panel-heading">Messages d'Accueil</div>
	<div class="panel-body">
		<ul>
			<?php
				while ($fetch = $req->fetch())
				{
					echo "<li class='special_input' id='panel_node_" . $fetch['id'] . "'><input name='message' type='text' value='" . htmlspecialchars(utf8_encode($fetch['message']), ENT_QUOTES) . "' placeholder='Vous devez placer une annonce' required='true'></p> <a href='#'><span class='glyphicon glyphicon-pencil' aria-hidden='true'></span></a> <a href='#'><span class='glyphicon glyphicon-minus' aria-hidden='true'></span></a></li>";
				}
			?>
			<li class='special_input' id='panel_node_add'><input name='message' type='text' placeholder='Placez votre annonce ici'/> <a href='#'><span class='glyphicon glyphicon-plus'></span></a></li>
		</ul>
	</div>
</div>