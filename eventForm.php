<!--
	Name: Merna Addison
	Email: merna.addison@gmail.com
	Date: 10/15/17
	Course: WDV341 Intro to PHP
-->
<?php
session_start(); // join current session equivalent to cookies in browser
//Only allow a valid user access to this page
if ($_SESSION['validUser'] !== "yes") {
	header('Location: index.php'); // if not valid user redirect to homepage
}
	//Setup variables used by the page	
		// field and email data
		$event_name = "";
		$event_description = "";
		$event_presenter = "";
		$event_date = "";
		$event_time = "";
		$roboTest = "";
		$message = "";

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
		function validateEventName($event_name){
			global $validForm, $event_name_Err;	//Use the GLOBAL Version of these variables instead of making them local
			if($event_name==""){
				$event_name_Err = "*Event name is required";
				$validForm = false;
			}
			// Remove all illegal characters from name
			$event_name = filter_var($event_name, FILTER_SANITIZE_STRING);
			if($event_name === false){ 
				$event_name_Err = "*That is not a valid entry";
				$validForm = false;
			}
		}
		
		// Validate Event Description - remove all illegal characters
		function validateEventDescription($event_description){
			global $validForm, $event_description_Err;
			$event_description = filter_var($event_description, FILTER_SANITIZE_STRING);
			if($event_description === false){
				$event_description_Err = "*That is not a valid entry";
				$validForm = false;
			}
		}
		
		// Validate Event Presenter
		function validateEventPresenter($event_presenter){
			global $validForm, $event_presenter_Err;
			$event_description = filter_var($event_presenter, FILTER_SANITIZE_STRING);
			if($event_presenter === false){
				$event_presenter_Err = "*That is not a valid entry";
				$validForm = false;
			}
		}
		
		// Validate Event Date
		function validateDate($event_date){
			global $validForm, $event_date_Err;
			
		}
		
		// Validate Event Time
		function validateDate($event_time){
			global $validForm, $event_time_Err;
			
		}
		
		// validate not robot
		if($roboTest){
			$message = "*No bots allowed! Email not sent!";
			$validForm = false;
	
		
		// Set form valid and run methods to check if anything is invalid
		$validForm = true;
		validateEventName($event_name);
		validateEventDescription($event_description);
		validateEventPresenter($event_presenter);
		//validateDate($event_date);
		//validateDate($event_time);
		
		if($validForm){
			$message = "All good";
			try {
				
				
				//mysql DATE stores data in a YYYY-MM-DD format
				//$todaysDate = date("Y-m-d");		//use today's date as the default input to the date( )
				
				INSERT INTO `wdv341_event`(`event_id`, `event_name`, `event_description`, `event_presenter`, `event_date`, `event_time`) VALUES ([value-1],[value-2],[value-3],[value-4],[value-5],[value-6])
					
				//Create the SQL command string
				$sql = "INSERT INTO wdv341_event (";
				$sql .= "event_name, ";
				$sql .= "event_description, ";
				$sql .= "event_presenter, ";
				$sql .= "event_date, ";
				$sql .= "event_time "; //Last column does NOT have a comma after it.
				$sql .= ") VALUES (:eventName, :eventDescription, :eventPresenter, :eventDate, :eventTime)";
				
				//PREPARE the SQL statement
				$stmt = $conn->prepare($sql);
				
				//BIND the values to the input parameters of the prepared statement
				$stmt->bindParam(':eventName', $event_name);
				$stmt->bindParam(':eventDescription', $event_description);		
				$stmt->bindParam(':eventPresenter', $event_presenter);		
				$stmt->bindParam(':eventDate', $event_date);		
				$stmt->bindParam(':eventTime', $event_time);	
				
				//EXECUTE the prepared statement
				$stmt->execute();	
				
				$message = "Your event has been added.";
			}
			
			catch(PDOException $e)
			{
				$message = "There has been a problem. The system administrator has been contacted. Please try again later.";
	
				error_log($e->getMessage());			//Delivers a developer defined error message to the PHP log file at c:\xampp/php\logs\php_error_log
				error_log(var_dump(debug_backtrace()));
			
				//Clean up any variables or connections that have been left hanging by this error.		
			
				header('Location: files/505_error_response_page.php');	//sends control to a User friendly page					
			}
		}
		
	}else{
		// Form has not been seen by the user. Display the form.
	}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>WDV341 Intro PHP - Programming Project - Contact Form</title>
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
<h2>Programming Project: PHP Contact Page with Validation</h2>
<?php
    //If the form was submitted and valid and properly put into database display the INSERT result message
	if($validForm)
	{
?>
		  <h1><?php echo $message?></h1>
		  
<?php
	}
	else	//display form
	{
?>
		<h2><?php echo $message?></h2>
		<form name="eventForm" method="post" action="eventForm.php">
		  <p>&nbsp;</p>
		  <p>
			<label>Event Name:
				<input type="text" name="event_name" id="event_name" value="<?php echo $event_name ?>"><span class="error" style="color:red; padding-left:2em"><?php echo $event_name_Err ?></span>
			</label>
		  </p>
		  <p>Event Description: 
			  <textare name="event_description" id="event_description" cols="45" rows="5"><?php echo $event_description ?></textare><span class="error" style="color:red; padding-left:2em"><?php echo $event_description_Err ?></span>
		  </p>
		  <p>
			<label>Event Presenter:
				<input type="text" name="event_presenter" id="event_presenter" value="<?php echo $event_name ?>"><span class="error" style="color:red; padding-left:2em"><?php echo $event_name_Err ?></span>
			</label>
		  </p>
		  <!--p>
			<label>
			  <input type="checkbox" name="mailingList" id="mailingList" checked>
			  Please put me on your mailing list.</label>
		  </p>
		  <p>
			<label>
			  <input type="checkbox" name="information" id="information" checked>
			  Send me more information</label>
		  about your products.  </p>
		  <p-->
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
