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
	
	// Debug mode
	const DEBUG = false;
		
	public function __construct( $model) {
		$this->__model = $model;
		parent::__construct();
	}
	
	// Compute total price
	private function _total_price() {
		$total_price = 0;
		if ( isset( Cars::$brands[$this->__model->getBrand()][$this->__model->getModel()])) {
			$total_price = Cars::$brands[$this->__model->getBrand()][$this->__model->getModel()];
		}
		// Selected gearbox (Radio button)
		if ( ! is_null( $this->__model->getGearbox())) {
			$total_price += Cars::$gearboxes[$this->__model->getGearbox()]['price'];
		}
		// Selected color (Radio button)
		if ( ! is_null( $this->__model->getColor())) {
			$total_price += Cars::$colors[$this->__model->getColor()]['price'];
		}
		// Selected options (Checkbox)
		foreach ( Cars::$options as $key => $value) {
			if ( isset( $this->__model->getOptions()[$key]))
				$total_price += Cars::$options[$key]['price'];
		}
		// Return price
		if ( is_numeric( $this->__model->getReturnPrice())) {
			$total_price -= $this->__model->getReturnPrice();
		}
		// Set model price
		$this->__model->setTotalPrice( $total_price);
	}
	
	//
	// Get inputs and set model properties
	// @Override
	public function input() {
		// Only from POST data
		if ( count( $_POST) > 0) {
			// Get and set :
			// Lastname, Firstname and Email
			$this->__model->setLastname( filter_input( INPUT_POST, 'lastname', FILTER_SANITIZE_STRING));
			$this->__model->setFirstname(filter_input( INPUT_POST, 'firstname', FILTER_SANITIZE_STRING));
			$this->__model->setEmail( filter_input( INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
			// Brand and Model
			$this->__model->setBrand( filter_input( INPUT_POST, 'brand', FILTER_SANITIZE_STRING));
			$this->__model->setModel( filter_input( INPUT_POST, 'model', FILTER_SANITIZE_STRING));
			// Selected gearbox (Radio button)
			$this->__model->setGearbox( filter_input( INPUT_POST, 'gearbox', FILTER_SANITIZE_STRING));
			// Selected color (Radio button)
			$this->__model->setColor( filter_input( INPUT_POST, 'color', FILTER_SANITIZE_STRING));
			// Selected options (Checkbox)
			$this->__model->setOptions( filter_input( INPUT_POST, 'options', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY));
			// Return price
			$this->__model->setReturnPrice( filter_input( INPUT_POST, 'return_price', FILTER_SANITIZE_NUMBER_INT));
			// Total price
			$this->__model->setTotalPrice( filter_input( INPUT_POST, 'total_price', FILTER_SANITIZE_NUMBER_INT));
		}
		// Compute total price
		$this->_total_price();
	}
	
	//
	// Create new order
	// @Override
	public function create( $action = 'read') {
		// Put Input data into the model
		$this->input();
		
		// View instance ( model object, "create")
		$view = View::factory( $this->__model, __FUNCTION__);
		
		// Display the view
		$view->display();
	}
	
}

?>

