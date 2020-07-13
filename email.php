<?php # Script 13.1 - email.php

    // set the sessions
    session_name('YourSession');
    session_start();

    $page_title = 'Send Email';
    include ('includes/header.html');
    //include ('includes/handle_errors.php');

    echo '<h1>Sending Email</h1>';

    // check for form submission
    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        /* The function takes one argument: a string
            * The function returns a clean version of the string
            * The clean version my be either an empty string or
            * just the removal of all newline characters
        */
        function spam_scrubber($value) {

            // list of bad values
            $bad = array('to:', 'cc:', 'bcc:', 'content-type:', 
                'mime-version:', 'multipart-mixed:', 'content-transfer-encoding:'
            );

            // if any bad values are in string, return an empty string
            foreach($bad as $v) {
                if(stripos($value, $v) !== false) return '';
            }

            // replace any newline characters with spaces
            $value = str_replace(array("\r", "\n", "%0a", "%0d") , ' ', $value);

            // return value
            return trim($value);
        } // end of spam_scrubber()

        // clean form data
        $scrubbed = array_map('spam_scrubber', $_POST);

        // minimal form validation
        if(!empty($scrubbed['name']) && !empty($scrubbed['email']) && !empty($scrubbed['comments'])) {

            // create body
            $body = "Name: {$scrubbed['name']}\n\nComments: {$scrubbed['comments']}";

            // create additional headers
            $headers = "From: {$scrubbed['email']}\r\nDate: " . date("F d, Y H:i:s");

            // make it no longer than 70 characters long
            $body = wordwrap($body, 70);

            // create to
            $to = (isset($_POST['e'])) ? $_POST['e'] : 'maxwell.beard1657@mail.davistech.edu';

            // send the email
            mail($to, 'Contact Form Submission', $body, $headers);

            // print message
            if(isset($_POST['e'])) {
                // message from teacher to student
                echo '<p><em>Your message has been sent to your student.</em></p><br />';
                // button for user to go back
                echo '<p><a href="view_classroom.php">Back</a></p>';
            } else {
                // message from teacher to development
                echo '<p><em>Thank you for contacting us. We will relpy with a fix in the coming days.</em></p>';
                // button for user to go back
                echo '<p><a href="index.php">Back</a></p>';
            }

            // clear $_POST (so that the forms not sticky)
            $_POST = array();

        } else {
            echo '<p stype="font_weight: bold; color: #C00">Please fill out the form completely.</p>';
            // button for user to go back
            if(isset($_POST['e'])) {
                echo '<p><a href="view_classroom.php">Back</a></p>';
            } else {
                echo '<p><a href="index.php">Back</a></p>';
            }
        }
    } else {
        // show form
        echo '<p>Please fill out the form.</p>
            <form action="email.php" method="post">
                <p>Name: <input type="text" name="name" size="30" maxlenght="60" value="' . 
                    ((isset($_POST['name'])) ? htmlentities($_POST['name']) : NULL) . '" /></p>
                <p>Email Address: <input type="text" name="email" size="30" maxlenght="80" value="' .
                    ((isset($_POST['email'])) ? htmlentities($_POST['email']) : NULL) . '" /></p>
                <p>Comments: <textarea name="comments" rows="5" cols="30">' .
                    ((isset($_POST['comments'])) ? htmlentities($_POST['comments']) : NULL) . '</textarea></p>
                <p><input type="submit" name="submit" value="Send!" /></p>
                <input type="hidden" name="e" value="' . 
                    ((isset($_GET['e'])) ? $_GET['e'] : NULL) . '" />
            </form>';
    } // END main submit if

    include ('includes/footer.html'); 
?>