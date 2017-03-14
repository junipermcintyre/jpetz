<?php
/*
*	This file contains functions used to interact with the database and perform calculations in regards to J-Pet combat.
*	It can be required / included wherever these functions are required.
*
*	This file does NOT create or maintain any database connections on its own.
*	It assumes a $db will be passed as (usually) the first parameter to a given function.
*/


// Estimate damage
function estimateDamage($att, $def) {
	return max(floor($att - ceil($def)), 0);
}


// Estimate damage
function estimateBossDamage($att, $def) {
	return max(floor($att - ceil($def/10)), 0);
}


// Pet in this case refers to just the ID
function dealDamage($db, $pet, $dmg) {

}


// p1 and p2 are expected to contain associative array values with att, def, hp, etc...
function tradeDamage($db, $p1, $p2) {

}


// Specifically for fighting raid bosses. Returns true if boss died
function hitBoss($db, $pet, $boss) {
	// Estimate damage to boss and deal it
	$dmg = estimateBossDamage($pet['att'], $boss['def']);

	// Deal the damage to the boss
	$boss['hp'] -= $dmg;
	$db->query("UPDATE boss SET hp = hp - {$dmg} WHERE active = TRUE");

	// Allocate dealt damage to damage table (for tracking rewards)
	$exist = $db->query("SELECT id FROM boss_dmg WHERE boss = {$boss['id']} AND pet = {$pet['id']}");
	if ($exist->fetch_assoc()) {	// If the row exists
		// Update it
		$db->query("UPDATE boss_dmg SET dmg = dmg + {$dmg} WHERE boss = {$boss['id']} AND pet = {$pet['id']}");
	} else {
		// Create it
		$db->query("INSERT INTO boss_dmg (pet, owner, boss, dmg) VALUES ({$pet['id']}, {$pet['owner']}, {$boss['id']}, {$dmg})");
	}

	// Estimate damage to pet and deal it
	$dmg = estimateDamage($boss['att'], $pet['def']);
	$pet['hp'] -= $dmg;
	$db->query("UPDATE pets SET hp = hp - {$dmg} WHERE id = {$pet['id']}");

	// If the pet died, kill it
	if ($pet['hp'] <= 0)
		kill($db, $pet['id']);

	// If the RB died, set some stuff
	if ($boss['hp'] <= 0) {
		$db->query("UPDATE boss SET hp = 0, beaten = TRUE, killer = {$pet['id']}");
		return true;
	}

	return false;
}


// Terminate a pets life
function kill($db, $petId) {
	// When a pet dies, the following happens:
	// hp is set to 0, busy is set to 0, alive is set to 0
	$db->query("UPDATE pets SET hp = 0, busy = FALSE, alive = FALSE WHERE id = {$petId}");
	return true;
}