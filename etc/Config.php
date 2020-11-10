<?php

class Config {
	
	// Debug Mode
	const DEBUG = true;
	
	// Verbose mode
	const  VERBOSE = false;
	
	// Database connection
	const DBTYPE = 'pgsql';
	const DBHOST = 'localhost';
	const DBPORT = 5433; // 5432 sur linserv-info-03
	const DBNAME = 'car-workshop';
	const DBUSER = 'jmbruneau';
	const DBPASSWD = '<jmb!5433>';
	
}
?>
