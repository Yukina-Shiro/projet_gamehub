<?php

// Load local database
require_once __DIR__ . '../../data/Cars.php';

// Acces Control
header( 'Access-Control-Allow-Origin: *');
header( 'Access-Control-Allow-Methods: POST, GET, OPTIONS');

?>
<form id="form_quote" method="post">
	<h1>
		Formulaire d'achat de voiture
	</h1>
	<fieldset>
		<legend>Informations personnelles : </legend>
		<label for="lastname">Nom :</label><input id="lastname" name="lastname" type="text" required value="<?php echo $this->data["lastname"] ?>" />
		<label for="firstname">Prénom :</label><input id="firstname" name="firstname" type="text" required value="<?php echo $this->data["firstname"] ?>" />
		<label for="email">Email :</label><input id="email" name="email" type="email" required value="<?php echo $this->data["email"] ?>" />
	</fieldset>
	<!--  Brend and Model -->
	<fieldset>
		<legend>Marque & Modèle : </legend>
		<select id="brend" name="brend" required>
			<option value="">Marque ?</option>
			<?php
			foreach ( Cars::$brends as $brend => $models) {
				if ( $brend == $this->data["brend"]) {
					echo "<option value=\"$brend\" selected>$brend</option>";
				} else {
					echo "<option value=\"$brend\">$brend</option>";
				}
			}
			?>
		</select>
		<select id="model" name="model" required>
				<option value="">Modèle ?</option>
				<?php
				foreach ( Cars::$brends[$this->data["brend] as $model => $prices) {
					if ( $model == $this->data["model"]) {
						echo "<option value=\"$model\" selected>$model</option>";
					} else {
						echo "<option value=\"$model\">$model</option>";
					}
				}
				?>
		</select>
		<!-- Model price -->
		<label for="model_price">Valeur (€) :</label><input id="model_price" type="number" name="model_price" readonly value="<?php echo $this->data["model_price"] ?>" />
		</fieldset>
	<!-- Gearbox -->
	<fieldset>
		<legend>Boite de vitesse : </legend>
		<label for="gearbox_manual">Manuelle</label><input type="radio" name="gearbox" id="gearbox_manual" value="manual" <?php echo $this->data["checked_gearboxes"]['manual'] ?> />
		<label for="gearbox_robotic">Robotisée (1000€)</label><input type="radio" name="gearbox" id="gearbox_robotic" value="robotic" <?php echo $this->data["checked_gearboxes"]['robotic'] ?> />
		<label for="gearbox_automatic">Automatique (1500€)</label><input type="radio" name="gearbox" id="gearbox_automatic" value="automatic" <?php echo $this->data["checked_gearboxes"]['automatic'] ?> />
		<label for="gearbox_price">Valeur (€) :</label><input id="gearbox_price" type="number" readonly value="<?php echo $this->data["gearbox_price"] ?>" />
	</fieldset>
	<!-- Color -->
	<fieldset>
		<legend>Couleur : </legend>
		<label for="color_standard">Standard</label><input type="radio" name="color" id="color_standard" value="standard" <?php echo $this->data["checked_colors"]['standard'] ?> />
		<label for="color_metallic">Métalisé (500€)</label><input type="radio" name="color" id="color_metallic" value="metallic" <?php echo $this->data["checked_colors"]['metallic'] ?> />
		<label for="color_nacreous">Nacrée (750€)</label><input type="radio" name="color" id="color_nacreous" value="nacreous" <?php echo $this->data["checked_colors"]['nacreous'] ?> />
		<label for="color_price">Valeur (€) :</label><input id="color_price" type="number" readonly value="<?php echo $this->data["color_price"] ?>" />
	</fieldset>
	<!-- Options -->
	<fieldset>
		<legend>Options : </legend>
		<label for="option_reversing_radar"><input type="checkbox" name="options[reversing_radar]" id="option_reversing_radar" value="reversing_radar" <?php echo $this->data["checked_options"]['reversing_radar'] ?> />Radar de recul (300€)</label>
		<label for="option_xenon_lighthouse"><input type="checkbox" name="options[xenon_lighthouse]" id="option_xenon_lighthouse" value="xenon_lighthouse" <?php echo $this->data["checked_options"]['xenon_lighthouse'] ?> />Phares au xénon (750€)</label>
		<label for="option_speed_regulator"><input type="checkbox" name="options[speed_regulator]" id="option_speed_regulator" value="speed_regulator" <?php echo $this->data["checked_options"]['speed_regulator'] ?> />Régulateur de vitesse (300€)</label>
		<label for="option_rain_sensor"><input type="checkbox" name="options[rain_sensor]" id="option_speed_​​regulator" value="rain_sensor" <?php echo $this->data["checked_options"]['rain_sensor'] ?> />Capteur de pluie (250€)</label>
		<label for="option_air_conditioner"><input type="checkbox" name="options[air_conditioner]" id="option_air_conditioner" value="air_conditioner" <?php echo $this->data["checked_options"]['air_conditioner'] ?> />Climatisation (1000€)</label>
		<label for="options_price">Valeur (€) : <input id="options_price" type="number" readonly value="<?php echo $this->data["options_price"] ?>" /></label>
	</fieldset>
	<fieldset>
		<legend>Reprise de l'ancien véhicule (€) :</legend>
		<label for="return_price"><input id="return_price" type="number" name="return_price" required value="<?php echo $this->data["return_price"] ?>" min="0" max="3000" /></label>
	</fieldset>
	<fieldset>
		<legend>Prix total (€) :</legend>
		<label for="total_price"><input id="total_price" type="number" name="total_price" readonly value="<?php echo $this->data["total_price"] ?>" /></label>
	</fieldset>
	<div>
		<input type="submit" value="Valider" />
		<input type="button" value="Sauvegarder" />
	</div>
</form>