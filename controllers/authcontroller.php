<?php
	// We'll be modifiying session stuff, so make sure the session is active
	session_start();

	/****************************************************************************
	*							AUTH CONTROLLER									*
	*****************************************************************************
	*	Author: 		J McIntyre												*
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
			echo login($_POST['email'], $_POST['password'], $_POST['rm']);				// If successful, sets the session for the current user. Returns success of failure
			break;
		case 'resetemail':																// Case when user requests email reset password
			echo resetEmail($_POST['email']);
			break;
		case 'reset':
			echo resetPassword($_POST['token'], $_POST['password']);					// Case when user follows reset email to reset password
			break;
		case 'update':																	// Case when user updates profile
			echo update();
			break;
		case 'feature':
			echo feature($_POST['name'], $_POST['msg']);							// Case when someone does a feature request
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
        $password = password_hash($password, PASSWORD_DEFAULT);								// Hash that sucker
        $sth = $db->prepare("INSERT INTO users (email, password, name, role, avatar) VALUES (?, ?, ?, 3, 'default.jpg')");
        if ($sth === false) {throw new Exception ($db->error);}								// If somehing went REALLY wrong
            
        $sth->bind_param("sss", $email, $password, $username);								// Add data to the query string (avoid SQL injects!)
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
	function login($email, $password, $rm) {
		// We're gonna need a database connection - MySQLi time
		$db = connect_to_db();	// (hint - this function is in conf/db.php)

		// Step #1 - Make sure the database connection is A+
		if ($db->connect_error) {
            throw new Exception ($db->connect_error);	// We should probably catch this... somewhere
        }

        // Step #2 - Get the account credentials for the matching email in DB
        $email = strtolower($email);					// In case cAsE-SeNsItIvE
        $user_struct = get_user($email, $db);
        
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

    	// Step #5 - If the user wished to be remembered, set an additional token
    	if ($rm) {
    		// Build a series identifier and token
    		$series = random_str(16);
    		$token = random_str(16);
    		$tokenHash = sha1($token);
    		// Assign cookie with concatenated values - handled in JS
    		//setcookie("rememberme", $series.".".$token, time()+60*60*24*30);	// Set the cookie with remember me things
    		// Add the entry to the database
    		$res = $db->query("SELECT id FROM login WHERE user = {$user_struct['id']}");
    		if ($res->fetch_assoc()) {		// ie if there was a match
    			// Update the record
    			$db->query("UPDATE login SET series = '{$series}', token = '{$tokenHash}' WHERE user = {$user_struct['id']}");
    		} else {						// Create a new one
    			// Create new record
    			$db->query("INSERT INTO login (user, series, token) VALUES ({$user_struct['id']}, '{$series}', '{$tokenHash}')");
    		}
    	}

    	$db->close();															// Make sure we close the database connection
        $response = array("success" => true, "message" => "You have been successfully logged in!");	// Everything went well!
        if ($rm)
        	$response['cookie'] = $series.".".$token;

        return json_encode($response);
	}


	/**************************** SEND RESET EMAIL ******************************
	*---------------------------------------------------------------------------*
	*	This function is called when a user attemps to reset their password		*
	*	via the password reset page. It generates a reset token, stores it in	*
	*	the database, then sends the token to the user via email which			*
	*	authenticates them to reset the password for which the token matches.	*
	****************************************************************************/
	function resetEmail($email) {
		// We're gonna need a database connection - MySQLi time
		$db = connect_to_db();	// (hint - this function is in conf/db.php)

		// Step #1 - Make sure the database connection is A+
		if ($db->connect_error) {
            throw new Exception ($db->connect_error);	// We should probably catch this... somewhere
        }

        // Step #2 - Get the account credentials for the matching email in DB
        $email = strtolower($email);					// In case cAsE-SeNsItIvE
        $user_struct = get_user($email, $db);

        if (empty($user_struct['email'])) { 			// If there ISN'T a match (bad)
            $response = array("success" => true, "message" => "If there's an account for that email, we sent an email!");	// Don't tell em!
            return json_encode($response);				// Let the user know it failed, and why (we ALWAYS say the same thing to limit info to users)
        }

        // Step #3 - Generate a reset token and insert it into the users account
        $token_length = 16;								// Set a token length (actual string ends up double this)
    	$token = bin2hex(openssl_random_pseudo_bytes($token_length));
    	$result = $db->query("UPDATE users SET resettoken = '{$token}' WHERE email = '{$email}'");

    	$db->close();

    	// Step #4 - If we updated properly, send the user an email with the token + URL for reset
    	if ($result) {
    		$body = "Hey dumbshit, you either forgot your password and are resetting it, or someone's messing with you. ";
    		$body .= "If it was you, follow this link to reset it (and try not to forget in the future): https://jpetz.junipermcintyre.net/reset.php?token={$token}. ";
    		$body .= "If it wasn't you, you can ignore this and it will all go away.";
    		$headers = "From: passwordrecovery@jpetz.junipermcintyre.net\r\n";
			if (mail($email, "Password Recovery", $body, $headers)) {
				$response = array("success" => true, "message" => "If there's an account for that email, we sent an email!");
				return json_encode($response);
			}
    	}
    	// If we're here, there must've been an error sending the email
    	$response = array("success" => false, "message" => "There was an error sending emails! Contact administrator.");
		return json_encode($response);
	}


	/***************************** RESET PASSWORD *******************************
	*---------------------------------------------------------------------------*
	*	This function is called when a user attemps to reset their password		*
	*	via the password reset page. It takes the password reset token generated*
	*	previously and sets a new password for the matching account.			*
	****************************************************************************/
	function resetPassword($token, $password) {
		// We're gonna need a database connection - MySQLi time
		$db = connect_to_db();	// (hint - this function is in conf/db.php)

		// Step #1 - Make sure the database connection is A+
		if ($db->connect_error) {
            throw new Exception ($db->connect_error);	// We should probably catch this... somewhere
        }

        // Step #2 - Update the users password
        $password = password_hash($password, PASSWORD_DEFAULT);											// Hash that sucker
        $sth = $db->prepare("UPDATE users SET password = ?, resettoken = NULL WHERE resettoken = ?");	// Build a query update password
        if ($sth === false) {throw new Exception ($db->error);}											// If somehing went REALLY wrong
            
        $sth->bind_param("ss", $password, $token);														// Add data to the query string (avoid SQL injects!)
        if ($sth === false) {throw new Exception ("bind (line ".__LINE__." failed\n");}	
        $sth->execute();
        if ($sth === false) {throw new Exception ("bind (execute ".__LINE__." failed\n");}
    
        $db->close();
    
        $response = array("success" => true, "message" => "Password updated successfully! Please login.");	// Everything went well!
        return json_encode($response);
	}



	/***************************** UPDATE PROFILE *******************************
	*---------------------------------------------------------------------------*
	*	This function is called when a user attemps to update their profile.	*
	*	It expects POST data containing necessary fields.						*
	****************************************************************************/
	function update() {
		// We're gonna need a database connection - MySQLi time
		$db = connect_to_db();	// (hint - this function is in conf/db.php)

		// Step #1 - Make sure the database connection is A+
		if ($db->connect_error) {
            throw new Exception ($db->connect_error);	// We should probably catch this... somewhere
        }

        // Step #2 - Pull out the stuff we know we get
        $name = $_POST['name'];
        $twitter = $_POST['twitter'];
        $steam = $_POST['steam'];
        $league = $_POST['league'];
        $website = $_POST['website'];
        $about = $_POST['about'];
        $intro = $_POST['intro'];

        // Step #3 - Diverge if image was uploaded (special case)
        if (isset($_FILES['pic'])) {
        	// Upload the image
        	$pic_name = uniqid("avatar_").".png";
        	$dir = "/images/avatars/";
        	move_uploaded_file($_FILES['pic']['tmp_name'], dirname(getcwd()).$dir.$pic_name);
        	$sth = $db->prepare(
        		"UPDATE users SET
        			name = ?,
					twitter = ?,
					steam_id = ?,
					summoner_id = ?,
					website = ?,
					about = ?,
					intro = ?,
					avatar = ?
        		WHERE id = ?"
        	);	// Build an update query
	        if ($sth === false) {throw new Exception ($db->error);}																		// If somehing went REALLY wrong
	            
	        $sth->bind_param("ssssssssi", $name, $twitter, $steam, $league, $website, $about, $intro, $pic_name, $_SESSION['id']);		// Add data to the query string (avoid SQL injects!)
	        if ($sth === false) {throw new Exception ("bind (line ".__LINE__." failed\n");}	

	        $sth->execute();
	        if ($sth === false) {throw new Exception ("bind (execute ".__LINE__." failed\n");}
	    
	        $db->close();
	    
	        $response = array("success" => true, "message" => "Profile updated successfully!");	// Everything went well!
	        return json_encode($response);
        } else {
        	$sth = $db->prepare(
        		"UPDATE users SET
        			name = ?,
					twitter = ?,
					steam_id = ?,
					summoner_id = ?,
					website = ?,
					about = ?,
					intro = ?
        		WHERE id = ?"
        	);	// Build an update query
	        if ($sth === false) {throw new Exception ($db->error);}																		// If somehing went REALLY wrong
	            
	        $sth->bind_param("sssssssi", $name, $twitter, $steam, $league, $website, $about, $intro, $_SESSION['id']);					// Add data to the query string (avoid SQL injects!)
	        if ($sth === false) {throw new Exception ("bind (line ".__LINE__." failed\n");}	

	        $sth->execute();
	        if ($sth === false) {throw new Exception ("bind (execute ".__LINE__." failed\n");}
	    
	        $db->close();
	    
	        $response = array("success" => true, "message" => "Profile updated successfully!");	// Everything went well!
	        return json_encode($response);
        }
	}


	/**************************** FEATURE REQUEST *******************************
	*---------------------------------------------------------------------------*
	*	This function is called when a user attemps to send a feature request.	*
	*	Grab the emails of mods and admins, and send them an email.				*
	****************************************************************************/
	function feature($name, $message) {
		// We're gonna need a database connection - MySQLi time
		$db = connect_to_db();	// (hint - this function is in conf/db.php)

		// Step #1 - Make sure the database connection is A+
		if ($db->connect_error) {
            throw new Exception ($db->connect_error);	// We should probably catch this... somewhere
        }

        $sql = "SELECT email FROM users WHERE role = 1";
        $res = $db->query($sql);

        $to = "";
        while ($email = $res->fetch_assoc()) {
        	$to .= "{$email['email']},";
        }
        $to = rtrim($to, ',');

        $db->close();

		$subject = 'Feature request on jpetz.junipermcintyre.net';
		$headers = 'From: noreply@jpetz.junipermcintyre.net' . "\r\n" .
				   'X-Mailer: PHP/' . phpversion();

		$to = "junipermcintyre@gmail.com";

		if (mail($to, $subject, $message, $headers))
	        $response = array("success" => true, "message" => "Feature request sent!");	// Everything went well!
	    else
	        $response = array("success" => false, "message" => "Couldn't send mail!");	// Not good!

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


	/**
	* Generate a random string, using a cryptographically secure 
	* pseudorandom number generator (random_int)
	* 
	* For PHP 7, random_int is a PHP core function
	* For PHP 5.x, depends on https://github.com/paragonie/random_compat
	* 
	* @param int $length      How many characters do we want?
	* @param string $keyspace A string of all possible characters
	*                         to select from
	* @return string
	*
	* See: http://stackoverflow.com/questions/4356289/php-random-string-generator/31107425#31107425
	*/
	function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
	{
		$str = '';
		$max = mb_strlen($keyspace, '8bit') - 1;
		for ($i = 0; $i < $length; ++$i) {
		    $str .= $keyspace[rand(0, $max)];
		}
		return $str;
	}
?>