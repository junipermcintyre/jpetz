<?php
/*
*	The purpose of this command is to switch up the current question of the day to the next one!
*/
// Step #1 - Get the current ID
$id = $db->query("SELECT id FROM questions WHERE active = 1 AND verified = 1 LIMIT 1");		// Build a query to get the current question ID
if ($id === false) {throw new Exception ($db->error);}										// If somehing went wrong
if ($id->num_rows > 0) {
	$id = $id->fetch_object()->id;

	// Step #2 - Remove active status of current question
	$db->query("UPDATE questions SET active = 0 WHERE id = ".$id);								// Remove active status of current question

	$db->next_result();																			// Be polite

	echo "Question successfully rotated!\n";													// Show some output to be nice
} else {
	echo "Looks like there's no questions... that's so sad...\n";
}
