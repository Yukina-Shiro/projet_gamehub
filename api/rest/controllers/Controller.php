<?php

namespace mvcCore\Api\Rest\Controllers;

use mvcCore\Etc\Config;
use mvcCore\Dao\DAO;

/*
 * @author : Jean-Michel Bruneau
 * @version : 1.0.0
 * @date : 2020-05-03
 *
 * Abstract Rest API Controller class
 */

//
// Controller Factory
//
abstract class Controller {

	// Debug mode
	const DEBUG = \Config::DEBUG;
	
	// HTTP response codes
	// https://restfulapi.net/http-status-codes/
	const STATUS_CODE = [
		'OK' => 200,
		'SUCCESS' => 201, // Create
		'NO_CONTENT' => 204,
		'BAD_REQUEST' => 400
	];

	// Controllers classes path
	public static $controller_dir = __DIR__ . '/';

	// Model object(s)
	protected $model;

	// Database access object
	protected $dao;

	// Constructor
	public function __construct( $model) {
		$this->model = $model;
		// New DAO
		$this->dao = new \DAO();
	}

	// Factory
	public static function factory( \Model $model) {
		// "order" => "Order" => "OrderController"
		$class_name = ucwords( $model::$name) . 'Controller';
		// "OrderController.php"
		$class_filename = self::$controller_dir . $class_name . ".php";
		if ( file_exists( $class_filename) && $class_filename != 'Controller.php') {
			// Load "controllers/OrderController.php" class
			require $class_filename;
			$class = '\\' . __NAMESPACE__ . '\\' . $class_name;
			// Return controller instance
			return new $class( $model);
		} else {
			throw new \UnexpectedValueException( "Class file $class_filename not found in " . __FILE__ . ' line ' . __LINE__);
		}
	}

	// Get inputs and set model properties
	// @Override
	abstract public function input();

	//
	// CRUD : Create, Read, Update, Delete, …
	//

	//
	// Create (i.e. POST) an object
	public function create() {
		// Put Input data into the model
		$this->input();
		// Get data (not the null and the default ones)
		$data = $this->model->getProperties();
		// Display data in debug mode
		if ( self::DEBUG) var_dump( $data);
		// Encrypt data
		$encrypt_data = $this->model->encrypt( $data);
		// Persist the order and get the new id
		$model_class = $this->model::$class_name;
		$id = $this->dao->create( $model_class::$table, $encrypt_data);
		// Output Message
		$msg = [
			'id' => $id,
			'data' => $data
		];
		// Set the message code, info and data
		if ( empty( $id)) {
			$msg['code'] = self::STATUS_CODE['BAD_REQUEST'];
			$msg['info'] = "A create  error has occured for $model_class object :(";
		} else if ( $id > 0) {
			$msg['code'] = self::STATUS_CODE['SUCCESS'];
			$msg['info'] = "Create $model_class object with id = $id :)";
		}
		// Display the message
		http_response_code( $msg['code']);
		echo json_encode( $msg);
	}

	// Read (i.e. GET) an object
	public function read() {
		// Get input id from $GLOBALS['request']
		$id = $GLOBALS['request']['id'];
		// Model Class
		$model_class = ucwords( $this->model::$name) . 'Model';
		// Get the model(s)
		$models = $this->dao->read( $model_class::$table, $model_class, $id);
		// View instance ( model object, "read")
		$data = [];
		if ( count( $models) == 1) { // Just one object
			$this->model = $models[0];
			// Decrypt some fields
			$this->model->decrypt();
			// Get model data
			$data = $this->model->getProperties( false, false);
		} elseif ( count( $models) > 1) { // More than one object ( i.e. use a template with a list layout)
			$this->model = $models;
			// Decrypt some fields
			for ($n = 0; $n < count( $models); $n++) {
				$this->model[$n]->decrypt();
				$data[$n] = $this->model[$n]->getProperties( false, false);
			}
		}
		// Output Message
		$msg = [
			'id' => $id,
			'data' => $data
		];
		// Set the message code, info and data
		if ( empty( $data)) {
			$msg['code'] = self::STATUS_CODE['BAD_REQUEST'];
			$msg['info'] = "A reading  error has occured for $model_class object with id = $id :(";
		} else if ( count( $models) == 1) { // Just one object
			$msg['code'] = self::STATUS_CODE['OK'];
			$msg['info'] = "Reading $model_class object with id = $id :)";
		} else { // More than one object ( i.e. use a template with a list layout)
			$msg['code'] = self::STATUS_CODE['OK'];
			$msg['info'] = "Reading all $model_class object !";
		}
		// Display the message
		http_response_code( $msg['code']);
		echo json_encode( $msg);
	}
	
	// Update (i.e. PUT) an object
	public function update() {
		// Get input id from $GLOBALS['request']
		$id = $GLOBALS['request']['id'];
		// Output Message
		$msg = [
			'id' => $id,
			'data' => null
		];
		if ( ! empty( $id)) {
			// Model Class
			$model_class = '\\' . ucwords( $this->model::$name) . 'Model';
			// Get the object from the database
			$models = $this->dao->read( $model_class::$table, $model_class, $id);
			if ( count( $models) == 1) { // Just one object
				$this->model = $models[0];
				// Decrypt some fields
				$this->model->decrypt();
				// Put POST data into the model
				$this->input();
				// Get data (not the null and the default ones)
				$data = $this->model->getProperties();
				// Encrypt data
				$encrypt_data = $this->model->encrypt( $data);
				// Update the database object
				$result = $this->dao->update( $model_class::$table, $encrypt_data, $id);
				// Message
				if ( empty( $result)) {
					$msg['code'] = self::STATUS_CODE['BAD_REQUEST'];;
					$msg['info'] = 'A update $model_class object error has occured with id : $id :(';
				} else {
					$msg['code'] = self::STATUS_CODE['OK'];;
					$msg['info'] = "Update $model_class object with id : $id :)";
					$msg['data'] = $data;
				}
			} else {
				$msg['code'] = self::STATUS_CODE['BAD_REQUEST'];
				$msg['info'] = "No $model_class object to update with id : $id !";
			}
		} else {
			$msg['code'] = self::STATUS_CODE['BAD_REQUEST'];
			$msg['info'] = "No $model_class object to update with an empty id !";
		}
		// Display the message
		http_response_code( $msg['code']);
		echo json_encode( $msg);
	}
	
	//
	// Delete (i.e. DELETE) an object
	public function delete() {
		// Get input id from $GLOBALS['request']
		$id = $GLOBALS['request']['id'];
		// Delete the order with the current id
		$model_class = $this->model::$class_name;
		$result = $this->dao->delete( $model_class::$table, $id);
		// Message
		$msg = [ 
			'id' => $id,
			'data' => null
		];
		// Set the message code and info
		if ( empty( $result)) {
			$msg['code'] = self::STATUS_CODE['BAD_REQUEST'];
			$msg['info'] = 'A delete error has occured :(';
		} else {
			$msg['code'] = self::STATUS_CODE['OK'];;
			$msg['info'] = "Delete $model_class object with id : $id :)";
		}
		// Display the message
		http_response_code( $msg['code']);
		echo json_encode( $msg);
	}

}
?>
