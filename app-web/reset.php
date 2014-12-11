<?php
/*
 *  -check secret question
 *  -disable old token
 *  -send email with new token
 */
session_start();
include_once('connection.php');

$user = $_SESSION['user'];
$email = $_SESSION['email'];

//check if the answer is correct...
$secretAnswer = $_POST['secretAnswer'];
$secretAnswer = hash('sha512', $secretAnswer);

$queryAnswer = 'select secretAnswer FROM users WHERE username = "'.$user.'" AND email = "'.$email.'"';
$database = mysql_query($queryAnswer, $connection);
$var = mysql_fetch_array($database);

if($var['secretAnswer'] != $secretAnswer){
    echo "answer wrong";
    echo "<br><a href='login.php'>Come Back</a>";

    //log data
    $what = "newTokenReq - failed";
    $description = "answer wrong for username ".$user." email: ".$email;
    $when= time();
}else{

//if I am here, it is all right... go on

//disable old token by setting the boolean to zero, then create the new token, store it and send the new mail..
//obviously we have to change the salt and the timestamp

    $queryDisable = 'UPDATE logToken SET valid = "0" WHERE user = "'.$user.'"';
    $db = mysql_query($queryDisable,$connection);

//re-calculate the salt
    $num = count($user) * 809;
    $now = time();
    $salt = $now.$num;

//then, the hash of the hash...
    $salt = hash('sha512', hash('sha512', $salt));

//the token is the hash of the concatenation of the hash of username + timestamp + salt
    $token = hash('sha512',hash('sha512',$user).hash('sha512',$now).hash('sha512',$salt));

    $token = substr($token,0,9);

    $query = 'UPDATE logToken SET token = "'.$token.'", timestamp = "'.$now.'", salt = "'.$salt.'" WHERE user = "'.$user.'"';
    $db2 = mysql_query($query,$connection);

//let's send the email....

    $address = $email;

    $text = "<html>
            <head>
                <title>Token Change</title>
            </head>
            <body>
            Hi! your token have been changed! your new token is ".$token."<br>
           You have 24h to complete the activation from the application.<br>
           Bye!<br>
           Staff
           </body>
         </html>";

    $subject = "confirmation mail";

    $additional = "MIME-Version: 1.0\r\n";
    $additional .= "Content-type: text/html; charset=iso-8859-1\r\n";

    $additional .= "From: webmaster@{$_SERVER['SERVER_NAME']}\r\n" .
        "Reply-To: webmaster@{$_SERVER['SERVER_NAME']}\r\n" .
        "X-Mailer: PHP/" . phpversion();

    $mail = mail($address,$subject,$text,$additional);
    if(!$mail){
        echo "<br>Error sending the mail!";
    }else{
        echo "<br>confirmation sent, check the mailbox <a href='login.php'>Come Back</a>";

        //log data
        $what = "newTokenReq - ok";
        $description = "reset ok for user ".$user;
        $when= time();
    }
}


//store the log
$queryLog = "INSERT INTO log (what, description, timestamp) VALUES ('".$what."', '".$description."', '".$when."')";
$db = mysql_query($queryLog, $connection);

