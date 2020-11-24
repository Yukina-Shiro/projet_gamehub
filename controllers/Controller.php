<?php
namespace mvcCore\Controllers;

use mvcCore\Etc\Config;
use mvcCore\Dao\DAO;

//
// Controller Factory
//
abstract class Controller {

	public static $controller_dir = __DIR__ . '/';
	
	// Database access
	public $dao;
	
	public function __construct() {
		// DAO instance
		$this->dao = new DAO( Config::DBTYPE,Config::DBHOST, Config::DBPORT, Config::DBNAME, Config::DBUSER, Config::DBPASSWD);
	}
	
	public static function factory( string $model_name, $model) {
		// "order" => "Order" => "OrderController"
		$class_name = ucwords( $model_name) . 'Controller';
		// "OrderController.php"
		$class_filename = self::$controller_dir . $class_name . '.php';
		if ( file_exists( $class_filename)) {
			// Class name with namespace
			$class = '\\' . __NAMESPACE__ . '\\' . $class_name;
			$ctrl = new $class( $model);
			return $ctrl;
		} else {
			throw new \InvalidArgumentException( "Class File $class_filename not found !");
		}
	}
	
	// 
	// CRUD : Create, Read, Update, Delete, …
	// 
	// Create
	// @Override
	public abstract function create( $method = INPUT_POST);
	
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
