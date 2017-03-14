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
        if ($pets === false) {throw new Exception ($db->error);}               // If something went wrong
        $p_array = array();                                                    // Get ready for row data
        while ($row = $pets->fetch_assoc()) {                                  // Iterate over each defending pet
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
            if (is_null($p['text']) || $p['text'] == "")
                $p['text'] = $row['flavour'];
            array_push($p_array, $p);                                          // And store the data into a result row
        }

        include 'includes/after.php';

        /***********************************   Complete view rendering   ***********************************/
        // Pass the defending pets data to the view
        $smarty->assign('pets', $p_array);

        // Display the associated template
        $dir = dirname(__FILE__);
        $smarty->display("$dir/views/raiddef.tpl");

    } else {                                // Show the raid attack screen
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
            t.name as type
            FROM pets p
            JOIN users u ON p.owner = u.id
            JOIN species s ON p.species = s.id
            JOIN types t ON s.type = t.id
            WHERE p.owner = {$id}
            AND p.alive = true
            AND p.defending = true"
        );   
        if ($dPets === false) {throw new Exception ($db->error);}              // If something went wrong
        $d_array = array();                                                    // Get ready for row data
        while ($row = $dPets->fetch_assoc()) {                                 // Iterate over each defending pet
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
            t.name as type
            FROM pets p
            JOIN users u ON p.owner = u.id
            JOIN species s ON p.species = s.id
            JOIN types t ON s.type = t.id
            WHERE p.owner = {$_SESSION['id']}
            AND p.alive = true
            AND p.busy = false"
        );   
        if ($aPets === false) {throw new Exception ($db->error);}              // If something went wrong
        $a_array = array();                                                    // Get ready for row data
        while ($row = $aPets->fetch_assoc()) {                                 // Iterate over each defending pet
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
            if (is_null($p['text']) || $p['text'] == "")
                $p['text'] = $row['flavour'];
            array_push($a_array, $p);                                          // And store the data into a result row
        }

        /***********************************   Complete view rendering   ***********************************/
        // Pass all the pets data to the view
        $smarty->assign('dPets', $dPets);
        $smarty->assign('aPets', $aPets);
        $smarty->display("$dir/views/raidatt.tpl");
    }
