<?php
include_once('connection.php');

$user = $_POST['user'];
$password = $_POST['pass'];

$queryGet = 'SELECT name,surname,password FROM users WHERE username="'.$user.'"';
$db = mysql_query($queryGet, $connection);
$var = mysql_fetch_array($db);
$name = $var['name'];



//$hash = sha1(sha1($password.sha1($user.sha1($var['name'].$var['surname']))));
$hash = hash('sha512',hash('sha512', $password.hash('sha512', $user.hash('sha512',$name.$user))));

$user = addslashes($user);



if($var['password'] == $hash){
    echo "<br>OK<br>";
}else{
    //echo "data....<br>";
    //echo "<br>".$queryGet;
    //echo "<br> user...".$user."<br>";
    //echo "<br> password..".$password;
    //echo "<br> hashata..".$hash;
    //echo "<br> db -> ".$var['password'];

}
?>