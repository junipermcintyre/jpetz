<?php
    /*
    * This page displays the Admin panel page
    */

    // Begin the login session!
    session_start();

    // Only allow access to the page if the user is logged in and moderator or higher, otherwise, go to the forbidden page
    if (!isset($_SESSION['email']) || $_SESSION['role'] >= 3) {
        header("Location: /forbidden.php");
        die();
    }

    // Include all Composer dependencies
    require_once __DIR__ . '/vendor/autoload.php';

    // Get some database access up in here
    require_once __DIR__ . '/conf/db.php';

    // Load ENV variables from .env
    $dotenv = new Dotenv\Dotenv(__DIR__);
    $dotenv->load(); 

    // Include the PHP Debug bar object
    use DebugBar\StandardDebugBar;
    $debugbar = new StandardDebugBar();
    $debugbarRenderer = $debugbar->getJavascriptRenderer();
    
    // Include the Smarty Framework for templating
    $dir = dirname(__FILE__);
    require("$dir/smarty/libs/Smarty.class.php");

    // Get a count of unverified questions, to make a neat little bootstrap badge thing
    // We're gonna need a database connection - MySQLi time
    $db = connect_to_db();                                          // (hint - this function is in conf/db.php)

    // Step #1 - Make sure the database connection is A+
    if ($db->connect_error) {
        throw new Exception ($db->connect_error);                   // We should probably catch this... somewhere
    }

    // Step #2 - Let's get that question code
    $result = $db->query("SELECT COUNT(*) as count FROM questions WHERE active = 1 AND verified = 0 LIMIT 1");   
    if ($result === false) {throw new Exception ($db->error);}      // If somehing went wrong
    $count = $result->fetch_object()->count;

    $badge = "";
    if ($count > 0) {
        $badge = "<span class='tag tag-default tag-pill float-xs-right'>{$count}</span>";
    }
    
    // Create Smarty object
    $smarty = new Smarty;

    // Pass the DebugBarRenderer to the view
    $smarty->assign('debugbarRenderer', $debugbarRenderer);

    // Pass the badge HTML to the view
    $smarty->assign('unverifiedBadge', $badge);
    
    // Display the associated template
    $smarty->display("$dir/views/admin.tpl");
?>