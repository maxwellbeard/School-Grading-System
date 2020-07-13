<?php # Script 12.6 - logout.php
    // This page lets the user logout

    session_name('YourSession');
    session_start();

    // if no session present, redirect user
    if(!isset($_SESSION['teacher_id'])) {
        // need the functions
        require ('includes/login_functions.inc.php');
        redirect_user();
    } else {
    // delete session
        // clear variables
        $_SESSION = array();
        // destroy session itself
        session_destroy();
        // destroy cookie
        setcookie(session_name(), '', time()-3600, '/', '', 0, 0);
    }

    // set the page title and include HTML header
    $page_title = 'Logged Out!';
    include ('includes/header.html');

    // print a customized message
    echo "<h1>Logged Out!</h1>
        <p>You are now logged out!</p>"
    ;

    include ('includes/footer.html');

?>