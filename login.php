<?php # login.php
    /* This page processes the login form submission
     * Upon successful login, the user is redirected
     * Two included files are necessary
     * Send NOTHING to the web browser prior to the session lines
    */

    // check if form has been submitted
    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        // for processing the login
        require ('includes/login_functions.inc.php');

        // need the database connection
        require ('../../../../mysqli_connect.php');

        // check login
        list($check, $data) = check_login($dbc, $_POST['email'], $_POST['pass']);

        // is OK
        if($check) {
            
            // set the sessions
            session_name('YourSession');
            session_start();
            $_SESSION['teacher_id'] = $data['teacher_id'];
            $_SESSION['first_name'] = $data['first_name'];
            $_SESSION['last_name'] = $data['last_name'];
            $_SESSION['gender'] = $data['gender'];
            $_SESSION['email'] = $data['email'];

            // store the HTTP_USER_AGENT
            $_SESSION['agent'] = md5($_SERVER['HTTP_USER_AGENT']);

            // redirect
            redirect_user('loggedin.php');
        } else {
        // unsuccessful
            // assign $data to $errors for error reporting
            // in the login_page.inc.php file
            $errors = $data;
        }

        // close db connection
        mysqli_close($dbc);

    } // end main submit if

    // create the page
    include ('includes/login_page.inc.php');

?>