<?php
    /*
    * This page displays the manage page. It's limited to administrators and moderators. Many aspects of the site can be managed here.
    */

    // Begin the login session!
    session_start();

    // Only allow access to the page if the user is logged in, and is an administrator or moderator
    if (!isset($_SESSION['email'])) {
        header("Location: /forbidden.php");
        die();
    } elseif ($_SESSION['role'] != 1 && $_SESSION['role'] != 2) {
        header("Location: /denied.php");
        die();
    }

    // Include all Composer dependencies
    require_once __DIR__ . '/vendor/autoload.php';

    // Include the PHP Debug bar object
    use DebugBar\StandardDebugBar;
    $debugbar = new StandardDebugBar();
    $debugbarRenderer = $debugbar->getJavascriptRenderer();
    
    // Include the Smarty Framework for templating
    $dir = dirname(__FILE__);
    require("$dir/smarty/libs/Smarty.class.php");
    
    // Create Smarty object
    $smarty = new Smarty;

    // Pass the DebugBarRenderer to the view
    $smarty->assign('debugbarRenderer', $debugbarRenderer);
    
    // Display the associated template
    $smarty->display("$dir/views/manage.tpl");
?>