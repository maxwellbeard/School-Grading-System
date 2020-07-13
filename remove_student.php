<?php # remove_student.php
// this script allows for the teacher to delete a student from the class

    // set the sessions
    session_name('YourSession');
    session_start();

    require_once ('../../../../mysqli_connect.php');

    $page_title = 'Remove Student';
    include ('includes/header.html');
    //include ('includes/handle_errors.php');

    echo '<h1>Remove Student</h1>';

    if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) {
        $id = $_GET['id'];
    } elseif ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) ) {
        $id = $_POST['id'];
    } else {
        echo '<p class="error">This page has been accessed in error.</p>';
        include ('includes/footer.html');
        exit();
    }

    // check if form has been submitted
    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        if($_POST['sure'] == 'Yes') {
        // delete record

            // make query
            $q = "DELETE FROM grades WHERE student_id=$id LIMIT 1";
            $r = @mysqli_query($dbc, $q);

            if(mysqli_affected_rows($dbc) == 1) { // if it ran OK
                // delete student
                $q = "DELETE FROM students WHERE student_id=$id LIMIT 1";
                $r= @mysqli_query($dbc, $q);
                if(mysqli_affected_rows($dbc) == 1) {
                    echo '<p>The student has been removed.</p>';
                } else {
                    echo '<p class="error">The student could not be removed due to a system error.</p>';
                    // debugging message (remove if fixed)
                    //echo '<p>' . mysqli_errors($dbc) . '<br />Query: ' . $q . '</p>';
                }
            } else { // if it did NOT run OK
                echo '<p class="error">The student could not be removed due to a system error.</p>';
                // debugging message (remove if fixed)
                //echo '<p>' . mysqli_errors($dbc) . '<br />Query: ' . $q . '</p>';
            }
        } else { // if no confirmation on deletion
            echo '<p>The student has NOT been removed.</p>';
        } // END $_POST['sure'] IF
    } else { // show form
        // get users info
        $q = "SELECT CONCAT(last_name, ' ', first_name)
            FROM students
            WHERE student_id=$id";
        $r = @mysqli_query($dbc, $q);
        if(mysqli_num_rows($r) == 1) {
        // validate userID, show form
            $row = mysqli_fetch_array($r, MYSQLI_NUM);

            // display record being deleted
            echo "<h3>Name: $row[0]</h3>
                Are you sure you want to remove this student?"
            ;

            // create form
            echo '<form action="remove_student.php" method="post">
                <input type="radio" name="sure" value="Yes" /> Yes
                <input type="radio" name="sure" value"No" checked="checked" /> No
                <input type="submit" name="submit" value="Submit" />
                <input type="hidden" name="id" value="' . $id . '" />
            </form>';
        } else { // not a valid userID
            echo '<p class="error">This page has been accessed in error.</p>';
        }
    }// END main submit IF

    mysqli_close($dbc);

    include ('includes/footer.html');

?>