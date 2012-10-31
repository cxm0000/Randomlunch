<?php


class DbConnection {
	
	private static $instance = null;
	
	private function __construct(){
		
		self::$instance = new mysqli("localhost", DB_USER, DB_PASSWORD, DB_CHOSEN);
		
	}
	
	 public static function getInstance(){
		
		if (!isset(self::$instance))
			new DbConnection();
		
		return self::$instance;
		
	}

}








?>