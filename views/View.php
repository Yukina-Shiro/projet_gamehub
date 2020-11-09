<?php
//
// View Factory
// 
class View {
	
	protected $data = null;
	
	public static $view_dir = __DIR__ . '/';
	
	public static $tpl_dir = __DIR__ . '/templates/';
	
	protected $tpl_filename = null;
	
	// Constructor
	public function __construct( $data, $tpl_filename) {
		$this->data = $data;
		$this->tpl_filename = $tpl_filename;
	}
	
	// Factory
	public static function factory( $model, $action, $data) {
		// "order" -> "Order", "create" -> "Create" => "OrderCreateView"
		$class_prefix =  ucwords( $model) . ucwords( $action);
		$class_name = $class_prefix . "View";
		// "OrderCreateView.php"
		$class_filename = self::$view_dir . $class_name . ".php";
		if ( file_exists( $class_filename)) {
			// Load "views/OrderCreateView.php"
			include $class_filename;
			// Set template file name
			$tpl_filename = self::$tpl_dir . $class_prefix . "Tpl.php";
			// View instance
			$view = new $class_name( $data, $tpl_filename);
			// Object return
			return $view;
		} else {
			throw new InvalidArgumentException( "Class File $class_filename not found !");
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
		// Load the template
		require $this->tpl_filename;
		// Return the template content
		return ob_get_clean();
	}
}

?>
