<?php
namespace mvcCore\Models;

/*
 * @author : Jean-Michel Bruneau
 * @version : 1.0
 */

// Model Factory
abstract class Model {
	
	// @Id
	protected ?string $id = null;

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
		// Class name with namespace
		$class = '\\' . __NAMESPACE__ . '\\' . $class_name;
		if ( class_exists( $class)) {
			$object = new $class();
			return $object;
		} else {
			throw new \InvalidArgumentException( "Class $class not found !");
		}
	}
	
	// Get properties
	public function getProperties( $empty = true, $default = true) {
		$properties = get_object_vars( $this);
		if ( $empty) { // Remove empty values
			foreach ( $properties as $key => $value) {
				if ( empty( $value)) unset( $properties[$key]);
			}
		}
		if ( $default) { // Remove properties with a default value
			unset( $properties['id']);
		}
		return $properties;
	}
	
	// Get all properties names
	public function getPropertiesNames( $default = true) {
		$properties_names = array_keys( get_object_vars( $this));
		if ( $default) { // Remove properties names with a default value
			unset( $properties_names['id']);
		}
		return $properties_names;
	}

	/**
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param mixed $id
	 */
	public function setId( $id) {
		$this->id = $id;
	}


}

?>
