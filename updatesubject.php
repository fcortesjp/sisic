<?php
    session_start();  //session  
?>

<!DOCTYPE html>
    
<head>
    <meta http-equiv="Content-Type" content="text/html"> <!-- charset=utf-8 will make accents show on page but will fuckup if accents come from database-->
    
    <script type="text/javascript" charset="utf-8">
    
        //this function cleans input for free text
        
        function DoTrimRemoveReturns(text)
        {
            text = text.replace(/[\s\t\n\r]/g,' '); //replace white, tabs, carriage returns for a space in any part of the string
            text = text.replace(/^\s\s*/, '').replace(/\s\s*$/, ''); // remove spaces at each end of the string
            text = text.replace(/\s+/g," "); //this replaces multiple spaces for only one space.
            return text; //return string fixed
        }
        
        function totitlecase(str)
        {
            
            return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
            
        }
        
        //this function checks if the input has a number or acentuated characters, if it does returns true.
        function goodSubject(str)
        {    
            var val = /^[A-Za-záéíóúñ.\- ]+$/;
            if(val.test(str))
            {
                return true;
            }
            else
            {
                return false; 
            }   
        }
        
        function saveSubject()
        {
            
            //values of all html components
            var enrollsubjectform = document.getElementById ("updatesubjectform");
            
            var dbArea  = document.getElementById("dbArea");
            var dbAreavalue = dbArea.options[dbArea.selectedIndex].value;
            var dbAreatext = dbArea.options[dbArea.selectedIndex].text;
            
            var dbCurso  = document.getElementById("dbCurso");
            var dbCursovalue = dbCurso.options[dbCurso.selectedIndex].value;
            var dbCursotext = dbCurso.options[dbCurso.selectedIndex].text;
            
            var tbSubject = document.getElementById("tbSubject").value;
            tbSubject = DoTrimRemoveReturns(tbSubject);
            tbSubject = totitlecase(tbSubject);
            
            var tbInthor  = document.getElementById("tbInthor");
            var tbInthorvalue = tbInthor.options[tbInthor.selectedIndex].value;
            
            var dbTeacher  = document.getElementById("dbTeacher");
            var dbTeachervalue = dbTeacher.options[dbTeacher.selectedIndex].value;
            var dbTeachertext = dbTeacher.options[dbTeacher.selectedIndex].text;
            
            var dbSubstatus  = document.getElementById("dbSubstatus");
            var dbSubstatusvalue = dbSubstatus.options[dbSubstatus.selectedIndex].value;
            var dbSubstatustext = dbSubstatus.options[dbSubstatus.selectedIndex].text;
            //check each required component
            
            if (dbAreavalue == 0)
            {
                alert("Selecione el area correspondiente asociada a la materia");
                return false; // return to exit out of the function.
            }
            
            if (dbCursovalue == 0)
            {
                alert("Selecione el curso correspondiente asociado a la materia");
                return false; // return to exit out of the function.
            }
            
            if (tbSubject.length == 0 || goodSubject(tbSubject) == false)
            {
                alert("La materia no puede usar caracteres no validos o estar vacia");
                return false; // return to exit out of the function.
            }
            
            if (tbInthorvalue == 0)
            {
                alert("Selecione una opcion valida de intensidad horaria");
                return false; // return to exit out of the function.
            }
            
            if (dbSubstatusvalue == 0)
            {
                alert("Selecione una opcion valida para el estado de la materia");
                return false; // return to exit out of the function.
            }
            
            var message = "";
            
            message += "Area: " + dbAreatext + "\n";
            message += "Curso: " + dbCursotext + "\n";
            message += "Materia: " + tbSubject + "\n";
            message += "Int.Hor: " + tbInthorvalue + "\n";
            message += "Profesor: " + dbTeachertext + "\n";
            message += "Estado: " + dbSubstatustext + "\n";
            
            
            var response=confirm("Desea guardar los cambios de la materia con la informacion ingresada?: \n\n" + message);
            
            if (response == true)
            {   
                
                document.getElementById("tbSubject").value = tbSubject;
                enrollsubjectform.action = "savesubject.php";
                enrollsubjectform.submit();
            }
            else
            {
                return false;
            }
        }
    
    </script>
    
</head>
<body>
    
    <?php
    
        $currentUser = $_SESSION['currentUser'];
        $userID = $_SESSION['currentID'];
        $role = $_SESSION['role'];
        
        if(isset($_SESSION['currentUser']) && $role = 'd' && $userID == 24)  // if the super global variable session called current user has been initialized then
        {
            
            include("config_sisic.php"); //including config.php in our file to connect to the database
            
            //get the subjects user/teacher is in charge of or if role is d (director) get all the classes
            
            
            echo "Bienvenido(a) ".$currentUser;
            echo "</br>";
            echo "<a href='logout.php'>Logout</a>";
    
                            
            $subject_id  = $_GET['id'];
            $subject = "";
            $scheduleintensity = "";
            $areaid = "";
            $classid = "";
            $teacherid = "";
            $substatus = "";
            
            try
            {
                $sqlSelect =    "SELECT ID, Subject, `Schedule Intensity`, AreaID, ClassID, TeacherID, substatus ".
                                "FROM `Class Copesal` ".
                                "WHERE ID = :subjectid ";
                
                $stmtSelect = $conn->prepare($sqlSelect);
                $stmtSelect->execute(array(':subjectid' => $subject_id));
                $rs = $stmtSelect->fetchAll(PDO::FETCH_ASSOC);                    
                foreach($rs as $row) 
                {
                    $subject = $row["Subject"];
                    $scheduleintensity = $row["Schedule Intensity"];
                    $areaid = $row["AreaID"];
                    $classid = $row["ClassID"];
                    $teacherid = $row["TeacherID"];
                    $substatus = $row["substatus"]; 
                }
                
                ?> 
            
                <div align="center">
                    <form name='updatesubjectform' id='updatesubjectform' method='post'>
                    
                        <h3>Materias en el Sistema</h3>
                            
                        <a href="preupdatesubject.php">Regresar</a>
                        </br></br>
                        <table align="center" border="1">
                            <tr>
                                <td>
                                    Area
                                </td>
                                <td align="center">
                                    <select name='dbArea' id='dbArea'/>
                                        <option value="0">Seleccione un Area</option>
                                        <?php
                                            
                                            $sqlselectA =   "SELECT `AreaID`,`Description` ".
                                                            "FROM `Area`";
                                            try 
                                            {
                                                $stmtA = $conn->prepare($sqlselectA);
                                                $stmtA->execute();
                                                $rsA = $stmtA->fetchAll(PDO::FETCH_ASSOC);
                                                
                                                foreach ($rsA as $rowA) 
                                                {
                                                    $selectedA = ''.($rowA['AreaID'] == $areaid ?'selected':'');
                                                    echo "<option value='".$rowA['AreaID']."' ".$selectedA.">".$rowA['Description']."</option>";
                                                } 
                                            }
                                            catch(PDOException $ex) 
                                            {
                                                echo "An Error occured in Area!"; //;.$ex->getMessage();
                                            }
                                           
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Curso
                                </td>
                                <td>
                                    <select name='dbCurso' id='dbCurso'>
                                        <option value="0">Seleccione el Curso</option>
                                        <?php
                                            
                                            $sqlselectC =   "SELECT `Class ID`,`Class` ".
                                                            "FROM `Class`";
                                            
                                            try 
                                            {
                                                $stmtC = $conn->prepare($sqlselectC);
                                                $stmtC->execute();
                                                $rsC = $stmtC->fetchAll(PDO::FETCH_ASSOC);
                                                
                                                foreach ($rsC as $rowC) 
                                                {
                                                    $selectedC = ''.($rowC['Class ID'] == $classid ?'selected':'');
                                                    echo "<option value='".$rowC['Class ID']."' ".$selectedC.">".$rowC['Class']."</option>";
                                                } 
                                            }
                                            catch(PDOException $ex) 
                                            {
                                                echo "An Error occured in Curso!"; //;.$ex->getMessage();
                                            }
                                        ?>
                                    </select>
                                </td>
                            </tr> 
                            <tr>
                                <td>
                                    Materia
                                </td>
                                <td>
                                    <input type="text" name="tbSubject" id="tbSubject" value="<?php echo $subject; ?>"></br>
                                    <input type="hidden" name="updateorsavesub" id="updateorsavesub" value="update">
                                    <input type="hidden" name="subjectidtoupdate" id="subjectidtoupdate" value="<?php echo $subject_id; ?>">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Intensidad Horaria
                                </td>
                                <td>
                                    Ingrese el numero de horas semanales
                                    <select name="tbInthor" id="tbInthor"></br>
                                        <option value="0">Seleccione Intensidad</option>
                                        <option value="1"<?php echo '',($scheduleintensity == 1 ? 'selected' : '');?>>1</option>
                                        <option value="2"<?php echo '',($scheduleintensity == 2 ? 'selected' : '');?>>2</option>
                                        <option value="3"<?php echo '',($scheduleintensity == 3 ? 'selected' : '');?>>3</option>
                                        <option value="4"<?php echo '',($scheduleintensity == 4 ? 'selected' : '');?>>4</option>
                                        <option value="5"<?php echo '',($scheduleintensity == 5 ? 'selected' : '');?>>5</option>
                                        <option value="6"<?php echo '',($scheduleintensity == 6 ? 'selected' : '');?>>6</option>
                                        <option value="7"<?php echo '',($scheduleintensity == 7 ? 'selected' : '');?>>7</option>
                                        <option value="8"<?php echo '',($scheduleintensity == 8 ? 'selected' : '');?>>8</option>
                                    </select>   
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Profesor
                                </td>
                                <td>
                                    Este campo NO es obligatorio al ingresar</br>
                                    una materia al sistema.</br>
                                    *Solo profesores activos seran enlistados.</br>
                                    <select name='dbTeacher' id='dbTeacher'>
                                        <option value="0">No Asignado</option>
                                        <?php
                                            
                                            $sqlselectT =   "SELECT ID, CONCAT(`Teacher Name`,' ',`Teacher Last`) AS teachername, tstatus ".
                                                            "FROM `Teacher`" .
                                                            "WHERE tstatus = 'A'";
                                            try 
                                            {
                                                $stmtT = $conn->prepare($sqlselectT);
                                                $stmtT->execute();
                                                $rsT = $stmtT->fetchAll(PDO::FETCH_ASSOC);
                                                
                                                foreach ($rsT as $rowT) 
                                                {
                                                    $selectedT = ''.($rowT['ID'] == $teacherid ?'selected':'');
                                                    echo "<option value='".$rowT['ID']."' ".$selectedT.">".$rowT['teachername']."</option>";
                                                } 
                                            }
                                            catch(PDOException $ex) 
                                            {
                                                echo "An Error occured in Teacher!"; //handle me.
                                            }
                                        ?>
                                    </select>
                                </td>                               
                            </tr>
                            <tr>
                                <td>
                                    Estado
                                </td>
                                <td>
                                    <select name="dbSubstatus" id="dbSubstatus"></br>
                                        <option value="0">Seleccione Estado</option>
                                        <option value="A"<?php echo '',($substatus == "A" ? 'selected' : '');?>>Activa</option>
                                        <option value="R"<?php echo '',($substatus == "R" ? 'selected' : '');?>>Retirada</option>
                                    </select> 
                                </td>
                            </tr>      
                        </table>
                        </br>
                        </br>
                        <button onClick="return saveSubject()">Guardar Materia</button>
                        </br>
                        </br>
                        <a href="preupdatesubject.php">Regresar</a>
                                 
                    </form>
    
                </div>
        
        <?php
                
            }
            catch(PDOException $ex) 
            {
                echo "An Error occured! ";//;.$ex->getMessage();; 
                
            }
           
        }
            
        else
        {
        
            echo "You need to be properly authenticated to see the content of this page";
            echo "</br>";
            echo "<a href='logout.php'>Log In</a>";
            
        }
    ?>
    
</body>