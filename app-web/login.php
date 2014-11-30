<?php
//need to add the token and all the logic...
?>
<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
<head>
    <title>LogIn Page</title>
</head>
<body>
    <div align='center'>
        <form action='login2.php' method='POST'>
            Username<input type='text' name='user' class='inputStyled' /><br>
            Password <input type='password' name='pass' class='inputStyled' /><br>
            <input type='submit' value='Login' class='inputButtonStyled' /><br><br>
        </form>
        <a href="register.php">Register</a><br><br>
        <a href="lostToken.php">Lost Token?</a><br><br>
    </div>

    <?php
    if(isset($_POST['user']) && isset($_POST['pass']) && $_POST['user'] != '' && $_POST['pass'] != ''){

        echo '<br> OK <br>';
    }
    ?>
</body>
</html>

