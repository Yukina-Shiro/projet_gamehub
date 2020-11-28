<?php
namespace mvcCore\Models;

/*
 * @author : Jean-Michel Bruneau
 * @version : 1.0
 */

// Order model
class OrderModel extends Model {
	// Debug mode
	public const DEBUG = false;
	
	// Model name
	public static $_model_name = 'order';
	
	// SQL table name
	public static $_model_table = 'public.orders';
	
	/**
	 * Orders model properties
	 */
	
	// Forms fields
	protected $lastname = null;
	protected $firstname = null;
	protected $email = null;
	
	protected $brend = null;
	protected $model = null;
	protected $gearbox = null;
	protected $color = null;
	
	// JSON of a PHP array()
	protected $options = null;

	protected $return_price = null;
	protected $total_price = null;
	
	// @Column( default='now()')
	protected  $date = null;
	
	// Get all properties
	public function getProperties( $empty = true, $default = true) {
		// Get all properties
		$properties =  parent::getProperties( $empty, $default);
		// Unset modelName and modelTable property
		unset( $properties['_model_name'], $properties['_model_table'],);
		if ( $default) { // Remove properties  with a default value
			unset( $properties['date']);
		}
		return $properties;
	}

	// Get all properties names
	// @ Override
	public function getPropertiesNames( $default = true) {
		// Get all properties names
		$properties_names = parent::getPropertiesNames( $default);
		if ( $default) { // Remove properties names with a default value
			unset( $properties_names['date']);
		}
		return $properties_names;
	}

	
	/**
	 * @return string
	 */
	public static function getModelName() {
		return OrderModel::$_model_name;
	}

	/**
	 * @param string $_model_name
	 */
	public static function setModelName( $_model_name) {
		OrderModel::$_model_name = $_model_name;
	}

	/**
	 * @return string
	 */
	public static function getModelTable() {
		return OrderModel::$_model_table;
	}

	/**
	 * @param string $_model_table
	 */
	public static function setModelTable( $_model_table) {
		OrderModel::$_model_table = $_model_table;
	}

	/**
	 * @return mixed
	 */
	public function getLastname() {
		return $this->lastname;
	}
	
	/**
	 * @param mixed $lastname
	 */
	public function setLastname( $lastname) {
		$this->lastname = $lastname;
	}
	
	/**
	 * @return mixed
	 */
	public function getFirstname() {
		return $this->firstname;
	}
	
	/**
	 * @param mixed $firstname
	 */
	public function setFirstname( $firstname) {
		$this->firstname = $firstname;
	}
	
	/**
	 * @return mixed
	 */
	public function getEmail() {
		return $this->email;
	}
	
	/**
	 * @param mixed $email
	 */
	public function setEmail( $email) {
		$this->email = $email;
	}
	
	/**
	 * @return mixed
	 */
	public function getBrend() {
		return $this->brend;
	}
	/**
	 * @param mixed $brend
	 */
	public function setBrend( $brend) {
		$this->brend = $brend;
	}
	
	/**
	 * @return mixed
	 */
	public function getModel() {
		return $this->model;
	}
	
	/**
	 * @param mixed $model
	 */
	public function setModel( $model) {
		$this->model = $model;
	}
	
	/**
	 * @return mixed
	 */
	public function getGearbox() {
		return $this->gearbox;
	}
	
	/**
	 * @param mixed $gearbox
	 */
	public function setGearbox( $gearbox) {
		$this->gearbox = $gearbox;
	}
	
	/**
	 * @return mixed
	 */
	public function getColor() {
		return $this->color;
	}
	
	/**
	 * @param mixed $color
	 */
	public function setColor( $color) {
		$this->color = $color;
	}
	/**
	 * @return multitype:
	 */
	public function getOptions() {
		return json_decode( $this->options);
	}
	
	/**
	 * @param multitype: $options
	 */
	public function setOptions( $options) {
		$this->options = json_encode( $options);
	}
	
	/**
	 * @return mixed
	 */
	public function getReturnPrice() {
		return $this->return_price;
	}
	
	/**
	 * @param mixed $return_price
	 */
	public function setReturnPrice( $return_price) {
		$this->return_price = $return_price;
	}
	
	/**
	 * @return mixed
	 */
	public function getTotalPrice() {
		return $this->total_price;
	}
	/**
	 * @param mixed $total_price
	 */
	public function setTotalPrice( $total_price) {
		$this->total_price = $total_price;
	}
	
	/**
	 * @return mixed
	 */
	public function getDate() {
		return $this->date;
	}
	
	/**
	 * @param mixed $date
	 */
	public function setDate( $date) {
		$this->date = $date;
	}
	
}

?>
