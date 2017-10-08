<!--
	Name: Merna Addison
	Email: merna.addison@gmail.com
	Date: 10/7/17
	Course: WDV341 Intro to PHP
-->
<?php
	$name = $_POST['name'];
	$fromEmail = $_POST['email'];
	$reason = $_POST['reason'];
	$comments = $_POST['comments'];
	$mailingList = $_POST['mailingList'];
	$information = $_POST['information'];
	$roboTest = $_POST['robotest'];
	$toEmail = "admin@mernaaddison.com, $fromEmail";
	$subject = "PROGRAMMING PROJECT: Contact Form with Email";
	$emailBody = "";
	$headers = "";
	$emailStatus = "";
	$message = "";
	
	
		// Email form
		//$emailBody = "Form Data\n\n ";			
		//foreach($_POST as $key => $value){
		//	$emailBody.= $key."=".$value."   \n";	
		//} 
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

		$headers = "From: $fromEmail" . "\r\n";
		$headers .= "Content-type: text/html\r\n";
			
		// validate not robot
		if($roboTest)
		{
			$message = "No bots allowed! Email not sent!";
		}else{
			// Send email 
			if (mail($toEmail,$subject,$emailBody,$headers)){
   				$emailStatus = "Message successfully sent!";
  			} 
			else{
   				$emailStatus = "Message delivery failed...";
  			}
		}
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>WDV341 Intro PHP - Programming Project - Contact Form</title>
</head>

<body>
	<h1>WDV341 Intro PHP</h1>
	<h2>Programming Project - Contact Form</h2>
<?php
	echo $emailStatus;
?><br><?php 
	echo $message;
?>
	<p>Recipient Email Address: <?php echo $fromEmail; ?></p>
	<p>Sender Email Address: <?php echo $toEmail; ?></p>
	<p>Email Subject: <?php echo $subject; ?></p>
	<p>Email Message: 
		<?php //It will create a table and display one set of name value pairs per row
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
		echo "<p>&nbsp;</p>";?>
</body>
</html>