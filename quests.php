<?php
    /*
    *   This page displays the quests page.
    *   There are four possibilities for this page execution, depending on variants of isset and quest status
    *   'user' and 'pet'.
    *       1. Show the full quests page
    *       2. Show un-taken quest
    *       3. Show status of user who aint u quest
    *       4. Show status of user who is u for quest
    */

    $access = "user";
    include 'includes/before.php';          // Get initial boilerplate

    // If we want all quests display
    if (!isset($_GET['quest'])) {
        // Grab the available quests
        $available = $db->query("
            SELECT 
            q.id,
            COALESCE(q.reqatt, 'none') as reqatt,
            COALESCE(q.reqdef, 'none') as reqdef,
            q.reqtype,
            q.reqspecies,
            q.length,
            q.reward,
            q.title,
            q.description,
            COALESCE(s.name, 'none') as species,
            COALESCE(t.name, 'none') as type
            FROM quests q
            LEFT JOIN species s ON q.reqspecies = s.id
            LEFT JOIN types t ON q.reqtype = t.id
            WHERE hero IS NULL"
        );
        $aQuests = array();
        while ($q = $available->fetch_assoc()) {
            $tmp = array(
                'id' => $q['id'],
                'reqatt' => $q['reqatt'],
                'reqdef' => $q['reqdef'],
                'reqtype' => $q['reqtype'],
                'reqspecies' => $q['reqspecies'],
                'length' => $q['length'],
                'reward' => $q['reward'],
                'title' => $q['title'],
                'description' => $q['description'],
                'species' => $q['species'],
                'type' => $q['type']
            );
            array_push($aQuests, $tmp);
        }

        // Get available pets for each quest
        $aPets = array();
        foreach ($aQuests as $q)
        	$aPets[$q['id']] = getPets($db, $_SESSION['id'], $q);

        // Grab the in progress quests
        $progress = $db->query("
            SELECT 
            q.id,
            q.progress,
            COALESCE(q.reqatt, 'none') as reqatt,
            COALESCE(q.reqdef, 'none') as reqdef,
            q.reqtype,
            q.reqspecies,
            q.progress,
            q.length,
            q.reward,
            q.title,
            q.description,
            u.name as hero,
            p.name as pet,
            COALESCE(s.name, 'none') as species,
            COALESCE(t.name, 'none') as type,
            sp.img
            FROM quests q
            LEFT JOIN species s ON q.reqspecies = s.id
            LEFT JOIN types t ON q.reqtype = t.id
            JOIN pets p ON q.pet = p.id
            JOIN users u ON q.hero = u.id
            LEFT JOIN species sp ON p.species = sp.id
            WHERE hero IS NOT NULL
            AND u.id = {$_SESSION['id']}
            AND progress < length"
        );

        $pQuests = array();
        while ($q = $progress->fetch_assoc()) {
            $tmp = array(
                'id' => $q['id'],
                'progress' => $q['progress'],
                'reqatt' => $q['reqatt'],
                'reqdef' => $q['reqdef'],
                'progress' => $q['progress'],
                'percent' => ($q['progress'] / $q['length']) * 100,
                'reqtype' => $q['reqtype'],
                'reqspecies' => $q['reqspecies'],
                'length' => $q['length'],
                'reward' => $q['reward'],
                'title' => $q['title'],
                'description' => $q['description'],
                'hero' => $q['hero'],
                'pet' => $q['pet'],
                'species' => $q['species'],
                'type' => $q['type'],
                'img' => $q['img']
            );

            // Change some of the req's

            array_push($pQuests, $tmp);
        }

        $smarty->assign('available', $aQuests);
        $smarty->assign('pets', $aPets);
        $smarty->assign('progress', $pQuests);
        $display = "/views/questsfull.tpl";
    }

    include 'includes/after.php';

    $dir = dirname(__FILE__);
    $smarty->display("{$dir}{$display}");


    // Helper function for getting eligible pets for a specific user
    function getPets($db, $id, $quest) {
    	// SELECT p.id, p.name, p.hp, p.hunger FROM pets p WHERE p.alive = 1 AND p.busy = 0
    	// AND p.att >= $quest.reqatt
    	// AND p.def >= $quest.reqDef
    	// AND p.type = $quest.reqtype
    	// AND p.species = $quest.reqspecies
    	$sql = "SELECT p.id, p.name, p.hp, p.hunger, s.img FROM pets p JOIN species s ON p.species = s.id WHERE p.alive = 1 AND p.busy = 0 AND p.owner = {$id}";
    	if (!is_null($quest['reqatt']) && $quest['reqatt'] != "none")
    		$sql .= " AND p.att >= {$quest['reqatt']}";
    	if (!is_null($quest['reqdef']) && $quest['reqdef'] != "none")
    		$sql .= " AND p.def >= {$quest['reqdef']}";
    	if (!is_null($quest['reqtype']) && $quest['reqtype'] != "none")
    		$sql .= " AND s.type = {$quest['reqtype']}";
    	if (!is_null($quest['reqspecies']) && $quest['reqspecies'] != "none")
    		$sql .= " AND p.species = {$quest['reqspecies']}";

    	$p_result = $db->query($sql);

        if (!$p_result)
            throw new Exception($sql);

    	$pets = array();
    	while ($p = $p_result->fetch_assoc()) {
    		$tmp = array (
    			'id' => $p['id'],
    			'img' => $p['img'],
    			'name' => $p['name'],
    			'hp' => $p['hp'],
    			'hunger' => $p['hunger']
    		);
    		array_push($pets, $tmp);
    	}
    	$db->next_result();
    	return $pets;
    }
?>
