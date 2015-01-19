<?php 
	session_start(); // code to start session
	include("config-CRDB.php"); //including config.php in our file
	$username = mysql_real_escape_string($_POST["userName"]); //Storing username in $username variable.
	$password = mysql_real_escape_string($_POST["userPass"]); //Storing password in $password variable.
	$table = 'Teacher';
	
	if(! $link )
	{
	  	$msg = die('Could not connect: ' . mysql_error());
		echo "<script>alert('$msg')</script>";					
	}
	else 
	{
		
		$match = 	"SELECT `Teacher Name`, ID, role ".
					"FROM $table ".
					"WHERE user = '$username' ".
					"AND pass = '$password';";
		
		$qry = mysql_query($match);// or die("something went wrong with this error ". mysql_error()); //run the mysql query looking for a match on the user/pass entered.
		
		$record = mysql_fetch_array($qry); //get the recordset
		
		
		$num_rows = mysql_num_rows($qry); //get the number of rows with a match
		
		if ($num_rows <= 0) //if there's no rows with a match
		{ 
			//tell user that there's no match
			echo "Sorry, Invalid username / password combination";
			echo "</br>";
        	echo "<a href='logout.php'>Intente de nuevo</a>";
			
			exit;
	
		} 
		else 
		{ // if a user was found then...then save some session variables : teacher name and teacher id
		
			$teacher = $record['Teacher Name']; // get the field for teacher name
			$teacherID = $record['ID']; // get the field for the teacher id
			$role = $record['role']; // get the field for the role
			
			$_SESSION['currentUser'] = $teacher;   //save the current user as the username that logged in the session variable called currentUser
			$_SESSION['currentID'] = $teacherID;
			$_SESSION['role'] = $role;
			//echo "<script>alert('$teacher and $teacherID')</script>";
			
			
			header("location:menu.php"); // admin.php is the page where you want to redirect user after login.
			 
			 
		}
	}
?>
