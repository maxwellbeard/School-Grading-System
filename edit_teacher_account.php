<?php // edit_teacher_account.php
// this script allows the teacher (the user) the edit and change their account info

    // start session
    session_name('YourSession');
    session_start();

    $page_title = 'Edit Account';
    include ('includes/header.html');
    //include ('includes/handle_errors.php');

    echo '<h1>Edit your Account</h1>';

    // check if user is logged in
    if(isset($_SESSION['teacher_id'])) {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // db connection
            require ('../../../../mysqli_connect.php');
            $errors = array();

            // check for first name
            if(empty($_POST['first_name'])) {
                $errors[] = 'You forgot to enter your first name.';
            } else {
                $fn = mysqli_real_escape_string($dbc, trim($_POST['first_name']));
            }

            // check for last name
            if(empty($_POST['last_name'])) {
                $errors[] = 'You forgot to enter your last name.';
            } else {
                $ln = mysqli_real_escape_string($dbc, trim($_POST['last_name']));
            }

            // check for gender
            if(empty($_POST['gender'])) {
                $errors[] = 'You forgot to enter your gender.';
            } else {
                $g = mysqli_real_escape_string($dbc, trim($_POST['gender']));
            }

            // check for email
            if(empty($_POST['email'])) {
                $errors[] = 'You forgot to enter your email address.';
            } else {
                $e = mysqli_real_escape_string($dbc, trim($_POST['email']));
            }

            // check for current password
            if(empty($_POST['pass'])) {
                $errors[] = 'You forgot to enter your current password.';
            } else {
                $p = mysqli_real_escape_string($dbc, trim($_POST['pass']));
            }

            // check for new password and match against old
            if(!empty($_POST['pass1'])) {
                if($_POST['pass1'] != $_POST['pass2']) {
                    $errors[] = 'Your new password did not match the confirmed password.';
                } else {
                    $np = mysqli_real_escape_string($dbc, trim($_POST['pass1']));
                }
            }

            if(empty($errors)) {   // if everything is OK
                // check for rigth email/passord combo
                $q = "SELECT teacher_id FROM teachers WHERE (email='{$_SESSION['email']}' AND pass=SHA1('$p'))";
                $r = @mysqli_query($dbc, $q);
                $num = @mysqli_num_rows($r);
                if($num == 1) {  // match was made
                    $row = mysqli_fetch_array($r, MYSQLI_NUM);

                    // make the update query
                    if(isset($np)) {
                        $q = "UPDATE teachers SET first_name='$fn', last_name='$ln', gender='$g', email='$e', pass=SHA1('$np') WHERE teacher_id=$row[0]";
                        $r = @mysqli_query($dbc, $q);
                    } else {
                        $q = "UPDATE teachers SET first_name='$fn', last_name='$ln', gender='$g', email='$e' WHERE teacher_id=$row[0]";
                        $r = @mysqli_query($dbc, $q);
                    }

                    if(mysqli_affected_rows($dbc) == 1) {  // if it ran OK

                        // query for updated data
                        $q = "SELECT first_name, last_name, gender, email FROM teachers WHERE teacher_id=$row[0]";
                        $r = @mysqli_query($dbc, $q);
                        if(mysqli_num_rows($r) == 1) {
                            $row = mysqli_fetch_array($r, MYSQLI_NUM);

                            // update session data
                            $_SESSION['first_name'] = $row[0];
                            $_SESSION['last_name'] = $row[1];
                            $_SESSION['gender'] = $row[2];
                            $_SESSION['email'] = $row[3];

                            // print message
                            echo '<h1>Thank you!</h1>
                                <p>Your account information has been updated!</p><p><br /></p>'
                            ;
                        }
                    } else {  // if it did NOT run OK
                        // public message
                        echo '<h1>System Error</h1>
                            <p class="error">Your account could not be changed due to a system error.
                            We apologize for any inconvenience.</p>';
                        // debugging message
                        //echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q . '</p>';
                    }

                    // close db
                    mysqli_close($dbc);

                    // include footer and quit script
                    include ('includes/footer.html');
                    exit();
                } else {  // invalid email/password combo
                    echo '<h1>Error!</h1>
                        <p class="error">The email address and password do not match those on file.</p>';
                }
            } else {  // report errors
                echo '<h1>Error!</h1><p class="error">The following error(s) occurred:<br />';
                foreach($errors as $msg) {
                    echo " - $msg<br />\n";
                }
                echo '</p><p>Please try again.</p><p><br /></p>';
            }  // END of if(empty($errors)) IF

            // close db
            mysqli_close($dbc);
        } // END of main submit IF

        // show edit form
        echo '<form action="edit_teacher_account.php" method="post">
            <p>First Name: <input type="text" name="first_name" size="20" maxlength="60" value="' . 
                ((isset($_POST['first_name'])) ? htmlentities($_POST['first_name']) : $_SESSION['first_name']) . '" />
            </p>
            <p>Last Name: <input type="text" name="last_name" size="20" maxlength="60" value="' . 
                ((isset($_POST['last_name'])) ? htmlentities($_POST['last_name']) : $_SESSION['last_name']) . '" />
            </p>
            <p>Gender: <input type="text" name="gender" size="20" maxlength="60" value="' . 
                ((isset($_POST['gender'])) ? htmlentities($_POST['gender']) : $_SESSION['gender']) . '" /> (M for male, F for female)
            </p>
            <p>Email Address: <input type="text" name="email" size="20" maxlength="60" value="' . 
                ((isset($_POST['email'])) ? htmlentities($_POST['email']) : $_SESSION['email']) . '" />
            </p>
            <p>Current Password: <input type="password" name="pass" size="10" maxlength="20" value="' . 
                ((isset($_POST['pass'])) ? htmlentities($_POST['pass']) : NULL) . '" /> **Required for updating account
            </p>
            <p>New Password: <input type="password" name="pass1" size="10" maxlength="20" value="'. 
                ((isset($_POST['pass1'])) ? htmlentities($_POST['pass1']) : NULL) . '" />
            </p>
            <p>Confirm New Password: <input type="password" name="pass2" size="10" maxlength="20" value="' .  
                ((isset($_POST['pass2'])) ? htmlentities($_POST['pass2']) : NULL) . '" />
            </p>
            <p><input type="submit" name="submit" value="Update Account" /></p>
        </form>';

    } else {
        echo '<p>Hello! You are currently not logged in. You need to login in order to use the site<br /><br /></p>';
    }

    include ('includes/footer.html');

?>