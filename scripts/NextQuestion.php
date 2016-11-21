<?php
	/*
	*	The purpose of this command is to switch up the current question of the day to the next one!
	*/
	// Load all Composer Dependencies
	require_once __DIR__ . '../../vendor/autoload.php';

	// Get some database access up in here
	require_once __DIR__ . '../../conf/db.php';

	// Load ENV variables from .env
	$dotenv = new Dotenv\Dotenv(__DIR__.'/..');
	$dotenv->load();

	// We're gonna need a database connection - MySQLi time
	$db = connect_to_db();																		// (hint - this function is in conf/db.php)

	// Step #1 - Make sure the database connection is A+
	if ($db->connect_error) {
        throw new Exception ($db->connect_error);												// We should probably catch this... somewhere
    }

    // Step #2 - Get the current ID
    $id = $db->query("SELECT id FROM questions WHERE active = 1 AND verified = 1 LIMIT 1");		// Build a query to get the current question ID
    if ($id === false) {throw new Exception ($db->error);}										// If somehing went wrong
    $id = $id->fetch_object()->id;

    // Step #3 - Remove active status of current question
    $db->query("UPDATE questions SET active = 0 WHERE id = ".$id);								// Remove active status of current question
    
    $db->close();																				// Be polite

    echo "Question successfully rotated!\n";													// Show some output to be nice
?>