<?php
	session_start();  //session
?>	
<!DOCTYPE html>
	<head>		
		<script type="text/javascript" charset="utf-8">
		
			function goback()
			{
				var reportForm = document.getElementById ("report");
				reportForm.action = "admin.php";	
				reportForm.submit();
				 	
			}
			function GenReport()
			{
				var reportForm = document.getElementById ("report");
				
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
	        	else
	        	{
	        		reportForm.submit();
	        	}
				
				 	
			}
		</script>
		<title>Reporte</title>	
	</head>
	<body>
		<!-- 
		<comment>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>></comment>
		<comment>Inicio de la forma</comment>
		<comment>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>></comment>
		-->
		<form name='report' id='report' method='post'>
			Bienvenido(a) <?php echo $_SESSION['currentUser'] ?>
			</br>
			<a href='logout.php'>Logout</a>
			<input type='hidden' id = 'classid' name='classid' value='<?php echo $_POST['classid']?>'/>
			<input type='hidden' id = 'classsub' name='classsub' value='<?php echo $_POST['classsub']?>'/>
			<div id="header" align="center"><h2 class="sansserif">Reporte de logros e Indicadores Ingresados</h2></div>
			<!--
			<comment>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>></comment>
			<comment>Inicio de la tabla</comment>
			<comment>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>></comment>
			<comment>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>></comment>
			-->
			 <table align="center">

            		
                        <tr>

                            <td>
                                
                                Seleccione el Periodo
                                </br>

                            </td>
                            

                     	</tr> 
					                        
                        
                       	<tr>

                            
                            
                            <td>
                            
                            
									<input type="radio" name="rdPeriod" id="per1" value="1">1 
	                                <input type="radio" name="rdPeriod" id="per2" value="2">2
									<input type="radio" name="rdPeriod" id="per3" value="3">3
									<input type="radio" name="rdPeriod" id="per4" value="4">4
								
							
                                
                            
                            </td>

                        </tr>
						
                        
	            		<tr>

                            <td>

                                <button id="btnGenReport" name="btnGenReport" onClick="GenReport()">Generar Reporte</button>
                                <button onclick='goback()'>Regresar</button>

                            </td>
                            
                        
                        </tr>
	            	
	            	</table>
	            	
	            	<table align="center">

            		
                        <tr>

                            <td>
                                
                               <?php 
                                
                                if(!empty($_POST['rdPeriod']))
                                {
                                	$per = $_POST['rdPeriod']; // get the period indicated by user
									
									echo '<th><h1>Periodo '.$per.'</h1></th>';
                                    
                                    include("functions.php"); // get the include file to execute the function
									
									//show a table with the total of goal and indicators so far entered for the period
									
									$rs_totalGoalandIndicators = searchTotalGandIentered($per);
									echo getHtmlTableTotalGandIentered($rs_totalGoalandIndicators);
									
									//show table with all the goals and indicators so far entered for the period
									
									$rs_goalIndicatorPerPeriod = SearchGoalandIndicatorsPerPeriod($per); //get the record set of goal ids and ids
									echo getHtmlTable4GandIEntered($rs_goalIndicatorPerPeriod); //populate a table out of the record set.
                               	}
                            	
                            	?>
                            </td>
                            

                     	</tr>
                     </table> 
		
		</form>
	</body>	
</html>