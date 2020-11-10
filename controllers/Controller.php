<?php

require_once __DIR__ . '/../views/View.php';

require_once __DIR__ . '/../dao/DAO.php';

//
// Controller Factory
//
class Controller {

	public static $controller_dir = __DIR__ . '/';
	
	// Database access
	public $dao;
	
	public function __construct() {
		// DAO instance
		$this->dao = new DAO( Config::DBTYPE,Config::DBHOST, Config::DBPORT, Config::DBNAME, Config::DBUSER, Config::DBPASSWD);
	}
	
	public static function factory( $model_name, $model) {
		// "order" => "Order" => "OrderController"
		$class_name = ucwords( $model_name) . 'Controller';
		// "OrderController.php"
		$class_filename = self::$controller_dir . $class_name . '.php';
		if ( file_exists( $class_filename)) {
			// Load "controllers/OrderController.php"
			require $class_filename;
			$ctrl = new $class_name( $model);
			return $ctrl;
		} else {
			throw new InvalidArgumentException( "Class File $class_filename not found !");
		}
	}
	
	// 
	// CRUD : Create, Read, Update, Delete, …
	// 
	// Create
	// @Override
	public function create( $method = INPUT_POST) {
		// To be define
	}
	
	// Read
	// @Override
	public function read( $method = INPUT_POST) {
		// To be define
	}
	
	// Update
	// @Override
	public function update( $method = INPUT_POST) {
		
	}
	
	// Delete
	// @Override
	public function delete( $method = INPUT_POST) {
		
	}
	
	// Others…
}
?>
