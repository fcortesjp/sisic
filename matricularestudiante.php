<?php
    session_start();  //session to start the page as info is only processable if the session is set	
?>	
	
<!DOCTYPE html>

<head>
	<meta http-equiv="Content-Type" content="text/html"> <!-- charset=utf-8 will make accents show on page but will fuckup if accents come from database-->
	
	<script type="text/javascript" charset="utf-8">
	
		function findStudent() 
		{
			//get the form and code submitted
			
			var matriculaForm = document.getElementById("MatricularEstudiante");
			var submitStudentCode = document.getElementById("tbCode").value;

			
			if (submitStudentCode == "")
			{
				alert("El codigo no puede estar vacio!");
				
			}
			else
			{
				alert("bien , El codigo es: " + submitStudentCode);
				
				//document.e
				//document.getElementById("submitCode").value; = submitStudentCode; // set the hidden value of the submitCode
				//alert("the code submitted is:" + submitStudentCode);	
				matriculaForm.action = "matricularestudiante.php";
				matriculaForm.submit();
			} 
				
		}
		
		function Save() 
		{
			var matriculaForm = document.getElementById ("menu");
			matriculaForm.action = "confirmacionmatricula.php";
			matriculaForm.submit();
			//alert("Submit button clicked!");	
		}
		
		function Cancel() 
		{
			var matriculaForm = document.getElementById ("menu");
			
			//alert("Submit button clicked!");	
		}
	</script>
	
</head>
<body>
	
	<form name='MatricularEstudiante' id='MatricularEstudiante' method='post'>
			
	
		<?php if (isset($_SESSION['currentUser']) ) : ?>
				
			<?php 
				
				// this snippet sets the sesion variables to be used on this page
				
				$currentUser = $_SESSION['currentUser'];
				$userID = $_SESSION['currentID'];
					
			?>	
			
			Bienvenido(a) <?php echo $currentUser; ?>
			</br>
			<a href='logout.php'>Logout</a> 
		
			<div align="center"> <!-- this center evertying on the page -->
	
			
				<h3>Matr&iacute;culas Copesal</h3>
				
				<!-- tabla que contiene-->
				<!-- titulo-->
				<!-- label codigo-->
				<!-- caja de texto para meter codigo-->
				<!-- boton bara buscar el codigo-->
				<table> 
					<tr>
						<td align = "center" colspan="4">
							Matricular Estudiante
							</br>
							</br>
						</td>
					</tr>
					<tr>
						<td>
							<td>
								Codigo
							</td>
							<td>
								<input type="text" name="tbCode" value="" id="tbCode"/>
								<input type='hidden' id='submitCode' = name='submitCode' value=''>
							</td>
							<td>
								<button id="btnSearchCode" name="btnSearchCode" onClick="findStudent()">Buscar</button>
							</td>
							
						</td>	
					</tr>
				</table>
				
				<?php if ($_SERVER["REQUEST_METHOD"] == "POST") 
				{
					//this will grab the tbCode submitted when the form was posted and will put into a variable
					// so it can be used  to retrieve info from the database 
					
					include("functions.php"); // get the include file to execute the function
					
					
					$tbCode = cleanInput($_POST["tbCode"]); //grab the posted code entered and clean it up
					
					
					/* 
					 * 
SELECT Student_ID, Student_First, Student_Last, Identification, Identificaciones.TypeOfID , CityDoc.CityName as CityDoc, Approved, CiudadStudent.CityName as CityStudent, direccion, telefono, CiudadNacimiento.CityName as CiudadNacimiento, fechaNac, Gender, nombreMa, ocupacionMa, CiudadMa.CityName as CiudadMa, DireccionMa, telefonoMa, emailMa, nombrePa, ocupacionPa, CiudadPa.CityName as CiudadPa, direccionPa, telefonoPa, emailPa, nombreAc, ocupacionAc, CiudadAc.CityName as CiudadAc, direccionAc, telefonoAc, emailAc, Parentescos.Parentesco, plantel, ultimoYear, LastClass.Class as LastClass, fechaMat, repitente, SangreTipos.Tipo, TablaEPS.EPS, estrato, aleOEnf, CurrentClass.Class as CurrentClass
FROM Alumnos
INNER JOIN Identificaciones
ON Alumnos.IDType = Identificaciones.ID
INNER JOIN Ciudades as CityDoc
ON Alumnos.City = CityDoc.ID
INNER JOIN Ciudades as CiudadStudent
ON Alumnos.ciudad = CiudadStudent.ID
INNER JOIN Ciudades as CiudadNacimiento
ON Alumnos.ciudadNac = CiudadNacimiento.ID
INNER JOIN Ciudades as CiudadMa
ON Alumnos.ciudadMa = CiudadMa.ID
INNER JOIN Ciudades as CiudadPa
ON Alumnos.ciudadPa = CiudadPa.ID
INNER JOIN Ciudades as CiudadAc
ON Alumnos.ciudadAc = CiudadAc.ID
INNER JOIN Parentescos
ON Alumnos.parentesco = Parentescos.ID
INNER JOIN Class as LastClass
ON Alumnos.ultimoGrado = LastClass.`Class ID`
INNER JOIN Class as CurrentClass
ON Alumnos.Class_ID = CurrentClass.`Class ID`
INNER JOIN SangreTipos
ON Alumnos.sangre = SangreTipos.ID
INNER JOIN TablaEPS
ON Alumnos.eps = TablaEPS.ID
					 * 
					 * 
					 * 
					 * 
					 * INSERT INTO `Alumnos` (`Student_ID`, `Student_First`, `Student_Last`, `Identification`, 
					 * `IDType`, `City`, `Approved`, `ciudad`, `direccion`, `telefono`, `ciudadNac`, `fechaNac`, 
					 * `Gender`, `nombreMa`, `ocupacionMa`, `ciudadMa`, `DireccionMa`, `telefonoMa`, `emailMa`, 
					 * `nombrePa`, `ocupacionPa`, `ciudadPa`, `direccionPa`, `telefonoPa`, `emailPa`, `nombreAc`, 
					 * `ocupacionAc`, `ciudadAc`, `direccionAc`, `telefonoAc`, `emailAc`, `parentesco`, `plantel`, 
					 * `ultimoYear`, `ultimoGrado`, `fechaMat`, `fechaCan`, `motivo`, `repitente`, `sangre`, `eps`, 
					 * `estrato`, `aleOEnf`, `Class_ID`) VALUES ('98765432', 'JAIME', 'CORTES', '80228159', '1', '1', 
					 * '1', '1', 'Calle 30 No 54-78, Aures II', '2517045', '1', '2000-07-10', 'F', 'ALBA MARY PEREZ', 
					 * 'Profesora', '1', 'Calle 30 No 54-78, Aures II', '2517045', 'ma@ma.com', 'CARLOS ARTURO CORTES', 
					 * 'Administrador', '1', 'Calle 30 No 54-78, Aures II', '2517045', 'pa@pa.com', 'FRANCISCO CORTES', 
					 * 'Soporte Tecnico', '1', 'Calle 30 No 54-78, Aures II', '2517045', 'fcortes@institutocopesal.com', 
					 * '1', 'MINUTO DE DIOS', '2013', '1', '2014-02-08', NULL, NULL, '0', '1', '1', '2', 
					 * 'no escucha bien por el oido derecho', '9');
					 * */
					
					
				}
				?>
				
				<table> 
						<tr>
							<td>
								Codigo entrado:
							</td>
							<td>
								<?php echo $tbCode; ?>
							</td>
						</tr>
						
						
				</table> 
			
		
			<?php else :  ?>	
	
		
				You need to be authenticated to see the content of this page
		    	</br>
	        	<a href='logout.php'>Log In</a>
			
		
			<?php endif; ?>
	
	
			
			
					
				
		
		</div>
	
	</form>		
	
	
</body>
