<?php
    session_start();  //session  
?>

<!DOCTYPE html>
	
<head>
	<meta http-equiv="Content-Type" content="text/html"> <!-- charset=utf-8 will make accents show on page but will fuckup if accents come from database-->
	
	<script type="text/javascript" charset="utf-8">
	
		function LogEInd() 
		{
			var menuForm = document.getElementById ("menu");
			menuForm.action = "admin.php";
			menuForm.submit();
			//alert("Submit button clicked!");	
		}
		
		//matricularestudiante.php
		
		function MatEst() 
		{
			var menuForm = document.getElementById ("menu");
			menuForm.action = "matricularestudiante.php";
			menuForm.submit();
			//alert("Submit button clicked!");	
		}
	
	   //insertobservation.php
        
        function Observador() 
        {
            var menuForm = document.getElementById ("menu");
            menuForm.action = "observador.php";
            menuForm.submit();
            //alert("Submit button clicked!");  
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
			
			//menu to display variable
			
			$menu ="";
			
			include("config-CRDB.php"); //including config.php in our file to connect to the database
			
			//get the subjects user/teacher is in charge of or if role is d (director) get all the classes
			
			if ($role == 'd') 
			{
				//if user logged is a director set this variable for themenu to display
				
				$menu = 'directormenu';
				
			} 
			else 
			{
				//if user logged is teacher set this variable for the menu to display
				
				$menu = 'teachermenu';
			}
		
		echo "Bienvenido(a) ".$currentUser;
		echo "</br>";
		echo "<a href='logout.php'>Logout</a>"; 
			
		}
		else
		{
		
			echo "You need to be authenticated to see the content of this page";
	    	echo "</br>";
        	echo "<a href='logout.php'>Log In</a>";
			
		}
		
	?>
	<div align="center">
	
		<form name='menu' id='menu' method='post'>
		
			<h3>Menu</h3>
				
			<?php if (isset($_SESSION['currentUser']) && $menu == 'directormenu') : ?>
				
				
				<table align="center" border="0">
				
					<tr>
						<td align = "center" colspan="2"> <!-- colspan makes the cell span over 2 cells if the tables is 2 cells wide -->
							Matr&iacute;culas
						</td>
					</tr>
					
					<tr>
						
						<td> <!-- buton de Matricular Alumno -->
							<button type="button" style="height: 100px; width: 100px" name="MatricularEstudiante" align="center" id="MatricularEstudiante" onclick="MatEst()" >Matricular Estudiante</button>
						</td>
						
						<td> <!-- buton de Editar Estudiante Existente -->
							<button type="button" style="height: 100px; width: 100px" name="EditarInformacionEstudianteExistente" align="center" id="EditarInformacionEstudianteExistente" onclick="CheckAndSubmit()" >Editar Informaci&oacute;n Estudiante Existente</button>
						</td>
						
					</tr>
					
					<tr>
						
						<td> <!-- buton de Cancelar Matricula -->
							<button type="button" style="height: 100px; width: 100px" name="CancelarMatricula" align="center" id="CancelarMatricula" onclick="CheckAndSubmit()" >Cancelar Matricula</button>
						</td>
						
						<td> <!-- buton de Generar reportes de matriculas -->
							<button type="button" style="height: 100px; width: 100px" name="GenerarReporteDeMatriculas" align="center" id="GenerarReporteDeMatriculas" onclick="CheckAndSubmit()">Generar Reporte De Matr&iacute;culas</button>
						</td>
						
					</tr>
					
					<tr>
						<td align = "center" colspan="2">
							Observador
						</td>
					</tr>
					
					<tr>
						
						<td align = "center" colspan="2"> <!-- button for the "observador del alumno" -->
							<button type="button" style="height: 100px; width: 100px" name="ObservadorDelAlumno" align="center" id="ObservadorDelAlumno" onclick="Observador()" >Observador Del Alumno</button>
						</td>
						
					</tr>
					
					<tr>
						<td align = "center" colspan="2">
							Logros e Indicadores
						</td>
					</tr>
					
					<tr>
						
						<td align = "center" colspan="2"> <!-- button for the "Logros e Indicadores" -->
							<button type="button" style="height: 100px; width: 100px" name="LogroseIndicadores" align="center" id="LogroseIndicadores" onclick="LogEInd()" >Logros e Indicadores</button>
						</td>
						
					</tr>
				
				</table>
				
		
			
			<?php elseif (isset($_SESSION['currentUser']) && $menu == 'teachermenu') :  ?>
				
			
					
				<table align="center" border="0">
					
					<td align = "center" colspan="2">
						Logros e Indicadores
					</tr>
						
					<tr>
					
						<td align = "center" colspan="2"> <!-- button for the "Logros e Indicadores" -->
							<button type="button" style="height: 100px; width: 100px" name="LogroseIndicadores" align="center" id="LogroseIndicadores" onclick="LogEInd()" >Logros e Indicadores</button>
						</td>
							
					</tr>
					
					<tr>
                        <td align = "center" colspan="2">
                            Observador
                        </td>
                    </tr>
                    
                    <tr>
                        
                        <td align = "center" colspan="2"> <!-- button for the "observador del alumno" -->
                            <button type="button" style="height: 100px; width: 100px" name="ObservadorDelAlumno" align="center" id="ObservadorDelAlumno" onclick="Observador()" >Observador Del Alumno</button>
                        </td>
                        
                    </tr>
				
				</table>
				
			<?php endif; ?>
		</form>
	</div>
</body>
