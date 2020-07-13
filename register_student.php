<?php # register_student.php
// this script allows the teacher to register a student to there class

    // start session
    session_name('YourSession');
    session_start();

    $page_title = 'Register Student';
    include ('includes/header.html');
    //include ('includes/handle_errors.php');

    echo '<h1>Register a Student</h1>';

    // check if user is logged in
    if(isset($_SESSION['teacher_id'])) {

        require_once ('../../../../mysqli_connect.php');

        // check for form submission
        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            $errrors = array();

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

                // validating email has not been used before
                $q = "SELECT student_id FROM students WHERE email='$e'";
                $r = @mysqli_query($dbc, $q);

                $num_rows = mysqli_num_rows($r);
                if($num_rows > 1){
                    $errors[] = 'Your email address has already been registered.';
                }
            }

            // check for errors
            if(empty($errors)) {

                // query for inserting student
                $q = "INSERT INTO students (first_name, last_name, email) 
                    VALUES ('$fn', '$ln', '$e')"
                ;
                $r = @mysqli_query($dbc, $q);

                if(mysqli_affected_rows($dbc) == 1) {

                    // query for inserting grade using subqueries
                    $q = "INSERT INTO grades (class_id, student_id, grade) 
                        VALUES ( 
                            (SELECT class_id FROM classes WHERE teacher_id={$_SESSION['teacher_id']} LIMIT 1),
                            (SELECT student_id FROM students ORDER BY student_id DESC LIMIT 1),
                            0.00
                        )"
                    ;
                    $r = @mysqli_query($dbc, $q);
                    
                    if(mysqli_affected_rows($dbc) == 1) {
                        echo '<p>The student is now registered for your class.</p>';
                    } else {
                        echo '<h1>System Error</h1>
                            <p class="error">You could not be registered due to
                            a system error. We apologize for any inconvenience.</p>'
                        ;   
                    }
                } else {
                    echo '<h1>System Error</h1>
                        <p class="error">You could not be registered due to
                        a system error. We apologize for any inconvenience.</p>'
                    ;
                }

                // close db
                mysqli_close($dbc);
            } else {
                echo '<h1>Error!</h1>
                    <p class="error">The following error(s) occurred:<br />'
                ;
                foreach($errors as $msg) {
                    echo " - $msg<br />\n";
                }
                echo '</p><p>Please try again.</p><p><br /></p>';

                // close db
                mysqli_close($dbc);
            } // end of if(empty($errors)) IF
        } // end of main submit IF

        // show register form
        echo '<p>Register a student for your class.</p>
            <form action="register_student.php" method="post">
                <p>First Name: <input type="text" name="first_name" value="' .
                    ((isset($_POST['first_name'])) ? $_POST['first_name'] : NULL) . '" />
                </p>
                <p>Last Name: <input type="text" name="last_name" value="' . 
                    ((isset($_POST['last_name'])) ? $_POST['last_name'] : NULL) . '" />
                </p>
                <p>Email Address: <input type="text" name="email" value="' . 
                    ((isset($_POST['email'])) ? $_POST['email'] : NULL) . '" />
                </p>
                <p><input type="submit" name="submit" value="Register" /></p>
            </form>'
        ;

    } else {
        echo '<p>Hello! You are currently not logged in. You need to login in order to use the site<br /><br /></p>';
    }

    include ('includes/footer.html');

?>