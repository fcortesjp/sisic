<?php

	//$database = "franc451_CR2013";  // the name of the database.
	$database = "franc451_cr2013";

	$server = "localhost";  // server to connect to.

	//string that holds the first part of the pdo object
	$pdostring = 'mysql:host='.$server.';dbname='.$database.';charset=utf8';

	//$db_user = "franc451_frank34";  // mysql username to access the database with.
	//$db_user = "franc451_CR2013";
	$db_user = "root";

	//$db_pass = "52190Lkwdic";  // mysql password to access the database with.
	$db_pass = "52190Lkwdic";

	try
	{
		$conn = new PDO($pdostring, $db_user, $db_pass);

		//attributes
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	}catch(PDOException $ex){
    	echo 'ERROR: ' . $ex->getMessage();
	}
	
	function query($sql, $arr){
		try {
			$statement = $conn->prepare($sql);
            $statement->execute($arr);
            $resultado = $statement->fetchAll();
            return $resultado;
        } catch(PDOException $ex) {
            echo "An Error occured!"; //handle me.
        }
	}
?>