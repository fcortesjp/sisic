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
        function PrintObserv()
        {
            var menuForm = document.getElementById ("observador");
            menuForm.action = "printobservation.php";
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
                
            <?php if (isset($_SESSION['currentUser']) && $menu == 'directormenu') : ?>
                
                
                <table align="center" border="0">
                    
                    <tr>
                        
                        <td> <!-- buton de insertar observacion -->
                            <button type="button" style="height: 100px; width: 100px" name="InsertarObservacion" align="center" id="InsertarObservacion" onclick="InsertObserv()" >Insertar Observacion</button>
                        </td>
                        
                        <td> <!-- buton de actualizar observacion -->
                            <button type="button" style="height: 100px; width: 100px" name="ActualizarObservacion" align="center" id="ActualizarObservacion" onclick="UpdateObserv()" >Actualizar Observacion</button>
                        </td>
                        
                        <td> <!-- buton de Imprimir observacion -->
                            <button type="button" style="height: 100px; width: 100px" name="ImprimirObservacion" align="center" id="ImprimirObservacion" onclick="PrintObserv()" >Imprimbir Observacion</button>
                        </td>
                        
                    </tr>
                  
                </table>
                </br>
                
                <a href="menu.php">regresar</a>        
        
            
            <?php elseif (isset($_SESSION['currentUser']) && $menu == 'teachermenu') :  ?>
                
                <table align="center" border="0">
                    
                    <td> <!-- buton de insertar observacion -->
                        <button type="button" style="height: 100px; width: 100px" name="InsertarObservacion" align="center" id="InsertarObservacion" onclick="InsertObserv()" >Insertar Observacion</button>
                    </td>   
                    
                     <td> <!-- buton de actualizar observacion -->
                        <button type="button" style="height: 100px; width: 100px" name="ActualizarObservacion" align="center" id="ActualizarObservacion" onclick="UpdateObserv()" >Actualizar Observacion</button>
                    </td>
                </table>
                
                </br>
                
                <a href="menu.php">regresar</a>
            <?php endif; ?>
        </form>
    </div>
</body>
