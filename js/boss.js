/**
 * This file contains the specific JavaScript code for the boss page.
**/
/********************************************************************************
*                     AJAX CALLS FOR API INTERACTION                            *
*********************************************************************************
*   All AJAX calls made will be to the BossController at                        *
*   controllers/bosscontroller.php. Calls will pass any necessary data, and     *
*   a specific 'action', which maps to a function in the controller.            *
*   The controller will handle all interaction with the database.               *
*********************************************************************************
*					  DEFAULT VARIABLES AND SETTINGS							*
*********************************************************************************
*	This page allows the viewing and fighting of a raid boss.					*
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
$(".fight").click(function(){
	var code = $(this).attr('id');
    code = decode(code);
    attack(code.pet);
});


/********************************************************************************
*                     HELPER FUNCTIONS FOR JS PROCESSES                         *
*********************************************************************************
*   Any functions or processes that will be called in loops / repeatedly        *
*   are defined here. These are unique to the League Stats page                 *
********************************************************************************/


/********************************** Attack **************************************
*-------------------------------------------------------------------------------*
*   When passed a pets ID attempt to attack the current raid boss.              *
********************************************************************************/
function attack(pet) {
	freeze();
    $.post(
	    "/controllers/bosscontroller.php",
	    {action: "attack", pet: pet},
	    function(response) {                            // Hit the controller successfully! Check for success etc...
	        console.log(response);
	        if (response.success == true) {				// Had enough hp, owned, wasn't busy etc
	        	 // Animations!
	           	$('#pet-img-'+pet).addClass('animated tada');
	            $('#pet-img-'+pet).one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
	            	$('#pet-img-'+pet).removeClass('animated tada');
	            	$('#boss-img').addClass('animated shake');
	            	$('#boss-img').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
	            		$('#boss-img').removeClass('animated shake');
	            		// Values!
			        	$("#pet-hp-"+pet).html(response.data.hp);
			        	$("#bosshp").html(response.data.bosshp);
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
function decode(s) {
    s = s.split('-');
    return {key: s[0], action: s[1], pet: s[2]};
}

// http://stackoverflow.com/questions/30479448/disable-all-buttons-on-page
function freeze() {
	$(':button').prop('disabled', true); // Disable all the buttons
}

function unfreeze() {
	$(':button').prop('disabled', false); // Enable all the button
}