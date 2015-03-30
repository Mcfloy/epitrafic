<?php
if (isset($_SESSION['login'])){
	?>
	<div id="sidebar-wrapper">
		<ul class="sidebar-nav">
			<li class="sidebar-brand">
				<a href="#">
					<img src="https://cdn.local.epitech.eu/userprofil/miniview/<?php echo $_SESSION['login']; ?>.jpg"/> <?php echo $_SESSION['title']; ?> <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
				</a>
			</li>
			<li>
				<a href="deconnexion.php">Déconnexion</a>
			</li>
			<li>
				<a href="#">Forum</a>
			</li>
			
			<li>
				<a href="#">Classement</a>
			</li>
			<li>
				<a href="#">Présentation du projet</a>
			</li>
			<li>
				<a href="https://github.com/Celousco/epitrafic" target="_blank">GitHub</a>
			</li>
			<li>
				<a href="#">Documentation Epitech Intranet</a>
			</li>
		</ul>
	</div>
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
				<a href="#">Présentation du projet</a>
			</li>
			<li>
				<a href="https://github.com/Celousco/epitrafic" target="_blank">GitHub</a>
			</li>
			<li>
				<a href="#">Documentation Epitech Intranet</a>
			</li>
		</ul>
	</div>
	<?php
}