<?php

require_once("/dispatcher.php");

class MySqlConnect {

	// avustava metodi joka suorittaa sql:t ilman typerää kopiointia
	public static function invokeQuery($sql, $parameters = null) {
		$connection = self::getConnection();

		if($parameters == null) {
			$parameters = array();
		}

		$statement = $connection->prepare($sql);
		$statement->execute($parameters);

		if(!preg_match("/INSERT|insert|UPDATE|update/",$sql)) {
			return $statement->fetchAll();
		}
	}

	// yhdistää kantaan (covacoodatut arwot)
	public static function getConnection() {
		$connection = null;

		$config = ConfigHelper::getConfig("/config/config.json");

		$db_host = $config->database->host;
		$db_name = $config->database->name;
		$db_user = $config->database->user;
		$db_password = $config->database->password;

		try {
			$connection = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
		} catch (PDOException $e) {
			die("VIRHE: " . $e->getMessage());
		}

		$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$connection->exec("SET NAMES UTF8");
		$connection->query('SET CHARACTER SET utf8');

		return $connection;
	}
}