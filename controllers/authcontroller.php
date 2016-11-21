<?php
	// We'll be modifiying session stuff, so make sure the session is active
	session_start();
	/****************************************************************************
	*							AUTH CONTROLLER								*
	*****************************************************************************
	*	Author: 		Jerad McIntyre											*
	*	Date updated:	May 20. 2016											*
	*	Purpose:		Serve as an AJAX controller for all actions involving	*
	*					user accounts.		 									*
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
		case 'register':																// Case when a new account is to be created
			echo register($_POST['email'], $_POST['password'], $_POST['username']);		// We can expect an array of new account info (email, password, etc). Returns success or failure
			break;
		case 'login':																	// Case when a user is logging in
			echo login($_POST['email'], $_POST['password']);							// If successful, sets the session for the current user. Returns success of failure
			break;
		default:
			break;
	}
	// SCRIPT EXECUTION SHOULD COMPLETE HERE



	/****************************************************************************
	*						AUTH CONTROLLER ACTION FUNCTIONS 					*
	****************************************************************************/
	// Each function is mapped to one of the controller actions listed above


	/******************************** REGISTER **********************************
	*---------------------------------------------------------------------------*
	*	This function is called when a user attemps to create a new account.	*
	*	It should make a new user database entry with the passed email and 		*
	*	password, should the email not already exist in the database. Returns	*
	*	either success or failure, and the reason why (if applicable)			*
	****************************************************************************/
	function register($email, $password, $username) {
		// We're gonna need a database connection - MySQLi time
		$db = connect_to_db();	// (hint - this function is in conf/db.php)

		// Step #1 - Make sure the database connection is A+
		if ($db->connect_error) {
            throw new Exception ($db->connect_error);	// We should probably catch this... somewhere
        }

        // Step #2 - Make sure the email doesn't already exist in the DB
        $email = strtolower($email);					// In case cAsE-SeNsItIvE
        $user_struct = get_user($email, $db);
        if (!empty($user_struct['email'])) { 			// If there IS a match (bad)
            $response = array("success" => false, "message" => "This email already exists!");
            return json_encode($response);				// Let the user know it failed, and why
        }

        // Step #3 - Add the user to the database
        $password = password_hash($password, PASSWORD_DEFAULT);											// Hash that sucker
        $sth = $db->prepare("INSERT INTO users (email, password, name, role) VALUES (?, ?, ?, 3)");		// Build a query to insert user
        if ($sth === false) {throw new Exception ($dbh->error);}										// If somehing went REALLY wrong
            
        $sth->bind_param("sss", $email, $password, $username);														// Add data to the query string (avoid SQL injects!)
        if ($sth === false) {throw new Exception ("bind (line ".__LINE__." failed\n");}	
        $sth->execute();
        if ($sth === false) {throw new Exception ("bind (execute ".__LINE__." failed\n");}
    
        $db->close();
    
        $response = array("success" => true, "message" => "Account created successfully! Please login.");	// Everything went well!
        return json_encode($response);
	}


	/********************************** LOGIN ***********************************
	*---------------------------------------------------------------------------*
	*	This function is called when a user attemps to login to an existing		*
	*	account. It will set the session with the users email if authentication	*
	*	is successful. Returns success or failure, and the reason why (if		*
	*	applicable)																*
	****************************************************************************/
	function login($email, $password) {
		// We're gonna need a database connection - MySQLi time
		$db = connect_to_db();	// (hint - this function is in conf/db.php)

		// Step #1 - Make sure the database connection is A+
		if ($db->connect_error) {
            throw new Exception ($db->connect_error);	// We should probably catch this... somewhere
        }

        // Step #2 - Get the account credentials for the matching email in DB
        $email = strtolower($email);					// In case cAsE-SeNsItIvE
        $user_struct = get_user($email, $db);
        $db->close();									// Make sure we close the database connection
        if (empty($user_struct['email'])) { 			// If there ISN'T a match (bad)
            $response = array("success" => false, "message" => "Password / email mismatch!");
            return json_encode($response);				// Let the user know it failed, and why (we ALWAYS say it's a mistmatch to limit info to users)
        }

        // Step #3 - Check if the passwords are the same (authentication)
       	if ( !password_verify($password, $user_struct['password'])) {	// Not a match!
       		$response = array("success" => false, "message" => "Password / email mismatch!");
            return json_encode($response);				// Let the user know it failed, and why (we ALWAYS say it's a mistmatch to limit info to users)
       	}
    
    	// Step #4 - Log the user into the session and inform them of success
    	$_SESSION['email'] = $email;
    	$_SESSION['role'] = $user_struct['role'];
    	$_SESSION['id'] = $user_struct['id'];
        $response = array("success" => true, "message" => "You have been successfully logged in!");	// Everything went well!
        return json_encode($response);
	}



	/****************************************************************************
	*								HELPER FUNCTIONS 							*
	****************************************************************************/
	// These are called within the controller actions, and do not need to return JSON


	/******************************** GET USER **********************************
	*---------------------------------------------------------------------------*
	*	This function takes an email and database connection and returns		*
	*	an array containing the users hashed password, and email if found.		*
	*	Otherwise, an empty array is returned.									*
	****************************************************************************/
	function get_user($email, $db) {
		$sql = "SELECT id, email, password, role FROM users WHERE email=?";
        if (! $sth = $db->prepare($sql)){throw new Exception ("SQL ($sql) failed: ". $db->error);}
        if (! $sth->bind_param("s",$email)) {throw new Exception ("Bind Param failed: ".__LINE__);}
        if (! $sth->bind_result($db_id, $db_user, $db_pass, $db_role)){throw new Exception ("Bind Result failed: ".__LINE__);}
    
        // Get a user from database
        if (!$result = $sth->execute()){throw new Exception ("Execute failed: ".$db->error);}
    
        // Get results (only need to get one row, because users are unique)
        $sth->fetch();
        $sth->close();
        
        $user = array();
        $user['id'] = $db_id;
        $user['email'] = $db_user;
        $user['password'] = $db_pass;
        $user['role'] = $db_role;
        return $user;
	}
?>