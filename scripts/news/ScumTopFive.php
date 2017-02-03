<?php
	/*
	*	The purpose of this script is to output the top five scum stats to the Green Gaming Discord channel using webhooks & JSON data
	*/
    // Step #1 - Set up and run a query get all the scum stats
    $scum = $db->query("SELECT u.name as name, r.name as role, u.scum_points, u.id
        				FROM users u JOIN roles r ON u.role = r.id ORDER BY scum_points DESC, role, u.name LIMIT 5");		
    if ($scum === false) {throw new Exception ($db->error);}							// If somehing went wrong

    $content .= "**Here's our Scum Points top five for today:**\n```";
    $place = 1;
    while ($row = $scum->fetch_object()) {												// Iterate over each user's scum data
        $str = "{$place}. {$row->name}: ";
    	$content .= str_pad($str, 20);
    	$content .= "{$row->scum_points}\n";
        $place++;
	}

    $content .= "```\nInterested in a spot on top? Ask some questions, or buy pets and send them on quests!\n\n";

    echo "Scum top five has been added to content!\n";							       // All done! Inform self of success
?>