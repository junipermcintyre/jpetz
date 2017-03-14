<?php
/*
*	The purpose of this command is to increase the total scum points of each user by 1 when executed. It should be from ../upkeep.php
*/

// Step #1 - Set up and run a query to increment all points by one
$sth = $db->prepare("UPDATE users SET scum_points = scum_points + 5");				// Build a query to increment points
if ($sth === false) {throw new Exception ($dbh->error);}							// If somehing went REALLY wrong

$sth->execute();																	// Run the query
if ($sth === false) {throw new Exception ("bind (execute ".__LINE__." failed\n");}	// If something ELSE went really wrong

$db->next_result();																	// Be polite, make room for next query

echo "Scum points successfuly incremented!\n";										// Show some output to be nice
