<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
	<title>EpiTrafic</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

	<link rel="stylesheet" type="text/css" href="/v3/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/v3/css/global/simple-sidebar.css">
	<link rel="stylesheet" type="text/css" href="/v3/css/global/global.css">
	<link rel="stylesheet" type="text/css" href="/v3/css/global/pace.css">
	<link rel="stylesheet" type="text/css" href="/v3/css/global/ticker.css">
	
	<script src="/v3/js/global/jquery.js"></script>
	<script src="/v3/js/global/pace.min.js"></script>
	<script src="/v3/js/global/jquery.ticker.js"></script>
	<script type="text/javascript" src="/v3/js/global/bootstrap.min.js"></script>
</head>
<body>
	<div id="wrapper">
		<?php
			include('navbar.php');
		?>

		<div id="page-content-wrapper">
			<div class="container-fluid">
				<div id="header">
					<a href="/v3/">
						<img src="/v3/img/logo/epitrafic.gif" />
					</a>
				</div>
				<?php
					include('ticker.php');
				?>