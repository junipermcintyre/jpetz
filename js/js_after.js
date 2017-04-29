/**
 * This file contains the specific JavaScript code that executes at the end of page load
 * Included in the footer!
**/


/************************* INTERACTION LISTENERS ********************************
*-------------------------------------------------------------------------------*
*   Listen for someone changing nighttime browsing, and change things           *
*   accordingly                                                                 *
********************************************************************************/
$("#nighttime").click(function(){
    console.log($('#nighttime').is(":checked"));
    if ($('#nighttime').is(":checked")) {
        Cookies.set('nighttime', 1, { expires: 356 });  // PHP interprets as string.... thx
        
        var body_name = $("body").attr("class").split("-dark")[0];
        $("body").attr("class", body_name + "-dark");

        var footer_name = $("credit").attr("class").split("-dark")[0];
        $("credit").attr("class", footer_name + "-dark");
    } else {
        Cookies.set('nighttime', 0, { expires: 356 });

        var body_name = $("body").attr("class").split("-dark")[0];
        $("body").attr("class", body_name);

        var footer_name = $("credit").attr("class").split("-dark")[0];
        $("credit").attr("class", footer_name);
    }
});