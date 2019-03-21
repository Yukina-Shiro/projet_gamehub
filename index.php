<?php

//
// URL sample :
//		index.pho?model=order&action=create
//		index.php?model=order&action=read&id=111
//

require_once __DIR__ . "models/Model.php";
require_once __DIR__ . "controllers/Controller.php";

// Model name
$model_name = filter_input( INPUT_GET, 'model', FILTER_SANITIZE_STRING);
// Action on this model
$model_action =  filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING);

// New Model
$model = Model::factory($model_name);
// New Controller
$controller = Controller::factory($model_name, $model);
// Action for this controller
if ( method_exists( $controller, $model_action))
	$controller->$model_action();

?>