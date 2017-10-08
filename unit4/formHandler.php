<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WDV341 Intro to PHP Contact Form with Email</title>
<style type="text/css">
.colorRed {
	color: #F00;
}
</style>
</head>

<body>
<h1>WDV341 Intro to PHP Contact Form with Email</h1>
<h2>Contact Form with Email</h2>
<hr />
<h3>Format  and display the form information.</h3>
<p>This process will process the 'name = value' pairs for all the elements of a form. It will format  the form information into HTML.  It will then display the name value pairs from your form on the response page created by this PHP page.</p>
<p>This page was called by the action attribute of your form on the contactForm.html page. It called the formHandler.php to process the name values from your form. </p>
<p><strong>name</strong> - The value of the name attribute from the HTML form element.</p>
<p><strong>value</strong> - The value entered in the field. This will vary depending upon the HTML form element.</p>

<p>RESULT WILL DISPLAY BELOW THIS LINE</p>
<hr />
<?php

	echo "<p class='colorRed'>This page was created by the PHP formHandler.php page on the server and sent back to your browser.</p>";

//It will create a table and display one set of name value pairs per row
	echo "<table border='1'>";
	echo "<tr><th>Field Name</th><th>Value of field</th></tr>";
	foreach($_POST as $key => $value)
	{
		echo '<tr class=colorRow>';
		echo '<td>',$key,'</td>';
		echo '<td>',$value,'</td>';
		echo "</tr>";
	} 
	echo "</table>";
	echo "<p>&nbsp;</p>";
?>
<h3>Send confirmation email to the Contact Email Address.</h3>
<p>This page will send confirmation email to the Contact Email Address. This confirms to the customer that your application has received the customer's information and will respond to it.</p>
<p>The page will also send an email to the page owner with the form information.  In this case you are acting as the point of contact for the client.  </p>
	<p>A MESSAGE CONFIRMING DELIVERY SUCCESS OR FAILURE WILL DISPLAY BELOW THIS LINE.</p>
<hr />
<?php
//This code pulls the field name and value attributes from the Post file
//The Post file was created by the form page when it gathered all the name value pairs from the form.
//It is building a string of data that will become the body of the email

//          CHANGE THE FOLLOWING INFORMATION TO SEND EMAIL FOR YOU //  

			
	
	$subject = "WDV341 Intro to PHP Contact Form with Email";	//CHANGE within the quotes. Place your own message.  

	$fromEmail = $_POST['email'];		//CHANGE within the quotes.  Use your DMACC email address for testing OR
										//use your domain email address if you have Heartland-Webhosting as your provider.
										//Example:  $fromEmail = "contact@jhgullion.org";  
	$toEmail = "admin@mernaaddison.com, $fromEmail";		//CHANGE within the quotes. Place email address where you wish to send the form data. 
										//Use your DMACC email address for testing. 
										//Example: $toEmail = "jhgullion@dmacc.edu";

//   DO NOT CHANGE THE FOLLOWING LINES  //

	$emailBody = "Form Data\n\n ";			//stores the content of the email
	foreach($_POST as $key => $value)		//Reads through all the name-value pairs. 	$key: field name   $value: value from the form									
	{
		$emailBody.= $key."=".$value."\n";	//Adds the name value pairs to the body of the email, each one on their own line
	} 
	
	$headers = "From: $fromEmail" . "\r\n";				//Creates the From header with the appropriate address

 	if (mail($toEmail,$subject,$emailBody,$headers)) 	//puts pieces together and sends the email to your hosting account's smtp (email) server
	{
   		echo("<p>Message successfully sent!</p>");
  	} 
	else 
	{
   		echo("<p>Message delivery failed...</p>");
  	}

?>

</body>
</html>
