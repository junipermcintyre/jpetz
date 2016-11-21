<?php
    /*
    * This page displays the scum page. It displays a listing of everyone's scum points and what they are.
    */

    // Begin the login session!
    session_start();

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
    $smarty->display("$dir/views/scum.tpl");
?>