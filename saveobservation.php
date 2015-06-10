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
        
        <form name='observador' id='observador' method='post'>
            
            <?php
                
                include("config-CRDB.php"); //including config.php in our file
                    
                $st_cod = mysql_real_escape_string($_POST['st_cod']);
            
                $classid = mysql_real_escape_string($_POST['classid']);
                
                date_default_timezone_set('America/Bogota');
                $date_open = mysql_real_escape_string($_POST['date_open']);
                //echo $date_open." : es la fecha de apertura</br></br>";
                
                $date_close = mysql_real_escape_string($_POST['date_close']);
                
                $cat = mysql_real_escape_string($_POST['cat']);
                
                $importance = mysql_real_escape_string($_POST['importance']);
                
                $observation = mysql_real_escape_string($_POST['observation']);
                //echo $observation." : es la observacion</br></br>";
                
                $objective = mysql_real_escape_string($_POST['objective']);
                
                $compromise = mysql_real_escape_string($_POST['compromise']);
                //echo $compromise." : es el compromiso</br></br>";
                
                $dbClassSubject = mysql_real_escape_string($_POST['dbClassSubject']);
                  
                   
                if (!$link) 
                {
                    die('Could not connect: ' . mysql_error());
                    echo "something wrong with the link to the database";
                }
                else 
                {
                    $table = 'Observations';
                    
                    if (isset($_POST["update"]))
                    {
                        $obsr_id = $_POST["update"]; 
                            
                        $updatesql =    "UPDATE `$table` ".
                                        "SET date_open = '$date_open', ".
                                            "date_close = '$date_close', ".
                                            "category = '$cat', ".
                                            "importance = '$importance', ".
                                            "observation = '$observation', ".
                                            "objetive = '$objective', ".
                                            "compromise = '$compromise', ".
                                            "subject_id = '$dbClassSubject' ".
                                        "WHERE observation_id = '$obsr_id';";     
                        
                        if (mysql_query($updatesql,$link)) 
                        {
                            ?>
                                </br>
                                Los cambios en la observacion han sido guardados Satisfactoriamente!
                                </br>
                                <a href="observador.php">regresar</a>
                                
                            <?php   
                        }
                        else 
                        {
                            ?>
                                </br>
                                Los cambios en la observacion no fueron guardados!
                                
                                </br>
                                <?php echo $obsr_id; ?>
                                </br>
                                <a href="observador.php">regresar</a>
                                </br>
                            <?php 
                            echo "Error insert: " . $updatesql . "<br>" . mysql_error($link);
                        }
                        
                    }
                    else
                    {
                        $insertsql =    "INSERT INTO `$table` (student_id,user_id,class_id,date_open,date_close,category,importance,observation,objetive,compromise,subject_id) ".
                                        "VALUES ('$st_cod','$userID','$classid','$date_open','$date_close','$cat','$importance','$observation','$objective','$compromise','$dbClassSubject');";
                                
                        if (mysql_query($insertsql,$link)) 
                        {
                            ?>
                                </br>
                                La observacion ha sido guardada Satisfactoriamente!
                                </br>
                                <a href="observador.php">regresar</a>
                                
                            <?php   
                        }
                        else 
                        {
                            ?>
                                </br>
                                La observacion no fue guarda!
                                
                                </br>
                                <a href="observador.php">regresar</a>
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