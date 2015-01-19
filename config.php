<?php
$database = "franc451_icopesal";  // the name of the database.
$server = "localhost";  // server to connect to.
$db_user = "franc451_frank3";  // mysql username to access the database with.
$db_pass = "52190Lkwdic";  // mysql password to access the database with.
$table = "users";    // the table that this script will set up and use.
$link = mysql_connect($server, $db_user, $db_pass);
mysql_select_db($database,$link);
?>