/**
 * This file contains the specific JavaScript code for the pet page.
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
$("#feed").click(function(){
    feed(petId);
});

$("#flaunt").click(function(){
    flaunt(petId);
});

$("#edit").click(function(){
    edit(petId);
});

/********************************************************************************
*                     HELPER FUNCTIONS FOR JS PROCESSES                         *
*********************************************************************************
*   Any functions or processes that will be called in loops / repeatedly        *
*   are defined here. These are unique to the League Stats page                 *
********************************************************************************/
// AJAX call to PetController for feeding a pet
function feed(id) {
	// Can only feed pet if feeds < 3 and points available
	$.post(
        "/controllers/petcontroller.php",                      		// Controller to send to
        {action: 'feed', pet: petId},                          		// Action to run, pet ID to feed
        function(data){            
            // The AJAX call was successful in this brace
            console.log("Data from Pet Controller:");        		// Uncomment these two lines for debug info
            console.log(data);
            try {                                                   // There's a fair chance we get undefined back
                var response = $.parseJSON(data);                   // Take the pet JSON data and build JS object
                if (response.success) {
                	notify(response.message, "success");
                	$("#hunger").html(response.data.hunger);
                    $("#usr-scum").html($("#usr-scum").html()*1-2);
                } else {
                	notify(response.message, "warning");
                }
            } catch(err) {
                notify("Wasn't able to feed pet (errors), contact admin (code: 631)", "danger");
                return false;                                       // Quit out, after informing of error
            }
        }
    ).fail(function(err, status){
        // The AJAX call was unsuccessful here
        notify("Something broke! Error code: 136", "danger");
        console.log(err);
        console.log(status);
    });
}

// AJAX call to PetController for flaunting a pet
function flaunt(id) {
	// Can only feed pet if feeds < 2 and points available
	$.post(
        "/controllers/petcontroller.php",                      		// Controller to send to
        {action: 'flaunt', pet: petId},                          	// Action to run, pet ID to feed
        function(data){            
            // The AJAX call was successful in this brace
            console.log("Data from Pet Controller:");        		// Uncomment these two lines for debug info
            console.log(data);
            try {                                                   // There's a fair chance we get undefined back
                var response = $.parseJSON(data);                   // Take the pet JSON data and build JS object
                if (response.success) {
                	notify(response.message, "success");
                    $("#usr-scum").html($("#usr-scum").html()*1-10);
                } else {
                	notify(response.message, "warning");
                }
            } catch(err) {
                notify("Wasn't able to flaunt pet (errors), contact admin (code: 361)", "danger");
                return false;                                       // Quit out, after informing of error
            }
        }
    ).fail(function(err, status){
        // The AJAX call was unsuccessful here
        notify("Something broke! Error code: 163", "danger");
        console.log(err);
        console.log(status);
    });
}

// AJAX call to PetController to edit pet deets
function edit(id) {
	// Grab values
	var bio = $("#bio").val();
	var name = $("#name").val();

	// Can only feed pet if feeds < 3 and points available
	$.post(
        "/controllers/petcontroller.php",                      		// Controller to send to
        {action: 'edit', pet: petId, name: name, bio: bio},         // Action to run, pet ID + info
        function(data){            
            // The AJAX call was successful in this brace
            console.log("Data from Pet Controller:");        		// Uncomment these two lines for debug info
            console.log(data);
            try {                                                   // There's a fair chance we get undefined back
                var response = $.parseJSON(data);                   // Take the pet JSON data and build JS object
                if (response.success) {
                	notify(response.message, "success");
                	$("#title-name").html(response.data.name);
                	$("#bio-display").html(response.data.bio);
                } else {
                	notify(response.message, "warning");
                }
            } catch(err) {
                notify("Wasn't able to edit pet (errors), contact admin (code: 316)", "danger");
                return false;                                       // Quit out, after informing of error
            }
        }
    ).fail(function(err, status){
        // The AJAX call was unsuccessful here
        notify("Something broke! Error code: 613", "danger");
        console.log(err);
        console.log(status);
    });
}


// Freeze all inputs / buttons
function freeze() {

}

// Unfreeze all inputs / buttons
function unfreeze() {

}