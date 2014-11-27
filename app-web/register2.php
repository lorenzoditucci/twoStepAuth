<?php
include_once('connection.php');

$name = $_POST['name'];
$surname =$_POST['surname'];
$email = $_POST['mail'];
$user = $_POST['username'];
$password = $_POST['password'];
$secretQuestion = $_POST['squest'];
$secretAnswer = $_POST['sansw'];

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

if($db){
    echo 'registered!! <br>';
    echo '<a href=login.php>Login!</a>';
}
?>