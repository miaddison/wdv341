<!--
	Name: Merna Addison
	Email: merna.addison@gmail.com
	Date: 11/9/17
	Course: WDV341 Intro to PHP
-->
<?php
//session_start(); // join current session equivalent to cookies in browser
//Only allow a valid user access to this page
//if ($_SESSION['validUser'] !== "yes") {
//	header('Location: index.php'); // if not valid user redirect to homepage
//}
	//Setup variables used by the page	
		// field and email data
		$cust_email = "";
		$cust_pref1 = "";
		$cust_pref2 = "";
		$cust_pref3 = "";
		$cust_pref4 = "";
		$cust_pref1_msg = "";
		$cust_pref2_msg = "";
		$cust_pref3_msg = "";
		$cust_pref4_msg = "";
		$dateTime = new DateTime("now", new DateTimeZone('America/Chicago'));
		$cust_input_date = $dateTime->format('Y-m-d');;
		$roboTest = "";
		$message = "";

		// Error messages
		$cust_email_Err = "";
		$cust_pref1_Err = "";
		$cust_pref2_Err = "";
		$cust_pref3_Err = "";
		$cust_pref4_Err = "";
		
		// flags for sucessful form submission
		$validForm = false;

	if(isset($_POST["submit"]))
	{
		// the form has been sumitted and needs to be processed
	
		$cust_email = $_POST['cust_email'];
		$cust_pref1 = $_POST['cust_pref1'];
		$cust_pref2 = $_POST['cust_pref2'];
		$cust_pref3 = $_POST['cust_pref3'];
		$cust_pref4 = $_POST['cust_pref4'];
		$roboTest = $_POST['robotest'];

		/*	FORM VALIDATION PLAN
		
			FIELD NAME			VALIDATION TESTS & VALID RESPONSES
			Cust Email			Required Field 		May not be empty, proper format
			
			Cust Pref1			Requred Field		unique
			Cust Pref2			Requred Field		unique
			Cust Pref3			Requred Field		unique
			Cust Pref4			Requred Field		unique
	
		*/
		
		// Validation Functions
		// Validate Email - required and valid email format (use reg exp for format)
		function validateEmail($cust_email){
			global $validForm, $cust_email_Err;	//Use the GLOBAL Version of these variables instead of making them local
			if($cust_email==""){
				$cust_email_Err = "*Email is required";
				$validForm = false;
			}else if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$cust_email))		//Copied straight from W3Schools.  Uses a Regular Expression
  			{
				$cust_email = "";
  				$cust_email_err = "*Invalid email format"; 
				$validForm = false;
			}
		}
				
		//Validate preferencese
		function createPrefMsg($cust_pref){
			$cust_pref_msg = "";
			switch($cust_pref){
				case "mw":
					$cust_pref_msg = "Monday/Wednesday 10:00am-Noon";
					break;
				case "t":
					$cust_pref_msg = "Tuesday 6:00-9:00pm";
					break;
				case "w":						
					$cust_pref_msg = "Wednesday 6:00-9:00pm";
					break;
				case "tr":
					$cust_pref_msg = "Tuesday/Thursday 10:10am-Noon";
					break;
			}	
			return $cust_pref_msg;
		}
		//Validate preference not null unique
		function validatePref($cust_pref1,$cust_pref2,$cust_pref3,$cust_pref4){
			global $validForm, $cust_pref1_Err, $cust_pref2_Err, $cust_pref3_Err, $cust_pref4_Err;
			if($cust_pref1==="default"){
				$cust_pref1_Err = "*You must select an option";
				$validForm = false;
			}
			if(($cust_pref2==$cust_pref1) || ($cust_pref2=="default")){
				$cust_pref2_Err = "*You must select an option and it must be unique";
				$validForm = false;
			}
			if(($cust_pref3==$cust_pref2) || ($cust_pref3==$cust_pref1) || ($cust_pref3=="default")){
				$cust_pref3_Err = "*You must select an option and it must be unique";
				$validForm = false;
			}
			if(($cust_pref4==$cust_pref3) || ($cust_pref4==$cust_pref2) || ($cust_pref4==$cust_pref1) || ($cust_pref4=="default")){
				$cust_pref4_Err = "*You must select an option and it must be unique";
				$validForm = false;
			}
		}
		// validate not robot
		if($roboTest){
			$message = "*No bots allowed! Email not sent!";
			$validForm = false;
		}
		
		// Set form valid and run methods to check if anything is invalid
		$validForm = true;
		validateEmail($cust_email); 
		$cust_pref1_msg = createPrefMsg($cust_pref1);
		$cust_pref2_msg = createPrefMsg($cust_pref2);
		$cust_pref3_msg = createPrefMsg($cust_pref3);
		$cust_pref4_msg = createPrefMsg($cust_pref4);
		validatePref($cust_pref1, $cust_pref2, $cust_pref3, $cust_pref4);
		
		
		
		if($validForm)
		{
			$message = "All good";
			try 
			{
				require 'connectPDO.php'; // connect to the database
				//$sql = "INSERT INTO wdv341.time_preferences(";
				$sql = "INSERT INTO miaddison_wdv.time_preferences(";
				$sql .= "cust_email, ";
				$sql .= " cust_pref1, ";
				$sql .= " cust_pref2, ";
				$sql .= " cust_pref3, ";
				$sql .= " cust_pref4, ";
				$sql .= " cust_input_date ";
				$sql .= ") VALUES (:custEmail, :custPref1, :custPref2, :custPref3, :custPref4, :custInputDate)";
				
				//PREPARE the SQL statement
				$stmt = $conn->prepare($sql);
				
				//if(!$stmt){
				//	echo"\nPDO::errorInfo():\n";
				//	print_r($conn->errorInfo());
				//}
				
				//BIND the values to the input parameters of the prepared statement
				$stmt->bindParam(':custEmail', $cust_email);
				$stmt->bindParam(':custPref1', $cust_pref1_msg);		
				$stmt->bindParam(':custPref2', $cust_pref2_msg);
				$stmt->bindParam(':custPref3', $cust_pref3_msg);		
				$stmt->bindParam(':custPref4', $cust_pref4_msg);
				$stmt->bindParam(':custInputDate', $cust_input_date);
				
				
				//EXECUTE the prepared statement
				$stmt->execute();	
				
				$message = "Your event has been added.";
				
				//$conn->null;
			}
			
			catch(PDOException $e)
			{
				$message = "There has been a problem. The system administrator has been contacted. Please try again later.";
				
				error_log($e->getMessage());			//Delivers a developer defined error message to the PHP log file at c:\xampp/php\logs\php_error_log
				error_log(var_dump(debug_backtrace()));
				
				//Clean up any variables or connections that have been left hanging by this error.		
			
				//header('Location: files/505_error_response_page.php');	//sends control to a User friendly page					
			}
		}
		else
		{
			$message = "Something went wrong";
		}//end check for valid form
		
	}
	else
	{
		// Form has not been seen by the user. Display the form.
	}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>WDV341 Intro PHP - Project: Survey Tool</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script>
	function resetForm(){
		document.getElementById('cust_email').value = "";
		document.getElementById('cust_pref1').value = "default";
		document.getElementById('cust_pref2').value = "default";
		document.getElementById('cust_pref3').value = "default";
		document.getElementById('cust_pref4').value = "default";
	}
</script>

</head>

<body>
<h1>WDV341 Intro PHP</h1>
<h2>Project: Survey Tool</h2>
<?php
    //If the form was submitted and valid and properly put into database display the INSERT result message
	if($validForm)
	{
?>
		  <h1><?php echo $message?></h1>
		  
		  <!--Testing outputs>
		  <!--p>
		  Email:
		  <!--?php echo $cust_email ?><br>
		  Event Description:
		  <!--?php echo $cust_pref1 ?><br>
		  Event Presenter:
		  <!--?php echo $cust_pref2 ?><br>
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
		<form name="surveyForm" method="post" action="surveyForm.php">
		  <p>&nbsp;</p>
		  <p>
			<label>Email:
				<input type="text" name="cust_email" id="cust_email" value="<?php echo $cust_email ?>"><span class="error" style="color:red; padding-left:2em"><?php echo $cust_email_Err ?></span>
			</label>
		  </p>
		 <div id = "select_group">
		  <p>
			<label>Preference 1:
			  <select name="cust_pref1" id="cust_pref1">
				<option value="default">Please Select a Time</option>
				<option value="mw" <?php if(isset($_POST['cust_pref1']) && $_POST['cust_pref1'] == 'mw') echo 'selected="selected"';?> >Monday/Wednesday 10:00am-Noon</option>
				<option value="t" <?php if(isset($_POST['cust_pref1']) && $_POST['cust_pref1'] == 't') echo 'selected="selected"'; ?> >Tuesday 6:00-9:00pm</option>
				<option value="w" <?php if(isset($_POST['cust_pref1']) && $_POST['cust_pref1'] == 'w') echo 'selected="selected"'; ?> >Wednesday 6:00-9:00pm</option>
				<option value="tr" <?php if(isset($_POST['cust_pref1']) && $_POST['cust_pref1'] == 'tr') echo 'selected="selected"'; ?> >Tuesday/Thursday 10:10am-Noon</option>
			  </select><span class="_error" style="color:red; padding-left:2em"><?php echo $cust_pref1_Err; ?></span>
			</label>
		  </p>
		  <p>
			<label>Preference 2:
			  <select name="cust_pref2" id="cust_pref2">
				<option value="default">Please Select a Time</option>
				<option value="mw" <?php if(isset($_POST['cust_pref2']) && $_POST['cust_pref2'] == 'mw') echo 'selected="selected"';?> >Monday/Wednesday 10:00am-Noon</option>
				<option value="t" <?php if(isset($_POST['cust_pref2']) && $_POST['cust_pref2'] == 't') echo 'selected="selected"'; ?> 
				>Tuesday 6:00-9:00pm</option>
				<option value="w" <?php if(isset($_POST['cust_pref2']) && $_POST['cust_pref2'] == 'w') echo 'selected="selected"'; ?> 
				>Wednesday 6:00-9:00pm</option>
				<option value="tr" <?php if(isset($_POST['cust_pref2']) && $_POST['cust_pref2'] == 'tr') echo 'selected="selected"'; ?> >Tuesday/Thursday 10:10am-Noon</option>
			  </select><span class="_error" style="color:red; padding-left:2em"><?php echo $cust_pref2_Err; ?></span>
			</label>
		  </p>
		  <p>
			<label>Preference 3:
			  <select name="cust_pref3" id="cust_pref3">
				<option value="default">Please Select a Time</option>
				<option value="mw" <?php if(isset($_POST['cust_pref3']) && $_POST['cust_pref3'] == 'mw') echo 'selected="selected"';?> >Monday/Wednesday 10:00am-Noon</option>
				<option value="t" <?php if(isset($_POST['cust_pref3']) && $_POST['cust_pref3'] == 't') echo 'selected="selected"'; ?> >Tuesday 6:00-9:00pm</option>
				<option value="w" <?php if(isset($_POST['cust_pref3']) && $_POST['cust_pref3'] == 'w') echo 'selected="selected"'; ?> >Wednesday 6:00-9:00pm</option>
				<option value="tr" <?php if(isset($_POST['cust_pref3']) && $_POST['cust_pref3'] == 'tr') echo 'selected="selected"'; ?> >Tuesday/Thursday 10:10am-Noon</option>
			  </select><span class="_error" style="color:red; padding-left:2em"><?php echo $cust_pref3_Err; ?></span>
				<!--input type="text" name="cust_pref3" id="cust_pref3" value="<!--?php echo $cust_pref3 ?>"><span class="error" style="color:red; padding-left:2em"><!--?php echo $cust_pref3_Err ?></span-->
			</label>
		  </p>
		  <p>
			<label>Preference 4:
			  <select name="cust_pref4" id="cust_pref4">
				<option value="default">Please Select a Time</option>
				<option value="mw" <?php if(isset($_POST['cust_pref4']) && $_POST['cust_pref4'] == 'mw') echo 'selected="selected"';?> >Monday/Wednesday 10:00am-Noon</option>
				<option value="t" <?php if(isset($_POST['cust_pref4']) && $_POST['cust_pref4'] == 't') echo 'selected="selected"'; ?> >Tuesday 6:00-9:00pm</option>
				<option value="w" <?php if(isset($_POST['cust_pref4']) && $_POST['cust_pref4'] == 'w') echo 'selected="selected"'; ?> >Wednesday 6:00-9:00pm</option>
				<option value="tr" <?php if(isset($_POST['cust_pref4']) && $_POST['cust_pref4'] == 'tr') echo 'selected="selected"'; ?> >Tuesday/Thursday 10:10am-Noon</option>
			  </select><span class="_error" style="color:red; padding-left:2em"><?php echo $cust_pref4_Err; ?></span>
				<!--input type="text" name="cust_pref4" id="cust_pref4" value="<!--?php  echo $cust_pref4 ?>"><span class="error" style="color:red; padding-left:2em"><!--?php echo $cust_pref4_Err ?></span-->
			</label>
		  </p>
	     <div id="notification"></div> 
		</div>
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
