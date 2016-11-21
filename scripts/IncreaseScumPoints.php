<?php
	/*
	*	The purpose of this command is to increase the total scum points of each user by 1 when executed. It should be run on a daily cron job.
	*/
	// Load all Composer Dependencies
	require_once __DIR__ . '../../vendor/autoload.php';

	// Get some database access up in here
	require_once __DIR__ . '../../conf/db.php';

	// Load ENV variables from .env
	$dotenv = new Dotenv\Dotenv(__DIR__.'/..');
	$dotenv->load();

	// We're gonna need a database connection - MySQLi time
	$db = connect_to_db();																// (hint - this function is in conf/db.php)

	// Step #1 - Make sure the database connection is A+
	if ($db->connect_error) {
        throw new Exception ($db->connect_error);										// We should probably catch this... somewhere
    }

    // Step #2 - Set up and run a query to increment all points by one
    $sth = $db->prepare("UPDATE users SET scum_points = scum_points + 1");				// Build a query to increment points
    if ($sth === false) {throw new Exception ($dbh->error);}							// If somehing went REALLY wrong

    $sth->execute();																	// Run the query
    if ($sth === false) {throw new Exception ("bind (execute ".__LINE__." failed\n");}	// If something ELSE went really wrong

    $db->close();																		// Be polite

    echo "Scum points successfuly incremented!\n";										// Show some output to be nice
?>