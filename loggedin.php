<?php # Script 12.4 - loggedin.php
    // the user is redirected here from login.php

    // this script now uses sessions
    session_name('YourSession');
    session_start();

    // if no cookie is present, redirect the user
    // also validate the HTTP_USER_AGENT
    if(!isset($_SESSION['agent']) OR ($_SESSION['agent'] != md5($_SERVER['HTTP_USER_AGENT']))) {
        // need the functions
        require ('includes/login_functions.inc.php');
        redirect_user();
    }

    // set the page title and include HTML header
    $page_title = 'Logged In!';
    include ('includes/header.html');

    // print a customized message
    echo "<h1>Logged In!</h1>
        <p>You are now logged in, {$_SESSION['first_name']}!</p>
        <p><a href=\"logout.php\">Logout</a></p>"
    ;

    include ('includes/footer.html');

?>