<?php
    session_start();  //session  
?>

<!DOCTYPE html>
    
<head>
    <meta http-equiv="Content-Type" content="text/html"> <!-- charset=utf-8 will make accents show on page but will fuckup if accents come from database-->
    
    <script type="text/javascript" charset="utf-8">
        
        function reloadtogetsubjectid(form)
        {
            var cursomatid = form.dbCursoMat.options[form.dbCursoMat.options.selectedIndex].value;
            var cursomatvalue = form.dbCursoMat.options[form.dbCursoMat.options.selectedIndex].text;
            document.getElementById("cursomatvalue").value = cursomatvalue;
            document.getElementById("cursomatid").value = cursomatid;
            
        }
        
        function reloadtogetteacherid(form)
        {
            var teacherid = form.dbTeacher.options[form.dbTeacher.options.selectedIndex].value;
            var teachervalue = form.dbTeacher.options[form.dbTeacher.options.selectedIndex].text;
            document.getElementById("teacherid").value = teacherid;
            document.getElementById("teachername").value = teachervalue;
            
        }
        
        function assignteachertosubject(form)
        {
            sub_id = document.getElementById("cursomatid").value;
            teach_id = document.getElementById("teacherid").value;
            if (sub_id == 0 || teach_id == 0)
            {
                alert("Asegurese de escoger tanto la materia como el profesor");
                return false;
            }
            else
            {
                alert("good");
            }
            
        }
    </script>
    
</head>
<body>
    
    <?php
    
        $currentUser = $_SESSION['currentUser'];
        $userID = $_SESSION['currentID'];
        $role = $_SESSION['role'];
            
        if(isset($_SESSION['currentUser']) && $role == 'd') // if the super global variable session called current user has been initialized then
        {
            //variable to get the curso id if selected
            $dbCursoMat = $_POST['cursomatid'];
            $dbTeacher = $_POST['teacherid'];
            
            include("config_sisic.php");
            
            echo "Bienvenido(a) ".$currentUser;
            echo "</br>";
            echo "<a href='logout.php'>Logout</a>";
    
            
    ?>
            <div align="center">
                <form name='assignteachersubject' id='assignteachersubject' method='post'>
                
                    <h3>Asignacion de Profesor - Materias</h3>
                        
                        <a href="materias.php">Regresar</a>
                        </br></br>
                        <table align="center" border="1">
                            
                            <tr>
                             
                                <td>
                                    Curso - Materia:</br>
                                    *Solo materias activas apareceran enlistadas</br>
                                    <select name='dbCursoMat' id='dbCursoMat' onchange="reloadtogetsubjectid(this.form)">
                                        <option value="0">Seleccione el Curso - Materia</option>
                                        
                                        <?php
                                        
                                            //this snipet gets the id and class from the database
                                            //to populate the curso drowpdown. when the dropdown changes
                                            //dbcurso will have an id and with the onchange parameter
                                            //the form gets reloaded and the id gets retrieved 
                                            //and stored on the variable dbCursoid which is used for the
                                            //dropdown curso
                                            
                                            $sqlCM = "SELECT ID, CONCAT(Class.Class, ' - ', `Class Copesal`.Subject) AS ClassSubject ".
                                                    "FROM `Class Copesal` ". 
                                                    "INNER JOIN Class ".
                                                    "ON `Class Copesal`.ClassID = Class.`Class ID` ".
                                                    "WHERE substatus = 'A' ".
                                                    "ORDER BY Class.`Class ID`;";
                                            
                                            try 
                                            {
                                                $stmtCM = $conn->prepare($sqlCM);
                                                $stmtCM->execute();
                                                $rsCM = $stmtCM->fetchAll(PDO::FETCH_ASSOC);
                                                
                                                foreach ($rsCM as $rowCM) 
                                                {
                                                    if ($rowCM['ID'] ==  $dbCursoMat)
                                                    {// then adds that as one of the options in the dropdown
                                                        echo "<option value='" . $rowCM['ID'] . "' selected>" . $rowCM['ClassSubject'] . "</option>";
                                                    }
                                                    // then adds that as one of the options in the dropdown
                                                    else
                                                    {
                                                        echo "<option value='" . $rowCM['ID'] . "'>" . $rowCM['ClassSubject'] . "</option>";
                                                    }
                                                    
                                                } 
                                            }
                                            catch(PDOException $ex) 
                                            {
                                                echo "An Error occured in Class!"; //handle me.
                                            }
                                           
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    Profesor:</br>
                                    *Solo se muestran profesores activos.</br>
                                    <select name='dbTeacher' id='dbTeacher' onchange="reloadtogetteacherid(this.form)">
                                        <option value="">Seleccione un Usuario</option>
                                        
                    <?php
                                            
                                            $sqlT = "SELECT `ID`,CONCAT(`Teacher Name`,' ',`Teacher Last`) AS TeacherName ".
                                                    "FROM `Teacher` ".
                                                    "WHERE tstatus = :tsta ".
                                                    "ORDER BY `Teacher Name`";
                                            
                                            try 
                                            {
                                                $stmtT = $conn->prepare($sqlT);
                                                $stmtT->execute(array(':tsta' => 'A'));
                                                $rsT = $stmtT->fetchAll(PDO::FETCH_ASSOC);
                                                
                                                foreach ($rsT as $rowT) 
                                                {
                                                    if ($rowT['ID'] ==  $dbTeacher)
                                                    {// then adds that as one of the options in the dropdown
                                                        echo "<option value='" . $rowT['ID'] . "' selected>" . $rowT['TeacherName'] . "</option>";
                                                    }
                                                    // then adds that as one of the options in the dropdown
                                                    else
                                                    {
                                                        echo "<option value='" . $rowT['ID'] . "'>" . $rowT['TeacherName'] . "</option>";
                                                    }
                                                    
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
                                <td align="center" colspan="3">
                                    
                                    <div align="center">
                                        Datos Seleccionados</br></br>
                                        
                                        <input type="text" style="text-align: center;" name="cursomatvalue" id="cursomatvalue" size="40px" readonly>
                                        la dictara
                                        <input type="text" style="text-align: center;" name="teachername" id="teachername" size="40px" readonly>
                                        <input type="hidden" name="cursomatid" id="cursomatid">
                                        <input type="hidden" name="teacherid" id="teacherid"></br></br>
                                        
                                        <button onClick="return assignteachertosubject(this.form)">Asignar Profesor - Materia</button>
                                    </div>
                                
                                </td>
                                
                            </tr>      
                        </table></br>       
                    <?php
                        
            //global variables
            
            include("config_sisic.php");
           
            $out = ""; //variable initialized to create an html table with values
            $title = ""; //variable that will have the title to show 
            
            
            if(isset($POST_['cursomatid']) && $POST_['cursomatid'] != 0 && isset($POST_['teacherid']) && $POST_['teacherid']) 
            {
                //check if subject, areaid and class id already exist
                $subexistalready = subjectareaclassexist($tbSubject,$dbArea,$dbCurso);
                
                if($subexistalready == 0)
                {
                   $subid = $_POST["subjectidtoupdate"];

                    $okupdate = "";

                    $updatesql =   "UPDATE `Class Copesal` ".
                                    "SET `Subject` = :subject, ".
                                    "`Schedule Intensity` = :scheint, ".
                                    "AreaID = :areaid, ".
                                    "ClassID = :classid, ".
                                    "TeacherID = :teacherid, ".
                                    "substatus = :substat ".
                                    "WHERE ID = :id";     
                    
                    try 
                    {
                        //prepare the stementemnt to execute        
                        $updatestmt = $conn->prepare($updatesql);

                        //execute the update statement
                        $updatestmt->execute(array(
                            ':id' => $subid,
                            ':subject' => $tbSubject,
                            ':scheint' => $tbInthor,
                            ':areaid' => $dbArea,
                            ':classid' => $dbCurso,
                            ':teacherid' => $dbTeacher,
                            ':substat' => $substatus
                        ));
                      
                        $okupdate = $updatestmt->rowCount();

                        if ($okupdate == 1) 
                        {
                            ?>
                                </br>
                                Los cambios para la materia han sido guardados satisfactoriamente!
                                </br>
                                <a href="preupdatesubject.php">Regresar</a>
                            <?php   
                        }

                    } 
                    catch(PDOException $ex) 
                    {
                        //echo "Un error occurio! </br>".$ex->getMessage();
                        ?>
                            </br>
                            Un error occurio!</br>
                            Los cambios para la materia no fueron guardados!
                            </br>
                            <a href="preupdatesubject.php">Regresar</a>
                            </br>
                        <?php 
                    } 
                }
                else
                {
                    ?>
                        </br>
                        Los cambios hechos crearian una materia duplicada por lo tanto</br>
                        los cambios no fueron guardados
                        </br>
                        <a href="materias.php">Regresar</a>
                    <?php 
                        
                }    
            }
            
        }    
        else
        {
        
            echo "You need to be properly authenticated to see the content of this page";
            echo "</br>";
            echo "<a href='logout.php'>Log In</a>";
            
        }
    ?>
        </form>
    </div>
</body>