<?php
    /*
    * This page displays the scum page. It displays a listing of everyone's scum points and what they are.
    */
    $access = "none";                   // Define access level
    include 'includes/before.php';      // Get initial boilerplate
    include 'includes/after.php';

    /*************************************** Grab scum data for leaderboard ***************************************/
    // We're gonna need a database connection - MySQLi time
	$db = connect_to_db();											// (hint - this function is in conf/db.php)

	// Step #1 - Let's get that user/scum data
    $result = $db->query("	SELECT u.name as name, r.name as role, u.scum_points, u.id, u.avatar
    						FROM users u JOIN roles r ON u.role = r.id ORDER BY scum_points DESC, role, u.name"
    );	
    if ($result === false) {throw new Exception ($dbh->error);}		// If something went wrong

    // Step #2 - build the result array
    $users = array();												// Get ready for row data
    while ($row = $result->fetch_assoc()) {							// Iterate over each user
    	$tmp = array();
    	$tmp['user'] = "<span><img class='scum-thumb' src='/images/avatars/{$row['avatar']}'></span><a href='/user.php?id={$row['id']}'>{$row['name']}</a>";
    	$tmp['role'] = $row['role'];
    	$tmp['sp'] = $row['scum_points'];
    	array_push($users, $tmp);									// And store the data into a result row
    }

    /*********************************** Grab top pet collector for leaderboard ***********************************/
    $result = $db->query("SELECT u.id, u.name, u.avatar, COUNT(DISTINCT p.species) as collection FROM users u JOIN pets p on u.id = p.owner WHERE p.alive = TRUE GROUP BY p.owner ORDER BY collection DESC");
    if ($result === false) {throw new Exception ($dbh->error);}		// If something went wrong

    // Step #2 - build the result array
    $collectors = array();											// Get ready for row data
    while ($row = $result->fetch_assoc()) {							// Iterate over each user
    	$tmp = array();
    	$tmp['user'] = "<span><img class='scum-thumb' src='/images/avatars/{$row['avatar']}'></span><a href='/user.php?id={$row['id']}'>{$row['name']}</a>";
    	$tmp['count'] = $row['collection'];
    	array_push($collectors, $tmp);								// And store the data into a result row
    }

    // Assign variables for template
    $smarty->assign('scum', $users);
    $smarty->assign('collectors', $collectors);

    // Display the associated template
    $dir = dirname(__FILE__);
    $smarty->display("$dir/views/scum.tpl");
?>