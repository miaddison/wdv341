
<!--
	Name: Merna Addison
	Email: merna.addison@gmail.com
	Date: 9/27/17
	Course: WDV341 Intro to PHP
-->
<?php
	
	include 'Email.php';
	
	$contactEmail = new Email("merna.addison@gmail.com");	//instantiate 

	$contactEmail->setRecipient("merna.addison@gmail.com");
	$contactEmail->setSender("myrnazarya@yahoo.com");
	$contactEmail->setSubject("Hello");
	$contactEmail->setMessage("Hello, I'm working on OOP in my PHP class.");
	$emailStatus = $contactEmail->sendMail(); // Create and send email

?>

<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>WDV341 Intro PHP</title>
</head>

<body>
	<h1>WDV341 Intro PHP</h1>
	<h2>PHP OOP Email Class Text</h2>
<?php
	if($emailStatus){
?>
	<h3>Thank you, it worked.</h3>
<?php
	}else{
?>
	<h3>Sorry! We are having problems.</h3>
<?php
	}
?>
	<p>Recipient Email Address: <?php echo $contactEmail->getRecipient(); ?></p>
	<p>Sender Email Address: <?php echo $contactEmail->getSender(); ?></p>
	<p>Email Subject: <?php echo $contactEmail->getSubject(); ?></p>
	<p>Email Message: <?php echo $contactEmail->getMessage(); ?></p>

</body>
</html>