<?php
    /*
    * This page displays the shop page.
    */
    $access = "user";                   // Define access level
    include 'includes/before.php';      // Get initial boilerplate

    /************************************   Grab available items   ************************************/
    $pets = $db->query("
        SELECT s.id, t.name as type, s.name, s.img, s.cost, s.basehp, s.baseatt, s.basedef, s.basehunger, s.flavour, s.stock
        FROM species s
        JOIN types t ON s.type = t.id
        WHERE s.cost IS NOT NULL
        ORDER BY s.type, s.cost ASC
    ");   
    if ($pets === false) {throw new Exception ($db->error);}               // If something went wrong
    $p_array = array();                                                    // Get ready for row data
    while ($row = $pets->fetch_assoc()) {                                  // Iterate over each question
        if (!isset($p_array[$row['type']]))
            $p_array[$row['type']] = array();
        $p = array();
        $p['id'] = $row['id'];
        $p['type'] = $row['type'];
        $p['name'] = $row['name'];
        $p['flavour'] = $row['flavour'];
        $p['img'] = $row['img'];
        $p['cost'] = $row['cost'];
        $p['basehp'] = $row['basehp'];
        $p['baseatt'] = $row['baseatt'];
        $p['basedef'] = $row['basedef'];
        $p['basehunger'] = $row['basehunger'];
        $p['stock'] = $row['stock'];
        array_push($p_array[$row['type']], $p);                            // And store the data into a result row
    }

    /***********************************   Assign Smarty Objects   ***********************************/

    include 'includes/after.php';

    // Pass all the user data shit to the view
    $smarty->assign('pets', $p_array);

    // Display the associated template
    $dir = dirname(__FILE__);
    $smarty->display("$dir/views/shop.tpl");
?>