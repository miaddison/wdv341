<?php
$serverName = "localhost";
//$username = "miaddison_meals";	//database/username from control panel
$username = "admin";
//$password = "password";	//database password	from control panel for the 
$password = "password";
//$database = "miaddison_meals";
$database = "meals";	//name of database/username from control panel

try{
	$conn = new PDO("mysql:host = $serverName;dbname = $database", $username, $password);
	// set the pDO error mode to exception
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		//echo "Connected successfully\n";
}
catch(PDOException $e){
	echo "Connection failed: ".$e->getMessage();
}


?>