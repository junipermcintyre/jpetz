<?php
    /*
    * This page displays the shop page.
    */
    $access = "user";                   // Define access level
    include 'includes/before.php';      // Get initial boilerplate

    /************************************   Grab available items   ************************************/
    $caps = $db->query("
        SELECT id, name, type, cost, pic
        FROM caps
        WHERE cost IS NOT NULL
    ");   
    if ($caps === false) {throw new Exception ($db->error);}    // If something went wrong
    $c_array = array();                                         // Get ready for row data
    while ($row = $caps->fetch_assoc()) {                       // Iterate over each question
        $c['id'] = $row['id'];
        $c['name'] = $row['name'];
        $c['type'] = $row['type'];
        $c['cost'] = $row['cost'];
        $c['pic'] = $row['pic'];
        array_push($c_array, $c);                               // And store the data into a result row
    }

    /***********************************   Assign Smarty Objects   ***********************************/

    include 'includes/after.php';

    // Pass all the user data shit to the view
    $smarty->assign('caps', $c_array);

    // Display the associated template
    $dir = dirname(__FILE__);
    $smarty->display("$dir/views/caps.tpl");
?>