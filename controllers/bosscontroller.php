<?php
	// We'll be modifiying session stuff, so make sure the session is active
	session_start();

	/****************************************************************************
	*								BOSS CONTROLLER								*
	*****************************************************************************
	*	Author: 		Jerad McIntyre											*
	*	Date updated:	[Date]													*
	*	Purpose:		Serve as an AJAX controller for ...						*
	*																			*
	****************************************************************************/
	// Load all Composer Dependencies
	require_once __DIR__ . '../../vendor/autoload.php';

	// Get some database access up in here
	require_once __DIR__ . '../../conf/db.php';

	// We'll be doing some combat stuff
	require_once __DIR__ . '../../includes/combat.php';

	// We'll also be interacting with pets in here
	require_once __DIR__ . '../../includes/pet.php';	

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
		case "attack":
			echo attack($_POST['pet']);
			break;
		default:
			break;
	}
	// SCRIPT EXECUTION SHOULD COMPLETE HERE



	/****************************************************************************
	*						BOSS CONTROLLER ACTION FUNCTIONS 					*
	*****************************************************************************
	* Each function is mapped to one of the controller actions listed above		*
	*****************************************************************************


	/********************************* ATTACK ***********************************
	*---------------------------------------------------------------------------*
	*	When passed a pets ID, this function will attempt to attack the current	*
	*	Raid Boss (RB). It can attack if it is owned by the current user,		*
	*	and not busy.															*
	****************************************************************************/
	function attack($petId) {
		$userId = $_SESSION['id'];						// Collect the current user

		$db = connect_to_db();							// We're gonna need a database connection - MySQLi time
		if ($db->connect_error) {
            throw new Exception ($db->connect_error);	// We should probably catch this... somewhere
        }

		# Collect DB info about the pet
		# Collect DB info about the RB
		# Confirm...
			# Pet belongs to current user
        	# Pet is not busy, or, dead
        	# Boss is not dead
        # Attack the boss
        	# Hit the raid boss
        	# Mark the damage for the pet as done
        	# Check if the pet died (lol)
        		# Adjust
        	# Check if the boss died (rip)
        		# Adjust

        // Collect DB info about the pet
        $pet = getPet($db, $petId);

	    // Collect DB info about the RB
	    $boss = getBoss($db);

	    // Check that a boss exists right now
	    if (!$boss)
	    	return buildResponse(false, "There's no boss to fight right now!");

	    // Confirm pet belongs to current user
	    if ($pet['owner'] != $userId)
	    	return buildResponse(false, "You don't own that J-Pet!");

	    // Confirm pet is not busy or dead
	    if (!$pet['alive'] || $pet['busy'])
	    	return buildResponse(false, "This J-Pet is busy... or dead.");

	    // Confirm boss hasn't died already
	    if ($boss['beaten'])
	    	return buildResponse(false, "This Raid Boss has already been defeated!");
	    
	    // Hit the RB (combat.php) (returns true if boss dies) (automatically adds to boss_dmg table)
	    $beaten = hitBoss($db, $pet, $boss);

	    $msg = "";	// Additional message to tack onto response if needed (deaths, usually)

	    // See if the pet died
	    $pet = getPet($db, $petId);
	    if (!$pet['alive'])
	    	$msg .= " Oh no! Your J-Pet perished in the fight! ";

	    // See if the RB died
	    if ($beaten)
	    	$msg .= " The raid boss perishes - your pet has defeated it! Bonuses and awards totaled at end of day (12AM).";

	    $boss = getBoss($db);
	    return buildResponse(true, "Your J-Pet smacks the Raid Boss.".$msg, array('hp' => $pet['hp'], 'bosshp' => $boss['hp']));
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


	/******************************** Get Boss **********************************
	*---------------------------------------------------------------------------*
	*	Get an object for the current boss.										*
	****************************************************************************/
	function getBoss($dbh) {
		$sql = "SELECT id, pet, maxhp, hp, att, def, reward, bonus, beaten, killer FROM boss WHERE active = TRUE";
		if (!$sth = $dbh->prepare($sql)){throw new Exception ("SQL ($sql) failed: ". $dbh->error);}
	    if (!$sth->bind_result($id, $pet, $maxhp, $hp, $att, $def, $reward, $bonus, $beaten, $killer)){throw new Exception ("Bind Result failed: ".__LINE__);}
	    // Get a pet from database
	    if (!$result = $sth->execute()){throw new Exception ("Execute failed: ".$dbh->error);}
	    // Get results (only need to get one row, because pets are unique)
	    $sth->fetch();
	    $sth->free_result();
	    $dbh->next_result();

	    if (!isset($id) || is_null($id) || $id == '' || !$id)
	    	return false;

	    return array(
	    	'id' => $id,
	    	'pet' => $pet,
	    	'maxhp' => $maxhp,
	    	'hp' => $hp,
	    	'att' => $att,
	    	'def' => $def,
	    	'reward' => $reward,
	    	'bonus' => $bonus,
	    	'beaten' => $beaten,
	    	'killer' => $killer
	    );
	}
?>