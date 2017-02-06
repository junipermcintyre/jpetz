<?php
/*
*	The purpose of this to see who's got the most pet species, and add it to the news content
*/
/*********************************** Grab top pet collector for leaderboard ***********************************/
$result = $db->query("SELECT u.name, COUNT(DISTINCT p.species) as collection FROM users u JOIN pets p on u.id = p.owner WHERE p.alive = TRUE GROUP BY p.owner ORDER BY collection DESC LIMIT 5");
if ($result === false) {throw new Exception ($dbh->error);}		// If something went wrong

// Step #2 - build the result output
$content .= "**And here's the top five unique J-Petz species collectors:**\n```";
$place = 1;
while ($row = $result->fetch_object()) {
    $str = "{$place}. {$row->name}: ";
	$content .= str_pad($str, 20);
	$content .= "{$row->collection} [J-Petz]\n";
    $place++;
}

$content .= "```\nDo you wanna be the very best? Use your scum points to buy pets at http://jeradmcintyre.com/shop.php!\n\n";

echo "Top J-Pet collectors successfully added to content!\n";
