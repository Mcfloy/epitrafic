<?php
	include('views/global/header.php');
?>
	<!--<link rel="stylesheet" href="/v3/css/deconnexion/deconnexion.css">-->
	<div class="container-fluid">
		<br>
		<div class="row-fluid row-centered">
			<div class="col-lg-8 col-md-9 col-centered">
			<?php
				if (isset($_SESSION['login'])) {
					$_SESSION = array();
					session_destroy();
					setcookie('connexionAuto', '1-0', (time() + 365*24*3600));
					header('location:/v3/deconnexion.php');
				} else {
					echo "<div class='alert alert-info' role='alert'>Vous êtes désormais déconnecté ! Ce n'est qu'un aurevoir :-(</div>";
				}
			?>
			</div>
		</div>
	</div>
<?php
	include('views/global/footer.php');
?>