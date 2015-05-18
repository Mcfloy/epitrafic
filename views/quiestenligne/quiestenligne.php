<?php
	include('views/quiestenligne/calcul.php');
?>
<div class="hidden-xs">
	<ol class="breadcrumb">
		<li>Qui est en ligne ?</li>
		<?php
			if ($ville == "all")
				echo "<li>Echelle National</li>";
			else
				echo "<li>Echelle Régional (" . $ville . ")</li>";

			if ($promo != "all")
				echo "<li>Promotion " . $promo . "</li>";
			else
				echo "<li>Toutes promotions</li>";
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
		<input type="submit" class="btn btn-xs btn-primary" value="Afficher le trombinoscope" />
	</form>
	<input type="text" onkeyup="refresh_trombi()" id="search_login" placeholder="Insérez un login">
	<button class="btn btn-xs btn-primary" onclick="show_business()">Afficher tout le monde</button>
	<button class="btn btn-xs btn-success" onclick="show_success()">Afficher les connectés à l'école</button>
	<button class="btn btn-xs btn-warning" onclick="show_warning()">Afficher les connectés à l'extérieur</button>
	<button class="btn btn-xs btn-danger" onclick="show_danger()">Afficher les déconnectés</button>
	<ul class="quiestenligne">
		<?php
			while ($fetch = $req->fetch())
			{
				if ($fetch['netsoul'] == 2)
					echo "<li><img src='https://cdn.local.epitech.eu/userprofil/profilview/" . $fetch['login'] . ".jpg' onerror='if (this.src != \"https://intra.epitech.eu/static7373/img/nopicture-profilview.png\") this.src = \"https://intra.epitech.eu/static7373/img/nopicture-profilview.png\"'/><a href='profil.php?login=" . $fetch['login'] . "' class='btn btn-success' data-toggle='tooltip' data-placement='top' title=\"Connecté à l'école\">" . $fetch['login'] . "</a></li>";
				elseif ($fetch['netsoul'] == 1)
					echo "<li><img src='https://cdn.local.epitech.eu/userprofil/profilview/" . $fetch['login'] . ".jpg' onerror='if (this.src != \"https://intra.epitech.eu/static7373/img/nopicture-profilview.png\") this.src = \"https://intra.epitech.eu/static7373/img/nopicture-profilview.png\"'/><a href='profil.php?login=" . $fetch['login'] . "' class='btn btn-warning' data-toggle='tooltip' data-placement='top' title=\"Connecté à l'extérieur\">" . $fetch['login'] . "</a></li>";
				elseif ($fetch['netsoul'] == 0)
					echo "<li><img src='https://cdn.local.epitech.eu/userprofil/profilview/" . $fetch['login'] . ".jpg' onerror='if (this.src != \"https://intra.epitech.eu/static7373/img/nopicture-profilview.png\") this.src = \"https://intra.epitech.eu/static7373/img/nopicture-profilview.png\"'/><a href='profil.php?login=" . $fetch['login'] . "' class='btn btn-danger' data-toggle='tooltip' data-placement='top' title=\"Non connecté\">" . $fetch['login'] . "</a></li>";
			}
		?>
	</ul>
</div>
<script type="text/javascript">
	$(function () {
  		$("[data-toggle='tooltip']").tooltip()
	})

	function refresh_trombi() {
		var login = $('#search_login').val();
		$('.quiestenligne li a:not(:contains(' + login + '))').parent().hide();
		$('.quiestenligne li a:contains(' + login + ')').parent().show();
	}

	function show_business() {
		$('.quiestenligne li').show();
	}

	function show_success() {
		$('.quiestenligne li a:not(.btn-success)').parent().hide();
		$('.quiestenligne li .btn-success').parent().show();
	}

	function show_warning() {
		$('.quiestenligne li a:not(.btn-warning)').parent().hide();
		$('.quiestenligne li .btn-warning').parent().show();
	}

	function show_danger() {
		$('.quiestenligne li a:not(.btn-danger)').parent().hide();
		$('.quiestenligne li .btn-danger').parent().show();
	}
</script>