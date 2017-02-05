<?php
    /*
    * This page displays the J-Pet graveyard page.
    */
    $access = "user";                   // Define access level
    include 'includes/before.php';      // Get initial boilerplate


    /***********************************   Grab dead pet data   **********************************/
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
        p.owner,
        u.name as ownerName,
        s.img,
        s.name as species,
        s.flavour,
        t.name as type
        FROM pets p
        JOIN users u ON p.owner = u.id
        JOIN species s ON p.species = s.id
        JOIN types t ON s.type = t.id
        WHERE p.alive = false"
    );   
    if ($pets === false) {throw new Exception ($db->error);}               // If something went wrong
    $p_array = array();                                                    // Get ready for row data
    while ($row = $pets->fetch_assoc()) {                                  // Iterate over each question
        $p = array();
        $p['id'] = $row['id'];
        $p['name'] = $row['name'];
        $p['hp'] = $row['hp'];
        $p['maxhp'] = $row['maxhp'];
        $p['att'] = $row['att'];
        $p['def'] = $row['def'];
        $p['hunger'] = $row['hunger'];
        $p['maxhunger'] = $row['maxhunger'];
        $p['owner'] = $row['owner'];
        $p['ownerName'] = $row['ownerName'];
        $p['img'] = $row['img'];
        $p['species'] = $row['species'];
        $p['type'] = $row['type'];
        $p['text'] = $row['bio'];
        if (is_null($p['text']) || $p['text'] == "")
            $p['text'] = $row['flavour'];
        array_push($p_array, $p);                                          // And store the data into a result row
    }

    include 'includes/after.php';

    /***********************************   Complete view rendering   ***********************************/
    // Pass all the pets data to the view
    $smarty->assign('pets', $p_array);

    // Display the associated template
    $dir = dirname(__FILE__);
    $smarty->display("$dir/views/graveyard.tpl");
