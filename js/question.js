/**
 * This file contains the specific JavaScript code for the question page.
**/

/********************************************************************************
*					  AJAX CALLS FOR QUESTION INTERACTION						*
*********************************************************************************
*	All AJAX calls made will be to the Question Controller at					*
*	controllers/scumcontroller. Calls will pass any necessary data, and		    *
*	a specific 'action', which maps to a function in the controller.			*
*	The controller will handle all interaction with the database.				*
*																				*
*********************************************************************************


***************************** INITIAL LOAD **************************************
*-------------------------------------------------------------------------------*
*	This function is called when the page has completed loading. It populates   *
*   the page with a strawpoll question.                     					*
********************************************************************************/
$(document).ready(function() {
    // Make the API call to the controller with the 'initialLoad' action
    $.post(
        "/controllers/questioncontroller.php",                      // Controller to send to
        {action: 'initialLoad'},                                    // Action to run
        function(data){            
            // The AJAX call was successful in this brace
            //console.log("Data from Question Controller:");        // Uncomment these two lines for debug info
            //console.log(data);
            var qDiv = $("#question");                              // Get the div the question goes in
            try {                                                   // There's a fair chance we get undefined back
                var question = $.parseJSON(data);                   // Take the question JSON data and build JS object
            } catch(err) {
                qDiv.html('<h2>Something went wrong!</h2><p>Please contact an administrator (error code 43).</p>');
                return false;                                       // Quit out, after informing of error
            }

            qDiv.html('');                                          // Everything went well? Kill the CSS Spinner
            qDiv.html('<iframe src="https://www.strawpoll.me/embed_1/'+question.code+'" style="width:680px;height:560px;border:0;">Loading poll...</iframe>');
        }
    ).fail(function(err, status){
        // The AJAX call was unsuccessful here
        notify("Something broke! Error code: 34", "danger");
        console.log(err);
        console.log(status);
        $("#qDiv").html('');                                         // Things went to shit? Kill the CSS Spinner
    });
});



/**************************** BUTTON CLICK HANDLERS *****************************
*-------------------------------------------------------------------------------*
*   The following code deals with UX interaction - specifically the control     *
*   buttons underneath the question. When clicked, the supplied code is sent    *
*   to the Question Controller to be added to the DB.                           *
********************************************************************************/
$("#qBtn").click(function(){
    sendCode($("#qCode").val());
});



/**************************** QUESTION ADDING AJAX ******************************
*-------------------------------------------------------------------------------*
*   The following takes a strawpoll code and sends it to the Question           *
*   to be added to the Database                                                 *
********************************************************************************/
function sendCode(code) {
    $.post(
        "/controllers/questioncontroller.php",                      // Controller to send to
        {action: 'addCode', code: code},                            // Action to run + strawpoll code
        function(data){            
            // The AJAX call was successful in this brace
            //console.log("Data from Question Controller:");        // Uncomment these two lines for debug info
            //console.log(data);                                    // There's a fair chance we get undefined back
            $("#qCode").val("");
            notify("Thank you for your submission! Your question is awaiting validation.", "success");
        }
    ).fail(function(err, status){
        // The AJAX call was unsuccessful here
        notify("Something broke! Error code: 76", "danger")
        console.log(err);
        console.log(status);
    });
}