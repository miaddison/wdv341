<?php
	include 'Email.php';
	
	$contactEmail = new Email("");
	$contactEmail->setRecipient("miaddison@dmacc.edu");
	$contactEmail->setSender("fromHere@gmail.com");
	$contactEmail->setSubject("Hello World!");
	$contactEmail->setMessage("Welcome to OOP PHP. This is the message body. We will make the text pretty long so that we can test the wordwrap and see if it works as expected.");
	$emailStatus = $contactEmail->sendMail(); // create and send email

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>

<h3>
/*<?php
	if($emailStatus){
		echo "Thank you, it worked."
	}else{
		echo "Sorry! We are having problems."
	}
?>*/
// or
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
</h3>
</body>
</html>