<?php
/*
 * Lost token page, what to do:
 *  -ask for username
 *  -ask for secret question
 *  -go to reset.php that...
 *  -check secret question
 *  -disable old token
 *  -send email with new token
 */
include_once('connection.php');
session_start();

echo "
        <div align='center'>
        <form action='#' method='POST'>
            Insert Your Username<input type='text' name='user' class='inputStyled' /><br>
            Insert your Registration Email <input type='text' name='email' class='inputStyled' /><br>
            <input type='submit' value='Login' class='inputButtonStyled' /><br><br>
        </form>
    </div>
        ";

if(isset($_POST['user']) && isset($_POST['email'])){
    //query on db searching for the secret question
    $query = 'select secretQuestion FROM users WHERE username = "'.$_POST['user'].'" AND email = "'.$_POST['email'].'"';
    $db = mysql_query($query,$connection);
    $var = mysql_fetch_array($db);
    if(!$var){
        echo "error - <a href='login.php'>Come Back!</a>";
    }else{
        $_SESSION['user'] = $_POST['user'];
        $_SESSION['email'] = $_POST['email'];
        //ask for the secret question
        echo "<div align = 'center'>
            <form action='reset.php' method='POST'>
                ".$var['secretQuestion']." <input type = 'text' name = 'secretAnswer' class='inputStyled' /><br>
            </form>
            </div>";
    }


}

?>
