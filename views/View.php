<?php
namespace mvcCore\Views;
//
// View Factory
//

abstract class View {
	
	// Model object(s)
	protected $_model = null;
	
	// Template
	protected $_tpl_filename = null;

	// Data array()
	protected $_data = null;
	
	// Template base directory
	public static $tpl_dir = __DIR__ . '/templates/';
	
	// template suffix
	public static $tpl_filename_suffix = '.tpl.php';
	
	// Constructor
	public function __construct( $model, $template) {
		// Set Model
		$this->_model = $model;
		// Set Template
		$this->_tpl_filename = $template;
		// Set view specific's properties
		$this->setProperties();
	}
	
	// Factory
	public static function factory( $model, $action) {
		$model_name = $model->getModelName();
		// "order" -> "Order", "create" -> "Create" => "OrderCreateView"
		$class_prefix =  ucwords( $model_name) . ucwords( $action);
		$class_name = $class_prefix . "View";
		// Class name with namespace
		$class = '\\' . __NAMESPACE__ . '\\' . $class_name;
		if ( class_exists( $class)) {
			// Set template file name
			$tpl_filename = self::$tpl_dir . $class_prefix . self::$tpl_filename_suffix;
			// View instance
			$object = new $class( $model, $tpl_filename);
			// Object return
			return $object;
		} else {
			throw new \InvalidArgumentException( "Class $class not found !");
		}
	}
	
	// Set properties
	abstract public function setProperties();

	// Get Properties
	public function getProperties( $abstract = null, $null = true) {
		$properties = get_object_vars( $this);
		// Remove the abstract view entries
		if ( $abstract)
			unset( $properties['_model'], $properties['_data'], $properties['_tpl_filename']);
			// Remove null values
			if ( $null)
				foreach ( $properties as $key => $value)
					if ( is_null( $value)) unset( $properties[$key]);
		return $properties;
	}
	
	// Display template content
	public function display() {
		echo self::fetch();
	}
	
	// Fetch template
	public function fetch() {
		// Put model data into $this->_data array()
		$this->_data = $this->_model->getProperties( false, false);
		// Add data view to $this->data
		$this->_data += $this->getProperties( false, false);
		// Turn on output buffering
		ob_start();
		// Form action
		$model = $GLOBALS['request']['model'];
		$action = $GLOBALS['request']['action'];
		// Define $data[]
		$data = $this->_data;
		// Load the template
		require $this->_tpl_filename;
		// Return the template content
		return ob_get_clean();
	}
}

