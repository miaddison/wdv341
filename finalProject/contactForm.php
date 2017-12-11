<!--
	Name: Merna Addison
	Email: merna.addison@gmail.com
	Date: 10/15/17
	Course: WDV341 Intro to PHP
-->
<?php
session_start();
		
		$userMessage = "";

		if (isset($_SESSION['validUser']) && $_SESSION['validUser'] == "yes")				//is this already a valid user?
		{
			//User is already signed on.  Skip the rest.
			$username = $_SESSION['username'];
			$userMessage = "Welcome Back $username!";	//Create greeting for VIEW area		
		}
	//Setup variables used by the page	
		// field and email data
		$name = "";
		$sanName = ""; //sanitized name
		$fromEmail = "";
		$comments = "";
		$sanComments = ""; // sanitized comments
		$roboTest = "";
		$dateTime = new DateTime("now", new DateTimeZone('America/Chicago'));
		$toEmail = "admin@mernaaddison.com";
		$subject = "Menu Planner - Contact Us";
		$emailBody = "";
		$headers = "";
		$emailMsg = "";
		$message = "";

		// Error messages
		$nameErr = "";
		$fromEmailErr = "";
		$commentsErr = "";
		
		// flags for sucessful form submission and mail sent
		$validForm = false;
		$emailSent = false;

	if(isset($_POST["submit"]))
	{
		// the form has been sumitted and needs to be processed
	
		$name = $_POST['name'];
		$fromEmail = $_POST['email'];
		$comments = $_POST['comments'];
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
		
		// Validate Comments - only required if reason=="other" check for HTML special chars
		function validateComments($comments){
			global $validForm, $commentsErr, $sanComments; //Use the GLOBAL Version of these variables instead of making them local
			// Remove all illegal characters from comments
			$sanComments = filter_var($comments, FILTER_SANITIZE_STRING);
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
		validateComments($comments);
			
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
        $emailBody .= '<p>Comments: '. $sanComments.'</p>';
     
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
<title>Menu Planner - Contact Us</title>
<script>
	function resetForm(){
		document.getElementById('name').value = "";
		document.getElementById('email').value = "";
		document.getElementById('comments').value = "";
	}
</script>	
<link href= "style.css" rel= "stylesheet" type= "text/css"/>
</head>

<body>
<div id = "container">
<div id = "login">
	<a href = "adminSelectMeals.php"><?php echo $userMessage; ?></a>
	<a href = "login.php"><img src="images/login.png" alt="Login" style="width:25px;hieght:25px;"></a>
	<a href = "logout.php"><img src="images/logout.png" alt="Logout" style="width:25px;hieght:25px;"></a>
</div>
<header>
	<h1>Meal Planner</h1>
</header>
<nav>
	<ul>
		<li><a href = "index.php">Home</a></li>
		<li><a href = "mealPick.php">Meal Planner</a></li>
		<li><a href = "selectMeals.php">View All Recipes</a></li>
		<li><a href = "contactForm.php">Contact Us</a></li>
	</ul>
</nav>
<main>
<h2>Contact Us</h2>
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
        <p>Comments: <?php echo htmlspecialchars($sanComments);?></p>
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
		  <p>
			<label>Comments:
			  <textarea name="comments" id="comments" cols="45" rows="5"><?php echo $comments;  ?></textarea><span class="error" style="color:red; padding-left:2em"><?php echo $commentsErr ?></span>
			</label>
		  </p>
		  <p>
			<input type="hidden" name="robotest" id="robotest">
		  </p>
		  <p>
		  	<input type="button" name="button2" id="button2" value="Reset" 
			 onClick = resetForm()>
			<input type="submit" name="submit" id="submit" value="Submit">
		  </p>
		</form>
<?php
	} // end else
?>

<p>&nbsp;</p>
</main>
<footer>
	<p>&copy; 2017 Merna Addison</p>
</footer>
</body>
</html>
