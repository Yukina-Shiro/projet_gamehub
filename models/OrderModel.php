<?php
/*
 * @author : Jean-Michel Bruneau
 * @version : 1.0
 */

require_once __DIR__ . "Model.php";

// Order model
class OrderModel extends Model {
	
	const DEBUG = false;
	
	public $table = 'orders';
	
	// Forms fields
	private $lastname = null;
	private $firstname = null;
	private $email = null;
	
	private $brend = null;
	private $model = null;
	private $gearbox = null;
	private $color = null;
	// JSON of an array()
	private $options = null;

	private $return_price = null;
	
	private $total_price = null;
	
	// @Column( default='now()')
	private  $date = null;
	
	/**
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return mixed
	 */
	public function getLastname() {
		return $this->lastname;
	}

	/**
	 * @return mixed
	 */
	public function getFirstname() {
		return $this->firstname;
	}

	/**
	 * @return mixed
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * @return mixed
	 */
	public function getBrend() {
		return $this->brend;
	}

	/**
	 * @return mixed
	 */
	public function getModel() {
		return $this->model;
	}

	/**
	 * @return mixed
	 */
	public function getGearbox() {
		return $this->gearbox;
	}

	/**
	 * @return mixed
	 */
	public function getColor() {
		return $this->color;
	}

	/**
	 * @return multitype:
	 */
	public function getOptions() {
		return json_decode( $this->options);
	}

	/**
	 * @return mixed
	 */
	public function getReturnPrice() {
		return $this->return_price;
	}

	/**
	 * @return mixed
	 */
	public function getTotalPrice() {
		return $this->total_price;
	}

	/**
	 * @return mixed
	 */
	public function getDate() {
		return $this->date;
	}

	/**
	 * @param mixed $id
	 */
	public function setId( $id) {
		$this->id = $id;
	}

	/**
	 * @param mixed $lastname
	 */
	public function setLastname( $lastname) {
		$this->lastname = $lastname;
	}

	/**
	 * @param mixed $firstname
	 */
	public function setFirstname( $firstname) {
		$this->firstname = $firstname;
	}

	/**
	 * @param mixed $email
	 */
	public function setEmail( $email) {
		$this->email = $email;
	}

	/**
	 * @param mixed $brend
	 */
	public function setBrend( $brend) {
		$this->brend = $brend;
	}

	/**
	 * @param mixed $model
	 */
	public function setModel( $model) {
		$this->model = $model;
	}

	/**
	 * @param mixed $gearbox
	 */
	public function setGearbox( $gearbox) {
		$this->gearbox = $gearbox;
	}

	/**
	 * @param mixed $color
	 */
	public function setColor( $color) {
		$this->color = $color;
	}

	/**
	 * @param multitype: $options
	 */
	public function setOptions( $options) {
		$this->options = json_encode( $options);
	}

	/**
	 * @param mixed $return_price
	 */
	public function setReturnPrice( $return_price) {
		$this->return_price = $return_price;
	}

	/**
	 * @param mixed $total_price
	 */
	public function setTotalPrice( $total_price) {
		$this->total_price = $total_price;
	}

	/**
	 * @param mixed $date
	 */
	public function setDate( $date) {
		$this->date = $date;
	}

	// Constructor
	// @ Override
	public function __construct( $data = null) {
		parent::__construct( $data);
		if ( ! is_null( $this->options))
			$this->options = json_encode( $this->options);
	}
	
	// Get all properties names
	// @ Override
	public function getPropertiesNames( $default = true) {
		$properties_names = parent::getPropertiesNames( $default);
		if ( $default) { // Remove properties names with a default value
			$properties_names = array_diff( $properties_names, array( 'date'));
		}
		return $properties_names;
	}
	
}

?>
