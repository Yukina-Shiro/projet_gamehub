<?php

// Load config
require_once __DIR__ . "../etc/Config.php";

require_once __DIR__ . "View.php";

if ( Config::DEBUG) {
	error_reporting( E_ALL);
	ini_set( 'display_errors', '1');
}

class OrderCreateView extends View {

	// Constructor
	public function __construct($data, $tpl_filename) {
		parent::__construct($data, $tpl_filename);
	}
	
}

?>

