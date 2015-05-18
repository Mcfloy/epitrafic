<?php
	include('views/global/header.php');
?>
	<div class="container-fluid">
		<br>
		<div class="row-fluid row-centered">
			<div class="col-lg-8 col-md-9 col-centered">
			<?php
				if (isset($_SESSION['login'])) {
					// On déconnecte l'utilisateur, en effaçant ses variables de session et en modifiant le cookie
					$_SESSION = array();
					session_destroy();
					setcookie('auto_connect', '0-0', (time() + 3600));
					header('location:/');
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