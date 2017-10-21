<?php

//The following section of PHP acts as the Controller.  It contains the processing logic
//needed to gather the data from the database table.  Format the data into a presentation
//format that can be viewed on the client's browser.


	include 'dbConnectOOP.php';				//connects to the database
	
	$sql = "SELECT event_name,event_description FROM wdv341_events";		//build the SQL command	

	$res = $link->query($sql);	//run the query
	
	if($res)
	{
		//process the result
		if ($res->num_rows > 0) 
		{
			$displayMsg = "<h1>Number of rows is " . $res->num_rows . "</h1>";			
			// output data of each row
			
			$displayMsg .= "<table class='tableFormat'>";
			while($row = $res->fetch_assoc()) 
			{
				//echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
				$displayMsg .= "<tr><td>";
				$displayMsg .= $row["event_name"];
				$displayMsg .= "</td><td>";
				$dipslayMsg .= $row["event_desc"];
				$displayMsg .= "</td></tr>";
			}
			//$displayMsg = "Number of rows is " . $res->num_rows;
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
		$displayMsg .= "<p>" . mysqli_error($link) . "</p>";			//Display error message
	}
	$link->close();
	
	
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

