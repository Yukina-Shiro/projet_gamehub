<?php
namespace mvcCore\Models;

/*
 * @author : Jean-Michel Bruneau
 * @version : 1.0
 */

// Model Factory
abstract class Model {
	
	public static $model_dir = __DIR__ . '/';
	
	// @Id
	private $id = null;
	
	// Constructor
	public function __construct( $data = null) {
		if ( ! is_null( $data)) {
			$properties = get_object_vars( $this);
			foreach ( $properties as $property => $value) {
				if ( isset( $data[$property]) && ! is_null( $value)) {
					$this->$property = $data[$property];
				}
			}
		}
	}

	// Factory
	public static function factory( $name) {
		// "order" -> "Order" => "OrderModel"
		$class_name =  ucwords( $name) . 'Model';
		// Path to "OrderModel.php"
		$class_filename = self::$model_dir . $class_name . '.php';
		if ( file_exists( $class_filename)) {
			// Class name with namespace
			$class = '\\' . __NAMESPACE__ . '\\' . $class_name;
			$model = new $class();
			return $model;
		} else {
			throw new \InvalidArgumentException( "Class File $class_filename not found !");
		}
	}

}

?>
