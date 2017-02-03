<?php
    /*
    * This page logs out the user
    */
    $access = "user";                   // Define access level
    include 'includes/before.php';      // Get initial boilerplate

    session_destroy();					// Remove login session
    setcookie("rememberme", "", time() - 3600); // Remove login cookies

    include 'includes/after.php';

    header("Location: /index.php");
	die();
?>