<?php
namespace mvcCore\Etc;

class Config {
	
	// Debug mode
	const  DEBUG = false;
	
	// Verbose mode
	const  VERBOSE = false;

	// XHTML flag
	const XHTML = true;
	
	// Default model
	const MODEL = 'order';
	// Default action
	const ACTION = 'create';
	
	// Database parameters
	const DBTYPE = 'pgsql';
	const DBHOST = 'localhost';
	const DBPORT = 5433; // 5432 sur linserv-info-03
	
	const DBNAME = 'car-workshop';
	const DBUSER = 'jmbruneau';
	const DBPASSWD = '<jmb!5433>';
	
	// Form data defintion
	static $REQUIRED = 'required=“required”';
	static $SELECTED = 'selected="selected"';
	static $CHECKED = 'checked=“checked”';
	
	static function init() {
		if ( ! self::XHTML) self::$REQUIRED = 'required';
		if ( ! self::XHTML) self::$SELECTED = 'selected';
		if ( ! self::XHTML) self::$CHECKED = 'checked';
	}

}

// Init call
Config::init();
?>
