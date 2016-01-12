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
            var enrollsubjectform = document.getElementById ("enrollsubjectform");
            
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
            
            
            var message = "";
            
            message += "Area: " + dbAreatext + "\n";
            message += "Curso: " + dbCursotext + "\n";
            message += "Materia: " + tbSubject + "\n";
            message += "Int.Hor: " + tbInthorvalue + "\n";
            message += "Profesor: " + dbTeachertext + "\n";
            
            
            var response=confirm("Desea guardar la materia con la informacion ingresada?: \n\n" + message);
            
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
    
        if(isset($_SESSION['currentUser']) && $role = 'd' && $userID == 24)
        {
            include("config_sisic.php");
            
            echo "Bienvenido(a) ".$currentUser;
            echo "</br>";
            echo "<a href='logout.php'>Logout</a>";
             
    ?>
            <div align="center">
                <form name='enrollsubjectform' id='enrollsubjectform' method='post'>
                
                    <h3>Ingresar Materias en el Sistema</h3>
                        
                    <a href="materias.php">Regresar</a>
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
                                        
                                        $sqlselectA =    "SELECT `AreaID`,`Description` ".
                                                        "FROM `Area`";
                                        try 
                                        {
                                            $stmtA = $conn->prepare($sqlselectA);
                                            $stmtA->execute();
                                            $rsA = $stmtA->fetchAll(PDO::FETCH_ASSOC);
                                            
                                            foreach ($rsA as $rowA) 
                                            {
                                                echo "<option value='".$rowA['AreaID']."'>".$rowA['Description']."</option>";
                                            } 
                                        }
                                        catch(PDOException $ex) 
                                        {
                                            echo "An Error occured in Area!"; //handle me.
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
                                                echo "<option value='".$rowC['Class ID']."'>".$rowC['Class']."</option>";
                                            } 
                                        }
                                        catch(PDOException $ex) 
                                        {
                                            echo "An Error occured in Curso!"; //handle me.
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
                                <input type="text" name="tbSubject" id="tbSubject"></br>
                                <input type="hidden" name="updateorsavesub" id="updateorsavesub" value="save">
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
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
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
                                                echo "<option value='".$rowT['ID']."'>".$rowT['teachername']."</option>";
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
                    </table>
                    </br>
                    </br>
                    <button onClick="return saveSubject()">Guardar Materia</button>
                    </br>
                    </br>
                    <a href="materias.php">Regresar</a>
                                
                </form>

            </div>
    
    <?php
        }
        else
        {
        
            echo "You need to be authenticated and be a special admin to see the content of this page";
            echo "</br>";
            echo "<a href='logout.php'>Log In</a>";
            
        }
        
    ?>
    
</body>