<?php

class DbConnection
{

	private static $instance = null;

	private function __construct()
	{
		$dsn = "mysql:dbname=" . DB_CHOSEN . ";host=127.0.0.1";
//		self::$instance = new mysqli("localhost", DB_USER, DB_PASSWORD, DB_CHOSEN);
		self::$instance = new PDO($dsn, DB_USER, DB_PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
	}

	/**
	 *
	 * @return PDO
	 */
	public static function getInstance()
	{
		if (!isset(self::$instance)) new DbConnection();

		return self::$instance;
	}

}

?>