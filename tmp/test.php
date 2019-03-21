<?php

class Test {
	
	const DEBUG = false;
	
	const DBTABLE = 'orders';

	public static $view_dir = __DIR__ . "/";
	
	public static $template_dir = __DIR__ . "/templates/";
	
	// Id
	private $id = null;
	
	// Forms fields
	private $lastname = null;
	private $firstname = null;
	private $email = null;
	
	private $brend = null;
	private $model = null;
	private $gearbox = null;
	private $color = null;
	private $options = array();

	private $return_price = null;
	
	private $total_price = null;
	
	private $date = null;

	public function __construct( $data = null) {
		
		if ( ! is_null( data)) {
			$properties = get_object_vars( $this);
			
			foreach ( $properties as $propertie => $value) {
				if ( isset( $data[$propertie])) {
					$this->$propertie = $data[$propertie];
				}
			}
		}
	}

	// Get properties
	public function getProperties() {
		return get_object_vars( $this);
	}
	
	public function getPropertiesNames() {
		return array_keys( get_object_vars( $this));
	}
}

class ExtendTest extends Test {
	public $public1 = null;
	public $public2 = null;
	public $public3 = null;
	public $public4 = null;
}

$data = array(
	'lastname' => 'BRUNEAU',
	'email' => 'jean-michel@netspace.fr',
	'public1' => 'p1'
	);

$test = new Test( $data);
var_dump( $test::$view_dir, $test::$template_dir);
var_dump( $test->getProperties());
var_dump( $test->getPropertiesNames());

$extendTest = new ExtendTest( $data);
var_dump( $extendTest::$view_dir, $extendTest::$template_dir);
var_dump( $extendTest->getProperties());
var_dump( $extendTest->getPropertiesNames());

?>
