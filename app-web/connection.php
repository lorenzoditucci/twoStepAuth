<?php
//mi connetto al database
$password = "";
$user = "";
$db_database = "";

$connection = mysql_connect(".mysql.uic.edu", $user, $password)
    or die("Connection error: " . mysql_error());
mysql_select_db($db_database, $connection);
?>
