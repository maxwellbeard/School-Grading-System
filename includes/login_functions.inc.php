<?php # login_functions.inc.php
    // this page defines two functions used by the login/logout process

    /* This function determines an absolute URL and redirects the user there.
     * The function takes one argument: the page to be redirected to.
     * The argument defaults to index.php 
    */
    function redirect_user($page = 'index.php') {

        // start defining the URL
        // URL is http:// plus the host name plus the current directory
        $url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);

        // remove any trailing slashes
        $url = rtrim($url, '/\\');

        // add the pages
        $url .= '/' . $page;

        // redirect the user
        header("Location: $url");
        exit();

    } // end of redirect_user()

    /* This function validates the form data (the email and password).
     * If both are present, the database is queried.
     * The function requires a database connection.
     * The function returns an array of info, including:
     * - a TRUE/FALSE value indicating success
     * - an array of either errors or the db result
    */
    function check_login($dbc, $email = '', $pass = '') {

        // initialize error array
        $errors = array();

        // validate the email address
        if(empty($email)) {
            $errors[] = 'You forgot to enter your email address.';
        } else {
            $e = mysqli_real_escape_string($dbc, trim($email));
        }

        //  validate password
        if(empty($pass)) {
            $errors[] = 'You forgot the enter your password.';
        } else {
            $p = mysqli_real_escape_string($dbc, trim($pass));
        }

        // if everything is OK
        if(empty($errors)) {

            // retrieve the user_id and first_name for that email/password combo
            $q = "SELECT teacher_id, first_name, last_name, gender, email
                FROM teachers
                WHERE email='$e' AND pass=SHA1('$p')"
            ;
            $r = @mysqli_query($dbc, $q);

            // check results
            if(mysqli_num_rows($r) == 1) {

                // fetch records
                $row = mysqli_fetch_array($r, MYSQLI_ASSOC);

                // return  true and the record
                return array(true, $row);
            } else {
            // not a match
                $errors[] = 'The email address and password entered do not match those on file.';
            }
        } // end of errors if

        // return false and errors
        return array(false, $errors);

    } // end check_login()

?>