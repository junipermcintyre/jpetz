<?php
    /*
    * This page displays the home page. It's more or less a static page, so no Smarty objects need assigning.
    */
    $access = "none";                    // Define access level
    include 'includes/before.php';      // Get initial boilerplate
    include 'includes/after.php';
    
    // Display the associated template
    $dir = dirname(__FILE__);
    $smarty->display("{$dir}/views/contact.tpl");
?>