<?php
    session_start();  //session  
?>

<!DOCTYPE html>
    
<head>
    <meta http-equiv="Content-Type" content="text/html"> <!-- charset=utf-8 will make accents show on page but will fuckup if accents come from database-->
    
    <script type="text/javascript" charset="utf-8">
        
        function reloadtogetclassid(form)
        {
            var cursoid=form.dbCurso.options[form.dbCurso.options.selectedIndex].value;
            self.location='preupdatestudent.php?dbCurso=' + cursoid ;
        }
        
        function getstudentidfromdropdown(form)
        {
            
            var studenid=form.dbAlumno.options[form.dbAlumno.options.selectedIndex].value;
            document.getElementById("studentid").value = studenid;
            
        }
        
        //this function cleans input for free text
        function DoTrimRemoveReturns(text)
        {
            text = text.replace(/[\s\t\n\r]/g,' '); //replace white, tabs, carriage returns for a space in any part of the string
            text = text.replace(/^\s\s*/, '').replace(/\s\s*$/, ''); // remove spaces at each end of the string
            text = text.replace(/\s+/g," "); //this replaces multiple spaces for only one space.
            return text; //return string fixed
        }

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
        //this function checks if the input has a number, if it does returns true.
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
                var enrollstudentform = document.getElementById ("preupdatestudent");
                enrollstudentform.action = "preupdatestudent.php";
                enrollstudentform.submit();
            }
        }

        function saveStudent()
        {
            
            //values of all html components
            var preupdatestudentform = document.getElementById ("preupdatestudent");
            
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

            var date_r = document.getElementById("date_r").value;

            var status = document.getElementById("status");
            var statusvalue = status.options[status.selectedIndex].value;

            var approved = document.getElementById("approved");
            var approvedvalue = approved.options[approved.selectedIndex].value;
            var approvedtext = approved.options[approved.selectedIndex].text;
            
            //check each required component
            
            if (fnames.length == 0 || hasnumbers(fnames) == true)
            {
                alert("El nombre no puede estar vacio ni puede contener numeros");
                return false; // return to exit out of the function.
            }
            
            if (lnames.length == 0 || hasnumbers(lnames) == true)
            {
                alert("El apellido no puede estar vacio ni puede contener numeros");
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
                alert("La ciudad de expedicion no puede estar vacia ni puede contener numeros");
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
                alert("Selecione una fecha de ingreso o reintegro en el formato correcto YYYY-MM-DD");
                return false; // return to exit out of the function.
            }

            if (statusvalue != "A" && baddate(date_r) == true)
            {
                alert("Si el alumno es retirado o desescolarizado es necesario que selecione una fecha de retiro o desescolarizacion y asegurese de que la fecha este en el formato correcto YYYY-MM-DD.");
                return false; // return to exit out of the function.
            }

            if ((baddate(date_r) == false) && (baddate(date_i) == false))
            {
                var date_re = new Date(date_r).getTime();
                var date_in = new Date(date_i).getTime();
                //alert("this is happening" + date_c + "and" + date_o);

                if (date_re < date_in)
                {
                    alert("la fecha de retiro/desescolarizacion no puede ser antes de la fecha de ingreso/reintegro");
                    return false; // return to exit out of the function.
                }
            }

            if (statusvalue == "A" && (baddate(date_r) == false || date_r.length > 0))
            {
                alert("Si el alumno esta activo no puede haber una fecha de retiro o desescolarizacion.");
                return false; // return to exit out of the function.
            }

            if (statusvalue == 0)
            {
                alert("Seleccione si el alumno aprobo o no aprobo el curso actual");
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
            message += "Fecha Ingreso/Reintegro: " + date_i + "\n";
            
            if (date_r != 0)
            {
                message += "Fecha Retiro/Desescolarizacion: " + date_r + "\n";
            }
            if (statusvalue != 0)
            {
                message += "Estado: " + statusvalue + "\n";
            }
            
            message += "Aprobo grado: " + approvedtext + "\n";
            
            var response=confirm("Desea guardar los cambios con la informacion ingresada: \n" + message);
            
            if (response == true)
            {   
                document.getElementById("newstudentid").value = newstudentid;
                document.getElementById("fnames").value = fnames;
                document.getElementById("lnames").value = lnames;
                document.getElementById("nalid").value = nalid;
                document.getElementById("cityid").value = cityid;
                preupdatestudentform.action = "savestudent.php";
                preupdatestudentform.submit();
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
            
            //variable to get the curso id if selected
            $dbCursoid=$_GET['dbCurso'];
            
            include("config-CRDB.php"); //including config.php in our file to connect to the database
            
            echo "Bienvenido(a) ".$currentUser;
            echo "</br>";
            echo "<a href='logout.php'>Logout</a>";
        }
        else
        {
        
            echo "You need to be authenticated and have and admin profile to see the content of this page";
            echo "</br>";
            echo "<a href='logout.php'>Log In</a>";
            
        }
        
        if(isset($_SESSION['currentUser']) && $role = 'd') // if the super global variable session called current user has been initialized then
        {   //and show the following 
    ?>
            <div align="center">
                <form name='preupdatestudent' id='preupdatestudent' method='post'>
                
                    <h3>Actualizar Estudiante</h3>
                        
                        <a href="matriculas.php">Regresar</a>
                        </br></br>
                        <table align="center" border="1">
                            
                            <tr>
                                
                                <td>
                                    Buscar Alumno
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
                                                    //show what was selected after the form is submitted      
                                                        if ($row['Class ID'] ==  $dbCursoid)
                                                        {// then adds that as one of the options in the dropdown
                                                            echo "<option value='" . $row['Class ID'] . "' selected>" . $row['Class'] . "</option>";
                                                        }
                                                    // then adds that as one of the options in the dropdown
                                                        else
                                                        {
                                                            echo "<option value='" . $row['Class ID'] . "'>" . $row['Class'] . "</option>";
                                                        }
                                                }
                                            }
                                        ?>
                                    </select>
                                    
                                    Alumnos:
                                    <select name='dbAlumno' id='dbAlumno' onchange="getstudentidfromdropdown(this.form)">
                                        <option value="0">Seleccione el Alumno</option>
                                        <?php
                                            
                                            if(isset($dbCursoid) and strlen($dbCursoid) > 0)
                                            {
                                                $sql =  "SELECT `Student ID`,CONCAT(`Student First`,' ',`Student Last`) AS name,`Class ID`, status ".
                                                            "FROM `Student`" .
                                                            "WHERE `Class ID` = $dbCursoid ".
                                                            "ORDER BY status, `Student Last`;";
                                                
                                            }
                                            
                                            
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
                                                    // then adds that as one of the options in the dropdown
                                                    echo "<option value='" . $row['Student ID'] . "'>" . $row['name'] . " (". $row['status'] .  ")</option>";
                                                }
                                            }
                                        ?>
                                    </select>
                                    </br>
                                    </br>
                                    
                                    Codigo:</br>
                                    <input type="text" name="studentid" id="studentid"></br>
                                    <button onClick="SearchStudent()">Buscar Estudiante</button>
                                </td>
                                
                            </tr>      
                        </table>           
                                    <?php
                                    
                                        $st_id = "";
                                        $out ="";
                                        if(trim($_POST['studentid']) !== "")
                                        {
                                            $st_id = mysql_real_escape_string($_POST['studentid']);

                                            include("config-CRDB.php");
                                            
                                            if (!$link) 
                                            {
                                                die('Could not connect: ' . mysql_error());
                                                echo "something wrong with the link to the database";
                                            }
                                            else //if connection is good...
                                            {
                                                //this query should give us one line only if id is in the database

                                                $sqlselectResult =  "SELECT `Student ID` AS Codigo, `Student First` AS Nombres, ".
                                                                    "`Student Last` AS Apellidos, Gender AS Sexo, IDType AS TipoID, ".
                                                                    "Identification AS NumeroID, City as CiudadID, ".
                                                                    "Class.Class AS Curso, date_i As FechaIng, ".
                                                                    "date_r AS FechaRet, status AS Estado, Approved AS Aprobo ". 
                                                                    "FROM `Student` ". 
                                                                    "LEFT JOIN `Class` ".
                                                                    "ON Student.`Class ID` = Class.`Class ID` ".
                                                                    "WHERE `Student ID` = '$st_id';"; 
                                            
                                                $qryRSselectResult = mysql_query($sqlselectResult); //run query and get recordset
                                                
                                                $num_rows = mysql_num_rows($qryRSselectResult);
                                                
                                                if ($num_rows == 1)
                                                {
                                                    $line = mysql_fetch_assoc($qryRSselectResult); //put each reacord fetched into an associative array variable.
                                                        
                                                    $codigo = $line["Codigo"];
                                                    $nombres = $line["Nombres"];
                                                    $apellidos =$line["Apellidos"];
                                                    $sexo = $line["Sexo"];
                                                    $tipoID = $line["TipoID"];
                                                    $numeroID = $line["NumeroID"];
                                                    $ciudadID = $line["CiudadID"];
                                                    $curso = $line["Curso"];
                                                    $fechaIng = $line["FechaIng"];
                                                    $fechaRet = $line["FechaRet"];
                                                    $estado = $line["Estado"];
                                                    $aprobo = $line["Aprobo"];
                                                
                                    ?>       
                                                    </br>
                                                    <table border="1">
                                                        <tr>
                                                            <td>
                                                                Codigo del Alumno:
                                                            </td>
                                                            <td align="center">
                                                                <input type="text" name="newstudentid" id="newstudentid" value="<?php echo $codigo; ?>" readonly/></br>
                                                                <!-- the field below is used to confirm this is an update and not a new enroll in savestudent.php-->
                                                                <input type="hidden" name="updateorsavestudent" id="updateorsavestudent" value="update"/> 
                                                            </td>
                                                        </tr> 
                                                        <tr>
                                                            <td>
                                                                Nombre completo
                                                            </td>
                                                            <td>
                                                                Nombres:</br>
                                                                <input type="text" name="fnames" id="fnames" value="<?php echo $nombres; ?>"/></br>
                                                                Apellidos:</br>
                                                                <input type="text" name="lnames" id="lnames" value="<?php echo $apellidos; ?>"/>
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
                                                                    <option value="T.I." <?php echo '',($tipoID == "T.I." ? 'selected' : '');?>>T.I.</option>
                                                                    <option value="C.C." <?php echo '',($tipoID == "C.C." ? 'selected' : '');?>>C.C.</option>
                                                                    <option value="R.C." <?php echo '',($tipoID == "R.C." ? 'selected' : '');?>>R.C.</option>
                                                                    <option value="C.E." <?php echo '',($tipoID == "C.E." ? 'selected' : '');?>>C.E.</option>
                                                                    <option value="NP" <?php echo '',($tipoID == "NP" ? 'selected' : '');?>>NP</option>
                                                                    <option value="NUIP" <?php echo '',($tipoID == "NUIP" ? 'selected' : '');?>>NUIP</option>
                                                                    <option value="SED" <?php echo '',($tipoID == "SED" ? 'selected' : '');?>>SED</option>
                                                                    <option value="CCabildo" <?php echo '',($tipoID == "CCabildo" ? 'selected' : '');?>>CCabildo</option>
                                                                </select>
                                                                </br>
                                                                Numero de Identificacion:</br>
                                                                <input type="text" name="nalid" id="nalid" value="<?php echo $numeroID; ?>"/></br>
                                                                Ciudad de Expedicion:</br>
                                                                <input type="text" name="cityid" id="cityid" value="<?php echo $ciudadID; ?>"/>
                                                            </td>
                                                        </tr> 
                                                        <tr>
                                                            <td>
                                                                Sexo
                                                            </td>
                                                            <td>
                                                                <input type="radio" name="sexo" id="sexm" value="M" <?php echo '',($sexo == "M" ? 'checked' : '');?>>Masculino
                                                                <br>
                                                                <input type="radio" name="sexo" id="sexf" value="F" <?php echo '',($sexo == "F" ? 'checked' : '');?>>Femenino
                                                                <input type="hidden" name="sex" id="sex" value=""></input>
                                                            </td>
                                                        </tr>
                                                        <tr> 
                                                            <td>
                                                                Curso
                                                            </td>
                                                            <td>
                                                                Seleccione el curso al cual pertence el alumno:
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
                                                                                if ($row['Class'] == $curso)
                                                                                {
                                                                                    echo "<option value='" . $row['Class ID'] . "' selected>" . $row['Class'] . "</option>";
                                                                                }
                                                                                else
                                                                                {
                                                                                    echo "<option value='" . $row['Class ID'] . "'>" . $row['Class'] . "</option>";
                                                                                }
                                                                                
                                                                            }
                                                                        }
                                                                    ?>
                                                                </select>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                Fecha de Ingreso</br>
                                                                o reintegro
                                                            </td> 
                                                            <td>
                                                                 Indique la fecha en que el alumno se ingresa o reintegra:</br>
                                                                 *Unico formato valido: YYYY-MM-DD</br>
                                                                 <input type="date" name="date_i" id="date_i" value="<?php echo $fechaIng; ?>"/></br>
                                                            </td> 
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                Fecha de Retiro o</br>
                                                                desescolarizacion
                                                            </td> 
                                                            <td>
                                                                 Indique la fecha en que el alumno se retira:</br>
                                                                 *Unico formato valido: YYYY-MM-DD</br>
                                                                 <input type="date" name="date_r" id="date_r" value="<?php echo $fechaRet; ?>"/></br>
                                                            </td> 
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                Estado
                                                            </td> 
                                                            <td>
                                                                <select name='status' id='status'>
                                                                    <option value="0">Seleccione estado</option>
                                                                    <option value="A" <?php echo '',($estado == "A" ? 'selected' : '');?>>Activo</option>
                                                                    <option value="R" <?php echo '',($estado == "R" ? 'selected' : '');?>>Retirado</option>
                                                                    <option value="D" <?php echo '',($estado == "D" ? 'selected' : '');?>>Desescolarizado</option>
                                                                </select>
                                                            </td> 
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                Aprobo Grado
                                                            </td> 
                                                            <td>
                                                                <select name='approved' id='approved'>
                                                                    <option value="0">Seleccione una opcion</option>
                                                                    <option value="yes" <?php echo '',($aprobo == "yes" ? 'selected' : '');?>>Si</option>
                                                                    <option value="no" <?php echo '',($aprobo == "no" ? 'selected' : '');?>>No</option>
                                                                </select>
                                                            </td> 
                                                        </tr>
                                                    </table>
                                                    </br>
                                                    </br>
                                                    <button onClick="return saveStudent()">Guardar Cambios</button>
                                                    </br>
                                                    </br>

                                               

                                                
                                   <?php       
                                                } 
                                                else
                                                {
                                    ?>
                                                    <table>
                                                        <tr>
                                                            <td colspan="2" align="center">
                                                                <b>Lo sentimos, No existe un alumno con ese codigo!</b></br>
                                                                </br>
                                                                Si el codigo del alumno deberia existir</br>
                                                                reporte esto a administracion.
                                                            </td>
                                                        </tr>
                                                    </table>
                                    <?php 
                                                } 
                                            }
                                        }
                                    ?>
                            
                        </table>
                        
                        </br>
                        
                        <a href="matriculas.php">Regresar</a>
                        
                </form>

            </div>
    
    <?php

        }
    
    ?>
    
</body>