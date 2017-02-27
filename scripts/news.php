
<?php
/*
*	The purpose of this command is perform a daily 'newspaper' function. Each day, it will post information to a Discord channel via webhook. Output is collected from a bunch of
*	php files in the news/ directory. Each file can assume the existence of the $db mysqli object, and should perform a $db->next_result() as necessary. Each file will also
*	assume the existence of a $content string, which is the message to be sent to discord
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
$date = date("l, d \o\\f F, Y");
$content = "__**Green Gaming Scum News for {$date}**__\n";

/***********************************************   RUN NEWS METHODS   ***********************************************/
// Show the Scum points leaders
include('news/ScumTopFive.php');

// Get previous question of the day answer
include('news/PreviousQuestionResults.php');

// Get new question and answers
//include('news/NewQuestion.php');

// Show which quests were completed last night
include('news/CompleteQuests.php');

// Show what happened to yesterdays raid boss
include('news/BossReward.php');

// Top pet collector
// SELECT u.name, COUNT(DISTINCT p.species) as collection FROM users u JOIN pets p on u.id = p.owner GROUP BY p.owner ORDER BY collection DESC;
// include('news/BestPetCollector.php');

// Random pet feature
// include('news/RandomPetFeature.php');

// Shop Stock output
// include('news/ShopStock.php');

$db->close();																		// Be polite


/*********************************************   PERFORM WEBHOOK POST   *********************************************/
$curl = curl_init();																// cURL, activate!

// Set some additional options for cURL (http://stackoverflow.com/a/24879774)
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($curl, CURLOPT_URL, getenv("NEWS_BOT_URL"));							// Change the URL to point at webhook URL
curl_setopt($curl, CURLOPT_POST, true);												// Gotta post the data to the webhook

$hookRequest = array(																// Build the discord webhook object
	"content" => $content,
	"embeds" => array(
		array(
    		"title" => "Your Daily Scum Journal",
    		"description" => "Every day, various facets of Green Gaming activity are updated on http://jeradmcintyre.com/. The *Town Crier* provides a daily journal like summary.",
    		"url" => "http://jeradmcintyre.com/",
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

curl_close($curl);																	// Done with curl, close it

echo "News successfully concluded!\n";
