<?php

// Autoloader
require __DIR__ . '/../../vendor/autoload.php';

use mvcCore\Etc\Config;
use mvcCore\Models\Model;
use mvcCore\Controllers\Controller;

/*
 * @author : Jean-Michel Bruneau
 * @version : 1.2.0
 * @date : 2019-12-18
 *
 * Mandatory main entry for this MVC Core framework
 */

// Acces Control for CORS
// Cross-Origin Resource Sharing (CORS) is a mechanism that uses additional HTTP headers to tell browsers
// to give a web application running at one origin, access to selected resources from a different origin.
// A web application executes a cross-origin HTTP request when it requests a resource that has a different origin
// (domain, protocol, or port) from its own.
header( 'Access-Control-Allow-Origin: *');
header( 'Access-Control-Allow-Methods:  DELETE, GET, POST, PUT');
header( 'Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');

// JSON output
header("Content-Type: application/json; charset=utf-8");

if ( \Config::DEBUG) {
	error_reporting( E_ALL);
	ini_set( 'display_errors', '1');
}

//
// URL samples :
// -> Create an Order
//		- index.php?model=order&action=create
// -> Create an Order with the template layout "mobile" (see templates/Template.php)
//		- index.php?model=order&action=create&layout=mobile
// -> Read (and of course display) the Order with id=1
//		- index.php?model=order&action=read&id=1
// -> Update the Order with id=11
//		- index.php?model=order&action=update&id=11
// -> Delete the Order with id=111
//		-  index.php?model=order&action=delete&id=1
//

// Model name
$model_name = filter_input( INPUT_GET, 'model', FILTER_SANITIZE_STRING, array( 'options' => array( 'default' => \Config::MODEL)));

// Request methods : DELETE, GET, POST, PUT
$request_method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING);

// Action on this model
$model_action = \Config::ACTION;
switch ( $request_method) { // CRUD actions, 
	case 'POST' :
		$model_action = 'create';
		break;
	case 'GET' :
		$model_action = 'read';
		break;
	case 'PUT' :
		$model_action = 'update';
		break;
	case 'DELETE' :
		$model_action = 'delete';
		break;
}
// Model id
$model_id = filter_input( INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

// ====================================================================
// Be careful : 'model', 'action', 'id', 'path', 'layout', 'service' adn 'step' are reserved input parameter names
// ====================================================================

// Set global input value
$GLOBALS['request'] = [
	'model' => $model_name,
	'action' => $model_action,
	'id' => $model_id
];
if ( \Config::DEBUG) var_dump( $GLOBALS['request'], $_REQUEST);

if ( ! empty( $model_name) && ! empty( $model_action)) {
	// New Model
	try {
		$model = \Model::factory($model_name);
	} catch ( \Exception $e) {
		die( 'Model Factory Exception : ' . $e->getMessage() . "\n");
	}
	// New API Rest Controller
	try {
		$controller = Controller::factory( $model);
	} catch ( \Exception $e) {
		die( 'Controler Factory Exception : ' . $e->getMessage() . "\n");
	}
	// Action for this controller
	if ( method_exists( $controller, $model_action))
		try {
			$controller->$model_action();
		} catch ( \Exception $e) {
			echo 'Controler Action Exception : ',  $e->getMessage(), "\n";
		}
	else
		die( "Action $model_action does not exists !");
} else
	die( "« Eh, what's up, doc ? »");
?>
