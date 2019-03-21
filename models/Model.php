<?php
/*
 * @author : Jean-Michel Bruneau
 * @version : 1.0
 */

// Model Factory
class Model {
	
	public static $model_dir = __DIR__;
	
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
		// "OrderCreateView.php"
		$class_filename = self::$model_dir . $class_name . ".php";
		if ( file_exists( $class_filename)) {
			// charger le fichier "views/OrderCreateView.php"
			include $class_filename;
			$model = new $class_name();
			return $model;
		} else {
			throw new InvalidArgumentException( "Class File $class_filename not found !");
		}
	}
	
	// Get all properties
	public function getProperties( $null = true) {
		$properties = get_object_vars( $this);
		if ( $null) { // Remove null values
			foreach ( $properties as $key => $value) {
				if ( is_null( $value)) unset( $properties[$key]);
			}
		}
		return $properties;
	}
	
	// Get all properties names
	public function getPropertiesNames( $default = true) {
		$properties_names = array_keys( get_object_vars( $this));
		if ( $default) { // Remove properties names with a default value
			$properties_names = array_diff( $properties_names, array( 'id'));
		}
		return $properties_names;
	}
	
}

?>
