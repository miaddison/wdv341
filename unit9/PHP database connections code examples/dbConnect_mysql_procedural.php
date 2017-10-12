<?php
//	This file contains the PHP coding to connect to the database.
//
//	Include this file in any page that needs to access the database.
/*
	NOTE!!!
	This method has been deprecated as of PHP 5.5.0.  Use the mysqli commands
	This example is included to show you the way that PHP has used for years to connect to a database.
*/

$hostname = "localhost";		//the name of the website generally localhost
$username = "admin";		//the username that you use to sign into the account or database
$password = "password";		//the password of the account or database
$database = "wdv341";		//the name of the database.  It must be the full name.  Check your cPanel


//STEP #!  Build the connection object called $db.  This is opened doorway to the database
$db = mysql_connect($hostname, $username, $password);		//$db is the connection object

if ($db)													//If connection is successful continue, else!
	{ 
		//echo "<h1>Connection Successful</h1>"; 
	}
else
	{ 
		echo "<p style='color:red'>Connection Failed</p>"; 
		echo mysql_error();
	}

//STEP #2  Build the database object called db_selected.  This tells the rest of the mysql commands what database to use and where it is
$db_selected = mysql_select_db($database, $db);     		//uses the connection object from above.

if ($db_selected)											//If database is available continue, else!
	{ //echo "<h1>Database Selected</h1>"; 
	}
else
	{ 
		echo "<p style='color:red'>Database not found</p>"; 
		echo mysql_error();									//Will display the error log if process fails
	}


?>