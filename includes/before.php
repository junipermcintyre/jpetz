<?php
/*
*	This file contains code that runs before every single page load
*/
// Begin the login session!
session_start();

/***********************************   Include dependencies   ************************************/
// Include all Composer dependencies
require_once __DIR__ . '/../vendor/autoload.php';

// Get some database access up in here
require_once __DIR__ . '/../conf/db.php';

// Load ENV variables from .env
$dotenv = new Dotenv\Dotenv(__DIR__."/..");
$dotenv->load(); 


/***************************************   Connect to DB   ****************************************/
// We're gonna need a database connection - MySQLi time
$db = connect_to_db();                                          // (hint - this function is in conf/db.php)

// Step #1 - Make sure the database connection is A+
if ($db->connect_error) {
    throw new Exception ($db->connect_error);                   // We should probably catch this... somewhere
}


/********************************   Check for existing session   *********************************/
// Check and see if anyone should be authenticated via remember-me cookies
if (isset($_COOKIE['rememberme']) && !isset($_SESSION['id'])) {
	$cookie = explode(".", $_COOKIE['rememberme']);	// Grab cookie data
	$series = $cookie[0];
	$token = sha1($cookie[1]);

	$sessions = $db->query("SELECT user FROM login WHERE series = '{$series}' AND token = '{$token}'");	// See if any matches
	if ($s = $sessions->fetch_assoc()) {	// If there are
		$uId = $s['user'];
		// Get that users email and role
		$us = $db->query("SELECT email, role FROM users WHERE id = {$uId}");
		$u = $us->fetch_assoc();
		$_SESSION['email'] = $u['email'];	// Logged in!
    	$_SESSION['role'] = $u['role'];
    	$_SESSION['id'] = $uId;
	}
}


/********************************   Setup custom access levels   *********************************/
switch($access) {
	case "none":
		break;
	case "user":
		if (!isset($_SESSION['email'])) {
		    header("Location: /login.php?goto={$_SERVER['REQUEST_URI']}");
		    die();
		}
		break;
	case "mod":
		if (!isset($_SESSION['email']) || $_SESSION['role'] >= 3) {
	        header("Location: /forbidden.php");
	        die();
	    }
		break;
	case "admin":
		if (!isset($_SESSION['email']) || $_SESSION['role'] >= 2) {
	        header("Location: /forbidden.php");
	        die();
	    }
		break;
	case "auth":
		if (!isset($_GET['token'])) {
	        header("Location: /forbidden.php");
	        die();
	    }
	    break;
	case "visitor":
		if (isset($_SESSION['email'])) {
			header("Location: /index.php");
			die();
		}
}

// Include the PHP Debug bar object
/*use DebugBar\StandardDebugBar;
$debugbar = new StandardDebugBar();
$debugbarRenderer = $debugbar->getJavascriptRenderer();*/

// Include the Smarty Framework for templating
$dir = dirname(__FILE__);
require("$dir/../smarty/libs/Smarty.class.php");

// Create Smarty object
$smarty = new Smarty;

// Pass the DebugBarRenderer to the view
//$smarty->assign('debugbarRenderer', $debugbarRenderer);

/***********************************   Get user info for views   ************************************/
$usrInfo = null;
if (isset($_SESSION['id'])) {
	$usrRes = $db->query("SELECT u.name, u.email, u.scum_points, r.name as role, u.role as roleId FROM users u JOIN roles r ON u.role = r.id WHERE u.id = {$_SESSION['id']}");
	$row = $usrRes->fetch_assoc();
	$usrInfo = array(
		'id' => $_SESSION['id'],
		'name' => $row['name'],
		'scumPoints' => $row['scum_points'],
		'role' => $row['role'],
		'roleId' => $row['roleId']
	);
	$db->next_result();
}
$smarty->assign('usrInfo', $usrInfo);


/***********************   Check if the nighttime mode cookie is enabled!   ************************/
if (isset($_COOKIE['nighttime'])) {
	$smarty->assign('nighttime', $_COOKIE['nighttime']);
} else {
	setcookie('nighttime', false, time() + (10 * 365 * 24 * 60 * 60));
	$smarty->assign('nighttime', false);
}
