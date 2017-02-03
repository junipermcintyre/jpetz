/**
 * This file contains the specific JavaScript code for the login page.
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

/**************************** ATTEMPT LOGIN *************************************
*-------------------------------------------------------------------------------*
*   This function is called when the user fills in an email and password,       *
*   and attempts to login via the 'Login' button. It pulls the credentials, and *
*   sends them to the AuthController for verification.                          *
********************************************************************************/
function attemptLogin() {
    var email = $("#emailInput").val();                     // Grab the email from the form field
    var password = $("#passwordInput").val();               // Grab the password from the form field
    var remember = $("#rememberme").is(":checked");         // See if remember me button was checked

    if (email != "" && password != "") {                    // Make sure shit ain't empty
        $.post(
            "/controllers/authcontroller.php",
            {action: "login", email: email, password: password, rm: remember},
            function(response) {                            // Hit the controller successfully! Check for success etc...
                console.log(response);
                if (response.success == true) {             // Account was created...
                    notify(response.message, "success");    // Clear inputs and inform user of success
                    if (remember)
                        Cookies.set('rememberme', response.cookie, {expires: 30});

                    if (gt != "")
                        document.location.href = gt;        // Send the user to the requested page
                    else
                        document.location.href = "/index.php";
                } else {                                    // Account was not created
                    notify(response.message, "warning");    // Inform user of failure, don't clear inputs
                }
            },
            "json"
        ).fail(function(err, status){                       // The AJAX call was unsuccessful here
            notify("Something broke! Error code: 2", "danger");
            console.log(err);
            console.log(status);
        });
    } else {        // Password was empty(?)
        notify("You have to fill in both fields!", "warning");
    }
}




/************************* INTERACTION LISTENERS ********************************
*-------------------------------------------------------------------------------*
*   Code below listens for keypresses or clicks on certain elements, and can    *
*   trigger login attempts.                                                     *
********************************************************************************/
$("#loginButton").click(function(){         // Listen for the login button being clicked
    attemptLogin();
});

$("input").keypress(function(e){            // Listen for the enter key being hit only if an input is selected
    if(e.keyCode == 13) {                   // 13 = keyCode for enter (could also use key == "Enter" or which == 13)
        attemptLogin();
    }
});