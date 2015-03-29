<?php
if (isset($_SESSION['login'])) {
	?>
	<ul id="js-news" class="js-hidden">
		<li class="news-item">Activité en cours : <strong>Cours de SQL</strong> (Vous avez validé votre token)</li>
		<li class="news-item">Prochaine activité : <strong>Aujourd'hui</strong> à <strong>14h30</strong> pour <strong>un examen</strong> intitulé <strong>Examen Machine</strong>.</li>
		<li class="news-item">Tout va bien</li>
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