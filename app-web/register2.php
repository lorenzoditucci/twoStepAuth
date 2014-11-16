<?php
include_once('connection.php');

$name = $_POST['name'];
$surname =$_POST['surname'];
$email = $_POST['mail'];
$user = $_POST['username'];
$password = $_POST['password'];

$hash = sha1(sha1($password.sha1($user.sha1($name.$surname))));
//echo $name.$surname.$email.$user.$password;
//echo "<br> invece ... ".$hash;

//add slashes
$name = addslashes($name);
$surname = addslashes($surname);
$email = addslashes($email);
$user = addslashes($user);
//$password = addslashes($password);


$query = 'INSERT INTO users (username, password,name,surname,email) VALUES ("'.$user.'", "'.$hash.'", "'.$name.'", "'.$surname.'", "'.$email.'")';
$db = mysql_query($query, $connection);

//echo "<br>".$query."<br>";
//echo "<br>".$db;
?>