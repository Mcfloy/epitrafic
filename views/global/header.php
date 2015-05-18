<?php
	session_start();
	include('views/global/identifiants.php');
	include('views/connexion/cookie.php');

	if(isset($_COOKIE['auto_connect']) && !isset($_SESSION['login']))
	{
		list($idCookie, $passCookie) = explode('-', urldecode($_COOKIE['auto_connect']));
		$login = htmlspecialchars($idCookie);
		$password = htmlspecialchars(my_decrypt($passCookie));
		$form = "login=" . $login . "&password=" . $password . "&remind=true";

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://intra.epitech.eu?format=json");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $form);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_HEADER, 1);

		if (($code = curl_exec($ch)) == false) {
			echo "Curl Error: " . curl_error($ch);
		}

		curl_close($ch);

		list($header, $content) = explode("\r\n\r\n", $code, 2);
		$session = str_replace("\r\n", "", $header);
		$session = preg_replace("/.*PHPSESSID=([^;]*);.*/","\\1", $session);

		$content = str_replace("// Epitech JSON webservice ...\n", "", $content);
		$json = json_decode($content);

		if (!isset($json->{"message"})) {
			$_SESSION['login'] = $login;
			$_SESSION['password'] = my_encrypt(urlencode($password));
			$_SESSION['title'] = $json->{"infos"}->{"title"};
			$_SESSION['ville'] = $json->{"infos"}->{"location"};
			$_SESSION['promo'] = $json->{"infos"}->{"promo"};
			$update = $bdd->prepare('UPDATE membres SET date_connexion = NOW() WHERE login = :login');
			$update->execute(array('login'=>$login));
			$update->closeCursor();
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>EpiTrafic</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/global/simple-sidebar.css">
	<link rel="stylesheet" type="text/css" href="css/global/global.css">
	<link rel="stylesheet" type="text/css" href="css/global/pace.css">
	<link rel="stylesheet" type="text/css" href="css/global/ticker.css">
	
	<script src="js/global/jquery.js"></script>
	<script src="js/global/pace.min.js"></script>
	<script src="js/global/jquery.ticker.js"></script>
	<script type="text/javascript" src="js/global/bootstrap.min.js"></script>
</head>
<body>
	<div id="wrapper">
		<?php
			include('navbar.php');
		?>

		<div id="page-content-wrapper">
			<div class="container-fluid">
				<div id="header">
					<a href="/">
						<img src="/img/logo/epitrafic.gif" />
					</a>
				</div>
				<script>
				$('#header').mousemove(function(e){
				    var amountMovedX = (e.pageX * -1 / 10);
				    var amountMovedY = (e.pageY * -1 / 2);
				    $(this).css('background-position', amountMovedX + 'px ' + amountMovedY + 'px');
				});
				</script>
				<?php
					include('ticker.php');
				?>