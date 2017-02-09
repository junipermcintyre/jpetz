<?php
	// We'll be modifiying session stuff, so make sure the session is active
	session_start();

	/****************************************************************************
	*								SHOP CONTROLLER								*
	*****************************************************************************
	*	Author: 		Jerad McIntyre											*
	*	Date updated:	[Date]													*
	*	Purpose:		Serve as an AJAX controller for all functions involving	*
	*					the transfer of scum points for items.					*
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
		case "purchase":
			echo purchase($_POST['id']);
			break;
		default:
			break;
	}
	// SCRIPT EXECUTION SHOULD COMPLETE HERE



	/****************************************************************************
	*						SHOP CONTROLLER ACTION FUNCTIONS 					*
	*****************************************************************************
	* Each function is mapped to one of the controller actions listed above		*
	*****************************************************************************


	/******************************** PURCHASE **********************************
	*---------------------------------------------------------------------------*
	*	Purchase a new item or pet! Pass the ID, user pulled from session.		*
	*	Only succeed if enough scum points to purchase. Decrement points.		*
	****************************************************************************/
	function purchase($id) {
		mysqli_report(MYSQLI_REPORT_ALL);
		# Get users scum points
		# Get cost of item + stats
		# If enough
			# Create pet
			# return success
		# If not enough
			# Return failed
		
		$user = $_SESSION['id'];						// Collect the current user

		$db = connect_to_db();							// We're gonna need a database connection - MySQLi time
		if ($db->connect_error) {
            throw new Exception ($db->connect_error);	// We should probably catch this... somewhere
        }

		// Step #1 - Get users scum points
		$points = $db->query("SELECT scum_points FROM users WHERE id = {$user}");
		$points = $points->fetch_assoc()['scum_points'];

		$db->next_result();

		// Step #2 - Get pet data
		$sql = "SELECT name, cost, stock, basehp, baseatt, basedef, basehunger FROM species WHERE id = ?";
		if (! $sth = $db->prepare($sql)){throw new Exception ("SQL ($sql) failed: ". $db->error);}
	    if (! $sth->bind_param("i",$id)) {throw new Exception ("Bind Param failed: ".__LINE__);}
	    if (! $sth->bind_result($name, $cost, $stock, $hp, $att, $def, $hunger)){throw new Exception ("Bind Result failed: ".__LINE__);}

	    // Get a pet from database
	    if (!$result = $sth->execute()){throw new Exception ("Execute failed: ".$db->error);}

	    // Get results (only need to get one row, because pets are unique)
	    $sth->fetch();
	    $sth->free_result();
	    $db->next_result();

	    // Step #3 - Handle failure cases
	    if ($stock <= 0) {
	    	$db->close();									// ALWAYS do this
		    $response = array("success" => false, "message" => "This pet is out of stock! Stocks are replenished daily.");
			return json_encode($response);
		} else if ($points < $cost) {
	    	$db->close();									// ALWAYS do this
		    $response = array("success" => false, "message" => "Not enough scum points! View at your profile.");
			return json_encode($response);
	    }

    	// Create pet
		$sql = "INSERT INTO pets (name, owner, species, hp, maxhp, att, def, hunger, maxhunger, actions) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 10)";
		if (!$sth = $db->prepare($sql)){throw new Exception ("SQL ($sql) failed: ". $db->error);}
	    if (!$sth->bind_param("siiiiiiii",$name,$_SESSION['id'],$id,$hp,$hp,$att,$def,$hunger,$hunger)) {throw new Exception ("Bind Param failed: ".__LINE__);}

	    // Create the new pet
	    if (!$result = $sth->execute()){throw new Exception ("Execute failed: ".$db->error);}

	    $sth->free_result();
    	$db->next_result();

    	// Decrement users points
    	$np = $points - $cost;
    	$db->query("UPDATE users SET scum_points = {$np} WHERE id = {$_SESSION['id']}");
    	$db->next_result();

    	// Decrement pet stock
    	$sql = "UPDATE species SET stock = stock - 1 WHERE id = ?";
		if (!$sth = $db->prepare($sql)){throw new Exception ("SQL ($sql) failed: ". $db->error);}
	    if (!$sth->bind_param("i",$id)) {throw new Exception ("Bind Param failed: ".__LINE__);}
	    if (!$result = $sth->execute()){throw new Exception ("Execute failed: ".$db->error);}
	    $sth->free_result();

	    $db->close();

	    // Success!
	    $response = array("success" => true, "message" => "Congratulations on your new pet! View at your profile.");
	    return json_encode($response);
	}


	/****************************************************************************
	*								HELPER FUNCTIONS 							*
	*****************************************************************************
	* These are called within the controller actions, and do not need to		*
	* return JSON																*
	****************************************************************************/


	/********************************* HELPER ***********************************
	*---------------------------------------------------------------------------*
	*	HELPER DESCRIPTION														*
	****************************************************************************/
	function helper($value) {

	}
?>