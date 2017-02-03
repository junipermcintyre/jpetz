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
*   This function is called when the user fills in a new password to replace    *
*   their forgotten one.                                                        *
********************************************************************************/
function resetPassword() {
    var password = $("#passwordInput").val();               // Grab the email from the form field
    if (password != "" ) {                                  // Make sure shit ain't empty
        $.post(
            "/controllers/authcontroller.php",
            {action: "reset", token: token, password: password},
            function(response) {                            // Hit the controller successfully! Check for success etc...
                console.log(response);
                if (response.success == true) {             // Password was updated
                    notify(response.message, "success");    // Clear inputs and inform user of success
                    $("#passwordInput").val("");
                    document.location.href = "/login.php";  // Send the user to the login page
                } else {                                    // Account was not created
                    notify(response.message, "warning");    // Inform user of failure, don't clear inputs
                }
            },
            "json"
        ).fail(function(err, status){                       // The AJAX call was unsuccessful here
            notify("Something broke! Error code: 66", "danger");
            console.log(err);
            console.log(status);
        });
    } else {        // Email was empty(?)
        notify("You have to fill in a new password.", "warning");
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