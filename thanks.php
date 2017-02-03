<?php
    /*
    * This page displays the THANKS GUYS page.
    */
    $access = "none";                   // Define access level
    include 'includes/before.php';      // Get initial boilerplate
    include 'includes/after.php';
       
    $dir = dirname(__FILE__);
	$smarty->display("$dir/views/thanks.tpl");