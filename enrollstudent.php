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

        //this function checks if the input has a number or acentuated characters, if it does returns true.
        function hasnumbers(str)
        {    
            var val = /^[A-Za-z ]+$/;
            if(val.test(str))
            {
                return false;
            }
            else
            {
                return true; 
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

        function SearchStudent()
        {
            var studentid = document.getElementById("studentid").value.replace(/^\s+|\s+$/g, '');
            if (studentid < 0 || studentid.length < 6)
            {
                alert("El codigo solo debe contener digitos (min 6) y no puede ser un numero negativo");
                //this will make sure the form won't submit if the alert comes up.
                document.getElementById("studentid").value = "";
                
            }
            else
            {
                var enrollstudentform = document.getElementById ("enrollstudent");
                enrollstudentform.action = "enrollstudent.php";
                enrollstudentform.submit();
            }
        }
        
        function saveStudent()
        {
            
            //values of all html components
            var enrollstudentform = document.getElementById ("enrollstudent");
            
            var newstudentid = document.getElementById("newstudentid").value;
            
            var fnames = document.getElementById("fnames").value;
            fnames = DoTrimRemoveReturns(fnames);
            fnames = fnames.toUpperCase();
            
            var lnames = document.getElementById("lnames").value;
            lnames = DoTrimRemoveReturns(lnames);
            lnames = lnames.toUpperCase();
            
            var ddidtype = document.getElementById("ddidtype");
            var ddidtypevalue = ddidtype.options[ddidtype.selectedIndex].value;
            
            var nalid = document.getElementById("nalid").value;
            nalid = nalid.toUpperCase();

            var cityid = document.getElementById("cityid").value;
            cityid = DoTrimRemoveReturns(cityid);
            cityid = cityid.toUpperCase();
            
            var sex = "";
            var sexf = document.getElementById("sexf");
            var sexm = document.getElementById("sexm");
            
            var newdbCurso = document.getElementById("newdbCurso")
            var newdbCursoid = newdbCurso.options[newdbCurso.selectedIndex].value;
            var newdbCursotext = newdbCurso.options[newdbCurso.selectedIndex].text;
            
            var date_i = document.getElementById("date_i").value;
            
            //check each required component
            
            if (fnames.length == 0 || hasnumbers(fnames) == true)
            {
                alert("El nombre no puede estar vacio, no puede contener numeros ni caracteres acentuados");
                return false; // return to exit out of the function.
            }
            
            if (lnames.length == 0 || hasnumbers(lnames) == true)
            {
                alert("El apellido no puede estar vacio, no puede contener numeros ni caracteres acentuados");
                return false; // return to exit out of the function.
            }
            
            if (ddidtypevalue == 0)
            {
                alert("Escoja un tipo de documento");
                return false; // return to exit out of the function.
            }
            
            if (nalid.length == 0)
            {
                alert("El numero de identificacion no puede estar vacio");
                return false; // return to exit out of the function.
            }
            
            if (cityid.length == 0 || hasnumbers(cityid) == true)
            {
                alert("La ciudad de expedicion no puede estar vacia, no puede contener numeros ni caracteres acentuados");
                return false; // return to exit out of the function.
            }
            
            if (!sexf.checked && !sexm.checked)
            {
                alert("Selecione el sexo del estudiante");
                return false; // return to exit out of the function.
            }
            else
            {
                if(sexf.checked)
                {
                    sex = sexf.value;
                    document.getElementById("sex").value = sexf.value;
                }
                else if(sexm.checked)
                {
                    sex = sexm.value;
                    document.getElementById("sex").value = sexm.value;
                }
            }
            
            if (newdbCursoid == 0)
            {
                alert("Selecione un curso");
                return false; // return to exit out of the function.
            }
            
            if (baddate(date_i) == true)
            {
                alert("la fecha no puede estar vacia y debe ser de la forma YYYY-MM-DD");
                return false; // return to exit out of the function.
            }
            var message = "";
            
            message += "Codigo: " + newstudentid + "\n";
            message += "Nombres: " + fnames + "\n";
            message += "Apellidos: " + lnames + "\n";
            message += "Tipo de Id.: " + ddidtypevalue + "\n";
            message += "identificacion: " + nalid + "\n";
            message += "Ciudad de Exp.: " + cityid + "\n";
            message += "Sexo: " + sex + "\n";
            message += "Curso: " + newdbCursotext + "\n";
            message += "Fecha Ingreso: " + date_i + "\n";
            
            var response=confirm("Desea guardar el estudiante con la informacion ingresada: \n" + message);
            
            if (response == true)
            {   
                document.getElementById("newstudentid").value = newstudentid;
                document.getElementById("fnames").value = fnames;
                document.getElementById("lnames").value = lnames;
                document.getElementById("nalid").value = nalid;
                document.getElementById("cityid").value = cityid;
                enrollstudentform.action = "savestudent.php";
                enrollstudentform.submit();
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
        
            echo "You need to be authenticated  and be an admin to see the content of this page";
            echo "</br>";
            echo "<a href='logout.php'>Log In</a>";
            
        }
        
        if(isset($_SESSION['currentUser']) && $role = 'd')
        {
            
    ?>
            <div align="center">
                <form name='enrollstudent' id='enrollstudent' method='post'>
                
                    <h3>Matricular Estudiante en el Sistema</h3>
                        
                        <a href="matriculas.php">Regresar</a>
                        </br></br>
                        <table align="center" border="1">
                            <tr>
                                <td>
                                    Codigo
                                </td>
                                <td align="center">
                                    Ingrese el codigo del estudiante</br>
                                    *el codigo es asignado por administracion</br>
                                    <input type="number" name="studentid" id="studentid"> </br>
                                    <button onClick="SearchStudent()">Buscar Codigo</button>
                                </td>
                            </tr>
                        </table>
                        </br>
                    <?php
                        
                        if(trim($_POST['studentid']) !== "")
                        {
                            include("config-CRDB.php");
                            
                            if (!$link) 
                            {
                                die('Could not connect: ' . mysql_error());
                                echo "something wrong with the link to the database";
                            }
                            else //if connection is good...
                            {
                                $st_id = mysql_real_escape_string($_POST['studentid']);
                                
                                $sqlselect =  "SELECT CONCAT(`Student First`,' ',`Student Last`) AS name, `Class ID`, date_i, date_r, status FROM `Student` WHERE `Student ID` = '$st_id';"; 
                                
                                $qryrecordset = mysql_query($sqlselect);
                                
                                $row = mysql_fetch_array($qryrecordset); 
                                
                                $nombre = $row['name'];
                                $class_id = $row['Class ID'];
                                $date_i = $row['date_i'];
                                $date_r = $row['date_r'];
                                $status = $row['status'];
                                
                                if (isset($nombre) and strlen($nombre) > 0)
                                {
                                   //the id appears on the database
                                    ?>
                                    <table>
                                        <tr>
                                            <td colspan="2" align="center">
                                                <b>Lo sentimos, un alumno con ese codigo ya existe!</b></br>
                                                Codigo: <?php echo $st_id; ?></br>
                                                Nombre: <?php echo $nombre; ?></br>
                                                Codigo Curso: <?php echo $class_id; ?></br>
                                                Fecha Ingreso: <?php echo $date_i; ?></br>
                                                Fecha Retiro: <?php echo $date_r; ?></br>
                                                Estado: <?php echo $status; ?></br>
                                                </br>
                                                Si el codigo del alumno reportado es incorrecto</br>
                                                reporte esto a administracion.
                                            </td>
                                        </tr>
                                    </table>
                                    <?php 
                                }
                                else
                                {
                                    //the id  DOES NOT appear on the database
                                    //so allow user to continue entering info for the new student
                                    ?>
                                    <table border="1">
                                        <tr>
                                            <td>
                                                Codigo a utilizar:
                                            </td>
                                            <td align="center">
                                                <input type="text" name="newstudentid" id="newstudentid" value="<?php echo $st_id; ?>" readonly></br>
                                                <input type="hidden" name="updateorsavestudent" id="updateorsavestudent" value="save"/>
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
                                                Identificacion
                                            </td>
                                            <td>
                                                Tipo de identificacion:</br>
                                                <select name='ddidtype' id='ddidtype'>
                                                    <option value="0">Seleccione identificacion</option>
                                                    <option value="T.I.">T.I.</option>
                                                    <option value="C.C.">C.C.</option>
                                                    <option value="R.C.">R.C.</option>
                                                    <option value="C.E.">C.E.</option>
                                                    <option value="NP">NP</option>
                                                    <option value="NUIP">NUIP</option>
                                                    <option value="SED">SED</option>
                                                    <option value="CCabildo">CCabildo</option>
                                                </select>
                                                </br>
                                                Numero de Identificacion:</br>
                                                <input type="text" name="nalid" id="nalid"></br>
                                                Ciudad de Expedicion:</br>
                                                <input type="text" name="cityid" id="cityid">
                                            </td>
                                        </tr> 
                                        <tr>
                                            <td>
                                                Sexo
                                            </td>
                                            <td>
                                                <input type="radio" name="sexo" id="sexm" value="M">Masculino
                                                <br>
                                                <input type="radio" name="sexo" id="sexf" value="F">Femenino
                                                <input type="hidden" name="sex" id="sex" value=""></input>
                                            </td>
                                        </tr>
                                        <tr> 
                                            <td>
                                                Curso
                                            </td>
                                            <td>
                                                <select name='newdbCurso' id='newdbCurso'>
                                                    <option value="0">Seleccione el Curso</option>
                                                    <?php
                                                        
                                                        $sql =  "SELECT `Class ID`,`Class` ".
                                                                "FROM `Class`";
                                                        
                                                        $recordset = mysql_query($sql) or die("error in Query: ". mysql_error());
                                                        
                                                        if (!$link) 
                                                        {
                                                            die('Could not connect: ' . mysql_error());
                                                            echo "something wrong with the link to the database";
                                                        }
                                                        else //if connection is good...
                                                        {
                                                            while ($row = mysql_fetch_array($recordset)) 
                                                            {
                                                                echo "<option value='" . $row['Class ID'] . "'>" . $row['Class'] . "</option>";
                                                            }
                                                        }
                                                    ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Ingreso
                                            </td> 
                                            <td>
                                                 Fecha en la cual el alumno se matricula:</br>
                                                 *unico formato valido (YYYY-MM-DD)
                                                 <input type="date" name="date_i" id="date_i"/></br>
                                            </td>   
                                            
                                        </tr>
                                        
                                    </table>
                                    </br>
                                    </br>
                                    <button onClick="return saveStudent()">Guardar Alumno</button>
                                    </br>
                                    </br>
                                    <a href="matriculas.php">Regresar</a>
                                    <?php 
                                    
                                }
                            }
                        }
                    ?>
                                              
                        
                   
                </form>

            </div>
    
    <?php
        }
    ?>
    
</body>