/**
 * This file contains the specific JavaScript code for the Raid attack page.
**/
/********************************************************************************
*                     AJAX CALLS FOR API INTERACTION                            *
*********************************************************************************
*   All AJAX calls made will be to the RaidController at                        *
*   controllers/raidcontroller.php. Calls will pass any necessary data, and     *
*   a specific 'action', which maps to a function in the controller.            *
*   The controller will handle all interaction with the database.               *
*********************************************************************************
*					  DEFAULT VARIABLES AND SETTINGS							*
*********************************************************************************
*	This page allows the attacking of other players in raids.					*
********************************************************************************/



/**************************** INITIAL LOAD **************************************
*-------------------------------------------------------------------------------*
*	This function is called when the page has completed loading. It filles all	*
*	data with the queried values, based on default arguments.					*
********************************************************************************/
$(document).ready(function() {
	// Enable the damage tooltips
	$('[data-toggle="tooltip"]').tooltip();
});



/********************************************************************************
*                                   LISTENERS                                   *
*********************************************************************************
*   Listeners for click events etc                                              *
********************************************************************************/
$("#attack").click(function(){
	var id = 10;				// Grab the defending users ID
    raid(id);					// Raid the user
});


/********************************************************************************
*                     HELPER FUNCTIONS FOR JS PROCESSES                         *
*********************************************************************************
*   Any functions or processes that will be called in loops / repeatedly        *
*   are defined here. These are unique to the League Stats page                 *
********************************************************************************/


/*********************************** Raid ***************************************
*-------------------------------------------------------------------------------*
*   When passed a users ID attempt to raid them with the current users			*
*	attacking J-Petz.												            *
********************************************************************************/
function raid(user) {
	freeze();
    $.post(
	    "/controllers/raidcontroller.php",
	    {action: "raid", defender: user},
	    function(response) {                            // Hit the controller successfully! Check for success etc...
	        console.log(response);
	        if (response.success == true) {				// Had enough hp, owned, wasn't busy etc
	        	 // Animations!
	           	$('.aPet').addClass('animated tada');
	            $('.aPet').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
	            	$('.aPet').removeClass('animated tada');
	            	$('.dPet').addClass('animated shake');
	            	$('.dPet').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
	            		$('.dPet').removeClass('animated shake');
	            		// Values!
			        	// Filler!
	            	});
	            });
	            notify(response.message, "success");
	        } else {
	            notify(response.message, "danger");    	// Wasn't owned or was busy
	        }
	        unfreeze();
	    },
	    "json"
	).fail(function(err, status){                       // The AJAX call was unsuccessful here
		console.log(err);
		unfreeze();
	    notify("Something broke! Error code: 142", "danger");
	});
}


/********************************** Decode **************************************
*-------------------------------------------------------------------------------*
*   Decode the ID of an element into its components.                            *
********************************************************************************/
// http://stackoverflow.com/questions/30479448/disable-all-buttons-on-page
function freeze() {
	$(':button').prop('disabled', true); // Disable all the buttons
}

function unfreeze() {
	$(':button').prop('disabled', false); // Enable all the button
}