<?php
/*
 * @author : Jean-Michel Bruneau
 * @version : 1.0
 */

// Load local database
require_once __DIR__ . '/../data/Cars.php';

class OrderController extends Controller {
	
	const DEBUG = false;
	
	// Model name
	public static $name = 'order';
		
	// Orders Model
	protected $model;
	
	// View specifics fields
	protected $checked_gearboxes = array ();
	protected $checked_colors = array ();
	protected $checked_options = array ();
	
	protected $model_price = 0;
	protected $gearbox_price = 0;
	protected $color_price = 0;
	protected $options_price = 0;
	
	public function __construct( $model) {
		$this->model = $model;
		parent::__construct();
	}
	
	// Create new order
	public function create( $method = INPUT_POST) {
		// Set Lastname, Firstname and Email
		$this->model->setLastname( filter_input( $method, 'lastname', FILTER_SANITIZE_STRING));
		$this->model->setFirstname(filter_input( $method, 'firstname', FILTER_SANITIZE_STRING));
		$this->model->setEmail( filter_input( $method, 'email', FILTER_SANITIZE_EMAIL));
		
		// Set Brend and Model
		$this->model->setBrend( filter_input( $method, 'brend', FILTER_SANITIZE_STRING));
		$this->model->setModel( filter_input( $method, 'model', FILTER_SANITIZE_STRING));
		
		if ( isset( Cars::$brends[$this->model->getBrend()][$this->model->getModel()])) {
			$this->model_price = Cars::$brends[$this->model->getBrend()][$this->model->getModel()];
		}
		// Set total price to  model price
		$total_price = $this->model_price;
		
		// Selected gearbox (Radio button)
		$this->model->setGearbox( filter_input( $method, 'gearbox', FILTER_SANITIZE_STRING));
		if ( ! is_null( $this->model->getGearbox())) {
			$this->gearbox_price = Cars::$gearboxes[$this->model->getGearbox()]['price'];
		}
		// Add gearbox price to total price
		$total_price += $this->gearbox_price;
		
		// Checked gearbox
		foreach ( Cars::$gearboxes as $key => $value) {
			if ( $key != $this->model->getGearbox())
				$this->checked_gearboxes[$key] = '';
			else
				$this->checked_gearboxes[$key] = 'checked="checked"';
		}
		
		// Selected color (Radio button)
		$this->color = filter_input( INPUT_POST, 'color', FILTER_SANITIZE_STRING);
		if ( ! is_null( $this->color)) {
			$this->color_price = Cars::$colors[$this->color]['price'];
		}
		// Add color price
		$total_price += $this->color_price;
		
		// Checked color
		foreach ( Cars::$colors as $key => $value) {
			if ( $key != $this->color)
				$this->checked_colors[$key] = '';
			else
				$this->checked_colors[$key] = 'checked="checked"';
		}

		// Selected options (Checkbox)
		$this->options = filter_input( $method, 'options', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
		foreach ( Cars::$options as $key => $value) {
			if ( isset( $this->options[$key])) {
				$this->checked_options[$key] = 'checked="checked"';
				$this->options_price += Cars::$options[$key]['price'];
			} else {
				$this->checked_options[$key] = '';
			}
		}
		// Add options price
		$total_price += $this->options_price;
		
		// Set the Return price
		$this->model->setReturnPrice( filter_input( INPUT_POST, 'return_price', FILTER_SANITIZE_NUMBER_INT));
		if ( is_numeric( $this->model->getReturnPrice())) {
			$total_price -= $this->model->getReturnPrice();
		}

		// Set model price
		$this->model->setTotalPrice( $total_price);
		
		// Get all the controler properties
		$data = $this->getProperties();
		
		// View instance
		$view = View::factory( self::$name, __FUNCTION__, $data);
		
		// Display view
		$view->display();
	}
	
	// Get all the view properties
	public function getProperties() {
		// View properties
		$properties = get_object_vars( $this);
		// Unset the DAO and the Model object
		unset( $properties['dao'], $properties['model']);
		// Merge with Model properties
		if ( Config::VERBOSE) var_dump( $properties, $this->model->getProperties());
		return array_merge( $properties, $this->model->getProperties());
	}
	
}

?>

