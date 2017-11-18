<?php

//The following section of PHP acts as the Controller.  It contains the processing logic
//needed to gather the data from the database table.  Format the data into a presentation
//format that can be viewed on the client's browser.
	$displayMsg = "";
	
try
{
	include 'dbConnectPDO.php';				//connects to the database
	$sql = "SELECT event_id,event_name,event_description,event_presenter,event_date,event_time FROM miaddison_wdv.wdv341_event";
	//$sql = "SELECT event_id,event_name,event_description,event_presenter,event_date,event_time FROM wdv341.wdv341_event";	//build the SQL command	

	//PREPARE the SQL statement
	$stmt = $conn->prepare($sql);
	$stmt->execute();

	if($stmt) // test that query was made
	{
		//process the result
		if ($stmt->rowCount() > 0) 
		{
			$displayMsg = "<h3>Number of rows is " . $stmt->rowCount() . "</h3>";	
			$displayMsg .= "<table>";
			$displayMsg .= "<tr>";
			$displayMsg .= "<th>Event Name</th>";
			$displayMsg .= "<th>Event Description</th>";
			$displayMsg .= "<th>Presenter</th>";
			$displayMsg .= "<th>Date</th>";
			$displayMsg .= "<th>Time</th>";
			$displayMsg .= "<th>Update</th>";
			$displayMsg .= "<th>Delete</th>";
			$displayMsg .= "</tr>";
			
			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$displayMsg .= "<tr><td>";
				$displayMsg .= $row["event_name"];
				$displayMsg .= "</td><td>";
				$displayMsg .= $row["event_description"];
				$displayMsg .= "</td><td>";
				$displayMsg .= $row["event_presenter"];
				$displayMsg .= "</td><td>";
				$displayMsg .= $row["event_date"];
				$displayMsg .= "</td><td>";
				$displayMsg .= $row["event_time"];
				$displayMsg .= "</td><td>";
				$event_id = $row["event_id"];
				$displayMsg .= "<a href='eventsForm.php?event_id=$event_id'>  Update  </a>";
				$displayMsg .= "</td><td>";
				$displayMsg .= "<a href='deleteEvent.php?event_id=$event_id'>  Delete  </a>";
  				$displayMsg .= "</td></tr><br>";
			}
			
			$displayMsg .= "</table>";
		} 
		else 
		{
			$displayMsg .= "0 results";
		}		
	}
	else
	{
		//display error message for DEVELOPMENT purposes
		$displayMsg .= "<h3>Sorry there has been a problem</h3>";
		//$displayMsg .= "<p>" . mysqli_error($con) . "</p>";			//Display error message
	}
}
catch(PDOException $e)
{
	$displayMsg .= "<h3>There has been a problem. The system administrator has been contacted. Please try again later.</h3>";
			
	error_log($e->getMessage());			//Delivers a developer defined error message to the PHP log file at c:\xampp/php\logs\php_error_log
	error_log(var_dump(debug_backtrace()));								
}
	
	
	
//The following HTML or markup is the VIEW.  This will be sent to the client for display in their browser.
//Notice that we echo the $displayMsg variable which contains the formatted output that we created in the 
//Controller area above.  	
?>
<html>
<head>
	<title>Modify selectEvents.php</title>
	<style>
		table,th,td {
   			border: 1px solid black;
			text-align: center;	
		}
		th, td{
			padding: .5em;
		}
	</style>
</head>
<body>
	<h1>Modify selectEvents.php</h1>
	<h2>We found the following information.</h2>
		<?php echo $displayMsg; ?>
</body>
</html>

