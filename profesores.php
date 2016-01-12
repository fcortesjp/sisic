<?php
    session_start();  //session  
?>

<!DOCTYPE html>
	
<head>
	<meta http-equiv="Content-Type" content="text/html"> <!-- charset=utf-8 will make accents show on page but will fuckup if accents come from database-->
	
	<script type="text/javascript" charset="utf-8">
	
		function enrollteacher() 
		{
			var teacherform = document.getElementById ("teacherform");
			teacherform.action = "enrollteacher.php";
			teacherform.submit();
			//alert("Submit button clicked!");	
		}
		
		function updateteacher() 
		{
			var teacherform = document.getElementById ("teacherform");
			teacherform.action = "preupdateteacher.php";
			teacherform.submit();
		}
		
		function reportteacher() 
		{
			var teacherform = document.getElementById ("teacherform");
			teacherform.action = "reportteacher.php";
			teacherform.submit();
			
		}
	   
	</script>
	
</head>
<body>
	<?php
		
		if(isset($_SESSION['currentUser'])) // if the super global variable session called current user has been initialized then
		{
			$currentUser = $_SESSION['currentUser'];
			$userID = $_SESSION['currentID'];
			$role = $_SESSION['role'];
			
			
			include("config-CRDB.php"); //including config.php in our file to connect to the database
			
			//get the subjects user/teacher is in charge of or if role is d (director) get all the classes
			
			if ($role == 'd') 
			{
				//if user logged is a director set this variable for themenu to display
				
				echo "Bienvenido(a) ".$currentUser;
				echo "</br>";
				echo "<a href='logout.php'>Logout</a>"; 

				?>
				<div align="center">
				
					<form name='teacherform' id='teacherform' method='post'>
							
						<h3>Profesores</h3>
						<a href="menu.php">Regresar</a>
		                </br></br>
						<table align="center" border="0">
						
							<tr>
								
								<td> <!-- buton de Matricular Estudiante -->
									<button type="button" style="height: 100px; width: 100px" name="IngresarProfesor" align="center" id="IngresarProfesor" onclick="enrollteacher()" >Ingresar Profesor</button>
								</td>
								
								<td> <!-- buton de Editar Estudiante Existente -->
									<button type="button" style="height: 100px; width: 100px" name="EditarProfesor" align="center" id="EditarProfesor" onclick="updateteacher()" >Editar Profesor</button>
								</td>
								
								<td> <!-- buton de Generar reportes de matriculas -->
									<button type="button" style="height: 100px; width: 100px" name="reportedeprofesores" align="center" id="reportedeprofesores" onclick="reportteacher()">Reportes de Profesores</button>
								</td>
								
							</tr>
							
						</table>
						</br>
						<a href="menu.php">Regresar</a>
					</form>
				</div>
	<?php
			}
			else
			{
				echo "You need to be authenticated as an admin to see the content of this page";
		    	echo "</br>";
	        	echo "<a href='logout.php'>Log In</a>";
			}
		}		
	?>
</body>
