<?php
session_start();
if ($_SESSION['validUser'] == "yes")	//If this is a valid user allow access to this page
{	
	if(isset($_POST["submit"]))
	{	
		//The form has been submitted and needs to be processed
		
		include 'dbConnect.php';	//connects to the database	
		
		//Validate the form data here!
	
		//Get the name value pairs from the $_POST variable into PHP variables
		//This example uses PHP variables with the same name as the name atribute from the HTML form
		$presenter_first_name = $_POST[presenter_first_name];
		$presenter_last_name = $_POST[presenter_last_name];
		$presenter_city = $_POST[presenter_city];
		$presenter_st = $_POST[presenter_st];
		$presenter_zip = $_POST[presenter_zip];
		$presenter_email = $_POST[presenter_email];
	
		//Create the SQL command string
		$sql = "INSERT INTO presenters (";
		$sql .= "presenter_first_name, ";
		$sql .= "presenter_last_name, ";
		$sql .= "presenter_city, ";
		$sql .= "presenter_st, ";
		$sql .= "presenter_zip, ";
		$sql .= "presenter_email ";	//Last column does NOT have a comma after it.
		$sql .= ") VALUES (?,?,?,?,?,?)";	//? Are placeholders for variables 
	
		//Display the SQL command to see if it correctly formatted.
		//echo "<p>$sql</p>";		
	
		$query = $connection->prepare($sql);	//Prepares the query statement 
				
		//Binds the parameters to the query.  
		//The ssssis are the data types of the variables in order.		
		$query->bind_param("ssssis",$presenter_first_name,$presenter_last_name,$presenter_city,$presenter_st,$presenter_zip,$presenter_email);
	
		//Run the SQL prepared statements
		if ( $query->execute() )
		{
			$message = "<h1>Your record has been successfully added to the database.</h1>";
			$message .= "<p>Please <a href='presentersSelectView.php'>view</a> your records.</p>";
		}
		else
		{
			$message = "<h1>You have encountered a problem.</h1>";
			$message .= "<h2 style='color:red'>" . mysqli_error($link) . "</h2>";	//remove this for production purposes
		}
	
		$query->close();
		$connection->close();	//closes the connection to the database once this page is complete.
		
	}// ends if submit 
	else
	{
		//Form has not been seen by the user.  display the form
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
<title>WDV341 Into PHP  - Presenters Admin Example</title>
</head>

<body>
<h1>WDV341 Presenters Admin System Example</h1>
<h3>Input Form for Adding Presenters</h3>

<?php
if(isset($_POST["submit"]))
{	
	//If the form was submitted display the INSERT result message
?>
	<h3><?php echo $message ?></h3>

<?php
}//end if
else
{
	//Display the Form.  The user will add a new record
?>

<p>This is the input form that allows the user/customer to enter the information for a Presenter. Once the form is submitted and validated it will call the addPresenters.php page. That page will pull the form data into the PHP and add a new record to the database.</p>
<form id="presentersForm" name="presentersForm" method="post" action="presentersInsertForm.php">
  <p>Add a new Presenter</p>
  <p>First Name: 
    <input type="text" name="presenter_first_name" id="presenter_first_name" />
  </p>
  <p>Last Name:  
    <input type="text" name="presenter_last_name" id="presenter_last_name" />
  </p>
  <p>City:  
    <input type="text" name="presenter_city" id="presenter_city" />
  </p>
  <p>State: 
    <input type="text" name="presenter_st" id="presenter_st" />
  </p>
  <p>Zip Code: 
    <input type="text" name="presenter_zip" id="presenter_zip" />
  </p>
  <p>Email Address: 
    <input type="text" name="presenter_email" id="presenter_email" />
  </p>
  <p>
    <input type="submit" name="submit" id="submit" value="Add Presenter" />
    <input type="reset" name="button2" id="button2" value="Clear Form" />
  </p>
</form>
<?php
}//end else
?>
<p>Return to <a href="presentersLogin.php">Administrator Options</a></p>
</body>
</html>
