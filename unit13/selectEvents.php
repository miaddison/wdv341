<?php

//The following section of PHP acts as the Controller.  It contains the processing logic
//needed to gather the data from the database table.  Format the data into a presentation
//format that can be viewed on the client's browser.
	$displayMsg = "";

	include 'dbConnectPDO.php';				//connects to the database
	$sql = "SELECT event_name,event_description,event_presenter,event_date,event_time FROM miaddison_wdv.wdv341_event";
	//$sql = "SELECT event_name,event_description,event_presenter,event_date,event_time FROM wdv341.wdv341_event";		//build the SQL command	

	//PREPARE the SQL statement
	$stmt = $conn->prepare($sql);
	$stmt->execute();

	if($stmt) // test that query was made
	{
		//process the result
		if ($stmt->rowCount() > 0) 
		{
			$displayMsg = "<h1>Number of rows is " . $stmt->rowCount() . "</h1>";			
			// output data of each row
			
			$displayMsg .= "<table class='tableFormat'>";
			while($row = $stmt->fetch(PDO::FETCH_ASSOC)) 
			{
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
				$displayMsg .= "</td></tr><br>";
			}
			
			//$event_date->format('m-d-Y'); 
        	//$event_time->format('g:i a T');
				
			//$displayMsg = "Number of rows is " . $stmt->num_rows;
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
	
	
	
//The following HTML or markup is the VIEW.  This will be sent to the client for display in their browser.
//Notice that we echo the $displayMsg variable which contains the formatted output that we created in the 
//Controller area above.  	
?>
<html>
<head>
	<title>WDV341 SELECT Example</title>

</head>
<body>
	<h1>We found the following information.</h1>
	<div id="content">
		<?php echo $displayMsg; ?>
	</div>

</body>
</html>

