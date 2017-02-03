<?php
/*
*	The purpose of this to see which quests were completed, and and them to the bot content
*/

// Get all quests that were just completed
$quests = $db->query("
	SELECT
	u.name as hero,
	p.name as pet,
	q.length,
	q.reward,
	q.title
	FROM quests q JOIN users u ON q.hero = u.id JOIN pets p ON q.pet = p.id
	WHERE q.length = q.progress AND q.complete = false"
);

$output = "";
while ($q = $quests->fetch_assoc()) {
	$output .= "After {$q['length']} days, **{$q['hero']}**'s ***{$q['pet']}*** completed __'{$q['title']}'__ for {$q['reward']} scum points!\n";
}

if ($output != "") {
	$content .= "**Quest Log**\n";
	$content .= $output;
	$content .= "\nNeed to make some cash? Send your J-Petz on quests today!\n\n";
}

echo "Completed quests successfully added to content\n";
