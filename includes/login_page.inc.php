<?php # login_page.inc.php

    $page_title = 'Login';
    include ('includes/header.html');
    include ('includes/handle_errors.php');

    // print any errors if any
    if(isset($errors) && !empty($errors)) {
        echo '<h1>Error!</h1>
            <p class="error">The following error(s) occurred:<br />';
        foreach($errors as $msg) {
            echo " - $msg<br />\n";
        }
        echo '</p><p>Please try again.</p>';
    }

?>

<h1>Login</h1>
<form action="login.php" method="post">
    <p>Email Address: <input type="text" name="email" size="20" maxlength="60" value="<?php 
        if(isset($_POST['email'])) echo $_POST['email']; ?>" />
    </p>
    <p>Password: <input type="password" name="pass" size="20" maxlength="20" value="<?php 
        if(isset($_POST['pass'])) echo $_POST['pass']; ?>" />
    </p>
    <p><input type="submit" name="submit" value="Login" /></p>
</form>

<?php include ('includes/footer.html'); ?>