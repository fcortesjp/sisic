<?php
	session_start();  //session
?>	
<!DOCTYPE html>
	<head>		
		<script type="text/javascript" charset="utf-8">
		
				function CheckAndSubmit() //this function is called by the button to check if the the add or modify radio button was selected
				{
		        	var regForm = document.getElementById ("selectClassSubject"); // get the form and put it into a variable
		        	var add = document.getElementById ("add"); // get the add radio button and put it into a variable
					var mod = document.getElementById ("mod");
		        	if(document.getElementById ("rep"))
		        	{ //if htis element exist
		        		var rep = document.getElementById("rep"); //assig it to the rep variable
		        	}
		        	
		        	
		        	var itemOnClassSubject = document.getElementById("dbClassSubject"); // get the dropdown list as is
		        	var strIDClassSubject = itemOnClassSubject.options[itemOnClassSubject.selectedIndex].value; //get the id of the value selected
		        	var strClassSubjectSelected = itemOnClassSubject.options[itemOnClassSubject.selectedIndex].text; // get the text of item selected
		     		
		     		if (rep != null) //if the rep variable is not null (rep is the report radio button)
		     		{
	    				if(rep.checked) // and if the radio button was checked.
	    				{
	    					document.getElementById("classid").value = strIDClassSubject; //populate the hidden field with the id of the ddbox item selected
		        			document.getElementById("classsub").value = strClassSubjectSelected; //populate the hidden field witht the subjectclass selected 
	    					regForm.action = "report.php"; //se the form action to the report page
	    					regForm.submit(); // submit the form
	    					return; //exit the function.
	    				}
		        	}
		        	if (!add.checked && !mod.checked ) //if neither the add or the mod are checked then
		        	{
		        		alert ("You need to choose to add or modify before before proceeding"); // advise the user to check on add or mod
		        		
		        		return; // return to exit out of the function.
		        	}
					else{ //
		        		
		        		if (add.checked) // if add is checked then send the form to page to add the goal/indicator
		        		{
		        			
		        			regForm.action = "IngresarLeI.php"; 
		        		} else // if add is not checked but mod is checked then send it to modify page
		        		{
		        			regForm.action = "ModificarLeI.php";
		        		}
		        		
		        		//we need to set the values for 2 hidden files represent the id and subject class that are going to be passed in the form
		        		// as post values that represent the the id and respective subject that will be either entered or modify
		        		// in the next page (modificar o ingresar).
		        		document.getElementById("classid").value = strIDClassSubject; //populate the hidden field with the id of the ddbox item selected
		        		document.getElementById("classsub").value = strClassSubjectSelected; //populate the hidden field witht the subjectclass selected 
		        		
		        		regForm.submit(); // submit the form.
		       		}
		     	}
			
		</script>
			
	</head>
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
			$sql = 	"SELECT ID, CONCAT(Class.Class, '-', `Class Copesal`.Subject) AS ClassSubject ".
					"FROM `Class Copesal` ". 
					"INNER JOIN Class ".
					"ON `Class Copesal`.ClassID = Class.`Class ID` ".
					//"WHERE TeacherID = '$userID' ".
					"ORDER BY Class.Order;";
		} 
		else 
		{
			$sql = 	"SELECT ID, CONCAT(Class.Class, '-', `Class Copesal`.Subject) AS ClassSubject ".
					"FROM `Class Copesal` ". 
					"INNER JOIN Class ".
					"ON `Class Copesal`.ClassID = Class.`Class ID` ".
					"WHERE TeacherID = '$userID' ".
					"ORDER BY Class.Order;";
			
		}
		
		
		
				
		$recordset = mysql_query($sql) or die("error in Query: ". mysql_error());
		
		
		
		// Close de conection
					
		mysql_close($link);
		
		
	    
	    //**************************************************************
        //*********** Biginning of the form ****************************
        //**************************************************************
        //**************************************************************
        //**************************************************************
		
			
			echo "<body>";	
		        echo "<form name='selectClassSubject' id='selectClassSubject' method='post'>";
		            	
			    	echo "Bienvenido(a) ".$currentUser; // indicate that you have logged and do whatever
				    echo "</br>";
				    echo "<a href='logout.php'>Logout</a>";
				    echo "</br>";
				    echo "<input type='hidden' id = 'classid' name='classid' value=''>";
				    echo "<input type='hidden' id = 'classsub' name='classsub' value=''>";
				    echo '<div id="header" align="center"><h2 class="sansserif">Ingresar o Modificar Logros e Indicadores</h2></div>';
				    echo '<div id="containt" align="center">';
				    echo "</br>";
				    echo "</br>";
					//echo "<div align = 'center'>";
				    echo "<table align = 'center'>"; // Table starts
						//echo "<col width='100'>"; //defines the % distribution of the size of the columns
						//echo "<col width='100'>"; 
						echo "<tr>"; // Row 1 starts
				            
				            
				            echo"<th>"; //column 1 - Title
				            	
				            	echo '<h3>Ingresar o Modificar</h3>';
								
							echo"</th>";
							
							echo"<th>"; //column 2 - Title
				            	
				            	echo '<h3>Clase - Materia</h3>';
							
							echo"</th>";	
						
						echo "</tr>"; // row 1 ends
						
						/////////////////////////////////////////////////////////////
						
						echo "<tr>"; // row 2 starts
							
							echo '<td align = "center">'; //column 1
							    
							    //radio buttons for user to 'choose if they are going to add or modify goals and indicators
			                    //echo "&nbsp";   
			                    echo '<input type="radio" name="AddOrMod" id="add" > Ingresar</input>';
			                    echo '</br>';
			                    echo "&nbsp";
			                    echo "&nbsp"; 
			                    //echo "&nbsp";  
			                    echo '<input type="radio" name="AddOrMod" id="mod" > Modificar</input>';
			                    if ($role == 'd') 
			                    {
									echo '</br>';	
									echo '<input type="radio" name="AddOrMod" id="rep" > Reporte</input>';
								} 
			                echo '</td>';
				        
		
			                echo '<td align="center">'; //column 2
							
			                    echo "<select name='dbClassSubject' id='dbClassSubject'>";
			                    //the following code gets each one of the rows of the query until there's none
			                    //puts it on a row variable
			                    while ($row = mysql_fetch_array($recordset)) 
			                    {
			                        // then adds that as one of the options in the dropdown
			                        echo "<option value='" . $row['ID'] . "'>" . $row['ClassSubject'] . "</option>";
			                    }
			                    echo "</select>"; //this closes the dropdownbox.
			                
			                echo '</td>';
			
			            echo '</tr>'; // row 2 ends
						
						//////////////////////////////////////////////////////////////
						
						//echo '<tr>'; // row 3 Begins
							//echo '<td align = "center">';
								
							//echo '</td>';
						//echo '</tr>'; // row 2 ends	
						
				    echo "</table>";
					echo '</br>';
					
					echo '<button type="button" name="btnProceed" align="center" id="btnProceed" onclick="CheckAndSubmit()" >Proceder</button>';
				    //echo "</div>";
					echo '</div>';			
			    echo "</form>"; // end of the form
			echo "<body>";
		echo "<html>";
	    
	}
	else
	{
	    // indicate that the user has not logged in yet
	    echo "You need to be authenticated to see the content of this page"; //otherwise say your are not logged in.
	    echo "</br>";
        echo "<a href='logout.php'>Log In</a>";
	}
?>
