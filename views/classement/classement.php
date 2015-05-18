<?php
	include('views/classement/calcul.php');
?>
<div class="hidden-xs">
	<ol class="breadcrumb">
		<li>Classement</li>
		<?php
			if ($ville == "all")
				echo "<li>National</li>";
			else
				echo "<li>Régional (" . $ville . ")</li>";

			if ($promo != "all")
				echo "<li>Promotion " . $promo . "</li>";
			else
				echo "<li>Toutes promotions</li>";

			if (!isset($_GET['tri']))
				echo "<li>GPA</li>";
			else
			{
				if ($_GET['tri'] == "log")
					echo "<li>Temps de connexion</li>";
				else if ($_GET['tri'] == "credits")
					echo "<li>Crédits</li>";
				else
					echo "<li>GPA</li>";
			}
		?>
	</ol>
	<form>
		<select name="promo" id="ville">
			<option selected="" disabled="">Année de promotion</option>
			<option value="2017">2017</option>
			<option value="2018">2018</option>
			<option value="2019">2019</option>
		</select>
		<select name="ville" id="ville">
			<option selected="" disabled="">Ville</option>
			<option value="all">Toutes</option>
			<option value="BDX">Bordeaux</option>
			<option value="LIL">Lille</option>
			<option value="LYN">Lyon</option>
			<option value="MAR">Marseille</option>
			<option value="MPL">Montpellier</option>
			<option value="NCY">Nancy</option>
			<option value="NAN">Nantes</option>
			<option value="NCE">Nice</option>
			<option value="PAR">Paris</option>
			<option value="REN">Rennes</option>
			<option value="STG">Strasbourg</option>
			<option value="TLS">Toulouse</option>
		</select>
		<select name="tri" id="tri">
			<option selected="" disabled="">Tri</option>
			<option value="gpa">Par GPA</option>
			<option value="log">Par temps de log</option>
			<option value="credits">Par crédits</option>
		</select>
		<input type="submit" class="btn btn-xs btn-primary" value="Afficher le classement" />
	</form>
	<input type="text" onkeyup="refresh_classement()" id="search_login" placeholder="Insérez un login">
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Classement National</th><th>Classement Régional</th><th>Login</th><th>Ville</th><th>GPA</th><th>Temps de connexion (Heure)</th><th>Crédits</th>
			</tr>
		</thead>
		<tbody>
			<?php
				foreach ($fetch as $key => $value) {
					if ($tri == "log") {
						if ($fetch[$key]['informations_epitech']['classement_log'] < $fetch[$key]['informations_epitech']['classement_log_old'])
							$arrow = "<td class='col-lg-2'>" . $fetch[$key]['informations_epitech']['classement_log'] . " <span class='label label-success'>+" . ($fetch[$key]['informations_epitech']['classement_log_old'] - $fetch[$key]['informations_epitech']['classement_log']) . "</span>";
						else if($fetch[$key]['informations_epitech']['classement_log'] > $fetch[$key]['informations_epitech']['classement_log_old'])
							$arrow = "<td class='col-lg-2'>" . $fetch[$key]['informations_epitech']['classement_log'] . " <span class='label label-danger'>" . ($fetch[$key]['informations_epitech']['classement_log_old'] - $fetch[$key]['informations_epitech']['classement_log']) . "</span>";
						else
							$arrow = "<td class='col-lg-2'>" . $fetch[$key]['informations_epitech']['classement_log'];

						if ($fetch[$key]['informations_epitech']['classement_national_log'] < $fetch[$key]['informations_epitech']['classement_national_log_old'])
							$arrow_national = "<td class='col-lg-2'>" . $fetch[$key]['informations_epitech']['classement_national_log'] . " <span class='label label-success'>+" . ($fetch[$key]['informations_epitech']['classement_national_log_old'] - $fetch[$key]['informations_epitech']['classement_national_log']) . "</span>";
						else if($fetch[$key]['informations_epitech']['classement_national_log'] > $fetch[$key]['informations_epitech']['classement_national_log_old'])
							$arrow_national = "<td class='col-lg-2'>" . $fetch[$key]['informations_epitech']['classement_national_log'] . " <span class='label label-danger'>" . ($fetch[$key]['informations_epitech']['classement_national_log_old'] - $fetch[$key]['informations_epitech']['classement_national_log']) . "</span>";
						else
							$arrow_national = "<td class='col-lg-2'>" . $fetch[$key]['informations_epitech']['classement_national_log'];
					}
					else if ($tri == "credits") {
						if ($fetch[$key]['informations_epitech']['classement_credits'] < $fetch[$key]['informations_epitech']['classement_credits_old'])
							$arrow = "<td class='col-lg-2'>" . $fetch[$key]['informations_epitech']['classement_credits'] . " <span class='label label-success'>+" . ($fetch[$key]['informations_epitech']['classement_credits_old'] - $fetch[$key]['informations_epitech']['classement_credits']) . "</span>";
						else if($fetch[$key]['informations_epitech']['classement_credits'] > $fetch[$key]['informations_epitech']['classement_credits_old'])
							$arrow = "<td class='col-lg-2'>" . $fetch[$key]['informations_epitech']['classement_credits'] . " <span class='label label-danger'>" . ($fetch[$key]['informations_epitech']['classement_credits_old'] - $fetch[$key]['informations_epitech']['classement_credits']) . "</span>";
						else
							$arrow = "<td class='col-lg-2'>" . $fetch[$key]['informations_epitech']['classement_credits'];
						
						if ($fetch[$key]['informations_epitech']['classement_national_credits'] < $fetch[$key]['informations_epitech']['classement_national_credits_old'])
							$arrow_national = "<td class='col-lg-2'>" . $fetch[$key]['informations_epitech']['classement_national_credits'] . " <span class='label label-success'>+" . ($fetch[$key]['informations_epitech']['classement_national_credits_old'] - $fetch[$key]['informations_epitech']['classement_national_credits']) . "</span>";
						else if($fetch[$key]['informations_epitech']['classement_national_credits'] > $fetch[$key]['informations_epitech']['classement_national_credits_old'])
							$arrow_national = "<td class='col-lg-2'>" . $fetch[$key]['informations_epitech']['classement_national_credits'] . " <span class='label label-danger'>". ($fetch[$key]['informations_epitech']['classement_national_credits_old'] - $fetch[$key]['informations_epitech']['classement_national_credits']) . "</span>";
						else
							$arrow_national = "<td class='col-lg-2'>" . $fetch[$key]['informations_epitech']['classement_national_credits'];
					}
					else {
						if ($fetch[$key]['informations_epitech']['classement_gpa'] < $fetch[$key]['informations_epitech']['classement_gpa_old'])
							$arrow = "<td class='col-lg-2'>" . $fetch[$key]['informations_epitech']['classement_gpa'] . " <span class='label label-success'>+" . ($fetch[$key]['informations_epitech']['classement_gpa_old'] - $fetch[$key]['informations_epitech']['classement_gpa']) . "</span>";
						else if($fetch[$key]['informations_epitech']['classement_gpa'] > $fetch[$key]['informations_epitech']['classement_gpa_old'])
							$arrow = "<td class='col-lg-2'>" . $fetch[$key]['informations_epitech']['classement_gpa'] . " <span class='label label-danger'>" . ($fetch[$key]['informations_epitech']['classement_gpa_old'] - $fetch[$key]['informations_epitech']['classement_gpa']) . "</span>";
						else
							$arrow = "<td class='col-lg-2'>" . $fetch[$key]['informations_epitech']['classement_gpa'];

						if ($fetch[$key]['informations_epitech']['classement_national_gpa'] < $fetch[$key]['informations_epitech']['classement_national_gpa_old'])
							$arrow_national = "<td class='col-lg-2'>" . $fetch[$key]['informations_epitech']['classement_national_gpa'] . " <span class='label label-success'>+" . ($fetch[$key]['informations_epitech']['classement_national_gpa_old'] - $fetch[$key]['informations_epitech']['classement_national_gpa']) . "</span>";
						else if($fetch[$key]['informations_epitech']['classement_national_gpa'] > $fetch[$key]['informations_epitech']['classement_national_gpa_old'])
							$arrow_national = "<td class='col-lg-2'>" . $fetch[$key]['informations_epitech']['classement_national_gpa'] . " <span class='label label-danger'>". ($fetch[$key]['informations_epitech']['classement_national_gpa_old'] - $fetch[$key]['informations_epitech']['classement_national_gpa']) ."</span>";
						else
							$arrow_national = "<td class='col-lg-2'>" . $fetch[$key]['informations_epitech']['classement_national_gpa'];
					}

					echo  "<tr>" . $arrow_national . "</td>" . $arrow . "</td><td class='col-lg-1'><a href='https://intra.epitech.eu/user/" . $fetch[$key]['login'] . "/' target='_blank'>" . $fetch[$key]['login'] . "</a></td><td class='col-lg-1'>" . $fetch[$key]['ville'] . "</td><td class='col-lg-2'>". $fetch[$key]['informations_epitech']['gpa'];
					if ($fetch[$key]['informations_epitech']['gpa'] - $fetch[$key]['informations_epitech']['gpa_old'] > 0)
						echo " <span class='label label-success'>+" . ($fetch[$key]['informations_epitech']['gpa'] - $fetch[$key]['informations_epitech']['gpa_old']);
					else
						echo " <span class='label label-danger'>" . ($fetch[$key]['informations_epitech']['gpa'] - $fetch[$key]['informations_epitech']['gpa_old']);
					echo "</span></td><td>" . $fetch[$key]['informations_epitech']['log'];
					if ($fetch[$key]['informations_epitech']['log'] - $fetch[$key]['informations_epitech']['log_old'] > 0)
						echo " <span class='label label-success'>+" . ($fetch[$key]['informations_epitech']['log'] - $fetch[$key]['informations_epitech']['log_old']);
					else
						echo " <span class='label label-danger'>" . ($fetch[$key]['informations_epitech']['log'] - $fetch[$key]['informations_epitech']['log_old']);
					echo "</span></td><td>".$fetch[$key]['informations_epitech']['credits'];
					if ($fetch[$key]['informations_epitech']['credits'] - $fetch[$key]['informations_epitech']['credits_old'] > 0)
						echo " <span class='label label-success'>+" . ($fetch[$key]['informations_epitech']['credits'] - $fetch[$key]['informations_epitech']['credits_old']);
					else 
						echo " <span class='label label-danger'>" . ($fetch[$key]['informations_epitech']['credits'] - $fetch[$key]['informations_epitech']['credits_old']);
					echo "</span></td></tr>";
				}
			?>
		</tbody>
	</table>
</div>
<script>
	function refresh_classement() {
		var login = $('#search_login').val();
		$('.table-striped tbody tr td:nth-child(3):not(:contains(' + login + '))').parent().hide();
		$('.table-striped tbody tr td:nth-child(3):contains(' + login + ')').parent().show();
	}
</script>