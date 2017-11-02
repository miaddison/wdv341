<?php
session_start();
if ($_SESSION['validUser'] == "yes")	//If this is a valid user allow access to this page
{
	$deleteRecId = $_GET['recId'];	//Pull the presenter_id from the GET parameter
	
	include 'dbConnect.php';		//connects to the database
	
	$sql = "DELETE FROM presenters WHERE presenter_id = ?";
	//echo "<p>The SQL Command: $sql </p>";     //testing
	
	$query = $connection->prepare($sql);	//prepare the statement
	
	$query->bind_param(i,$deleteRecId);	//bind the parameter to the statement
	
	if ( $query->execute() )			//process the query
	{
		$message =  "<h1>Your record has been successfully deleted.</h1>";
		$message .= "<p>Please <a href='presentersSelectView.php'>view</a> your records.</p>";	
	}
	else
	{
		$message = "<h1>You have encountered a problem with your delete.</h1>";
		$message .= "<h2 style='color:red'>" . mysqli_error($link) . "</h2>";
	}
	$query->close();
	$connection->close();	//close the database connection
}//end Valid User True
else
{
	//Invalid User attempting to access this page. Send person to Login Page
	header('Location: presentersLogin.php');
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WDV341 Intro PHP  - Presenters Admin Example</title>
</head>

<body>

<h1>WDV341 Intro PHP </h1>
<h2>Presenters Admin System Example</h2>
<h3>DELETE Record Page</h3>
<p>This page is called from the viewPresenters.php page when the user/customer clicks on the Delete link. This page will use the presenter_id that has been passed as a GET parameter on the URL to this page. </p>
<p>The SQL DELETE query will be created. Once the query is processed this page will confirm that it processed correctly. It will display a confirmation to the user/customer if it worked correctly or it will display an error message if there were problems.</p>
<p>Note: In a production environment this error message should be user/customer friendly. Additional information should be sent to the developer so that they can see 
what happened when they attempt to fix it. </p>

<h2>
	<?php echo $message; ?>
</h2>

</body>
</html>

