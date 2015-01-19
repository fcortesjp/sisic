<?php
	session_start();  //session for the page needs to be set prior everything
?>
<html>
    <head>
        <title>modprocess</title>				        	        
		<script type="text/javascript" charset="utf-8">
			
			//this function takes the user back to the screen to add a new goal for the same subject
			
			function gobackSameSubject()
			{
				var modprocessForm = document.getElementById ("modprocess"); //get the form object into a variable
				modprocessForm.action = "ModificarLeI.php";	// set the action for the object to the page you IngresarLeI.php	
				modprocessForm.submit(); // send the user back to that page = submit the form programatically
				 	
			}
			
			// this function takes the user back 2 pages before for them to add a goal for different subject from the one
			// they have just worked on
			
			function gobackNewSubject()
			{
				var modprocessForm = document.getElementById ("modprocess"); // get the form object into a variable
				modprocessForm.action = "admin.php";	// set the action for the form to the page admin.php
				modprocessForm.submit(); // submit the form programatically
				 	
			}
		</script>
	</head>
	<body>
		<?php
			
		    if(isset($_SESSION['currentUser'])) // if the user has been set then execute the following code
			{

				// set some variables
				$modORdel = $_POST['modORdel']; //find if the submition was to save or modify			
				$currentUser = $_SESSION['currentUser']; //get the name of the user authenticated to display it later
				$modORdelProcess = "";
				
				//variables to save goal/indicators
				$cid = $_POST['classid']; // the classid from the dropdown from previous page.
				$csubject = $_POST['classsub']; // the classSubject from the dropdown from previous page.
				
				
				// variables to delete goal/indicators
				$goalID = $_POST['gid'];
				//$goalID = "retard";
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
				
				// execute functions save and delete as needed
				
				if ($modORdel == "mod") // if user submitted the form with the modify goal button
				{
					$delOld = deleteGoalandIndicators($goalID); //try to delete the actual goal
					$saveNew = btnsave_Click2();  //try to save the goal/indicators submitted
					
					if ($delOld == 1 && $saveNew == 1) //if oldGoal and indc were deleted and the new goal and indicators were added successfully ...
					{
						$modORdelProcess = 1; //... process was ok
					}else
					{
						$modORdelProcess = 0; //... if not... process failed
					}
						
					
					
				} elseif ($modORdel == "del") // if user sumbmitted the form with the delete goal button
				{
					$modORdelProcess = deleteGoalandIndicators($goalID);
				} 
				
				
				//////////////////////////////////////////////////////////////////////////////////////
				///////////////  Begining of the form ///////////////////////
				//////////////////////////////////////////////////////////////////////////////////////
				
				echo "<form name='modprocess' id='modprocess' method='post' >";
					
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
							
								if ($modORdelProcess == 1 && $modORdel == "del") //if the process executed correctly and we are deleting...
								{
									echo 'Logro e Indicadores respectivos fueron Borrados exitosamente!'; //show a success message
								} 
								elseif ($modORdelProcess == 1 && $modORdel == "mod") 
								{
									echo 'Logro e Indicadores respectivos fueron Modificados exitosamente!'; //show a success message
								} 
								else 
								{
									echo "$modORdelProcess and the error is this: $saveNew"; // else show the error.	
								}
								echo "</br>";
								//echo "$goalID";
							
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