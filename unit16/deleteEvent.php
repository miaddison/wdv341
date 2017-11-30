<?php
session_cache_limiter('none');			//This prevents a Chrome error when using the back button to return to this page. May not still need this.
session_start();			// attaches page to current session
 	
	$message = "";

	if (isset($_SESSION['validUser']) && $_SESSION['validUser'] == "yes")				//is this already a valid user?
	{
		
		try{
			$message = "";
			$delete_event_id = $_GET['event_id']; // retrieve id from GET parameter

			require 'dbConnectPDO.php';				//connects to the database

			$sql = "DELETE FROM miaddison_wdv.wdv341_event WHERE event_id = :event_id";
			//$sql = "DELETE FROM wdv341.wdv341_event WHERE event_id = :event_id";

			//PREPARE the SQL statement
			$stmt = $conn->prepare($sql);
			$stmt->bindParam(':event_id',$delete_event_id);
			$stmt->execute();

			// test that query was made
			if($stmt){
				$message =  "<h2>Your record has been successfully deleted.</h2>";
				$message .= "<p>Please <a href='selectEvents.php'>view</a> your records.</p>";
			}
		}
		catch(PDOException $e){
			$message = "<h2>There has been a problem. The system administrator has been contacted. Please try again later.</h2>";

			error_log($e->getMessage());			//Delivers a developer definederror message to the PHP log file at c:\xampp/php\logs\php_error_log
			error_log(var_dump(debug_backtrace()));

			//Clean up any variables or connections that have been left hanging by this error.		

			//header('Location: files/505_error_response_page.php');	//sends control to a User friendly page					
		}
	}else{
		header('Location: login.php');
	}
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Create deleteEvent.php page</title>
</head>
<body>
	<h1>WDV341 Intro PHP</h1>
	<h2>Create deleteEvent.php page</h2>
	<h2>
		<?php echo $message; ?>
	</h2>
</body>
</html>