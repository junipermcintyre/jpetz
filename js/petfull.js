/**
 * This file contains the specific JavaScript code for the pet full view for owner page.
**/
/********************************************************************************
*                     AJAX CALLS FOR API INTERACTION                            *
*********************************************************************************
*   All AJAX calls made will be to the PET Controller at                        *
*   controllers/petcontroller.php. Calls will pass any necessary data, and      *
*   a specific 'action', which maps to a function in the controller.            *
*   The controller will handle all interaction with the database.               *
*********************************************************************************
*					  DEFAULT VARIABLES AND SETTINGS							*
*********************************************************************************
*	This page allows the viewing and modification (permissions willing) of a	*
*	users pet data.																*
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
$("#feedAll").click(function(){
    feedAll();
});

/********************************************************************************
*                     HELPER FUNCTIONS FOR JS PROCESSES                         *
*********************************************************************************
*   Any functions or processes that will be called in loops / repeatedly        *
*   are defined here. These are unique to the League Stats page                 *
********************************************************************************/
// AJAX call to PetController for feeding a all pets
function feedAll() {
	// Can only feed all pets if points available
	$.post(
        "/controllers/petcontroller.php",                      		// Controller to send to
        {action: 'feedAll'},                          		        // Action to run, pet ID to feed
        function(data){            
            // The AJAX call was successful in this brace
            console.log("Data from Pet Controller:");        		// Uncomment these two lines for debug info
            console.log(data);
            try {                                                   // There's a fair chance we get undefined back
                var response = $.parseJSON(data);                   // Take the pet JSON data and build JS object
                if (response.success) {
                	notify(response.message, "success");
                    $("#usr-scum").html($("#usr-scum").html()*1 - $("#hungerTtl").html()*2);
                    $("#feedAll").html('<img src="/images/hunger.gif"> Fed!)');
                } else {
                	notify(response.message, "warning");
                }
            } catch(err) {
                notify("Wasn't able to feed pets (errors), contact admin (code: 441)", "danger");
                return false;                                       // Quit out, after informing of error
            }
        }
    ).fail(function(err, status){
        // The AJAX call was unsuccessful here
        notify("Something broke! Error code: 892", "danger");
        console.log(err);
        console.log(status);
    });
}
