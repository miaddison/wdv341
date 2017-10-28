<!--
	Name: Merna Addison
	Email: merna.addison@gmail.com
	Date: 10/15/17
	Course: WDV341 Intro to PHP
-->
<?php
//session_start();
//if ($_SESSION['validUser'] == "yes")	//If this is a valid user allow access to this page
//{
	//Setup variables used by the page	
		// field and email data
		$contact_name = "";
		$sanitized_contact_name = ""; //sanitized name
		$contact_email = "";
		$contact_reason = "";
		$contact_reason_msg = "";
		$contact_comments = "";
		$sanitized_contact_comments = ""; // sanitized comments
		$contact_newsletter = "";
		$contact_newsletter_msg = "";
		$contact_more_product = "";
		$contact_information_msg = "";
		$roboTest = "";
		$dateTime = new DateTime("now", new DateTimeZone('America/Chicago'));
		$contact_date = $dateTime->format('Y-m-d');
		$contact_time = $dateTime->format('H:i:s');
		$toEmail = "admin@mernaaddison.com";
		$subject = "Programming Project-PHP Contact Page with Validation";
		$emailBody = "";
		$headers = "";
		$emailMsg = "";
		$message = "";

		// __error messages
		$contact_name_err = "";
		$contact_email_err = "";
		$contact_reason_err = "";
		$contact_err = "";		
		$contact_comments_err = "";
		
		// flags for sucessful form submission and mail sent
		$validForm = false;
		$emailSent = false;

	if(isset($_POST["submit"]))
	{
		// the form has been sumitted and needs to be processed
	
		$contact_name = $_POST['name'];
		$contact_email = $_POST['email'];
		$contact_reason = $_POST['reason'];
		$contact_comments = $_POST['comments'];
		$contact_newsletter = $_POST['mailingList'];
		$contact_more_product = $_POST['information'];
		$roboTest = $_POST['robotest'];
		
		// Validation Functions
		// Validate name - required and check HTML special characters
		function validateName($contact_name){
			global $validForm, $contact_name_err, $sanitized_contact_name;	//Use the GLOBAL Version of these variables instead of making them local
			if($contact_name==""){
				$contact_name_err = "*Name is required";
				$validForm = false;
			}else{ 
				// Remove all illegal characters from name
				$sanitized_contact_name = filter_var($contact_name, FILTER_SANITIZE_STRING);
			}
		}
		
		//validate email - required and valid email format (use reg exp for format)
		function validateFromEmail($contact_email){
			global $validForm, $contact_email_err, $toEmail;	//Use the GLOBAL Version of these variables instead of making them local
			if(empty($contact_email)){
				$contact_email_err = "*Email is required";
				$validForm = false;
			}else if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$contact_email))		//Copied straight from W3Schools.  Uses a Regular Expression
  			{
				$contact_email = "";
  				$contact_email_err = "*Invalid email format"; 
				$validForm = false;
  			}
		}
		
		// Validate Reason - option requred if "other" then comments required
		function validateReason($contact_reason, $contact_comments){
			global $validForm, $contact_reason_err, $contact_comments_err, $contact_reason_msg; //Use the GLOBAL Version of these variables instead of making them local
			
			switch($contact_reason){
				case "default":
					$contact_reason_err = "*You must select an option";
					$validForm = false;
					break;
				case "other":
					$contact_reason_msg = "Other";
					if($contact_comments == ""){
						$contact_comments_err = "*You must enter a comments if you selected \"Other\"";
						$validForm = false;
					}
					break;
				case "product":
					$contact_reason_msg = "Product Problem";
					break;
				case "return":
					$contact_reason_msg = "Return a Product";
					break;
				case "billing":
					$contact_reason_msg = "Billing Question";
					break;
				case "technical":
					$contact_reason_msg = "Report a Website Problem";
					break;
			}
		}
		
		// Validate Comments - only required if reason=="other" check for HTML special chars
		function validateComments($contact_comments){
			global $validForm, $contact_comments_err, $sanitized_contact_comments; //Use the GLOBAL Version of these variables instead of making them local
			// Remove all illegal characters from comments
			$sanitized_contact_comments = filter_var($contact_comments, FILTER_SANITIZE_STRING);
		}
		
		// format output for checkboxes
		function checkBoxOutput($checkbox){
			if($checkbox){
				return "Yes";
			}else{
				return "No";
			}
		}
		
		// find bool for checkboxes
		function checkBoxBool($checkbox){
			if($checkbox){
				return true;
			}else{
				return false;
			}
		}
		
		// validate not robot
		if($roboTest){
			$message = "*No bots allowed! Email not sent!";
			$validForm = false;
		}
		
		// Set form valid and run methods to check if anything is invalid
		$validForm = true;
		validateName($contact_name);
		validateFromEmail($contact_email);
		validateReason($contact_reason, $contact_comments);
		validateComments($contact_comments);
		
		// Run methods for checkboxes to format output
		$contact_newsletter_msg = checkBoxOutput($contact_newsletter);
		$contact_information_msg = checkBoxOutput($contact_more_product);
		
		// Run methods so checkboxes are changed to booleans
		$contact_newsletter = checkBoxBool($contact_newsletter);
		$contact_more_products = checkBoxBool($contact_more_product);
		
		$emailBody = '<h1>The following information was successfully submitted:</h1>';
        $emailBody .= '<p>Date Submitted: ' . $dateTime->format('m-d-Y').'</p>';
        $emailBody .= '<p>Time Submitted: '. $dateTime->format('g:i a T').'</p>';
        $emailBody .= '<p>Your Name: '. $sanitized_contact_name.'</p>';
        $emailBody .= '<p>Your Email: '. $contact_email.'</p>';
        $emailBody .= '<p>Reason for Contact: '. $contact_reason_msg.'</p>';
        $emailBody .= '<p>Comments: '. $sanitized_contact_comments.'</p>';
        $emailBody .= '<p>Signed up for Mailing List: '. $contact_newsletter_msg.'</p>';
        $emailBody .= '<p>Requested more information on Products: '. $contact_information_msg.'</p>';
		

		$headers = "From: $toEmail" . "\r\n";
		$headers .= "Cc: $contact_email" . "\r\n";
		$headers .= "Content-type: text/html\r\n";
		
		if($validForm){
			//send email if everything on form is valid
			if(mail($toEmail, $subject, $emailBody, $headers)){
				$emailMsg = "Email successfully sent!";
				$emailSent = true;
				$message = "The following information was successfully sent via email. ";
			}else{
				$emailMsg = "Email delivery failed...";
				$message = "Your message is not able to be submitted via email at this time. ";
			}
			try 
			{
				require 'connectPDO.php'; // connect to the database
				
				//Create the SQL command string
				$sql = "INSERT INTO wdv341.wdv_341_customer_contacts(";
				//$sql = "INSERT INTO miaddison_wdv.wdv341_customer_contacts(";
				$sql .= "contact_name, ";
				$sql .= " contact_email, ";
				$sql .= " contact_reason, ";
				$sql .= " contact_comments, ";
				$sql .= " contact_newsletter, ";
				$sql .= " contact_more_products, ";
				$sql .= " contact_date, ";
				$sql .= " contact_time"; //Last column does NOT have a comma after it.
				$sql .= ") VALUES (:contactName, :contactEmail, :contactReason, :contactComments, :contactNewsletter, :contactMoreProducts, :contactDate, :contactTime)";
				
				//PREPARE the SQL statement
				$stmt = $conn->prepare($sql);
				
				//if(!$stmt){
				//	echo"\nPDO::errorInfo():\n";
				//	print_r($conn->errorInfo());
				//}
				
				//BIND the values to the input parameters of the prepared statement
				$stmt->bindParam(':contactName', $sanitized_contact_name);
				$stmt->bindParam(':contactEmail', $contact_email);		
				$stmt->bindParam(':contactReason', $contact_reason);		
				$stmt->bindParam(':contactComments', $sanitized_contact_comments);
				$stmt->bindParam(':contactNewsletter', $contact_newsletter);
				$stmt->bindParam(':contactMoreProducts', $contact_more_products);
				$stmt->bindParam(':contactDate', $contact_date);	
				$stmt->bindParam(':contactTime', $contact_time);	
				
				//EXECUTE the prepared statement
				$stmt->execute();	
				
				$message .= "Your event has been added to the database.";
				
				//$conn->null;
			}
			
			catch(PDOException $e)
			{
				$message .= "There has been a problem adding the event to the database. The system administrator has been contacted. Please try again later.";
				
				error_log($e->getMessage());			//Delivers a developer defined error message to the PHP log file at c:\xampp/php\logs\php_error_log
				error_log(var_dump(debug_backtrace()));
				
				//Clean up any variables or connections that have been left hanging by this error.		
			
				//header('Location: files/505_error_response_page.php');	//sends control to a User friendly page					
			}
		}
		else
		{
			$message = "Something went wrong";
		}//end check for valid form*/
		
		
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
        <p>Your Name: <?php echo htmlspecialchars($sanitized_contact_name);?></p>
        <p>Your Email: <?php echo htmlspecialchars($contact_email);?></p>
        <p>Reason for Contact: <?php echo $contact_reason_msg;?></p>
        <p>Comments: <?php echo htmlspecialchars($sanitized_contact_comments);?></p>
        <p>Signed up for Mailing List: <?php echo $contact_newsletter_msg;?></p>
        <p>Requested more information on Products: <?php echo $contact_information_msg;?></p>
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
				<input type="text" name="contact_name" id="contact_name" value="<?php echo $contact_name ?>"><span class="_error" style="color:red; padding-left:2em"><?php echo $contact_name_err ?></span>
			</label>
		  </p>
		  <p>Your Email: 
			<input type="text" name="contact_email" id="contact_email" value="<?php echo $contact_email  ?>"><span class="_error" style="color:red; padding-left:2em"><?php echo $contact_email_err ?></span>
		  </p>
		  <p>Reason for contact: 
			<label>
			  <select name="contact_reason" id="contact_reason">
				<option value="default">Please Select a Reason</option>
				<option value="product" <?php if(isset($_POST['reason']) && $_POST['reason'] == 'product') echo 'selected="selected"';?> >Product Problem</option>
				<option value="return" <?php if(isset($_POST['reason']) && $_POST['reason'] == 'return') echo 'selected="selected"'; ?> >Return a Product</option>
				<option value="billing" <?php if(isset($_POST['reason']) && $_POST['reason'] == 'billing') echo 'selected="selected"'; ?> >Billing Question</option>
				<option value="technical" <?php if(isset($_POST['reason']) && $_POST['reason'] == 'technical') echo 'selected="selected"'; ?> >Report a Website Problem</option>
				<option value="other" <?php if(isset($_POST['reason']) && $_POST['reason'] == 'other') echo 'selected="selected"'; ?> >Other</option>
			  </select><span class="_error" style="color:red; padding-left:2em"><?php echo $contact_reason_err; ?></span>
			</label>
		  </p>
		  <p>
			<label>Comments:
			  <textarea name="contact_comments" id="contact_comments" cols="45" rows="5"><?php echo $contact_comments;  ?></textarea><span class="_error" style="color:red; padding-left:2em"><?php echo $contact_comments_err ?></span>
			</label>
		  </p>
		  <p>
			<label>
			  <input type="checkbox" name="contact_newletter" id="contact_newletter" checked>
			  Please put me on your mailing list.</label>
		  </p>
		  <p>
			<label>
			  <input type="checkbox" name="contact_more_product" id="contact_more_product" checked>
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
