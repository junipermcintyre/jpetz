/**
 * This file contains the specific JavaScript code for the Scum Admin page.
**/

/********************************************************************************
*					  AJAX CALLS FOR API INTERACTION							*
*********************************************************************************
*	All AJAX calls made will be to the Scum Controller at						*
*	controllers/scumcontroller. Calls will pass any necessary data, and		    *
*	a specific 'action', which maps to a function in the controller.			*
*	The controller will handle all interaction with the database.				*
*																				*
*********************************************************************************


***************************** INITIAL LOAD **************************************
*-------------------------------------------------------------------------------*
*	This function is called when the page has completed loading. It filles all	*
*	data with the queried values, based on default arguments.					*
********************************************************************************/
$(document).ready(function() {
    // Make the API call to the controller with the 'initialLoad' action
    $.post(
        "/controllers/scumcontroller.php",                          // Controller to send to
        {action: 'initialAdmin'},                                   // Action to run
        function(data){            
            // The AJAX call was successful in this brace
            //console.log("Data from Scum Controller:");            // Uncomment these two lines for debug info
            //console.log(data);
            var scumDiv = $("#scumTable");                          // Get the div the scumtable goes in
            try {                                                   // There's a fair chance we get undefined back
                var scum = $.parseJSON(data);                       // Take the scum JSON data and build JS object
            } catch(err) {
                scumDiv.html('<h2>Something went wrong!</h2><p>Please contact an administrator (error code 9).</p>');
                return false;                                       // Quit out, after informing of error
            }

            scumDiv.html('');                                       // Everything went well? Kill the CSS Spinner
            var scumTable = buildTable(scum.headers, scum.rows);    // Build the scumtable HTML string and
            scumDiv.html(scumTable);
        }
    ).fail(function(err, status){
        // The AJAX call was unsuccessful here
        notify("Something broke! Error code: 4", "danger");
        console.log(err);
        console.log(status);
        $("#scumTable").html('');                                   // Things went to shit? Kill the CSS Spinner
    });
});



/**************************** BUTTON CLICK HANDLERS *****************************
*-------------------------------------------------------------------------------*
*   The following code deals with UX interaction - specifically the control     *
*   buttons underneath the scum table. When clicked, buttons apply the given    *
*   action to all selected rows.                                                *
********************************************************************************/
$("#btn_bp").click(function(){  // Beautiful Person button
    modifyPoints(1);
});
$("#btn_ss").click(function(){  // Semi-scum button
    modifyPoints(-1);
});
$("#btn_sc").click(function(){  // Scum botton
    modifyPoints(-2);
});



/**************************** POINT MODIFYING AJAX ******************************
*-------------------------------------------------------------------------------*
*   The following takes a list of IDs and a modifier value as input, and        *
*   applies the modifer to the scum points of the IDs belong to.                *
********************************************************************************/
function modifyPoints(mod) {
    var ids = [];
    $('#scumTable :checkbox:checked').each(function() {             // Build a list of selected IDs
        ids.push(this.id);
    });

    // Handle no checkboxes
    if (ids.length < 1) {
        notify("You didn't click any checkboxes!", "warning");
        return;
    }


    /*
    *   Mod can have three possible values:
    *       ->  1:  Beautiful person modifier
    *       -> -1:  Semi-scum modifier
    *       -> -2:  Scum modifier
    *   These values do not directly reflect the points system, as it is assumed the user will gain +1 point a day regardless, so
    *   these are responsible for making up the difference
    */
    $.post(
        "/controllers/scumcontroller.php",                          // Controller to send to
        {action: 'modifyPoints', ids: ids, mod: mod},               // Action to run + id list and modifier
        function(data){            
            // The AJAX call was successful in this brace
            //console.log("Data from Scum Controller:");            // Uncomment these two lines for debug info
            //console.log(data);
            var scumDiv = $("#scumTable");                          // Get the div the scumtable goes in
            try {                                                   // There's a fair chance we get undefined back
                var scum = $.parseJSON(data);                       // Take the scum JSON data and build JS object
            } catch(err) {
                notify("Something went wrong! Please contact administrator (error code 11)", "danger");
                scumDiv.html('');
                return false;                                       // Quit out, after informing of error
            }

            scumDiv.html('');                                       // Everything went well? Kill the CSS Spinner
            var scumTable = buildTable(scum.headers, scum.rows);    // Build the scumtable HTML string and
            scumDiv.html(scumTable);
        }
    ).fail(function(err, status){
        // The AJAX call was unsuccessful here
        notify("Something broke! Error code: 6", "danger");
        console.log(err);
        console.log(status);
    });
}