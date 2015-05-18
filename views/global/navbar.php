<?php
if (isset($_SESSION['login'])){
?>
	<div id="sidebar-wrapper">
		<ul class="sidebar-nav">
			<li class="sidebar-brand">
				<a href="profil.php?login=<?php echo $_SESSION['login']; ?>">
					<img src="https://cdn.local.epitech.eu/userprofil/miniview/<?php echo $_SESSION['login']; ?>.jpg"/><p id="pseudo_header"><?php echo $_SESSION['title']; ?></p>
				</a>
			</li>
			<?php
				if ($_SESSION['grade'] == 5) {
					echo "<li><a href='admin.php'><span class='glyphicon glyphicon-fire'></span> Administration</a></li>";
				}
			?>
			<li>
				<a href="index.php"><span class="glyphicon glyphicon-home"></span> Accueil</a>
			</li>
			<li>
				<a href="generateur.php"><span class="glyphicon glyphicon-barcode"></span> Générateur de token</a>
			</li>
			<li>
				<a href="classement.php"><span class="glyphicon glyphicon-list"></span> Classement</a>
			</li>
			<li>
				<a href="quiestenligne.php"><span class="glyphicon glyphicon-globe"></span> Qui est ligne ?</a>
			</li>
			<li>
				<a href="deconnexion.php"><span class="glyphicon glyphicon-off"></span> Déconnexion</a>
			</li>
			<li>
				<a href="https://github.com/Celousco/epitrafic" target="_blank"><span class="glyphicon glyphicon-download-alt"></span> GitHub</a>
			</li>
			<li>
				<a href="documentation.php"><span class="glyphicon glyphicon-paperclip"></span> Documentation Epitech Intranet</a>
			</li>
		</ul>
	</div>
	<script>
		var divWidth = $(".sidebar-brand").width();
		var text = $("#pseudo_header");
		var fontSize = 18;

		while (text.width() > divWidth)
		  text.css("font-size", fontSize -= 0.5);
	</script>
	<?php
} else {
	?>
	<div id="sidebar-wrapper">
		<ul class="sidebar-nav">
			<li class="sidebar-brand">
				<a href="#">
					Invité
				</a>
			</li>
			<li>
				<a href="index.php">Accueil</a>
			</li>
			<li>
				<a href="https://github.com/Celousco/epitrafic" target="_blank">GitHub</a>
			</li>
			<li>
				<a href="documentation.php">Documentation Epitech Intranet</a>
			</li>
		</ul>
	</div>
	<?php
}