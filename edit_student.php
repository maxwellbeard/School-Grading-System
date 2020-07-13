<?php # edit_student.php
// this script allows for the teacher to edit a students information:
// their name, email, and grade

    // set the sessions
    session_name('YourSession');
    session_start();

    require ('../../../../mysqli_connect.php');

    $page_title = 'Edit Student';
    include ('includes/header.html');
    //include ('includes/handle_errors.php');

    echo '<h1>Edit a Student</h1>';

    // check for valid user ID, through GET or POST
    if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) {
        $id = $_GET['id'];
    } elseif ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) ) {
        $id = $_POST['id'];
    } else {
        echo '<p class="error">This page has been accessed in errors.</p>';
        include ('includes/footer.html');
        exit();
    }

    // check if form has been submitted
    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        $errors = array();

        // check for first name
        if(empty($_POST['first_name'])) {
            $errors[] = 'You forgot to enter the first name.';
        } else {
            $fn = mysqli_real_escape_string($dbc, trim($_POST['first_name']));
        }

        // check for last name
        if(empty($_POST['last_name'])) {
            $errors[] = 'You forgot to enter the last name.';
        } else {
            $ln = mysqli_real_escape_string($dbc, trim($_POST['last_name']));
        }

        // check for email
        if(empty($_POST['email'])) {
            $errors[] = 'You forgot to enter the email address.';
        } else {
            $e = mysqli_real_escape_string($dbc, trim($_POST['email']));
        }

        // check for grade
        if(empty($_POST['grade'])) {
            $errors[] = 'You forgot to enter the grade';
        } else {
            if((is_numeric($_POST['grade'])) && ($_POST['grade'] >= 0) && ($_POST['grade'] <= 100)) {
                $g = mysqli_real_escape_string($dbc, trim($_POST['grade']));
            } else {
                $errors[] = 'The grade needs to be a valid percentage grade';
            }
        }

        if(empty($errors)) {
        // if eveything is OK

            // test for unique email
            $q = "SELECT student_id
                FROM students
                WHERE email='$e' AND student_id != $id"
            ;
            $r = @mysqli_query($dbc, $q);
            if(mysqli_num_rows($r) == 0) {

                // make query
                $q = "UPDATE students SET first_name='$fn', last_name='$ln', email='$e'
                    WHERE student_id=$id LIMIT 1"
                ;
                $r = @mysqli_query($dbc, $q);
                if(mysqli_affected_rows($dbc) == 1 || mysqli_affected_rows($dbc) == 0) {
                // if it ran OK
                    // query for updating grade
                    $q = "UPDATE grades SET grade=$g WHERE student_id=$id LIMIT 1";
                    $r = @mysqli_query($dbc, $q);
                    if(mysqli_affected_rows($dbc) == 1 || mysqli_affected_rows($dbc) == 0) {
                        // print message
                        echo '<p>The student has been edited.</p>';
                    } else {
                        // if it did NOT run OK
                        echo '<p class="error">The student could not be edited due to a system error.
                            We apologize for any inconvenience.</p>'
                        ;
                        // debugging message (remove if fixed)
                        //echo '<p>' . mysqli_errors($dbc) . '<br />Query: ' . $q . '</p>';
                    }
                } else {
                // if it did NOT run OK
                    echo '<p class="error">The student could not be edited due to a system error.
                        We apologize for any inconvenience.</p>'
                    ;

                    // debugging message (remove if fixed)
                    //echo '<p>' . mysqli_error($dbc) . '<br />Query: ' . $q . '</p>';
                }
            } else {
            // already registered
                echo '<p class="error">The email address has already been registered.</p>';
            }
        } else {
        // report errors
            echo '<p class="error">The following error(s) occurred:<br />';
            foreach($errors as $msg) {
                echo " - $msg<br />\n";
            }
            echo '</p><p>Please try again.</p>';
        } // END errors IF
    } // END main submit IF

    // always showing form

    // retrieve students info
    $q = "SELECT s.first_name, s.last_name, s.email, g.grade
        FROM students AS s
        INNER JOIN grades AS g
        USING (student_id)
        WHERE s.student_id=$id"
    ;
    $r = @mysqli_query($dbc, $q);

    if(mysqli_num_rows($r) == 1) {
    // valid user ID, show form

        // get student info
        $row = mysqli_fetch_array($r, MYSQLI_NUM);

        // create form
        echo '<form action="edit_student.php" method="post">
            <p>First Name: <input type="text" name="first_name" size="15" maxlength="15" value="' . 
                ((isset($_POST['first_name'])) ? htmlentities($_POST['first_name']) : $row[0]) . '" /></p>
            <p>Last Name: <input type="text" name="last_name" size="15" maxlength="30" value="' . 
                ((isset($_POST['last_name'])) ? htmlentities($_POST['last_name']) : $row[1]) . '" /></p>
            <p>Email Address: <input type="text" name="email" size="20" maxlength="60" value="' . 
                ((isset($_POST['email'])) ? htmlentities($_POST['email']) : $row[2]) . '" /></p>
            <p>Grade: <input type="text" name="grade" size="10" maxlength="20" value="' . 
                ((isset($_POST['grade'])) ? htmlentities($_POST['grade']) : $row[3]) . '" /></p>
            <p><input type="submit" name="submit" value="Submit" /></p>
            <input type="hidden" name="id" value="'. $id . '" />
        </form>';
    } else {
    // not a valid user ID
        echo '<p class="error">This page has been accessed in errors.</p>';
    }

    mysqli_close($dbc);

    include ('includes/footer.html');

?>