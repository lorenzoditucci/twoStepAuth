<?php
include_once('connection.php');

$name = $_POST['name'];
$surname =$_POST['surname'];
$email = $_POST['mail'];
$user = $_POST['username'];
$password = $_POST['password'];
$secretQuestion = $_POST['squest'];
$secretAnswer = $_POST['sansw'];

if($name == '' || $surname == '' || $email == '' || $user == '' || $password == '' || $secretQuestion == '' || $secretAnswer == ''){
    echo "error, values missing...!!";
}else{
    //$hash = sha1(sha1($password.sha1($user.sha1($name.$surname))));

    $hash = hash('sha512',hash('sha512', $password.hash('sha512', $user.hash('sha512',$name.$user))));

    $hashSecretAnswer = hash('sha512', $secretAnswer);
//echo $name.$surname.$email.$user.$password;
//echo "<br> invece ... ".$hash;

//add slashes
    $name = addslashes($name);
    $surname = addslashes($surname);
    $email = addslashes($email);
    $user = addslashes($user);
//$password = addslashes($password);


    $query = 'INSERT INTO users (username, password,name,surname,email,secretQuestion,secretAnswer) VALUES ("'.$user.'", "'.$hash.'", "'.$name.'", "'.$surname.'", "'.$email.'","'.$secretQuestion.'","'.$hashSecretAnswer.'")';
    $db = mysql_query($query, $connection);

//echo "<br>".$query."<br>";
//echo "<br>".$db;

    /*
     * let's say that the salt is the timestamp of now (registration) concatenated to the number of letter of the username multiplied
     * for 809
     */
    $num = count($user) * 809;
    $now = time();
    $salt = $now.$num;

//then, the hash of the hash...
    $salt = hash('sha512', hash('sha512', $salt));

//the token is the hash of the concatenation of the hash of username + timestamp + salt
    $token = hash('sha512',hash('sha512',$user).hash('sha512',$now).hash('sha512',$salt));

    $token = substr($token,0,9);

    $query = 'INSERT INTO logToken (user,token,timestamp,salt,valid) VALUES ("'.$user.'","'.$token.'","'.$now.'","'.$salt.'","0")';
    $db2 = mysql_query($query,$connection);

//let's send the email....

    $address = $email;

    $text = "<html>
            <head>
                <title>Registration</title>
            </head>
            <body>
            Hi! you have been registred! your token is ".$token."<br>
           You have 24h to complete the registration from the application.<br>
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
    }

    if($db && $db2){
        echo 'registered && mail sent! please confirm!! <br>';
        echo '<a href=login.php>Login!</a>';
    }else{
        echo 'maybe something wrong, not sure..!!';
        echo "<br>db1 = ".var_dump($db);
        echo "<br>db2 = ".var_dump($db2);
    }
}


?>