<?php
    /*
    * This page displays the reset password form (not the email sending one)
    */
    include 'includes/before.php';      // Get initial boilerplate

    // Step #1 - Let's get that email for that token
    $result = $db->query("SELECT email FROM users WHERE resettoken = '{$_GET['token']}'");   
    if ($result === false) {throw new Exception ($db->error);}      // If something went wrong
    $email = $result->fetch_object()->email;

    include 'includes/after.php';

    // Pass the reset token to the view
    $smarty->assign('token', $_GET['token']);

    // Pass the email to the view
    $smarty->assign('email', $email);

    // Display the associated template
    $dir = dirname(__FILE__);
    $smarty->display("$dir/views/reset.tpl");
?>