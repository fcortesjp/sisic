<?php
	session_start();  //session
	
	if(isset($_SESSION['currentUser'])) // if the super global variable session called current user has been initialized then
	{
		$currentUser = $_SESSION['currentUser'];
		$userID = $_SESSION['currentID'];
		$role = $_SESSION['role'];
		
		include("config-CRDB.php"); //including config.php in our file to connect to the database
		
		//get the subjects user/teacher is in charge of or if role is d (director) get all the classes        
        
		$sql = 	"SELECT ID, CONCAT(Class.Class, ' - ', `Class Copesal`.Subject) AS ClassSubject ".
				"FROM `Class Copesal` ". 
				"INNER JOIN Class ".
				"ON `Class Copesal`.ClassID = Class.`Class ID` ".
				"WHERE ID = ".$_POST["CLASS_ID"];
				
		$recordset = mysql_query($sql) or die("error in Query: ". mysql_error());
		$clases_periodos = "";
		while ($row = mysql_fetch_array($recordset)) 
        {
        	$CLASS_SUBJECT = $row["ClassSubject"];
        }
		
		$sql = 	"SELECT b.goal as LOGRO, b.ID as ID_LOGRO 
				FROM indicator a
				LEFT JOIN goal b ON a.Goal_ID = b.ID
				WHERE a.`Class Copesal_id` = ".$_POST["CLASS_ID"]."
				GROUP BY b.goal";
	
		$recordset = mysql_query($sql) or die("error in Query: ". mysql_error());
		$clases_periodos = "";
		$i = 1;
		$LIST = "";
		while ($row = mysql_fetch_array($recordset)) 
        {
        	$sql2 = 	"SELECT a.indicator as INDICATOR, a.ID as ID_INDICATOR
					FROM indicator a
					LEFT JOIN goal b ON a.Goal_ID = b.ID
					WHERE a.`Class Copesal_id` = ".$_POST["CLASS_ID"]."
					AND Goal_ID = ".$row["ID_LOGRO"];

			$recordset2 = mysql_query($sql2) or die("error in Query: ". mysql_error());
			$clases_periodos = "";
			$i = 1;
			$INDICATOR = "";
			$NOTAS = "";
			while ($row_logro = mysql_fetch_array($recordset2)) 
	        {
	        	$INDICATOR .= "<td>".$row_logro["INDICATOR"]."</td>";
	        	$NOTAS .= "<td><input type='text' size='2' id='NOTA_".$row_logro["ID_INDICATOR"]."_".$_POST["CLASS_ID"]."_".$_POST["PERIODO"]."' /></td>";
	        }
	        
	        $sql_stu = "SELECT a.`Student ID` as identification, CONCAT(a.`Student First`, ' ', a.`Student Last`) as NOMBRES, 
					a.`Identification`, c.`ID` as ID_CLASE_MATERIA, c.`Subject` as NOMBRE_MATERIA, 
					d.`Class ID` as ID_GRADO, d.`Class` as NOMBRE_GRADO
					FROM `student` a
					LEFT JOIN `student - class - grade` b ON a.`Student ID` = b.`Student ID`
					LEFT JOIN `class copesal` c ON b.Class_ID = c.ID
					LEFT JOIN class d ON c.ClassID = d.`Class ID`
					WHERE c.ID = ".$_POST["CLASS_ID"];
		
			$recordset_stu = mysql_query($sql_stu) or die("error in Query: ". mysql_error());
			$STUDENTS = "";
			while ($row_students = mysql_fetch_array($recordset_stu)) 
	        {
	        	$STUDENTS .= "<tr>
	        		<td>".$row_students["identification"]."</td>
	        		<td>".$row_students["NOMBRES"]."</td>
	        		$NOTAS
	        	</tr>";
	        }
	        
        	$LIST .= "<tr>
        		<td colspan='2'><b>LOGRO $i: ".$row["LOGRO"]."</b></td>
        		$INDICATOR
        	</tr>
        	<tr>
        		$STUDENTS
        	</tr>";
        	$i++;
        }
        
		// Close de conection
		mysql_close($link);
		
		
		$diccionario = array( "USUARIO" => $currentUser,
			"CLASES_PERIODOS" => $clases_periodos,
			"CLASS_SUBJECT" => $CLASS_SUBJECT,
			"LIST" => $LIST,
			"PERIODO" => $_POST["PERIODO"]
		);
		$file = "html/notas_list.html";
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