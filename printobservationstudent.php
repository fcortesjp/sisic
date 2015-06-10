<?php
    session_start();  //session  
?>

<!DOCTYPE html>
    
<head>
    <meta http-equiv="Content-Type" content="text/html"> <!-- charset=utf-8 will make accents show on page but will fuckup if accents come from database-->
    
    <script type="text/javascript" charset="utf-8">
        
        function reloadtogetclassid(form)
        {
            var cursoid=form.dbCurso.options[form.dbCurso.options.selectedIndex].value;
            self.location='printobservationstudent.php?dbCurso=' + cursoid ;
        }
        
        function getstudentidfromdropdown(form)
        {
            
            var studenid=form.dbAlumno.options[form.dbAlumno.options.selectedIndex].value;
            document.getElementById("studentid").value = studenid;
            //self.location='insertobservation.php?dbAlumno=' + val ;
        }
        
        function SearchStudent()
        {
            var insertObserForm = document.getElementById ("updateobservador");
            insertObserForm.action = "printobservationstudent.php";
            insertObserForm.submit();
        }
        
    </script>
    
</head>
<style>
    p.page { page-break-after: always; }
</style>
<body>
    
    <?php
    
        if(isset($_SESSION['currentUser'])) // if the super global variable session called current user has been initialized then
        {
            $currentUser = $_SESSION['currentUser'];
            $userID = $_SESSION['currentID'];
            $role = $_SESSION['role'];
            
            //variable to get the curso id if selected
            $dbCursoid=$_GET['dbCurso'];
            
            //menu to display variable
            
            $menu ="";
            
            include("config-CRDB.php"); //including config.php in our file to connect to the database
            
            //get the subjects user/teacher is in charge of or if role is d (director) get all the classes
            
            
            echo "Bienvenido(a) ".$currentUser;
            echo "</br>";
            echo "<a href='logout.php'>Logout</a>";
    
    ?>
    <div align="center">
        <form name='updateobservador' id='updateobservador' method='post'>
        
            <h3>Imprimir Observador del alumno</h3>
                <a href="observador.php">regresar</a>
                </br>
                </br>
                <table align="center" border="1">
                    
                    <tr>
                        
                        <td>
                            Buscar Alumno
                        </td>
                        
                        <td>
                            Curso:
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
                                            // then adds that as one of the options in the dropdown
                                            echo "<option value='" . $row['Class ID'] . "'>" . $row['Class'] . "</option>";
                                        }
                                    }
                                ?>
                            </select>
                            
                            Alumnos:
                            <select name='dbAlumno' id='dbAlumno' onchange="getstudentidfromdropdown(this.form)">
                                <option value="0">Seleccione el Alumno</option>
                                <?php
                                    
                                    if(isset($dbCursoid) and strlen($dbCursoid) > 0)
                                    {
                                        $sql =  "SELECT `Student ID`,CONCAT(`Student First`,' ',`Student Last`) AS name,`Class ID` ".
                                                "FROM `Student`" .
                                                "WHERE `Class ID` = $dbCursoid";
                                        //$quer="SELECT DISTINCT subcategory FROM subcategory where cat_id=$cat order by subcategory"; 
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
                                            echo "<option value='" . $row['Student ID'] . "'>" . $row['name'] . "</option>";
                                        }
                                    }
                                ?>
                            </select>
                            </br>
                            </br>
                            
                            Codigo:</br>
                            <input type="text" name="studentid" id="studentid"></br>
                            <button onClick="SearchStudent()">Buscar Estudiante</button>
                        </td>
                    <tr>   
                </table>
                </br>
                <p class="page"></p> 
                </br>
                <table border="0">
                    <tr>
                        <td align="left" width="300">
                            <img src="./images/logo.png" />
                        </td>
                        
                        <td align="center" width="400">
                            <h2>INSTITUTO COPESAL</h2>
                            <p>
                                SABIDURIA, AMOR Y LIBERTAD</br>
                                FORMACION DE VALORES EN LA COMUNIDAD</br>
                                APROBACION RESOLUCION No. 3669 de 2007</br>
                                PREESCOLAR-BASICA PRIMARIA-BASICA SECUNDARIA Y MEDIA
                            </p>
                        </td>
                        
                        <td align="right" width="300">
                            <img src="./images/escudo.png" />
                        </td>
                    </tr>
                </table>
                </br>
                <table align="center" border="1">
                           
                            <?php
                                $st_id = "";
                                $out ="";
                                if(trim($_POST['studentid']) !== "")
                                {
                                    include("config-CRDB.php");
                                    
                                    if (!$link) 
                                    {
                                        die('Could not connect: ' . mysql_error());
                                        echo "something wrong with the link to the database";
                                    }
                                    else //if connection is good...
                                    {
                                        $table = 'Student';
                                        
                                        $st_id = $_POST['studentid'];
                                        
                                        $sqlselect =  "SELECT `Class ID`, CONCAT(`Student First`,' ',`Student Last`) AS name FROM `$table` WHERE `Student ID` = '$st_id';"; 
                                        
                                        $qryrecordset = mysql_query($sqlselect);
                                        
                                        $row = mysql_fetch_array($qryrecordset); 
                                        
                                        $nombre = $row['name'];
                                        $class_id = $row['Class ID'];
                                    
                            ?>
                    <tr>
                        <td align="center">
                            Informacion Alumno
                        </td>
                    </tr>  
                    <tr>  
                        <td>
                                        Codigo:<input type="text" name="st_cod" id="st_cod" size="10" value="<?php echo $st_id; ?>" readonly></br>
                                        Nombre:<input type="text" name="st_name" id="st_name" size="40" value="<?php echo $nombre; ?>" readonly></br>
                                        Clase:<input type="text" name="classid" id="classid" size="5" value="<?php echo $class_id; ?>" readonly></br>
                            
                        </td>
                    </tr>            
                            <?php    
                                                
                                        $table = 'Observations';
                                    
                                        $sqlselectResult =  "SELECT Class.Class, date_open, category, importance, observation, objetive, compromise, CONCAT(Class.Class,'-',Subject) as subject, date_close ". 
                                                            "FROM `Observations` ". 
                                                            "LEFT JOIN `Teacher` ".
                                                            "ON Observations.user_id = Teacher.ID ".
                                                            "LEFT JOIN `Class` ".
                                                            "ON Observations.class_id = Class.`Class ID` ".
                                                            "LEFT JOIN `Class Copesal` ".
                                                            "ON Observations.subject_id = `Class Copesal`.`ID` ".
                                                            "WHERE `student_id` = '$st_id';"; 
                                    
                                        $qryRSselectResult = mysql_query($sqlselectResult); //run query and get recordset
                                        
                                        //generate table using the data from the recordset
                                        $out = '<table border="1">'; // set the start of the html output which is a table
                                        for($i = 0; $i < mysql_num_fields($qryRSselectResult); $i++) //do this as many fields exist on the recordset
                                        {
                                            $aux = mysql_field_name($qryRSselectResult, $i); // get the title of the fields in the record set
                                            $out .= "<th>".$aux."</th>"; //put the title in between row headers
                                        }
                                        while ($line = mysql_fetch_assoc($qryRSselectResult)) //put each reacord fetched into an associative array variable.
                                        {
                                               
                                            $catgry ="";
                                            switch ($line["category"]) 
                                            {
                                                case 1:
                                                    $catgry = "Convivencia";
                                                    break;
                                                case 2:
                                                     $catgry = "Academico";
                                                    break;
                                                case 3:
                                                     $catgry = "Psicologico";
                                                    break;
                                                case 4:
                                                     $catgry = "Salud";
                                                    break;
                                                case 0:
                                                     $catgry = "Otro";
                                                    break;
                                            }
                                            
                                            $imptce ="";
                                            switch ($line["importance"]) 
                                            {
                                                case 1:
                                                    $imptce = "Bajo";
                                                    break;
                                                case 2:
                                                     $imptce = "Medio";
                                                    break;
                                                case 3:
                                                     $imptce = "Alto";
                                                    break;
                                            }
                                                
                                            $out .= "<tr>"; //add the start of the row for the record on the table
                                                $out .= '<td>'.$line["Class"].'</td>';
                                                $out .= '<td>'.$line["date_open"].'</td>';
                                                $out .= '<td>'.$catgry.'</td>';
                                                $out .= '<td>'.$imptce.'</td>';
                                                $out .= '<td>'.$line["observation"].'</td>';
                                                $out .= '<td>'.$line["objetive"].'</td>';
                                                $out .= '<td>'.$line["compromise"].'</td>';
                                                $out .= '<td>'.$line["subject"].'</td>';
                                                $status = ''.($line["date_close"] == "0000-00-00" ?'Abierto':'Cerrado');
                                                $out .= '<td>'.$status.'</td>';
                                            $out .= "</tr>"; //add the close of the row
                                        }
                                        $out .= "</table>"; // add the closing of the table
                                 ?>       
                    <tr>
                        <td align="center">
                            Observaciones existentes
                        </td>
                    </tr>
                    <tr>
                        <td>
                                <?php echo $out; ?>
                                
                        </td>
                    </tr>
                           <?php         
                                    }
                                }
                            ?>
                    
                </table>
                </br>
                </br>
                </br>
                <table border="0">
                    <tr align="center">
                        <td align="center" width="200">
                            -------------------------------<br>
                            Firma de Padre
                        </td>
                        <td align="center" width="200">
                            -------------------------------<br>
                            Firma de Alumno
                        </td>
                        <td align="center" width="200">
                            -------------------------------<br>
                            Firma de Director
                        </td>
                    </tr>
                    
                </table>
                </br>
                
                <a href="observador.php">regresar</a>
                
        </form>

    </div>
    
    <?php

        }
            
        else
        {
        
            echo "You need to be authenticated to see the content of this page";
            echo "</br>";
            echo "<a href='logout.php'>Log In</a>";
            
        }
    ?>
    
</body>