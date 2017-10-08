<?php
session_start();
//if ($_SESSION['validUser'] == "yes")	//If this is a valid user allow access to this page
//{
		
	//Setup the variables used by the page
		//field data
		$first_name = "";
		$last_name = "";
		$robotest = "";
		$subject = "WDV341 Group Project";	
		$fromEmail = "njotoole@dmacc.edu";		
		$toEmail = "admin@mernaaddison.com, njotoole@dmacc.edu";		
		$emailBody = "";			
		$headers = "";				
 		
			
	
		//error messages
		$firstNameErrMsg = "";
		$lastNameErrMsg = "";
		
		
		$validForm = false;
				
	if(isset($_POST["submit"]))
	{	
		//The form has been submitted and needs to be processed
		
		
		//Validate the form data here!
	
		//Get the name value pairs from the $_POST variable into PHP variables
		//This example uses PHP variables with the same name as the name atribute from the HTML form
		$first_name = $_POST['inFirstName'];
		$last_name = $_POST['inLastName'];
		$robotest = $_POST['inRobotest'];

		
		// Email form
		$emailBody = "Form Data\n\n ";			
		foreach($_POST as $key => $value)									
		{
			$emailBody.= $key."=".$value."\n";	
		} 

		$headers = "From: $fromEmail" . "\r\n";				
 		
		
		//VALIDATION FUNCTIONS		Use functions to contain the code for the field validations.  
			function validateFirstName($inName)
			{
				global $validForm, $firstNameErrMsg;		//Use the GLOBAL Version of these variables instead of making them local
				$firstNameErrMsg = "";
				
				if($inName == "")
				{
					$validForm = false;
					$firstNameErrMsg = "First name cannot be spaces";
				}
			}//end validateName()

			function validateLastName($inName)
			{
				global $validForm, $lastNameErrMsg;		//Use the GLOBAL Version of these variables instead of making them local
				$lastNameErrMsg = "";
				
				if($inName == "")
				{
					$validForm = false;
					$lastNameErrMsg = "Last name cannot be spaces";
				}
			}//end validateName()			
		
		//VALIDATE FORM DATA  using functions defined above
		$validForm = true;		//switch for keeping track of any form validation errors
		
		validateFirstName($first_name);
		validateLastName($last_name);
		
		if($validForm)
		{
			//$message = "All good";	
			$message = "";
			
			// Send email if form is valid
			if (mail($toEmail,$subject,$emailBody,$headers)) 	
			{
   				$emailMessage = "Message successfully sent!";
  			} 
			else 
			{
   				$emailMessage = "Message delivery failed...";
  			}
		}
		else
		{
			$message = "Something went wrong";
		}
		
		// validate not robot
		if($robotest)
		{
			$message = "No bots allowed!";
		}
		
	}
	else
	{
		//Form has not been seen by the user.  display the form
	}
	


?><!DOCTYPE html>
<html >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WDV341 Intro PHP - Group Project</title>
<style>

#orderArea	{
	width:600px;
	background-color:lightblue;
}

.error	{
	color:red;
	font-style:italic;	
}
.robotic{
	display: none;
}
</style>

</head>

<body>
<h1>WDV341 Intro PHP</h1>
<h2>Group Project
</h2>
<p style="color: red;" > <?php echo($message);?> </p>
<p id="emailMsg"><?php echo($emailMessage);?> </p>
<div id="orderArea">
  <form id="form1" name="form1" method="post" action="GroupProject.php">
  <h3>Registration Form</h3>
  <table width="587" border="0">
    <tr>
      <td width="117">First Name:</td>
      <td width="246"><input type="text" name="inFirstName" id="inFirstName" size="40" value=""/></td>
		<td width="210" class="error" style="color:red;"><?php echo $firstNameErrMsg; ?></td>
    </tr>
    <tr>
      <td width="117">Last Name:</td>
      <td width="246"><input type="text" name="inLastName" id="inLastName" size="40" value=""/></td>
		<td width="210" class="error" style="color:red;"><?php echo $lastNameErrMsg; ?></td>
    </tr>
	<tr>
      <!-- The following field is for robots only, invisible to humans: -->
    <p class="robotic" id="pot">
      <label>If you're human leave this blank:</label>
      <input name="inRobotest" type="text" id="inRobotest" class="inRobotest" />
    </p>
    </tr>
  </table>
  <p>
    <input type="submit" name="submit" id="button" value="Register" />
    <input type="reset" name="reset" id="reset" value="Clear Form"/>
  </p>
  
</form>
</div>

</body>
</html>