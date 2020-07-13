<?php # view_classroom.php
// this script displays the current teachers classroom
// it display each student and their grades with option to contact student

    // session 
    session_name('YourSession');
    session_start();
    if(isset($_SESSION['teacher_id'])) {
        $teacher_id = $_SESSION['teacher_id'];
    }

    $page_title = 'View Your Classroom';
    include ('includes/header.html');
    //include ('includes/handle_errors.php');

    echo '<h1>View Your Classroom</h1>';

    // check if user is logged in
    if(isset($_SESSION['teacher_id'])) {

        // connect to database
        require_once ('../../../../mysqli_connect.php');

        // determine the sort
        // default is by name
        $sort = (isset($_GET['sort'])) ? $_GET['sort'] : 'n';

        // determine the sorting order
        switch($sort) {
            case 'n':
                $order_by = 'sName ASC';
                break;
            case 'g':
                $order_by = 'grade ASC';
                break;
            case 'e':
                $order_by = 'email ASC';
                break;
            default:
                $order_by = 'sName ASC';
                $sort = 'n';
            break;
        }

        $q = "SELECT name, description FROM classes WHERE teacher_id=$teacher_id";
        $r = @mysqli_query($dbc, $q);
        $row = mysqli_fetch_array($r, MYSQLI_ASSOC);
        echo '<p><b>Class Name:</b> ' . $row['name'] . '<br />
            <b>Class Description:</b> ' . $row['description'] . '<br />'
        ;
        unset($row);

        // create query
        $q = "SELECT CONCAT(s.first_name, ' ', s.last_name) AS sName, g.grade, s.email, s.student_id
            FROM teachers AS t
            INNER JOIN classes AS c
            USING (teacher_id)
            INNER JOIN grades AS g
            USING (class_id)
            INNER JOIN students AS s
            USING (student_id)
            WHERE t.teacher_id=$teacher_id
            ORDER BY $order_by"
        ;
        $r = @mysqli_query($dbc, $q);

        // get number of returned rows
        $num = mysqli_num_rows($r);
        echo '<b>Current # of Students:</b> ' . $num . '<br /><br /></p>';
        
        if($num > 0) {
            // validate query
            echo '<table align="center" cellspacing="3" cellpadding="3" width="75%">
                <tr>
                    <td align="left"><b>Edit</b></td>
                    <td align="left"><b>Remove</b></td> 
                    <td align="left"><b><a href="view_classroom.php?sort=n">Name</a></b></td>
                    <td align="left"><b><a href="view_classroom.php?sort=g">Grade</a></b></td>
                    <td align="left"><b><a href="view_classroom.php?sort=e">Email (contact student)</a></b></td>
                </tr>'
            ;
            
            // fetch and print all records
            $bg = '#eeeeee'; // set the initial background color
            while($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
                $bg = ($bg == '#eeeeee' ? '#ffffff' : '#eeeeee'); // switch the bg color
                echo '<tr bgcolor="' . $bg . '">
                    <td align="left"><a href="edit_student.php?id=' . $row['student_id'] . '">Edit</a></td>
                    <td align="left"><a href="remove_student.php?id=' . $row['student_id'] . '">Remove</a></td>
                    <td align="left">' . $row['sName'] . '</td>
                    <td align="left">' . $row['grade'] . '%</td>
                    <td align="left"><a href="email.php?e=' . $row['email'] . '">' . $row['email'] . '</a></td>
                </tr>';
            }
            echo '</table>'; // close table

            mysqli_free_result($r);
            mysqli_close($dbc);

        }
    } else {
        echo '<p>Hello! You are currently not logged in. You need to login in order to use the site<br /><br /></p>';
    }

    include ('includes/footer.html');

?>