/**
 * This file contains the specific JavaScript code for the all quests page
**/
/********************************************************************************
*                     AJAX CALLS FOR API INTERACTION                            *
*********************************************************************************
*   All AJAX calls made will be to the Shop Controller at                       *
*   controllers/questcontroller.php. Calls will pass any necessary data, and    *
*   a specific 'action', which maps to a function in the controller.            *
*   The controller will handle all interaction with the database.               *
*********************************************************************************


/**************************** INITIAL LOAD **************************************
*-------------------------------------------------------------------------------*
*	This function is called when the page has completed loading. It filles all	*
*	data with the queried values, based on default arguments.					*
********************************************************************************/
$(document).ready(function() {

});



/********************************************************************************
*                                   LISTENERS                                   *
*********************************************************************************
*   Listeners for click events etc                                              *
********************************************************************************/
$(".send-pet").click(function(){
    var qId = $(this).attr('id');
    code = $("#questpicker-"+qId).val();
    code = decode(code);
    send(code.pet, code.quest);
});

$("#show-available").click(function(){
	$("#hidden-available").toggle();
	$("#show-available").toggle();
});

$("#show-progress").click(function(){
	$("#hidden-progress").toggle();
	$("#show-progress").toggle();
});


/********************************************************************************
*                     HELPER FUNCTIONS FOR JS PROCESSES                         *
*********************************************************************************
*   Any functions or processes that will be called in loops / repeatedly        *
*   are defined here. These are unique to the League Stats page                 *
*********************************************************************************



/********************************** Decode **************************************
*-------------------------------------------------------------------------------*
*   Decode the ID of an element into its components.                            *
********************************************************************************/
function decode(s) {
    s = s.split('-');
    return {key: s[0], quest: s[1], pet: s[2]};
}


/*********************************** Send ***************************************
*-------------------------------------------------------------------------------*
*   When passed a pets ID and a quest ID, attempt to send that pet, on that		*
*	quest.													                    *
********************************************************************************/
function send(pet, quest) {
    $.post(
	    "/controllers/questcontroller.php",
	    {action: "send", pet: pet, quest: quest},
	    function(response) {                            // Hit the controller successfully! Check for success etc...
	        console.log(response);
	        if (response.success == true) {				// Had enough points, owned, etc..
	            notify(response.message, "success");
	        } else {
	            notify(response.message, "danger");    	// Did not have enough points, didn't own, etc...
	        }
	    },
	    "json"
	).fail(function(err, status){                       // The AJAX call was unsuccessful here
		//notify(err.responseText, "danger");
	    notify("Something broke! Error code: 142", "danger");
	});
}
