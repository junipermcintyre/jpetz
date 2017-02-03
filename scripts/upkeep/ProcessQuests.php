<?php
/*
*	The purpose of this command is to process the daily tick for each pet quest
*/

# Complete all quests where progress >= length
# Increment progress of quests with a hero value that are not complete
# For all quests where progress = length
	# Update the pet to be un-busy
	# Reward the reward to the hero

// Complete all completed quests
$db->query("UPDATE quests SET complete = true WHERE progress = length");

// Increment value of in-progress quests
$db->query("UPDATE quests SET progress = progress + 1 WHERE hero IS NOT NULL AND complete = false");

// For each quest that has just been completed
$qs = $db->query("SELECT hero, pet, reward FROM quests WHERE progress = length AND complete = false");
while ($q = $qs->fetch_assoc()) {
	// Free the pet
	$db->query("UPDATE pets SET busy = false WHERE id = {$q['pet']}");

	// Give rewards to user
	$db->query("UPDATE users SET scum_points = scum_points + {$q['reward']} WHERE id = {$q['hero']}");
}

echo "Successfully processed quests!\n";
