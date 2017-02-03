<?php
/*
*	The purpose of this script is to output status of the previous question of the day.
*/
// Step #2 - Set up and run a query get the current question of the day
$code = $db->query("SELECT code FROM questions WHERE active = 0 AND verified = 1 ORDER BY id DESC LIMIT 1");		// Build a query to get the current question ID
if ($code === false) {throw new Exception ($db->error);}								    // If something went wrong
$code = $code->fetch_object()->code;													    // Grab the code from the result

// Step #1 - Hit the Strawpoll API (GET https://strawpoll.me/api/v2/polls) and get stats for the question
$curl = curl_init();																	    // cURL, activate!
$strawpollUrl = "https://strawpoll.me/api/v2/polls/{$code}";								// Build the strawpoll API Url
curl_setopt($curl, CURLOPT_URL, $strawpollUrl);												// And set it for cURL

// Set some additional options for cURL (http://stackoverflow.com/a/24879774)
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);

$pollData = curl_exec($curl);																// Send the request, store the response (JSON)
if (curl_errno($curl) > 0) {                                                                // Ensure we sent the data properly
    echo "Error getting strawpoll data!\n";
    throw new Exception("Could not get Strawpoll data, cURL error");
}

curl_close($curl);      

if (!$pollData)	{																			// cURL done fucked up
	echo "Error getting poll data! Code is: {$code}\n";
	throw new Exception("Could not get poll data for: {$code}");
}

$pollData = json_decode($pollData);															// Turn the JSON into a php array

$content .= "**Here's what everyone voted for last question:**\n";			                // Build the question output string
$content .= "*Question: {$pollData->title}*\n```";
$i = 0;
foreach ($pollData->options as $option) {
    $option = html_entity_decode($option, ENT_QUOTES);
	$content .= str_pad("{$option}:", 25);
	$content .= "{$pollData->votes[$i]} votes\n";
	$i++;
}
$content .= "```\nIf you voted, thanks! If not, vote next time!\n\n";

echo "Previous Question of the Day results added to content!\n";							// All done! Inform self of success
