/**
 * This file contains the specific JavaScript code for the user profile page.
**/
/********************************************************************************
*                     AJAX CALLS FOR API INTERACTION                            *
*********************************************************************************
*   All AJAX calls made will be to the User Controller at                       *
*   controllers/usercontroller.php. Calls will pass any necessary data, and     *
*   a specific 'action', which maps to a function in the controller.            *
*   The controller will handle all interaction with the database.               *
*********************************************************************************
*					  DEFAULT VARIABLES AND SETTINGS							*
*********************************************************************************
*	This page allows the viewing and modification (permissions willing) of a	*
*	users profile data.															*
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
$("#edit").click(function(){
    edit();
});

$("#save").click(function(){
    save();
})


/********************************************************************************
*                     HELPER FUNCTIONS FOR JS PROCESSES                         *
*********************************************************************************
*   Any functions or processes that will be called in loops / repeatedly        *
*   are defined here. These are unique to the League Stats page                 *
*********************************************************************************



/*************************** Turn on edit fields ********************************
*-------------------------------------------------------------------------------*
*   Change classes with editable to remove readonly, change classes with hidden *
*   to visible.                                                                 *
********************************************************************************/
function edit() {
    $(".editable").prop("readonly", false);
    $(".hidden").show();
    $("#button").html('<button type="button" class="btn btn-success btn-lg btn-block" id="save">Save</button>')
    $("#save").click(function(){
        save();
    })
}



/*************************** Save the edit fields *******************************
*-------------------------------------------------------------------------------*
*   Change classes with editable to add readonly, change classes with hidden    *
*   to hidden.                                                                 *
********************************************************************************/
function save() {
    // Collect values
    formData = new FormData();
    formData.append('action', "update");
    formData.append('name', $("#name").val());
    formData.append('twitter', $("#twitter").val());
    formData.append('steam', $("#steam").val());
    formData.append('league', $("#league").val());
    formData.append('website', $("#website").val());
    formData.append('about', $("#about").html());
    formData.append('intro', $("#intro").val());
    formData.append('pic', document.getElementById("profileImg").files[0]);

   $.ajax({
        url: "/controllers/authcontroller.php",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(data){
            console.log(data);
            data = JSON.parse(data);
            if (data.success) {
                notify("Profile successfully updated!", "success");
            } else {
                notify("Error 83: Contact administrator and tell 'em whatcha did to get this.", "danger");
            }
        },
        fail: function(data){
            notify("Error 38: Contact administrator and tell 'em whatcha did to get this.", "danger");
        }
    });

    $(".editable").prop("readonly", true);
    $(".hidden").hide();
    $("#button").html('<button type="button" class="btn btn-primary btn-lg btn-block" id="edit">Edit</button>')
    $("#edit").click(function(){
        edit();
    });
}