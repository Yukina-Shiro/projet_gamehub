<?php
namespace mvcCore\Dao;

/*
 * @author : Jean-Michel Bruneau
 * @version : 1.0
 * 
 * Data Object Access
 * 
 */

class DAO {
	
	const DEBUG = false;
	
	private static $dbtype = '';
	
	private static $dbhost = '';
	private static $dbport = '';
	private static $dbname = '';
	
	private static $dbuser = '';
	private static $dbpasswd = '';
	
	// Data source name
	private static $dsn = '';
	
	// PDO
	public static $pdo = null;

	public function __construct( $dbtype, $dbhost, $dbport, $dbname, $dbuser, $dbpasswd) {
		self::$dbtype = $dbtype;
		self::$dbhost = $dbhost;
		self::$dbport = $dbport;
		
		self::$dbname = $dbname;
		self::$dbuser = $dbuser;
		self::$dbpasswd = $dbpasswd;
		// Set DSN
		self::$dsn = self::$dbtype . ":host=" . self::$dbhost . ";port=" . self::$dbport . ";dbname=" . self::$dbname;
	}
	
	// Database connection
	private function pdo() {
		if ( self::$pdo == null) {
			try {
				self::$pdo = new \PDO(self::$dsn, self::$dbuser, self::$dbpasswd, array( \PDO::ATTR_PERSISTENT => true));
			} catch (\PDOException $e) {
				print "Database commection error : " . $e->getMessage();
				die();
			}
		}
	}
	
	// Create ( i.e. Insert)
	// @param $table : table name
	// @param $data - array( key1 => value1, key2 => value2, …)
	public function persist( $table, $data) {
		
		// Connection
		$this->pdo();
		
		// Get data keys :
		// id, lastname, firstname, email, …, return_price, total_price
		$keys = implode(", ", array_keys( $data));
		// Get value names :
		// :id, :lastname, :firtsname, :email, …, :return_price, :total_price
		$values = implode(", :", array_keys( $data));
		
		// SQL query : INSERT INTO orders ( lastname, firstname, …) VALUES ( :lastname, :firstname, …);
		$sql = <<< _EOS_
INSERT INTO $table
	( $keys ) VALUES ( $values)
_EOS_;
		
		self::$pdo->prepare( $sql)->execute( $data);
		
	}
	
	//
	
	// Close database connection
	public function __destruct() {
		self::$pdo = null;
	}
	
}

?>
