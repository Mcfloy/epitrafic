<div class="hidden-xs">
	<?php
	$login = $_SESSION['login'];
	$password = my_decrypt($_SESSION['password']);
	$form = "login=" . urlencode($login) . "&password=" . urlencode($password) . "&remind=true";

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, "https://intra.epitech.eu?format=json");
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $form);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);

	curl_setopt($ch, CURLOPT_HEADER, 1);

	if (($code = curl_exec($ch)) == false) {
		echo "Curl Error: ".curl_error($ch);
	}

	if (curl_errno($ch))
	{
		echo "Erreur Curl : ".curl_error($ch);
	}

	curl_close($ch);

	list($header, $content) = explode("\r\n\r\n", $code, 2);
	$session = str_replace("\r\n", "", $header);
	$session = preg_replace("/.*PHPSESSID=([^;]*);.*/","\\1", $session);

	$content = str_replace("// Epitech JSON webservice ...\n", "", $content);
	$json = json_decode($content);

	$activites = $json->{"board"}->{"activites"};

	$i = 0;
	$token = 0;

	while (isset($activites[$i])){
		if ($activites[$i]->{"token"} != NULL)
			$token++;
		$i++;
	}

	if ($token != 0)
	{
		?>
			<div style="text-align:center">
				<p>Activités en attente d'activation :</p>
				<select id="activite">
				<?php
				$i = 0;
				while (isset($activites[$i])){
					if ($activites[$i]->{"token"} != NULL)
						echo "<option value='".$activites[$i]->{"token_link"}."'>".$activites[$i]->{"title"}."</option>";
					$i++;
				}
				?>
				</select>
				<button class="small inline" id="button_generate" onclick="generate()">Générer un token</button>
				<div id="token"></div>
				<script type="text/javascript">
					var display = document.getElementById('token');

					function display_title(txt){
		                for(var i = 0, l = txt.length; i < l; i++) {
		                    (function(i) {
		                        setTimeout(function() {
		                            display.innerHTML += txt.charAt(i);
		                        }, i * 30);
		                    }(i));
		                }
		        	}
					function generate() {
						var xhr = new XMLHttpRequest(),
							activite = document.getElementById("activite"),
							token = activite.options[activite.selectedIndex].value;

							xhr.onreadystatechange = function () {
								if (xhr.readyState == 4 && xhr.status == 200) {
									document.getElementById("button_generate").disabled = false;
									$("#token").fadeOut("slow", function() {
										$(this).html(xhr.responseText)
									}).fadeIn("slow");
								}
							}

						document.getElementById("button_generate").disabled = true;
						document.getElementById("token").innerHTML = "<p>";
						display_title("Obtention d'un token via la base de données d'EPITECH...");
						setTimeout(function(){
							document.getElementById("token").innerHTML += "</p><img src='/img/ajax/Bill.gif'/>"; }, 1800);

						xhr.open("POST", "/views/generateur/generate.php", true);
						xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
						xhr.send("token_link=" + token);
					}
				</script>
			</div>
		<?php
	}
	else
	{
		echo "<p style='text-align:center'>Vous n'avez aucune activité à valider.</p>";
	}
	?>
</div>