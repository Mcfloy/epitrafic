<?php
if (isset($_SESSION['login'])) {
	include('views/global/identifiants.php');
	$req = $bdd->query('SELECT message FROM panel');
	?>
	<ul id="js-news" class="js-hidden">
		<?php
		while ($fetch = $req->fetch()) {
			echo "<li class='news-item'>". utf8_encode($fetch['message']) ."</li>";
		}
		$req->closeCursor();
		?>
	</ul>
<?php
} else {
	setlocale (LC_TIME, 'fr_FR.utf8','fra');
	?>
	<ul id="js-news" class="js-hidden">
		<li class="news-item">Nous sommes le <?php echo strftime("%A %d %B"); ?>.</li>
	</ul>
	<?php
}