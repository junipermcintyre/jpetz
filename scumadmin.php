<?php
    /*
    * This page displays the Scum Admin page. It's more or less a static page, so no Smarty objects need assigning.
    */
    $access = "mod";                    // Define access level
    include 'includes/before.php';      // Get initial boilerplate
    include 'includes/after.php';
    
    // Display the associated template
    $dir = dirname(__FILE__);
    $smarty->display("$dir/views/scumadmin.tpl");
?>