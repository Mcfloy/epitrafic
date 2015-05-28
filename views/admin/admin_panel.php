<?php
	$req = $bdd->query('SELECT * FROM panel');
?>

<div class="panel panel-default">
	<div class="panel-heading">Messages d'Accueil</div>
	<div class="panel-body">
		<ul class="list-unstyled">
			<?php
				while ($fetch = $req->fetch())
				{
					echo "<li class='special_input' id='panel_node_" . $fetch['id'] . "'><input id='panel_node_input_" . $fetch['id'] . "' name='message' type='text' value='" . htmlspecialchars(html_entity_decode($fetch['message']), ENT_QUOTES) . "' placeholder='Vous devez placer une annonce' required='true'></p> <a href='javascript:void(0)' onclick='update_node(" . $fetch['id'] . ")'><span class='glyphicon glyphicon-pencil' aria-hidden='true'></span></a> <a href='javascript:void(0)' onclick='delete_node(" . $fetch['id'] . ")'><span class='glyphicon glyphicon-minus' aria-hidden='true'></span></a></li>";
				}
			?>
			<li class='special_input' id='panel_node_add'><input name='message' id='node_add_message' type='text' placeholder='Placez votre annonce ici'/> <a href='javascript:void(0)' onclick="add_node()"><span class='glyphicon glyphicon-plus'></span></a></li>
		</ul>
	</div>
</div>
<script src="/js/admin/node.js"></script>