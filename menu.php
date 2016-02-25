<?php
    session_start();  //session  
?>

<!DOCTYPE html>
	
<head>
	<meta http-equiv="Content-Type" content="text/html"> <!-- charset=utf-8 will make accents show on page but will fuckup if accents come from database-->
	
	<script type="text/javascript" charset="utf-8">
	
		
		//for admins only
		function MatEst() 
		{
			var menuForm = document.getElementById ("menu");
			menuForm.action = "matriculas.php";
			menuForm.submit();
		}

		//for admins only
		function GestionDeProfesores() 
		{
			var menuForm = document.getElementById ("menu");
			menuForm.action = "profesores.php";
			menuForm.submit();
		}
		// for admins only
		function Materias()
		{
		    var menuForm = document.getElementById ("menu");
            menuForm.action = "materias.php";
            menuForm.submit();
		}
		//for teachers and admins
		function Observador() 
        {
            var menuForm = document.getElementById ("menu");
            menuForm.action = "observador.php";
            menuForm.submit();
            //alert("Submit button clicked!");  
        }

        //for teachers and admins
		function LogEInd() 
		{
			var menuForm = document.getElementById ("menu");
			menuForm.action = "admin.php";
			menuForm.submit();
		}
		
		//for teachers and admins
		function CalNotas() 
		{
			var menuForm = document.getElementById ("menu");
			menuForm.action = "notas.php";
			menuForm.submit();
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
						<td align = "center"> 
							Matr&iacute;culas</br>
							<button type="button" style="height: 100px; width: 100px" name="MatricularEstudiante" align="center" id="MatricularEstudiante" onclick="MatEst()" >Matriculas de Estudiante</button>
						</td>
						<td align = "center"> 
                            Profesores</br>
                            <button type="button" style="height: 100px; width: 100px" name="gestiondeprofesores" align="center" id="gestiondeprofesores" onclick="GestionDeProfesores()" >Gestion de Profesores</button>
                        </td>
                        <td align = "center"> 
                            Materias</br>
                            <button type="button" style="height: 100px; width: 100px" name="gestiondeprofesores" align="center" id="gestiondeprofesores" onclick="Materias()" >Materias</button>
                        </td>
                        
					</tr>
					
					<tr>
						
						<td align = "center" colspan="3"> <!-- button for the "observador del alumno" -->
						    Observadores</br>
							<button type="button" style="height: 100px; width: 100px" name="ObservadorDelAlumno" align="center" id="ObservadorDelAlumno" onclick="Observador()" >Observador Del Alumno</button>
						</td>
						
					</tr>
					
					<tr>
						<td align = "center" colspan="3">
							Logros e Indicadores</br>
							<button type="button" style="height: 100px; width: 100px" name="LogroseIndicadores" align="center" id="LogroseIndicadores" onclick="LogEInd()" >Logros e Indicadores</button>
						</td>
					</tr>
					<tr>
						<td align = "center" colspan="3">
							Notas</br>
							<button type="button" style="height: 100px; width: 100px" name="Notas" align="center" id="Notas" onclick="CalNotas()" >Notas</button>
						</td>
					</tr>
					
				</table>
				
		
			
			<?php elseif (isset($_SESSION['currentUser']) && $menu == 'teachermenu') :  ?>
				
			
					
				<table align="center" border="0">						
					<tr>
					
						<td align = "center" colspan="3"> <!-- button for the "Logros e Indicadores" -->
							Logros e Indicadores</br>
							<button type="button" style="height: 100px; width: 100px" name="LogroseIndicadores" align="center" id="LogroseIndicadores" onclick="LogEInd()" >Logros e Indicadores</button>
						</td>
					</tr>
					<tr>
						<td align="center" colspan="3">
							Notas</br>
							<button type="button" style="height: 100px; width: 100px" name="Notas" align="center" id="Notas" onclick="CalNotas()" >Notas</button>
						</td>
							
					</tr>
					
					<tr>
                        <td align = "center" colspan="2">
                            Observadores
                        </td>
                    </tr>
                    
                    <tr>
                        
                        <td align = "center" colspan="2"> <!-- button for the "observador del alumno" -->
                            <button type="button" style="height: 100px; width: 100px" name="ObservadorDelAlumno" align="center" id="ObservadorDelAlumno" onclick="Observador()" >Observador Del Alumno</button>
                        </td>
                        
                    </tr>
                    <tr>
						<td align = "center"> 
							Matr&iacute;culas
						</td>
					</tr>
					
					<tr>
						
						<td align = "center"> <!-- buton de Matricular Alumno -->
							<button type="button" style="height: 100px; width: 100px" name="MatricularEstudiante" align="center" id="MatricularEstudiante" onclick="MatEst()" >Matriculas de Estudiante</button>
						</td>
						
					</tr>
				
				</table>
				
			<?php endif; ?>
		</form>
	</div>
</body>
