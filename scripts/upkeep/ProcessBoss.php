<?php
/*
*	The purpose of this script is to see if the Raid Boss is alive, or dead. If alive - award points to boss owner. If dead, award points to fighters + bonus to last hit
*/

// Step #1 - See if the boss is dead or not
$res = $db->query("SELECT id, pet, date, maxhp, hp, att, def, reward, bonus, active, beaten, killer FROM boss WHERE active = TRUE");
$boss = $res->fetch_assoc();
$db->next_result();

// Only do the following if a boss exists
if ($boss) {
// Step #2A - If it is dead, award points to fighters
	if ($boss['beaten']) {
		pointsToFighters($db, $boss);
	}

	// Step #2B - If it is alive, award points to owner
	if (!$boss['beaten']) {
		pointsToOwner($db, $boss);
	}
}

// Step 3 - Make new boss
newBoss($db, $boss);

echo "Boss processing successfully completed!\n";

/**********************************  Helper Functions  **********************************/
function pointsToFighters($db, $b) {
	// See how much damage everyone did to the boss
	$res = $db->query("SELECT owner, SUM(dmg) as dmg FROM boss_dmg WHERE boss = {$b['id']} GROUP BY owner");
	$dmg = $res->fetch_all(MYSQLI_ASSOC);
	$db->next_result();

	// Award points to users
	foreach ($dmg as $d) {
		$share = max(ceil(($d['dmg'] / $b['maxhp']) * $b['reward']), 10);	// 10 being the minimum payout (for anyone who hit it)
		$db->query("UPDATE users SET scum_points = scum_points + {$share} WHERE id = {$d['owner']}");
		$db->next_result();
	}

	// Find ID of last-hitter
	$res = $db->query("SELECT u.id FROM users u JOIN pets p ON p.owner = u.id JOIN boss b ON b.killer = p.id WHERE b.id = {$b['id']}");
	$killer = $res->fetch_assoc();
	$db->next_result();

	// Give em the bonus points
	$db->query("UPDATE users SET scum_points = scum_points + {$b['bonus']} WHERE id = {$killer['id']}");
	$db->next_result();
}


function pointsToOwner($db, $b) {
	// Just give the whole reward to the pet owner
	$res = $db->query("SELECT u.id FROM users u JOIN pets p ON p.owner = u.id JOIN boss b ON b.pet = p.id WHERE b.id = {$b['id']}");
	$owner = $res->fetch_assoc();
	$db->next_result();

	// Give em the bonus
	$db->query("UPDATE users SET scum_points = scum_points + {$b['bonus']} WHERE id = {$owner['id']}");
	$db->next_result();
}


function newBoss($db, $b) {
	// Set the current boss to INactive
	$db->query("UPDATE boss SET active = FALSE WHERE id = {$b['id']}");
	$db->next_result();

	// Pick a new, random boss from all pets that are alive
	$res = $db->query("SELECT id, maxhp, att, def FROM pets WHERE alive = TRUE");
	$pets = $res->fetch_all(MYSQLI_ASSOC);
	$db->next_result();
	$rn = rand(0, count($pets)-1);
	$pet = $pets[$rn];

	// Determine whether this should be an easy, medium, hard, or extra hard raid boss
	$difficulties = array("easy", "medium", "hard", "extreme");
	$rn = rand(0, count($difficulties)-1);
	$difficulty = $difficulties[$rn];

	// Calculate new pet stats based on difficulty
	if ($difficulty = "easy") {
		$att = $pet['att'] * 5;
		$def = $pet['def'] * 5;
		$maxhp = $pet['maxhp'] * 8;
		$hp = $maxhp;
		$reward = 300;
		$bonus = 80;
	} else if ($difficulty = "medium") {
		$att = $pet['att'] * 9;
		$def = $pet['def'] * 9;
		$maxhp = $pet['maxhp'] * 12;
		$hp = $maxhp;
		$reward = 500;
		$bonus = 120;
	} else if ($difficulty = "hard") {
		$att = $pet['att'] * 15;
		$def = $pet['def'] * 15;
		$maxhp = $pet['maxhp'] * 17;
		$hp = $maxhp;
		$reward = 750;
		$bonus = 250;
	} else if ($difficulty = "extreme") {
		$att = $pet['att'] * 20;
		$def = $pet['def'] * 20;
		$maxhp = $pet['maxhp'] * 25;
		$hp = $maxhp;
		$reward = 1200;
		$bonus = 350;
	}

	// Create today's boss
	$date = date("Y-m-d");
	$db->query("INSERT INTO boss (pet, date, maxhp, hp, att, def, reward, bonus, active, beaten, killer)
				VALUES ({$pet['id']}, '{$date}', {$maxhp}, {$hp}, {$att}, {$def}, {$reward}, {$bonus}, TRUE, FALSE, NULL)");
}
