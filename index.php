<?php

//
// URL sample :
//		index.pho?model=order&action=create
//

// Load config
require_once __DIR__ . '/etc/Config.php';

// Load Model
require_once __DIR__ . '/models/Model.php';

// Load controller
require_once __DIR__ . '/controllers/Controller.php';

// Set debug mode
if ( Config::DEBUG) {
	error_reporting( E_ALL);
	ini_set( 'display_errors', '1');
}

// Model name
$model_name = filter_input( INPUT_GET, 'model', FILTER_SANITIZE_STRING);

// Action on this model
$model_action =  filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING);

// New Model
$model = Model::factory( $model_name);

// New Controller
$controller = Controller::factory($model_name, $model);

// Action for this controller
if ( method_exists( $controller, $model_action)) {
	$controller->$model_action();
}
?>