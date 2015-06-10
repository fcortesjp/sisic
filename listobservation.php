<?php
    session_start();  //session  
?>

<!DOCTYPE html>
    
<head>
    <meta http-equiv="Content-Type" content="text/html"> <!-- charset=utf-8 will make accents show on page but will fuckup if accents come from database-->
    
    <script type="text/javascript" charset="utf-8">
        
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
        <form name='updateobservador' id='updateobservador' method='post'>
        
            <h3>Lista de Observaciones Hechas por <?php echo $currentUser; ?></h3>
                
                <table align="center" border="1">
                           
                            <?php
                               
                                $out ="";
                            
                                include("config-CRDB.php");
                                
                                if (!$link) 
                                {
                                    die('Could not connect: ' . mysql_error());
                                    echo "something wrong with the link to the database";
                                }
                                else //if connection is good...
                                {
                                    
                                    $sqlselectResult =  "SELECT observation_id, Student.`Student ID`, Student.`Student Last`, Class.Class, date_open, category, importance, observation, CONCAT(Class.Class,'-',Subject) as subject, date_close ". 
                                                        "FROM `Observations` ". 
                                                        "LEFT JOIN `Class` ".
                                                        "ON Observations.class_id = Class.`Class ID` ".
                                                        "LEFT JOIN `Class Copesal` ".
                                                        "ON Observations.subject_id = `Class Copesal`.`ID` ".
                                                        "LEFT JOIN `Student` ".
                                                        "ON Observations.student_id = `Student`.`Student ID` ".
                                                        "WHERE Observations.user_id = '$userID';"; 
                                
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
                                            case 5:
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
                                            $out .= '<td><a href="updateobservation.php?id='.$line["observation_id"].'">'."Actualizar".'</a></td>';
                                            $out .= '<td>'.$line["Student ID"].'</td>';
                                            $out .= '<td>'.$line["Student Last"].'</td>';
                                            $out .= '<td>'.$line["Class"].'</td>';
                                            $out .= '<td>'.$line["date_open"].'</td>';
                                            $out .= '<td>'.$catgry.'</td>';
                                            $out .= '<td>'.$imptce.'</td>';
                                            $out .= '<td>'.$line["observation"].'</td>';
                                            $out .= '<td>'.$line["subject"].'</td>';
                                            $status = ''.($line["date_close"] == "0000-00-00" ?'Abierto':'Cerrado');
                                            $out .= '<td>'.$status.'</td>';
                                        $out .= "</tr>"; //add the close of the row
                                    }
                                    $out .= "</table>"; // add the closing of the table
                                 ?>       
                    <tr>
                        <td>
                    
                            <?php echo $out; ?>
                                
                        </td>
                    </tr>
                           <?php         
                                }
                                
                            ?>
                    
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