<?php
include_once('connection.php');

$user = $_POST['user'];
$password = $_POST['pass'];
$token = $_POST['token'];

if($user == '' || $password == '' || $token == ''){
    header('location:login.php');

    //log data
    $what = "login-failed";
    $description = "empty fields";
    $when= time();
}

$queryGet = 'SELECT name,surname,password FROM users WHERE username="'.$user.'"';
$db = mysql_query($queryGet, $connection);
$var = mysql_fetch_array($db);
$name = $var['name'];

//check if user is valid
$queryValid = 'SELECT valid FROM logToken WHERE user = "'.$user.'"';
$database = mysql_query($queryValid,$connection);
$valid = mysql_fetch_array($database);




//$hash = sha1(sha1($password.sha1($user.sha1($var['name'].$var['surname']))));
$hash = hash('sha512',hash('sha512', $password.hash('sha512', $user.hash('sha512',$name.$user))));

$user = addslashes($user);

//I have to check the token
$queryToken = "SELECT timestamp, salt FROM logToken WHERE user = '".$user."'";
$data = mysql_query($queryToken, $connection);
$tokenData = mysql_fetch_array($data);
$timeDb = $tokenData['timestamp'];
$saltDb = $tokenData['salt'];
$now = time();
if(($now - $timeDb) > 30){
    echo "token expired <a href='login.php'>Back</a>";

    //log data
    $what = "login-failed";
    $description = "token expired - username: ".$user;
    $when= time();
}else{
    $tokenDb = hash('sha512',hash('sha512',$user).hash('sha512',$timeDb).hash('sha512',$saltDb));
    $tokenDb = substr($tokenDb,0,9);



    if($var['password'] == $hash && $valid['valid'] == 1 && $tokenDb == $token){
        echo "<br>OK<br>";

        //log data
        $what = "login-ok";
        $description = "user: ".$user;
        $when= time();
    }else{

        //log data
        $what = "login-failed";
        $description = "user ".$user." valid : ".$valid;
        $when= time();

        //header('location:login.php');
        //echo "data....<br>";
        //echo "<br>".$queryGet;
        //echo "<br> user...".$user."<br>";
        //echo "<br> password..".$password;
        //echo "<br> hashata..".$hash;
        //echo "<br> db -> ".$var['password'];
        //echo "<br>typed token -> ".$token;
        //echo "<br>calculated -> ".$tokenDb;

    }
}

//store the log
$queryLog = "INSERT INTO log (what, description, timestamp) VALUES ('".$what."', '".$description."', '".$when."')";
$db = mysql_query($queryLog, $connection);


?>