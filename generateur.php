<?php
	include('views/global/header.php');
?>
	<link rel="stylesheet" href="css/generateur/generateur.css">
	<div class="container-fluid">
		<br>
		<div class="row-fluid row-centered">
			<div class="col-lg-12 col-centered">
			<?php
				include('views/global/mobile.php');

				if (isset($_SESSION['login']))
					include('views/generateur/generateur.php');
				else
					header('location:connexion.php');
			?>
			</div>
		</div>
	</div>
<?php
	include('views/global/footer.php');
?>