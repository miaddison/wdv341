<?php
$serverName = "localhost";
$username = "admin";
$password = "password";
$database = "wdv341";
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