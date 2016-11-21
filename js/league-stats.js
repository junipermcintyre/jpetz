/**
 * This file contains the specific JavaScript code for the League Stats page.
**/

/********************************************************************************
*					  DEFAULT VARIABLES AND SETTINGS							*
*********************************************************************************
*	This page allows the League of Legends API to be queried for different		*
*	players, and will require some settings to be stored during runtime.		*
*																				*
********************************************************************************/
var players = ["andwhatnot", "hurricanelasers", "tunagun", "friendlytaco", "canislegion"];



/********************************************************************************
*					  AJAX CALLS FOR API INTERACTION							*
*********************************************************************************
*	All AJAX calls made will be to the League Controller at						*
*	controllers/leaguecontroller. Calls will pass any necessary data, and		*
*	a specific 'action', which maps to a function in the controller.			*
*	The controller will handle all interaction with the Riot API.				*
*																				*
*********************************************************************************


***************************** INITIAL LOAD **************************************
*-------------------------------------------------------------------------------*
*	This function is called when the page has completed loading. It filles all	*
*	data with the queried values, based on default arguments.					*
********************************************************************************/
$(document).ready(function() {
	// Make the API call to the controller with the 'initialLoad' action. Send in a list of usernames
    $.post(
        "/controllers/leaguecontroller.php",		        // Controller to send to
    	{action: 'initialLoad', players: players},	        // Action to run + additional arguments
    	function(data){            
            // console.log("Data from League Controller:"); // Uncomment these two lines for debug info
            // console.log(data);
            var chartDiv = $("#charts");            // Get the div the charts are in
            // The AJAX call was successful in this brace
            try {                                               // There's a fair chance we get undefined back
                var player_data = $.parseJSON(data);            // Take the player JSON data and build JS object
            } catch(err) {
                chartDiv.html('<h2>League of Legends API Exhausted!</h2><p>Try again in a few minutes <sup>hurry up and implement database ID caching...</sup></p>');
                return false;   // Quit out, after informing of error
            }

            // Build a player box and append it to #player-boxes foreach player
            $.each(player_data, function(index, player) {
                console.log(player);
                $("#player-boxes").append(buildPlayerBox(player));
            });

            chartDiv.html('');                      // Everything went well? Kill the CSS Spinner
            buildChart(player_data, chartDiv);      // Build a chart.js and set it
    	}
    ).fail(function(err, status){
        // The AJAX call was unsuccessful here
        alert("Something broke! Error code: 1")
        console.log(err);
        console.log(status);
        $("#charts").html('');              // Things went to shit? Kill the CSS Spinner
    });
});



/********************************************************************************
*                     HELPER FUNCTIONS FOR JS PROCESSES                         *
*********************************************************************************
*   Any functions or processes that will be called in loops / repeatedly        *
*   are defined here. These are unique to the League Stats page                 *
*                                                                               *
*********************************************************************************


***************************** BUILD PLAYER BOX **********************************
*-------------------------------------------------------------------------------*
*   This function takes the passed player object and build a display BOX        *
*   containing their data                                                       *
********************************************************************************/
function buildPlayerBox(player) {
    /*
    *   The basic outline should result in the following container HTML, with inner elements
    *   <div class="main-container container">
    *       <h2>Xx_$layer_God_420_xX</h2>
    *       <div class="row">
    *        
    *       </div>
    *   </div>
    */
    /*var boxHTML =   "<div class='main-container container'>" +
                        "<h2>"+player.name+"</h2>" +
                        "<div class='row'>" +
                            "<div class='col-md-2 col-sm-4 col-xs-4'>" +
                                "<img class='img-fluid img-rounded' src='http://ddragon.leagueoflegends.com/cdn/6.9.1/img/profileicon/" + player.profileIconId + ".png'>" +
                            "</div>" +
                            "<div class='col-md-10 col-sm-8 col-xs-8'>" +
                                "<p>Player level: " + player.summonerLevel + "</p>" +
                            "</div>" +
                        "</div>" +
                    "</div>";
    return boxHTML;*/


    var cardHTML =   "<div class='card'>" +
                        "<img class='card-img-top' src='http://ddragon.leagueoflegends.com/cdn/6.9.1/img/profileicon/" + player.profileIconId + ".png' alt='Player icon'>" +
                        "<div class='card-block'>" +
                            "<h4 class='card-title'>"+player.name+"</h4>" +
                            /*"<p class='card-text'>Some quick example text to build on the card title and make up the bulk of the card's content.</p>" +*/
                        "</div>" +
                        "<ul class='list-group list-group-flush'>" +
                            "<li class='list-group-item'>Lvl:"+player.summonerLevel+"</li>" +
                        "</ul>" +
                        /*"<div class='card-block'>" +
                            "<a href='#'' class='card-link'>Card link</a>" +
                            "<a href='#'' class='card-link'>Another link</a>" +
                        "</div>" +*/
                    "</div>";
    return cardHTML;
}


/***************************** BUILD CHART JS ***********************************
*-------------------------------------------------------------------------------*
*   This function takes the passed player data and builds a complete chart      *
*   from the stats in the passed location.                                      *
********************************************************************************/
function buildChart(player_stats, location) {
    location.append('<h2>Stats - Season 6</h2>')
    location.append('<canvas id="stats_chart" width="400" height="400"></canvas>'); // We need to add a canvas to place the chart
    var ctx = $("#stats_chart");                                                    // And we get the canvases context too
    /* Before we build the chart, we're gonna need to build some arrays from the player data. This includes the following:
    *   -Array of player names
    *   -Array of each players kills / damage etc
    *   -Array of background colors to use based on # of players
    *   -Array of border colors to use based on # of players
    */
    var names = [];
    var kills = [];
    var wins = [];
    $.each(player_stats, function(index, player) {
        names.push(player.name);
        wins.push(player.summary.CAP5x5.wins);      // CAP5x5 is Teambuilder!
        kills.push(player.summary.CAP5x5.aggregatedStats.totalChampionKills);   
    });
    var stats_chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: names,
            datasets: [{
                label: 'Teambuilder Champion Kills',
                data: kills,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)'
                ],
                borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: false
                    }
                }]
            }
        }
    });
}