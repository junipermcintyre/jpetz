<?php
/*
*	The purpose of this command is to refresh the daily actions of each pet
*/

// Step #1 - Set up and run a query to increment all points by one
$sth = $db->prepare("UPDATE pets SET feeds = 0, flaunts = 0");						// Build a query to refresh pets
if ($sth === false) {throw new Exception ($dbh->error);}							// If something went REALLY wrong

$sth->execute();																	// Run the query
if ($sth === false) {throw new Exception ("bind (execute ".__LINE__." failed\n");}	// If something ELSE went really wrong

$db->next_result();																	// Be polite, make room for next query

echo "Pets were successfully refreshed!\n";											// Show some output to be nice
