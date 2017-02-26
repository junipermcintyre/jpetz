<?php
/*
*	The purpose of this command is to decrement the hunger/hp of all pets
*/

$db->query("UPDATE pets SET hp = (hp+1) WHERE hunger > 0 AND hp < maxhp");			// Add one health, for healthy pets (up to max)
$db->query("UPDATE pets SET alive = 0 WHERE hp <= 0");								// Kill pets with 0 health
$db->query("UPDATE pets SET hp = (hp-1) WHERE hunger <= 0 AND hp > 0");				// Remove health, as necessary
$db->query("UPDATE pets SET hunger = (hunger-1) WHERE alive = 1 AND hunger > 0");	// Remove hunger from pets

$db->next_result();

echo "Pet hunger, hp, and life successfully decremented!\n";
