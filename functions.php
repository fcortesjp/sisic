<?php
    	
    	
    	
		 /* 
		 
		 *//*
		 *//**
		 * AddGoal function
		 * will execute the adding of the Goal to the database when called
		 * and will return a TRUE if the insertion completes, or FALSE if it doesnt
		 *  
		 */
    	
    	function AddGoal(&$goalString, &$periodNumber) 
		{
				//variables
				
				$goalID = 0; //default value that only changes if it works
				$msg = "";
				
				if (($goalString <> "" ) && ( $periodNumber <> 0)) //simple data validation
				{
					
					//set the connection parameters for the database
					
					include("config-CRDB.php"); //including config file for data connection
					
					//if the connexion does not work then through an error
					
					if(! $link )
					{
		  				$msg = die('Could not connect: ' . mysql_error());
						echo "<script>alert('$msg')</script>";
						
					}
					else 
					{	
						//set the sql command to enter data with the variables-parameters
						
						$sql = 	"INSERT INTO Goal ".
		       					"(Goal,`Class Grading Period`)" .
		       					"VALUES ('$goalString', '$periodNumber');";
						
						//set the sql command to retrieve the last id of the goal table
						
						$sqlGetLastGoalID = "SELECT Goal.ID ".
											"FROM `Goal` ".
											"ORDER BY Goal.ID DESC ".
											"LIMIT 1;";
							
						
						//proceed with the adding of the information and get confirmation into a variable
						
						$retval = mysql_query( $sql, $link );
						
						//if the confirmation is unsuccesful then display an error, but if all is good display Great success
						
						if(! $retval )
						{
		  					die('Could not enter goal data : ' . mysql_error());
							echo "<script>alert('$msg')</script>";
						}
						else 
						{
							$msg = "Goal data Entered successfully";
							//echo "<script>alert('$msg')</script>";
							
							// now retrieve the last id record
							
							$retlastID = mysql_query( $sqlGetLastGoalID, $link ); //or die("something went wrong: ". mysql_error()); // execute query and put recordset into variable
							
							
							$row = mysql_fetch_array($retlastID); // get the only one row from the recordset
							
							
							// since we got a goal entered and we can get the last goal id, the adding of a goal was a success
							// so returning anything than 0 for the goalID would be configuration that a record was added. 
							
							$goalID = $row['ID']; //get the value field under the the ID column and put it into the goalID variable.
							
							//echo "<script>alert('retlastID = $retlastID, row = $row, goaldID = $goalID ')</script>";
															
						}
						
						// Close de conection
						
						mysql_close($link);
							
					}
					
					
				}
				else //if simple data validation failed then display that info needs to be checked.
				{
					$msg = "Goal Data entered is not correct, Check Data and try again";
					echo "<script>alert('$msg')</script>";
					
				}
				
				//return variable
				
				return $goalID;
					
		}
	
		/**
		 * addIndicator function
		 * will execute the adding of 1 indicator to the database when called
		 * and will return TRUE if the insertion completes, or FALSE if it doesnt
		 *  
		 */	
		
		function addIndicator(&$ind, &$gID, &$cID) 
		{
			//variables
					
			$addIndicatorOK = FALSE; //default value that only changes if it works	
			$msg = "";	
			
			if ($ind <> "" && $gID > 0 && $cID > 0) //simple validation for data entered
			{
					
					//set the connection parameters for the database
					
					include("config-CRDB.php"); //including config file for data connection
					
					//if the connexion does not work then through an error
					
					if(! $link )
					{
		  				$msg = die('Could not connect: ' . mysql_error());
						echo "<script>alert('$msg')</script>";
						
					}
					else 
					{	
						//set the sql command to enter data with the variables-parameters
						/*
						 * rst!Indicator = ind
	        				rst![Class Copesal_ID] = cID
	        				rst!Goal_ID = gID
						 */
						$sql = 	"INSERT INTO Indicator ".
		       					"(Indicator,`Class Copesal_ID`, Goal_ID) ".
		       					"VALUES ('$ind', '$cID', '$gID');";
						
						//proceed with the adding of the information and get confirmation into a variable
						
						$retval = mysql_query( $sql, $link ); // execute the query and save the recordset into a variable
						
						//if the confirmation is unsuccesful then display an error if all good display Great success
						
						if(! $retval )
						{
		  					die('Could not enter indicator data: ' . mysql_error());
							echo "<script>alert('$msg')</script>";
						}
						else 
						{
							$msg = "Entered indicator data successfully";
							//echo "<script>alert('$msg')</script>";
							$addIndicatorOK = TRUE;	//only if the retrieval is true then the return value is True so it works.	
															
						}
						
						// Close de conection
						
						mysql_close($link);
							
					}
					
			} 
			else //if simple data validation failed then display that info needs to be checked.
			{
				$msg = "Indicator Data Entered is not correct, Check Data and try again";
				echo "<script>alert('$msg')</script>";
			}
			
			//variables to be returned
			
			return $addIndicatorOK;
			
			
		}
		
		/**
		 * GoalIndicatorExist function
		 * will execute a query on the database that will check if the indicator 
		 * being added already exist in the database.
		 * and will return TRUE if so, or FALSE if it doesnt
		 * 
		 * note: the goaltext
		 *  
		 */	
		
		function GoalIndicatorExist($prd, $goalText, $firstIndText, $classID) 
		{
			
				//variables
				//echo "<script>alert('GoalIndicatorExist is being executed')</script>";
					
				$giExist = FALSE; //default value that only changes if it the goal/indicator being added exist	
				$msg = "";
					
				//set the connection parameters for the database
				include("config-CRDB.php"); //including config file for data connection
					
				//if the connexion does not work then through an error if it does try to get the data
				
				if(! $link )
				{
		  			$msg = die('Could not connect: ' . mysql_error());
					echo "<script>alert('$msg')</script>";
					
				}
				else 
				{
					//echo "<script>alert('period = $prd, goaltext =  $goalText, firstIndText = $firstIndText, classID = $classID')</script>";
					$sql = 	"SELECT Goal.ID, Goal.`Class Grading Period`, Goal, Indicator, Indicator.`Class Copesal_ID` ".
							"FROM `Indicator` ".
							"INNER JOIN Goal ".
							"ON Goal.ID = Indicator.Goal_ID ".
							"INNER JOIN `Class Copesal` ".
							"ON `Class Copesal`.ID = Indicator.`Class Copesal_ID` ".
							"WHERE `Class Grading Period` = $prd AND  Goal = '$goalText' ".
							"AND Indicator = '$firstIndText' AND `Class Copesal_ID` = '$classID';";
							
					//proceed with the retrieval of info with the query
						
					$retval = mysql_query( $sql, $link ) or die("error in Query");
					
					//change the die(message) to this when debugging a mysql statement during developing so a big message shows 
					//why mysql is complainting.
					//$retval = mysql_query( $sql, $link ) or die("error in Query: $sql ". mysql_error());
					
					//echo "<script>alert('did the retrieval die or not = $retval')</script>";
					
					//$row = mysql_fetch_array($retval);
					
					// put the rows from the query into a variable
						
					$NumOfrows = mysql_num_rows($retval);
					
					
					// if rows is > 0 ... so the goal/indicator exist
						
					if($NumOfrows > 0 )
					{
		  				//$msg = "Ops. the goal seems like it has been entered already\n";
						//echo "<script>alert('$msg')</script>";
						
						//only if the retrieval is produces a record then the return 
						//value is True, the goal had already being entered.
						$giExist = TRUE;
						
						//echo "<script>alert('goal and ind have already being entered: rows = $NumOfrows ')</script>";	
		  				
					}
					else // if no row is found all is good so no need for message.
					{
						//echo "<script>alert('goal and first ind doesnt exist')</script>";
						//echo "<script>alert('rows = $NumOfrows > 0')</script>";					
						//don't do anyting
		  				;
															
					}
						
					// Close de conection
						
					mysql_close($link);
							
				}
					
			
			
			//variables to be returned
			
			return $giExist;
			
			
		}
		
		/**
		 * btnsave_Click function
		 * 
		 * *********************************************************************************
		 * *****************this is the main function of this form**************************
		 * *********************************************************************************
		 * will take the goal and first indicator and will check if such goal exist, if it 
		 * doesnt will save the goal, get the goal id and then will add the indicators tie 
		 * them up with the goal indicator into the respective database tables.
		 *  
		 */	
		function btnsave_Click()
		{
			
		
			//echo "<script>alert('tbnsave_Click is being executed')</script>";
			//Set variables with values from form, class, period, goal and indicators 
			
			/*
			 * " " (ASCII 32 (0x20)), an ordinary space.
				"\t" (ASCII 9 (0x09)), a tab.
				"\n" (ASCII 10 (0x0A)), a new line (line feed).
				"\r" (ASCII 13 (0x0D)), a carriage return.
				"\0" (ASCII 0 (0x00)), the NUL-byte.
				"\x0B" (ASCII 11 (0x0B)), a vertical tab.
			 */ 
			
			$charlist = "\t\n\r\0\x0B"; //charlist of characters to remove from goal and indicator strings	
			$indArray = array(); //array for indicators
			$GoalIndicatorExistYet = FALSE;
			$goaladdedOK = FALSE;
			$msg = 1;
			/************************************************************
			 * get the data from the form and sanitize it if needed *****
			 ************************************************************
			 * 
			 * */
			//a connection is needed for the  mysql_real_escape_string function to work
			include("config-CRDB.php"); //including config file for data connection
			 
			$cclass = $_POST["ddboxClassSubject"]; // get the class and subject from dropdown and put it into a variable
			
			
			$period = $_POST["rdPeriod"]; // get the period and put it into a variable
			
			
			$goal = $_POST["tbgoal"]; //get the goal and sanitize the input then put into a variable
			$goal = mysql_real_escape_string($goal);
			$goal = trim ($goal, $charlist); // remove hasty character from beginning and end of the string
			
			//get all the indicators and put into an array
			
			foreach ($_POST as $key => $value ) // iterate through all the elements in POST array (form)
			{
				//if the name of the post element has the word tbIndicator and its value is not empty.
				//$teststrpos = strpos($key,"tbIndicator");
				//$testempty = !empty($value); 
				
				if (strpos($key,'tbIndicator') === 0 && !empty($value) > 0) //if strpos found at the biginning === 0 and if not empty > 0
				{
					$value = mysql_real_escape_string($value); //sanitize the contents of its respective value
					$value = trim ($value, $charlist); // remove hasty characters from the beginning and end of the value
					array_push($indArray, $value); //put the value onto the array 
					//echo "<script>alert('ind just pushed into array = $value')</script>";
					//echo "<script>alert(' values pushed into array: key = $key and value = $value , teststrpos = $teststrpos , testempty = $testempty ')</script>>";
				}
			}
			
			//is there at least one indicator, period selected, class selected and is there text for the goal?
			
			if (strlen($indArray[0]) > 0 && $cclass > 0 && $period > 0  && strlen($goal) > 0)
			{
				
				//'check to see if the same period, goal text, first indicator text and class have already
		        //being entered
					
				$GoalIndicatorExistYet = GoalIndicatorExist($period,$goal,$indArray[0],$cclass);
				//echo "<script>alert('goalIndicatorExistYet = $GoalIndicatorExistYet')</script>";
				
				//only if the retrieval produces a record then the return 
				//value is True, the goal had already being entered.
				
				if($GoalIndicatorExistYet == 1) //trows 1 if goal and indicator being entered already exist
				{
						
					$msg = "Ops. the goal seems like it has been entered already";
					echo "<script>alert('$msg')</script>";
						
				}
				else //if the goal being entered does not exist then ...
				{				
					$goaladdedOK = AddGoal($goal,$period); // try to add the goal
					
					// if goal added ok (not 0) then
					//echo "<script>alert('goaladdedOK = $goaladdedOK')</script>";
					
					if($goaladdedOK != 0)
					{
						
						//lets add the indicators
						
						foreach ($indArray as $key => $value) //iterate through the indicators array
						{
							$indaddedOK = addIndicator($value, $goaladdedOK, $cclass); //add an ind to the table.
							
							if ($indaddedOK = 0) // if something goes wrong report it a message.
							{
								echo "<script>alert('something went wrong adding the an indicator')</script>"; // something when wrong when adding the indicator
								$msg = "adding an indicator failed";
								break;	// break the loop
							}						
						}		 
						
					}
				}
				
				// Close de conection
						
				//mysql_close($link);
			} 
			else 
			{
				//return a message confirming that there's something no entered properly
				
				$msg = "check the that there's a class, period, a goal and at least an indicator entered";	
			}			
			
			return $msg;    
		}
		
		/**
		 * btnsave_Click function
		 * 
		 * *********************************************************************************
		 * *****************this is the main function of this form**************************
		 * *********************************************************************************
		 * will take the goal and first indicator and will check if such goal exist, if it 
		 * doesnt will save the goal, get the goal id and then will add the indicators tie 
		 * them up with the goal indicator into the respective database tables.
		 *  
		 */	
		function btnsave_Click2()
		{
			
		
			//echo "<script>alert('tbnsave_Click is being executed')</script>";
			//Set variables with values from form, class, period, goal and indicators 
			
			/*
			 * " " (ASCII 32 (0x20)), an ordinary space.
				"\t" (ASCII 9 (0x09)), a tab.
				"\n" (ASCII 10 (0x0A)), a new line (line feed).
				"\r" (ASCII 13 (0x0D)), a carriage return.
				"\0" (ASCII 0 (0x00)), the NUL-byte.
				"\x0B" (ASCII 11 (0x0B)), a vertical tab.
			 */ 
			
			$charlist = "\t\n\r\0\x0B"; //charlist of characters to remove from goal and indicator strings	
			$indArray = array(); //array for indicators
			$GoalIndicatorExistYet = FALSE;
			$goaladdedOK = FALSE;
			$msg = 1;
			/************************************************************
			 * get the data from the form and sanitize it if needed *****
			 ************************************************************
			 * 
			 * */
			//a connection is needed for the  mysql_real_escape_string function to work
			include("config-CRDB.php"); //including config file for data connection
			 
			$cclass = $_POST["csid"]; // get the class and subject from dropdown and put it into a variable
			
			
			$period = $_POST["perd"]; // get the period and put it into a variable
			
			
			$goal = $_POST["tbgoal"]; //get the goal and sanitize the input then put into a variable
			$goal = mysql_real_escape_string($goal);
			$goal = trim ($goal, $charlist); // remove hasty character from beginning and end of the string
			
			//get all the indicators and put into an array
			
			foreach ($_POST as $key => $value ) // iterate through all the elements in POST array (form)
			{
				//if the name of the post element has the word tbIndicator and its value is not empty.
				//$teststrpos = strpos($key,"tbIndicator");
				//$testempty = !empty($value); 
				
				if (strpos($key,'tbIndicator') === 0 && !empty($value) > 0) //if strpos found at the biginning === 0 and if not empty > 0
				{
					$value = mysql_real_escape_string($value); //sanitize the contents of its respective value
					$value = trim ($value, $charlist); // remove hasty characters from the beginning and end of the value
					array_push($indArray, $value); //put the value onto the array 
					//echo "<script>alert('ind just pushed into array = $value')</script>";
					//echo "<script>alert(' values pushed into array: key = $key and value = $value , teststrpos = $teststrpos , testempty = $testempty ')</script>>";
				}
			}
			
			//is there at least one indicator, period selected, class selected and is there text for the goal?
			
			if (strlen($indArray[0]) > 0 && $cclass > 0 && $period > 0  && strlen($goal) > 0)
			{
				
				//'check to see if the same period, goal text, first indicator text and class have already
		        //being entered
					
				$GoalIndicatorExistYet = GoalIndicatorExist($period,$goal,$indArray[0],$cclass);
				//echo "<script>alert('goalIndicatorExistYet = $GoalIndicatorExistYet')</script>";
				
				//only if the retrieval produces a record then the return 
				//value is True, the goal had already being entered.
				
				if($GoalIndicatorExistYet == 1) //trows 1 if goal and indicator being entered already exist
				{
						
					$msg = "Ops. the goal seems like it has been entered already";
					echo "<script>alert('$msg')</script>";
						
				}
				else //if the goal being entered does not exist then ...
				{				
					$goaladdedOK = AddGoal($goal,$period); // try to add the goal
					
					// if goal added ok (not 0) then
					//echo "<script>alert('goaladdedOK = $goaladdedOK')</script>";
					
					if($goaladdedOK != 0)
					{
						
						//lets add the indicators
						
						foreach ($indArray as $key => $value) //iterate through the indicators array
						{
							$indaddedOK = addIndicator($value, $goaladdedOK, $cclass); //add an ind to the table.
							
							if ($indaddedOK = 0) // if something goes wrong report it a message.
							{
								echo "<script>alert('something went wrong adding the an indicator')</script>"; // something when wrong when adding the indicator
								$msg = "adding an indicator failed";
								break;	// break the loop
							}						
						}		 
						
					}
				}
				
				// Close de conection
						
				//mysql_close($link);
			} 
			else 
			{
				//return a message confirming that there's something no entered properly
				
				$msg = "check the that there's a class, period, a goal and at least an indicator entered. classid: ".$cclass.", period: ".$period.", goal: ".$goal.", first ind: ".$indArray[0];	
			}			
			
			return $msg;    
		}
		/* 
		 *//**
		 * getHtmlTable(&$result)
		 * 
		 * *****************************************************************************************************************
		 * *****************this function will make a table out of a record set to disply goal text**************************
		 * **********************************************************************************************************
		 * will take a recordset and will turn into into a table that will show a table with 
		  * 2 columns a select colomn for the client to choose that record and the column showing the goal text
		 *  
		 */	
		//function that takes a recordset with goals and out put it as as an html table
		
		function getHtmlTable(&$result)
		{
    		// receive a record set and print
    		// it into an html table
    		$out = '<table width="550">'; // set the start of the html output which is a table
    		for($i = 0; $i < mysql_num_fields($result); $i++) //do this as many records exist on the recordset
    		{
        		$aux = mysql_field_name($result, $i); // get the title of the fields in the record set
        		$out .= "<th>".$aux."</th>"; //put the title in between row headers
    		}
    		while ($linea = mysql_fetch_array($result, MYSQL_ASSOC)) //put each reacord fetched into an associative array variable.
    		{
        		$out .= "<tr>"; //add the start of the row for the record on the table
        		//foreach ($linea as $valor_col) $out .= '<td>'.$valor_col.'</td>'; //output each field in the record into each table cell
        		
	        		//$out .= '<td><button onClick="setGoalID(\''.str_replace("'", "\\'", $linea["Select"]).'\',\''.str_replace("'", "\\'", $linea["Logro"]).'\')">'.$linea["Select"].'</button></td>';
	        		$out .= '<td><button onClick="setGoalID(\''.str_replace("'", "\\'", $linea["Select"]).'\',\''.str_replace("'", "\\'", $linea["Logro"]).'\')">Seleccionar</button></td>';
	        		$out .= '<td>'.$linea["Logro"].'</td>';
        		$out .= "</tr>"; //add the close of the row
    		}
    		$out .= "</table>"; // add the closing of the table
    		return $out; //return the html string that contains the whole table.
		}
		
		
		/* 
		 *//**
		 * function SearchGoal()
		 * 
		 * *********************************************************************************
		 * *****************this function will search for a goal if exist**************************
		 * *********************************************************************************
		 * will take the subject and period and if there's a goal entered in the database for those values
		 * it will display get the goalID and will get the subject line.
		 *  
		 */	
		function SearchGoal(&$subjectID, &$period)
		{
			//variables
			//$goal = array(); //this will be the return value	
				
			//set the connection parameters for the database
			include("config-CRDB.php"); //including config file for data connection
			
			//if the connexion does not work then through an error
					
			if(! $link )
			{
  				$msg = die('Could not connect: ' . mysql_error());
				echo "<script>alert('$msg')</script>";
				
			}
			else 
			{	
				
				
				//set the sql command to retrieve the goals for subject and period selected
				
				$sqlGetGoal4SubAndPer = "SELECT DISTINCT Goal.ID AS `Select`, Goal.Goal AS `Logro` ".
										"FROM Goal ".
										"INNER JOIN Indicator ".
										"ON Goal.ID = Indicator.Goal_ID ".
										"INNER JOIN `Class Copesal` ".
										"ON `Class Copesal`.ID = Indicator.`Class Copesal_ID` ".
										"WHERE `Class Copesal`.ID = '$subjectID' AND Goal.`Class Grading Period` = '$period';";
					
				
				//proceed with the adding of the information and get confirmation into a variable
				
				$retval = mysql_query( $sqlGetGoal4SubAndPer, $link );
						
			
				return $retval;
			
			}
		}

		/* 
		 *//**
		 * getHtmlTable4Indicators(&$result)
		 * 
		 * *********************************************************************************
		 * *****************this function will make a table out of a record set**************************
		 * *********************************************************************************
		 * will take a recordset and will turn into into a table that will show a table with 
		  * 2 columns a select colomn for the client to choose that record and the column showing the goal text
		 *  
		 */	
		//function that takes a recordset with goals and out put it as as an html table
		
		function getHtmlTable4Indicators(&$result)
		{
    		// receive a record set and print
    		// it into an html table
    		
    		$i = 1; //counter
    		$tbmaxsize = 250; // size of text boxes
            //$rowTextAreaL = 3; //rows of Logro box
			$rowTextAreaI = 2; //rows of Indicator box
			$sizeTextArea = 60; //width of boxes
    		
    		$out = '<table align="center">'; // set the start of the html output which is a table
	    		$out .= '<col width="100">';
	  			$out .= '<col width="100">';
	    		for($i = 0; $i < mysql_num_fields($result); $i++) //do this as many records exist on the recordset
	    		{
	        		$aux = mysql_field_name($result, $i); // get the title of the fields in the record set
	        		$out .= "<th></th>"; 
	        		$out .= "<th>".$aux."</th>"; //put the title in between row headers
	    		}
	    		while ($linea = mysql_fetch_array($result, MYSQL_ASSOC)) //put each reacord fetched into an associative array variable.
	    		{
	        		$out .= "<tr>"; //add the start of the row for the record on the table
		        		$out .= '<td>Indicador '.$i.'</td>';
		        		//$out .= '<td><input type="textbox" name="tbIndicator'.$i.'" id="tbIndicator'.$i.'" size="'.$tbmaxsize.'" maxlength="'.$tbmaxsize.'" value="'.$linea["Indicadores"].'"></input></td>';
	        			$out .= '<td><textarea rows='.$rowTextAreaI.' cols='.$sizeTextArea.' name="tbIndicator'.$i.'" id="tbIndicator'.$i.'" maxlength="'.$tbmaxsize.'">'.$linea["Indicadores"].'</textarea></td>';
	        		$out .= "</tr>"; //add the close of the row
	        		$i += 1;
	    		}
				if ($i < 8)
				{
					while ($i < 8) //indicator shouldn not be more than 7 per goal so fill up with blank textoboxes.
					{
						$out .= "<tr>";	
							$out .= '<td>Indicador '.$i.'</td>';
			        		//$out .= '<td><input type="textbox" name="tbIndicator'.$i.'" id="tbIndicator'.$i.'" size="'.$tbmaxsize.'" maxlength="'.$tbmaxsize.'"></input></td>';
		        			$out .= '<td><textarea rows='.$rowTextAreaI.' cols='.$sizeTextArea.' name="tbIndicator'.$i.'" id="tbIndicator'.$i.'" maxlength="'.$tbmaxsize.'"></textarea></td>';
		        		$out .= "</tr>"; //add the close of the row
		        		$i += 1;
					}	
				}
    		
    		$out .= "</table>"; // add the closing of the table
    		
    		$out .= '<table align="center">';
	    		$out .=  "<tr>";
					$out .=  "<td>";
						$out .=  "<button onclick='gobackNewSubject()'>Modificar Logro De Otra Materia</button>";
					$out .=  "</td>";
					$out .=  "<td>";
						$out .=  '<button type="button" name="btnDeleteGoal" id="btnDeleteGoal" align="center" onClick= "deleteGoal_Click()" />Borrar Logro</button>';
					$out .=  "</td>";
					$out .=  "<td>";
						$out .=  '<button type="button" name="btnSaveChanges" id="btnSaveChanges" align="center" onClick= "saveChanges_Click()" />Guardar Cambios</button>';
					$out .=  "</td>";
				$out .=  "</tr>";
            $out .=  '</table>';	
    		
    		return $out; //return the html string that contains the whole table.
		}

		/* 
		 *//**
		 * function getGoalIndicators(goalid)
		 * 
		 * *********************************************************************************
		 * *****************this function will get the indicators tied to a goal*************
		 * *********************************************************************************
		 * will take the goal id and will get the array of indicators tied to that goal.
		 *  
		 */	
		function getGoalIndicators(&$goalID)
		{
			//variables
			//$goal = array(); //this will be the return value	
			//echo "<script>alert('something')</script>";	
			//set the connection parameters for the database
			include("config-CRDB.php"); //including config file for data connection
			
			//if the connexion does not work then through an error
					
			if(! $link )
			{
  				$msg = die('Could not connect: ' . mysql_error());
				echo "<script>alert('$msg')</script>";
				
			}
			else 
			{	

				//set the sql command to retrieve the goals for subject and period selected
				
				$sqlIndForGoalID = 	"SELECT Indicator AS Indicadores ".
									"FROM  `Indicator` ".
									"WHERE Goal_ID = '$goalID';";
					
				
				//proceed with the adding of the information and get confirmation into a variable
				
				$retval = mysql_query( $sqlIndForGoalID, $link );
						
				//$retval = "this is from getGoalIndicators";
				return $retval;
				
				
			
			}
			//$retval = "this is from getGoalIndicators";
			//return $retval;
		}

/* 
		 *//**
		 * function getGoalIndicators(goalid)
		 * 
		 * *********************************************************************************
		 * *****************this function will get the indicators tied to a goal*************
		 * *********************************************************************************
		 * will take the goal id and will get the array of indicators tied to that goal.
		 *  
		 */	

		function deleteGoalandIndicators(&$gID)
		{
			include("config-CRDB.php"); //including config file for data connection
			
			//if the connexion does not work then through an error
					
			if(! $link )
			{
  				$msg = die('Could not connect: ' . mysql_error());
				echo "<script>alert('$msg')</script>";
				
			}
			else 
			{	

				//set the sql command to delete the goal and respective indicators
				
				$sqltoDelGoalandInd =	"DELETE FROM Goal, Indicator ".
										"USING Goal ".
										"INNER JOIN Indicator ON Goal.ID = Indicator.Goal_ID ".
										"WHERE Goal.ID = '$gID';";
					
				
				//proceed with the adding of the information and get confirmation into a variable
				
				$retval = mysql_query( $sqltoDelGoalandInd, $link );
						
				//the return value will be true or false
				return $retval;	
			}
		}	
		
		/* 
		 *//**
		 * function SearchGoalandIndicatorsPerPeriod(&$period)
		 * 
		 * ******************************************************************************************************************
		 * *****************this function will search for a goal and indicators entered for a period**************************
		 * *****************************************************************************************************************
		 * will take a period and will get a recordset that includes
		  * Teacher subject, class, goal, indicators
		  * 
		  * sql query to check on goals and indicators to modify manually (enter teacher id and tener class id)
		  * 
		  * SELECT CONCAT(`Teacher`.`Teacher Name`,' ',`Teacher`.`Teacher Last`) AS TeacherName, CONCAT(Class.Class, '-', `Class Copesal`.Subject) AS ClassSubject, Goal.ID, Goal.Goal, Indicator.ID, `Indicator`.Indicator
			FROM `Teacher`
			INNER JOIN `Class Copesal`
			ON Teacher.ID = `Class Copesal`.TeacherID
			INNER JOIN Class
			ON Class.`Class ID` = `Class Copesal`.ClassID
			INNER JOIN Indicator
			ON Indicator.`Class Copesal_ID` = `Class Copesal`.ID
			INNER JOIN Goal
			ON Goal.ID = Indicator.`Goal_ID`
			WHERE
			Goal.`Class Grading Period` = 4 AND Teacher.ID = # AND Class.`Class ID` = #
			ORDER BY TeacherName, ClassSubject, Goal.ID
		  * 
		  * to get class teacher and class id base on class (name)
		  * 
		  * SELECT `ID`, `Class Name`, `Subject`, `Class`, `ClassID`, `TeacherID`
			FROM `Class Copesal` 
			WHERE `Class` = "Séptimo"
		 *  
		 */	
		function SearchGoalandIndicatorsPerPeriod(&$period)
		{
			//variables
			//$goal = array(); //this will be the return value	
				
			//set the connection parameters for the database
			include("config-CRDB.php"); //including config file for data connection
			
			//if the connexion does not work then through an error
					
			if(! $link )
			{
  				$msg = die('Could not connect: ' . mysql_error());
				echo "<script>alert('$msg')</script>";
				
			}
			else 
			{	
				
				
				//set the sql command to retrieve the goals for subject and period selected
				
				$sqlGetGoalnIndPerPeriod =	"SELECT CONCAT(`Teacher`.`Teacher Name`,' ',`Teacher`.`Teacher Last`) ".
											"AS TeacherName, CONCAT(Class.Class, '-', `Class Copesal`.Subject) AS ClassSubject, ".
											"`Goal`.Goal, `Indicator`.Indicator ".
											"FROM `Teacher` ".
											"INNER JOIN `Class Copesal` ".
											"ON Teacher.ID = `Class Copesal`.TeacherID ".
											"INNER JOIN Class ".
											"ON Class.`Class ID` = `Class Copesal`.ClassID ".
											"INNER JOIN Indicator ".
											"ON Indicator.`Class Copesal_ID` = `Class Copesal`.ID ".
											"INNER JOIN Goal ".
											"ON Goal.ID = Indicator.`Goal_ID` ".
											"WHERE ".
											"Goal.`Class Grading Period` = '$period'".
											"ORDER BY TeacherName, ClassSubject; ";

					
				
				//proceed with the adding of the information and get confirmation into a variable
				
				$retval = mysql_query( $sqlGetGoalnIndPerPeriod, $link );
						
			
				return $retval;
			
			}
		}

/* 
		 *//**
		 * getHtmlTable4GandIEntered(&$result)
		 * 
		 * *********************************************************************************
		 * *****************this function will make a table out of a record set**************************
		 * *********************************************************************************
		 * will take a recordset and will turn into into a table that will show a table with 
		  * 2 columns a select colomn for the client to choose that record and the column showing the goal text
		 *  
		 */	
		//function that takes a recordset with goals and out put it as as an html table
		
		function getHtmlTable4GandIEntered(&$result)
		{
    		// receive a record set and print
    		// it into an html table
    		
    		$i = 1; //counter
    		//$tbmaxsize = 250; // size of text boxes
            //$rowTextAreaL = 3; //rows of Logro box
			//$rowTextAreaI = 2; //rows of Indicator box
			//$sizeTextArea = 60; //width of boxes
    		
    		//title of table
    		
    		$out = '<center><h2 class="sansserif">Indicadores y logros Ingresados</h2></center>';
    		
			//start of table
			
    		$out .= '<table align="center" border="1">'; // set the start of the html output which is a table
	    		$out .= '<col width="25">';
	  			$out .= '<col width="25">';
				$out .= '<col width="25">';
	  			$out .= '<col width="25">';
				
	    		//for($i = 0; $i < mysql_num_fields($result); $i++) //do this as many records exist on the recordset
	    		//{
	        		//$aux = mysql_field_name($result, $i); // get the title of the fields in the record set
	        		//$out .= "<th></th>"; 
	        		
	        	$out .= "<tr>";
	        		$out .= "<th>Profesor</th>"; //put the title in between row headers
	        		$out .= "<th>Clase-Materia</th>";
					$out .= "<th>Logro</th>";
					$out .= "<th>Indicador</th>";
	        	$out .= "</tr>";
	    		//}
	    		while ($linea = mysql_fetch_array($result, MYSQL_ASSOC)) //put each reacord fetched into an associative array variable.
	    		{
	        		$out .= "<tr>"; //add the start of the row for the record on the table
		        		//$out .= '<td>Indicador '.$i.'</td>';
		        		//<input type="textbox" name="perd" id="perd" disabled="disabled" size="10" value="'.$_POST['period'].'"/>
		        		//$out .= '<td><input type="textbox" name="tbIndicator'.$i.'" id="tbIndicator'.$i.'" size="'.$tbmaxsize.'" maxlength="'.$tbmaxsize.'" value="'.$linea["Indicadores"].'"></input></td>';
	        			//Teacher subject, class, goal, indicators
	        			$out .= '<td>'.$linea["TeacherName"].'</td>';
						$out .= '<td>'.$linea["ClassSubject"].'</td>';
						$out .= '<td>'.$linea["Goal"].'</td>';
						$out .= '<td>'.$linea["Indicator"].'</td>';
	        		$out .= "</tr>"; //add the close of the row
	        		$i += 1;
	    		}
				
    		
    		$out .= "</table>"; // add the closing of the table
    		
    		$out .= '<table align="center">';
	    		$out .=  "<tr>";
					$out .=  "<td>";
						$out .=  "<button onclick='goback()'>Regresar</button>";
					$out .=  "</td>";
				$out .=  "</tr>";
            $out .=  '</table>';	
    		$out .= '</br></br>';
    		return $out; //return the html string that contains the whole table.
		}
		
		/* 
		 *//**
		 * function searchTeacherStatsOfGandIentered(&$period)
		 * 
		 * ******************************************************************************************************************
		 * *****************this function will search for a goal and indicators entered for a period**************************
		 * *****************************************************************************************************************
		 * will take a period and will get a recordset that includes
		  * Teacher, subject - class, count(goal) entered for that period.
		  
		  * 
		  * this is the holly grail of 6 hours of work - will only show total of goals
		  * 
		  * SELECT `Teacher-Subject`.TeacherName, `Teacher-Subject`.ClassSubject , COUNT(DISTINCT Goal.ID) AS totalgoals 
			FROM 
				(SELECT `Class Copesal`.ID, CONCAT(`Teacher`.`Teacher Name`,' ',`Teacher`.`Teacher Last`) 
			        AS TeacherName, CONCAT(Class.Class, '-', `Class Copesal`.Subject) AS ClassSubject
				FROM Teacher
				INNER JOIN `Class Copesal`
				ON Teacher.ID = `Class Copesal`.TeacherID
				INNER JOIN Class
				ON Class.`Class ID` = `Class Copesal`.ClassID) as `Teacher-Subject`       
			LEFT JOIN Indicator
			ON `Teacher-Subject`.ID = Indicator.`Class Copesal_ID`
			LEFT JOIN Goal
			ON Goal.ID = Indicator.`Goal_ID` AND Goal.`Class Grading Period` = 4
			GROUP BY TeacherName, ClassSubject
			ORDER BY TeacherName, ClassSubject;
		  * 
		  * 
		  * this part will show total of indicators per goal
		  * 
		  * 
		  * SELECT Goal.`Class Grading Period`, `Goal`.ID , COUNT(DISTINCT Indicators.IndID) AS TotalIndicators
			FROM Goal
			INNER JOIN (
				SELECT Indicator.Goal_ID, Indicator.ID AS IndID FROM Indicator
			        ) AS Indicators
			ON Indicators.Goal_ID = `Goal`.ID and Goal.`Class Grading Period` = 2
			GROUP BY `Goal`.ID 
		  * 
		  * 
		  * and this is the mother of all (will show total of goals and total of indicators for the period)
		  * 
		  * SELECT `Teacher-Subject`.TeacherName, `Teacher-Subject`.ClassSubject , COUNT(DISTINCT GoalIndicators.GoldID) AS TotalGoals, SUM(GoalIndicators.TotalIndicators) as TotalInd
			FROM 
				(SELECT `Class Copesal`.ID, CONCAT(`Teacher`.`Teacher Name`,' ',`Teacher`.`Teacher Last`) 
				AS TeacherName, CONCAT(Class.Class, '-', `Class Copesal`.Subject) AS ClassSubject
				FROM Teacher
				INNER JOIN `Class Copesal`
					ON Teacher.ID = `Class Copesal`.TeacherID
				INNER JOIN Class
					ON Class.`Class ID` = `Class Copesal`.ClassID) as `Teacher-Subject`       
			LEFT JOIN 
				(SELECT Indicators.`Class Copesal_ID`, Goal.`Class Grading Period`, `Goal`.ID AS GoldID , COUNT(DISTINCT Indicators.IndID) AS TotalIndicators
				FROM Goal
				INNER JOIN (
					SELECT Indicator.`Class Copesal_ID` , Indicator.Goal_ID, Indicator.ID AS IndID FROM Indicator
			        	) AS Indicators
				ON Indicators.Goal_ID = `Goal`.ID
				GROUP BY `Goal`.ID ) as GoalIndicators
			ON GoalIndicators.`Class Copesal_ID` = `Teacher-Subject`.ID AND GoalIndicators.`Class Grading Period` = 2
			GROUP BY TeacherName, ClassSubject
			ORDER BY TeacherName, ClassSubject;
		  * 
		  * 
		  * 
		  * 
		 */	
		
		  
			
		function searchTotalGandIentered(&$period)
		{
			//variables
			//$goal = array(); //this will be the return value	
				
			//set the connection parameters for the database
			include("config-CRDB.php"); //including config file for data connection
			
			//if the connexion does not work then through an error
					
			if(! $link )
			{
  				$msg = die('Could not connect: ' . mysql_error());
				echo "<script>alert('$msg')</script>";
				
			}
			else 
			{	
				
				
				//set the sql command to retrieve the goals for subject and period selected
				
				$sqlGetTotalsGandIEnteredPerPeriod =	"SELECT `Teacher-Subject`.TeacherName, `Teacher-Subject`.ClassSubject , ". 
														"COUNT(DISTINCT GoalIndicators.GoldID) AS TotalGoals, ".
														"SUM(GoalIndicators.TotalIndicators) as TotalInd ".
														"FROM	(SELECT `Class Copesal`.ID, CONCAT(`Teacher`.`Teacher Name`,' ',`Teacher`.`Teacher Last`) ".
																"AS TeacherName, CONCAT(Class.Class, '-', `Class Copesal`.Subject) AS ClassSubject ".
																"FROM Teacher ".
																"INNER JOIN `Class Copesal` ".
																"	ON Teacher.ID = `Class Copesal`.TeacherID ".
																"INNER JOIN Class ".
																"	ON Class.`Class ID` = `Class Copesal`.ClassID) as `Teacher-Subject` ".      
														"LEFT JOIN 	(SELECT Indicators.`Class Copesal_ID`, Goal.`Class Grading Period`, ".
																	"`Goal`.ID AS GoldID , COUNT(DISTINCT Indicators.IndID) AS TotalIndicators ".
																	"FROM Goal ".
																	"INNER JOIN (SELECT Indicator.`Class Copesal_ID` , Indicator.Goal_ID, ".
																				"Indicator.ID AS IndID ".
																				"FROM Indicator) AS Indicators ".
																	"ON Indicators.Goal_ID = `Goal`.ID ".
																	"GROUP BY `Goal`.ID ) as GoalIndicators ".
														"ON GoalIndicators.`Class Copesal_ID` = `Teacher-Subject`.ID ".
														"AND GoalIndicators.`Class Grading Period` = '$period' ".
														"GROUP BY TeacherName, ClassSubject ".
														"ORDER BY TeacherName, ClassSubject; ";

					
				
				//proceed with the adding of the information and get confirmation into a variable
				
				$retval = mysql_query( $sqlGetTotalsGandIEnteredPerPeriod, $link );
						
			
				return $retval;
			
			}
		}
		
		/* 
		 *//**
		 * getHtmlTableTotalGandIentered(&$result)
		 * 
		 * *********************************************************************************
		 * *****************this function will make a table out of a record set**************************
		 * *********************************************************************************
		 * will take a recordset and will turn into into a table that will show a table with 
		  * the following columns: Period / teacher / class / goals / and will show the count of goasl entered for determined period
		 *  
		  
		  * 
		  * 
		  */
		
		function getHtmlTableTotalGandIentered(&$result)
		{
    		// variables for table
    		
    		$i = 1; //counter
    		//$tbmaxsize = 250; // size of text boxes
            //$rowTextAreaL = 3; //rows of Logro box
			//$rowTextAreaI = 2; //rows of Indicator box
			//$sizeTextArea = 60; //width of boxes
    		
    		//table title
    		
    		$out = '<center><h2 class="sansserif">Total Logros e Indicadores Ingresados</h2></center>';
    		
    		$out .= '<table align="center" border="1">'; // set the start of the html output which is a table
	    		$out .= '<col width="25">';
	  			$out .= '<col width="25">';
				$out .= '<col width="25">';
	  			$out .= '<col width="25">';
				
	    		//for($i = 0; $i < mysql_num_fields($result); $i++) //do this as many records exist on the recordset
	    		//{
	        		//$aux = mysql_field_name($result, $i); // get the title of the fields in the record set
	        		//$out .= "<th></th>"; 
	        		
	        	$out .= "<tr>";
	        		$out .= "<th>Profesor</th>"; //put the title in between row headers
	        		$out .= "<th>Clase-Materia</th>";
					$out .= "<th>Total Logros</th>";
					$out .= "<th>Total Indicadores</th>";
	        	$out .= "</tr>";
	    		//}
	    		while ($linea = mysql_fetch_array($result, MYSQL_ASSOC)) //put each reacord fetched into an associative array variable.
	    		{
	        		$out .= "<tr>"; //add the start of the row for the record on the table
		        		//$out .= '<td>Indicador '.$i.'</td>';
		        		//<input type="textbox" name="perd" id="perd" disabled="disabled" size="10" value="'.$_POST['period'].'"/>
		        		//$out .= '<td><input type="textbox" name="tbIndicator'.$i.'" id="tbIndicator'.$i.'" size="'.$tbmaxsize.'" maxlength="'.$tbmaxsize.'" value="'.$linea["Indicadores"].'"></input></td>';
	        			//Teacher subject, class, goal, indicators
	        			$out .= '<td>'.$linea["TeacherName"].'</td>';
						$out .= '<td>'.$linea["ClassSubject"].'</td>';
						$out .= '<td>'.$linea["TotalGoals"].'</td>';
						$out .= '<td>'.$linea["TotalInd"].'</td>';
	        		$out .= "</tr>"; //add the close of the row
	        		$i += 1;
	    		}
				
    		
    		$out .= "</table>"; // add the closing of the table
    		
    		$out .= '<table align="center">';
	    		$out .=  "<tr>";
					$out .=  "<td>";
						$out .=  "<button onclick='goback()'>Regresar</button>";
					$out .=  "</td>";
				$out .=  "</tr>";
            $out .=  '</table>';	
    		$out .= '</br></br>';
    		return $out; //return the html string that contains the whole table.
		}
		
		//function to clean up data entered
		
		function cleanInput($data)
		{
		   $data = trim($data);
		   $data = stripslashes($data);
		   $data = htmlspecialchars($data);
		   return $data;
		}
		
        
        /*
         * 
         *  ******************************************************************************************************************
         * *****************this function will search for number of goal and indicators entered for a period per 
         * ******************************************teacher user*********************************************************
         * *****************************************************************************************************************
         * will take a period and will take user logged and will get a recordset that includes
          * teacher user, Teacher, subject - class, count(goal) , total (indicatores) entered for that period.
          
          * 
          * 
         * SELECT `Teacher-Subject`.user,`Teacher-Subject`.TeacherName, `Teacher-Subject`.ClassSubject ,
COUNT(DISTINCT GoalIndicators.GoldID) AS TotalGoals, 
SUM(GoalIndicators.TotalIndicators) as TotalInd
FROM    (SELECT `Teacher`.user, `Class Copesal`.ID, CONCAT(`Teacher`.`Teacher Name`,' ',`Teacher`.`Teacher Last`)
        AS TeacherName, CONCAT(Class.Class, '-', `Class Copesal`.Subject) AS ClassSubject
        FROM Teacher
        INNER JOIN `Class Copesal`
            ON Teacher.ID = `Class Copesal`.TeacherID
        INNER JOIN Class
            ON Class.`Class ID` = `Class Copesal`.ClassID
        WHERE `Teacher`.user = 'areyes') as `Teacher-Subject`
LEFT JOIN   (SELECT Indicators.`Class Copesal_ID`, Goal.`Class Grading Period`,
            `Goal`.ID AS GoldID , COUNT(DISTINCT Indicators.IndID) AS TotalIndicators
            FROM Goal
            INNER JOIN (SELECT Indicator.`Class Copesal_ID` , Indicator.Goal_ID,
                        Indicator.ID AS IndID
                        FROM Indicator) AS Indicators
                        ON Indicators.Goal_ID = `Goal`.ID
                        GROUP BY `Goal`.ID ) as GoalIndicators
            ON GoalIndicators.`Class Copesal_ID` = `Teacher-Subject`.ID
            AND GoalIndicators.`Class Grading Period` = 1
GROUP BY TeacherName, ClassSubject
ORDER BY TeacherName, ClassSubject
         * 
        */
        
        function searchTotalGandIenteredPerUser(&$user)
        {
            
            //set the connection parameters for the database
            include("config-CRDB.php"); //including config file for data connection
            
            //if the connexion does not work then through an error
                    
            if(! $link )
            {
                $msg = die('Could not connect: ' . mysql_error());
                echo "<script>alert('$msg')</script>";
                
            }
            else 
            {   
                
                
                //set the sql command to retrieve the goals for subject and period selected
                
                $sqlGetTotalsGandIEnteredPerPeriodandUser = "SELECT GoalIndicators.`Class Grading Period` AS Period,`Teacher-Subject`.TeacherName, `Teacher-Subject`.ClassSubject , ".
                                                            "COUNT(DISTINCT GoalIndicators.GoldID) AS TotalGoals, ".
                                                            "SUM(GoalIndicators.TotalIndicators) as TotalInd ".
                                                            "FROM    (SELECT `Teacher`.ID as tid, `Class Copesal`.ID, CONCAT(`Teacher`.`Teacher Name`,' ',`Teacher`.`Teacher Last`) ".
                                                                    "AS TeacherName, CONCAT(Class.Class, '-', `Class Copesal`.Subject) AS ClassSubject ".
                                                                    "FROM Teacher ".
                                                                    "INNER JOIN `Class Copesal` ".
                                                                        "ON Teacher.ID = `Class Copesal`.TeacherID ".
                                                                    "INNER JOIN Class ".
                                                                        "ON Class.`Class ID` = `Class Copesal`.ClassID ".
                                                                    "WHERE `Teacher`.ID = '$user') as `Teacher-Subject` ".
                                                            "LEFT JOIN   (SELECT Indicators.`Class Copesal_ID`, Goal.`Class Grading Period`, ".
                                                                        "`Goal`.ID AS GoldID , COUNT(DISTINCT Indicators.IndID) AS TotalIndicators ".
                                                                        "FROM Goal ".
                                                                        "INNER JOIN (SELECT Indicator.`Class Copesal_ID` , Indicator.Goal_ID, ".
                                                                                    "Indicator.ID AS IndID ".
                                                                                    "FROM Indicator) AS Indicators ".
                                                                                    "ON Indicators.Goal_ID = `Goal`.ID ".
                                                                                    "GROUP BY `Goal`.ID ) as GoalIndicators ".
                                                                        "ON GoalIndicators.`Class Copesal_ID` = `Teacher-Subject`.ID ".
                                                            "GROUP BY TeacherName, ClassSubject ".
                                                            "ORDER BY `Class Grading Period`, TeacherName, ClassSubject; ";

                    
                
                //proceed with the adding of the information and get confirmation into a variable
                
                $retval = mysql_query( $sqlGetTotalsGandIEnteredPerPeriodandUser, $link );
                        
            
                return $retval;
            
            }
        }

/* 
         *//**
         * getHtmlTableTotalGandIentered(&$result)
         * 
         * *********************************************************************************
         * *****************this function will make a table out of a record set**************************
         * *********************************************************************************
         * will take a recordset and will turn into into a table that will show a table with 
          * the following columns: Period / teacher / class / goals / and will show the count of goasl entered for determined period
         *  
          
          * 
          * 
          */
        
        function getHtmlTableTotalGandIenteredPerUser(&$result)
        {
            // variables for table
            
            $i = 1; //counter
            //$tbmaxsize = 250; // size of text boxes
            //$rowTextAreaL = 3; //rows of Logro box
            //$rowTextAreaI = 2; //rows of Indicator box
            //$sizeTextArea = 60; //width of boxes
            
            //table title
            
            $out = '<center><h2 class="sansserif">Total Logros e Indicadores Ingresados</h2></center>';
            
            $out .= '<table align="center" border="1">'; // set the start of the html output which is a table
                $out .= '<col width="25">';
                $out .= '<col width="25">';
                $out .= '<col width="25">';
                $out .= '<col width="25">';
                
                //for($i = 0; $i < mysql_num_fields($result); $i++) //do this as many records exist on the recordset
                //{
                    //$aux = mysql_field_name($result, $i); // get the title of the fields in the record set
                    //$out .= "<th></th>"; 
                    
                $out .= "<tr>";
                    $out .= "<th>Periodo</th>"; //put the title in between row headers
                    $out .= "<th>Profesor</th>";
                    $out .= "<th>Clase-Materia</th>";
                    $out .= "<th>Total Logros</th>";
                    $out .= "<th>Total Indicadores</th>";
                $out .= "</tr>";
                //}
                while ($linea = mysql_fetch_array($result, MYSQL_ASSOC)) //put each reacord fetched into an associative array variable.
                {
                    $out .= "<tr>"; //add the start of the row for the record on the table
                        //$out .= '<td>Indicador '.$i.'</td>';
                        //<input type="textbox" name="perd" id="perd" disabled="disabled" size="10" value="'.$_POST['period'].'"/>
                        //$out .= '<td><input type="textbox" name="tbIndicator'.$i.'" id="tbIndicator'.$i.'" size="'.$tbmaxsize.'" maxlength="'.$tbmaxsize.'" value="'.$linea["Indicadores"].'"></input></td>';
                        //Teacher subject, class, goal, indicators
                        $out .= '<td>'.$linea["Period"].'</td>';
                        $out .= '<td>'.$linea["TeacherName"].'</td>';
                        $out .= '<td>'.$linea["ClassSubject"].'</td>';
                        $out .= '<td>'.$linea["TotalGoals"].'</td>';
                        $out .= '<td>'.$linea["TotalInd"].'</td>';
                    $out .= "</tr>"; //add the close of the row
                    $i += 1;
                }
                
            
            $out .= "</table>"; // add the closing of the table
            
              
            $out .= '</br></br>';
            return $out; //return the html string that contains the whole table.
        }



        /*
         * this function below will get all the goal an indicators that a user has entered
         * 
         * */

        function SearchGoalandIndicatorsPerUser(&$userid)
        {
            //variables
            //$goal = array(); //this will be the return value  
                
            //set the connection parameters for the database
            include("config-CRDB.php"); //including config file for data connection
            
            //if the connexion does not work then through an error
                    
            if(! $link )
            {
                $msg = die('Could not connect: ' . mysql_error());
                echo "<script>alert('$msg')</script>";
                
            }
            else 
            {   
                
                
                //set the sql command to retrieve the goals for subject and period selected
                
                $sqlGetGoalnIndPerUser =    "SELECT Goal.`Class Grading Period` AS Period, CONCAT(`Teacher`.`Teacher Name`,' ',`Teacher`.`Teacher Last`) ".
                                            "AS TeacherName, CONCAT(Class.Class, '-', `Class Copesal`.Subject) AS ClassSubject, ".
                                            "`Goal`.Goal, `Indicator`.Indicator ".
                                            "FROM `Teacher` ".
                                            "INNER JOIN `Class Copesal` ".
                                            "ON Teacher.ID = `Class Copesal`.TeacherID ".
                                            "INNER JOIN Class ".
                                            "ON Class.`Class ID` = `Class Copesal`.ClassID ".
                                            "INNER JOIN Indicator ".
                                            "ON Indicator.`Class Copesal_ID` = `Class Copesal`.ID ".
                                            "INNER JOIN Goal ".
                                            "ON Goal.ID = Indicator.`Goal_ID` ".
                                            "WHERE `Teacher`.ID = '$userid' ".
                                            "ORDER BY Goal.`Class Grading Period`, TeacherName, ClassSubject; ";

                    
                
                //proceed with the adding of the information and get confirmation into a variable
                
                $retval = mysql_query( $sqlGetGoalnIndPerUser, $link );
                        
            
                return $retval;
            
            }
        }

/* 
         *//**
         * getHtmlTable4GandIEnteredPerUser(&$result)
         * 
         * *********************************************************************************
         * *****************this function will make a table out of a record set**************************
         * ***************************for the records retrieved with SearchGoalandIndicatorsPerUser*************
         * 
         */ 
        //function that takes a recordset with goals and out put it as as an html table
        
        function getHtmlTable4GandIEnteredPerUser(&$result)
        {
            // receive a record set and print
            // it into an html table
            
            $i = 1; //counter
            //$tbmaxsize = 250; // size of text boxes
            //$rowTextAreaL = 3; //rows of Logro box
            //$rowTextAreaI = 2; //rows of Indicator box
            //$sizeTextArea = 60; //width of boxes
            
            //title of table
            
            $out = '<center><h2 class="sansserif">Indicadores y logros Ingresados</h2></center>';
            
            //start of table
            
            $out .= '<table align="center" border="1">'; // set the start of the html output which is a table
                $out .= '<col width="25">';
                $out .= '<col width="25">';
                $out .= '<col width="25">';
                $out .= '<col width="25">';
                
                //for($i = 0; $i < mysql_num_fields($result); $i++) //do this as many records exist on the recordset
                //{
                    //$aux = mysql_field_name($result, $i); // get the title of the fields in the record set
                    //$out .= "<th></th>"; 
                    
                $out .= "<tr>";
                    $out .= "<th>Periodo</th>";
                    $out .= "<th>Profesor</th>"; //put the title in between row headers
                    $out .= "<th>Clase-Materia</th>";
                    $out .= "<th>Logro</th>";
                    $out .= "<th>Indicador</th>";
                $out .= "</tr>";
                //}
                while ($linea = mysql_fetch_array($result, MYSQL_ASSOC)) //put each reacord fetched into an associative array variable.
                {
                    $out .= "<tr>"; //add the start of the row for the record on the table
                        //$out .= '<td>Indicador '.$i.'</td>';
                        //<input type="textbox" name="perd" id="perd" disabled="disabled" size="10" value="'.$_POST['period'].'"/>
                        //$out .= '<td><input type="textbox" name="tbIndicator'.$i.'" id="tbIndicator'.$i.'" size="'.$tbmaxsize.'" maxlength="'.$tbmaxsize.'" value="'.$linea["Indicadores"].'"></input></td>';
                        //Teacher subject, class, goal, indicators
                        $out .= '<td>'.$linea["Period"].'</td>';
                        $out .= '<td>'.$linea["TeacherName"].'</td>';
                        $out .= '<td>'.$linea["ClassSubject"].'</td>';
                        $out .= '<td>'.$linea["Goal"].'</td>';
                        $out .= '<td>'.$linea["Indicator"].'</td>';
                    $out .= "</tr>"; //add the close of the row
                    $i += 1;
                }
                
            
            $out .= "</table>"; // add the closing of the table
           
            return $out; //return the html string that contains the whole table.
        }



?>