<?php
	/****************************************************************************
	*							LEAGUE CONTROLLER								*
	*****************************************************************************
	*	Author: 		J McIntyre												*
	*	Date updated:	May 19. 2016											*
	*	Purpose:		Serve as an AJAX controller for all queries to the 		*
	*					League of Legends API 									*
	*																			*
	****************************************************************************/
	// Load all Composer Dependencies
	require_once __DIR__ . '../../vendor/autoload.php';

	// Load ENV variables from .env
	$dotenv = new Dotenv\Dotenv(__DIR__.'/..');
	$dotenv->load();



	/****************************************************************************
	*					ROUTING TABLE FOR JQUERY AJAX CALLS 					*
	****************************************************************************/
	/*
	*	This table takes the form of a switch statement which reads the 'action'
	*	index of the $_POST data. Depending on the action, a specific function is
	*	called. In this way, we map action strings to specific controller actions.
	*
	*	We can assume that each function / controller action called will return
	*	the necessary data in JSON.
	*/

	switch($_POST['action']) {
		case 'initialLoad':							// Loads initial data for page and sends it back to client
			echo initialLoad($_POST['players']);	// We can expect an array of players being sent via POST
			break;
		default:
			break;
	}
	// SCRIPT EXECUTION SHOULD COMPLETE HERE



	/****************************************************************************
	*						LEAGUE CONTROLLER ACTION FUNCTIONS 					*
	****************************************************************************/
	// Each function is mapped to one of the controller actions listed above


	/****************************** INITIAL LOAD ********************************
	*---------------------------------------------------------------------------*
	*	This function is called on the initial League stats page - queries 		*
	*	the Riot API for the passed usernames, and sends back some cool data 	*
	*	in JSON																	*
	****************************************************************************/
	function initialLoad($players) {
		$ch = curl_init();								// We're gonna hit the Riot API via cURL, so let's build an object
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);	// DON'T output returned results to page
		$player_data = array();		// Gotta hold that player data somewhere
		foreach($players as $player) {
			// We want to build the query URL for each player - No spaces or caps!
			$player_url = "https://na.api.pvp.net/api/lol/na/v1.4/summoner/by-name/".strtolower(str_replace(" ", "",$player))."?api_key=".getenv("LEAGUE_KEY");
			curl_setopt($ch, CURLOPT_URL, $player_url);
			$player_info = json_decode(curl_exec($ch), true);	// It comes in JSON form already, so we have to decode it before recoding it

			// We want to get each players summary as well, so we perform a second query
			// We then push the summary onto the data that will be returned
			$player_info[$player]['summary'] = getPlayerSummary($player_info[$player]['id']);

			array_push($player_data, $player_info[$player]);	// Tack on the player's data to the key / value array
		}
		curl_close($ch);					// Make sure we close cURL. Always.
		return json_encode($player_data);	// Send back JSON to the client
	}



	/****************************************************************************
	*								HELPER FUNCTIONS 							*
	****************************************************************************/
	// These are called within the controller actions, and do not need to return JSON


	/*************************** GET PLAYER SUMMARY *****************************
	*---------------------------------------------------------------------------*
	*	This function takes in a summoner ID, and returns an array representing	*
	*	a summary of the user's stats											*
	****************************************************************************/
	function getPlayerSummary($summonerId){
		$ch = curl_init();														// We're gonna hit the Riot API via cURL, so let's build an object
		$summary_url = "https://na.api.pvp.net/api/lol/na/v1.3/stats/by-summoner/".$summonerId."/summary?api_key=".getenv("LEAGUE_KEY");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);							// DON'T output returned results to page
		curl_setopt($ch, CURLOPT_URL, $summary_url);
		$player_summary = json_decode(curl_exec($ch), true);					// It comes in JSON, so we need to decode

		// Data comes in with game modes in an array of random order. We need to convert these to associative
		$i = 0;
		foreach($player_summary["playerStatSummaries"] as $gamemode) {
			$player_summary["playerStatSummaries"][$gamemode["playerStatSummaryType"]] = $player_summary["playerStatSummaries"][$i];
			unset($player_summary["playerStatSummaries"][$i]);
			$i++;
		}

		return $player_summary["playerStatSummaries"];							// The return object also comes with the summonerId, but we can assume we already have it
	}
?>