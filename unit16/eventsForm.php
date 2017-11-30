<!--
	Name: Merna Addison
	Email: merna.addison@gmail.com
	Date: 10/15/17
	Course: WDV341 Intro to PHP
-->
<?php
session_start(); // join current session equivalent to cookies in browser
//Only allow a valid user access to this page
if (isset($_SESSION['validUser']) && $_SESSION['validUser'] == "yes"){

	//Setup variables used by the page	
		// field and email data
		$event_name = "";
		$event_description = "";
		$event_presenter = "";
		$event_date = "";
		$event_time = "";
		$roboTest = "";
		$message = "";
		$update_event_id = "";

		// Error messages
		$event_name_Err = "";
		$event_description_Err = "";
		$event_presenter_Err = "";
		$event_date_Err = "";
		$event_time_Err = "";		
		
		// flags for sucessful form submission
		$validForm = false;
	
	
	if(isset($_POST["submit"]))
	{
		// the form has been sumitted and needs to be processed
		$event_name = $_POST['event_name'];
		$event_description = $_POST['event_description'];
		$event_presenter = $_POST['event_presenter'];
		$event_date = $_POST['event_date'];
		$event_time = $_POST['event_time'];
		$update_event_id = $_POST['update_event_id'];
		$roboTest = $_POST['robotest'];

		/*	FORM VALIDATION PLAN
		
			FIELD NAME			VALIDATION TESTS & VALID RESPONSES
			Event Name			Required Field		May not be empty
			
			Event Description	Optional
			Event Presenter		Optional
			
			Event Date			Required Field		Database Format
			Event Time			Required Field		Database Format
		*/
		
		// Validation Functions
		// Validate name - required and check HTML special characters
		function validateSanitizeEventName($event_name){
			global $validForm, $event_name_Err;	//Use the GLOBAL Version of these variables instead of making them local
			if($event_name==""){
				$event_name_Err = "*Event name is required";
				$validForm = false;
			}
			// Remove all illegal characters from name
			$sanitized_event_name = filter_var($event_name, FILTER_SANITIZE_STRING);
			return $sanitized_event_name;
		}
		
		// Validate Event Description - remove all illegal characters
		function validateSanitizeEventDescription($event_description){
			global $validForm, $event_description_Err;
			$sanitized_event_description = filter_var($event_description, FILTER_SANITIZE_STRING);
			return $sanitized_event_description;
		}
		
		// Validate Event Presenter
		function validateSanitizeEventPresenter($event_presenter){
			global $validForm, $event_presenter_Err;
			$sanitized_event_Presenter = filter_var($event_presenter, FILTER_SANITIZE_STRING);
			return $sanitized_event_Presenter;
		}
		
		// Validate Event in Future
		function isFutureDate($event_date){
			return(strtotime($event_date) > time());
		}
		
		// Validate Event Date is in Future
		function futureDate($event_date){
			$today = date('Y-m-d');
			global $event_date_Err;
			/*if($today > $event_date){
				return false;
			}else{
				return true;
			}*/
			 $event_date_Err = $today;
		}
		
		// Validate Event Date
		function validateDate($event_date){
			global $validForm, $event_date_Err;
			if($event_date==""){
				$event_date_Err = "*Event date is required";
				$validForm = false;
			}
			$event_date = date('Y-m-d',strtotime($event_date));
			if(!isFutureDate($event_date)){
				$event_date_Err = "*Please select a date in the future";
				$validForm = false;
			}else{
				return $event_date;
			}
		}
		
		// Validate Event Time
		function validateTime($event_time){
			global $validForm, $event_time_Err;
			if($event_time==""){
				$event_time_Err = "*Event time is required";
				$validForm = false;
			}
			$event_time = date('H:i:s',strtotime($event_time));
			return $event_time;
		}
		
		// validate not robot
		if($roboTest){
			$message = "*No bots allowed! Email not sent!";
			$validForm = false;
		}
		
		// Set form valid and run methods to check if anything is invalid
		$validForm = true;
		$event_name = validateSanitizeEventName($event_name); 
		$event_description = validateSanitizeEventDescription($event_description);
		$event_presenter = validateSanitizeEventPresenter($event_presenter);
		$event_date = validateDate($event_date);
		$event_time = validateTime($event_time);
		
		
		
		if($validForm)
		{
			$message = "All good";
			try 
			{
				
				require 'dbConnectPDO.php'; // connect to the database
	
				/*INSERT INTO `wdv341_event` (`event_id`, `event_name`, `event_description`, `event_presenter`, `event_date`, `event_time`) VALUES (NULL, 'name', 'description', 'presenter', '2017-10-26', '07:00:00')	*/
				//Create the SQL command string
				if($update_event_id!=""){
					//$sql = "UPDATE wdv341.wdv341_event SET";
					$sql = "UPDATE miaddison_wdv.wdv341_event SET";
					$sql .= " event_name=:eventName,";
					$sql .= " event_description=:eventDescription,";
					$sql .= " event_presenter=:eventPresenter,";
					$sql .= " event_date=:eventDate,";
					$sql .= " event_time=:eventTime";
					$sql .=" WHERE event_id=:update_event_id";
					//echo($sql); // testing
					//PREPARE the SQL statement
					$stmt = $conn->prepare($sql);

					//if(!$stmt){
					//	echo"\nPDO::errorInfo():\n";
					//	print_r($conn->errorInfo());
					//}

					//BIND the values to the input parameters of the prepared statement
					$stmt->bindParam(':eventName', $event_name);
					$stmt->bindParam(':eventDescription', $event_description);		
					$stmt->bindParam(':eventPresenter', $event_presenter);		
					$stmt->bindParam(':eventDate', $event_date);		
					$stmt->bindParam(':eventTime', $event_time);
					$stmt->bindParam(':update_event_id', $update_event_id);
					
					$stmt->execute();	

					$message = "<h2>Your event has been updated.</h2>";
					$message .= "<h2>Please <a href='selectEvents.php'>view</a> your records.</h2>";
					
				}else{
					//$sql = "INSERT INTO wdv341.wdv341_event(";
					$sql = "INSERT INTO miaddison_wdv.wdv341_event(";
					$sql .= "event_name, ";					
					$sql .= " event_description, ";
					$sql .= " event_presenter, ";
					$sql .= " event_date, ";
					$sql .= " event_time"; //Last column does NOT have a comma after it.
					$sql .= ") VALUES (:eventName, :eventDescription, :eventPresenter, :eventDate, :eventTime)";
					
					//PREPARE the SQL statement
					$stmt = $conn->prepare($sql);

					//if(!$stmt){
					//	echo"\nPDO::errorInfo():\n";
					//	print_r($conn->errorInfo());
					//}

					//BIND the values to the input parameters of the prepared statement
					$stmt->bindParam(':eventName', $event_name);
					$stmt->bindParam(':eventDescription', $event_description);		
					$stmt->bindParam(':eventPresenter', $event_presenter);		
					$stmt->bindParam(':eventDate', $event_date);		
					$stmt->bindParam(':eventTime', $event_time);

					//EXECUTE the prepared statement
					$stmt->execute();	

					$message = "Your event has been added.";
					$message .= "<h2>Please <a href='selectEvents.php'>view</a> your records.</h2>";
				
				}
				
				
				//$conn->null;
			}
			
			catch(PDOException $e)
			{
				$message = "There has been a problem. The system administrator has been contacted. Please try again later.";
				
				error_log($e->getMessage());			//Delivers a developer defined error message to the PHP log file at c:\xampp/php\logs\php_error_log
				
				//error_log($e->errorInfo());
				error_log($e->getMessage());
				//error_log($conn->errorCode());
				//error_log($conn->connect_error);
				error_log(var_dump(debug_backtrace()));
				
				//Clean up any variables or connections that have been left hanging by this error.		
			
				//header('Location: files/505_error_response_page.php');	//sends control to a User friendly page					
			}
			finally
			{
				$conn = null;
			}
		}
		else
		{
			$message = "Something went wrong";
		}//end check for valid form
		
	}
	else
	{
		try{
			//user has not seen form yet
			$update_event_id=$_GET['event_id'];
			require 'dbConnectPDO.php'; // connect to the database
			$sql = "SELECT event_id,event_name,event_description,event_presenter,event_date,event_time FROM miaddison_wdv.wdv341_event WHERE event_id=$update_event_id";
			//$sql = "SELECT event_id,event_name,event_description,event_presenter,event_date,event_time FROM wdv341.wdv341_event WHERE event_id=:update_event_id";		//build the SQL command	

			//PREPARE the SQL statement
			$stmt = $conn->prepare($sql);

			$stmt->bindParam(':update_event_id', $update_event_id);

			$stmt->execute();

			if($stmt) // test that query was made
			{
				while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
					$event_name = $row['event_name'];
					$event_description = $row['event_description'];
					$event_presenter = $row['event_presenter'];
					$event_date = $row['event_date'];
					$event_time = $row['event_time'];
					$update_event_id = $row['event_id'];
				}
			}else{
				$message = "No record found";
			}
		}
		catch(PDOException $e)
		{
			$message = "There has been a problem. The system administrator has been contacted. Please try again later.";
				
			error_log($e->getMessage());			//Delivers a developer defined error message to the PHP log file at c:\xampp/php\logs\php_error_log
				
			//error_log($e->errorInfo());
			error_log($e->getMessage());
			//error_log($conn->errorCode());
			//error_log($conn->connect_error);
			error_log(var_dump(debug_backtrace()));
								
			//Clean up any variables or connections that have been left hanging by this error.	
			
			//header('Location: files/505_error_response_page.php');	
			//sends control to a User friendly page					
		}
		finally
		{
			$conn = null;
		}		
	}
}else{
	header('Location: login.php');
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>WDV341 Intro PHP - Create a form page for the events table</title>
<script>
	function resetForm(){
		document.getElementById('event_name').value = "";
		document.getElementById('event_description').value = "";
		document.getElementById('event_presenter').value = "";
		document.getElementById('event_date').value = "";
		document.getElementById('event_time').value = "";
	}
</script>

</head>

<body>
<h1>WDV341 Intro PHP</h1>
<h2>Create a form page for the events table</h2>
<?php
    //If the form was submitted and valid and properly put into database display the INSERT result message
	if($validForm)
	{
?>
		  <h1><?php echo $message?></h1>
		  
		  <!--Testing outputs>
		  <!--p>
		  Event Name:
		  <!--?php echo $event_name ?><br>
		  Event Description:
		  <!--?php echo $event_description ?><br>
		  Event Presenter:
		  <!--?php echo $event_presenter ?><br>
		  Event Date: 
		  <!--?php echo $event_date ?><br>
		  Event Time: 
		  <!--?php echo $event_time ?><br>
		  SQL: 
		  <!--?php echo $sql ?></p-->
		  
		  
<?php
	}
	else	//display form
	{
?>
		<h2><?php echo $message?></h2>
		<form name="eventForm" method="post" action="eventsForm.php">
		  <p>&nbsp;</p>
		  <p>
			<label>Event Name:
				<input type="text" name="event_name" id="event_name" value="<?php echo $event_name ?>"><span class="error" style="color:red; padding-left:2em"><?php echo $event_name_Err ?></span>
			</label>
		  </p>
		  <p>Event Description: 
			  <textarea name="event_description" id="event_description" cols="45" rows="5"><?php echo $event_description ?></textarea><span class="error" style="color:red; padding-left:2em"><?php echo $event_description_Err ?></span>
		  </p>
		  <p>
			<label>Event Presenter:
				<input type="text" name="event_presenter" id="event_presenter" value="<?php echo $event_presenter ?>"><span class="error" style="color:red; padding-left:2em"><?php echo $event_presenter_Err ?></span>
			</label>
		  </p>
		  <p>
			<label>Event Date:
				<input type="date" name="event_date" id="event_date" value="<?php  echo $event_date ?>"><span class="error" style="color:red; padding-left:2em"><?php echo $event_date_Err ?></span>
			</label>
		  </p>
		  <p>
			<label>Event Time:
				<input type="time" name="event_time" id="event_time" step = "1" value="<?php  echo $event_time ?>"><span class="error" style="color:red; padding-left:2em"><?php echo $event_time_Err ?></span>
			</label>
		  </p>
		  <p>
			<input type="hidden" name="update_event_id" id="update_event_id" value="<?php echo $update_event_id;?>">
		  </p>
		  <p>
			<input type="hidden" name="robotest" id="robotest">
		  </p>
		  <p>
			<input type="submit" name="submit" id="submit" value="Submit">
			<input type="button" name="button2" id="button2" value="Reset" 
			 onClick = resetForm()>
		  </p>
		</form>
<?php
	} // end else
?>
<p>&nbsp;</p>
</body>
</html>
