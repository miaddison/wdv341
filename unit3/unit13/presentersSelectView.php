<?php
session_start();
if ($_SESSION['validUser'] == "yes")	//If this is a valid user allow access to this page
{	
	include 'dbConnect.php';			//connects to the database
	
	$sql = "SELECT presenter_id,presenter_first_name,presenter_last_name,presenter_city,presenter_st,presenter_zip,presenter_email FROM presenters";	// SQL command
	
	$query = $connection->prepare($sql) or die("<h1>Prepare Error</h1>");	//prepare the Statement Object
	
	//run the statement
	
	if( $query->execute() )	//Run Query and Make sure the Query ran correctly
	{
		$query->bind_result($presenter_id,$presenter_first_name,$presenter_last_name,$presenter_city,$presenter_st,$presenter_zip,$presenter_email);
	
		$query->store_result();
	}
	else
	{
		//Problems were encountered.
		$message = "<h1 style='color:red'>Houston, We have a problem!</h1>";	
		$message .= "mysqli_error($connection)";		//Display error message information	
		echo $message;
	}
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
<title>WDV341 Intro PHP  - Presenters Example</title>

</head>

<body>
<h1>WDV341 Intro PHP</h1>
<h2>Presenters Admin System Example</h2>
<h3>View Presenters</h3>
<p>This page will pull all of the presenters from the presenters table in the database. It will display all of the columns for each presenter as an HTML table. </p>
<p>Each presenter has an Update link. The update link will call a form page that uses PHP to fill out the form for the chosen presenter. Notice how the link passes the presenter_id as a GET parameter on the URL in the href attribute of the hyperlink element.</p>
<p>Each presenter has a Delete link. The delete link will call a php page that will delete the selected record from the table. Notice how the link passes the presenter_id as a GET parameter on the URL in the href attribute of the hyperlink element. </p>
<?php
	echo "<h3>" . $query->num_rows . " Presenters were found.</h3>";	//display number of rows found by query
?>
<div>
	<table border="1">
	<tr>
		<th>First Name</th>
		<th>Last Name</th>
		<th>City</th>
		<th>State</th>
		<th>Zip</th>
		<th>Email Address</th>
		<th>Update</th>
		<th>Delete</th>
	</tr>    
<?php
	//Display rows of data in table
	while( $query->fetch() )		
	//Turn each row of the result into an associative array 
  	{
		//For each row you have in the array create a table row
  		echo "<tr>";
  		echo "<td>$presenter_first_name</td>";
  		echo "<td>$presenter_last_name</td>";
  		echo "<td>$presenter_city</td>";
  		echo "<td>$presenter_st</td>";
  		echo "<td>$presenter_zip</td>";
  		echo "<td>$presenter_email</td>";
		echo "<td><a href='presentersUpdateForm.php?recId=$presenter_id'>Update</a></td>";
		echo "<td><a href='presentersDelete.php?recId=$presenter_id'>Delete</a></td>";
  		echo "</tr>\n";
  	}
	$query->close();
	$connection->close();	//Close the database connection	
?>
	</table>
</div>	
<p>Return to <a href="presentersLogin.php">Administrator Options</a></p>
</body>
</html>