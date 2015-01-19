<?php
	session_start();  //session for the page needs to be set prior everything
?>
<html>
    <head>
        <title>addprocess</title>				        	        
		<script type="text/javascript" charset="utf-8">
			
			//this function takes the user back to the screen to add a new goal for the same subject
			
			function gobackSameSubject()
			{
				var addprocessForm = document.getElementById ("addprocess"); //get the form object into a variable
				addprocessForm.action = "IngresarLeI.php";	// set the action for the object to the page you IngresarLeI.php	
				addprocessForm.submit(); // send the user back to that page = submit the form programatically
				 	
			}
			
			// this function takes the user back 2 pages before for them to add a goal for different subject from the one
			// they have just worked on
			
			function gobackNewSubject()
			{
				var addprocessForm = document.getElementById ("addprocess"); // get the form object into a variable
				addprocessForm.action = "admin.php";	// set the action for the form to the page admin.php
				addprocessForm.submit(); // submit the form programatically
				 	
			}
		</script>
	</head>
	<body>
		<?php
			
		    if(isset($_SESSION['currentUser'])) // if the user has been set then execute the following code
			{

				// set some variables
								
				$currentUser = $_SESSION['currentUser']; //get the name of the user authenticated to display it later

				$cid = $_POST['classid']; // the classid from the dropdown from previous page.
				$csubject = $_POST['classsub']; // the classSubject from the dropdown from previous page.
				
				// include the functions.php file which has the functions to execute the main tasks of the app
				
				include("functions.php");
				
				//////////////////////////////////////////////////////////////////////////////////////
				///////////////This is the beginning of the page ///////////////////////
				//////////////////////////////////////////////////////////////////////////////////////
				
				//header with username and logout link
				echo "Bienvenido(a) ".$currentUser; // indicate that you have logged and do whatever
				echo "</br>";
				echo "<a href='logout.php'>Logout</a>";
				echo "</br>";
				
				// execute the save function and save the result into a variable
				
				$saveProcess = btnsave_Click();
				
				//////////////////////////////////////////////////////////////////////////////////////
				///////////////  Begining of the form ///////////////////////
				//////////////////////////////////////////////////////////////////////////////////////
				
				echo "<form name='addprocess' id='addprocess' method='post' >";
					
					//hidden files to save the subject in case the user wants to use the same 
					//subject to add a new goal for the same subject
					
					echo "<input type='hidden' id = 'classid' name='classid' value='".$cid."'>";
					echo "<input type='hidden' id = 'classsub' name='classsub' value='".$csubject."'>";
					
					//title of the body of the page or header
					
					echo '<div id="header" align="center"><h2 class="sansserif">Resultados</h2></div>';
					echo "</br>";
					
					//////////////////////////////////////////////////////////////////////////////////////
				///////////////  Begining of the table ///////////////////////
				//////////////////////////////////////////////////////////////////////////////////////
					
					echo "<table align = 'center'>";
					
						echo "<tr>"; // first  row
							echo "<td align='center' width='600' colspan='2'>"; //this expands the a field over several columns 
							
								if ($saveProcess == 1) //if the save process function executed correctly ...
								{
									echo 'Logros e Indicadores fueron guardados'; //show a success message
								} else {
									echo "$saveProcess"; // else show the error.
								}
								echo "</br>";
							
							echo "</td>";
						echo "</tr>";
						echo "<tr>"; // second row with buttons
							echo "<td align='center' width='200'>";
								// execute this if client wants to go back to add a goal for same subject
								echo "<button onclick='gobackSameSubject()'>Adicionar logro para la misma materia</button>";
							echo "</td>";
							echo "<td align='center' width='200'>";
								// execute this button to take client back to select a new subject for which to add a goal
								echo "<button onclick='gobackNewSubject()'>Adicionar logro para nueva materia</button>";
							echo "</td>";
						echo "</tr>";
					echo "</table>";	// end of the table
				echo "</form>"; // end of the form.
				}
				else // if the user was not set advised authentication is needed and provide a link for it
				{
					// indicate that the user has not logged in yet
			            echo "Para poder utilizar esta pagina necesita ser autenticado"; //otherwise say your are not logged in.
			            echo "</br>";
		        		echo "<a href='logout.php'>Log In</a>";
				}
			 
		?>
	</body>
</html>