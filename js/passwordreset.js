/**
 * This file contains the specific JavaScript code for the password reset page.
**/

/********************************************************************************
*                     AJAX CALLS FOR API INTERACTION                            *
*********************************************************************************
*   All AJAX calls made will be to the Auth Controller at                       *
*   controllers/authcontroller. Calls will pass any necessary data, and         *
*   a specific 'action', which maps to a function in the controller.            *
*   The controller will handle all interaction with the user database.          *
*                                                                               *
********************************************************************************/

/**************************** RESET PASSWORD ************************************
*-------------------------------------------------------------------------------*
*   This function is called when the user fills in an email and asks for the    *
*   password reset email (ie clicks the button)                                 *
********************************************************************************/
function resetPassword() {
    var email = $("#emailInput").val();                     // Grab the email from the form field
    if (email != "" ) {                                     // Make sure shit ain't empty
        $.post(
            "/controllers/authcontroller.php",
            {action: "resetemail", email: email},
            function(response) {                            // Hit the controller successfully! Check for success etc...
                console.log(response);
                notify(response.message, "success");        // Let the user know if the email was sent or not
            },
            "json"
        ).fail(function(err, status){                       // The AJAX call was unsuccessful here
            notify("Something broke! Error code: 55", "danger");
            console.log(err);
            console.log(status);
        });
    } else {        // Email was empty(?)
        notify("You have to fill in the email...", "warning");
    }
}




/************************* INTERACTION LISTENERS ********************************
*-------------------------------------------------------------------------------*
*   Code below listens for keypresses or clicks on certain elements, and can    *
*   trigger reset attempts.                                                     *
********************************************************************************/
$("#resetButton").click(function(){         // Listen for the reset button being clicked
    resetPassword();
});

$("input").keypress(function(e){            // Listen for the enter key being hit only if an input is selected
    if(e.keyCode == 13) {                   // 13 = keyCode for enter (could also use key == "Enter" or which == 13)
        resetPassword();
    }
});