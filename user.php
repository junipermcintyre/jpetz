<?php
    /*
    * This page displays ta user profile page.
    */

    // Begin the login session!
    session_start();

    // Only allow access to the page if the user is logged in and moderator or higher, otherwise, go to the forbidden page
    if (!isset($_SESSION['email'])) {
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

    /*
    *   So basically, what we wanna do is get either:
    *       - the current user if not $_GET['id'] is assigned
    *       - the supplied user if a $_GET['id'] is assigned
    *   Then we're gonna grab a bunch of that users data and send it to the view
    */
    if (isset($_GET['id'])) {           // If we were supplied with an ID
        $id = $_GET['id'];              // Use that one
    } else {                            // If we WERENT
        $id = $_SESSION['id'];          // Use the current user's ID
    }

    // We're gonna need a database connection - MySQLi time
    $db = connect_to_db();                                          // (hint - this function is in conf/db.php)

    // Step #1 - Make sure the database connection is A+
    if ($db->connect_error) {
        throw new Exception ($db->connect_error);                   // We should probably catch this... somewhere
    }

    // Step #2 - Let's get that user data
    $sql = "SELECT u.name as name, u.summoner_id, u.steam_id, u.avatar, r.name as role, u.scum_points
            FROM users u JOIN roles r WHERE r.id = u.role AND u.id = ?";
    if (! $sth = $db->prepare($sql)){throw new Exception ("SQL ($sql) failed: ". $db->error);}
    if (! $sth->bind_param("i",$id)) {throw new Exception ("Bind Param failed: ".__LINE__);}
    if (! $sth->bind_result($name, $l_id, $s_id, $avatar, $role, $scumPoints)){throw new Exception ("Bind Result failed: ".__LINE__);}

    // Get a user from database
    if (!$result = $sth->execute()){throw new Exception ("Execute failed: ".$db->error);}

    // Get results (only need to get one row, because users are unique)
    $sth->fetch();
    
    // Create Smarty object
    $smarty = new Smarty;

    // Pass the DebugBarRenderer to the view
    $smarty->assign('debugbarRenderer', $debugbarRenderer);

    // Pass all the user data shit to the view
    $smarty->assign('name', $name);
    $smarty->assign('l_id', $l_id);
    $smarty->assign('s_id', $s_id);
    $smarty->assign('avatar', $avatar);
    $smarty->assign('role', $role);
    $smarty->assign('scumPoints', $scumPoints);
    
    // Display the associated template
    $smarty->display("$dir/views/user.tpl");
?>