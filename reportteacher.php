<?php
    session_start();  //session  
?>

<!DOCTYPE html>
    
<head>
    <meta http-equiv="Content-Type" content="text/html"> <!-- charset=utf-8 will make accents show on page but will fuckup if accents come from database-->
    
    <script type="text/javascript" charset="utf-8">
        
        function SearchTeacherall(form) 
        {
            
            var reportform = document.getElementById("reportteachers");
            
            //this value is set once all posted data is good so the query can be executed
            document.getElementById("all").value = 1;
            
            
            reportform.action = "reportteacher.php";
            reportform.submit();
            
            
        }
        // this formula ensures that the value is compounded by only numbers
         ///*
        function onlynumbers(str)
        {    
            var val = /^\d+$/gm;
            if(val.test(str))
            {
                return true;
            }
            else
            {
                return false; 
            }   
        }
        
        function SearchTeacherone(form)
        {
            //this line takes string and removes every single space of any kind on the sides and inside
            //var studentid = document.getElementById("studentid").value.replace(/(^\s+)|\s(?=\s+)|(\s+$)|(\s)/gm, '');
            var reportform = document.getElementById("reportteachers");
            var teachercc = document.getElementById("teachercc").value;

            if (teachercc < 0 || onlynumbers(teachercc) == false)
            {
                alert("La cedula solo debe contener digitos y no puede ser un numero negativo");
                //this will make sure the form won't submit if the alert comes up.
                return false;

            }
            else
            {
                
                document.getElementById("one").value = 1;
                reportform.action='reportteacher.php';
                reportform.submit();
            
            }
        }
        //*/
    </script> 
    
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
            
        }
        else
        {
        
            echo "You need to be authenticated to see the content of this page";
            echo "</br>";
            echo "<a href='logout.php'>Log In</a>";
            
        }
        
    ?>

    <div align="center">
                
    <?php   
            if (isset($_SESSION['currentUser']) && $role = 'd')  
            {
    ?>
                <form name='reportteachers' id='reportteachers' method='post'>
        
                <h3>Lista de Profesores</h3>
                <a href="profesores.php">Regresar</a>
                </br></br>

                <table border="1">
                    <th colspan="2">
                        Profesores
                    </th> 
                    <tr>
                        <td>
                            Enliste todos los profesores</br></br>
                            <input type="hidden" name="all" id="all" value=""></input>
                            <button onClick="return SearchTeacherall(this.form)">Buscar Profesor</button>
                        </td>
                        <td>
                            Ingrese la Cedula de un profesor:</br></br>
                            <input type="number" name="teachercc" id="teachercc"/></br>
                            <!-- hidden field set to determine the type of query -->
                            <input type="hidden" name="one" id="one" value=""></input>
                            <button onClick="return SearchTeacherone(this.form)">Buscar Profesor</button>
                        </td>
                    </tr>
                        
                   
            </table>
            <?php
                
                include("config_sisic.php");
                
                $out = ""; //variable initialized to create an html table with values
                $title = ""; //variable that will have the title to show 
                
                $sqlselect = "";
                $sqlselect .=   "SELECT `Teacher Name` AS Nombres,`Teacher Last` AS Apellidos, ".
                                "`user` AS Usuario, role As Rol, tidentification AS Cedula, ".
                                "tdate_i AS FechaIng, tdate_r AS FechaRet, tstatus AS Estado ".
                                "FROM `Teacher` ";
                try 
                {
                    if(isset($_POST["all"]) && $_POST["all"] == 1 && $userID == 24)
                    {
                        $stmt = $conn->prepare($sqlselect);
                        $stmt->execute();
                        
                    }        
                    elseif(isset($_POST["one"]) && $_POST["one"] == 1 && $userID == 24)
                    {
                        $t_cc = mysql_real_escape_string($_POST['teachercc']);
                        $sqlselect .= "WHERE `tidentification` = :cedula ";
                        $stmt = $conn->prepare($sqlselect);
                        $stmt->execute(array(':cedula' => $t_cc));
                    }               
                    elseif(isset($_POST["all"]) && $_POST["all"] == 1 && $userID != 24)
                    {
                        $sqlselect .= "WHERE role = :role ";
                        $stmt = $conn->prepare($sqlselect);
                        $stmt->execute(array(':role' => 't'));
                    }                
                    elseif(isset($_POST["one"]) && $_POST["one"] == 1 && $userID != 24)
                    {
                        $t_cc = mysql_real_escape_string($_POST['teachercc']);
                        $sqlselect .= "WHERE role = :role ".
                        $sqlselect .= "AND `tidentification` = :cedula"; 
                        $stmt = $conn->prepare($sqlselect);
                        $stmt->execute(array(':cedula' => $t_cc, ':role' => 't'));
                       
                    }
                    $numrows = $stmt->rowCount(); 
                    $fullrs = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $colheaders = current($fullrs);
                    
                    $out .= '<table border="1">';    
                    foreach($colheaders as $key=>$val) 
                    {
                        $out .= '<th>'.$key.'</th>';
                    }   
                    
                    foreach($fullrs as $row) 
                    {
                        $out .= "<tr>"; //add the start of the row for the record on the table
                            $out .= '<td>'.$row["Nombres"].'</td>';
                            $out .= '<td>'.$row["Apellidos"].'</td>';
                            $out .= '<td>'.$row["Usuario"].'</td>';
                            $out .= '<td>'.$row["Rol"].'</td>';
                            $out .= '<td>'.$row["Cedula"].'</td>';
                            $out .= '<td>'.$row["FechaIng"].'</td>';
                            $out .= '<td>'.$row["FechaRet"].'</td>';
                            $out .= '<td>'.$row["Estado"].'</td>';
                        $out .= "</tr>"; //add the close of the row) {
                    }
                    $out .= '</table>'; 
                } 
                catch(PDOException $ex) 
                {
                    echo "An Error occured!"; //handle me.
                }
               
                $title .= "Profesores";
                
                if(isset($_POST["all"]) || isset($_POST["one"]))
                {
            ?>

                <div align="center">
                    
                    <h3>
                        <?php 
                            //this shows the title of the search
                            echo $title; 
                        ?>
                    </h3>

                    </br>
                    Total de records arrojados por la busqueda: <?php echo $numrows; ?>
                    </br>
                    </br> 
                    <?php 
                        
                        //this shows the table created above with the filtered data
                        echo $out;
                    ?>
                    </br>
                    </br>
                    Total de records arrojados por la busqueda: <?php echo $numrows; ?>
                    </br>
                    
                </div>

                </br>
                <a href="profesores.php">Regresar</a>
                    
          <?php 
                }  
                    
            }
        ?>
        </form>
    </div>
</body>
