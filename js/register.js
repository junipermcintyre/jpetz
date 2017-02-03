/**
 * This file contains the specific JavaScript code for the register page.
**/

/********************************************************************************
*                     AJAX CALLS FOR API INTERACTION                            *
*********************************************************************************
*   All AJAX calls made will be to the Auth Controller at                       *
*   controllers/authcontroller. Calls will pass any necessary data, and         *
*   a specific 'action', which maps to a function in the controller.            *
*   The controller will handle all interaction with the Riot API.               *
*                                                                               *
*********************************************************************************


***************************** REGISTER USER *************************************
*-------------------------------------------------------------------------------*
*   This function is called when a user has entered login data and clicked      *
*   'Create Account'. If everything is A-Ok, the users account is created and   *
*   a successful message is returned.                                           *
********************************************************************************/
$("#register").click(function(){
    
    var email = $("#emailInput").val();                     // Grab the email from the form field
    var password = $("#passwordInput").val();               // Grab the password from the form field
    var username = $("#usernameInput").val();               // Grab the username from the form field

    if (email != "" && password != "" && username != "") {  // Make sure shit ain't empty
        $.post(
            "/controllers/authcontroller.php",
            {action: "register", email: email, password: password, username: username},
            function(response) {                            // Hit the controller successfully! Check for success etc...
                console.log(response);
                if (response.success == true) {             // Account was created...
                    notify(response.message, "success");    // Clear inputs and inform user of success
                    $("#emailInput").val("");
                    $("#passwordInput").val("");
                    document.location.href = "/login.php";  // Send the user to the login page
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
        notify("All fields are required for account creation!", "warning");
    }
});
