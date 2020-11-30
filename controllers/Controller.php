<?php
namespace mvcCore\Controllers;

use mvcCore\Etc\Config;
use mvcCore\Dao\DAO;

//
// Controller Factory
//
abstract class Controller {
	
	// Database access
	protected $__dao;
	
	public function __construct() {
		// DAO service instance
		$this->__dao = new DAO( Config::DBTYPE,Config::DBHOST, Config::DBPORT, Config::DBNAME, Config::DBUSER, Config::DBPASSWD);
	}
	
	public static function factory( $model) {
		// "order" => "Order" => "OrderController"
		$class_name = ucwords( $model->getModelName()) . 'Controller';
		// Class name with namespace
		$class = '\\' . __NAMESPACE__ . '\\' . $class_name;
		if ( class_exists( $class)) {
			$object = new $class( $model);
			return $object;
		} else {
			throw new \InvalidArgumentException( "Class $class not found !");
		}
	}
	
	/**
	 * CRUD : Create, Read, Update, Delete, …
	 */
	
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
