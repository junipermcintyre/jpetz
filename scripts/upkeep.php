
<?php
/*
*	The purpose of this command is perform a daily 'tick' functionality. Basically process each turn, once a day. All actual functionality is done via files in the upkeep/ folder.
*	Each file assumes the DB object is active. If DB queries are used, the $db->next_result() method is expected to have been run prior to the end of the file.
*	Each file is expected to have one line of output indicating success, or failure (if possible).
*	No variable naming rules are enforced between files except existence of the $db mysqli object.
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


/***********************************************   RUN UPKEEP METHODS   ***********************************************/
// Increase each users scum points
include('upkeep/IncreaseScumPoints.php');

// Update question of the day
include('upkeep/NextQuestion.php');

// Perform pet hunger and hp checks
include('upkeep/DecrementPetHunger.php');

// Reset pet stats (flaunts, feeds)
include('upkeep/RefreshPets.php');

// Reset pet stocks
include('upkeep/RefreshSpeciesStock.php');

// Process quests
include('upkeep/ProcessQuests.php');

// Generate new quests
include('upkeep/GenQuests.php');

$db->close();																		// Be polite

echo "Upkeep successfully concluded!\n";
