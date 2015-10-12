<?php
@session_start();

//DATABASE DETAILS 
//SET HOSTNAME
$hostname = "localhost";	

//MYSQL USERNAME
$db_username ="root";	

//MYSQL PASSWORD
$db_password="";

//MYSQL DATABASE NAME
$database="bharat_advertisement";

//DATABASE CONNECTION
mysql_connect($hostname,$db_username,$db_password) or die("Failed To Connect the Database.");
mysql_select_db($database) or die("Failed to Select Database");

unset($hostname);
unset($db_username);
unset($db_password);
unset($database);
?>