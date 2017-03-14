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
		case "buycap":
			echo buycap($_POST['id']);
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



	/**************************** PURCHASE CAPSULE ******************************
	*---------------------------------------------------------------------------*
	*	Purchase a new J-Cap! Pass the cap ID, user pulled from session.		*
	*	Only succeed if enough scum points to purchase. Decrement points.		*
	****************************************************************************/
	function buycap($id) {
		# Get users scum points
		# Get cap stats
		# If enough
			# Decrement cost
			# Select the pet
			# return success + pet ID
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

		// Step #2 - Get cap data
		$sql = "SELECT name, type, cost FROM caps WHERE id = ?";
		if (! $sth = $db->prepare($sql)){throw new Exception ("SQL ($sql) failed: ". $db->error);}
	    if (! $sth->bind_param("i",$id)) {throw new Exception ("Bind Param failed: ".__LINE__);}
	    if (! $sth->bind_result($name, $type, $cost)){throw new Exception ("Bind Result failed: ".__LINE__);}

	    // Grab the cap data
	    if (!$result = $sth->execute()){throw new Exception ("Execute failed: ".$db->error);}

	    // Get results (only need to get one row, because caps are unique)
	    $sth->fetch();
	    $sth->free_result();
	    $db->next_result();

	    // Step #3 - Handle failure cases
	    if ($points < $cost) {
	    	$db->close();									// ALWAYS do this
		    $response = array("success" => false, "message" => "You don't have enough Scum Points to buy this!");
			return json_encode($response);
	    }

	    // Get a list of pets in the capsule
	    $sql = "SELECT id, rarity FROM species WHERE type = ? AND rarity IS NOT NULL";
	    if (! $sth = $db->prepare($sql)){throw new Exception ("SQL ($sql) failed: ". $db->error);}
	    if (! $sth->bind_param("i",$type)) {throw new Exception ("Bind Param failed: ".__LINE__);}
	    if (! $sth->bind_result($speciesId, $weight)){throw new Exception ("Bind Result failed: ".__LINE__);}

	    // Grab the pets data
	    if (!$result = $sth->execute()){throw new Exception ("Execute failed: ".$db->error);}

	    // Build a weighted distribution
	    $sum = 0;
	    $options = array();
	    while ($sth->fetch()) {
	    	$sum += $weight;
	    	$options[$speciesId] = $weight;
	    }

		$sth->free_result();
	    $db->next_result();

	    // Pick the selected pet
	    $rnd = rand(0, $sum);
	    foreach($options as $sId => $w) {
	    	if ($rnd < $w) {
	    		$selectedId = $sId;
	    		break;
	    	}
	    	$rnd -= $w;
	    }

	    // Get its stats
		$sql = "SELECT s.name, r.name AS rarity, s.basehp, s.baseatt, s.basedef, s.basehunger, s.img FROM species s JOIN rarity r ON s.rarity = r.id WHERE s.id = ?";
		if (! $sth = $db->prepare($sql)){throw new Exception ("SQL ($sql) failed: ". $db->error);}
	    if (! $sth->bind_param("i",$sId)) {throw new Exception ("Bind Param failed: ".__LINE__);}
	    if (! $sth->bind_result($name, $rarity, $hp, $att, $def, $hunger, $img)){throw new Exception ("Bind Result failed: ".__LINE__);}

	    // Get a pet from database
	    if (!$result = $sth->execute()){throw new Exception ("Execute failed: ".$db->error);}

	    // Get results (only need to get one row, because pets are unique)
	    $sth->fetch();
	    $sth->free_result();
	    $db->next_result();

    	// Create pet
		$sql = "INSERT INTO pets (name, owner, species, hp, maxhp, att, def, hunger, maxhunger, actions) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 10)";
		if (!$sth = $db->prepare($sql)){throw new Exception ("SQL ($sql) failed: ". $db->error);}
	    if (!$sth->bind_param("siiiiiiii",$name,$_SESSION['id'],$sId,$hp,$hp,$att,$def,$hunger,$hunger)) {throw new Exception ("Bind Param failed: ".__LINE__);}

	    // Create the new pet
	    if (!$result = $sth->execute()){throw new Exception ("Execute failed: ".$db->error);}

	    // Get the new pets ID
	    $newPet = $sth->insert_id;

	    $sth->free_result();
    	$db->next_result();

    	// Decrement users points
    	$np = $points - $cost;
    	$db->query("UPDATE users SET scum_points = {$np} WHERE id = {$_SESSION['id']}");
    	$db->next_result();

	    $db->close();

	    // Success!
	    $response = array(
	    	"success" => true,
	    	"message" => "Congratulations on your new pet! View at your profile.",
	    	"name" => $name,
	    	"hp" => $hp,
	    	"hunger" => $hunger,
	    	"att" => $att,
	    	"def" => $def,
	    	"id" => $newPet,
	    	"img" => $img,
	    	"rarity" => $rarity
	    );

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