<?php
    session_start();  //session     
?>
<!DOCTYPE html>
    
<head>
    <meta http-equiv="Content-Type" content="text/html">
</head>
<body>   
    <?php
        
        $currentUser = $_SESSION['currentUser'];
        $userID = $_SESSION['currentID'];
        $role = $_SESSION['role'];

        if(isset($_SESSION['currentUser']) && $role = 'd' && $userID == 24)
        {
            echo "Bienvenido(a) ".$currentUser;
            echo "</br>";
            echo "<a href='logout.php'>Logout</a>";
        
    ?>
            <div align="center">
                
                <form name='savestudent' id='savestudent' method='post'>
                    
                    <?php
                        
                        include("config_sisic.php"); //including config.php in our file
                            
                        $dbArea = mysql_real_escape_string($_POST['dbArea']);
                    
                        $dbCurso = mysql_real_escape_string($_POST['dbCurso']);
                        
                        $tbSubject = mysql_real_escape_string($_POST['tbSubject']);
                        
                        $tbInthor = mysql_real_escape_string($_POST['tbInthor']);
                        
                        $dbTeacher = mysql_real_escape_string($_POST['dbTeacher']);
                        
                        $substatus = mysql_real_escape_string($_POST['dbSubstatus']);
           
                        
                        if (isset($_POST["updateorsavesub"]) && $_POST["updateorsavesub"] == "update") //if this field is posted it's an update
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
                        elseif (isset($_POST["updateorsavesub"]) && $_POST["updateorsavesub"] == "save")
                        {
                            //check if subject, areaid and class id already exist
                            $subexistalready = subjectareaclassexist($tbSubject,$dbArea,$dbCurso);
                            
                            if($subexistalready == 0)
                            {
                            
                                $subid = createsubjectid($dbArea,$dbCurso); //function definition below
                                
                                if ($subid != "0")
                                {
                                    $insertsql =    "INSERT INTO `Class Copesal` ".
                                                    "(`ID`, `Subject`, `Schedule Intensity`, AreaID, ClassID, ".
                                                    "TeacherID, substatus) ".
                                                    "VALUES ".
                                                    "(:id, :subject, :scheint, :areaid, :classid, :teacherid, ".
                                                    ":substat)";
                                      
                                    try 
                                    {
                                        //prepare the stementemnt to execute        
                                        $insertstmt = $conn->prepare($insertsql);
                
                                        //execute the update statement
                                        $insertstmt->execute(array(
                                            ':id' => $subid,
                                            ':subject' => $tbSubject,
                                            ':scheint' => $tbInthor,
                                            ':areaid' => $dbArea,
                                            ':classid' => $dbCurso,
                                            ':teacherid' => $dbTeacher,
                                            ':substat' => 'A'
                                        ));
                                        
                                        $okinsert = $insertstmt->rowCount();
                
                                        if ($okinsert == 1) 
                                        {
                                            //echo 'subid='.$subid;
                                            ?>
                                                </br>
                                                La materia ha sido cargada en el sistema satisfactoriamente!
                                                </br>
                                                <a href="materias.php">Regresar</a>
                                            <?php   
                                        }
                
                                    } 
                                    catch(PDOException $ex) 
                                    {
                                        echo "Un error occurio! </br>";//.$ex->getMessage();
                                        ?>
                                            </br>
                                            Un error occurio al intentar adicionar la materia a la base de datos!</br>
                                            La materia no puedo ser cargada en el sistema!
                                            </br>
                                            <a href="materias.php">Regresar</a>
                                            </br>
                                        <?php 
                                    }   
                                        
                                }
                                else 
                                {
                                    echo "hay un problema con el el id de la materia, id=".$subid;
                                }
                            }
                            else
                            {
                                 echo "la materia ".$tbSubject." con id de area ".$dbArea." y id de curso ".$dbCurso." Ya existe";
                                 ?>
                                    </br>
                                    <a href="materias.php">Regresar</a>
                                    </br>
                                <?php
                            }
                        }
                    ?>
                </form>
        
            </div>
    <?php
            
        }
        else
        {
        
            echo "You need to be authenticated as an admin with especial priviledges to see the content of this page";
            echo "</br>";
            echo "<a href='logout.php'>Log In</a>";
            
        }
        
    ?>
    
</body>

<?php

function createsubjectid(&$Area,&$Curso)
{
   include("config_sisic.php"); //including config.php in our file
   //initialize the return variable   
   $sub_id = "";
   
   //get the unique subject number or secuence number for the subject
   try
   {
       $sql = "SELECT COUNT(ClassID) AS countsubjects FROM `Class Copesal` WHERE ClassID = :curso";
       $stmt = $conn->prepare($sql);
       $stmt->execute(array(':curso' => $Curso));
       $row = $stmt->fetch();
       //to the count of subject for the class, add 1 to make it the next one in the secuence
       //and then add 100 to ensure that the unique number is always 3 digits
       $unique = $row['countsubjects'] + 1 + 100;
       
       //check unique is between 100 and 200
       if (($unique > 100) && ($unique < 200))
       {
          //sub_id is classid + unique subject + areaid
          $sub_id = $Curso.$unique.$Area;
          return $sub_id; 
       }
       else 
       {
          //$sub_id should be 0 if this occurs
          $sub_id = "0";
          return $sub_id; 
       }
   }
   catch(PDOException $e) 
   {
       echo "Un error occurio! </br>";//.$e->getMessage();
   }
   
}

function subjectareaclassexist(&$Materia,&$Area,&$Curso)
{
   include("config_sisic.php"); //including config.php in our file
   //initialize the return variable   
   $subjectalreadyexist = 0;
   try
   {
       $sql =   "SELECT `Class Copesal`.`ID` AS MateriaID, Class.Class AS Curso, ".
                "Subject AS Materia, Area.Description AS AreaM ".
                "FROM `Class Copesal` ".
                "LEFT JOIN `Area` ".
                "ON `Class Copesal`.AreaID = Area.AreaID ".
                "LEFT JOIN `Class` ".
                "ON `Class Copesal`.ClassID = Class.`Class ID` ".
                "WHERE Class.`Class ID` = :classid ".
                "AND Area.AreaID = :areaid ".
                "AND Subject = :materia ";
                
       $stmt = $conn->prepare($sql);
       $stmt->execute(array(
            ':classid' => $Curso,
            ':areaid' => $Area,
            ':materia' => $Materia,
       ));
       
       $numrows = $stmt->rowCount();
       
       
       //check unique is between 100 and 200
       if ($numrows > 0)
       {
          $subjectalreadyexist = 1;
          
       }
       return $subjectalreadyexist; 
       
   }
   catch(PDOException $e) 
   {
       echo "Un error occurio! </br>";//.$e->getMessage();
   }
}
?>