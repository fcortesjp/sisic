<?php
    session_start();  //session  
?>

<!DOCTYPE html>
    
<head>
    <meta http-equiv="Content-Type" content="text/html"> <!-- charset=utf-8 will make accents show on page but will fuckup if accents come from database-->
    
    <script type="text/javascript" charset="utf-8">
    
        function SearchStudent()
        {
            var insertObserForm = document.getElementById ("observador");
            insertObserForm.action = "insertobservation.php";
            insertObserForm.submit();
        }
        
        function saveObservation()
        {
            //values of all html components
            var insertObserForm = document.getElementById ("observador");
            
            var id = document.getElementById("st_cod").value;
            
            var date = document.getElementById("date_open").value;
            
            var name = document.getElementById("st_name").value;
            
            var catdd = document.getElementById("cat");
            var catid = catdd.options[catdd.selectedIndex].value;
            var cattext = catdd.options[catdd.selectedIndex].text;
            
            var importancedd = document.getElementById("importance");
            var importanceid = importancedd.options[importancedd.selectedIndex].value;
            var importancetext = importancedd.options[importancedd.selectedIndex].text;
            
            var observ = document.getElementById("observation").value.replace(/^\s+|\s+$/g, '');
            
            var objective = document.getElementById("objective").value.replace(/^\s+|\s+$/g, ''); 
            
            var compromise = document.getElementById("compromise").value.replace(/^\s+|\s+$/g, ''); 
            
            var subjectdd = document.getElementById("dbClassSubject");
            var subjectid = subjectdd.options[subjectdd.selectedIndex].value;
            var subjecttext = subjectdd.options[subjectdd.selectedIndex].text;
            
            //document.getElementById("classsubject").value = subjecttext
            
            //check each required component
            
            if (id.length == 0)
            {
                alert("el codigo no puede estar vacio");
                return; // return to exit out of the function.
            }
            
            if (date.length == 0)
            {
                alert("la fecha de apertura no puede estar vacia");
                return; // return to exit out of the function.
            }
            
            if (catid == 0)
            {
                alert("Escoja una categoria");
                return; // return to exit out of the function.
            }
            
            if (importanceid == 0)
            {
                alert("Escoja un nivel de importancia");
                return; // return to exit out of the function.
            }
            
            if (observ.length == 0)
            {
                alert("la observacion no puede estar vacia");
                return; // return to exit out of the function.
            }
            
            var message = "";
            
            message += "Codigo: " + id + "\n";
            message += "Alumno: " + name + "\n";
            message += "Categoria: " + cattext + "\n";
            message += "Importancia: " + importancetext + "\n";
            message += "Observacion: " + observ + "\n";
            message += "Objetivos: " + objective + "\n";
            message += "Compromiso: " + compromise + "\n";
            message += "Materia: " + subjecttext + "\n";
            
            var response=confirm("Desea guardar esta observacion: \n" + message);
            if (response == true)
            {
                
                insertObserForm.action = "saveobservation.php";
                insertObserForm.submit();
            }
        }
    
    </script>
    
</head>
<body>
    
    <?php
    
        if(isset($_SESSION['currentUser'])) // if the super global variable session called current user has been initialized then
        {
            $currentUser = $_SESSION['currentUser'];
            $userID = $_SESSION['currentID'];
            $role = $_SESSION['role'];
            
            //menu to display variable
            
            $menu ="";
            
            include("config-CRDB.php"); //including config.php in our file to connect to the database
            
            //get the subjects user/teacher is in charge of or if role is d (director) get all the classes
            
            
            echo "Bienvenido(a) ".$currentUser;
            echo "</br>";
            echo "<a href='logout.php'>Logout</a>";
    
    ?>
    <div align="center">
        <form name='observador' id='observador' method='post'>
        
            <h3>Observaciones</h3>
                
                <table align="center" border="1">
                    
                    <tr>
                        
                        <td>
                            Alumno
                        </td>
                        
                        <td>
                            Codigo:</br>
                            <input type="text" name="studentid" id="studentid"></br>
                            <button onClick="SearchStudent()">Buscar Estudiante</button>
                            </br>
                            </br>
                            <?php
                                $st_id = "";
                                $nombre = "";
                                $class_id ="";
                                
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
                                     
                                    }
                                }
                            ?>
                            Codigo:<input type="text" name="st_cod" id="st_cod" size="10" value="<?php echo $st_id; ?>" readonly></br>
                            Nombre:<input type="text" name="st_name" id="st_name" size="40" value="<?php echo $nombre; ?>" readonly></br>
                            Clase:<input type="text" name="classid" id="classid" size="5" value="<?php echo $class_id; ?>" readonly></br>
                            
                        </td>
                    </tr>
                    
                    <tr>
                        <td>
                            Fecha de Apertura
                        </td>
                        <td>
                            <input type="date" name="date_open" id="date_open"/></br>
                        </td>    
                    </tr>
                    
                        <?php
                    
                            //$role = $_SESSION['role'];
                            
                            if ($role == 'd') 
                            {
                    
                        ?>
                                <tr>
                                    <td>
                                        Fecha de Cierre
                                    </td>
                                    <td>
                                        <input type="date" name="date_close" id="date_close"/></br>
                                    </td>    
                                </tr>
                        <?php
                        
                            }
                        
                        ?>
                    
                    <tr>
                        <td>
                            Categoria
                        </td>
                        <td>
                            <select name="cat" id="cat"/></br>
                                <option value="0">--Escoja--</option>
                                <option value="1">Convivencia</option>
                                <option value="2">Academico</option>
                                <option value="3">Psicologico</option>
                                <option value="4">Salud</option>
                                <option value="5">Otro</option>
                            </select>
                        </td>    
                    </tr>
                    
                    <tr>
                        <td>
                            Importancia
                        </td>
                        <td>
                            <select name="importance" id="importance"/></br>
                                <option value="0">--Escoja--</option>
                                <option value="1">Bajo</option>
                                <option value="2">Medio</option>
                                <option value="3">Alto</option>
                            </select>
                        </td>    
                    </tr>
                    
                    <tr>
                        <td>
                            Observacion
                        </td>
                        <td>
                            <textarea name="observation" id="observation" rows="5" cols="50" maxlength="500"></textarea>
                        </td>
                    </tr>
                    
                    <tr>
                        <td>
                            Objetivo de la Institucion
                        </td>
                        <td>
                            <textarea name="objective" id="objective" rows="5" cols="50" maxlength="500"></textarea>
                        </td>
                    </tr>
                    
                    <tr>
                        <td>
                            Compromisos del Alumno
                        </td>
                        <td>
                            <textarea name="compromise" id="compromise" rows="5" cols="50" maxlength="500"></textarea>
                        </td>
                    </tr>
                    
                    <tr>
                        <td>
                            Materia (si aplicable)
                        </td>
                        <td>
                            <select name='dbClassSubject' id='dbClassSubject'>
                                <option value="0">n/a</option>
                                <?php
                                
                                    //include("config-CRDB.php");
                                    
                                    if ($role == 'd') 
                                    {
                                        $sql =  "SELECT ID, CONCAT(Class.Class, '-', `Class Copesal`.Subject) AS ClassSubject ".
                                                "FROM `Class Copesal` ". 
                                                "INNER JOIN Class ".
                                                "ON `Class Copesal`.ClassID = Class.`Class ID` ".
                                                //"WHERE TeacherID = '$userID' ".
                                                "ORDER BY Class.Order;";
                                    } 
                                    else 
                                    {
                                        $sql =  "SELECT ID, CONCAT(Class.Class, '-', `Class Copesal`.Subject) AS ClassSubject ".
                                                "FROM `Class Copesal` ". 
                                                "INNER JOIN Class ".
                                                "ON `Class Copesal`.ClassID = Class.`Class ID` ".
                                                "WHERE TeacherID = '$userID' ".
                                                "ORDER BY Class.Order;";
                                        
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
                                            echo "<option value='" . $row['ID'] . "'>" . $row['ClassSubject'] . "</option>";
                                        }
                                    }
                                ?>
                            </select>
                            
                        </td>
                    </tr>
                                      
                </table>
                
                </br>
                
                <button onClick="saveObservation()">Guardar Observacion</button>
                
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