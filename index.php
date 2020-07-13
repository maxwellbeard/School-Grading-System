<?php # index.php
// This is the start page of the website

    // start session
    session_name('YourSession');
    session_start();

    $page_title = 'Home';
    include ('includes/header.html');
    //include ('includes/handle_errors.php');

    echo '<h1>Welcome to the school grading system!</h1>';

    // check for session data
    // user IS logged in
    if((isset($_SESSION['gender'])) && (isset($_SESSION['last_name']))) {
        $surname = ($_SESSION['gender'] == 'M') ? 'Mr.' : 'Mrs.';
        $last_name = $_SESSION['last_name'];
        
        // print welcome message
        echo "<p>Hello $surname $last_name! Welcome of the schools grading system. 
            It is currently in beta and we sure hope to have the full site up and running soon.
            We are currently working on implementing new functionality and will soon have it ready.
            If you encounter any problems, please contact us below with comments about what the
            problem is and we will get back to you with a fix!</p><p><br /></p>"
        ;

        // create contact form
        echo '<h2>Contact for Help</h2>
            <form action="email.php" method="post">
                <p>Name: <input type="text" name="name" size="30" maxlenght="60" /></p>
                <p>Email Address: <input type="email" name="email" size="30" maxlength="80" /></p>
                <p>Comments: <textarea name="comments" rows="5" cols="30"></textarea></p>
                <p><input type="submit" name="submit" value="Send" /></p>
            </form>'
        ;
    } else { // user NOT logged in
        echo '<p>Hello! You are currently not logged in. You need to login in order to use the site<br /><br /></p>';
    }

    include ('includes/footer.html');

?>