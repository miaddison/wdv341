<?php

//The following section of PHP acts as the Controller.  It contains the processing logic
//needed to gather the data from the database table.  Format the data into a presentation
//format that can be viewed on the client's browser.
	$displayMsg = "";
	$pref2Valid = false;
	$pref3Valid = false;
	$pref4Valid = false;
	
try
{
	include 'connectPDO.php';				//connects to the database
	
	//$sql = "SELECT cust_pref1, count(*) as count1 FROM wdv341.time_preferences GROUP BY cust_pref1 ORDER by 2 DESC";	
	$sql = "SELECT cust_pref1, count(*) as count1 FROM miaddison_wdv.time_preferences GROUP BY cust_pref1 ORDER by 2 DESC";	
	//build the SQL command	for customer preference 1 count
	
	//$sql2 = "SELECT cust_pref2, count(*) as count2 FROM wdv341.time_preferences GROUP BY cust_pref2 ORDER by 2 DESC";	
	$sql2 = "SELECT cust_pref2, count(*) as count2 FROM miaddison_wdv.time_preferences GROUP BY cust_pref2 ORDER by 2 DESC";
	//build the SQL command	for customer preference 2 count
	
	//$sql3 = "SELECT cust_pref3, count(*) as count3 FROM wdv341.time_preferences GROUP BY cust_pref3 ORDER by 2 DESC";	
	$sql3 = "SELECT cust_pref3, count(*) as count3 FROM miaddison_wdv.time_preferences GROUP BY cust_pref3 ORDER by 2 DESC";
	//build the SQL command	for customer preference 3 count
	
	//$sql4 = "SELECT cust_pref4, count(*) as count4 FROM wdv341.time_preferences GROUP BY cust_pref4 ORDER by 2 DESC";	
	$sql4 = "SELECT cust_pref4, count(*) as count4 FROM miaddison_wdv.time_preferences GROUP BY cust_pref4 ORDER by 2 DESC";
	//build the SQL command	for customer preference 4 count	


	//PREPARE the SQL statements
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	
	$stmt2 = $conn->prepare($sql2);
	$stmt2->execute();
	
	$stmt3 = $conn->prepare($sql3);
	$stmt3->execute();
	
	$stmt4 = $conn->prepare($sql4);
	$stmt4->execute();

	if($stmt) // test that query was made
	{
		//process the result
		if ($stmt->rowCount() > 0) 
		{
			//$displayMsg = "<h3>Number of rows is " . $stmt->rowCount() . "</h3>";	
			$displayMsg .= "<table class='tableFormat'>";
			$displayMsg .= "<tr>";
			$displayMsg .= "<th>Customer Preference 1</th>";
			//$displayMsg .= "<th>Count  </th>";
			$displayMsg .= "<th>Customer Preference 2</th>";
			//$displayMsg .= "<th>Count   </th>";
			$displayMsg .= "<th>Customer Preference 3</th>";
			//$displayMsg .= "<th>Count   </th>";
			$displayMsg .= "<th>Customer Preference 4</th>";
			//$displayMsg .= "<th>count   </th>";
			$displayMsg .= "</tr>";
				
				//while($row1 = $stmt->fetch(PDO::FETCH_ASSOC)){
					$row1 = $stmt->fetch(PDO::FETCH_ASSOC);
					$row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
					$row3 = $stmt3->fetch(PDO::FETCH_ASSOC);
					$row4 = $stmt4->fetch(PDO::FETCH_ASSOC);
					$displayMsg .= "<tr><td>";
					$displayMsg .= $row1["cust_pref1"];
					$pref1 = $row1["cust_pref1"]; //
					$displayMsg .= "</td><td>";
					//$displayMsg .= $row1["count1"];
					//$displayMsg .= "</td><td>";
					
					// If the first choice of cust_pref2 is different from cust_pref1, set it as pref2
					// otherwise go to next row of cust_pref2 and repeat until new choice found
					do{
						//compare to previously chosen preferences
						if($row2["cust_pref2"]!=$pref1){
							$displayMsg .= $row2["cust_pref2"];
							$displayMsg .= "</td><td>";
							//$displayMsg .= $row2["count2"];
							//$displayMsg .= "</td><td>";
							$pref2 = $row2["cust_pref2"];
							$pref2Valid = true; // flag pref2 as found
						}else{
							// advance to next row if current preference already chosen
							$row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
						}
					}while (!$pref2Valid);
					
					// If the first choice of cust_pref3 is different from cust_pref1, or cust_pref2 set it as pref3
					// otherwise go to next row of cust_pref3 and repeat until new choice found
					do{
						//compare to previously chosen preferences
						if($row3["cust_pref3"]!=$pref1 && $row3["cust_pref3"]!=$pref2){
							$displayMsg .= $row3["cust_pref3"];
							$displayMsg .= "</td><td>";
							//$displayMsg .= $row3["count3"];
							//$displayMsg .= "</td><td>";
							$pref3 = $row3["cust_pref3"]; 
							$pref3Valid = true; // flag pref3 as found
						}else{
							// advance to next row if current preference already chosen
							$row3 = $stmt3->fetch(PDO::FETCH_ASSOC);
						}
					}while (!$pref3Valid);
					
					// If the first choice of cust_pref3 is different from cust_pref1, or cust_pref2, or cust_pref3 set it as pref3
					// otherwise go to next row of cust_pref3 and repeat until new choice found
					do{
						//compare to previously chosen preferences
						if($row4["cust_pref4"]!=$pref1 && $row4["cust_pref4"]!=$pref2 && $row4["cust_pref4"]!=$pref3){
							$displayMsg .= $row4["cust_pref4"];
							//$displayMsg .= "</td><td>";
							//$displayMsg .= $row4["count4"];
							//$displayMsg .= "</td>";
							$pref4 = $row4["cust_pref4"];
							$pref4Valid = true;	// flag pref4 as found
						}else{
							// advance to next row if current preference already chosen
							$row4 = $stmt4->fetch(PDO::FETCH_ASSOC);
						}
					}while(!$pref4Valid);
			
					$displayMsg .= "</td></tr><br>";
			//}
			
			$displayMsg .= "</table>";
		} 
		else 
		{
			// if no rows returned
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
	<title>Paired Programming surveyFormReport.php</title>
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
	<h1>Paired Programming surveyFormReport.php</h1>
	<h2>The following is the order for customer preference.</h2>
	<div id="content">
		<?php echo $displayMsg; ?>
	</div>
</body>
</html>

