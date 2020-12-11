<?php

namespace mvcCore\Api\Rest\Controllers;


namespace mvcCore\Api\Rest\Controllers;


use mvcCore\Data\Cars;

/*
 * @author : Jean-Michel Bruneau
 * @version : 1.0.0
 * @date : 2020-05-03
 *
 * Rest API Order Controller class
 */

class OrderController extends Controller {

	// Debug mode
	const DEBUG = false;
	
	// Compute total price
	private function _total_price() {
		$total_price = 0;
		if ( isset( \Cars::$brands[$this->model->getBrand()][$this->model->getModel()])) {
			$total_price = \Cars::$brands[$this->model->getBrand()][$this->model->getModel()];
		}
		// Selected gearbox (Radio button)
		if ( ! is_null( $this->model->getGearbox())) {
			$total_price += \Cars::$gearboxes[$this->model->getGearbox()]['price'];
		}
		// Selected color (Radio button)
		if ( ! is_null( $this->model->getColor())) {
			$total_price += \Cars::$colors[$this->model->getColor()]['price'];
		}
		// Selected options (Checkbox)
		foreach ( \Cars::$options as $key => $value) {
			if ( isset( $this->model->getOptions()[$key]))
				$total_price += \Cars::$options[$key]['price'];
		}
		// Return price
		if ( is_numeric( $this->model->getReturnPrice())) {
			$total_price -= $this->model->getReturnPrice();
		}
		// Set model price
		$this->model->setTotalPrice( $total_price);
	}
	
	//
	// Get inputs and set model properties
	// @Override
	public function input() {
		// JSON input for POST and PUT methods
		$_POST = array();
		if ( ( $_SERVER['REQUEST_METHOD'] == 'POST') || ( $_SERVER['REQUEST_METHOD'] == 'PUT')) {
			$_POST = json_decode( file_get_contents( 'php://input'), true);
		}
		if ( \Config::DEBUG) var_dump( $_POST);
		// Set model properties from POST data
		if ( count( $_POST) > 0) {
			// Get and set :
			// Lastname, Firstname and Email
			$this->model->setLastname( filter_var( @$_POST['lastname'], FILTER_SANITIZE_STRING));
			$this->model->setFirstname(filter_var( @$_POST['firstname'], FILTER_SANITIZE_STRING));
			$this->model->setEmail( filter_var( @$_POST['email'], FILTER_SANITIZE_EMAIL));
			// Brand and Model
			$this->model->setBrand( filter_var( @$_POST['brand'], FILTER_SANITIZE_STRING));
			$this->model->setModel( filter_var( @$_POST['model'], FILTER_SANITIZE_STRING));
			// Selected gearbox (Radio button)
			$this->model->setGearbox( filter_var( @$_POST['gearbox'], FILTER_SANITIZE_STRING));
			// Selected color (Radio button)
			$this->model->setColor( filter_var( @$_POST['color'], FILTER_SANITIZE_STRING));
			// Selected options (Checkbox) : already in JSON so no json encoding needed
			$this->model->setOptions( filter_var( @$_POST['options'], FILTER_DEFAULT), false);
			// Return price
			$this->model->setReturnPrice( filter_var( @$_POST['return_price'], FILTER_SANITIZE_NUMBER_INT));
			// Total price
			$this->model->setTotalPrice( filter_var( @$_POST['total_price'], FILTER_SANITIZE_NUMBER_INT));
			if ( \Config::DEBUG) var_dump( $this->model);
		}
		// Compute total price
		$this->_total_price();
	}
	
}
?>
