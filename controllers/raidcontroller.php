<?php
	// We'll be modifiying session stuff, so make sure the session is active
	session_start();

	/****************************************************************************
	*								RAID CONTROLLER								*
	*****************************************************************************
	*	Author: 		J McIntyre												*
	*	Date updated:	2017-03-14												*
	*	Purpose:		Serve as an AJAX controller for Raiding!				*
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
		case "raid":
			echo raid($_POST['defender']);
			break;
		default:
			break;
	}
	// SCRIPT EXECUTION SHOULD COMPLETE HERE



	/****************************************************************************
	*						RAID CONTROLLER ACTION FUNCTIONS 					*
	*****************************************************************************
	* Each function is mapped to one of the controller actions listed above		*
	*****************************************************************************


	/********************************** RAID ************************************
	*---------------------------------------------------------------------------*
	*	When passed a user's ID, this function will attempt to that user		*
	*	using the current users pets. Raiding involves a chance of success		*
	*	or failure, then a calculated reward based on the defenders scum		*
	*	points, the defenders ttl defence, and the attackers ttl attack.		*
	*	Attacking pets may also take damage.									*
	****************************************************************************/
	function raid($dId) {
		$aId = $_SESSION['id'];					// Collect the current user

		$db = connect_to_db();							// We're gonna need a database connection - MySQLi time
		if ($db->connect_error) {
            throw new Exception ($db->connect_error);	// We should probably catch this... somewhere
        }

        # Get relevant info about the defender
        #	* scum points
        #	* number of pets
        #	* pets total defence
        # Get relevant info about the attacker
        #	* scum points
        #	* number of pets available, with actions, with hp > 1
        #	* total attack of those pets
        # Determine if attacker is allowed to attack this user
        #	* If not, build appropriate response
        # Determine if the raid was successful
        #	* If successful, allocate raided points
        # Determine if the attackers took damage
        #	* If so, allocate damage
        # Determine if the defenders took damage
        #	* If so, allocate damage
        # Decrement attacking pets actions
        # Return appropriate response

        // Get defender info
        $defender = getDefender($db, $dId);

        // Get attacker info
        $attacker = getAttacker($db, $aId);

        // Make sure they can hit each other
       	if (!canRaid($attacker['points'], $attacker['pets'], $defender['points'], $defender['pets']))
        	return buildResponse(false, "You're too powerful to raid this user!");

        // Determine whether or not it was a success
        $raidSuccess = getRaidSuccess($attacker['att'], $defender['def']);

        // Allocate damage - defenders take 1 if success, attackers take 2 always
        if ($raidSuccess)
        	raidDefenceDamage($db, $defender);

		// Decrement attacking pets actions and health
		decrementPet($db, $attacker);

        // If successful, steal the points
        if ($raidSuccess)
        	$cash = rob($db, $attacker, $defender);

        // Grab pet data to refresh view with
        $dPets = getDefenderPets($db, $defender['id']);
        $aPets = getAttackerPets($db, $attacker['id']);
		
		// Determine appropriate response
		if ($raidSuccess)
	    	return buildResponse(true, "You successfully looted {$cash} SP from {$defender['name']}! Your J-Petz took 2 damage. Your target's J-Petz took 1 damage.", array("d" => $dPets, "a" => $aPets, "status" => "success"));
	    else
	    	return buildResponse(true, "{$defender['name']} held off your raid! Your J-Petz took 2 damage.", array("d" => $dPets, "a" => $aPets, "status" => "warning"));
	}



	/****************************************************************************
	*								HELPER FUNCTIONS 							*
	*****************************************************************************
	* These are called within the controller actions, and do not need to		*
	* return JSON																*
	****************************************************************************/


	/*************************** Get Defender stats *****************************
	*---------------------------------------------------------------------------*
	*	Grab needed data by ID, for the raid defender.							*
	****************************************************************************/
	function getDefender($dbh, $id) {
		# We're looking for the following data
		#	* ID
		#	* Name
		#	* Number of pets
		#	* Total defence of pets
		#	* Current scum points

		$sql = "
			SELECT
			u.name,
			u.id,
			u.scum_points,
			COALESCE(SUM(p.def), 0) as def
			FROM users u
			LEFT JOIN pets p ON u.id = p.owner
			WHERE u.id = {$id}
			AND p.alive = 1
			AND p.busy = false
			AND p.hp > 1
			AND p.defending = true";

		$result = $dbh->query($sql);
		$d = $result->fetch_assoc();

		$defender = array(
			"id" => $d['id'],
			"name" => $d['name'],
			"points" => $d['scum_points'],
			"def" => $d['def']
		);

		$dbh->next_result();

		$sql = "
			SELECT
			COUNT(p.owner) as pets
			FROM users u
			LEFT JOIN pets p ON u.id = p.owner
			WHERE u.id = {$id}
			AND p.alive = 1";

		$result = $dbh->query($sql);
		$d = $result->fetch_assoc();

		$defender['pets'] = $d['pets'];

		$dbh->next_result();
		return $defender;
	}


	/*************************** Get Attacker stats *****************************
	*---------------------------------------------------------------------------*
	*	Grab needed data by ID, for the raid attacker.							*
	****************************************************************************/
	function getAttacker($dbh, $id) {
		# We're looking for the following data
		#	* ID
		#	* Name
		#	* Number of pets
		#	* Total attack of pets
		#	* Current scum points

		$sql = "
			SELECT
			u.name,
			u.id,
			u.scum_points,
			COUNT(p.owner) as pets,
			COALESCE(SUM(p.att), 0) as att
			FROM users u
			LEFT JOIN pets p ON u.id = p.owner
			WHERE u.id = {$id}
			AND p.alive = 1
			AND p.busy = 0
			AND p.actions >= 5
			AND p.hp > 2";

		$result = $dbh->query($sql);
		$a = $result->fetch_assoc();

		$attacker = array(
			"id" => $a['id'],
			"name" => $a['name'],
			"points" => $a['scum_points'],
			"pets" => $a['pets'],
			"att" => $a['att']
		);

		$dbh->next_result();
		return $attacker;
	}


	/*************************** Raid Defence Damage ****************************
	*---------------------------------------------------------------------------*
	*	Apply damage to the pets defending against the raid.					*
	****************************************************************************/
	function raidDefenceDamage($dbh, $d) {
		$sql ="
			UPDATE pets
			SET hp = hp - 1
			WHERE owner = {$d['id']}
			AND alive = 1
			AND busy = 0
			AND hp > 1
			AND defending = true";
		$dbh->query($sql);
	}


	/*************************** Raid Attack Damage *****************************
	*---------------------------------------------------------------------------*
	*	Apply damage to the pets attacking in the raid.							*
	****************************************************************************/
	function raidAttackDamage($dbh, $a) {
		$sql ="
			UPDATE pets
			SET hp = hp - 2
			WHERE owner = {$a['id']}
			AND actions >= 5
			AND alive = 1
			AND busy = 0
			AND hp > 2";
		$dbh->query($sql);
	}


	/*********************************** Rob ************************************
	*---------------------------------------------------------------------------*
	*	Take some of the defenders scum_points and allocate them to the attacker*
	****************************************************************************/
	function rob($dbh, $a, $d) {
		// Calculate points to be transferred
		$modifier = ($a['att'] / max($d['def'], 1)) * 0.1;
		$modifier = min(0.3, $modifier);
		$modifier = max($modifier, 0.05);
		$c = floor($d['points'] * $modifier);

		// Remove them from the defender
		$dbh->query("UPDATE users SET scum_points = scum_points - {$c} WHERE id = {$d['id']}");
		// Add them to attacker
		$dbh->query("UPDATE users SET scum_points = scum_points + {$c} WHERE id = {$a['id']}");

		return $c;
	}


	/************************** Decrement pet actions ***************************
	*---------------------------------------------------------------------------*
	*	Decrements actions of each attacker pet									*
	****************************************************************************/
	function decrementPet($dbh, $a) {
		$sql = "
			UPDATE pets
			SET hp = hp - 2, 
			actions = actions - 5
			WHERE owner = {$a['id']}
			AND actions >= 5
			AND alive = 1
			AND busy = 0
			AND hp > 2";
		$dbh->query($sql);
	}


	/**************************** Get Defender Pets *****************************
	*---------------------------------------------------------------------------*
	*	Get defending pet information											*
	****************************************************************************/
	function getDefenderPets($dbh, $id) {
		$dPets = $dbh->query("
            SELECT
            p.id,
            p.name,
            p.bio,
            p.hp,
            p.maxhp,
            p.att,
            p.def,
            p.hunger,
            p.maxhunger,
            s.img,
            s.name as species,
            s.flavour,
            t.name as type,
            r.name as rarity
            FROM pets p
            JOIN users u ON p.owner = u.id
            JOIN species s ON p.species = s.id
            JOIN types t ON s.type = t.id
            JOIN rarity r ON s.rarity = r.id
            WHERE p.owner = {$id}
            AND p.alive = true
            AND p.busy = false
            AND p.defending = true
            AND p.hp > 1"
        );   
        if ($dPets === false) {throw new Exception ($dbh->error);}              // If something went wrong
        $d_array = array();                                                     // Get ready for row data
        $defTtl = 0;                                                            // Track total defence
        while ($row = $dPets->fetch_assoc()) {                                  // Iterate over each defending pet
            $p = array();
            $p['id'] = $row['id'];
            $p['name'] = $row['name'];
            $p['hp'] = $row['hp'];
            $p['maxhp'] = $row['maxhp'];
            $p['att'] = $row['att'];
            $p['def'] = $row['def'];
            $p['hunger'] = $row['hunger'];
            $p['maxhunger'] = $row['maxhunger'];
            $p['img'] = $row['img'];
            $p['species'] = $row['species'];
            $p['type'] = $row['type'];
            $p['text'] = $row['bio'];
            $p['rarity'] = $row['rarity'];
            $defTtl += $p['def'];
            if (is_null($p['text']) || $p['text'] == "")
                $p['text'] = $row['flavour'];
            array_push($d_array, $p);                                          // And store the data into a result row
        }

        return array(
        	"ttl" => $defTtl,
        	"pets" => $d_array
        );
	}


	/**************************** Get Attacker Pets *****************************
	*---------------------------------------------------------------------------*
	*	Get attacking pet information											*
	****************************************************************************/
	function getAttackerPets($dbh, $id) {
		$aPets = $dbh->query("
            SELECT
            p.id,
            p.name,
            p.bio,
            p.hp,
            p.maxhp,
            p.att,
            p.def,
            p.hunger,
            p.maxhunger,
            s.img,
            s.name as species,
            s.flavour,
            t.name as type,
            r.name as rarity
            FROM pets p
            JOIN users u ON p.owner = u.id
            JOIN species s ON p.species = s.id
            JOIN types t ON s.type = t.id
            JOIN rarity r ON s.rarity = r.id
            WHERE p.owner = {$_SESSION['id']}
            AND p.alive = true
            AND p.busy = false
            AND p.actions >= 5
            AND p.hp > 2"
        );   
        if ($aPets === false) {throw new Exception ($dbh->error);}              // If something went wrong
        $a_array = array();                                                     // Get ready for row data
        $attTtl = 0;                                                            // Track total attack
        while ($row = $aPets->fetch_assoc()) {                                  // Iterate over each defending pet
            $p = array();
            $p['id'] = $row['id'];
            $p['name'] = $row['name'];
            $p['hp'] = $row['hp'];
            $p['maxhp'] = $row['maxhp'];
            $p['att'] = $row['att'];
            $p['def'] = $row['def'];
            $p['hunger'] = $row['hunger'];
            $p['maxhunger'] = $row['maxhunger'];
            $p['img'] = $row['img'];
            $p['species'] = $row['species'];
            $p['type'] = $row['type'];
            $p['text'] = $row['bio'];
            $p['rarity'] = $row['rarity'];
            $attTtl += $p['att'];
            if (is_null($p['text']) || $p['text'] == "")
                $p['text'] = $row['flavour'];
            array_push($a_array, $p);                                          // And store the data into a result row
        }

        return array(
        	"ttl" => $attTtl,
        	"pets" => $a_array
        );
	}


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