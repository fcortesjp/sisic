<?php
    session_start();  //session to start the page as info is only processable if the session is set	
?>	
	
<!DOCTYPE html>
<html>
    <head>
        <title>IngreseLeITeacher</title>
        	
	        <script type="text/javascript" charset="utf-8">
	        	
	        	function gobackNewSubject()
				{
					var addprocessForm = document.getElementById ("addGoalform");
					addprocessForm.action = "admin.php"	
					addprocessForm.submit();
					 	
				}
				
				function DoTrimRemoveReturns(text)
				{
					text = text.replace(/[\s\t\n\r]/g,' '); //replace white, tabs, carriage returns for a space in any part of the string
					text = text.replace(/^\s\s*/, '').replace(/\s\s*$/, ''); // remove spaces at each end of the string
					text = text.replace(/\s+/g," "); //this replaces multiple spaces for only one space.
					return text; //return string fixed
				}
	        	
	        	function btnsave_Click()
	        	{
	        		var message = ""; //get the form and the items from the form themselves.
	        		
	        		var addForm = document.getElementById ("addGoalform"); // get the form and put it into a variable
		        	
		        	var subjectOnDDB = document.getElementById ("ddboxClassSubject"); // subject dropdown
		        	var subjectID = subjectOnDDB.options[subjectOnDDB.selectedIndex].value; //get the id selected from dropdown
					var subject = subjectOnDDB.options[subjectOnDDB.selectedIndex].text;
					
					message += "\nSubject:\n"+ subject + "\n";
					
					var per1 = document.getElementById ("per1"); // radiobutton selection
					var per2 = document.getElementById ("per2"); 
					var per3 = document.getElementById ("per3"); 
					var per4 = document.getElementById ("per4");
					
					
					
					var period;
					
					if (per1.checked)
					{
						period = 1;
					}
					else if (per2.checked)
					{
						period = 2;
					}
					else if (per3.checked)
					{
						period = 3;
					}
					else
					{
						period = 4;
					}
					
					message += "\nPeriod:\n"+ period + "\n";
					
					
		        	var goal = document.getElementById ("tbgoal").value;
		        	
		        	goal = DoTrimRemoveReturns(goal);
		        	
		        	goal = goal.toUpperCase(); //convert this string to upper case
		        	
		        	message += "\nGoal:\n"+ goal + "\n"; //get goal into the confirmation message
		        	
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
		        	
		        	if(!per1.checked && !per2.checked && !per3.checked && !per4.checked)
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
		        	
		        	var response=confirm("are you sure you want to save this information: \n" + message);
		        	if (response == true)
		        	{
		        		document.getElementById("classid").value = subjectID; //populate the hidden field with the id of the ddbox item selected
		        		document.getElementById("classsub").value = subject; //populate hidden file with the subject as this may be need later
		        		document.getElementById("tbgoal").value = goal; //since the goal was set to upper case save this back on the form object to pass it.
		        		
		        		//set the fixed indicator valures (trimmed values) back into the text boxes before submitting the page
		        		document.getElementById("tbIndicator1").value = ind1
		        		document.getElementById("tbIndicator2").value = ind2
		        		document.getElementById("tbIndicator3").value = ind3
		        		document.getElementById("tbIndicator4").value = ind4
		        		document.getElementById("tbIndicator5").value = ind5
		        		document.getElementById("tbIndicator6").value = ind6
		        		document.getElementById("tbIndicator7").value = ind7
		        		
		        		
		        		addForm.action = "addprocess.php"; // set the form action to submit it to that file.
		        		addForm.submit(); // submit the form.
		        	}
		        	else
		        	{
		        		;//do nothing
		        	}	        	
	        	}        
	        </script>      
    </head>
    <body>
    <?php
        if(isset($_SESSION['currentUser'])) // if the super global variable session called current user has been initialized then
        {
        	//variables
            
            $currentUser = $_SESSION['currentUser']; // current user logged in
            
            $cid = $_POST['classid']; // the classid from the dropdown from previous page.
			$csubject = $_POST['classsub']; // the classSubject from the dropdown from previous page.
            
            $tbmaxsize = 250; // size of text boxes
            $rowTextAreaL = 3; //rows of Logro box
			$rowTextAreaI = 2; //rows of Indicator box
			$sizeTextArea = 60; //width of boxes
            
            
            //**************************************************************
            //*********** Biginning of the form ****************************
            //**************************************************************
            //**************************************************************
            //**************************************************************
			
          	echo "<form id = 'addGoalform' name = 'addGoalform' method='post'>";
            echo "Bienvenido(a) ".$currentUser; // show the current user
           	echo "</br>";
           	echo "<a href='logout.php'>Logout</a>";
			echo "</br>";
            //title
                echo '<div id="containt" align="center">';
				
                echo '<div id="header" align="center"><h2 class="sansserif">Ingreso de Logros e Indicadores</h2></div>';
                    echo "<input type='hidden' id = 'classid' name='classid' value=''>";
				    echo "<input type='hidden' id = 'classsub' name='classsub' value=''>";
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
                        
                        //this following section has a the period selector
                        
                        //**************************************************************
            			//*********** Period selector ****************************
            			//**************************************************************
                        
                        echo '<tr>'; // row 2

                            echo '<td>'; //cell 2.1

                                echo "Seleccione el periodo</br>";
                                //echo '</br>';


                            echo '</td>';
                            
                            echo '<td>'; // cell 2.2
                                
                                echo '<input type="radio" name="rdPeriod" id="per1" value="1">1 ';
                                echo '<input type="radio" name="rdPeriod" id="per2" value="2">2 ';
                                echo '<input type="radio" name="rdPeriod" id="per3" value="3">3 ';
                                echo '<input type="radio" name="rdPeriod" id="per4" value="4">4 ';
                            echo '</td>';

                        echo '</tr>';
						
                        //the following section shows a textarea to add the goal
                        
                        //**************************************************************
	            		//*********** Goal text Area ****************************
	            		//**************************************************************
                        
                        echo '<tr>'; //row 3

                            echo '<td>'; //cell 3.1

                                echo 'Ingrese el logro</br>';
                                //echo '</br>';

                            echo '</td>';
                            
                            echo '<td>'; //cell 3.2
                            
                                echo '<textarea rows='.$rowTextAreaL.' cols='.$sizeTextArea.' name="tbgoal" id="tbgoal" maxlength ='.$tbmaxsize.'></textarea><br>';
                              
                            echo '</td>';

                        echo '</tr>';
                        
                        //this section is for the first indicator
                        
                        //**************************************************************
            			//*********** Indicator 1 ****************************
            			//**************************************************************
                        
                        echo '<tr>'; //row 4

                            echo '<td>'; //cell 4.1

                                echo 'Ingrese Indicador 1 </br>';
                                //echo '</br>';



                            echo '</td>';
                            
                            echo '<td>'; //cell 4.2
                            
                                echo '<textarea rows='.$rowTextAreaI.' cols='.$sizeTextArea.' name="tbIndicator1" id="tbIndicator1" maxlength="'.$tbmaxsize.'"></textarea><br>';
                                
                            echo '</td>';

                        echo '</tr>';
                       
					   
					    //this section is for the Second indicator
                        
                        //**************************************************************
            			//*********** Indicator 2 ****************************
            			//**************************************************************
                        
                        
                        echo '<tr>'; //row 5

                            echo '<td>'; //cell 5.1

                                echo 'Ingrese Indicador 2 </br>';
                                //echo '</br>';



                            echo '</td>';
                            
                            echo '<td>'; //cell 5.2
                            
                                echo '<textarea rows='.$rowTextAreaI.' cols='.$sizeTextArea.' name="tbIndicator2" id="tbIndicator2" maxlength="'.$tbmaxsize.'"></textarea><br>';
                                
                            echo '</td>';

                        echo '</tr>';
                        
                        //this section is for the third indicator
                        
                        
                        //**************************************************************
            			//*********** Indicator 3 ****************************
            			//**************************************************************
                        
                        echo '<tr>'; //row 6

                            echo '<td>'; //cell 6.1

                                echo 'Ingrese Indicador 3 </br>';
                                //echo '</br>';

                            echo '</td>';
                            
                            echo '<td>'; //cell 6.2
                            
                                echo '<textarea rows='.$rowTextAreaI.' cols='.$sizeTextArea.' name="tbIndicator3" id="tbIndicator3" maxlength="'.$tbmaxsize.'"></textarea><br>';
                                
                            echo '</td>';

                        echo '</tr>';
                        //this section is for the forth indicator
                        
                        //**************************************************************
            			//*********** Indicator 4 ****************************
            			//**************************************************************
                        
                        
                        
                        echo '<tr>'; //row 7

                            echo '<td>'; //cell 7.1

                                echo 'Ingrese Indicador 4 </br>';
                                //echo '</br>';

                            echo '</td>';
                            
                            echo '<td>'; //cell 7.2
                            
                                echo '<textarea rows='.$rowTextAreaI.' cols='.$sizeTextArea.' name="tbIndicator4" id="tbIndicator4" maxlength="'.$tbmaxsize.'"></textarea><br>';
                                
                            echo '</td>';

                        echo '</tr>';
                        
                        
                        //this section is for the fifth indicator
                        
                        //**************************************************************
            			//*********** Indicator 5 ****************************
            			//**************************************************************
                        
                        
                        echo '<tr>'; //row 8

                            echo '<td>'; //cell 8.1

                                echo 'Ingrese Indicador 5 </br>';
                                //echo '</br>';

                            echo '</td>';
                            
                            echo '<td>'; //cell 8.2
                            
                                echo '<textarea rows='.$rowTextAreaI.' cols='.$sizeTextArea.' name="tbIndicator5" id="tbIndicator5" maxlength="'.$tbmaxsize.'"></textarea><br>';
                                
                            echo '</td>';

                        echo '</tr>';
                        
                        
                        //this section is for the sicth indicator
                        
                        
                        //**************************************************************
            			//*********** Indicator 6 ****************************
            			//**************************************************************
                        
                        echo '<tr>'; //row 9

                            echo '<td>'; //cell 9.1

                                echo 'Ingrese Indicador 6 </br>';
                                //echo '</br>';

                            echo '</td>';
                            
                            echo '<td>'; //cell 9.2
                            
                                echo '<textarea rows='.$rowTextAreaI.' cols='.$sizeTextArea.' name="tbIndicator6" id="tbIndicator6" maxlength="'.$tbmaxsize.'"></textarea><br>';
                                
                            echo '</td>';

                        echo '</tr>';
                        
                        //this section is for the seventh indicator
                        
                        //**************************************************************
            			//*********** Indicator 7 ****************************
            			//**************************************************************
                        
                        echo '<tr>'; //row 10

                            echo '<td>'; //cell 10.1

                                echo 'Ingrese Indicador 7 </br>';
                                //echo '</br>';

                            echo '</td>';
                            
                            echo '<td>'; //cell 10.2
                            
                                echo '<textarea rows='.$rowTextAreaI.' cols='.$sizeTextArea.' name="tbIndicator7" id="tbIndicator7" maxlength="'.$tbmaxsize.'"></textarea><br>';
                                
                            echo '</td>';

                        echo '</tr>';
                        
                    echo '</table>'; // end of the table
					echo '</br>';
					
					//**************************************************************
            		//*********** Save Button ****************************
            		//**************************************************************
            		
	            	echo '<table align="center">';
	            		echo "<tr>";
							echo "<td>";
								echo "<button onclick='gobackNewSubject()'>Adicionar logro para nueva materia</button>";
							echo "</td>";
							echo "<td>";
								echo '<button type="button" name="btnInsertarLogro" id="btnInsertarLogro" align="center" onClick= "btnsave_Click()" />Insertar Logro</button>';
							echo "</td>";
						echo "</tr>";
            		echo '</table>';	
					
                
                echo '</div>';
      
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
