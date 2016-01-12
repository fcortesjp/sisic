<?php
    session_start();  //session  
?>

<!DOCTYPE html>
    
<head>
    <meta http-equiv="Content-Type" content="text/html"> <!-- charset=utf-8 will make accents show on page but will fuckup if accents come from database-->
    
    <script type="text/javascript" charset="utf-8">
    
        function InsertObserv() 
        {
            var menuForm = document.getElementById ("observador");
            menuForm.action = "insertobservation.php";
            menuForm.submit();
        }
        
        function UpdateObserv()
        {
            var menuForm = document.getElementById ("observador");
            menuForm.action = "preupdateobservation.php";
            menuForm.submit();
        }
        function PrintObservStudent()
        {
            var menuForm = document.getElementById ("observador");
            menuForm.action = "printobservationstudent.php";
            menuForm.submit();
        }
        
        function PrintObservClass()
        {
            var menuForm = document.getElementById ("observador");
            menuForm.action = "printobservationclass.php";
            menuForm.submit(); 
        }
        function PrintObservSchool()
        {
            var menuForm = document.getElementById ("observador");
            menuForm.action = "printobservationschool.php";
            menuForm.submit(); 
        }
        function PrintObservUser()
        {
            var menuForm = document.getElementById ("observador");
            menuForm.action = "printobservationuser.php";
            menuForm.submit(); 
        }
        function ListObservUser()
        {
            var menuForm = document.getElementById ("observador");
            menuForm.action = "listobservationuser.php";
            menuForm.submit();
        }
        function ListObserv()
        {
            var menuForm = document.getElementById ("observador");
            menuForm.action = "listobservation.php";
            menuForm.submit();
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
            
            //menu to display variable
            
            $menu ="";
            
            include("config-CRDB.php"); //including config.php in our file to connect to the database
            
            //get the subjects user/teacher is in charge of or if role is d (director) get all the classes
            
            if ($role == 'd') 
            {
                //if user logged is a director set this variable for themenu to display
                
                $menu = 'directormenu';
                
            } 
            else 
            {
                //if user logged is teacher set this variable for the menu to display
                
                $menu = 'teachermenu';
            }
        
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
    
        <form name='observador' id='observador' method='post'>
        
            <h3>Observador del Alumno</h3>
            </br>
            <a href="menu.php">Regresar</a>
            </br>    
            <?php if (isset($_SESSION['currentUser']) && $menu == 'directormenu') : ?>
                
                    
                <table align="center" border="0">
                    <tr>
                        <td colspan="2">
                            Insertar/Actualizar Observaciones
                        </td>
                    </tr>
                    <tr>
                        
                        <td> <!-- buton de insertar observacion -->
                            <button type="button" style="height: 100px; width: 100px" name="InsertarObservacion" align="center" id="InsertarObservacion" onclick="InsertObserv()" >Insertar Observacion</button>
                        </td>
                        
                        <td> <!-- buton de actualizar observacion -->
                            <button type="button" style="height: 100px; width: 100px" name="ActualizarObservacion" align="center" id="ActualizarObservacion" onclick="UpdateObserv()" >Actualizar Observacion</button>
                        </td>
                    </tr>
                </table>
                
                 <table align="center" border="0">
                    <tr>
                        <td colspan="4" align="center">
                            Imprimir Observaciones
                        </td>
                    </tr>
                    <tr>
                        <td> <!-- buton de Imprimir observacion por alumno -->
                            <button type="button" style="height: 100px; width: 100px" name="ImprimirObservacion" align="center" id="ImprimirObservacion" onclick="PrintObservStudent()" >Imprimir Observacion Alumno</button>
                        </td>
                        
                        <td> <!-- buton de Imprimir observacion por curso-->
                            <button type="button" style="height: 100px; width: 100px" name="ImprimirObservacionCurso" align="center" id="ImprimirObservacionCurso" onclick="PrintObservClass()" >Imprimir Observacion Curso</button>
                        </td>
                        
                        <td> <!-- buton de Imprimir observacion para todo el colegio-->
                            <button type="button" style="height: 100px; width: 100px" name="ImprimirObservacionSchool" align="center" id="ImprimirObservacionSchool" onclick="PrintObservSchool()" >Imprimir Observacion Colegio</button>
                        </td>
                        
                        <td> <!-- buton de Imprimir observacion por un usuario especifico-->
                            <button type="button" style="height: 100px; width: 100px" name="ImprimirObservacionUser" align="center" id="ImprimirObservacionUser" onclick="PrintObservUser()" >Imprimir Observacion de un Usuario</button>
                        </td>
                    </tr>
                 </table>
                 
                 <table align="center" border="0">
                    <tr>
                        <td align="center">
                            Enlistar Observaciones Hechas por el usuario
                        </td>
                    </tr>
                    <tr>
                        
                        <td align="center"> <!-- buton de ver observaciones hechas por el usuario logeado-->
                            <button type="button" style="height: 100px; width: 100px" name="ListObservacion" align="center" id="ListObservacion" onclick="ListObservUser()" >Enlistar Observaciones</button>
                        </td>
                       
                    </tr>
                  
                </table>
                </br>
                
                <a href="menu.php">regresar</a>        
        
            
            <?php elseif (isset($_SESSION['currentUser']) && $menu == 'teachermenu') :  ?>
                
                 <table align="center" border="0">
                    <tr>
                        <td colspan="2" align="center">
                            Insertar/Actualizar Observaciones
                        </td>
                    </tr>
                    <tr>
                        <td> <!-- buton de insertar observacion -->
                            <button type="button" style="height: 100px; width: 100px" name="InsertarObservacion" align="center" id="InsertarObservacion" onclick="InsertObserv()" >Insertar Observacion</button>
                        </td>   
                        
                         <td> <!-- buton de actualizar observacion -->
                            <button type="button" style="height: 100px; width: 100px" name="ActualizarObservacion" align="center" id="ActualizarObservacion" onclick="UpdateObserv()" >Actualizar Observacion</button>
                        </td>
                    </tr>
                </table>
                 <table align="center" border="0">
                    <tr>
                        <td colspan="2" align="center">
                            Enlistar Observaciones Hechas por el usuario
                        </td>
                    </tr>
                    <tr>
                        <td align="center"> <!-- buton de ver observaciones hechas por el usuario logeado -->
                            <button type="button" style="height: 100px; width: 100px" name="ListObservacion" align="center" id="ListObservacion" onclick="ListObserv()" >Enlistar Observaciones</button>
                        </td>
                    </tr>
                </table>
                
                </br>
                
                <a href="menu.php">Regresar</a>
            <?php endif; ?>
        </form>
    </div>
</body>
