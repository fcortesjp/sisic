<?php
    session_start();  //session  
?>

<!DOCTYPE html>
    
<head>
    <meta http-equiv="Content-Type" content="text/html"> <!-- charset=utf-8 will make accents show on page but will fuckup if accents come from database-->
    
    <script type="text/javascript" charset="utf-8">
        
        //this function cleans input for free text
        
        function DoTrimRemoveReturns(text)
        {
            text = text.replace(/[\s\t\n\r]/g,' '); //replace white, tabs, carriage returns for a space in any part of the string
            text = text.replace(/^\s\s*/, '').replace(/\s\s*$/, ''); // remove spaces at each end of the string
            text = text.replace(/\s+/g," "); //this replaces multiple spaces for only one space.
            return text; //return string fixed
        }
        
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

        function totitlecase(str)
        {
            
            return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
            
        }
        
        //this function checks if the input has a number or acentuated characters, if it does returns true.
        function hasnumbers(str)
        {    
            var val = /^[A-Za-záéíóúñ ]+$/;
            if(val.test(str))
            {
                return false;
            }
            else
            {
                return true; 
            }   
        }

        function passgood(str)
        {
            var val = /^[A-Za-z\d]+$/;
            if(val.test(str))
            {
                return true;
            }
            else
            {
                return false; 
            }   
        }

        function usergood(str)
        {
            var val = /^[a-z]+$/;
            if(val.test(str))
            {
                return true;
            }
            else
            {
                return false; 
            }   
        }
        //this function checks if date has the wrong format (not YYYY-MM-DD) if wrong format it returns true
        function baddate(date)
        {
            var format = 
/^((19|20)\d\d)\-((0[123456789])|(1[0-2]))\-((0[123456789])|(1[0123456789])|(2[0123456789])|(3[0-1]))$/;
            if(format.test(date))
            {
                return false;
            }
            else
            {
                return true; 
            }  

        }
        
        
        function SearchTeacher(form)
        {
            var enrollteacherform = document.getElementById ("enrollteacherform");
            var teachercc = document.getElementById("teachercc").value.replace(/^\s+|\s+$/g, '');
            if (onlynumbers(teachercc) == false || teachercc.length == 0 || teachercc < 0)
            {
                alert("La cedula solo puede contener numeros, sin espacios u otros caracteres y no puede ser un campo en blanco");
                //this will make sure the form won't submit if the alert comes up.
                document.getElementById("teachercc").value = "";
                
            }
            else
            {
                enrollteacherform.action = "enrollteacher.php";
                enrollteacherform.submit();
            }
        }
        
        function saveTeacher()
        {
            
            //values of all html components
            var enrollteacherform = document.getElementById ("enrollteacherform");
            
            var newteachercc = document.getElementById("newteachercc").value;
            
            var fnames = document.getElementById("fnames").value;
            fnames = DoTrimRemoveReturns(fnames);
            fnames = totitlecase(fnames);

            
            var lnames = document.getElementById("lnames").value;
            lnames = DoTrimRemoveReturns(lnames);
            lnames = totitlecase(lnames);
            
            var un = document.getElementById("un").value;
            un = DoTrimRemoveReturns(un);
            var pw = document.getElementById("pw").value;
            pw = DoTrimRemoveReturns(pw);


            var tdate_i = document.getElementById("tdate_i").value;
            
            //check each required component
            
            if (fnames.length == 0 || hasnumbers(fnames) == true)
            {
                alert("El nombre no puede estar vacio y no puede contener numeros");
                return false; // return to exit out of the function.
            }
            
            if (lnames.length == 0 || hasnumbers(lnames) == true)
            {
                alert("El apellido no puede estar vacio y no puede contener numeros");
                return false; // return to exit out of the function.
            }
            
            if (un.length == 0 || usergood(un) == false)
            {
                alert("hay un problema con el usuario que escogio");
                return false; // return to exit out of the function.
            }

            if (pw.length == 0 || passgood(pw) == false)
            {
                alert("hay un problema con la clave que escogio");
                return false; // return to exit out of the function.
            }

            if (baddate(tdate_i) == true)
            {
                alert("la fecha no puede estar vacia y debe ser de la forma YYYY-MM-DD");
                return false; // return to exit out of the function.
            }
            
            var message = "";
            
            message += "Cedula: " + newteachercc + "\n";
            message += "Nombres: " + fnames + "\n";
            message += "Apellidos: " + lnames + "\n";
            message += "Usuario: " + un + "\n";
            message += "Clave: " + pw + "\n";
            message += "Fecha Ingreso: " + tdate_i + "\n";
            
            var response=confirm("Desea guardar el profesor con la informacion ingresada: \n" + message);
            
            if (response == true)
            {   
                
                document.getElementById("fnames").value = fnames;
                document.getElementById("lnames").value = lnames;
                document.getElementById("un").value = un;
                document.getElementById("pw").value = pw;
                enrollteacherform.action = "saveteacher.php";
                enrollteacherform.submit();
            }
            else
            {
                return false;
            }
            
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
            
            echo "Bienvenido(a) ".$currentUser;
            echo "</br>";
            echo "<a href='logout.php'>Logout</a>";
        }
        else
        {
        
            echo "You need to be authenticated and be an admin to see the content of this page";
            echo "</br>";
            echo "<a href='logout.php'>Log In</a>";
            
        }
        
        if(isset($_SESSION['currentUser']) && $role = 'd')
        {
            
    ?>
            <div align="center">
                <form name='enrollteacherform' id='enrollteacherform' method='post'>
                
                    <h3>Ingresar Profesor en el Sistema</h3>
                        
                        <a href="profesores.php">Regresar</a>
                        </br></br>
                        <table align="center" border="1">
                            <tr>
                                <td>
                                    Cedula
                                </td>
                                <td align="center">
                                    Ingrese la cedula del profesor</br>
                                    </br>
                                    <input type="number" name="teachercc" id="teachercc"> </br>
                                    <button onClick="return SearchTeacher(this.form)">Buscar Cedula</button>
                                </td>
                            </tr>
                        </table>
                        </br>
                    <?php
                        
                        if(trim($_POST['teachercc']) !== "")
                        {
                            //holds the conn variable: a pdo implementation to connect to the database
                            //safely
                            include("config_sisic.php");
                            
                            //parametrized query
                            $sqlselect =    "SELECT CONCAT(`Teacher Name`,' ',`Teacher Last`) AS name, `user`, tdate_i, tdate_r, tstatus ".
                                            "FROM `Teacher` ".
                                            "WHERE `tidentification` = :cedula"; 

                            //parameter to check against database in the where statement.
                            $t_cc = mysql_real_escape_string($_POST['teachercc']);

                            //global variable taht will contain a row if query gets a hit.
                            $row="";

                            //variables to store fields from row retrieved.
                            $nombre = "";
                            $username = "";
                            $tdate_i = "";
                            $tdate_r = "";
                            $tstatus = "";

                            //this section prepares select statament and launches query
                            try 
                            {
                                
                                $statement = $conn->prepare($sqlselect);
                                $statement->execute(array(':cedula' => $t_cc));
                                $row = $statement->fetch(); 

                                $nombre = $row['name'];
                                $username = $row['user'];
                                $tdate_i = $row['tdate_i'];
                                $tdate_r = $row['tdate_r'];
                                $tstatus = $row['tstatus'];

                            } catch(PDOException $ex) {
                                echo "An Error occured!"; //handle me.
                            }
                                
                                
                            if (isset($nombre) and strlen($nombre) > 0)
                            {
                               //the cc appears on the database
                                ?>
                                <table>
                                    <tr>
                                        <td colspan="2" align="center">
                                            <b>Lo sentimos, un profesor con esa cedula ya existe!</b></br>
                                            Cedula: <?php echo $t_cc; ?></br>
                                            Nombre: <?php echo $nombre; ?></br>
                                            usuario: <?php echo $username; ?></br>
                                            Fecha Ingreso: <?php echo $tdate_i; ?></br>
                                            Fecha Retiro: <?php echo $tdate_r; ?></br>
                                            Estado: <?php echo $tstatus; ?></br>
                                            </br>
                                            Si el cedula no corresponde al profesor reportado</br>
                                            reporte esto a administracion.
                                        </td>
                                    </tr>
                                </table>
                        <?php 
                            }
                            else
                            {
                                //the cc  DOES NOT appear on the database
                                //so allow user to continue entering info for the new teacher
                                ?>
                                <table border="1">
                                    <tr>
                                        <td>
                                            Cedula a utilizar:
                                        </td>
                                        <td align="center">
                                            <input type="text" name="newteachercc" id="newteachercc" value="<?php echo $t_cc; ?>" readonly></br>
                                            <input type="hidden" name="updateorsave" id="updateorsave" value="save">
                                        </td>
                                    </tr> 
                                    <tr>
                                        <td>
                                            Nombre completo
                                        </td>
                                        <td>
                                            Nombres:</br>
                                            <input type="text" name="fnames" id="fnames"></br>
                                            Apellidos:</br>
                                            <input type="text" name="lnames" id="lnames">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Usuario y clave
                                        </td>
                                        <td>
                                            Usuario:</br>
                                            Este campo es por convencion la primera letra del</br>
                                            primer nombre y el primer apellido</br>
                                            <input type="text" name="un" id="un"></br>
                                            Clave:</br>
                                            <input type="text" name="pw" id="pw"></br>
                                        </td>
                                    </tr>      
                                    <tr>
                                        <td>
                                            Ingreso
                                        </td> 
                                        <td>
                                             Fecha en la cual el profesor es contratado:</br>
                                             *unico formato valido (YYYY-MM-DD)</br>
                                             <input type="date" name="tdate_i" id="tdate_i"/></br>
                                        </td>   
                                        
                                    </tr>
                                    
                                </table>
                                </br>
                                </br>
                                <button onClick="return saveTeacher()">Guardar Profesor</button>
                                </br>
                                </br>
                                <a href="profesores.php">Regresar</a>
                                <?php 
                                
                            }
                            
                        }
                    ?>
                                              
                        
                   
                </form>

            </div>
    
    <?php
        }
    ?>
    
</body>