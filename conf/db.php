<?php
	/*
	*
	* This file contains connection and database information information for the project.
	* Including this configuration file in a controller or page will allow access to the database
	* by using the connection function
	*
	*/
	// Include all Composer dependencies
	require_once __DIR__ . '/../vendor/autoload.php';

	// Load ENV variables from .env
	$dotenv = new Dotenv\Dotenv(__DIR__.'/..');
	$dotenv->load();

	/************************************************************
	*					CONNECT TO DATABASE 					*
	*************************************************************
	*	Calling this function will return a MySQLi object that 	*
	*	is connected to the project database. Connection info 	*
	*	is loaded from the .env file in the root directory		*
	************************************************************/
	function connect_to_db() {
		$servername = getenv("DB_HOST");
        $username = getenv("DB_USER");
        $password = getenv("DB_PASS");
        $database = getenv("DB_NAME");
        $dbport = getenv("DB_PORT");
        $dbh = new mysqli($servername, $username, $password, $database, $dbport);
        return $dbh;
	}
?>