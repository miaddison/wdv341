<?php
	$displayMsg = "";
try{
	//Get the Event data from the server.
	require 'dbConnectPDO.php'; // connect to the database
	$event_id = "";
	$event_name = "";
	$event_description = "";
	$event_presenter = "";
	$event_day = "";
	$event_time = "";
	
	$sql = "SELECT event_id,event_name,event_description,event_presenter,DATE_FORMAT(event_day,'%m/%d/%Y') AS event_day,DATE_FORMAT( event_time,'%l:%i %p' )AS event_time FROM miaddison_wdv.wdv341_events";
	//$sql = "SELECT event_id,event_name,event_description,event_presenter,DATE_FORMAT(event_day,'%m/%d/%Y') AS event_day,event_time FROM wdv341.wdv341_events";
	
	$query = $conn->prepare($sql);
	$query->bindParam(':today',$today_date);
	$query->execute();

	
}catch(PDOException $e)
{
	$displayMsg .= "<h3>There has been a problem. The system administrator has been contacted. Please try again later.</h3>";
			
	error_log($e->getMessage());			//Delivers a developer defined error message to the PHP log file at c:\xampp/php\logs\php_error_log
	error_log(var_dump(debug_backtrace()));								
}


?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>WDV341 Intro PHP  - Display Events Example</title>
    <style>
		.eventBlock{
			width:500px;
			margin-left:auto;
			margin-right:auto;
			background-color:#CCC;	
		}
		
		.displayEvent{
			text_align:left;
			font-size:18px;	
		}
		
		.displayDescription {
			margin-left:100px;
		}
	</style>
</head>

<body>
    <h1>WDV341 Intro PHP</h1>
    <?php echo $displayMsg ?>
    <h2>Example Code - Display Events as formatted output blocks</h2>   
    <h3> <?php echo $query->rowCount(); ?> Events are available today.</h3>

<?php
	//Display each row as formatted output
	while( $row = $query->fetch(PDO::FETCH_ASSOC) )		
	//Turn each row of the result into an associative array 
  	{
		//For each row you have in the array create a block of formatted text
?>
	<p>
        <div class="eventBlock">	
            <div>
            	<span class="displayEvent">Event: <?php echo $event_name = $row['event_name'];?></span>
            	<span class="displayDescription">Description: <?php echo $event_description = $row['event_description'];?></span>
            </div>
            <div>
            	Presenter: <?php echo $event_presenter = $row['event_presenter'];?>
            </div>
            <div>
            	<span class="displayTime">Time: <?php echo $event_time = $row['event_time'];?></span>
            </div>
            <div>
            	<span class="displayDate">Date: <?php echo $event_day = $row['event_day'];?></span>
            </div>
        </div>
    </p>

<?php
  	}//close while loop
	$query=null;
	$conn=null;	//Close the database connection	
?>
</div>	
</body>
</html>