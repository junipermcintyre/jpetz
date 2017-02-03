<?php
/*
*	The purpose of this command is to refresh the stock of species (by setting it to something random between 0 - # of players, in order of cost)
*/
$db->query("UPDATE species SET stock = 0 WHERE cost IS NOT NULL");							// Set all stock to 0 (if there is a cost)
$species = $db->query("SELECT id FROM species WHERE cost IS NOT NULL ORDER BY cost ASC");	// Grab the ID of each (cheapest first)
$plays = $db->query("SELECT COUNT(*) as count FROM users");									// Figure out how many pets we wanna spawn
$max = ceil($plays->fetch_assoc()['count']);
$sum = 0;

while (($s = $species->fetch_assoc()) && $sum < $max) {
	$stock = rand(0, ($max-$sum));
	$db->query("UPDATE species SET stock = {$stock} WHERE id = {$s['id']}");
	$sum += $stock;
}

echo "Successfully created {$sum} / {$max} possible species stock!\n";
