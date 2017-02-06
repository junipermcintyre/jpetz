<?php
/*
*	This file contains functions used to interact with the database and perform calculations in regards to J-Pets.
*	It can be required / included wherever these functions are required.
*
*	This file does NOT create or maintain any database connections on its own.
*	It assumes a $db will be passed as (usually) the first parameter to a given function.
*/


// Get a pet and return it
function getPet($db, $petId) {
	$sql = "SELECT
	p.id,
	p.name,
	p.owner,
	p.bio,
	p.att,
	p.def,
	p.busy,
	p.alive,
	p.hp,
	p.maxhp,
	p.hunger,
	p.maxhunger,
	p.actions,
	p.species,
	s.name,
	s.type,
	t.name
	FROM pets p
	JOIN species s ON p.species = s.id
	JOIN types t ON s.type = t.id
	WHERE p.id = ?";
	if (!$sth = $db->prepare($sql)){throw new Exception ("SQL ($sql) failed: ". $db->error);}
    if (!$sth->bind_param("i",$petId)) {throw new Exception ("Bind Param failed: ".__LINE__);}
    if (!$sth->bind_result($id, $name, $owner, $bio, $att, $def, $busy, $alive, $hp, $maxhp, $hunger, $maxhunger, $actions, $speciesId, $species, $typeId, $type)){throw new Exception ("Bind Result failed: ".__LINE__);}
    // Get a pet from database
    if (!$result = $sth->execute()){throw new Exception ("Execute failed: ".$db->error);}
    // Get results (only need to get one row, because pets are unique)
    $sth->fetch();
    $sth->free_result();
    $db->next_result();

    return array(
    	'id' => $id,
		'name' => $name,
		'owner' => $owner,
		'bio' => $bio,
		'att' => $att,
		'def' => $def,
		'busy' => $busy,
		'alive' => $alive,
		'hp' => $hp,
		'maxhp' => $maxhp,
		'hunger' => $hunger,
		'maxhunger' => $maxhunger,
		'actions' => $actions,
		'speciesId' => $speciesId,
		'species' => $species,
		'typeId' => $typeId,
		'type' => $type,
    );
}