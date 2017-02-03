<?php
    /*
    * This page displays the login form. It's more or less a static page, so no Smarty objects need assigning.
    */
    $access = "visitor";                // Define access level
    include 'includes/before.php';      // Get initial boilerplate

    $gt = "";
    if (isset($_GET['goto']))
    	$gt = $_GET['goto'];

    include 'includes/after.php';

    // Display the associated template
    $dir = dirname(__FILE__);
    $smarty->assign('gt', $gt);
    $smarty->display("$dir/views/login.tpl");
?>