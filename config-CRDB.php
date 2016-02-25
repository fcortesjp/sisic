<?php
//$database = "franc451_CR2013";  // the name of the database.
$database = "franc451_cr2013";

$server = "localhost";  // server to connect to.

//$db_user = "franc451_frank34";  // mysql username to access the database with.
//$db_user = "franc451_CR2013";
$db_user = "root";

$db_pass = "52190Lkwdic";  // mysql password to access the database with.
//$db_pass = "52190Lkwdic";
//$db_pass = "";

$link = mysql_connect($server, $db_user, $db_pass);
mysql_select_db($database,$link);

//the values below make sure the connection to the database don't fuck up the accents on the database
//and when the accent values are retrieved from the database
//this values are set as active on the on the vps server where the application is being tested
//but are being commented out on the local mamp server as they don't seem to be needed.

//mysql_query("SET NAMES 'utf8'"); 
//mysql_query("SET CHARACTER SET utf8");
//mysql_query("SET COLLATION_CONNECTION = 'utf8_spanish2_ci'");
?>