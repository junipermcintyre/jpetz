/**
 * This file contains the specific JavaScript code for the shop page.
**/
/********************************************************************************
*                     AJAX CALLS FOR API INTERACTION                            *
*********************************************************************************
*   All AJAX calls made will be to the Shop Controller at                       *
*   controllers/shopcontroller.php. Calls will pass any necessary data, and     *
*   a specific 'action', which maps to a function in the controller.            *
*   The controller will handle all interaction with the database.               *
*********************************************************************************
*					  DEFAULT VARIABLES AND SETTINGS							*
*********************************************************************************
*	This page allows the viewing and modification (permissions willing) of a	*
*	users profile data.															*
********************************************************************************/



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
$(".buyBtn").click(function(){
    var code = $(this).attr('id');
    code = decode(code);
    purchase(code.id);
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
    return {type: s[0], action: s[1], id: s[2]};
}


/********************************* Purchase *************************************
*-------------------------------------------------------------------------------*
*   When passed an ID, attempt to purchase that item/pet.                       *
********************************************************************************/
function purchase(id) {
    $.post(
	    "/controllers/shopcontroller.php",
	    {action: "purchase", id: id},
	    function(response) {                            // Hit the controller successfully! Check for success etc...
	        console.log(response);
	        if (response.success == true) {				// Had enough points
	            notify(response.message, "success");
	        } else {
	            notify(response.message, "danger");    	// Did not have enough points
	        }
	    },
	    "json"
	).fail(function(err, status){                       // The AJAX call was unsuccessful here
		//notify(err.responseText, "danger");
	    notify("Something broke! Error code: 142", "danger");
	});
}
