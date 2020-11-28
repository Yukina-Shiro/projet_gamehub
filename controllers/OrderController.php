<?php
namespace mvcCore\Controllers;

use mvcCore\Etc\Config;
use mvcCore\Data\Cars;
use mvcCore\Views\View;

/*
 * @author : Jean-Michel Bruneau
 * @version : 1.0
 */

class OrderController extends Controller {
	
	const DEBUG = false;
	
	// Orders Model object
	protected $__model;
	
	// View specifics fields
	protected $checked_gearboxes = array ();
	protected $checked_colors = array ();
	protected $checked_options = array ();
	
	protected $model_price = 0;
	protected $gearbox_price = 0;
	protected $color_price = 0;
	protected $options_price = 0;
	
	public function __construct( $model) {
		$this->__model = $model;
		parent::__construct();
	}
	
	// Create new order
	public function create( $method = INPUT_POST) {
		// Set Lastname, Firstname and Email
		$this->__model->setLastname( filter_input( $method, 'lastname', FILTER_SANITIZE_STRING));
		$this->__model->setFirstname(filter_input( $method, 'firstname', FILTER_SANITIZE_STRING));
		$this->__model->setEmail( filter_input( $method, 'email', FILTER_SANITIZE_EMAIL));
		
		// Set Brend and Model
		$this->__model->setBrend( filter_input( $method, 'brend', FILTER_SANITIZE_STRING));
		$this->__model->setModel( filter_input( $method, 'model', FILTER_SANITIZE_STRING));
		
		if ( isset( Cars::$brends[$this->__model->getBrend()][$this->__model->getModel()])) {
			$this->model_price = Cars::$brends[$this->__model->getBrend()][$this->__model->getModel()];
		}
		// Set total price to  model price
		$total_price = $this->model_price;
		
		// Selected gearbox (Radio button)
		$this->__model->setGearbox( filter_input( $method, 'gearbox', FILTER_SANITIZE_STRING));
		if ( ! is_null( $this->__model->getGearbox())) {
			$this->gearbox_price = Cars::$gearboxes[$this->__model->getGearbox()]['price'];
		}
		// Add gearbox price to total price
		$total_price += $this->gearbox_price;
		
		// Checked gearbox
		foreach ( Cars::$gearboxes as $key => $value) {
			if ( $key != $this->__model->getGearbox())
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
		$this->__model->setReturnPrice( filter_input( INPUT_POST, 'return_price', FILTER_SANITIZE_NUMBER_INT));
		if ( is_numeric( $this->__model->getReturnPrice())) {
			$total_price -= $this->__model->getReturnPrice();
		}

		// Set model price
		$this->__model->setTotalPrice( $total_price);
		
		// Get all the controler properties
		$data = $this->getProperties();
		
		// View instance
		$view = View::factory( $this->__model->getModelName(), __FUNCTION__, $data);
		
		// Display view
		$view->display();
	}
	
	// Get all the view properties
	public function getProperties() {
		// View properties
		$properties = get_object_vars( $this);
		// Unset the DAO and the Model object
		unset( $properties['__dao'], $properties['__model']);
		// Merge with Model properties
		if ( Config::VERBOSE) var_dump( $properties, $this->__model->getProperties());
		return array_merge( $properties, $this->__model->getProperties());
	}
	
}

?>

