<?php
    /*
    * This page displays the scum page. It displays a listing of everyone's scum points and what they are.
    */
    $access = "none";                   // Define access level
    include 'includes/before.php';      // Get initial boilerplate
    include 'includes/after.php';

    // Display the associated template
    $dir = dirname(__FILE__);
    $smarty->display("$dir/views/scum.tpl");
?>