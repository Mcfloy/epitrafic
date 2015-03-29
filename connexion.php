<?php
	include('views/global/header.php');
?>
	<link rel="stylesheet" href="/v3/css/connexion/connexion.css">
	<div class="container-fluid">
		<br>
		<div class="row-fluid row-centered">
			<div class="col-lg-6 col-md-7 col-centered">
			<?php
				include('views/global/mobile.php');

				if (isset($_SESSION['login']))
					header('location:index.php');
				else
					include('views/connexion/form.php');
			?>
			</div>
		</div>
	</div>
<?php
	include('views/global/footer.php');
?>