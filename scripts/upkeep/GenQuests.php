<?php
/*
*	The purpose of this command is to process the daily tick for generating new quests
*/

# Get max number of quests (2x # of pets)
# Set current = # of incomplete quests
# Get list of unbusy pets
# While there;s still pets and current < max
# 	Make a quest for that pet
#	Add 1 to current

// Get max amount and current amount of available quests
$res = $db->query("SELECT COUNT(*) AS count FROM pets WHERE alive = true");
$max = $res->fetch_assoc()['count'] * 2;
$db->next_result();
$res = $db->query("SELECT COUNT(*) AS count FROM quests WHERE complete = false");
$cur = $res->fetch_assoc()['count'];
$db->next_result();

// Get a list of un-busy pets
$sql = "
	SELECT
	p.att,
	p.def,
	s.type,
	p.species,
	s.name as speciesname,
	t.name as typename
	FROM pets p
	JOIN species s ON p.species = s.id
	JOIN types t ON s.type = t.id
	WHERE p.alive = true
	AND p.busy = false
";
$pets = $db->query($sql);

// Main genquests loop here
$total = 0;
while (($pet = $pets->fetch_assoc()) && $cur < $max) {
	makeQuest($db, $pet);
	$cur++;
	$total++;
}


echo "Quest generation complete! {$total} new quests ({$cur}/{$max}).\n";

// Helper functions
function makeQuest($db, $p) {
	// Create an empty quest object
	$quest = array(
		"reqatt" => null,
		"reqdef" => null,
		"reqtype" => null,
		"reqspecies" => null,
		"typename" => null,
		"speciesname" => null,
		"length" => null,
		"reward" => null,
		"title" => null,
		"description" => null
	);

	// Set attreq level
	if (fiftyfifty())
		$quest['reqatt'] = $p['att'];

	// Set defreq level
	if (fiftyfifty())
		$quest['reqdef'] = $p['def'];

	// Set typereq level
	if (fiftyfifty()) {
		$quest['reqtype'] = $p['type'];
		$quest['typename'] = $p['typename'];
	}

	// Set speciesreq level
	if (!is_null($quest['reqtype']) && fiftyfifty()) {
		$quest['reqspecies'] = $p['species'];
		$quest['speciesname'] = $p['speciesname'];
	}

	// Grab a random length
	$quest['length'] = rand(1, 10);

	// Generate the reward
	$quest['reward'] = calcReward($quest);

	// Grab a story for the quest (title and desc)
	$story = getStory($quest);
	if ($story) {
		$quest['title'] = $story['title'];
		$quest['description'] = $story['description'];
		// Insert the quest
		insertQuest($db, $quest);
	} else {
		echo "Could not create story for quest with data: ".print_r($quest, true);
	}
}

function fiftyfifty() {
	return (1 == rand(1,2));
}

function calcReward($q) {
	// Can safely add nulls
	$r = 10;
	$r += $q['reqatt'] + $q['reqdef'];
	if (!is_null($q['reqtype']))
		$r += 2;
	if (!is_null($q['reqspecies']))
		$r += 2;
	$r *= $q['length'];
	return $r;
}

function insertQuest($db, $q) {
	// Convert nulls to NULL string for MySQL insert
	if (is_null($q['reqatt']))
		$q['reqatt'] = "NULL";
	if (is_null($q['reqdef']))
		$q['reqdef'] = "NULL";
	if (is_null($q['reqtype']))
		$q['reqtype'] = "NULL";
	if (is_null($q['reqspecies']))
		$q['reqspecies'] = "NULL";
	
	// Build insertion string
	$sql = "INSERT INTO quests (reqatt, reqdef, reqtype, reqspecies, length, progress, reward, title, description, complete) VALUES
			({$q['reqatt']}, {$q['reqdef']}, {$q['reqtype']}, {$q['reqspecies']}, {$q['length']}, 0, {$q['reward']}, '{$q['title']}', '{$q['description']}', 0)";

	// Insert it!
	$db->query($sql);
}

function getStory($q) {
	// $book contains all possible stories (title + desc)
	// Book is separated by type, by species. If no type, use misc. If no species, use misc within type. Can assume if species exists, type exists (otherwise null)
	$book = array(
		"misc" => array(
			array(
					"title" => "Cook's Assistant",
					"description" => "The birthday party for the duke is tomorrow and a cake is required! Can you send a J-Pet to collect an egg, some milk, and some flour?"
				),
				array(
					"title" => "Sheep Shearer",
					"description" => "A local farmer is looking to hire a J-Pet to do some farm work."
				)
		),
		"csgo" => array(
			"Counter-Terrorist" => array(
				array(
					"title" => "Defuse the Bomb",
					"description" => "Where's the bomb? Where's the bomb? Where's the bomb? Where's the bomb? Where's the bomb? Where's the bomb? Where's the bomb?"
				),
				array(
					"title" => "Cover B Site",
					"description" => "Intel suggests a group of terrorists is rushing towards bomb site B. Send a J-Pet to defend it!"
				)
			),
			"Terrorist" => array(
				array(
					"title" => "Plant the Bomb",
					"description" => "Plant the bomb! Plant the bomb! Plant the bomb! Plant the bomb! Plant the bomb! Plant the bomb! Plant the bomb!Plant the bomb!"
				),
				array(
					"title" => "Rush B Site",
					"description" => "Hey you. Yeah you. Take this Tec-9 and run that way. Don't worry about it."
				)
			),
			"Camper" => array(
				array(
					"title" => "Win a Valve DM",
					"description" => "Word has gone around that some DMs are taking place. Sending a J-Pet to participate could result in a substantial reward."
				)
			),
			"Squeaker" => array(

			),
			"AWPer" => array(

			),
			"misc" => array(
				array(
					"title" => "Clutch the Round",
					"description" => "It's down to a 5v1, bomb planted. Can your J-Pet clutch the round?"
				)
			)
		),
		"meme" => array(
			"Business Owl" => array(
				array(
					"title" => "Avian Consulting Gig",
					"description" => "A Fortune-500 company is looking for a Business Owl to provide top-tier consulting services."
				)
			),
			"Cammy Pls" => array(
				array(
					"title" => "Dispose of Extra Beer",
					"description" => "You heard the man."
				)
			),
			"misc" => array(
				array(
					"title" => "Pioneer New Memes",
					"description" => "It appears there's a shortage of original memes on the internet today. Could your J-Pet help brainstorm some?"
				)
			)
		),
		"anime" => array(
			"misc" => array(
				
			)
		),
		"overwatch" => array(
			"misc" => array(
				
			)
		),
		"starcraft" => array(
			"misc" => array(
				
			)
		)
	);

	# Basically, try the following
	# If the type is null or not set in books, use misc
	# If the type isn't null and set,
	#	If the species is null or not set in books, use misc of the type
	#	If the species isn't null, use those

	if (is_null($q['typename']) || !isset($book[$q['typename']]) || empty($book[$q['typename']])) {
		$max = count($book['misc']) - 1;
		$index = rand(0, $max);
		return array("title" => $book['misc'][$index]['title'], "description" => $book['misc'][$index]['description']);
	} else {
		if (is_null($q['speciesname']) || !isset($book[$q['typename']][$q['speciesname']]) || empty($book[$q['typename']][$q['speciesname']])) {
			$max = count($book[$q['typename']]['misc']) - 1;
			$index = rand(0, $max);
			return array("title" => $book[$q['typename']]['misc'][$index]['title'], "description" => $book[$q['typename']]['misc'][$index]['description']);
		} else {
			$max = count($book[$q['typename']][$q['speciesname']]) - 1;
			$index = rand(0, $max);
			return array("title" => $book[$q['typename']][$q['speciesname']][$index]['title'], "description" => $book[$q['typename']][$q['speciesname']][$index]['description']);
		}
	}

	return false;	// Weird
}