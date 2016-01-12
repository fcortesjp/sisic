<?php
    session_start();  //session  
?>
<!DOCTYPE html>
    
<head>
    <meta http-equiv="Content-Type" content="text/html">
</head>
<body>   
    <?php
        
        if(isset($_SESSION['currentUser'])) // if the super global variable session called current user has been initialized then
        {
            $currentUser = $_SESSION['currentUser'];
            $userID = $_SESSION['currentID'];
            $role = $_SESSION['role'];
                        
            echo "Bienvenido(a) ".$currentUser;
            echo "</br>";
            echo "<a href='logout.php'>Logout</a>";
       
    ?>
    <div align="center">
        
        <form name='savestudent' id='savestudent' method='post'>
            
            <?php
                
                include("config-CRDB.php"); //including config.php in our file
                    
                $newstudentid = mysql_real_escape_string($_POST['newstudentid']);
            
                $fnames = mysql_real_escape_string($_POST['fnames']);
                
                $lnames = mysql_real_escape_string($_POST['lnames']);
                
                $ddidtype = mysql_real_escape_string($_POST['ddidtype']);
                
                $nalid = mysql_real_escape_string($_POST['nalid']);
                
                $cityid = mysql_real_escape_string($_POST['cityid']);
                
                $sex = mysql_real_escape_string($_POST['sex']);
                
                $newdbCurso = mysql_real_escape_string($_POST['newdbCurso']);
                
                date_default_timezone_set('America/Bogota');
                $date_i = mysql_real_escape_string($_POST['date_i']);

                //fields set if request comes from preupdatestudent.php
                date_default_timezone_set('America/Bogota');    
                $date_r = mysql_real_escape_string($_POST['date_r']);

                $status = mysql_real_escape_string($_POST['status']);

                $approved = mysql_real_escape_string($_POST['approved']);
                   
                if (!$link) 
                {
                    die('Could not connect: ' . mysql_error());
                    echo "something wrong with the link to the database";
                }
                else 
                {
                    if (isset($_POST["updateorsavestudent"]) && $_POST["updateorsavestudent"] == "update")
                    {
                            
                        $updatesql =    "UPDATE `Student` ".
                                        "SET `Student First` = '$fnames', ".
                                            "`Student Last` = '$lnames', ".
                                            "Identification = '$nalid', ".
                                            "Gender = '$sex', ".
                                            "`Class ID` = '$newdbCurso', ".
                                            "City = '$cityid', ".
                                            "IDType = '$ddidtype', ".
                                            "Approved = '$approved', ".
                                            "date_i = '$date_i', ".
                                            "date_r = '$date_r', ".
                                            "status = '$status' ".
                                        "WHERE `Student ID` = '$newstudentid';";     
                        
                        if (mysql_query($updatesql,$link)) 
                        {
                            ?>
                                </br>
                                Los cambios para el estudiante han sido guardados Satisfactoriamente!
                                </br>
                                <a href="preupdatestudent.php">Regresar</a>
                                
                            <?php   
                        }
                        else 
                        {
                            ?>
                                </br>
                                Los cambios para el estudiante no fueron guardados!
                                
                                </br>
                                <?php echo $obsr_id; ?>
                                </br>
                                <a href="preupdatestudent.php">Regresar</a>
                                </br>
                            <?php 
                            echo "Error insert: " . $updatesql . "<br>" . mysql_error($link);
                        }
                        
                    }
                    elseif(isset($_POST["updateorsavestudent"]) && $_POST["updateorsavestudent"] == "save")
                    {
                        $insertsql =    "INSERT INTO `Student` (`Student ID`, `Student First`, `Student Last`, Identification, Gender, `Class ID`, City, IDType, Approved, date_i, status) ".
                                        "VALUES ('$newstudentid','$fnames','$lnames','$nalid','$sex','$newdbCurso','$cityid','$ddidtype','yes','$date_i','A');";
                                
                        if (mysql_query($insertsql,$link)) 
                        {
                            ?>
                                </br>
                                El estudiante ha sido matriculado satisfactoriamente!
                                </br>
                                <a href="enrollstudent.php">Regresar</a>
                                
                            <?php   
                        }
                        else 
                        {
                            ?>
                                </br>
                                El estudiante no puedo ser matriculado!
                                
                                </br>
                                <a href="enrollstudent.php">Regresar</a>
                                </br>
                            <?php 
                            echo "Error insert: " . $insertsql . "<br>" . mysql_error($link);
                        }
                    }
                }   
                      
                
            ?>
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