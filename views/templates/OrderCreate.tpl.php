<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="Car Whorkshop Form Order">
<meta name="author" content="Jean-Michel Bruneau">
<title>Car order form</title>
<!-- global css go here -->
<link href="css/index.css" rel="stylesheet">
<!-- global scripts go here -->

</head>
<body class="elegant-aero">
	<header>
		<h1>Bon de commande d'une voiture</h1>
	</header>
	<form id="order_create" method="post">
		<fieldset>
			<legend>Informations personnelles : </legend>
			<label for="lastname">Nom :</label><input id="lastname" name="lastname" type="text" required="required" value="<?= $data['lastname'] ?>" /> <label for="firstname">Prénom :</label><input id="firstname"
				name="firstname" type="text" required="required" value="<?= $data['firstname'] ?>"
			/> <label for="email">Email :</label><input id="email" name="email" type="email" required="required" value="<?= $data['email'] ?>" />
		</fieldset>
		<!--  Brend and Model -->
		<fieldset>
			<legend>Marque & Modèle : </legend>
			<!--  Brend -->
			<select id="brend" name="brend" required="required" onchange="document.getElementById('order_create').submit();"> <!-- Submit on change -->
				<option value="">Marque ?</option>
			<?php
			foreach ( Cars::$brends as $brend => $models) {
				if ( $brend == $data['brend']) {
					echo "<option value=\"$brend\" selected=\"selected\">$brend</option>";
				} else {
					echo "<option value=\"$brend\">$brend</option>";
				}
			}
			?>
			</select>
			<!--  Model -->
			<select id="model" name="model" required="required" onchange="document.getElementById('order_create').submit();">
				<option value="">Modèle ?</option>
				<?php
				foreach ( Cars::$brends[$data['brend']] as $model => $prices) {
					if ( $model == $data['model']) {
						echo "<option value=\"$model\" selected=\"selected\">$model</option>";
					} else {
						echo "<option value=\"$model\">$model</option>";
					}
				}
				?>
		</select>
			<!-- Model price -->
			<label for="model_price">Valeur (€) :</label><input id="model_price" type="number" name="model_price" readonly="readonly" value="<?= $data['model_price'] ?>" />
		</fieldset>
		<!-- Gearbox -->
		<fieldset>
			<legend>Boite de vitesse : </legend>
			<label for="gearbox_manual">Manuelle</label><input type="radio" name="gearbox" id="gearbox_manual" value="manual" <?= $data['checked_gearboxes']['manual'] ?> /> <label for="gearbox_robotic">Robotisée
				(1000€)</label><input type="radio" name="gearbox" id="gearbox_robotic" value="robotic" <?= $data['checked_gearboxes']['robotic'] ?> /> <label for="gearbox_automatic">Automatique (1500€)</label><input
				type="radio" name="gearbox" id="gearbox_automatic" value="automatic" <?= $data['checked_gearboxes']['automatic'] ?>
			/> <label for="gearbox_price">Valeur (€) :</label><input id="gearbox_price" type="number" readonly="readonly" value="<?= $data['gearbox_price'] ?>" />
		</fieldset>
		<!-- Color -->
		<fieldset>
			<legend>Couleur : </legend>
			<label for="color_standard">Standard</label><input type="radio" name="color" id="color_standard" value="standard" <?= $data['checked_colors']['standard'] ?> /> <label for="color_metallic">Métalisé
				(500€)</label><input type="radio" name="color" id="color_metallic" value="metallic" <?= $data['checked_colors']['metallic'] ?> /> <label for="color_nacreous">Nacrée (750€)</label><input
				type="radio" name="color" id="color_nacreous" value="nacreous" <?= $data['checked_colors']['nacreous'] ?>
			/> <label for="color_price">Valeur (€) :</label><input id="color_price" type="number" readonly="readonly" value="<?= $data['color_price'] ?>" />
		</fieldset>
		<!-- Options -->
		<fieldset>
			<legend>Options : </legend>
			<label for="option_reversing_radar"><input type="checkbox" name="options[reversing_radar]" id="option_reversing_radar" value="reversing_radar"
				<?= $data['checked_options']['reversing_radar'] ?>
			/>Radar de recul (300€)</label> <label for="option_xenon_lighthouse"><input type="checkbox" name="options[xenon_lighthouse]" id="option_xenon_lighthouse" value="xenon_lighthouse"
				<?= $data['checked_options']['xenon_lighthouse'] ?>
			/>Phares au xénon (750€)</label> <label for="option_speed_regulator"><input type="checkbox" name="options[speed_regulator]" id="option_speed_regulator" value="speed_regulator"
				<?= $data['checked_options']['speed_regulator'] ?>
			/>Régulateur de vitesse (300€)</label> <label for="option_rain_sensor"><input type="checkbox" name="options[rain_sensor]" id="option_speed_​​regulator" value="rain_sensor"
				<?= $data['checked_options']['rain_sensor'] ?>
			/>Capteur de pluie (250€)</label> <label for="option_air_conditioner"><input type="checkbox" name="options[air_conditioner]" id="option_air_conditioner" value="air_conditioner"
				<?= $data['checked_options']['air_conditioner'] ?>
			/>Climatisation (1000€)</label> <label for="options_price">Valeur (€) : <input id="options_price" type="number" readonly="readonly" value="<?= $data['options_price'] ?>" /></label>
		</fieldset>
		<fieldset>
			<legend>Reprise de l'ancien véhicule (€) :</legend>
			<label for="return_price"><input id="return_price" type="number" name="return_price" required="required" value="<?= $data['return_price'] ?>" min="0" max="3000" /></label>
		</fieldset>
		<fieldset>
			<legend>Prix total (€) :</legend>
			<label for="total_price"><input id="total_price" type="number" name="total_price" readonly="readonly" value="<?= $data['total_price'] ?>" /></label>
		</fieldset>
		<div>
			<input type="reset" class="buttonReset" value="Effacer" /><input type="submit" class="buttonSave" value="Sauvegarder" />
		</div>
	</form>
	<footer>
		<p>© 2020 JM BRUNEAU - UCA</p>
	</footer>
</body>
</html>