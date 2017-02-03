<?php
	// We'll be modifiying session stuff, so make sure the session is active
	session_start();

	/****************************************************************************
	*								SCUM CONTROLLER								*
	*****************************************************************************
	*	Author: 		Jerad McIntyre											*
	*	Date updated:	October 26th 2016										*
	*	Purpose:		Serve as an AJAX controller for all queries to the 		*
	*					SCUM data in the DB										*
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
		case 'initialLoad':				// Loads initial data for page and sends it back to client
			echo initialLoad();			// We can expect an array headers and rows for user scum data
			break;
		case 'initialAdmin':
			echo initialAdmin();
			break;
		case 'modifyPoints':
			modifyPoints($_POST['ids'], $_POST['mod']);
			echo initialAdmin();
			break;			
		default:
			echo 'Uknown action specified!';
			break;
	}
	// SCRIPT EXECUTION SHOULD COMPLETE HERE



	/****************************************************************************
	*						SCUM CONTROLLER ACTION FUNCTIONS 					*
	****************************************************************************/
	// Each function is mapped to one of the controller actions listed above


	/****************************** INITIAL LOAD ********************************
	*---------------------------------------------------------------------------*
	*	This function is called on the initial scum page - queries the users	*
	*	for a list of users and their scum points								*
	****************************************************************************/
	function initialLoad() {
		// We're gonna need a database connection - MySQLi time
		$db = connect_to_db();											// (hint - this function is in conf/db.php)

		// Step #1 - Make sure the database connection is A+
		if ($db->connect_error) {
            throw new Exception ($db->connect_error);					// We should probably catch this... somewhere
        }

        // Step #2 - Let's get that user/scum data
        $result = $db->query("	SELECT u.name as name, r.name as role, u.scum_points, u.id, u.avatar
        						FROM users u JOIN roles r ON u.role = r.id ORDER BY scum_points DESC, role, u.name"
        );	
        if ($result === false) {throw new Exception ($dbh->error);}		// If somehing went wrong

        // Step #3 - build the result array
        $response['headers'] = array("User", "Role", "Scum Points");	// Define some headers
        $response['rows'] = array();									// Get ready for row data
        while ($row = $result->fetch_assoc()) {							// Iterate over each user
        	$tmp = array();
        	$tmp[0] = "<span><img class='scum-thumb' src='/images/avatars/{$row['avatar']}'></span><a href='/user.php?id={$row['id']}'>{$row['name']}</a>";
        	$tmp[1] = $row['role'];
        	$tmp[2] = $row['scum_points'];
        	array_push($response['rows'], $tmp);						// And store there data into a result row
        }
    
        $db->close();													// ALWAYS do this
    
        return json_encode($response);									// Everything's good! Return the scum/user data
	}


	/*************************** INITIAL ADMIN LOAD *****************************
	*---------------------------------------------------------------------------*
	*	This function is called on the scum admin page - queries the users		*
	*	for a list of users and their scum points. This is the same as the		*
	*	above function, with the addition of some control buttons.				*
	****************************************************************************/
	function initialAdmin() {
		// We're gonna need a database connection - MySQLi time
		$db = connect_to_db();													// (hint - this function is in conf/db.php)

		// Step #1 - Make sure the database connection is A+
		if ($db->connect_error) {
            throw new Exception ($db->connect_error);							// We should probably catch this... somewhere
        }

        // Step #2 - Let's get that user/scum data
        $result = $db->query("	SELECT u.name as name, r.name as role, u.scum_points, u.id
        						FROM users u JOIN roles r ON u.role = r.id ORDER BY scum_points DESC, role, u.name"
        );	
        if ($result === false) {throw new Exception ($dbh->error);}				// If somehing went wrong

        // Step #3 - build the result array
        $response['headers'] = array("Selected","Name", "Role", "Scum Points");	// Define some headers
        $response['rows'] = array();											// Get ready for row data
        while ($row = $result->fetch_assoc()) {									// Iterate over each user
        	$tmp = array();
        	$tmp[0] = "<input type='checkbox' id='{$row['id']}' class='player'>";
        	$tmp[1] = "<a href='/user.php?id={$row['id']}'>{$row['name']}</a>";
        	$tmp[2] = $row['role'];
        	$tmp[3] = $row['scum_points'];
        	array_push($response['rows'], $tmp);								// And store the data into a result row
        }
    
        $db->close();															// ALWAYS do this
    
        return json_encode($response);											// Everything's good! Return the scum/user data
	}


	/*************************** ADJUST USER POINTS *****************************
	*---------------------------------------------------------------------------*
	*	This function is called on the scum admin page - takes a list of user	*
	*	IDs and a modifier, and applies that modifier to the users.		*
	****************************************************************************/
	function modifyPoints($ids, $mod) {
		// +1 = beautiful person
		// -1 = semi-scum
		// -2 = scum

		// We're gonna need a database connection - MySQLi time
		$db = connect_to_db();													// (hint - this function is in conf/db.php)

		// Step #1 - Make sure the database connection is A+
		if ($db->connect_error) {
            throw new Exception ($db->connect_error);							// We should probably catch this... somewhere
        }

		$sql = "UPDATE users SET scum_points = scum_points + ? WHERE id = ?";	// SQL string to modify one users points

		$sth = $db->prepare($sql);												// Standard MySQLi prep stuff
        if ($sth === false) {throw new Exception ($dbh->error);}				// If somehing went REALLY wrong

        foreach ($ids as $id) {													// For every user, send the modifying point query
			$sth->bind_param("ii", $mod, $id);									// Add data to the query string (avoid SQL injects!)
	        if ($sth === false) {throw new Exception ("bind (line ".__LINE__." failed\n");}	
	        $sth->execute();
	        if ($sth === false) {throw new Exception ("bind (execute ".__LINE__." failed\n");}
        }
            
        $db->close();
	}
?>