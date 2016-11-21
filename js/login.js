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
*********************************************************************************


***************************** ATTEMPT LOGIN *************************************
*-------------------------------------------------------------------------------*
*   This function is called when the user fills in an email and password,       *
*   and attempts to login via the 'Login' button. It pulls the credentials, and *
*   sends them to the AuthController for verification.                          *
********************************************************************************/
$("#loginButton").click(function(){
    
    var email = $("#emailInput").val();                     // Grab the email from the form field
    var password = $("#passwordInput").val();               // Grab the password from the form field

    if (email != "" && password != "") {                    // Make sure shit ain't empty
        $.post(
            "/controllers/authcontroller.php",
            {action: "login", email: email, password: password},
            function(response) {                            // Hit the controller successfully! Check for success etc...
                console.log(response);
                if (response.success == true) {             // Account was created...
                    alert(response.message);                // Clear inputs and inform user of success
                    document.location.href = "/index.php";  // Send the user back to the home page
                } else {                                    // Account was not created
                    alert(response.message);                // Inform user of failure, don't clear inputs
                }
            },
            "json"
        ).fail(function(err, status){                       // The AJAX call was unsuccessful here
            alert("Something broke! Error code: 2")
            console.log(err);
            console.log(status);
        });
    } else {        // Password was empty(?)
        alert("You have to fill in both fields!");
    }
});
