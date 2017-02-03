<?php
    /*
    * This page displays the Admin panel page
    */
    $access = "mod";                    // Define access level
    include 'includes/before.php';      // Get initial boilerplate
    
    // Step #1 - Let's get that question code
    $result = $db->query("SELECT COUNT(*) as count FROM questions WHERE active = 1 AND verified = 0 LIMIT 1");   
    if ($result === false) {throw new Exception ($db->error);}      // If somehing went wrong
    $count = $result->fetch_object()->count;

    $badge = "";
    if ($count > 0) {
        $badge = "<span class='tag tag-default tag-pill float-xs-right'>{$count}</span>";
    }

    include 'includes/after.php';

    $dir = dirname(__FILE__);

    // Pass the badge HTML to the view
    $smarty->assign('unverifiedBadge', $badge);
    
    // Display the associated template
    $smarty->display("{$dir}/views/admin.tpl");
?>