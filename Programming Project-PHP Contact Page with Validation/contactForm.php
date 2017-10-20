<!--
	Name: Merna Addison
	Email: merna.addison@gmail.com
	Date: 10/15/17
	Course: WDV341 Intro to PHP
-->
<?php
session_start();
//if ($_SESSION['validUser'] == "yes")	//If this is a valid user allow access to this page
//{
	//Setup variables used by the page	
		// field and email data
		$name = "";
		$sanName = ""; //sanitized name
		$fromEmail = "";
		$reason = "";
		$reasonMsg = "";
		$comments = "";
		$sanComments = ""; // sanitized comments
		$mailingList = "";
		$mailListMsg = "";
		$information = "";
		$infoMsg = "";
		$roboTest = "";
		$dateTime = new DateTime("now", new DateTimeZone('America/Chicago'));
		$toEmail = "admin@mernaaddison.com";
		$subject = "Programming Project-PHP Contact Page with Validation";
		$emailBody = "";
		$headers = "";
		$emailMsg = "";
		$message = "";

		// Error messages
		$nameErr = "";
		$fromEmailErr = "";
		$reasonErr = "";
		$contactErr = "";		
		$commentsErr = "";
		
		// flags for sucessful form submission and mail sent
		$validForm = false;
		$emailSent = false;

	if(isset($_POST["submit"]))
	{
		// the form has been sumitted and needs to be processed
	
		$name = $_POST['name'];
		$fromEmail = $_POST['email'];
		$reason = $_POST['reason'];
		$comments = $_POST['comments'];
		$mailingList = $_POST['mailingList'];
		$information = $_POST['information'];
		$roboTest = $_POST['robotest'];
		
		// Validation Functions
		// Validate name - required and check HTML special characters
		function validateName($name){
			global $validForm, $nameErr, $sanName;	//Use the GLOBAL Version of these variables instead of making them local
			if($name==""){
				$nameErr = "*Name is required";
				$validForm = false;
			}else{ 
				// Remove all illegal characters from name
				$sanName = filter_var($name, FILTER_SANITIZE_STRING);
			}
		}
		
		//validate email - required and valid email format (use reg exp for format)
		function validateFromEmail($fromEmail){
			global $validForm, $fromEmailErr, $toEmail;	//Use the GLOBAL Version of these variables instead of making them local
			if(empty($fromEmail)){
				$fromEmailErr = "*Email is required";
				$validForm = false;
			}else if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$fromEmail))		//Copied straight from W3Schools.  Uses a Regular Expression
  			{
				$fromEmail = "";
  				$fromEmailErr = "*Invalid email format"; 
				$validForm = false;
  			}
		}
		
		// Validate Reason - option requred if "other" then comments required
		function validateReason($reason, $comments){
			global $validForm, $reasonErr, $commentsErr, $reasonMsg; //Use the GLOBAL Version of these variables instead of making them local
			
			switch($reason){
				case "default":
					$reasonErr = "*You must select an option";
					$validForm = false;
					break;
				case "other":
					$reasonMsg = "Other";
					if($comments == ""){
						$commentsErr = "*You must enter a comments if you selected \"Other\"";
						$validForm = false;
					}
					break;
				case "product":
					$reasonMsg = "Product Problem";
					break;
				case "return":
					$reasonMsg = "Return a Product";
					break;
				case "billing":
					$reasonMsg = "Billing Question";
					break;
				case "technical":
					$reasonMsg = "Report a Website Problem";
					break;
			}
		}
		
		// Validate Comments - only required if reason=="other" check for HTML special chars
		function validateComments($comments){
			global $validForm, $commentsErr, $sanComments; //Use the GLOBAL Version of these variables instead of making them local
			// Remove all illegal characters from comments
			$sanComments = filter_var($comments, FILTER_SANITIZE_STRING);
		}
		
		// format output for checkboxes
		function checkBoxOutput($checkbox){
			if($checkbox){
				return "Yes";
			}else{
				return "No";
			}
		}
		
		
		// validate not robot
		if($roboTest){
			$message = "*No bots allowed! Email not sent!";
			$validForm = false;
		}else{
			// Send email 
			/*if (mail($toEmail,$subject,$emailBody,$headers)){
   				$emailStatus = "Message successfully sent!";
  			} 
			else{
   				$emailStatus = "Message delivery failed...";
  			}*/
		}
		
		// Set form valid and run methods to check if anything is invalid
		$validForm = true;
		validateName($name);
		validateFromEmail($fromEmail);
		validateReason($reason, $comments);
		validateComments($comments);
		
		// Run methods for checkboxes to format output
		$mailListMsg = checkBoxOutput($mailingList);
		$infoMsg = checkBoxOutput($information);
			
			
		// Email form
		/*$emailBody = "Form Data\n\n ";			
		foreach($_POST as $key => $value){
			$emailBody.= $key."=".$value."   \n";	
		} 
		$emailBody = "<table border='1'>";
		$emailBody .= "<tr><th>Field Name</th><th>Value of field</th></tr>";
		foreach($_POST as $key => $value)
		{
			$emailBody .= '<tr class=colorRow>';
			$emailBody .= '<td>'.$key.'</td>';
			$emailBody .= '<td>'.$value.'</td>';
			$emailBody .= "</tr>";
		} 
		$emailBody .= "</table>";
		$emailBody .= "<p>&nbsp;</p>";
		*/
		
		$emailBody = '<h1>The following information was successfully submitted:</h1>';
        $emailBody .= '<p>Date Submitted: ' . $dateTime->format('m-d-Y').'</p>';
        $emailBody .= '<p>Time Submitted: '. $dateTime->format('g:i a T').'</p>';
        $emailBody .= '<p>Your Name: '. $sanName.'</p>';
        $emailBody .= '<p>Your Email: '. $fromEmail.'</p>';
        $emailBody .= '<p>Reason for Contact: '. $reasonMsg.'</p>';
        $emailBody .= '<p>Comments: '. $sanComments.'</p>';
        $emailBody .= '<p>Signed up for Mailing List: '. $mailListMsg.'</p>';
        $emailBody .= '<p>Requested more information on Products: '. $infoMsg.'</p>';
		

		$headers = "From: $toEmail" . "\r\n";
		$headers .= "Cc: $fromEmail" . "\r\n";
		$headers .= "Content-type: text/html\r\n";
		
		if($validForm){
			//send email if everything on form is valid
			if(mail($toEmail, $subject, $emailBody, $headers)){
				$emailMsg = "Email successfully sent!";
				$emailSent = true;
				$message = "The following information was successfully sent";
			}else{
				$emailMsg = "Email delivery failed...";
				$message = "Your message is not able to be submitted at this time.";
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
		document.getElementById('name').value = "";
		document.getElementById('email').value = "";
		document.getElementById('reason').value = "default";
		document.getElementById('comments').value = "";
	}
</script>	
</head>

<body>
<h1>WDV341 Intro PHP</h1>
<h2>Programming Project: PHP Contact Page with Validation</h2>
<?php
    //If the form was submitted and valid and properly put into database display the INSERT result message
	if($validForm && $emailSent)
	{
?>
        <h1><?php echo $message?></h1>
        <p>Date Submitted: <?php echo $dateTime->format('m-d-Y'); ?></p>
        <p>Time Submitted: <?php echo $dateTime->format('g:i a T'); ?></p>
        <p>Your Name: <?php echo htmlspecialchars($sanName);?></p>
        <p>Your Email: <?php echo htmlspecialchars($fromEmail);?></p>
        <p>Reason for Contact: <?php echo $reasonMsg;?></p>
        <p>Comments: <?php echo htmlspecialchars($sanComments);?></p>
        <p>Signed up for Mailing List: <?php echo $mailListMsg;?></p>
        <p>Requested more information on Products: <?php echo $infoMsg;?></p>
<?php
	}
	else if($validForm && !$emailSent)
	{
?>      
		  <h1><?php echo $message?></h1>
		  <p><?php echo $emailMsg?></p>
		  
<?php	
	}
	else	//display form
	{
?>
		<h2><?php echo $message?></h2>
		<form name="contactForm" method="post" action="contactForm.php">
		  <p>&nbsp;</p>
		  <p>
			<label>Your Name:
				<input type="text" name="name" id="name" value="<?php if(isset($_POST['name'])){echo $_POST['name'];} ?>"><span class="error" style="color:red; padding-left:2em"><?php echo $nameErr ?></span>
			</label>
		  </p>
		  <p>Your Email: 
			<input type="text" name="email" id="email" value="<?php if(isset($_POST['email'])){echo $_POST['email'];}  ?>"><span class="error" style="color:red; padding-left:2em"><?php echo $fromEmailErr ?></span>
		  </p>
		  <p>Reason for contact: 
			<label>
			  <select name="reason" id="reason">
				<option value="default">Please Select a Reason</option>
				<option value="product" <?php if(isset($_POST['reason']) && $_POST['reason'] == 'product') echo 'selected="selected"';?> >Product Problem</option>
				<option value="return" <?php if(isset($_POST['reason']) && $_POST['reason'] == 'return') echo 'selected="selected"'; ?> >Return a Product</option>
				<option value="billing" <?php if(isset($_POST['reason']) && $_POST['reason'] == 'billing') echo 'selected="selected"'; ?> >Billing Question</option>
				<option value="technical" <?php if(isset($_POST['reason']) && $_POST['reason'] == 'technical') echo 'selected="selected"'; ?> >Report a Website Problem</option>
				<option value="other" <?php if(isset($_POST['reason']) && $_POST['reason'] == 'other') echo 'selected="selected"'; ?> >Other</option>
			  </select><span class="error" style="color:red; padding-left:2em"><?php echo $reasonErr; ?></span>
			</label>
		  </p>
		  <p>
			<label>Comments:
			  <textarea name="comments" id="comments" cols="45" rows="5"><?php echo $comments;  ?></textarea><span class="error" style="color:red; padding-left:2em"><?php echo $commentsErr ?></span>
			</label>
		  </p>
		  <p>
			<label>
			  <input type="checkbox" name="mailingList" id="mailingList" checked>
			  Please put me on your mailing list.</label>
		  </p>
		  <p>
			<label>
			  <input type="checkbox" name="information" id="information" checked>
			  Send me more information</label>
		  about your products.  </p>
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
