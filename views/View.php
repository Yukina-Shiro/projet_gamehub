<?php
namespace mvcCore\Views;
//
// View Factory
//

abstract class View {
	
	// Data array()
	protected $data = null;
		
	public static $tpl_dir = __DIR__ . '/templates/';
	
	protected $tpl_filename = null;
	
	public static $tpl_filename_suffix = '.tpl.php';
	
	// Constructor
	public function __construct( $data, $tpl_filename) {
		$this->data = $data;
		$this->tpl_filename = $tpl_filename;
	}
	
	// Factory
	public static function factory( $model_name, $action, $data) {
		// "order" -> "Order", "create" -> "Create" => "OrderCreateView"
		$class_prefix =  ucwords( $model_name) . ucwords( $action);
		$class_name = $class_prefix . "View";
		// Class name with namespace
		$class = '\\' . __NAMESPACE__ . '\\' . $class_name;
		if ( class_exists( $class)) {
			// Set template file name
			$tpl_filename = self::$tpl_dir . $class_prefix . self::$tpl_filename_suffix;
			// View instance
			$object = new $class( $data, $tpl_filename);
			// Object return
			return $object;
		} else {
			throw new \InvalidArgumentException( "Class $class not found !");
		}
	}
	
	// Display template content
	public function display() {
		echo self::fetch();
	}
	
	// Fetch template
	public function fetch() {
		// Turn on output buffering
		ob_start();
		// Define $data[]
		$data = $this->data;
		// Load the template
		require $this->tpl_filename;
		// Return the template content
		return ob_get_clean();
	}
}

?>
