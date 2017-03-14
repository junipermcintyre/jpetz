/**
 * This file contains the JavaScript code for common functions that many pages could use.
**/

/***************************** BUILD TABLE **************************************
*-------------------------------------------------------------------------------*
*	This function builds and outputs a bootstrap table as a string.	The table	*
*	headers, and table data are passed as arguments to the function.			*
********************************************************************************/
function buildTable(headers, rows){
	/*	Uncomment for debug info
	console.log("Table headers:");
	console.log(headers);
	console.log("Table rows");
	console.log(rows);
	*/
	
	var tableHtml = "";														// This be where we buildin the table
	tableHtml += "<table class='table table-bordered table-hover table-sm'>"
	tableHtml += "<thead class='thead-inverse'><tr>";
	$.each(headers, function(key, value){									// Iterate over each header
		tableHtml += "<th>" + value + "</th>";								// Build the header tags and values...
	});					
	tableHtml += "</tr></thead><tbody>";									// Close up the head, start the body
	$.each(rows, function(key, value){										// Iterate over each row of data
		tableHtml += "<tr>";												// New row - build it
		$.each(value, function(key, value){									// Iterate over each data field in the row
			tableHtml += "<td>" + value + "</td>";							// Got a field in the row - <td> it
		});					
		tableHtml += "</tr>";												// Row's done - end it
	});					
	tableHtml += "</tbody></table>";										// Should be good, end the body and table
	return tableHtml;														// Aaaand done. Return the complete HTML string
}


/******************************** NOTIFY ****************************************
*-------------------------------------------------------------------------------*
*	This function takes a message and a bootstrap message level, and displays	*
*	a notification banner across the top of the viewport.						*
********************************************************************************/
function notify(m, l){
	/*var s = '<div class="alert alert-'+l+' alert-dismissible show" role="alert">';
	s += 		'<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
	s += 			'<span aria-hidden="true">&times;</span>';
	s += 		'</button>';
	s += 		m;
	s += 	'</div>';
	$("#notify").html(s)*/
	if (l == "danger")
		l = "error";
	$.notify(
		m,
		{position: "right bottom", className: l}
	);
}


/**************************** FEATURE REQUEST ***********************************
*-------------------------------------------------------------------------------*
*	This is a listener for when users click the submit request button. Grabs	*
*	The supplied name, and supplied request, sends em off to me (email).		*
********************************************************************************/
$(document).ready(function(){
	$("#featureBtn").click(function(){
		var name = $("#featureName").val();
		var msg = $("#featureRequest").val();

		if (name == "" || msg == "") {
			notify("Please fill in all fields", "danger");
		} else {
			request(name, msg);
			$("#featureClose").click();
		}
	});
});

function request(name, msg){
	// Send an AJAX request to send administrators & mods an email
	$.post(
        "/controllers/authcontroller.php",
        {action: "feature", name: name, msg: msg},
        function(response) {                            // Hit the controller successfully! Check for success etc...
            console.log(response);
            if (response.success == true) {             // Account was created...
                notify("Thanks! That helps", "success");    // Inform user of success
            } else {                                    // Account was not created
                notify(response.message, "warning");    // Inform user of failure, don't clear inputs
            }
        },
        "json"
    ).fail(function(err, status){                       // The AJAX call was unsuccessful here
        notify("Something broke! Error code: 3168", "danger");
        console.log(err);
        console.log(status);
    });
}
