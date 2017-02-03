<?php
    /*
    * This page displays the reset password form
    */
    $access = "none";                   // Define access level
    include 'includes/before.php';      // Get initial boilerplate
    include 'includes/after.php';

    // Display the associated template
    $dir = dirname(__FILE__);
    $smarty->display("$dir/views/passwordreset.tpl");
?>