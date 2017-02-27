<?php
/*
*	The purpose of this script is to output what happened to the previous boss, and what happened to the rewards
*/
// Step #1 - Grab info on yesterdats raid boss
$boss = $db->query("
	SELECT b.id, u.name as owner, p.name as pet, b.maxhp, b.reward, b.bonus, b.beaten, b.killer
	FROM boss b
	JOIN pets p ON b.pet = p.id
	JOIN users u ON p.owner = u.id
	WHERE b.active = 0 ORDER BY b.id DESC LIMIT 1
");
if ($boss === false) {throw new Exception ($db->error);}									// Check query for errors
$b = $boss->fetch_object();																	// Grab the boss data
$db->next_result();

// Step #2 - Build first line of query
if ($b->beaten) {
	$content .= "**Yesterday's Raid Boss,** ***{$b->owner}'s {$b->pet},*** **was defeated by adventuring J-Petz!**\n";

	// Step #2a - Build the rewards table. Line 1 => total reward, line 2..x => dmg percentages + reward share, line x+1 => last hit bonus
	$content .= "```Reward pool: {$b->reward} SP\n";
	$shares = $db->query("
		SELECT u.name, SUM(dmg) as dmg FROM boss_dmg b
		JOIN users u ON b.owner = u.id
		WHERE boss = {$b->id}
		GROUP BY owner
		ORDER BY SUM(dmg) DESC;
	");
	if ($shares === false) {throw new Exception ($db->error);}

	while ($s = $shares->fetch_object()) {
		$name = str_pad("{$s->name}:", 20);
		$dmg = str_pad(ceil(($s->dmg / $b->maxhp) * 100).'%', 4).'-> ';
		$r = ceil(($s->dmg / $b->maxhp) * $b->reward);
		$content .= "{$name}{$dmg}{$r} SP\n";
	}

	// Step #2b - Get last hit info
	$hit = $db->query("
		SELECT u.name as owner, p.name as pet FROM boss b
		JOIN pets p ON b.killer = p.id
		JOIN users u ON p.owner = u.id
		where b.id = {$b->id}
	");
	if ($hit === false) {throw new Exception ($db->error);}
	$h = $hit->fetch_object();
	$db->next_result();
	$content .= "{$h->owner}'s {$h->pet} struck the killing blow for the {$b->bonus} SP bonus!```\n\n";
} else {
	// Step #2 - Build the only, first, line of the query
	$content .= "**Yesterday's Raid Boss,** ***{$b->owner}'s {$pet},*** **Went undefeated! They claim the {$b->bonus} SP bonus.**\n\n";
}

echo "Previous boss results sent!\n";														// All done! Inform self of success
