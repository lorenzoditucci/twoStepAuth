<?php
/*
 * the real token should work in this way
 * at the first time, given the token takes the salt and the
 */

include_once('connection.php');

$now = time();

//take it from the address..
$user = $_GET['user'];


//take the salt
$querySalt = 'SELECT salt FROM logToken WHERE user= "'.$user.'"';
$database = mysql_query($querySalt, $connection);
$var = mysql_fetch_array($database);

$salt = $var['salt'];

//update the timestamp....
$updateTimestamp = 'UPDATE logToken SET timestamp = "'.$now.'" WHERE user= "'.$user.'"';
$db = mysql_query($updateTimestamp,$connection);



//the token is the hash of the concatenation of the hash of username + timestamp + salt
$token = hash('sha512',hash('sha512',$user).hash('sha512',$now).hash('sha512',$salt));

$token = substr($token,0,9);

echo "<br>Token<br>";
echo $token;

