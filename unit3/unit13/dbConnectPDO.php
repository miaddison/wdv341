<?php

//$serverName = "localhost";
$username = "miaddison_wdv";	//database/username from control panel
//$username = "admin";
$password = "wdvpassword";	//database password	from control panel for the 
//$password = "password";
$database = "wdv_event";	//name of database/username from control panel
//$database = "wdv341";	

try{
	$conn = new PDO("mysql:host = $serverName;dbname = $database", $username, $password);
	// set the pDO error mode to exception
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		echo "Connected successfully\n";
}
catch(PDOException $e){
	echo "Connection failed: ".$e->getMessage();
}


?>