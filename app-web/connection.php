<?php
//mi connetto al database
$password = "pinguino";
$user = "ldituc2";
$db_database = "ldtcourses";

$connection = mysql_connect("ldtcourses.mysql.uic.edu", $user, $password)
    or die("Connection error: " . mysql_error());
mysql_select_db($db_database, $connection);
?>