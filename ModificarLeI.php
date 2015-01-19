<?php
    session_start();  //session to start the page as info is only processable if the session is set	
?>	
	
<!DOCTYPE html>
<html>
    <head>
        <title>ModificarLeITeacher</title>
        	
	        <script type="text/javascript" charset="utf-8">
	        	
	        	function gobackNewSubject()
				{
					var modprocessForm = document.getElementById ("modGoalform");
					modprocessForm.action = "admin.php"	
					modprocessForm.submit();
				}	
			
				
				function DoTrimRemoveReturns(text)
				{
					text = text.replace(/[\s\t\n\r]/g,' '); //replace white, tabs, carriage returns for a space in any part of the string
					text = text.replace(/^\s\s*/, '').replace(/\s\s*$/, ''); // remove spaces at each end of the string
					text = text.replace(/\s+/g," "); //this replaces multiple spaces for only one space.
					return text; //return string fixed
				}
	        	
	        	function saveChanges_Click()
	        	{
	        		var message = ""; //get the form and the items from the form themselves.
	        		
	        		var modForm = document.getElementById ("modGoalform"); // get the form and put it into a variable
		        	
		        	
		        	var subjectID = document.getElementById("csid").value; //get the id of the classSubject
					var subject = document.getElementById("classSub").value // get the subject and class
					
					message += "\nSubject:\n"+ subject + "\n";
					
					// get the period
					
					var period = document.getElementById ("perd").value; 
					
					message += "\nPeriod:\n"+ period + "\n";
					
					//get the goal
		        	
		        	var goal = document.getElementById ("tbgoal").value; // put goal text box into variable
		        	
		        	goal = DoTrimRemoveReturns(goal); //trim and remove carriage returns on the the goal text
		        	
		        	goal = goal.toUpperCase(); //convert goal string to upper case
		        	
		        	message += "\nGoal:\n"+ goal + "\n";
		  
		        	
		        	// take each indicator box and put into a variable then 
		        	//fix the string (trim it and remove carriage returns)
		        	
		        	var ind1 = document.getElementById("tbIndicator1").value 
		        	ind1 = DoTrimRemoveReturns(ind1);
		        	var ind2 = document.getElementById("tbIndicator2").value; 
		        	ind2 = DoTrimRemoveReturns(ind2);
		        	var ind3 = document.getElementById("tbIndicator3").value; 
		        	ind3 = DoTrimRemoveReturns(ind3);
		        	var ind4 = document.getElementById("tbIndicator4").value; 
		        	ind4 = DoTrimRemoveReturns(ind4);
		        	var ind5 = document.getElementById("tbIndicator5").value; 
		        	ind5 = DoTrimRemoveReturns(ind5);
		        	var ind6 = document.getElementById("tbIndicator6").value; 
		        	ind6 = DoTrimRemoveReturns(ind6);
		        	var ind7 = document.getElementById("tbIndicator7").value; 
		        	ind7 = DoTrimRemoveReturns(ind7);
		        	
		        	message += "\nIndicators:\n"+ ind1 + "\n" + ind2 + "\n" + ind3 + "\n" + ind4 + "\n" + ind5 + "\n" + ind6 + "\n" + ind7;
		        	
		        	//var allgood = 1;
		        	//check if the subject id is good
		        	
		        	
		        	if(subjectID < 1)
		        	{
		        		alert('you need to select a subject');
		        		return;
		        	}
		        	
		        	//check if the period is set
		        	
		        	if(period == "")
		        	{
		        		alert('you need to select a period');
		        		return;
		        	}
		        	
		        	//check if goal has text
		        	
		        	if(goal.length == 0)
		        	{
		        		alert('the goal can\'t be empty');
		        		return;
		        	}
		        	
		        	//check if all the periods have text
		        	if((ind1.length == 0) && (ind2.length == 0) && (ind3.length == 0) && (ind4.length == 0) && (ind5.length == 0) && (ind6.length == 0) && (ind7.length == 0)){
		        		alert('you need to enter at least one indicator');
		        		return;
		        	}
		        	
		        	//alert('hoorey, all is good');
		        	
		        	var response=confirm("Are you sure you want to save this information: \n" + message);
		        	if (response == true)
		        	{
		        		//alert('the goal id is ')
		        		//document.getElementById("classid").value = subjectID; //populate the hidden field with the id of the ddbox item selected
		        		//document.getElementById("classsub").value = subject; //populate hidden file with the subject as this may be need later
		        		document.getElementById("tbgoal").value = goal; //since the goal was set to upper case save this back on the form object to pass it.
		        		document.getElementById("modORdel").value = "mod";
		        		
		        		//set the fixed indicator values (trimmed values) back into the text boxes before submitting the page
		        		document.getElementById("tbIndicator1").value = ind1
		        		document.getElementById("tbIndicator2").value = ind2
		        		document.getElementById("tbIndicator3").value = ind3
		        		document.getElementById("tbIndicator4").value = ind4
		        		document.getElementById("tbIndicator5").value = ind5
		        		document.getElementById("tbIndicator6").value = ind6
		        		document.getElementById("tbIndicator7").value = ind7
		        		
		        		modForm.action = "modprocess.php"; // set the form action to submit it to that file.
		        		modForm.submit(); // submit the form.
		        	}
		        	else
		        	{
		        		;//do nothing
		        	}	        	
	        	}
	        	
	        	function deleteGoal_Click()
	        	{
	        		var message = ""; //get the form and the items from the form themselves.
	        		
	        		var modForm = document.getElementById ("modGoalform"); // get the form and put it into a variable
		        	
		        	
		        	var subjectID = document.getElementById("csid").value; //get the id of the classSubject
					var subject = document.getElementById("classSub").value // get the subject and class
					
					message += "\nSubject:\n"+ subject + "\n";
					
					// get the period
					
					var period = document.getElementById ("perd").value; 
					
					message += "\nPeriod:\n"+ period + "\n";
					
					//get the goal
		        	
		        	var goal = document.getElementById ("tbgoal").value.replace(/^\s+|\s+$/g, '');; // goal text box trimmed
		        	
		        	goal = goal.toUpperCase(); //convert goal string to upper case
		        	
		        	message += "\nGoal:\n"+ goal + "\n";
		        	
		        	//get goal id
		        	//var goalid = document.getElementById["$submitGoalID"].value;
		        	
		        	// get the indicators
		        	
		        	var ind1 = document.getElementById("tbIndicator1").value.replace(/^\s+|\s+$/g, ''); //indicator1 trimmed
		        	var ind2 = document.getElementById("tbIndicator2").value.replace(/^\s+|\s+$/g, ''); //indicator2 trimmed
		        	var ind3 = document.getElementById("tbIndicator3").value.replace(/^\s+|\s+$/g, ''); //indicator3 trimmed
		        	var ind4 = document.getElementById("tbIndicator4").value.replace(/^\s+|\s+$/g, ''); //indicator4 trimmed
		        	var ind5 = document.getElementById("tbIndicator5").value.replace(/^\s+|\s+$/g, ''); //indicator5 trimmed
		        	var ind6 = document.getElementById("tbIndicator6").value.replace(/^\s+|\s+$/g, ''); //indicator6 trimmed
		        	var ind7 = document.getElementById("tbIndicator7").value.replace(/^\s+|\s+$/g, ''); //indicator7 trimmed
		        	
		        	message += "\nIndicators:\n"+ ind1 + "\n" + ind2 + "\n" + ind3 + "\n" + ind4 + "\n" + ind5 + "\n" + ind6 + "\n" + ind7;
		        	
		        	//var allgood = 1;
		        	//check if the subject id is good
		        	
		        	if(subjectID < 1)
		        	{
		        		alert('you need to select a subject');
		        		return;
		        	}
		        	
		        	//check if the period is set
		        	
		        	if(period == "")
		        	{
		        		alert('you need to select a period');
		        		return;
		        	}
		        	
		        	//check if goal has text
		        	
		        	if(goal.length == 0)
		        	{
		        		alert('the goal can\'t be empty');
		        		return;
		        	}
		        	
		        	//check if all the periods have text
		        	if((ind1.length == 0) && (ind2.length == 0) && (ind3.length == 0) && (ind4.length == 0) && (ind5.length == 0) && (ind6.length == 0) && (ind7.length == 0)){
		        		alert('you need to enter at least one indicator');
		        		return;
		        	}
		        	
		        	//alert('hoorey, all is good');
		        	
		        	var response=confirm("Are you sure you want to delete this information?: \n" + message);
		        	if (response == true)
		        	{
		        		//alert('the goal id is ')
		        		//document.getElementById("classid").value = subjectID; //populate the hidden field with the id of the ddbox item selected
		        		//document.getElementById("classsub").value = subject; //populate hidden file with the subject as this may be need later
		        		document.getElementById("tbgoal").value = goal; //since the goal was set to upper case save this back on the form object to pass it.
		        		document.getElementById("modORdel").value = "del";
		        		modForm.action = "modprocess.php"; // set the form action to submit it to that file.
		        		modForm.submit(); // submit the form.
		        	}
		        	else
		        	{
		        		;//do nothing
		        	}	        	
	        	}
	        	
	        	function SearchGoal()
				{
					//get the form in a variable
					
					var modprocessForm = document.getElementById("modGoalform");
					
					//get the radio buttons from the form
					
					var per1 = document.getElementById ("per1"); 
					var per2 = document.getElementById ("per2"); 
					var per3 = document.getElementById ("per3"); 
					var per4 = document.getElementById ("per4");
					
					//check at least one period is checked if not alert and exit out
					
					if(!per1.checked && !per2.checked && !per3.checked && !per4.checked)
		        	{
		        		alert('you need to select a period');
		        		return;
		        	}
					
					//check that the subjectID is not empty if it is empty exit out.
					
					if(subjectID < 1)
		        	{
		        		alert('you need to select a subject');
		        		return;
		        	}
					
					//get the subjectid and subject elements from the form
					
					//var subjectOnDDB = document.getElementById ("ddboxClassSubject"); // subject dropdown
		        	//var subjectID = subjectOnDDB.options[subjectOnDDB.selectedIndex].value; //get the id selected from dropdown
					//var subject = subjectOnDDB.options[subjectOnDDB.selectedIndex].text;
					
					
					//document.getElementById("classid").value = subjectID; //populate the hidden field with the id of the ddbox item selected
		        	//document.getElementById("classsub").value = subject; //populate hidden file with the subject as this may be need later
					
					//set the action to where submit the form (same page)
					
					modprocessForm.action = "ModificarLeI.php";	
					modprocessForm.submit();
					 	
				}
				
				//function setGoalID(id,goal)
				function setGoalID(id,goal)
			   	{
					
					document.modGoalform.submitGoalID.value = id; // set the hidden value of the goal id
					document.modGoalform.GoalText.value = goal; // set the hiddne value of the goal text
					//alert('id '+ id + 'goal ' + goal);
					
				}
			    
			    
				
				   
	        </script>      
    </head>
    <body>
    <?php
        if(isset($_SESSION['currentUser'])) // if the super global variable session called current user has been initialized then
        {
        	//variables
            
            $currentUser = $_SESSION['currentUser']; // current user logged in
            $per = $_POST['rdPeriod']; // period
            $cid = $_POST['classid']; // the classid from the dropdown from previous page.
			$csubject = $_POST['classsub']; // the classSubject from the dropdown from previous page.
            $goalText = $_POST['GoalText']; //get the goaltext submitted (selected) which is set in the respective textarea
			$submitGoalID = $_POST['submitGoalID']; //get the submitted goal id to search for its indicators
            $tbmaxsize = 125; // size of text boxes
            
            
            
            //**************************************************************
            //*********** Biginning of the form ****************************
            //**************************************************************
            //**************************************************************
            //**************************************************************
			
            echo "<form id = 'modGoalform' name = 'modGoalform' method='post'>";
            echo "Bienvenido(a) ".$currentUser; // show the current user
           	echo "</br>";
           	echo "<a href='logout.php'>Logout</a>";
			echo "</br>";
            //title
                echo '<div id="containt" align="center">';
				
                echo '<div id="header" align="center"><h2 class="sansserif">Modificacion de Logros e Indicadores</h2></div>';
                    echo "<input type='hidden' id = 'classid' name='classid' value='$cid'>";
				    echo "<input type='hidden' id = 'classsub' name='classsub' value='$csubject'>";
                    echo "<input type='hidden' id='submitGoalID' = name='submitGoalID' value=''>"; //$submitGoalID
					echo "<input type='hidden' id='GoalText' = name='GoalText' value='$goalText'>";
					echo "<input type='hidden' id='period' = name='period' value='$per'>";
					echo "<input type='hidden' id='modORdel' = name='modORdel' value=''>";
                    echo '<table>';

                    //**************************************************************
            		//*********** Class- Subject Dropdown ****************************
            		//**************************************************************
            		
                        echo '<tr>';//row 1

                            echo '<td>'; //cell 1.1
                                //the dropdownbox starts being defined here
                                echo "Seleccione el curso y la materia</br>";
                                //echo '</br>';

                            echo '</td>';
                            echo '<td>'; //cell 1.2
                                echo "<select name='ddboxClassSubject' id = 'ddboxClassSubject' >";
                                //the following code gets each one of the rows of the query until there's none
                                //puts it on a row variable
                                //while ($row = mysql_fetch_array($result)) {
                                    // then adds that as one of the options in the dropdown
                                    //echo "<option value='" . $cid . "' selected>" . utf8_encode($csubject) . "</option>";
									echo "<option value='" . $cid . "' selected>" . $csubject . "</option>";
                                //}
                                echo "</select>"; //this closes the dropdownbox.
                            echo '</td>';

                        echo '</tr>'; 
					                        
                       //this following section has the period selector
                        
                        //**************************************************************
            			//*********** Period selector ****************************
            			//**************************************************************
                        
                        echo '<tr>'; // row 2

                            echo '<td>'; //cell 2.1

                                echo "Seleccione el periodo</br>";
                                //echo '</br>';


                            echo '</td>';
                            
                            echo '<td>'; // cell 2.2
                            
                            
									echo '<input type="radio" name="rdPeriod" id="per1" value="1">1' ;
	                                echo '<input type="radio" name="rdPeriod" id="per2" value="2">2' ;
									echo '<input type="radio" name="rdPeriod" id="per3" value="3">3' ;
									echo '<input type="radio" name="rdPeriod" id="per4" value="4">4' ;
								
							
                                
                            
                            echo '</td>';

                        echo '</tr>';
						
                        //the following section shows button to search
                        
                        //**************************************************************
	            		//*********** Button to search for goals ****************************
	            		//**************************************************************
	            		
	            		 echo '<tr>'; // row 2

                            echo '<td>'; //cell 2.1

                                echo '<button id="btnSearchGoal" name="btnSearchGoal" onClick="SearchGoal()">Buscar Logros</button>';
                                //echo '<button type="button" name="btnInsertarLogro" id="btnInsertarLogro" align="center" onClick= "btnsave_Click()" />Insertar Logro</button>';
                                echo '</br>';
								echo '<button id="btnOtherSubject" name="btnOtherSubject" onClick="gobackNewSubject()">Otra Materia</button>';


                            echo '</td>';
                            
                            echo '<td>'; // cell 2.2
	                                
                                //results of Search goal will be displayed here
                                //if the form is submitted and the period is selected (the period is not selected on load)
                                //then get the class id and the period and search for the goals matching that criteria
                                //and display them for the user to select
                                //and not empty.
                                if(!empty($_POST['rdPeriod']))
                                {
                                	//echo '<script>alert("'.$per.' and '.$cid.'")</script>';
                                    include("functions.php"); // get the include file to execute the function
									$rs = SearchGoal($cid,$per); //get the record set of goal ids and ids
									echo getHtmlTable($rs); //populate a table out of the record set.
                               	}
                            
                            echo '</td>';

                        
                        echo '</tr>';
	            	
	            	echo '</table>';
					
					echo '<hr>'; // divider
				//echo '<div>';	
             		//the next segment is expected to be executed when the form is submitted when pressing the goal that
             		//the user is looking to update/modify which will display 2 tables with the goal text and respective
             		//indicators text.
	             	if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['submitGoalID']) && !empty($_POST['GoalText']))
	                {
						//the following section shows a textarea to modify the goal
						
						//variables for the goal box
						$tbmaxsize = 250; // size of text boxes
			            $rowTextAreaL = 3; //rows of Logro box
						//$rowTextAreaI = 2; //rows of Indicator box
						$sizeTextArea = 60; //width of boxes
						
						//**************************************************************
						//*********** Goal text Area ****************************
						//**************************************************************
						
	                     
						echo '<table align="center">'; 
							
							echo '<col width="100">';
							echo '<col width="100">';
							
							echo '<tr>'; 
							
								echo '<th>';
									echo 'Periodo&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp'; 
								echo '</th>';
							
								echo '<td>';
									echo '<input type="textbox" name="perd" id="perd" disabled="disabled" size="10" value="'.$_POST['period'].'"/>';
									echo '<input type="hidden" name="perd" id="perd" value="'.$_POST['period'].'"/>'; 
								echo '</td>';
								
							echo '</tr>'; 
						
						  	echo '<tr>'; 
							
								echo '<th>';
									echo 'Clase&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp'; 
								echo '</th>';
							
								echo '<td>';
									echo '<input type="text" name="classSub" id="classSub" disabled="disabled" size="20" value="'.$_POST['classsub'].'"/>'; 
									echo '<input type="hidden" name="csid" id="csid" size="20" value="'.$_POST['classid'].'"/>'; 
								echo '</td>';
								
							echo '</tr>'; 
	                        echo '<tr>'; //row 3
	
	                            echo '<th>'; //cell 3.1
	
	                                echo '<label>Logro&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label></br>';
	                                
	
	                            echo '</th>';
	                            
	                            echo '<td>'; //cell 3.2
	                            
	                                //echo '<input type="textbox" name="tbgoal" id="tbgoal" maxlength ="'.$tbmaxsize.'" size="'.$tbmaxsize.'" value="'.$goalText.'"/><br>';
	                              	echo '<textarea rows='.$rowTextAreaL.' cols='.$sizeTextArea.' name="tbgoal" id="tbgoal" maxlength ='.$tbmaxsize.'>'.$goalText.'</textarea><br>';
	                              	echo '<input type="hidden" name="gid" id="gid" value="'.$_POST['submitGoalID'].'"/>';
	                            echo '</td>';
	
	                        echo '</tr>';
						echo '</table>'; 	
						 
	                    
                        //this section is to modify the first indicator
                        
                        //**************************************************************
            			//*********** table with indicators ****************************
            			//************************************'**************************
                        include("functions.php"); // get the include file to execute the function
									
						$rs1 = getGoalIndicators($submitGoalID); //get the record set of goal ids and ids
									//echo getGoalIndicators($submitGoalID);
									//echo 'something';
									//echo "<script>alert('$submitGoalID and $goalText and $rs')</script>"; 
						echo getHtmlTable4Indicators($rs1); //populate a table out of the record set.
								
				
// 									
					}
					else
					{
						;//don't display shit	
					}
                       
                //echo '</div>';    
                        
           
                        
            //**************************************************************
            //*********** End of Form ****************************
        	//**************************************************************
        	/**************************************************************
			 * /**************************************************************
			 */
			
            echo "</form>";

        }
        else
        {
            // indicate that the user has not logged in yet
            echo "necesita haber sido autenticado para ver el contenido de esta pagina"; //otherwise say your are not logged in.
            echo "</br>";
        	echo "<a href='logout.php'>Log In</a>";
        }

    ?>
    </body>
</html>
