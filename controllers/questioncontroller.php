<?php
	// We'll be modifiying session stuff, so make sure the session is active
	session_start();

	/****************************************************************************
	*							QUESTION CONTROLLER								*
	*****************************************************************************
	*	Author: 		Jerad McIntyre											*
	*	Date updated:	November 9th 2016										*
	*	Purpose:		Serve as an AJAX controller for all queries to the 		*
	*					QUESTION data in the DB									*
	*																			*
	****************************************************************************/
	// Load all Composer Dependencies
	require_once __DIR__ . '../../vendor/autoload.php';

	// Get some database access up in here
	require_once __DIR__ . '../../conf/db.php';

	// Load ENV variables from .env
	$dotenv = new Dotenv\Dotenv(__DIR__.'/..');
	$dotenv->load();



	/****************************************************************************
	*					ROUTING TABLE FOR JQUERY AJAX CALLS 					*
	****************************************************************************/
	/*
	*	This table takes the form of a switch statement which reads the 'action'
	*	index of the $_POST data. Depending on the action, a specific function is
	*	called. In this way, we map action strings to specific controller actions.
	*
	*	We can assume that each function / controller action called will return
	*	the necessary data in JSON.
	*/

	switch($_POST['action']) {
		case 'initialLoad':					// Loads initial data for page and sends it back to client
			echo initialLoad();
			break;
		case 'addCode':						// Takes in a question code and inserts it into the question database
			echo addCode($_POST['code']);	// We can expect a strawpoll code to be sent
		case 'initialAdmin':
			echo initialAdmin();
			break;
		case 'verify':						// Verifies each code sent, so its added to post-ing queue
			verify($_POST['ids']);
			echo initialAdmin();
			break;
		case 'discard':
			discard($_POST['ids']);			// Discards each code send, so it can never be added to queue
			echo initialAdmin();
			break;
		default:							// Just in case something weird comes in
			echo 'Uknown action specified!';
			break;
	}
	// SCRIPT EXECUTION SHOULD COMPLETE HERE



	/****************************************************************************
	*					QUESTION CONTROLLER ACTION FUNCTIONS 					*
	****************************************************************************/
	// Each function is mapped to one of the controller actions listed above


	/****************************** INITIAL LOAD ********************************
	*---------------------------------------------------------------------------*
	*	This function is called on the initial question page - queries the		*
	*	questions for the first active and verified question					*
	****************************************************************************/
	function initialLoad() {
		// We're gonna need a database connection - MySQLi time
		$db = connect_to_db();											// (hint - this function is in conf/db.php)

		// Step #1 - Make sure the database connection is A+
		if ($db->connect_error) {
            throw new Exception ($db->connect_error);					// We should probably catch this... somewhere
        }

        // Step #2 - Let's get that question code
        $result = $db->query("SELECT code FROM questions WHERE active = 1 AND verified = 1 LIMIT 1");	
        if ($result === false) {throw new Exception ($db->error);}		// If somehing went wrong
        $result = $result->fetch_object();

        // Step #3 - build response
        $response = $result;

        $db->close();													// ALWAYS do this
        return json_encode($response);									// Everything's good! Return the scum/user data
	}


	/******************************** ADD CODE **********************************
	*---------------------------------------------------------------------------*
	*	This function is called when a user attemps to submit a strawpoll code.	*
	*	The code will be inserted into the database and await approval.	If		*
	*	insertion is successful, return true. Otherwise, error must be thrown.	*
	****************************************************************************/
	function addCode($code) {
		// We're gonna need a database connection - MySQLi time
		$db = connect_to_db();											// (hint - this function is in conf/db.php)

		// Step #1 - Make sure the database connection is A+
		if ($db->connect_error) {
            throw new Exception ($db->connect_error);					// We should probably catch this... somewhere
        }

        // Step #2 - Let's insert the question
        $user = "NULL";
        if (isset($_SESSION['id']))										// If the user is logged in, track who submitted it
        	$user = $_SESSION['id'];
        $sth = $db->prepare("INSERT INTO questions (id, code, active, date, verified, user) VALUES (NULL, ?, 1, '".date('Y-m-d')."', 0, {$user})");
        if ($sth === false) {throw new Exception ($db->error);}			// If somehing went REALLY wrong
        $sth->bind_param("s", $code);									// Add data to the query string (avoid SQL injects!)
        if ($sth === false) {throw new Exception ("bind (line ".__LINE__." failed\n");}	
        $sth->execute();
        if ($sth === false) {throw new Exception ("bind (execute ".__LINE__." failed\n");}

        // Step #3 - build response
        $response = true;

        $db->close();													// ALWAYS do this
        return json_encode($response);									// Everything's good! Return the scum/user data
	}


	/*************************** INITIAL ADMIN LOAD *****************************
	*---------------------------------------------------------------------------*
	*	This function is called on the question admin page - queries the q's	*
	*	for a list of codes and sends em back all nice like.					*
	****************************************************************************/
	function initialAdmin() {
		// We're gonna need a database connection - MySQLi time
		$db = connect_to_db();																	// (hint - this function is in conf/db.php)

		// Step #1 - Make sure the database connection is A+
		if ($db->connect_error) {
            throw new Exception ($db->connect_error);											// We should probably catch this... somewhere
        }

        // Step #2 - Let's get that unverified question data
        $result = $db->query("SELECT q.id as id, q.code as code, q.date as date, COALESCE(u.name, 'Anonymous') as name
        					  FROM questions q LEFT JOIN users u ON q.user = u.id
        					  WHERE q.verified = 0 AND q.active = 1");
        if ($result === false) {throw new Exception ($db->error);}								// If somehing went wrong

        // Step #3 - build the result array
        $tmp_response['headers'] = array("Selected","Question", "Submit Date", "Submitter");	// Define some headers
        $tmp_response['rows'] = array();														// Get ready for row data
        while ($row = $result->fetch_assoc()) {													// Iterate over each question
        	$tmp = array();
        	$tmp[0] = "<input type='checkbox' id='{$row['id']}' class='question'>";
        	$tmp[1] = "<a href='http://strawpoll.me/{$row['code']}' target='_blank'>{$row['code']}</a>";
        	$tmp[2] = $row['date'];
        	$tmp[3] = $row['name'];
        	array_push($tmp_response['rows'], $tmp);											// And store the data into a result row
        }

        // Step #3.5 - Push the unverified table data onto the response
        $response['unverified'] = $tmp_response;

        // Step #4 - Let's get that verified / active question data
        $result = $db->query("SELECT q.id as id, q.code as code, q.date as date, COALESCE(u.name, 'Anonymous') as name
        					  FROM questions q LEFT JOIN users u ON q.user = u.id
        					  WHERE q.verified = 1 AND q.active = 1");	
        if ($result === false) {throw new Exception ($dbh->error);}								// If somehing went wrong

        // Step #5 - build the result array
        $tmp_response['headers'] = array("Question", "Submit Date", "Live ETA Date", "Submitter");
        $tmp_response['rows'] = array();														// Get ready for row data
        $live = 0;																				// Track estimation of when question goes live (days)
        while ($row = $result->fetch_assoc()) {													// Iterate over each question
        	$tmp = array();
        	$tmp[0] = "<a href='http://strawpoll.me/{$row['code']}' target='_blank'>{$row['code']}</a>";
        	$tmp[1] = $row['date'];
        	$tmp[2] = date('F jS, Y', strtotime("+".$live." days"));
        	$tmp[3] = $row['name'];
        	array_push($tmp_response['rows'], $tmp);											// And store the data into a result row
        	$live++;																			// Increment the ETA counter
        }

        // Step #5.5 - Push the verified table data onto the response
        $response['verified'] = $tmp_response;
    
        $db->close();																			// ALWAYS do this
    
        return json_encode($response);															// Everything's good! Return the question data
	}


	/**************************** VERIFY QUESTIONS ******************************
	*---------------------------------------------------------------------------*
	*	This function is called on the question admin page - takes a list of	*
	*	question IDs and verifies the questions for rotation.					*
	****************************************************************************/
	function verify($ids) {
		// We're gonna need a database connection - MySQLi time
		$db = connect_to_db();														// (hint - this function is in conf/db.php)

		// Step #1 - Make sure the database connection is A+
		if ($db->connect_error) {
            throw new Exception ($db->connect_error);								// We should probably catch this... somewhere
        }

		$sql = "UPDATE questions SET verified = 1 WHERE id = ? AND verified = 0";	// SQL string to verify one question
		$qSql = "SELECT user FROM questions WHERE id = ?";							// SQL string to get the submitter of one question
		$scumSql = "UPDATE users SET scum_points = scum_points + 6 WHERE id = ?";	// SQL string to increment scum points for a user

		// TO-DO: Find more efficient way to do this...

        foreach ($ids as $id) {														// For every question, verify it
        	/********************************************* Run query to verify question ********************************************/
        	$qUpdate = $db->prepare($sql);											// Prepare verification query for question
        	if ($qUpdate === false) {throw new Exception ($db->error);}
			$qUpdate->bind_param("i", $id);											// Add data to the query string (avoid SQL injects!)
	        if ($qUpdate === false) {throw new Exception ("bind (line ".__LINE__." failed\n");}	
	        $qUpdate->execute();
	        if ($qUpdate === false) {throw new Exception ("bind (execute ".__LINE__." failed\n");}
	        $qUpdate->close();														// Get out of the way for the next query

	        /******************************************** Run query to see if user sub'd *******************************************/
	        $qCheck = $db->prepare($qSql);											// Prepare sql check of user submitter-thing
        	if ($qCheck === false) {throw new Exception ($db->error);}
	        $qCheck->bind_param("i", $id);											// Add data to the user check sql query
	        if ($qCheck === false) {throw new Exception ("bind (line ".__LINE__." failed\n");}	
	        if (!$qCheck->bind_result($user)) {throw new Exception ("Bind Result failed: ".__LINE__);}
	        $qCheck->execute();
	        if ($qCheck === false) {throw new Exception ("bind (execute ".__LINE__." failed\n");}
	        $qCheck->fetch();														// Try to grab the user ID of submitted question
	        $qCheck->close();														// Get out of the way for the next query

	        /**************************************** Run query increment user's points by 3 ***************************************/
	        if (!is_null($user)) {
	        	$sInc = $db->prepare($scumSql);										// Prepare query to increment scum points
        		if ($sInc === false) {throw new Exception ($db->error);}
	        	$sInc->bind_param("i", $user);										// Add data to the query string (safely)
	        	if ($sInc === false) {throw new Exception ("bind (line ".__LINE__." failed\n");}
			    $sInc->execute();
			    if ($sInc === false) {throw new Exception ("bind (execute ".__LINE__." failed\n");}
			    $sInc->close();														// Get out of way for next query
	        }
        }
        $db->close();
	}


	/**************************** DISCARD QUESTIONS *****************************
	*---------------------------------------------------------------------------*
	*	This function is called on the question admin page - takes a list of	*
	*	question IDs and discards the questions for rotation.					*
	****************************************************************************/
	function discard($ids) {
		// We're gonna need a database connection - MySQLi time
		$db = connect_to_db();													// (hint - this function is in conf/db.php)

		// Step #1 - Make sure the database connection is A+
		if ($db->connect_error) {
            throw new Exception ($db->connect_error);							// We should probably catch this... somewhere
        }

		$sql = "UPDATE questions SET active = 0 WHERE id = ?";					// SQL string to discard one question

		$sth = $db->prepare($sql);												// Standard MySQLi prep stuff
        if ($sth === false) {throw new Exception ($db->error);}				// If somehing went REALLY wrong

        foreach ($ids as $id) {													// For every question, discard it
			$sth->bind_param("i", $id);											// Add data to the query string (avoid SQL injects!)
	        if ($sth === false) {throw new Exception ("bind (line ".__LINE__." failed\n");}	
	        $sth->execute();
	        if ($sth === false) {throw new Exception ("bind (execute ".__LINE__." failed\n");}
        }
            
        $db->close();
	}
?>