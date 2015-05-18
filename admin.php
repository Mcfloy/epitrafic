<?php
	include('views/global/header.php');
?>
	<link rel="stylesheet" href="/v3/css/admin/admin.css">
	<div class="container-fluid">
		<br>
		<div class="row-fluid row-centered">
			<div class="col-lg-12 col-centered">
			<?php
				include('views/global/mobile.php');

				if (isset($_SESSION['grade'])) {
					if ($_SESSION['grade'] < 5)
						include('views/erreur/grade.php');
					else {
						if (isset($_GET['action']))
							include('views/admin/action.php');
						else
							include('views/admin/index.php');
					}
				}
				else
					header('location:connexion.php');
			?>
			</div>
		</div>
	</div>
<?php
	include('views/global/footer.php');
?>