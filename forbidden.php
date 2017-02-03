<?php
    /*
    * This page displays the forbidden page. Users end up here if they try to access something they need to be logged in to see.
    */
    $access = "none";                   // Define access level
    include 'includes/before.php';      // Get initial boilerplate
    include 'includes/after.php';

    // Display the associated template
    $dir = dirname(__FILE__);
    $smarty->display("$dir/views/forbidden.tpl");
?>