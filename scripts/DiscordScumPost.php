<?php
	/*
	*	The purpose of this script is to output current scum stats to the Green Gaming Discord channel using webhooks & JSON data
	*/
	// Load all Composer Dependencies
	require_once __DIR__ . '../../vendor/autoload.php';

	// Get some database access up in here
	require_once __DIR__ . '../../conf/db.php';

	// Load ENV variables from .env
	$dotenv = new Dotenv\Dotenv(__DIR__.'/..');
	$dotenv->load();

	// We're gonna need a database connection - MySQLi time
	$db = connect_to_db();																// (hint - this function is in conf/db.php)

	// Step #1 - Make sure the database connection is A+
	if ($db->connect_error) {
        throw new Exception ($db->connect_error);										// We should probably catch this... somewhere
    }

    // Step #2 - Set up and run a query get all the scum stats
    $scum = $db->query("SELECT u.name as name, r.name as role, u.scum_points, u.id
        				FROM users u JOIN roles r ON u.role = r.id ORDER BY scum_points DESC, role, u.name");		
    if ($scum === false) {throw new Exception ($db->error);}							// If somehing went wrong

    $db->close();																		// Won't need that anymore!

    // Step #3 - Build the result data into something we can send to the Discord bot webhook
    $curl = curl_init();																// cURL, activate!

    // Set some additional options for cURL (http://stackoverflow.com/a/24879774)
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);

    curl_setopt($curl, CURLOPT_URL, getenv("SCUM_BOT_URL"));							// Change the URL to point at webhook URL
    curl_setopt($curl, CURLOPT_POST, true);												// Gotta post the data to the webhook

    $content = "__**Who's racking up the points?**__\n";								// Build the scum points output string
    $content .= "*More points = better person (don't ask!)*\n```";

    while ($row = $scum->fetch_object()) {												// Iterate over each user's scum data
    	$content .= str_pad($row->name, 20);
    	$content .= "{$row->scum_points}\n";
	}

    $content .= "```Want more points? Try contributing questions!\n";

    $hookRequest = array(																// Build the discord webhook object
    	"content" => $content,
    	"embeds" => array(
    		array(
	    		"title" => "Scum point tally!",
	    		"description" => "For those who's ego requires larger numbers",
	    		"url" => "http://jeradmcintyre.com/scum.php",
    		)
    	)
    );

    $hookRequest = json_encode($hookRequest);											// Convert out request array to JSON

    curl_setopt($curl, CURLOPT_POSTFIELDS, $hookRequest);								// Set the hook data to the curl object
    $hookResponse = curl_exec($curl);													// Send it off!
    
    if (curl_errno($curl) > 0) {														// Ensure we sent the data properly
    	echo "Error sending webhook to discord!\n";
    	throw new Exception("Could not send scum data to discord");
    }

    curl_close($curl);																	// Done with curl, close it
    echo "Scum data has successfully been sent to Discord\n";							// All done! Inform self of success
?>