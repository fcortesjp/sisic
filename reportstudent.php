<?php
    session_start();  //session  
?>

<!DOCTYPE html>
	
<head>
	<meta http-equiv="Content-Type" content="text/html"> <!-- charset=utf-8 will make accents show on page but will fuckup if accents come from database-->
	
	<script type="text/javascript" charset="utf-8">
		
		function allstudentsreport(form) 
		{
			
			var reportform = document.getElementById("reportstudents");
			
			//sex

			var B = document.getElementById("B");
            var F = document.getElementById("F");
            var M = document.getElementById("M");

            var filtersex = "";
            var sextext = "";

            if (B.checked)
        	{
    			filtersex = B.value;
    			sextext = "Hombres y Mujeres";
        	}
        	else if (F.checked)
    		{
    			filtersex = F.value;
    			sextext = "Mujeres";
    		}
    		else if (M.checked)
    		{
    			filtersex = M.value;
    			sextext = "Hombres";
    		}
			
			//status variables

			var A = document.getElementById("A");
            var R = document.getElementById("R");
            var D = document.getElementById("D");
            var T = document.getElementById("T");
            
            var filterstatus = "";
            var statustext = "";
            if (A.checked)
        	{
    			filterstatus = A.value;
    			statustext = "Activo";
        	}
        	else if (R.checked)
    		{
    			filterstatus = R.value;
    			statustext = "Retirado";
    		}
    		else if (D.checked)
    		{
    			filterstatus = D.value;
    			statustext = "Desescolarizado";
    		}
    		else if (T.checked)
    		{
    			filterstatus = T.value;
                statustext = "Todos";
    		}

            //id types
            
            var ddidtype = document.getElementById("ddidtype");
            var ddidtypeid = ddidtype.options[ddidtype.options.selectedIndex].value;
            var ddidtypetext = ddidtype.options[ddidtype.options.selectedIndex].text;
    		
            //classes
           
            var dbClass = document.getElementById("dbClass");
            var classid = dbClass.options[dbClass.options.selectedIndex].value;
            var classname = dbClass.options[dbClass.options.selectedIndex].text;

            //set variables from html elements into hidden fields which are going to be used
            //in the queries

            document.getElementById("statusselected").value = filterstatus;
            document.getElementById("statustext").value = statustext;
            document.getElementById("classid").value = classid;
            document.getElementById("classname").value = classname;
            document.getElementById("filtersex").value = filtersex;
            document.getElementById("sextext").value = sextext;
            document.getElementById("ddidtypeid").value = ddidtypeid;
            document.getElementById("ddidtypetext").value = ddidtypetext;

            //this value is set once all posted data is good so the query can be executed
        	document.getElementById("all").value = 1;
        	
			
			reportform.action = "reportstudent.php";
			reportform.submit();
			
			
		}
        // this formula ensures that the value is compounded by only numbers
         ///*
        function onlynumbers(str)
        {    
            var val = /^\d+$/gm;
            if(val.test(str))
            {
                return true;
            }
            else
            {
                return false; 
            }   
        }
		
		function reloadtogetclassid(form)
        {
            var cursoid=form.dbCurso.options[form.dbCurso.options.selectedIndex].value;
            self.location='reportstudent.php?dbCurso=' + cursoid ;
        }

        function getstudentidfromdropdown(form)
        {
            
            var studenid=form.dbAlumno.options[form.dbAlumno.options.selectedIndex].value;
            document.getElementById("studentid").value = studenid;
            
            
        }
       
        function SearchStudent(form)
        {
            //this line takes string and removes every single space of any kind on the sides and inside
            //var studentid = document.getElementById("studentid").value.replace(/(^\s+)|\s(?=\s+)|(\s+$)|(\s)/gm, '');
            var reportstudentsform = document.getElementById("reportstudents");
            var studentid = document.getElementById("studentid").value;

            if (studentid < 0 || studentid.length < 6 || onlynumbers(studentid) == false)
            {
                alert("El codigo solo debe contener digitos (min 6) y no puede ser un numero negativo");
                //this will make sure the form won't submit if the alert comes up.
                return false;

            }
            else
            {
                
                document.getElementById("one").value = 1;
                document.getElementById("studentid").value = studentid;
                reportstudentsform.action='reportstudent.php?studentid=' + studentid;
                //reportform.action = "reportstudent.php?";
                reportstudentsform.submit();
            
            }
        }
        //*/
	</script> 
	
</head>
<body>
	<?php
		
		if(isset($_SESSION['currentUser'])) // if the super global variable session called current user has been initialized then
		{
			$currentUser = $_SESSION['currentUser'];
			$userID = $_SESSION['currentID'];
			$role = $_SESSION['role'];
		
            include("config-CRDB.php"); //including config.php in our file to connect to the database

            //variable to get the curso id if selected
            $dbCursoid=$_GET['dbCurso'];

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
				
	<?php   
            if (isset($_SESSION['currentUser']))  
            {
    ?>
				<form name='reportstudents' id='reportstudents' method='post'>
        
                <h3>Reportes de Matriculas</h3>
                <a href="matriculas.php">Regresar</a>
                </br></br>

                <table align="center" border="0">
                       
                    <tr>

                        <td>

            				<table border="1">
            				    <th colspan="3">
                                    Grupos de Estudiantes
                                </th> 
            					<tr>
            						
            						<td> <!-- Todos los estudiantes segun sexo -->
            							sexo:</br>

            							<input type="radio" name="sexo" id="B" value="B" checked>M y F
                                        <br>
                                        <input type="radio" name="sexo" id="M" value="M">Masculino
                                    	</br>
                                    	<input type="radio" name="sexo" id="F" value="F">Femenino
                                    	
                                    </td>
                                    <td> <!-- Todos los estudiantes segun estado -->
                                        Estado:</br>
                                    
                                        <input type="radio" name="status" id="A" value="A" checked>Activo
                                        <br>
                                        <input type="radio" name="status" id="R" value="R">Retirado
                                    	</br>
                                    	<input type="radio" name="status" id="D" value="D">Desescolarizado
                                    	</br>
                                    	<input type="radio" name="status" id="T" value="T">Todos
                                    </td>
                                    <td><!-- Todos los estudiantes segun tipo de documento -->
                                        Tipo de ID:</br>
                                        
                                        <select name='ddidtype' id='ddidtype'>
                                            <option value="T">Todo Tipo de ID</option>
                                            <option value="T.I.">T.I.</option>
                                            <option value="C.C.">C.C.</option>
                                            <option value="R.C.">R.C.</option>
                                            <option value="C.E.">C.E.</option>
                                            <option value="NP">NP</option>
                                            <option value="NUIP">NUIP</option>
                                            <option value="SED">SED</option>
                                            <option value="CCabildo">CCabildo</option>
                                        </select>
                                    </td>

                                </tr>
                                <tr align="center">
                                    <td colspan="3">	
                                        Clase:</br>

                                        <select name='dbClass' id='dbClass'>
            								<option value='0'>Todos los cursos</option>
                                            <?php
                                               
                                                $sql =  "SELECT `Class ID`, Class ".
                                                        "FROM `Class` ". 
                                                        "ORDER BY `Class ID;";
                                                
                                                $recordset = mysql_query($sql) or die("error in Query: ". mysql_error());
                                                
                                                if (!$link) 
                                                {
                                                    die('Could not connect: ' . mysql_error());
                                                    echo "something wrong with the link to the database";
                                                }
                                                else //if connection is good...
                                                {
                                                    while ($row = mysql_fetch_array($recordset)) 
                                                    {
                                                        // then adds that as one of the options in the dropdown
                                                        echo "<option value='" . $row['Class ID'] . "'>" . $row['Class'] . "</option>";
                                                    }
                                                }
                                            ?>
                                    	</select>
            							</br>
                                        </br>
                                        
                                        <!-- hidden field set to determine the type of query launched-->
                                        <input type="hidden" name="all" id="all" value=""></input>

                                        <!-- hidden field with value to filter results from database-->
                                        <input type="hidden" name="statusselected" id="statusselected" value=""></input>
                                        <!-- hidden field with value to show a title of results display-->
                                    	<input type="hidden" name="statustext" id="statustext" value=""></input>
                                    	<!-- hidden field set to determine the the classid to filter query-->
                                    	<input type="hidden" name="classid" id="classid" value=""></input>
                                    	<!-- hidden field set to determine the the class name for title -->
                                    	<input type="hidden" name="classname" id="classname" value=""></input>
            							<!-- hidden field set to determine the the classid to filter query-->
                                    	<input type="hidden" name="filtersex" id="filtersex" value=""></input>
                                    	<!-- hidden field set to determine the the class name for title -->
                                    	<input type="hidden" name="sextext" id="sextext" value=""></input>
                                        <!-- hidden field set to determine the the type of id to filter query-->
                                        <input type="hidden" name="ddidtypeid" id="ddidtypeid" value=""></input>
                                        <!-- hidden field set to determine the id type for the title -->
                                        <input type="hidden" name="ddidtypetext" id="ddidtypetext" value=""></input>
            							
            							<button onClick="return allstudentsreport(this.form)">Mostrar Estudiantes</button>

            						</td>

            					</tr>
            					
            				</table>
                        </td>
                        <td>
                            <table align="center" border="1">
                                <th>
                                    Un Estudiante en Particular
                                </th>
                                <tr>
                                    <td>
                                        Curso:</br> 
                                        <select name='dbCurso' id='dbCurso' onchange="reloadtogetclassid(this.form)">
                                            <option value="0">Seleccione el Curso</option>
                                            <?php
                                            
                                                //this snipet gets the id and class from the database
                                                //to populate the curso drowpdown. when the dropdown changes
                                                //dbcurso will have an id and with the onchange parameter
                                                //the form gets reloaded and the id gets retrieved 
                                                //and stored on the variable dbCursoid which is used for the
                                                //dropdown curso
                                                
                                                $sql =  "SELECT `Class ID`,`Class` ".
                                                        "FROM `Class`";
                                                
                                                $recordset = mysql_query($sql) or die("error in Query: ". mysql_error());
                                                
                                                if (!$link) 
                                                {
                                                    die('Could not connect: ' . mysql_error());
                                                    echo "something wrong with the link to the database";
                                                }
                                                else //if connection is good...
                                                {
                                                    while ($row = mysql_fetch_array($recordset)) 
                                                    {
                                                        //show what was selected after the form is submitted      
                                                            if ($row['Class ID'] ==  $dbCursoid)
                                                            {// then adds that as one of the options in the dropdown
                                                                echo "<option value='" . $row['Class ID'] . "' selected>" . $row['Class'] . "</option>";
                                                            }
                                                        // then adds that as one of the options in the dropdown
                                                            else
                                                            {
                                                                echo "<option value='" . $row['Class ID'] . "'>" . $row['Class'] . "</option>";
                                                            }
                                                    }
                                                }
                                            ?>
                                        </select>
                                        </br>
                                        Alumnos:</br>
                                        *Debe selecionar el curso antes</br> 
                                        <select name='dbAlumno' id='dbAlumno' onchange="getstudentidfromdropdown(this.form)">
                                            <option value="0">Seleccione el Alumno</option>
                                            <?php
                                                
                                                if(isset($dbCursoid) and strlen($dbCursoid) > 0)
                                                {
                                                    $sql =  "SELECT `Student ID`,CONCAT(`Student First`,' ',`Student Last`) AS name,`Class ID`, status ".
                                                            "FROM `Student`" .
                                                            "WHERE `Class ID` = $dbCursoid ".
                                                            "ORDER BY status, `Student Last`;";
                                                    
                                                }
                                                
                                                
                                                $recordset = mysql_query($sql) or die("error in Query: ". mysql_error());
                                                
                                                if (!$link) 
                                                {
                                                    die('Could not connect: ' . mysql_error());
                                                    echo "something wrong with the link to the database";
                                                }
                                                else //if connection is good...
                                                {
                                                    while ($row = mysql_fetch_array($recordset)) 
                                                    {
                                                        // then adds that as one of the options in the dropdown
                                                        echo "<option value='" . $row['Student ID'] . "'>" . $row['name'] . " (". $row['status'] .  ")</option>";
                                                    }
                                                }
                                            ?>
                                        </select>
                                        </br>
                                        </br>
                                        
                                        Codigo:</br>
                                        <input type="number" name="studentid" id="studentid"/></br>
                                        <!-- hidden field set to determine the type of query -->
                                        <input type="hidden" name="one" id="one" value=""></input>
                                        <button onClick="return SearchStudent(this.form)">Buscar Estudiante</button>
                                    </td>        
                                </tr>
                                
                            </table>
                        </td>

                    </tr>
                </table>
			<?php
				
				$out = ""; //variable initialized to create an html table with values
                $title = ""; //variable that will have the title to show 
                //resulting from the query executed below

				//$all = $_POST['all']; //this variable is set if form data has been validated
                //$one = $_POST['one'];
                //$studentid=$_GET['studentid'];
                //if form is posted the all variable should be set

                //build first part of the query string as per posted filtered values
                $sqlselectResult = "";
                    
                $sqlselectResult .= "SELECT `Student ID` AS Codigo, `Student First` AS Nombres, ".
                                    "`Student Last` AS Apellidos, Class.Class AS Curso, ".
                                    "Gender AS Sexo, IDType AS TipoID, Identification AS ID, ".
                                    "City AS Ciudad, date_i AS FechaIng, date_r AS FechaRoD, ".
                                    "status AS Estado ". 
                                    "FROM `Student` ". 
                                    "LEFT JOIN `Class` ".
                                    "ON Student.`Class ID` = Class.`Class ID` ";

				if(isset($_GET['studentid'])) // if the query is for only one student
                {
                    $studentid = $_GET['studentid'];

                    //echo "one=".$one;

                    $sqlselectResult .= "WHERE `Student ID` = '$studentid';";

                    $title .= "Estudiante con Codigo: ".$studentid;

                }
                elseif(isset($_POST['all'])) // if the query is for all students..
				{
                    //echo "all =".$all;
                    //grab posted values from hidden fields to filter the student query

                    $statusselected = $_POST['statusselected']; 
                    $statustext = $_POST['statustext'];
                    $classid = $_POST['classid'];
                    $classname = $_POST['classname'];
                    $filtersex = $_POST['filtersex'];
                    $sextext = $_POST['sextext'];
                    $ddidtypeid = $_POST['ddidtypeid'];
                    $ddidtypetext = $_POST['ddidtypetext'];

                    //keep building the query string as per the type of query and values to filter query by

                    if($classid == 0 && $statusselected == 'T' && $filtersex == 'B' && $ddidtypeid == 'T')
                    {
                        //don't filter anything
                    }
                    elseif($classid == 0 && $statusselected == 'T' && $filtersex == 'B' && $ddidtypeid != 'T')
                    {
                        $sqlselectResult .= "WHERE IDType = '$ddidtypeid' ";
                    }
                    elseif($classid == 0 && $statusselected == 'T' && $filtersex != 'B' && $ddidtypeid == 'T')
                    {
                        $sqlselectResult .= "WHERE Gender = '$filtersex' "; 
                    }
                    elseif($classid == 0 && $statusselected == 'T' && $filtersex != 'B' && $ddidtypeid != 'T')
                    {
                        $sqlselectResult .= "WHERE Gender = '$filtersex' ";
                        $sqlselectResult .= "AND IDType = '$ddidtypeid' ";  
                    }       
                    elseif($classid != 0 && $statusselected != 'T' && $filtersex == 'B' && $ddidtypeid == 'T')
                    {
                        $sqlselectResult .= "WHERE status = '$statusselected' ";
                        $sqlselectResult .= "AND Class.Class = '$classname' ";
                            
                    }
                    elseif($classid != 0 && $statusselected != 'T' && $filtersex == 'B' && $ddidtypeid != 'T')
                    {
                        $sqlselectResult .= "WHERE status = '$statusselected' ";
                        $sqlselectResult .= "AND Class.Class = '$classname' ";
                        $sqlselectResult .= "AND IDType = '$ddidtypeid' ";
                    }
                    elseif($classid != 0 && $statusselected != 'T' && $filtersex != 'B' && $ddidtypeid == 'T')
                    {
                        $sqlselectResult .= "WHERE status = '$statusselected' ";
                        $sqlselectResult .= "AND Class.Class = '$classname' ";
                        $sqlselectResult .= "AND Gender = '$filtersex' ";
                        
                    }
                    elseif($classid != 0 && $statusselected != 'T' && $filtersex != 'B' && $ddidtypeid != 'T')
                    {
                        $sqlselectResult .= "WHERE status = '$statusselected' ";
                        $sqlselectResult .= "AND Class.Class = '$classname' ";
                        $sqlselectResult .= "AND Gender = '$filtersex' ";
                        $sqlselectResult .= "AND IDType = '$ddidtypeid' ";     
                    }
                    elseif($classid != 0 && $statusselected == 'T' && $filtersex == 'B' && $ddidtypeid == 'T')
                    {
                        $sqlselectResult .= "WHERE Class.Class = '$classname' ";
                        
                    }
                    elseif($classid != 0 && $statusselected == 'T' && $filtersex == 'B' && $ddidtypeid != 'T')
                    {
                        $sqlselectResult .= "WHERE Class.Class = '$classname' ";
                        $sqlselectResult .= "AND IDType = '$ddidtypeid' "; 
                    }
                    elseif($classid != 0 && $statusselected == 'T' && $filtersex != 'B' && $ddidtypeid == 'T')
                    {
                        $sqlselectResult .= "WHERE Class.Class = '$classname' ";
                        $sqlselectResult .= "AND Gender = '$filtersex' ";
                    }
                    elseif($classid != 0 && $statusselected == 'T' && $filtersex != 'B' && $ddidtypeid != 'T')
                    {
                        $sqlselectResult .= "WHERE Class.Class = '$classname' ";
                        $sqlselectResult .= "AND Gender = '$filtersex' ";
                        $sqlselectResult .= "AND IDType = '$ddidtypeid' ";
                    }
                    elseif($classid == 0 && $statusselected != 'T' && $filtersex == 'B' && $ddidtypeid == 'T')
                    {
                        $sqlselectResult .= "WHERE status = '$statusselected' ";
                    }
                    elseif($classid == 0 && $statusselected != 'T' && $filtersex == 'B' && $ddidtypeid != 'T')
                    {
                        $sqlselectResult .= "WHERE status = '$statusselected' ";
                        $sqlselectResult .= "AND IDType = '$ddidtypeid' ";
                    }
                    elseif($classid == 0 && $statusselected != 'T' && $filtersex != 'B' && $ddidtypeid == 'T')
                    {
                        $sqlselectResult .= "WHERE status = '$statusselected' ";
                        $sqlselectResult .= "AND Gender = '$filtersex' ";
                    }
                    elseif($classid == 0 && $statusselected != 'T' && $filtersex != 'B' && $ddidtypeid != 'T')
                    {
                        $sqlselectResult .= "WHERE status = '$statusselected' ";
                        $sqlselectResult .= "AND Gender = '$filtersex' ";
                        $sqlselectResult .= "AND IDType = '$ddidtypeid' ";
                    }

                    $sqlselectResult .=  "ORDER BY Curso, Apellidos;";

                    $title .= "Estudiante(s) con Estado: ";
                    
                    $title .= $statustext.", Sexo: ".$sextext.", Tipo de ID: ".$ddidtypetext.", Curso: ".$classname;    
                    //now show the results of the query

                }

                if(isset($_POST['all']) || isset($_POST['studentid']))
                {

                    $qryRSselectResult = mysql_query($sqlselectResult); //run query and get recordset

                    $numrows = mysql_num_rows($qryRSselectResult); //get the number of rows in recordset
                    //generate table using the data from the recordset
                    $out = '<table border="1">'; // set the start of the html output which is a table
                    
                    for($i = 0; $i < mysql_num_fields($qryRSselectResult); $i++) //do this as many fields exist on the recordset
                    {
                        $aux = mysql_field_name($qryRSselectResult, $i); // get the title of the fields in the record set
                        $out .= "<th>".$aux."</th>"; //put the title in between row headers
                    }
                    while ($line = mysql_fetch_assoc($qryRSselectResult)) //put each reacord fetched into an associative array variable.
                    {
                        $out .= "<tr>"; //add the start of the row for the record on the table
                            $out .= '<td>'.$line["Codigo"].'</td>';
                            $out .= '<td>'.$line["Nombres"].'</td>';
                            $out .= '<td>'.$line["Apellidos"].'</td>';
                            $out .= '<td>'.$line["Curso"].'</td>';
                            $out .= '<td>'.$line["Sexo"].'</td>';
                            $out .= '<td>'.$line["TipoID"].'</td>';
                            $out .= '<td>'.$line["ID"].'</td>';
                            $out .= '<td>'.$line["Ciudad"].'</td>';
                            $out .= '<td>'.$line["FechaIng"].'</td>';
                            $out .= '<td>'.$line["FechaRoD"].'</td>';
                            $out .= '<td>'.$line["Estado"].'</td>';
                        $out .= "</tr>"; //add the close of the row
                    }
                    $out .= "</table>"; 
                    
                ?>

                    <div align="center">
                        
                        <h3>
                            <?php 
                                //this shows the title of the search
                                echo $title; 
                            ?>
                        </h3>

                        </br>
                        Total de records arrojados por la busqueda: <?php echo $numrows; ?>
                        </br>
                        </br> 
                        <?php 
                            
                            //this shows the table created above with the filtered data
                            echo $out;
                        ?>
                        </br>
                        </br>
                        Total de records arrojados por la busqueda: <?php echo $numrows; ?>
                        </br>
                        
                    </div>

                    </br>
                    <a href="matriculas.php">Regresar</a>
                    
		  <?php   
                }    
			}
        ?>
		</form>
	</div>
</body>
