<?php
    session_start();  //session  
?>

<!DOCTYPE html>
    
<head>
    <meta http-equiv="Content-Type" content="text/html"> <!-- charset=utf-8 will make accents show on page but will fuckup if accents come from database-->
    
    <script type="text/javascript" charset="utf-8">
    
        function enrollsubject() 
        {
            var subjectform = document.getElementById ("subjectform");
            subjectform.action = "enrollsubject.php";
            subjectform.submit();
            //alert("Submit button clicked!");  
        }
        
        function updatesubject() 
        {
            var subjectform = document.getElementById ("subjectform");
            subjectform.action = "preupdatesubject.php";
            subjectform.submit();
        }
        
        function reportsubject() 
        {
            var subjectform = document.getElementById ("subjectform");
            subjectform.action = "reportsubject.php";
            subjectform.submit();
            
        }
        
        function assignteachersubject() 
        {
            var subjectform = document.getElementById ("subjectform");
            subjectform.action = "assignteachersubject.php";
            subjectform.submit();
            
        }
    </script>
    
</head>
<body>
    <?php
        
        if(isset($_SESSION['currentUser'])) // if the super global variable session called current user has been initialized then
        {
            $currentUser = $_SESSION['currentUser'];
            $userID = $_SESSION['currentID'];
            $role = $_SESSION['role'];
            
            
            //include("config-CRDB.php"); //including config.php in our file to connect to the database
            
            //get the subjects user/teacher is in charge of or if role is d (director) get all the classes
            
            if ($role == 'd') 
            {
                //if user logged is a director set this variable for themenu to display
                
                echo "Bienvenido(a) ".$currentUser;
                echo "</br>";
                echo "<a href='logout.php'>Logout</a>"; 

                ?>
                <div align="center">
                
                    <form name='subjectform' id='subjectform' method='post'>
                            
                        <h3>Materias</h3>
                        <a href="menu.php">Regresar</a>
                        </br></br>
                        <table align="center" border="0">
                        
                            <tr>
                                
                                <td> <!-- buton de Matricular Estudiante -->
                                    <button type="button" style="height: 100px; width: 100px" name="IngresarMateria" align="center" id="IngresarMateria" onclick="enrollsubject()" >Ingresar Materia</button>
                                </td>
                                
                                <td> <!-- buton de Editar Estudiante Existente -->
                                    <button type="button" style="height: 100px; width: 100px" name="EditarMateria" align="center" id="EditarMateria" onclick="updatesubject()" >Editar Materia</button>
                                </td>
                                
                                <td> <!-- buton de Generar reportes de matriculas -->
                                    <button type="button" style="height: 100px; width: 100px" name="ReportedeMaterias" align="center" id="ReportedeMaterias" onclick="reportsubject()" >Reportes de Materias</button>
                                </td>
                                
                            </tr>
                            <tr>
                                <td align="center" colspan="3"> <!-- buton de Generar reportes de matriculas -->
                                    <button type="button" style="height: 100px; width: 200px" name="AsignarProfesorMateria" align="center" id="AsignarProfesorMateria" onclick="assignteachersubject()" >Asignar Profesor - Materias</button>
                                </td>
                            </tr>
                            
                        </table>
                        </br>
                        <a href="menu.php">Regresar</a>
                    </form>
                </div>
    <?php
            }
            else
            {
                echo "You need to be authenticated as an admin to see the content of this page";
                echo "</br>";
                echo "<a href='logout.php'>Log In</a>";
            }
        }       
    ?>
</body>
