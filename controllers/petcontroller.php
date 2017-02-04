<?php
	// We'll be modifiying session stuff, so make sure the session is active
	session_start();

	/****************************************************************************
	*								PET CONTROLLER								*
	*****************************************************************************
	*	Author: 		Jerad McIntyre											*
	*	Date updated:	[Date]													*
	*	Purpose:		Serve as an AJAX controller for all interactions		*
	*					involving PETS.											*
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
		case "feed":
			echo feed($_POST['pet']);
			break;
		case "flaunt":
			echo flaunt($_POST['pet']);
			break;
		case "edit":
			echo edit($_POST['pet'], $_POST['name'], $_POST['bio']);
			break;
		case "train":
			echo train($_POST['pet'], $_POST['stat']);
		default:
			break;
	}
	// SCRIPT EXECUTION SHOULD COMPLETE HERE



	/****************************************************************************
	*						PET CONTROLLER ACTION FUNCTIONS 					*
	*****************************************************************************
	* Each function is mapped to one of the controller actions listed above		*
	*****************************************************************************


	/********************************** CHECK ***********************************
	*---------------------------------------------------------------------------*
	*	Confirm pet belongs to supplied owner.									*
	****************************************************************************/
	function checkPet($dbh, $user, $pet) {
		// Confirm pet belongs to current user
		$sql = "SELECT owner FROM pets WHERE id = ?";
		if (!$sth = $dbh->prepare($sql)){throw new Exception ("SQL ($sql) failed: ". $dbh->error);}
        if (!$sth->bind_param("i",$pet)) {throw new Exception ("Bind Param failed: ".__LINE__);}
        if (!$sth->bind_result($realId)){throw new Exception ("Bind Result failed: ".__LINE__);}
        if (!$result = $sth->execute()){throw new Exception ("Execute failed: ".$dbh->error);}
    
        // Get results (only need to get one row, because pets are unique)
        $sth->fetch();
        $sth->close();

        $dbh->next_result();
        
        return ($_SESSION['id'] == $realId);
	}


	/*********************************** GET ************************************
	*---------------------------------------------------------------------------*
	*	Get the stats for a pet with given ID									*
	****************************************************************************/
	function get($dbh, $pet) {
		// Get pet data, return
        $sql = "SELECT
        p.id,
        p.name,
        s.img,
        p.owner,
        u.name as ownername,
        s.name as species,
        t.name as type,
        s.flavour,
        p.bio,
        p.hp,
        p.maxhp,
        p.att,
        p.def,
        p.hunger,
        p.maxhunger,
        p.alive,
        p.actions
        FROM pets p JOIN species s ON p.species = s.id
        JOIN types t ON s.type = t.id 
        JOIN users u ON p.owner = u.id
        WHERE p.id = ?";
		if (!$sth = $dbh->prepare($sql)){throw new Exception ("SQL ($sql) failed: ". $dbh->error);}
        if (!$sth->bind_param("i",$pet)) {throw new Exception ("Bind Param failed: ".__LINE__);}
        if (!$sth->bind_result($id, $name, $img, $owner, $ownername, $species, $type, $flavour, $bio, $hp, $maxhp, $att, $def, $hunger, $maxhunger, $alive, $actions)){throw new Exception ("Bind Result failed: ".__LINE__);}
        if (!$result = $sth->execute()){throw new Exception ("Execute failed: ".$dbh->error);}
    
        // Get results (only need to get one row, because pets are unique)
        $sth->fetch();
        $sth->close();

        $pet_data = array(
        	"id" => $id,
        	"name" => $name,
        	"img" => $img,
        	"owner" => $owner,
        	"ownername" => $ownername,
        	"species" => $species,
        	"type" => $type,
        	"flavour" => $flavour,
        	"bio" => $bio,
        	"hp" => $hp,
        	"maxhp" => $maxhp,
        	"att" => $att,
        	"def" => $def,
        	"hunger" => $hunger,
        	"maxhunger" => $maxhunger,
        	"alive" => $alive,
        	"actions" => $actions
        );

		return buildResponse(true, "J-Pet data supplied.", $pet_data);
	}


	/********************************** FEED ************************************
	*---------------------------------------------------------------------------*
	*	Feed a pet! Pet must belong to current user ($_SESION['id']), pet must	*
	*	have at least 1 action remaning, and user must have 2 points.			*
	****************************************************************************/
	function feed($pet) {
		$db = connect_to_db();	// (hint - this function is in conf/db.php)

		// Step #1 - Make sure the database connection is A+
		if ($db->connect_error) {
            throw new Exception ($db->connect_error);	// We should probably catch this... somewhere
        }

		// Confirm pet belongs to current user
		if (!checkPet($db, $_SESSION['id'], $pet))
        	return buildResponse(false, "You don't own this J-Pet! (tell me how you got this error)");

		// Confirm that the user has the necessary points to feed pet
		if (!checkPoints($db, $_SESSION['id'], 2))
			return buildResponse(false, "You don't have enough points to buy food!");

		// Confirm that the pet has hunger < maxhunger
		$sql = "SELECT hunger, maxhunger FROM pets WHERE id = ?";
		if (!$sth = $db->prepare($sql)){throw new Exception ("SQL ($sql) failed: ". $db->error);}
        if (!$sth->bind_param("i",$pet)) {throw new Exception ("Bind Param failed: ".__LINE__);}
        if (!$sth->bind_result($hunger, $maxhunger)){throw new Exception ("Bind Result failed: ".__LINE__);}
        if (!$result = $sth->execute()){throw new Exception ("Execute failed: ".$db->error);}
        // Get results (only need to get one row, because pets are unique)
        $sth->fetch();
        $sth->close();
        $db->next_result();

        if ($hunger >= $maxhunger || !checkActions($db, $pet, 1))
        	return buildResponse(false, "Your J-Pet does not seem interested in eating.");

		// Subtract users SP
		dockPoints($db, $_SESSION['id'], 2);

		// Feed the pet (+1 hunger, +1 actions)
		feedPet($db, $pet);

		$pd = json_decode(get($db, $pet));
        $pd = $pd->data;
		return buildResponse(true, "J-Pet successfully fed!", $pd);
	}


	/********************************** TRAIN ***********************************
	*---------------------------------------------------------------------------*
	*	Train a pet! Pet must belong to current user ($_SESION['id']), pet must	*
	*	have two actions available.												*
	****************************************************************************/
	function train($pet, $stat) {
		$db = connect_to_db();	// (hint - this function is in conf/db.php)

		// Step #1 - Make sure the database connection is A+
		if ($db->connect_error) {
            throw new Exception ($db->connect_error);	// We should probably catch this... somewhere
        }

		// Confirm pet belongs to current user
		if (!checkPet($db, $_SESSION['id'], $pet))
        	return buildResponse(false, "You don't own this J-Pet! (tell me how you got this error)");

        if (!checkActions($db, $pet, 2))
        	return buildResponse(false, "Your J-Pet is far too tired to train right now.");

		// Train the pet
		trainPet($db, $pet, $stat, null);	// null here represents the bonus to add - if not supplied, use the pets mod stat
		dockActions($db, $pet, 2);
		
		$pd = json_decode(get($db, $pet));
        $pd = $pd->data;
        
		return buildResponse(true, "J-Pet successfully trained in {$stat}!", $pd);
	}


	/********************************* FLAUNT ***********************************
	*---------------------------------------------------------------------------*
	*	Flaunt a pet! Pet must belong to current user, and user must have 10	*
	*	points. Must be one action available.									*
	****************************************************************************/
	function flaunt($pet) {
		$db = connect_to_db();	// (hint - this function is in conf/db.php)

		// Step #1 - Make sure the database connection is A+
		if ($db->connect_error) {
            throw new Exception ($db->connect_error);	// We should probably catch this... somewhere
        }

		// Confirm pet belongs to current user
		if (!checkPet($db, $_SESSION['id'], $pet))
        	return buildResponse(false, "You don't own this J-Pet! (tell me how you got this error)");

		// Confirm that the user has the necessary points to feed pet
		if (!checkPoints($db, $_SESSION['id'], 2))
			return buildResponse(false, "You don't have enough points to pay the flaunt tax!");

		// Confirm pet has been flaunted < 2 today
        if (!checkActions($db, $pet, 1))
        	return buildResponse(false, "Your J-Pet is too tired to be a fabulous bitch right now.");

		// Subtract users SP
		dockPoints($db, $_SESSION['id'], 10);

		// Flaunt pet (post + )
		flauntPet($db, $pet);

		return buildResponse(true, "J-Pet successfully flaunted!");
	}


	/********************************** EDIT ************************************
	*---------------------------------------------------------------------------*
	*	Edit a pets info! Pet must belong to current user.						*
	****************************************************************************/
	function edit($pet, $name, $bio) {
		$db = connect_to_db();	// (hint - this function is in conf/db.php)

		// Step #1 - Make sure the database connection is A+
		if ($db->connect_error) {
            throw new Exception ($db->connect_error);	// We should probably catch this... somewhere
        }

		// Confirm pet belongs to current user
		if (!checkPet($db, $_SESSION['id'], $pet))
        	return buildResponse(false, "You don't own this pet! (tell me how you got this error)");

		// Update pet
		$sql = "UPDATE pets SET name = ?, bio = ? WHERE id = ?";
		if (! $sth = $db->prepare($sql)){throw new Exception ("SQL ($sql) failed: ". $db->error);}
        if (! $sth->bind_param("ssi",$name,$bio,$pet)) {throw new Exception ("Bind Param failed: ".__LINE__);}
        if (!$result = $sth->execute()){throw new Exception ("Execute failed: ".$db->error);}

        // Get the updated pet data and send it to the view
        $pd = json_decode(get($db, $pet));
        $pd = $pd->data;

		return buildResponse(true, "Pet successfully edited!", $pd);
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


	/****************************** Check Points ********************************
	*---------------------------------------------------------------------------*
	*	See if a user has at least x points										*
	****************************************************************************/
	function checkPoints($dbh, $user, $points) {
		// Confirm pet belongs to current user
		$sql = "SELECT scum_points FROM users WHERE id = ?";
		if (!$sth = $dbh->prepare($sql)){throw new Exception ("SQL ($sql) failed: ". $dbh->error);}
        if (!$sth->bind_param("i",$user)) {throw new Exception ("Bind Param failed: ".__LINE__);}
        if (!$sth->bind_result($sp)){throw new Exception ("Bind Result failed: ".__LINE__);}
        if (!$result = $sth->execute()){throw new Exception ("Execute failed: ".$dbh->error);}
    
        // Get results (only need to get one row, because pets are unique)
        $sth->fetch();
        $sth->close();

        $dbh->next_result();
        
        return ($sp >= $points);
	}


	/****************************** Dock Points *********************************
	*---------------------------------------------------------------------------*
	*	Remove a specific amount of users points								*
	****************************************************************************/
	function dockPoints($dbh, $user, $points) {
		// Confirm pet belongs to current user
		$sql = "UPDATE users SET scum_points = scum_points - ? WHERE id = ?";
		if (!$sth = $dbh->prepare($sql)){throw new Exception ("SQL ($sql) failed: ". $dbh->error);}
        if (!$sth->bind_param("ii",$points,$user)) {throw new Exception ("Bind Param failed: ".__LINE__);}
        if (!$result = $sth->execute()){throw new Exception ("Execute failed: ".$dbh->error);}

        $dbh->next_result();
        
        return;
	}


	/******************************** Feed pet **********************************
	*---------------------------------------------------------------------------*
	*	Increase pets hunger by one, actions by one								*
	****************************************************************************/
	function feedPet($dbh, $pet) {
		$sql = "UPDATE pets SET hunger = hunger + 1 WHERE id = ?";
		if (!$sth = $dbh->prepare($sql)){throw new Exception ("SQL ($sql) failed: ". $dbh->error);}
        if (!$sth->bind_param("i",$pet)) {throw new Exception ("Bind Param failed: ".__LINE__);}
        if (!$result = $sth->execute()){throw new Exception ("Execute failed: ".$dbh->error);}

        $dbh->next_result();
        dockActions($dbh, $pet, 1);
        return;
	}


	/******************************** Train pet *********************************
	*---------------------------------------------------------------------------*
	*	Increase pets supplied stat by supplied value, or, the statmod.			*
	****************************************************************************/
	function trainPet($dbh, $pet, $stat, $mod = null) {
		if ($stat == "att") {
			$sql = "UPDATE pets SET att = att + ";
			$modStr = "(SELECT attmod FROM species WHERE pets.species = species.id) WHERE id = ?";
		} else if ($stat == "def") {
			$sql = "UPDATE pets SET def = def + ";
			$modStr = "(SELECT defmod FROM species WHERE pets.species = species.id) WHERE id = ?";
		} else if ($stat == "maxhp") {
			$sql = "UPDATE pets SET maxhp = maxhp + ";
			$modStr = "(SELECT hpmod FROM species WHERE pets.species = species.id) WHERE id = ?";
		} else {
			throw new Exception("Unrecognized stat to train!");
		}

		if (!is_null($mod))
			$modstr = "{$mod} WHERE id = ?";

		$sql = $sql.$modStr;
		if (!$sth = $dbh->prepare($sql)){throw new Exception ("SQL ($sql) failed: ". $dbh->error);}
        if (!$sth->bind_param("i",$pet)) {throw new Exception ("Bind Param failed: ".__LINE__);}
        if (!$result = $sth->execute()){throw new Exception ("Execute failed: ".$dbh->error);}

        $dbh->next_result();
        return;
	}


	/******************************* Flaunt pet *********************************
	*---------------------------------------------------------------------------*
	*	Flaunt the pet to the Discord Channel.									*
	****************************************************************************/
	function flauntPet($dbh, $pet) {
        // Grab pet data
        $pd = json_decode(get($dbh, $pet));
        $pd = $pd->data;

        $content = "__***{$pd->ownername} has flaunted their {$pd->species}, {$pd->name}***__\n\n";

        // Choose an intro line
        if (is_null($pd->bio) || $pd->bio == "") {
        	$in = "\"{$pd->flavour}\"";
        	$out = strlen($in) > 1000 ? substr($in,0,1000)."...\"" : $in; 
        	$next = "\n-**{$pd->name}** *the* **{$pd->species}***\n\n";

        	$content .= $out.$next;
        }
        else {
        	$in = "\"{$pd->bio}\"";
        	$out = strlen($in) > 1000 ? substr($in,0,1000)."...\"" : $in; 
        	$next = "\n-**{$pd->name}** *the* **{$pd->species}***\n\n";
        	
        	$content .= $out.$next;
        }

        // Dump the pets stats
        $content .= "**Stats**\n";
        $hpLine = str_pad("Health:", 25)."{$pd->hp} / {$pd->maxhp}\n";
        $hungerLine = str_pad("Hunger:", 25)."{$pd->hunger} / {$pd->maxhunger}\n";
        $attLine = str_pad("Attack:", 25)."{$pd->att}\n";
        $defLine = str_pad("Defence:",25)."{$pd->def}";
        $content .= "```{$hpLine}{$hungerLine}{$attLine}{$defLine}```\n";

        $curl = curl_init();																// cURL, activate!

		// Set some additional options for cURL (http://stackoverflow.com/a/24879774)
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_URL, getenv("PET_BOT_URL"));								// Change the URL to point at webhook URL
		curl_setopt($curl, CURLOPT_POST, true);												// Gotta post the data to the webhook

		$hookRequest = array(																// Build the discord webhook object
			"content" => $content,
			"embeds" => array(
				array(
		    		"title" => "Examine the J-Pet for yourself",
		    		"description" => "{$pd->ownername} has flaunted their pet, {$pd->name}! {$pd->species} is a {$pd->type} J-Pet.",
		    		"url" => "http://jeradmcintyre.com/pet.php?pet={$pet}",
		    		"image" => array(
						"url" => "http://jeradmcintyre.com/images/pets/{$pd->img}"
					)
				)
			)
		);

		$hookRequest = json_encode($hookRequest);											// Convert out request array to JSON

		curl_setopt($curl, CURLOPT_POSTFIELDS, $hookRequest);								// Set the hook data to the curl object
		$hookResponse = curl_exec($curl);													// Send it off!

		if (curl_errno($curl) > 0) {														// Ensure we sent the data properly
			echo "Error sending webhook to discord!\n";
			throw new Exception("Couldn't get the newspaper out!");
		}

		curl_close($curl);
		dockActions($dbh, $pet, 1);
        return;
	}


	/****************************** Dock actions ********************************
	*---------------------------------------------------------------------------*
	*	Like dock points but for pet actions.									*
	****************************************************************************/
	function dockActions($dbh, $petId, $n) {
		$sql = "UPDATE pets SET actions = actions - ? WHERE id = ?";
		if (!$sth = $dbh->prepare($sql)){throw new Exception ("SQL ($sql) failed: ". $dbh->error);}
        if (!$sth->bind_param("ii",$n,$petId)) {throw new Exception ("Bind Param failed: ".__LINE__);}
        if (!$result = $sth->execute()){throw new Exception ("Execute failed: ".$dbh->error);}

        $dbh->next_result();
	}

	/****************************** Check Actions *******************************
	*---------------------------------------------------------------------------*
	*	See if a pet has at least x actions										*
	****************************************************************************/
	function checkActions($dbh, $petId, $n) {
		// Confirm pet belongs to current user
		$sql = "SELECT actions FROM pets WHERE id = ?";
		if (!$sth = $dbh->prepare($sql)){throw new Exception ("SQL ($sql) failed: ". $dbh->error);}
        if (!$sth->bind_param("i",$petId)) {throw new Exception ("Bind Param failed: ".__LINE__);}
        if (!$sth->bind_result($ac)){throw new Exception ("Bind Result failed: ".__LINE__);}
        if (!$result = $sth->execute()){throw new Exception ("Execute failed: ".$dbh->error);}
    
        // Get results (only need to get one row, because pets are unique)
        $sth->fetch();
        $sth->close();

        $dbh->next_result();
        
        return ($ac >= $n);
	}
