/**
 * This file contains the specific JavaScript code for the Question Admin page.
**/

/********************************************************************************
*					  AJAX CALLS FOR API INTERACTION							*
*********************************************************************************
*	All AJAX calls made will be to the Question Controller at					*
*	controllers/questioncontroller. Calls will pass any necessary data, and	    *
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
    // Make the API call to the controller with the 'initialAdmin' action
    $.post(
        "/controllers/questioncontroller.php",                          // Controller to send to
        {action: 'initialAdmin'},                                       // Action to run
        function(data){            
            // The AJAX call was successful in this brace
            //console.log("Data from Question Controller:");            // Uncomment these two lines for debug info
            //console.log(data);
            var qDiv = $("#qTable");                                    // Get the div the unverified questions go in
            var uDiv = $("#uTable");                                    // Get the div the verified questions go in
            try {                                                       // There's a fair chance we get undefined back
                var questions = $.parseJSON(data);                      // Take the question JSON data and build JS object
            } catch(err) {
                qDiv.html('<h2>Something went wrong!</h2><p>Please contact an administrator (error code 92).</p>');
                return false;                                           // Quit out, after informing of error
            }

            qDiv.html('');                                              // Everything went well? Kill the CSS Spinner
            var qTable = buildTable(questions.unverified.headers, questions.unverified.rows);
            qDiv.html(qTable);                                          // Set the unverified questions HTML

            uDiv.html('');
            var uTable = buildTable(questions.verified.headers, questions.verified.rows);
            uDiv.html(uTable);                                          // Set the verified questions HTML
        }
    ).fail(function(err, status){
        // The AJAX call was unsuccessful here
        notify("Something broke! Error code: 29", "danger");
        console.log(err);
        console.log(status);
        $("#qTable").html('');                                            // Things went to shit? Kill the CSS Spinner
    });
});



/**************************** BUTTON CLICK HANDLERS *****************************
*-------------------------------------------------------------------------------*
*   The following code deals with UX interaction - specifically the control     *
*   buttons underneath the question table. When clicked, buttons apply the      *
*   given action to all selected rows.                                          *
********************************************************************************/
$("#btn_verify").click(function(){      // Verify button
    modify("verify");
});
$("#btn_discard").click(function(){     // Discard button
    modify("discard");
});



/************************* VERIFYINGG/DISCARDIN AJAX ****************************
*-------------------------------------------------------------------------------*
*   The following takes a list of IDs as input, and verifies all questions      *
*   with those IDs.                                                             *
********************************************************************************/
function modify(action) {
    var ids = [];
    $('#qTable :checkbox:checked').each(function() {                        // Build a list of selected IDs
        ids.push(this.id);
    });

    if (ids.length != 0) {                                                  // Don't make calls with no ids
        $("#mod-loader").show();                                            // Can take a while, so show spinner

        $.post(
            "/controllers/questioncontroller.php",                          // Controller to send to
            {action: action, ids: ids},                                     // Action to run + id list and modifier
            function(data){            
                // The AJAX call was successful in this brace
                //console.log("Data from Question Controller:");            // Uncomment these two lines for debug info
                //console.log(data);
                var qDiv = $("#qTable");                                    // Get the div the unverified questions go in
                var uDiv = $("#uTable");                                    // Get the div the verified questions go in
                try {                                                       // There's a fair chance we get undefined back
                    var questions = $.parseJSON(data);                      // Take the question JSON data and build JS object
                } catch(err) {
                    qDiv.html('<h2>Something went wrong!</h2><p>Please contact an administrator (error code 51)</p>');
                    return false;                                           // Quit out, after informing of error
                }

                qDiv.html('');                                              // Everything went well? Kill the CSS Spinner
                var qTable = buildTable(questions.unverified.headers, questions.unverified.rows);
                qDiv.html(qTable);                                          // Set the unverified questions table

                uDiv.html('');
                var uTable = buildTable(questions.verified.headers, questions.verified.rows);
                uDiv.html(uTable);                                          // Set the verified questions table

                $("#mod-loader").hide();                                    // Done, turn off spinner
                notify("Questions successfully set using: " + action, "success");
            }
        ).fail(function(err, status){
            // The AJAX call was unsuccessful here
            notify("Something broke! Error code: 15", "danger");
            console.log(err);
            console.log(status);

            $("#mod-loader").hide();                                        // Done, turn off spinner
        });
    } else {
        notify("Please select some questions!", "warning");
    }
}