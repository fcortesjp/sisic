<?php
    session_start();  //session  
?>

<!DOCTYPE html>
    
<head>
    <meta http-equiv="Content-Type" content="text/html"> <!-- charset=utf-8 will make accents show on page but will fuckup if accents come from database-->
    
    <script type="text/javascript" charset="utf-8">
        
        function reloadtogetclassid(form)
        {
            var cursoid = form.dbCurso.options[form.dbCurso.options.selectedIndex].value;
            self.location = 'preupdatesubject.php?dbCurso=' + cursoid ;
        }
        
        function getsubjectidfromdropdown(form)
        {
            
            var subjectid = form.dbSubject.options[form.dbSubject.options.selectedIndex].value;
            var subjectvalue = form.dbSubject.options[form.dbSubject.options.selectedIndex].text;
            
            var cursovalue = form.dbCurso.options[form.dbCurso.options.selectedIndex].text;
            document.getElementById("cursovalue").value = cursovalue;
            
            document.getElementById("subjectid").value = subjectid;
            document.getElementById("subjectvalue").value = subjectvalue;
        }
        
        function SearchSubject()
        {
            var cursoid = form.dbCurso.options[form.dbCurso.options.selectedIndex].value;
            self.location = 'preupdatesubject.php?dbCurso=' + cursoid ;
            
        }
        
    </script>
    
</head>
<body>
    
    <?php
    
        $currentUser = $_SESSION['currentUser'];
        $userID = $_SESSION['currentID'];
        $role = $_SESSION['role'];
            
        if(isset($_SESSION['currentUser']) && $userID = 24) // if the super global variable session called current user has been initialized then
        {
            //variable to get the curso id if selected
            $dbCursoid = $_GET['dbCurso'];
            
            include("config_sisic.php");
            
            echo "Bienvenido(a) ".$currentUser;
            echo "</br>";
            echo "<a href='logout.php'>Logout</a>";
    
            
    ?>
            <div align="center">
                <form name='updatesubject' id='updatesubject' method='post'>
                
                    <h3>Actualizar Materia</h3>
                        
                        <a href="materias.php">Regresar</a>
                        </br></br>
                        <table align="center" border="1">
                            
                            <tr>
                                
                                <td>
                                    Buscar Materia
                                </td>
                                
                                <td>
                                    Curso:
                                    <select name='dbCurso' id='dbCurso' onchange="reloadtogetclassid(this.form)">
                                        <option value="0">Seleccione el Curso</option>
                                        
                                        <?php
                                        
                                            //this snipet gets the id and class from the database
                                            //to populate the curso drowpdown. when the dropdown changes
                                            //dbcurso will have an id and with the onchange parameter
                                            //the form gets reloaded and the id gets retrieved 
                                            //and stored on the variable dbCursoid which is used for the
                                            //dropdown curso
                                            
                                            $sqlC =  "SELECT `Class ID`,`Class` ".
                                                    "FROM `Class`";
                                            
                                            try 
                                            {
                                                $stmtC = $conn->prepare($sqlC);
                                                $stmtC->execute();
                                                $rsC = $stmtC->fetchAll(PDO::FETCH_ASSOC);
                                                
                                                foreach ($rsC as $rowC) 
                                                {
                                                    if ($rowC['Class ID'] ==  $dbCursoid)
                                                    {// then adds that as one of the options in the dropdown
                                                        echo "<option value='" . $rowC['Class ID'] . "' selected>" . $rowC['Class'] . "</option>";
                                                    }
                                                    // then adds that as one of the options in the dropdown
                                                    else
                                                    {
                                                        echo "<option value='" . $rowC['Class ID'] . "'>" . $rowC['Class'] . "</option>";
                                                    }
                                                    
                                                } 
                                            }
                                            catch(PDOException $ex) 
                                            {
                                                echo "An Error occured in Class!"; //handle me.
                                            }
                                           
                                        ?>
                                    </select>
                                    
                                    Materia:
                                    <select name='dbSubject' id='dbSubject' onchange="getsubjectidfromdropdown(this.form)">
                                        <option value="0">Seleccione La Materia</option>
                                        <?php
                                            
                                            if(isset($dbCursoid) && strlen($dbCursoid) > 0)
                                            {
                                                $sqlM = "SELECT ID, Subject ".
                                                        "FROM `Class Copesal` ".
                                                        "WHERE ClassID = :cursoid ".
                                                        "ORDER BY Subject";
                                                
                                                try 
                                                {
                                                    $stmtM = $conn->prepare($sqlM);
                                                    $stmtM->execute(array(':cursoid' => $dbCursoid));;
                                                    $rsM = $stmtM->fetchAll(PDO::FETCH_ASSOC);
                                                    
                                                    foreach ($rsM as $rowM) 
                                                    {
                                                        echo "<option value='" . $rowM['ID'] . "'>" . $rowM['Subject'] . "</option>";
                                                    } 
                                                }
                                                catch(PDOException $ex) 
                                                {
                                                    echo "An Error occured in Class!"; //handle me.
                                                }
                                            }
                                            
                                        ?>
                                        <option value="todas">Todas las Materias</option>
                                    </select>
                                    </br>
                                    </br>
                                    
                                    Codigo:</br>
                                    <input type="text" name="subjectid" id="subjectid">
                                    <input type="text" name="cursovalue" id="cursovalue" readonly>
                                    <input type="text" name="subjectvalue" id="subjectvalue" readonly></br>
                                    <button onClick="SearchSubject()">Buscar Materia</button>
                                </td>
                                
                            </tr>      
                        </table></br>       
                        <?php
                        
            
            $out ="";
            if(trim($_POST['subjectid']) !== "")
            {
                include("config_sisic.php");
                
                $out = ""; //variable initialized to create an html table with values
                $title = ""; //variable that will have the title to show 
                
                $sqlSelect ="";
                
                $sqlSelect .=   "SELECT `Class Copesal`.`ID` AS MateriaID, Class.Class AS Curso, ".
                                "Subject AS Materia, CONCAT(`Teacher Name`,' ',`Teacher Last`) AS Profesor, ".
                                "`Schedule Intensity` AS IH, Area.Description AS AreaM, substatus AS Estado ".
                                "FROM `Class Copesal` ".
                                "LEFT JOIN `Area` ".
                                "ON `Class Copesal`.AreaID = Area.AreaID ".
                                "LEFT JOIN `Class` ".
                                "ON `Class Copesal`.ClassID = Class.`Class ID` ".
                                "LEFT JOIN `Teacher` ".
                                "ON `Class Copesal`.TeacherID = Teacher.ID ";
                
                try 
                {
                    if(isset($_POST["subjectid"]) && $_POST["subjectid"] == "todas")
                    {
                        $sqlSelect .= "WHERE Class.`Class ID` = :classid ";
                        $sqlSelect .= "ORDER BY Class.Class, Subject"; 
                        $stmtSelect = $conn->prepare($sqlSelect);
                        $stmtSelect->execute(array(':classid' => $dbCursoid));
                         
                        
                        $title .= "Todas las Materias de ".$_POST["cursovalue"];
                    }        
                    elseif(isset($_POST["subjectid"]) && $_POST["subjectid"] != "todas" && $_POST["subjectid"] != 0)
                    {
                        $s_id = mysql_real_escape_string($_POST['subjectid']);
                        $sqlSelect .= "WHERE `Class Copesal`.`ID` = :subjectid ";
                        $sqlSelect .= "ORDER BY Class.Class, Subject"; 
                        $stmtSelect = $conn->prepare($sqlSelect);
                        $stmtSelect->execute(array(':subjectid' => $s_id));
                        
                        $title .= "Materia: ".$_POST["subjectvalue"]." Curso: ".$_POST["cursovalue"];
                    }
                    $numrows = $stmtSelect->rowCount(); 
                    $fullrs = $stmtSelect->fetchAll(PDO::FETCH_ASSOC);
                    $colheaders = current($fullrs);
                    
                    $out .= '<table border="1">';    
                    foreach($colheaders as $key=>$val) 
                    {
                        $out .= '<th>'.$key.'</th>';
                    }   
                    
                    foreach($fullrs as $row) 
                    {
                        $out .= "<tr>"; //add the start of the row for the record on the table
                            $out .= '<td><a href="updatesubject.php?id='.$row["MateriaID"].'">'."Actualizar".'</a></td>';
                            $out .= '<td>'.$row["Curso"].'</td>';
                            $out .= '<td>'.$row["Materia"].'</td>';
                            $out .= '<td>'.$row["Profesor"].'</td>';
                            $out .= '<td>'.$row["IH"].'</td>';
                            $out .= '<td>'.$row["AreaM"].'</td>';
                            $out .= '<td>'.$row["Estado"].'</td>';
                        $out .= "</tr>"; //add the close of the row) {
                    }
                    $out .= '</table>';
                    
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
                    <a href="materias.php">Regresar</a>
                     
                <?php
                     
                } 
                catch(PDOException $ex) 
                {
                    echo "An Error occured! ";//;.$ex->getMessage();; 
                    
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