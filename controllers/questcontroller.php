<?php
	// We'll be modifiying session stuff, so make sure the session is active
	session_start();

	/****************************************************************************
	*							QUEST CONTROLLER								*
	*****************************************************************************
	*	Author: 		J McIntyre												*
	*	Date updated:	Jan 28, 2017											*
	*	Purpose:		Serve as an AJAX controller for all functions involving	*
	*					the the going on of quests.								*
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
		case "send":
			echo send($_POST['pet'], $_POST['quest']);
			break;
		default:
			break;
	}
	// SCRIPT EXECUTION SHOULD COMPLETE HERE



	/****************************************************************************
	*						QUEST CONTROLLER ACTION FUNCTIONS 					*
	*****************************************************************************
	* Each function is mapped to one of the controller actions listed above		*
	*****************************************************************************


	/********************************** SEND ************************************
	*---------------------------------------------------------------------------*
	*	Send a pet on a quest! Expects pet and quest id. Checks if the pet		*
	*	belongs to the logged in owner, and that it meets the requirements.		*
	****************************************************************************/
	function send($petId, $questId) {
		$userId = $_SESSION['id'];						// Collect the current user

		$db = connect_to_db();							// We're gonna need a database connection - MySQLi time
		if ($db->connect_error) {
            throw new Exception ($db->connect_error);	// We should probably catch this... somewhere
        }

        # Collect DB info about the pet
        # Collect DB info about the quest
        # Confirm..
        	# Quest does not currently belong to someone
        	# Pet belongs to current user
        	# Pet is not busy, or, dead
        	# Pet meets quest requirements
        # Send pet on quest
        	# Update busy status
        	# Update quest fields

        // Collect DB info about the pet
        $sql = "SELECT p.name, p.owner, p.species, s.type, p.att, p.def, p.busy, p.alive FROM pets p JOIN species s ON p.species = s.id WHERE p.id = ?";
		if (!$sth = $db->prepare($sql)){throw new Exception ("SQL ($sql) failed: ". $db->error);}
	    if (!$sth->bind_param("i",$petId)) {throw new Exception ("Bind Param failed: ".__LINE__);}
	    if (!$sth->bind_result($name, $owner, $species, $type, $att, $def, $busy, $alive)){throw new Exception ("Bind Result failed: ".__LINE__);}
	    // Get a pet from database
	    if (!$result = $sth->execute()){throw new Exception ("Execute failed: ".$db->error);}
	    // Get results (only need to get one row, because pets are unique)
	    $sth->fetch();
	    $sth->free_result();
	    $db->next_result();

	    // Collect DB info about the quest
	    $sql = "SELECT length, hero, pet, reqatt, reqdef, reqtype, reqspecies FROM quests WHERE id = ?";
		if (!$sth = $db->prepare($sql)){throw new Exception ("SQL ($sql) failed: ". $db->error);}
	    if (!$sth->bind_param("i",$questId)) {throw new Exception ("Bind Param failed: ".__LINE__);}
	    if (!$sth->bind_result($length, $hero, $pet, $reqatt, $reqdef, $reqtype, $reqspecies)){throw new Exception ("Bind Result failed: ".__LINE__);}
	    // Get a pet from database
	    if (!$result = $sth->execute()){throw new Exception ("Execute failed: ".$db->error);}
	    // Get results (only need to get one row, because pets are unique)
	    $sth->fetch();
	    $sth->free_result();
	    $db->next_result();

	    // Confirm quest doesn't belong to anyone
	    if (!is_null($hero) || !is_null($pet))
	    	return buildResponse(false, "Someone's J-Pet is already on that quest!");

	    // Confirm pet belongs to current user
	    if ($owner != $userId)
	    	return buildResponse(false, "You don't own that J-Pet!");

	    // Confirm pet is not busy or dead
	    if (!$alive || $busy)
	    	return buildResponse(false, "This J-Pet is busy... or dead.");

	    // Confirm pet meets requirements
	    // Check att
	    if (!is_null($reqatt) && $att < $reqatt)
	    	return buildResponse(false, "Your J-Pet does not meet the attack requirements!");

	    // Check def
	    if (!is_null($reqdef) && $def < $reqdef)
	    	return buildResponse(false, "Your J-Pet does not meet the defence requirements!");

	    // Check type
	    if (!is_null($reqtype) && $reqtype != $type)
	    	return buildResponse(false, "Your J-Pet does not meet the type requirements!");

	    // Check species
	    if (!is_null($reqspecies) && $reqspecies != $species)
	    	return buildResponse(false, "Your J-Pet does not meet the species requirements");

	    // Send pet on the quest
	    // Update quest fields
	    $sql = "UPDATE quests SET hero = {$userId}, pet = {$petId} WHERE id = {$questId}";
	    $db->query($sql);

	    // Update pet fields
	    $sql = "UPDATE pets SET busy = 1 WHERE id = {$petId}";
	    $db->query($sql);

	    // Success!
	    return buildResponse(true, "Your J-Pet {$name} has departed on their quest! They should be back in {$length} days.");
	}


	/****************************************************************************
	*								HELPER FUNCTIONS 							*
	*****************************************************************************
	* These are called within the controller actions, and do not need to		*
	* return JSON																*
	****************************************************************************/


	/***************************** Build Response *******************************
	*---------------------------------------------------------------------------*
	*	Build a response object													*
	****************************************************************************/
	function buildResponse($success, $message, $data = array()) {
		$r = array(
			"success" => $success,
			"message" => $message,
			"data" => $data
		);
		return json_encode($r);
	}
?>