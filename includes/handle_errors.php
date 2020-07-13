<?php # Script - handle_errors.php

    // flag variable for site status
    define('LIVE', FALSE);

    // create the error handler
    function my_error_handler ($e_number, $e_message, $e_file, $e_line, $e_vars) {
        
        // build the error message
        $message = "An error occured in script '$e_file' on line $e_line: $e_message\n";

        // append $e_vars to $message
        $message .= print_r ($e_vars, 1);

        if (!LIVE) {  // development (print errors)
            echo '<pre>' . $message . "\n";
            debug_print_backtrace();
            echo '</pre><br />';
        } else {  // don't show error
            echo '<div class="error">A system error occurred. We apologize for the inconvenience.</div><br />';
        }
    }  // end of my_error_handler

    // use my error handler
    set_error_handler('my_error_handler');
?>