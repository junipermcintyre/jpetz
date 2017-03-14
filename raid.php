<?php
    /*
    * This page displays the user raid (not raid boss) page.
    */
    $access = "user";                   // Define access level
    include 'includes/before.php';      // Get initial boilerplate

    /*
    *   There are two possibilities for this page, depending on what the user parameter. Display the user's raid page, or the attack page.
    */

    if (!isset($_GET['user']))          // Set what user ID we'll be using
        $id = $_SESSION['id'];
    else
        $id = $_GET['user'];

    if(!isset($_GET['user']) || $id == $_SESSION['id']) {             // Show static info / defence page for current user
        /***********************************   Grab defending pet data   **********************************/
        $pets = $db->query("
            SELECT
            p.id,
            p.name,
            p.bio,
            p.hp,
            p.maxhp,
            p.att,
            p.def,
            p.hunger,
            p.maxhunger,
            s.img,
            s.name as species,
            s.flavour,
            t.name as type,
            r.name as rarity
            FROM pets p
            JOIN users u ON p.owner = u.id
            JOIN species s ON p.species = s.id
            JOIN types t ON s.type = t.id
            JOIN rarity r ON s.rarity = r.id
            WHERE p.owner = {$id}
            AND p.alive = true
            AND p.defending = true"
        );   
        if ($pets === false) {throw new Exception ($db->error);}                // If something went wrong
        $p_array = array();                                                     // Get ready for row data
        $defTtl = 0;                                                            // Track total defence
        while ($row = $pets->fetch_assoc()) {                                   // Iterate over each defending pet
            $p = array();
            $p['id'] = $row['id'];
            $p['name'] = $row['name'];
            $p['hp'] = $row['hp'];
            $p['maxhp'] = $row['maxhp'];
            $p['att'] = $row['att'];
            $p['def'] = $row['def'];
            $p['hunger'] = $row['hunger'];
            $p['maxhunger'] = $row['maxhunger'];
            $p['img'] = $row['img'];
            $p['species'] = $row['species'];
            $p['type'] = $row['type'];
            $p['text'] = $row['bio'];
            $p['rarity'] = $row['rarity'];
            $defTtl += $p['def'];
            if (is_null($p['text']) || $p['text'] == "")
                $p['text'] = $row['flavour'];
            array_push($p_array, $p);                                          // And store the data into a result row
        }

        include 'includes/after.php';

        /***********************************   Complete view rendering   ***********************************/
        // Pass the defending pets data to the view
        $smarty->assign('pets', $p_array);
        $smarty->assign('def', $defTtl);

        // Display the associated template
        $dir = dirname(__FILE__);
        $smarty->display("$dir/views/raiddef.tpl");

    } else {                                // Show the raid attack screen
        /**********************   Handle cases where a user can't raid another user   *********************/
        $dUser = $db->query("SELECT u.name, u.scum_points, COUNT(p.owner) as pets FROM users u LEFT JOIN pets p ON u.id = p.owner WHERE u.id = {$id} GROUP BY u.id");
        $dUser = $dUser->fetch_assoc();
        $name = $dUser['name'];
        $dPoints = $dUser['scum_points'];
        $dPets = $dUser['pets'];
        $db->next_result();

        $aUser = $db->query("SELECT u.scum_points, COUNT(p.owner) as pets FROM users u LEFT JOIN pets p ON u.id = p.owner WHERE u.id = {$_SESSION['id']} GROUP BY u.id");
        $aUser = $aUser->fetch_assoc();
        $aPoints = $aUser['scum_points'];
        $aPets = $aUser['pets'];
        $db->next_result();

        // Validate if raiding is OK
        $rFlag = true;
        if ($aPoints > $dPoints + 300)                          // If the attacker has 300 or more scum points than defender
            $rFlag = false;
        if ($aPets - $dPets > min($dPets, $aPets) * 0.25)       // If the attacker has more pets (with some leeway)
            $rFlag = false;

        /***********************************   Grab defending pet data   **********************************/
        $dPets = $db->query("
            SELECT
            p.id,
            p.name,
            p.bio,
            p.hp,
            p.maxhp,
            p.att,
            p.def,
            p.hunger,
            p.maxhunger,
            s.img,
            s.name as species,
            s.flavour,
            t.name as type,
            r.name as rarity
            FROM pets p
            JOIN users u ON p.owner = u.id
            JOIN species s ON p.species = s.id
            JOIN types t ON s.type = t.id
            JOIN rarity r ON s.rarity = r.id
            WHERE p.owner = {$id}
            AND p.alive = true
            AND p.defending = true"
        );   
        if ($dPets === false) {throw new Exception ($db->error);}               // If something went wrong
        $d_array = array();                                                     // Get ready for row data
        $defTtl = 0;                                                            // Track total defence
        while ($row = $dPets->fetch_assoc()) {                                  // Iterate over each defending pet
            $p = array();
            $p['id'] = $row['id'];
            $p['name'] = $row['name'];
            $p['hp'] = $row['hp'];
            $p['maxhp'] = $row['maxhp'];
            $p['att'] = $row['att'];
            $p['def'] = $row['def'];
            $p['hunger'] = $row['hunger'];
            $p['maxhunger'] = $row['maxhunger'];
            $p['img'] = $row['img'];
            $p['species'] = $row['species'];
            $p['type'] = $row['type'];
            $p['text'] = $row['bio'];
            $p['rarity'] = $row['rarity'];
            $defTtl += $p['def'];
            if (is_null($p['text']) || $p['text'] == "")
                $p['text'] = $row['flavour'];
            array_push($d_array, $p);                                          // And store the data into a result row
        }

        /***********************************   Grab attacking pet data   **********************************/
        $aPets = $db->query("
            SELECT
            p.id,
            p.name,
            p.bio,
            p.hp,
            p.maxhp,
            p.att,
            p.def,
            p.hunger,
            p.maxhunger,
            s.img,
            s.name as species,
            s.flavour,
            t.name as type,
            r.name as rarity
            FROM pets p
            JOIN users u ON p.owner = u.id
            JOIN species s ON p.species = s.id
            JOIN types t ON s.type = t.id
            JOIN rarity r ON s.rarity = r.id
            WHERE p.owner = {$_SESSION['id']}
            AND p.alive = true
            AND p.busy = false
            AND p.actions >= 5"
        );   
        if ($aPets === false) {throw new Exception ($db->error);}               // If something went wrong
        $a_array = array();                                                     // Get ready for row data
        $attTtl = 0;                                                            // Track total attack
        while ($row = $aPets->fetch_assoc()) {                                  // Iterate over each defending pet
            $p = array();
            $p['id'] = $row['id'];
            $p['name'] = $row['name'];
            $p['hp'] = $row['hp'];
            $p['maxhp'] = $row['maxhp'];
            $p['att'] = $row['att'];
            $p['def'] = $row['def'];
            $p['hunger'] = $row['hunger'];
            $p['maxhunger'] = $row['maxhunger'];
            $p['img'] = $row['img'];
            $p['species'] = $row['species'];
            $p['type'] = $row['type'];
            $p['text'] = $row['bio'];
            $p['rarity'] = $row['rarity'];
            $attTtl += $p['att'];
            if (is_null($p['text']) || $p['text'] == "")
                $p['text'] = $row['flavour'];
            array_push($a_array, $p);                                          // And store the data into a result row
        }

        /***********************************   Complete view rendering   ***********************************/
        // Pass all the pets data to the view
        $smarty->assign('dPets', $d_array);
        $smarty->assign('aPets', $a_array);
        $smarty->assign('def', $defTtl);
        $smarty->assign('att', $attTtl);
        $smarty->assign('rFlag', $rFlag);
        $smarty->assign('name', $name);
        $dir = dirname(__FILE__);
        $smarty->display("$dir/views/raidatt.tpl");
    }
