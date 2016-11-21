<?php
	/*
	*	The purpose of this script is to output status of current Question of the Day to the Green Gaming Discord channel using webhooks & JSON data
	*/
	// Load all Composer Dependencies
	require_once __DIR__ . '../../vendor/autoload.php';

	// Get some database access up in here
	require_once __DIR__ . '../../conf/db.php';

	// Load ENV variables from .env
	$dotenv = new Dotenv\Dotenv(__DIR__.'/..');
	$dotenv->load();

	// We're gonna need a database connection - MySQLi time
	$db = connect_to_db();																			// (hint - this function is in conf/db.php)

	// Step #1 - Make sure the database connection is A+
	if ($db->connect_error) {
        throw new Exception ($db->connect_error);													// We should probably catch this... somewhere
    }

    // Step #2 - Set up and run a query get the current question of the day
    $code = $db->query("SELECT code FROM questions WHERE active = 1 AND verified = 1 LIMIT 1");		// Build a query to get the current question ID
    if ($code === false) {throw new Exception ($db->error);}										// If something went wrong
    $code = $code->fetch_object()->code;															// Grab the code from the result

    $db->close();																					// Won't need that anymore!

    // Step #3 - Hit the Strawpoll API (GET https://strawpoll.me/api/v2/polls) and get stats for the question
    $curl = curl_init();																			// cURL, activate!
    $strawpollUrl = "https://strawpoll.me/api/v2/polls/{$code}";									// Build the strawpoll API Url
    curl_setopt($curl, CURLOPT_URL, $strawpollUrl);													// And set it for cURL

    // Set some additional options for cURL (http://stackoverflow.com/a/24879774)
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);

    $pollData = curl_exec($curl);																	// Send the request, store the response (JSON)

    if (!$pollData)	{																				// cURL done fucked up
    	echo "Error getting poll data! Code is: {$code}\n";
    	throw new Exception("Could not get poll data for: {$code}");
    }

    $pollData = json_decode($pollData);																// Turn the JSON into a php array

    // Step #4 - Build the result data into something we can send to the Discord bot webhook
    curl_setopt($curl, CURLOPT_URL, getenv("QUESTION_BOT_URL"));									// Change the URL to point at webhook URL
    curl_setopt($curl, CURLOPT_POST, true);															// Gotta post the data to the webhook

    $content = "__**Here are the current results for the question of the day!**__\n";				// Build the question output string
    $content .= "*Question: {$pollData->title}*\n```";
    $i = 0;
    foreach ($pollData->options as $option) {
    	$content .= str_pad("{$option}:", 25);
    	$content .= "{$pollData->votes[$i]} votes\n";
    	$i++;
    }
    $content .= "```Thank you for participating in question of the day. Submit your questions today!";

    $hookRequest = array(																			// Build the discord webhook object
    	"content" => $content,
    	"embeds" => array(
    		array(
	    		"title" => "Question of the day!",
	    		"description" => "{$pollData->title}",
	    		"url" => "http://jeradmcintyre.com/question.php",
    		)
    	)
    );

    $hookRequest = json_encode($hookRequest);														// Convert out request array to JSON

    curl_setopt($curl, CURLOPT_POSTFIELDS, $hookRequest);											// Set the hook data to the curl object
    $hookResponse = curl_exec($curl);																// Send it off!
    
    if (curl_errno($curl) > 0) {																	// Ensure we sent the data properly
    	echo "Error sending webhook to discord!\n";
    	throw new Exception("Could not send poll data to discord");
    }

    curl_close($curl);																				// Done with curl, close it
    echo "Question of the day data successfully sent!\n";											// All done! Inform self of success
?>