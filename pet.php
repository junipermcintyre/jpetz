<?php
    /*
    * This page displays the pet page.
    */
    $access = "user";                   // Define access level
    include 'includes/before.php';      // Get initial boilerplate

    /*
    *   There are four possibilities for this page execution, depending on variants of isset for two variables
    *   'user' and 'pet'.
    *       1. Neither set - show current users pets
    *       2. Only user set - show users pets
    *       3. Only pet set - show edit version of pet screen
    *       4. Both set - show non-edit version of pet screen
    */

    if (!isset($_GET['user'])) {            // Set what user ID we'll be using
        $id = $_SESSION['id'];
        $qry = "";
    } else {
        $id = $_GET['user'];
        $qry = "&user={$id}";
    }

    if(!isset($_GET['pet'])) {              // Show full pet page (assume ID is set from previous)
        /***************************************   Get user's name   **************************************/
        $name = $db->query("SELECT name FROM users WHERE id = {$id}");
        $name = $name->fetch_assoc();
        $name = $name['name'];
        $db->next_result();

        /***********************************   Grab available pet data   **********************************/
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
            JOIN rarity r ON s.rarity = r.id
            JOIN types t ON s.type = t.id
            WHERE p.owner = {$id}
            AND p.alive = true
            AND p.busy = false"
        );   
        if ($pets === false) {throw new Exception ($db->error);}                // If something went wrong
        $p_array = array();                                                     // Get ready for row data
        $hungerTtl = 0;                                                         // Keep track of total missing hunger
        while ($row = $pets->fetch_assoc()) {                                   // Iterate over each pet
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
            array_push($p_array, $p);                                           // And store the data into a result row
            $hungerTtl += ($p['maxhunger'] - $p['hunger']);                     // Add to total missing hunger
        }

        /***************************************   Get user's name   **************************************/
        $name = $db->query("SELECT name FROM users WHERE id = {$id}");
        $name = $name->fetch_assoc();
        $name = $name['name'];
        $db->next_result();

        /**************************************   Grab BUSY pet data   ************************************/
        $bPets = $db->query("
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
            JOIN rarity r ON s.rarity = r.id
            JOIN types t ON s.type = t.id
            WHERE p.owner = {$id}
            AND p.alive = true
            AND p.busy = true"
        );   
        if ($bPets === false) {throw new Exception ($db->error);}               // If something went wrong
        $b_array = array();                                                     // Get ready for row data
        while ($row = $bPets->fetch_assoc()) {                                  // Iterate over each question
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
            array_push($b_array, $p);                                          // And store the data into a result row
        }

        include 'includes/after.php';

        /***********************************   Complete view rendering   ***********************************/
        // Pass all the pets data to the view
        $smarty->assign('qry', $qry);
        // Pass all the pets data to the view
        $smarty->assign('name', $name);
        // Pass all the pets data to the view
        $smarty->assign('pets', $p_array);
        // Pass busy pets data to the view
        $smarty->assign('bpets', $b_array);
        // Pass count of hunger missing to view
        $smarty->assign('hunger', $hungerTtl);

        // Display the associated template
        $dir = dirname(__FILE__);
        $smarty->display("$dir/views/petfull.tpl");

    } else {                                // Show single pet screen
        /****************************************   Grab pet data   ***************************************/
        $pet = $db->query("
            SELECT
            p.owner,
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
            p.alive,
            p.busy,
            t.name as type,
            p.actions
            FROM pets p
            JOIN users u ON p.owner = u.id
            JOIN species s ON p.species = s.id
            JOIN types t ON s.type = t.id
            WHERE p.id = {$_GET['pet']}");   

        if ($pet === false) {throw new Exception ($db->error);}               // If something went wrong
        $row = $pet->fetch_assoc();

        // Handle pet not existing
        if (is_null($row)) {
            echo "This J-Pet doesn't exist! Don't go putting random IDs in the URL!!";
            return;
        }

        // Handle pet being busy
        if ($row['busy'] == 1) {
            echo "This J-Pet is busy! Don't try to subvert game rules!";
            return;
        }

        if (!$row['alive']) {
        	echo "The {$row['species']}, {$row['name']} has died! Our condolences.";
        	return;
        }

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
        $p['actions'] = $row['actions'];
        if (is_null($p['text']) || $p['text'] == "")
            $p['text'] = $row['flavour'];
        $owner = $row['owner'];
        $db->next_result();

        /***************************************   Get user's name   **************************************/
        $sql = "SELECT name FROM users WHERE id = {$owner}";
        $name = $db->query("SELECT name FROM users WHERE id = {$owner}");
        $name = $name->fetch_assoc();
        $name = $name['name'];
        
        include 'includes/after.php';
        $dir = dirname(__FILE__);

        /***********************************   Complete view rendering   ***********************************/
        // Pass all the pets data to the view
        $smarty->assign('name', $name);
        // Pass all the pets data to the view
        $smarty->assign('pet', $p);

        if ($row['owner'] != $_SESSION['id']) {             // For view version
            /***********************************   Complete view rendering   ***********************************/
            // Display the associated template
            $smarty->display("$dir/views/petview.tpl");
        } else {                                // For edit version
            /***********************************   Complete view rendering   ***********************************/
            // Display the associated template
            $smarty->display("$dir/views/petedit.tpl");
        }
    }

?>
