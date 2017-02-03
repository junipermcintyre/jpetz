/**
 * This file contains the specific JavaScript code for the scum page.
**/

/********************************************************************************
*                     AJAX CALLS FOR API INTERACTION                            *
*********************************************************************************
*   All AJAX calls made will be to the Scum Controller at                       *
*   controllers/scumcontroller. Calls will pass any necessary data, and         *
*   a specific 'action', which maps to a function in the controller.            *
*   The controller will handle all interaction with the user database.          *
*                                                                               *
*********************************************************************************

***************************** INITIAL LOAD **************************************
*-------------------------------------------------------------------------------*
*	This function is called when the page has completed loading. It gets		*
*	the initial load of every user and their scum points from the 				*
*	ScumController																*
********************************************************************************/
$(document).ready(function() {
	// Make the API call to the controller with the 'initialLoad' action
	$.post(
        "/controllers/scumcontroller.php",		        			// Controller to send to
    	{action: 'initialLoad'},	        						// Action to run
    	function(data){            
    		// The AJAX call was successful in this brace
            //console.log("Data from Scum Controller:"); 				// Uncomment these two lines for debug info
            //console.log(data);
            var scumDiv = $("#scumTable");            				// Get the div the scumtable goes in
            try {                                           		// There's a fair chance we get undefined back
                var scum = $.parseJSON(data);        				// Take the scum JSON data and build JS object
            } catch(err) {
                scumDiv.html('<h2>Something went wrong!</h2><p>Please contact an administrator.</sup></p>');
                return false;   									// Quit out, after informing of error
            }

            scumDiv.html('');                      					// Everything went well? Kill the CSS Spinner
            var scumTable = buildTable(scum.headers, scum.rows);    // Build the scumtable HTML string and
            scumDiv.html(scumTable);
    	}
    ).fail(function(err, status){
        // The AJAX call was unsuccessful here
        notify("Something broke! Error code: 2", "danger");
        console.log(err);
        console.log(status);
        $("#scumTable").html('');              						// Things went to shit? Kill the CSS Spinner
    });
});