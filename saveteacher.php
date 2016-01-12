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
                
                include("config_sisic.php"); //including config.php in our file
                    
                $newteachercc = mysql_real_escape_string($_POST['newteachercc']);
            
                $fnames = mysql_real_escape_string($_POST['fnames']);
                
                $lnames = mysql_real_escape_string($_POST['lnames']);
                
                $un = mysql_real_escape_string($_POST['un']);
                
                $pw = mysql_real_escape_string($_POST['pw']);

                $role = mysql_real_escape_string($_POST['role']);
                
                date_default_timezone_set('America/Bogota');
                $tdate_i = mysql_real_escape_string($_POST['tdate_i']);

                //fields set if request comes from preupdatestudent.php
                date_default_timezone_set('America/Bogota');    
                $tdate_r = mysql_real_escape_string($_POST['tdate_r']);

                $tstatus = mysql_real_escape_string($_POST['tstatus']);
   

                
                if (isset($_POST["updateorsave"]) && $_POST["updateorsave"] == "update") //if this field is posted it's an update
                {
                    $tid = $_POST["tid"];

                    $okupdate = "";

                    $updatesql = "";
                    $updatesql .=   "UPDATE `Teacher` ".
                                    "SET `Teacher Name` = :fnames, ".
                                    "`Teacher Last` = :lnames, ".
                                    "user = :un, ";
                                        
                    if(isset($role) && isset($pw) && ($userID == '24'))
                    {
                        $updatesql .= " pass = :pw, role = :role, ";
                    }
                                        
                    $updatesql .=       "tidentification = :tidentification, ".
                                        "tdate_i = :tdate_i, ".
                                        "tdate_r = :tdate_r, ".
                                        "tstatus = :tstatus ".
                                    "WHERE ID = :tid";     
                    
                    try 
                    {
                        //prepare the stementemnt to execute        
                        $updatestmt = $conn->prepare($updatesql);

                        //execute the update statement
                        $updatestmt->execute(array(
                            ':tid' => $tid,
                            ':fnames' => $fnames,
                            ':lnames' => $lnames,
                            ':un' => $un,
                            ':pw' => $pw,
                            ':role' => $role,
                            ':tidentification' => $newteachercc,
                            ':tdate_i' => $tdate_i,
                            ':tdate_r' => $tdate_r,
                            ':tstatus' => $tstatus
                        ));
                      
                        $okupdate = $updatestmt->rowCount();

                        if ($okupdate == 1) 
                        {
                            ?>
                                </br>
                                Los cambios para el profesor han sido guardados satisfactoriamente!
                                </br>
                                <a href="profesores.php">Regresar</a>
                            <?php   
                        }

                    } 
                    catch(PDOException $ex) 
                    {
                        //echo "Un error occurio! </br>".$ex->getMessage();
                        ?>
                            </br>
                            Un error occurio!</br>
                            Los cambios para el profesor no fueron guardados!
                            </br>
                            <a href="profesores.php">Regresar</a>
                            </br>
                        <?php 
                    }
                        
                }
                elseif (isset($_POST["updateorsave"]) && $_POST["updateorsave"] == "save")
                {
                    $okinsert = "";

                    $insertsql =    "INSERT INTO `Teacher` ".
                                    "(`Teacher Name`, `Teacher Last`, user, pass, role, ".
                                    "tidentification, tdate_i, tstatus) ".
                                    "VALUES ".
                                    "(:fnames, :lnames, :un, :pw, :role, :tidentification, ".
                                    ":tdate_i, :tstatus)";
                          
                    try 
                    {
                        //prepare the stementemnt to execute        
                        $insertstmt = $conn->prepare($insertsql);

                        //execute the update statement
                        $insertstmt->execute(array(
                            ':fnames' => $fnames,
                            ':lnames' => $lnames,
                            ':un' => $un,
                            ':pw' => $pw,
                            ':role' => 't',
                            ':tidentification' => $newteachercc,
                            ':tdate_i' => $tdate_i,
                            ':tstatus' => 'A'
                        ));
                        
                        $okinsert = $insertstmt->rowCount();

                        if ($okinsert == 1) 
                        {
                            ?>
                                </br>
                                El profesor ha sido cargado en el sistema satisfactoriamente!
                                </br>
                                <a href="profesores.php">Regresar</a>
                            <?php   
                        }

                    } 
                    catch(PDOException $ex) 
                    {
                        //echo "Un error occurio! </br>".$ex->getMessage();
                        ?>
                            </br>
                            Un error occurio!</br>
                            El profesor no puedo ser matriculado en el sistema!
                            </br>
                            <a href="profesores.php">Regresar</a>
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
        
            echo "You need to be authenticated to see the content of this page";
            echo "</br>";
            echo "<a href='logout.php'>Log In</a>";
            
        }
         
    ?>
</body>