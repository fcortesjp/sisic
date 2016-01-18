<?php
	session_start();  //session
	
	if(isset($_SESSION['currentUser'])) // if the super global variable session called current user has been initialized then
	{
		$currentUser = $_SESSION['currentUser'];
		$userID = $_SESSION['currentID'];
		$role = $_SESSION['role'];
		
		include("config_sisic.php"); //including config.php in our file to connect to the database
		
		//get the subjects user/teacher is in charge of or if role is d (director) get all the classes
		if ($role == 'd') 
		{
			$sql = 	"SELECT ID, CONCAT(Class.Class, ' - ', `Class Copesal`.Subject) AS ClassSubject ".
					"FROM `Class Copesal` ". 
					"INNER JOIN Class ".
					"ON `Class Copesal`.ClassID = Class.`Class ID` ".
					"ORDER BY Class.`Class ID`;";
		}else{
			$sql = 	"SELECT ID, CONCAT(Class.Class, ' - ', `Class Copesal`.Subject) AS ClassSubject ".
					"FROM `Class Copesal` ". 
					"INNER JOIN Class ".
					"ON `Class Copesal`.ClassID = Class.`Class ID` ".
					"WHERE TeacherID = :TeacherID ".
					"ORDER BY Class.`Class ID`;";
			$arr = array(':TeacherID' => $userID);
		}

		$resultado = query($sql, $arr);
				
		$clases_periodos = "";
		foreach($resultado as $row) 
        {

        	$clases_periodos .= "<tr>";
            $clases_periodos .= "<td>" . $row['ClassSubject'] . "</td>";
            $clases_periodos .= "<td><input type='radio' name='periodo". $row['ID'] ."' id='1' value='1' > 1</input>";
            $clases_periodos .= "<input type='radio' name='periodo". $row['ID'] ."' id='2' value='2'> 2</input>";
            $clases_periodos .= "<input type='radio' name='periodo". $row['ID'] ."' id='3' value='3'> 3</input>";
            $clases_periodos .= "<input type='radio' name='periodo". $row['ID'] ."' id='4' value='4'> 4</input></td>";
            $clases_periodos .= "</tr>";
        }

		$diccionario = array( "USUARIO" => $currentUser,
			"CLASES_PERIODOS" => $clases_periodos
		);
		$file = "html/notas.html";		
		$content = file_get_contents($file);
		foreach($diccionario as $clave => $valor){
			$content = str_replace("{".$clave."}",$valor,$content);
		}
		echo $content;
	    
	}else{
	    // indicate that the user has not logged in yet
	    echo "You need to be authenticated to see the content of this page"; //otherwise say your are not logged in.
	    echo "</br>";
        echo "<a href='logout.php'>Log In</a>";
	}
?>