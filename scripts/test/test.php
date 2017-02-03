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

    $id = 24;

    $sql = "UPDATE questions SET verified = 1 WHERE id = ?";                    // SQL string to verify one question
    $qSql = "SELECT user FROM questions WHERE id = ?";                          // SQL string to get the submitter of one question
    $scumSql = "UPDATE users SET scum_points = scum_points + 3 WHERE id = ?";   // SQL string to increment scum points for a user

    

    

    


    /********************************************* Run query to verify question ********************************************/
    $qUpdate = $db->prepare($sql);                                              // Standard MySQLi prep stuff for update query
    if ($qUpdate === false) {throw new Exception ($db->error);}                 // If somehing went REALLY wrong
    $qUpdate->bind_param("i", $id);                                     // Add data to the query string (avoid SQL injects!)
    if ($qUpdate === false) {throw new Exception ("bind (line ".__LINE__." failed\n");} 
    $qUpdate->execute();
    if ($qUpdate === false) {throw new Exception ("bind (execute ".__LINE__." failed\n");}
    $qUpdate->close();


    /******************************************** Run query to see if user sub'd *******************************************/
    $qCheck = $db->prepare($qSql);                                              // Prepare sql check of user submitter-thing
    if ($qCheck === false) {throw new Exception ($db->error);}
    $qCheck->bind_param("i", $id);                                      // Add data to the user check sql query
    if ($qCheck === false) {throw new Exception ("bind (line ".__LINE__." failed\n");}  
    if (!$qCheck->bind_result($user)) {throw new Exception ("Bind Result failed: ".__LINE__);}
    $qCheck->execute();
    if ($qCheck === false) {throw new Exception ("bind (execute ".__LINE__." failed\n");}
    $qCheck->fetch();                                                   // Try to grab the user ID of submitted question
    $qCheck->close();


    /**************************************** Run query increment user's points by 3 ***************************************/
    if (!is_null($user)) {
        $sInc = $db->prepare($scumSql);                                             // Prepare query to increment scum points
        if ($sInc === false) {throw new Exception ($db->error);}
        $sInc->bind_param("i", $user);
        if ($sInc === false) {throw new Exception ("bind (line ".__LINE__." failed\n");}
        $sInc->execute();                                                                   // Run the query
        if ($sInc === false) {throw new Exception ("bind (execute ".__LINE__." failed\n");} // If something ELSE went really wrong
        $sInc->close();
    }

    $db->close();
?>